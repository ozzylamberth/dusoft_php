<?php

/**
 * $Id: app_CentroAutorizacion_userclasses_HTML.php,v 1.3 2009/11/10 14:56:39 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo visual de las autorizaciones.
 */

/**
 * Contiene los metodos visuales para realizar las autorizaciones.
 */
class app_CentroAutorizacion_userclasses_HTML extends app_CentroAutorizacion_user {

    /**
     * Constructor de la clase app_CentroAutorizacion_user_HTML
     * El constructor de la clase app_CentroAutorizacion_user_HTML se encarga de llamar
     * a la clase app_CentroAutorizacion_user quien se encarga de el tratamiento
     * de la base de datos.
     */
    function app_CentroAutorizacion_user_HTML() {
        $this->salida = '';
        $this->app_CentroAutorizacion_user();
        return true;
    }

    function SetStyle($campo) {
        if ($this->frmError[$campo] || $campo == "MensajeError") {
            if ($campo == "MensajeError") {
                return ("<tr><td class='label_error' colspan='3' align='center'>" . $this->frmError["MensajeError"] . "</td></tr>");
            }
            return ("label_error");
        }
        return ("label");
    }

    /**
     *
     */
    function ComboJustificacion() {
        $this->salida .= "<SCRIPT>\n";
        $this->salida .= "function ComboJustificacion(valor,forma){\n";
        $this->salida .= "  if(valor!=-1){;\n";
        $this->salida .= "     forma.Observaciones.value=valor;\n";
        $this->salida .= "  }\n";
        $this->salida .= "}\n";
        $this->salida .= "</SCRIPT>\n";
    }

    /**
     * Forma del menu de admisiones
     * @access private
     * @return boolean
     */
    function FormaMenus() {
        $_SESSION['CENTROAUTORIZACION']['EMPRESA'] = $_REQUEST['permiso']['empresa_id'];
        $_SESSION['CENTROAUTORIZACION']['CENTROUTILIDAD'] = $_REQUEST['permiso']['centro_utilidad'];
        unset($_SESSION['CENTROAUTORIZACION']['TODO']);
        $this->salida .= ThemeAbrirTabla('MENUS CENTRO AUTORIZACION');
        $this->salida .= "            <br>";
        $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU CENTRO AUTORIZACION</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        if (empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            $accionC = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarBuscar');
        } else {
            $accionC = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'FormaBuscarTodos');
        }
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionC\">Solicitudes Por Autorizar</a></td>";
        $this->salida .= "               </tr>";
        /* if(empty($_SESSION['CENTROAUTORIZACION']['TODOS']))
          { */
        $this->salida .= "               <tr>";
        $accionA = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarBuscarOS');
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionA\">Listado De OS</a></td>";
        $this->salida .= "               </tr>";
        //}
        $this->salida .= "           </table>";
        if (empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'main');
        } else {
            if($_SESSION['CENTROAUTORIZACION']['TODOS']){
                $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'MenuDos');
            }else{
                $accion = ModuloGetURL('system', 'Menu', 'user', 'main');
            }
        }
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function Encabezado() {
        $datos = $this->DatosEncabezado();
        $this->salida .= "<table  border=\"0\" class=\"modulo_table_list\" width=\"70%\" align=\"center\" >";
        $this->salida .= " <tr class=\"modulo_table_title\">";
        $this->salida .= " <td>EMPRESA</td>";
        $this->salida .= " <td>RESPONSABLE</td>";
        $this->salida .= " <td>PLAN</td>";
        $this->salida .= " </tr>";
        $this->salida .= " <tr align=\"center\">";
        $this->salida .= " <td class=\"modulo_list_claro\" >" . $datos[razon_social] . "</td>";
        $this->salida .= " <td class=\"modulo_list_claro\" >" . $_SESSION['CENTROAUTORIZACION']['RESPONSABLE'] . "</td>";
        $this->salida .= " <td class=\"modulo_list_claro\">" . $datos[plan_descripcion] . "</td>";
        $this->salida .= " </tr>";
        $this->salida .= " </table><br>";
    }

    /**
     *
     */
    function FormaMetodoBuscar($arr) {
        $this->salida.= ThemeAbrirTabla('BUSCAR SOLICITUDES');
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'Buscar');
        $this->salida .= "<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA: -21- </td>";
        $this->salida .= "</tr>";
        $this->salida .= "<tr class=\"modulo_list_claro\" >";
        $this->salida .= "<td width=\"40%\" >";
        $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr><td>";
        $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
        $tipo_id = $this->tipo_id_paciente();
        $this->BuscarIdPaciente($tipo_id, '');
        $this->salida .= "</select></td></tr>";
        $this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
        $this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\"></td></tr>";
        $this->salida .= "<tr><td class=\"label\">No. SOLICITUD: </td><td><input type=\"text\" class=\"input-text\" name=\"Solicitud\" maxlength=\"32\"></td></tr>";
        $this->salida .= "                <tr><td class=\"label\">TIPO SOLICITUD: </td><td><select name=\"Tipo\" class=\"select\">";
        $tipo = $this->TiposSolicitud();
        $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
        for ($i = 0; $i < sizeof($tipo); $i++) {
            if ($tipo[$i][os_tipo_solicitud_id] == $_REQUEST[Tipo]) {
                $this->salida .=" <option value=\"" . $tipo[$i][os_tipo_solicitud_id] . "\" selected>" . $tipo[$i][descripcion] . "</option>";
            } else {
                $this->salida .=" <option value=\"" . $tipo[$i][os_tipo_solicitud_id] . "\">" . $tipo[$i][descripcion] . "</option>";
            }
        }
        $this->salida .= "                  </select></td></tr>";
        $this->salida .= "                <tr><td class=\"label\">TIPO SERVICIO: </td><td><select name=\"Servicio\" class=\"select\">";
        $tipo = $this->TiposServicios();
        $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
        for ($i = 0; $i < sizeof($tipo); $i++) {
            if ($tipo[$i][servicio] == $_REQUEST[Servicio]) {
                $this->salida .=" <option value=\"" . $tipo[$i][servicio] . "\" selected>" . $tipo[$i][descripcion] . "</option>";
            } else {
                $this->salida .=" <option value=\"" . $tipo[$i][servicio] . "\">" . $tipo[$i][descripcion] . "</option>";
            }
        }
        $this->salida .= "                  </select></td></tr>";
        $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
        $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
        $this->salida .= "</form>";
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'TiposPlanes');
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table></td></tr>";
        $this->salida .= "</td></tr></table>";
        $this->salida .= "</td>";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "<td>";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= "       </td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>";
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        if (!empty($arr)) {
            $d = 0;
            $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td width=\"25%\">IDENTIFICACION</td>";
            $this->salida .= "        <td width=\"45%\">PACIENTE</td>";
            $this->salida .= "        <td width=\"50%\">PROCESO AUTORIZACION</td>";
            $this->salida .= "        <td width=\"10%\"></td>";
            $this->salida .= "      </tr>";
            for ($i = $d; $i < sizeof($arr); $i++) {
                if ($i % 2) {
                    $estilo = "modulo_list_claro";
                } else {
                    $estilo = "modulo_list_oscuro";
                }
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td>" . $arr[$i][tipo_id_paciente] . " " . $arr[$i][paciente_id] . "</td>";
                $this->salida .= "        <td>" . $arr[$i][nombres] . "" . $arr[$i][evolucion_id] . "</td>";
                if ($arr[$i][usuario_id] != NULL) {
                    $this->salida .= "        <td align=\"center\" class=\"label_error\">\n";
                    $this->salida .= "          <b class=\"label_error\">EN PROCESO</b> - USUARIO: " . $arr[$i]['nombre_usuario'] . "\n";
                    $this->salida .= "        </td>\n";
                } else {
                    $this->salida .= "        <td align=\"center\"></td>";
                }
                if ($arr[$i][usuario_id] == NULL) {
                    $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleSolicitud', array('tipoid' => $arr[$i][tipo_id_paciente], 'paciente' => $arr[$i][paciente_id], 'nombre' => $arr[$i][nombres]));
                    $this->salida .= "        <td align=\"center\"><a href=\"$accion\">VER</a></td>";
                } else {
                    $this->salida .= "        <td align=\"center\"></td>";
                }
                $this->salida .= "      </tr>";
            }
            $this->salida .= " </table>";
            $this->conteo = $_SESSION['SPY2'];
            $this->salida .=$this->RetornarBarrad();
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
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

    function RetornarBarrad() {
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
        if (empty($vec)) {
            $vec = array();
        }
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'Buscar', $vec);
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
     *
     */
    function Todos() {
        $this->salida .= "<SCRIPT>";
        $this->salida .= "function Todos(frm,x){";
        $this->salida .= "  if(x==true){";
        $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
        $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
        $this->salida .= "        frm.elements[i].checked=true";
        $this->salida .= "      }";
        $this->salida .= "    }";
        $this->salida .= "  }else{";
        $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
        $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
        $this->salida .= "        frm.elements[i].checked=false";
        $this->salida .= "      }";
        $this->salida .= "    }";
        $this->salida .= "  }";
        $this->salida .= "}";
        $this->salida .= "</SCRIPT>";
    }

    /**
     *
     */
    function FormaDetalle() {
        $reporte = new GetReports();
        $arr = $_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE'];
        $arr2 = $_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE2'];
        $this->salida .= ThemeAbrirTabla('DETALLE SOLICITUDES');
        $this->Encabezado();
        $this->Todos();
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        $this->salida .= "     <table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" >";
        $this->salida .= "      <tr align=\"center\">";
        $this->salida .= "        <td colspan=\"8\" align=\"center\">";
        $this->salida .= "     <table width=\"70%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\" colspan=\"6\" align=\"left\">DATOS PACIENTE </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION: -21-</td><td width=\"20%\" class=\"modulo_list_claro\">" . $_SESSION['CENTROAUTORIZACION']['tipo_id_paciente'] . " " . $_SESSION['CENTROAUTORIZACION']['paciente_id'] . "</td>";
        $this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">PACIENTE:</td><td width=\"40%\" class=\"modulo_list_claro\" colspan=\"3\">" . $_SESSION['CENTROAUTORIZACION']['nombre_paciente'] . "</td>";
        //$this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">INGRESO:</td><td width=\"60%\" class=\"modulo_list_claro\">".$arr[0][ingreso]."</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "       </table>";
        $this->salida .= "        </td>";
        $this->salida .= "      </tr>";
        //links bd
        $p = $this->ClasificarPlan($_SESSION['CENTROAUTORIZACION']['PLAN']);
        if ($p[sw_afiliacion] == 1) {
            $bd = $this->DatosBD($_SESSION['CENTROAUTORIZACION']['tipo_id_paciente'], $_SESSION['CENTROAUTORIZACION']['paciente_id'], $_SESSION['CENTROAUTORIZACION']['PLAN']);
            if (!empty($bd)) {
                $this->salida .= "      <tr><td colspan=\"8\">";
                $this->SetJavaScripts('DatosBD');
                $this->SetJavaScripts('DatosBDAnteriores');
                $this->SetJavaScripts('DatosEvolucionInactiva');
                $this->salida .= "<br><table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"50%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "  <tr class=\"modulo_list_claro\">";
                $this->salida .= "   <td align=\"center\">" . RetornarWinOpenDatosBD($_SESSION['CENTROAUTORIZACION']['tipo_id_paciente'], $_SESSION['CENTROAUTORIZACION']['paciente_id'], $_SESSION['CENTROAUTORIZACION']['PLAN']) . "</td>";
                $x = $this->CantidadMeses($_SESSION['CENTROAUTORIZACION']['PLAN']);
                if ($x > 1) {
                    $this->salida .= "   <td align=\"center\">" . RetornarWinOpenDatosBDAnteriores($_SESSION['CENTROAUTORIZACION']['tipo_id_paciente'], $_SESSION['CENTROAUTORIZACION']['paciente_id'], $_SESSION['CENTROAUTORIZACION']['PLAN'], $x) . "</td>";
                }
                $this->salida .= "  </tr>";
                $this->salida .= "</table>";
                $sw = $this->BuscarSwHc();
                if (!empty($sw)) {
                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso'] = $arr[0][ingreso];
                    $dat = $this->BuscarEvolucion();
                    if ($dat) {
                        $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"30%\" align=\"center\" class=\"normal_10\">";
                        $this->salida .= "  <tr class=\"modulo_list_claro\">";
                        $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'] = 'CentroAutorizacion';
                        $_SESSION['HISTORIACLINICA']['RETORNO']['metodo'] = 'FormaDetalle';
                        $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'] = 'user';
                        $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'] = 'app';
                        $accion = ModuloHCGetURL($dat, '', '', '', '');
                        $this->salida .= "   <td align=\"center\"><a href=\"$accion\">HISTORIA CLINICA</a></td>";
                        $this->salida .= "  </tr>";
                        $this->salida .= "</table><BR>";
                    }
                }
                $this->salida .= "      </td></tr>";
            }
        }
        //fin links bd      
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'PedirAutorizacion', array('ingreso' => $arr[0][ingreso]));
        for ($i = 0; $i < sizeof($arr);) {
            $f = 0;
            $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $d = $i;
            if ($arr[$i][servicio] == $arr[$d][servicio]) {
                $this->salida .= "      <tr><td colspan=\"8\"><br></td></tr>";
                $this->salida .= "      <tr><td colspan=\"8\" class=\"modulo_table_list_title\">PLAN:" . $arr[$i][plan_descripcion] . "</td></tr>";
                $this->salida .= "      <tr>";
                $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"12%\">SERVICIO: </td>";
                $this->salida .= "        <td class=\"modulo_list_claro\" width=\"13%\" colspan=\"2\">" . $arr[$i][desserv] . "</td>";
                $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"11%\">DEPARTAMENTO: </td>";
                $this->salida .= "        <td class=\"modulo_list_claro\" align=\"left\" colspan=\"4\">" . $arr[$i][despto] . "</td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                $this->salida .= "        <td>FECHA</td>";
                $this->salida .= "        <td width=\"10%\">SOLICITUD</td>";
                $this->salida .= "        <td width=\"10%\">CARGO</td>";
                $this->salida .= "        <td colspan=\"2\" width=\"40%\">DESCRIPCION</td>";
                $this->salida .= "        <td width=\"7%\">CANTIDAD</td>";
                $this->salida .= "        <td width=\"10%\">TIPO</td>";
                $this->salida .= "        <td width=\"10%\"></td>";
                $this->salida .= "      </tr>";
            }
            while ($arr[$i][servicio] == $arr[$d][servicio]) {
                if ($d % 2) {
                    $estilo = "modulo_list_claro";
                } else {
                    $estilo = "modulo_list_oscuro";
                }
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td>" . $this->FechaStamp($arr[$d][fecha]) . " " . $this->HoraStamp($arr[$d][fecha]) . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$d][hc_os_solicitud_id] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$d][cargos] . "</td>";
                $this->salida .= "        <td colspan=\"2\">" . $arr[$d][descripcion] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$d][cantidad] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$d][desos] . "</td>";
                $equi = $this->ValidarEquivalencias($arr[$d][cargos]);
                $cont = $this->ValidarContrato($arr[$d][cargos], $arr[$d][plan_id]);
                if ($arr[$d][nivel_autorizador_id] < $arr[$d][x]) {
                    $this->salida .= "        <td align=\"center\" width=\"7%\">Necesita Nivel " . $arr[$d][x] . "";
                }
                //elseif($equi>=1 AND $equi==$cont
                elseif ($equi >= 1 AND $cont > 0
                        AND $arr[$d][nivel_autorizador_id] >= $arr[$d][nivel]) {
                    $s = '';
                    $de = $this->ComboDepartamento($arr[$d][cargos], $arr[$d][hc_os_solicitud_id]);
                    if (empty($de)) {
                        $p = $this->ComboProveedor($arr[$d][cargos]);
                        if (empty($p)) {
                            $s = 'NO PROVEEDOR <BR>';
                        }
                    }
                    /* if(empty($arr[$d][departamento])
                      AND empty($arr[$d][tipo_id_tercero]))
                      {  $s='NO PROVEEDOR <BR>';  } */
                    $this->salida .= "        <td align=\"center\" class=\"label_error\">$s<input type=\"checkbox\" value=\"" . $arr[$d][cargos] . "," . $arr[$d][tarifario_id] . "," . $arr[$d][ingreso] . "," . $arr[$d][servicio] . "," . $arr[$d][hc_os_solicitud_id] . "," . $arr[$d][cargos] . "\" name=\"Auto" . $arr[$d][hc_os_solicitud_id] . "\">";
                    $f++;
                } elseif ($cont == 0) {
                    $this->salida .= "        <td align=\"center\" class=\"label_error\" width=\"7%\">NO ESTA CONTRATADO";
                } elseif ($equi == 0) {
                    $this->salida .= "        <td align=\"center\" class=\"label_error\" width=\"7%\">NO TIENE EQUIVALENCIAS";
                }
                $accionhref = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'FormaAnularSolicitud', array('solicitud' => $arr[$d][hc_os_solicitud_id], 'descripcion' => $arr[$d][descripcion]));
                $this->salida .= "<a class=\"label_mark\" href=\"$accionhref\" class=\"label_mark\"><BR>ANULAR</a>";
                $this->salida .= "      </td>";
                $this->salida .= "      </tr>";
                $d++;
            }
            $i = $d;
            if ($f == 0) {
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td class=\"label_error\" align=\"center\" colspan=\"8\">NINGUN CARGO PUEDE SER AUTORIZADO</td>";
                $this->salida .= "      </tr>";
            }
            if ($f > 0) {
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td align=\"right\" colspan=\"8\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"AUTORIZAR\"></td>";
                $this->salida .= "      </tr>";
            }
            $this->salida .= "                       </form>";
        }
        $this->salida .= "      <tr><td colspan=\"7\"><br></td></tr>";
        if (!empty($arr2)) {
            $this->salida .= "      <tr><td colspan=\"6\" class=\"modulo_table_title\" align=\"center\">SOLICITUDES EN OTROS PLANES</td></tr>";
        }
        for ($i = 0; $i < sizeof($arr2);) {
            $f = 0;
            $d = $i;
            if ($arr2[$i][servicio] == $arr2[$d][servicio]
                    AND $arr2[$i][plan_id] == $arr2[$d][plan_id]) {
                $this->salida .= "      <tr><td colspan=\"6\"></td></tr>";
                $this->salida .= "      <tr><td colspan=\"6\" class=\"modulo_table_list_title\">PLAN:" . $arr2[$i][plan_descripcion] . "</td></tr>";
                $this->salida .= "      <tr>";
                $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"12%\">SERVICIO: </td>";
                $this->salida .= "        <td class=\"modulo_list_claro\" width=\"13%\">" . $arr2[$i][desserv] . "</td>";
                $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"11%\">DEPARTAMENTO: </td>";
                $this->salida .= "        <td class=\"modulo_list_claro\" width=\"45%\" align=\"left\" colspan=\"3\">" . $arr2[$i][despto] . "</td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                $this->salida .= "        <td>FECHA</td>";
                $this->salida .= "        <td>CARGO</td>";
                $this->salida .= "        <td colspan=\"2\" width=\"40%\">DESCRIPCION</td>";
                $this->salida .= "        <td>TIPO</td>";
                $this->salida .= "        <td width=\"11%\">PLAN</td>";
                //$this->salida .= "        <td></td>";
                $this->salida .= "      </tr>";
            }
            while ($arr2[$i][servicio] == $arr2[$d][servicio] AND $arr2[$i][plan_id] == $arr2[$d][plan_id]) {
                if ($d % 2) {
                    $estilo = "modulo_list_claro";
                } else {
                    $estilo = "modulo_list_oscuro";
                }
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td>" . $this->FechaStamp($arr2[$i][fecha]) . " " . $this->HoraStamp($arr2[$i][fecha]) . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr2[$d][cargos] . "</td>";
                $this->salida .= "        <td colspan=\"2\">" . $arr2[$d][descar] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr2[$d][desos] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr2[$d][plan_descripcion] . "</td>";
                $this->salida .= "      </tr>";
                $d++;
            }
            $i = $d;
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleSolicitud', array('tipoid' => $_SESSION['CENTROAUTORIZACION']['tipo_id_paciente'], 'paciente' => $_SESSION['CENTROAUTORIZACION']['paciente_id'], 'nombre' => $_SESSION['CENTROAUTORIZACION']['nombre_paciente'], 'plan' => $arr2[0][plan_id]));
            $this->salida .= "      <tr class=\"$estilo\">";
            $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "        <td align=\"right\" colspan=\"6\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VER\"></td>";
            $this->salida .= "      </tr>";
            $this->salida .= "                       </form>";
        }
        $this->salida .= " </table><br>";
        if (!empty($_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE3'])) {
            $this->ListadoOsAuto('FormaDetalle', &$reporte);
        }
        if (!empty($_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE4'])) {
            $this->ListadoOsNoAuto('FormaDetalle', &$reporte);
        }
        $this->salida .= "     <table width=\"50%\" border=\"0\" align=\"center\">";
        $this->salida .= "               <tr>";
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarBuscar');
        $this->salida .= "             <form name=\"forma1\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "                       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></td>";
        $this->salida .= "                       </form>";
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'main');
        $this->salida .= "             <form name=\"forma2\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "                       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></td>";
        $this->salida .= "                       </form>";
        $this->salida .= "               </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function ListadoOsAuto($regreso, $reporte) {
        $var = $_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE3'];
        if (!empty($var)) {
            $this->salida .= ThemeAbrirTabla('ORDENES SERVICIO AUTORIZADAS', 850);
            for ($i = 0; $i < sizeof($var);) {
                $d = $i;


                $this->salida .= "  <table width=\"95%\" border=\"1\" align=\"center\" >";
                $this->salida .= "      <tr class=\"modulo_table_title\">";
                $this->salida .= "        <td colspan=\"5\" align=\"left\">NÚMERO DE ORDEN DE SERVICIO " . $var[$i][orden_servicio_id] . "</td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr>";
                $this->salida .= "        <td colspan=\"5\" class=\"modulo_list_claro\">";
                $this->salida .= "            <table width=\"100%\" border=\"1\" align=\"center\" class=\"\">";
                $this->salida .= "                <tr>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">TIPO AFILIADO: </td>";
                $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][tipo_afiliado_nombre] . "</td>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">RANGO: </td>";
                $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][rango] . "</td>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">SEMANAS COT.: </td>";
                $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][semanas_cotizadas] . "</td>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">SERVICIO: </td>";
                $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][desserv] . "</td>";
                $this->salida .= "                </tr>";
                $this->salida .= "                <tr>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">AUT. INT.: </td>";
                $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][autorizacion_int] . "</td>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">AUT. EXT: </td>";
                $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][autorizacion_ext] . "</td>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">AUTORIZADOR: </td>";
                $this->salida .= "                    <td width=\"5%\" colspan=\"3\" class=\"hc_table_submodulo_list_title\">" . $var[$d][autorizador] . "</td>";
                $this->salida .= "                </tr>";
                $this->salida .= "                <tr>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">PLAN: </td>";
                $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\" colspan=\"7\" align=\"left\">" . $var[$d][plan_descripcion] . "</td>";
                $this->salida .= "                </tr>";
                $this->salida .= "             </table>";
                $this->salida .= "        </td>";
                $this->salida .= "      </tr>";
                while ($var[$i][orden_servicio_id] == $var[$d][orden_servicio_id]) {

                    $tipsol = "";
                    if (!empty($var[$d][hc_os_solicitud_id])) {
                        $hc_os_solicitud_idser = $var[$d][hc_os_solicitud_id];
                    } else {
                        $id_ordser = $this->ObtenerHcOsId($var[$d][numero_orden_id]);
                        $hc_os_solicitud_idser = $id_ordser[0][hc_os_solicitud_id];
                    }

                    $obser = $this->ObtenerTipoSolicitud($hc_os_solicitud_idser);
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
                        $obser = $this->ObtenerObservacionSolicitud($hc_os_solicitud_idser, $tabla);
                        if (count($obser) > 0)
                            $cadobse = $obser[0][observacion];
                    }

                    $this->salida .= "      <tr>";
                    $this->salida .= "        <td colspan=\"5\">";
                    $this->salida .= "        <table width=\"99%\" border=\"0\" align=\"center\">";
                    $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "        <td width=\"6%\">ITEM </td>";
                    $this->salida .= "        <td width=\"6%\">CANT.</td>";
                    $this->salida .= "        <td width=\"10%\">CARGO</td>";
                    $this->salida .= "        <td width=\"45%\">DESCRICPION</td>";
                    $this->salida .= "        <td width=\"20%\">PROVEEDOR</td>";
                    $this->salida .= "      </tr>";
                    if ($d % 2) {
                        $estilo = "modulo_list_claro";
                    } else {
                        $estilo = "modulo_list_oscuro";
                    }
                    $this->salida .= "      <tr class=\"$estilo\">";
                    $this->salida .= "        <td align=\"center\">" . $var[$d][numero_orden_id] . "</td>";
                    $this->salida .= "        <td align=\"center\">" . $var[$d][cantidad] . "</td>";
                    /* if(!empty($var[$d][cargo])){  $cargo=$var[$d][cargo];  }
                      else {  $cargo=$var[$d][cargoext];   } */
                    $cargo = $var[$d][cargo_cups];
                    $this->salida .= "        <td align=\"center\">" . $cargo . "</td>";
                    $this->salida .= "        <td>" . $var[$d][descripcion] . " " . $var[$d][desc_especialidad] . "</td>";
                    $p = '';
                    $pro = $this->BuscarProveedorOrden($var[$d][numero_orden_id]);
                    if (!empty($pro[departamento])) {
                        $p = 'DPTO. ' . $pro[desdpto];
                        $id = $pro[departamento];
                    } else {
                        $p = $pro[planpro];
                        $id = $pro[plan_proveedor_id];
                    }
                    $bdDA = $this->TraerPlanD($hc_os_solicitud_idser);
                    $tarifario_iddea="";
                    $tarifario_dedea="";
                    $subtarifario_iddea = "";
                    $subtarifario_dedea = "";
                    if (count($bdDA) > 0){
                        $tarifario_iddea = $bdDA['grupo_tarifario_id'];
                        $tarifario_dedea = $bdDA['grupo_tarifario_descripcion'];
                        $subtarifario_iddea = $bdDA['subgrupo_tarifario_id'];
                        $subtarifario_dedea = $bdDA['subgrupo_tarifario_descripcion'];
                    }
                    
                    $this->salida .= "        <td align=\"center\">" . $p . "</td>";
                    $this->salida .= "      </tr>";
                    $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
                    $this->salida .= "        <td colspan=\"5\">";
                    $this->salida .= "            <table width=\"100%\" border=\"0\" align=\"center\">";

                    $this->salida .= "                <tr class=\"$estilo\">";
                    $this->salida .= "                    <td colspan=\"4\" width=\"5%\" class=\"modulo_table_list_title\">CÓDIGO TARIFARIO </td>";
                    $this->salida .= "                    <td colspan=\"4\" width=\"5%\" class=\"modulo_table_list_title\">DESCRIPCION TARIFARIO </td>";
                    $this->salida .= "                </tr>";
                    $this->salida .= "                <tr class=\"$estilo\">";
                    $this->salida .= "                    <td  colspan=\"4\" >" . $tarifario_iddea . "</td>";
                    $this->salida .= "                    <td  colspan=\"4\" >" . $tarifario_dedea . "</td>";
                    $this->salida .= "                </tr>";

                    $this->salida .= "                <tr class=\"$estilo\">";
                    $this->salida .= "                    <td colspan=\"4\" width=\"5%\" class=\"modulo_table_list_title\">CÓDIGO SUBGRUPO TARIFARIO </td>";
                    $this->salida .= "                    <td colspan=\"4\" width=\"5%\" class=\"modulo_table_list_title\">DESCRIPCION SUBGRUPO TARIFARIO </td>";
                    $this->salida .= "                </tr>";
                    $this->salida .= "                <tr class=\"$estilo\">";
                    $this->salida .= "                    <td  colspan=\"4\" >" . $subtarifario_iddea . "</td>";
                    $this->salida .= "                    <td  colspan=\"4\" >" . $subtarifario_dedea . "</td>";
                    $this->salida .= "                </tr>";
                    
                    $this->salida .= "                <tr class=\"modulo_list_claro\">";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">OBSERVACIÓN: </td>";
                    $this->salida .= "                    <td width=\"5%\" colspan=\"7\" class=\"hc_table_submodulo_list_title\" align=\"left\">" . $cadobse . "</td>";
                    $this->salida .= "                </tr>";
                    $this->salida .= "                <tr class=\"modulo_list_claro\">";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">ACTIVACION: </td>";
                    $this->salida .= "                    <td width=\"5%\" colspan=\"2\">" . $this->FechaStamp($var[$d][fecha_activacion]) . "</td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">VENC.: </td>";
                    $x = '';
                    $vecimiento = $var[$d][fecha_vencimiento];
                    $arr_fecha = explode(" ", $vecimiento);
                    if (strtotime(date("Y-m-d")) > strtotime($arr_fecha[0]))
                        $x = 'VENCIDA';
                    if (strtotime(date("Y-m-d")) == strtotime($arr_fecha[0]))
                        $x = '';
                    $this->salida .= "                    <td width=\"5%\" >" . $this->FechaStamp($var[$d][fecha_vencimiento]) . "</td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"label_error\" align=\"center\">" . $x . "</td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">REFRENDAR HASTA: </td>";
                    $this->salida .= "                    <td width=\"5%\">" . $this->FechaStamp($var[$d][fecha_refrendar]) . "</td>";
                    $this->salida .= "                </tr>";
                    $this->salida .= "             </table>";
                    $this->salida .= "    <table width=\"100%\" border=\"0\" align=\"center\">";
                    $this->salida .= "      <tr class=\"modulo_list_claro\" align=\"center\">";
                    $this->salida .= "                    <td width=\"7%\" class=\"modulo_table_list_title\">ESTADO: </td>";
                    $this->salida .= "                    <td width=\"9%\" class=\"hc_table_submodulo_list_title\" colspan=\"2\">" . $var[$d][estado] . "</td>";
                    $this->salida .= "        <td width=\"20%\"></td>";
                    //$accion=ModuloGetURL('app','CentroAutorizacion','user','ReporteOrdenServicio',array('regreso'=>$regreso,'orden'=>$var[$d][orden_servicio_id],'plan'=>$var[$d][plan_id],'tipoid'=>$var[$d][tipo_id_paciente],'paciente'=>$var[$d][paciente_id],'afiliado'=>$var[$d][tipo_afiliado_id]));
                    $this->salida .= "        <td width=\"10%\"></td>";
                    $this->salida .= "      </tr>";
                    $this->salida .= "       </table>";
                    $this->salida .= "        </td>";
                    $this->salida .= "      </tr>";
                    $this->salida .= "       </table>";
                    $this->salida .= "        </td>";
                    $this->salida .= "      </tr>";
                    $d++;
                }
                $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'ReporteOrdenServicio', array('regreso' => $regreso, 'orden' => $var[$d - 1][orden_servicio_id], 'plan' => $var[$d - 1][plan_id], 'tipoid' => $var[$d - 1][tipo_id_paciente], 'paciente' => $var[$d - 1][paciente_id], 'afiliado' => $var[$d - 1][tipo_afiliado_id], 'pos' => 1));
                if ($x != 'VENCIDA' AND ($var[$d - 1][estado] == 'PAGADO' OR $var[$d - 1][estado] == 'ACTIVO' OR $var[$d - 1][estado] == 'TRASCRIPCION')) {
                    $this->salida .= "      <tr class=\"modulo_list_claro\">";
                    $this->salida .= "        <td align=\"center\"><a href=\"$accion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";
                    $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'ReporteOrdenServicio', array('regreso' => $regreso, 'orden' => $var[$d - 1][orden_servicio_id], 'pos' => 0, 'plan' => $var[$d - 1][plan_id], 'tipoid' => $var[$d - 1][tipo_id_paciente], 'paciente' => $var[$d - 1][paciente_id], 'afiliado' => $var[$d - 1][tipo_afiliado_id]));
                    $this->salida .= "                <td align=\"center\" width=\"27%\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'>&nbsp;<a href=\"$accion\"> IMPRIMIR MEDIA CARTA</a></td>";
                    $mostrar = $reporte->GetJavaReport('app', 'CentralImpresionHospitalizacion', 'ordenservicioHTM', array('orden' => $var[$d - 1][orden_servicio_id]), array('rpt_name' => 'orden', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                    $funcion = $reporte->GetJavaFunction();
                    $this->salida .=$mostrar;
                    $this->salida.="  				 <td align=\"center\" width=\"33%\"><a href=\"javascript:$funcion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'> IMPRIMIR</a></td>";
                    $this->salida .= "      </tr>";
                }
                $i = $d;
                $this->salida .= "       </table><br>";
            }//fin for
            $this->salida .= ThemeCerrarTabla();
        }
    }

    function ListadoOsNoAuto($regreso, $reporte) {
        $arr = $_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE4'];
        if (!empty($arr)) {
            $this->salida .= ThemeAbrirTabla('ORDENES SERVICIO NO AUTORIZADAS', 850);
            $this->salida .= "  <table width=\"95%\" border=\"1\" align=\"center\" >";
            $this->salida .= "      <tr class=\"modulo_table_title\"><td colspan=\"8\" align=\"center\">SOLICITUDES</td></tr>";
            $this->salida .= "      <tr class=\"modulo_table_list_title\">";
            $this->salida .= "        <td width=\"10%\">FECHA</td>";
            $this->salida .= "        <td width=\"10%\">CARGO</td>";
            $this->salida .= "        <td colspan=\"2\" width=\"50%\">DESCRIPCION</td>";
            $this->salida .= "        <td width=\"10%\">TIPO</td>";
            $this->salida .= "        <td width=\"11%\">PLAN</td>";
            $this->salida .= "        <td width=\"10%\"></td>";
            $this->salida .= "        <td width=\"10%\"></td>";
            $this->salida .= "      </tr>";
            for ($d = 0; $d < sizeof($arr); $d++) {
                if ($d % 2) {
                    $estilo = "modulo_list_claro";
                } else {
                    $estilo = "modulo_list_oscuro";
                }
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td>" . $this->FechaStamp($arr[$d][fecha]) . " " . $this->HoraStamp($arr[$d][fecha]) . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$d][cargos] . "</td>";
                $this->salida .= "        <td colspan=\"2\">" . $arr[$d][descar] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$d][desos] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$d][plan_descripcion] . "</td>";
                $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'ReporteSolicitudesNoAuto', array('regreso' => $regreso, 'datos' => $arr[$d], 'tipoid' => $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'], 'paciente' => $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'], 'solicitud' => $arr[$d][hc_os_solicitud_id], 'pos' => 1));
                $this->salida .= "        <td align=\"center\" width=\"7%\"><a href=\"$accion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";
                //$reporte= new GetReports();
                $mostrar = $reporte->GetJavaReport('app', 'CentralImpresionHospitalizacion', 'solicitudesnoautorizadasHTM', array('TipoDocumento' => $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'], 'Documento' => $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'], 'solicitud' => $arr[$d][hc_os_solicitud_id]), array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                $funcion = $reporte->GetJavaFunction();
                $this->salida .=$mostrar;
                $this->salida.="  				 <td align=\"center\"><a href=\"javascript:$funcion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'> IMPRIMIR</a></td>";
                //$accion1=ModuloGetURL('app','CentroAutorizacion','user','ReporteSolicitudesNoAuto',array('regreso'=>$regreso,'datos'=>$arr[$d],'tipoid'=>$arr[$d][tipo_id_paciente],'paciente'=>$arr[$d][paciente_id],'solicitud'=>$arr[$d][hc_os_solicitud_id],'pos'=>0));
                //$this->salida.="  				 <td align=\"center\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";
                $this->salida .= "      </tr>";
            }
            $this->salida .= " </table>";
            $this->salida .= ThemeCerrarTabla();
        }
    }

//-----------------------------------------------------------------------------------

    /**
     *
     */
    function FormaListadoCargos($arr) {
        IncludeLib("tarifario_cargos");
        $this->salida .= ThemeAbrirTabla('CARGOS ORDENES SERVICIO');
        if (empty($_SESSION['CENTROAUTORIZACION']['TODO'])) {
            $this->Encabezado();
        }
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        $this->salida .= "     <table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr>";
        if (empty($_SESSION['CENTROAUTORIZACION']['TODO'])) {
            $this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\">IDENTIFICACION: </td><td width=\"20%\" class=\"modulo_list_claro\">" . $_SESSION['CENTROAUTORIZACION']['tipo_id_paciente'] . " " . $_SESSION['CENTROAUTORIZACION']['paciente_id'] . "</td>";
            $this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\">PACIENTE:</td><td width=\"60%\" class=\"modulo_list_claro\">" . $_SESSION['CENTROAUTORIZACION']['nombre_paciente'] . "</td>";
        } else {
            $this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\">IDENTIFICACION: </td><td width=\"20%\" class=\"modulo_list_claro\">" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . " " . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "</td>";
            $this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\">PACIENTE:</td><td width=\"60%\" class=\"modulo_list_claro\">" . $_SESSION['CENTROAUTORIZACION']['TODO']['nombre_paciente'] . "</td>";
        }
        $this->salida .= "      </tr>";
        $this->salida .= "       </table><br>";
        $_SESSION['CENTRO_AUTORIZACION']['DATOS'] = $arr;
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'CrearOrdenServicio');
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        for ($i = 0; $i < sizeof($arr);) {
            $this->salida .= "     <table width=\"98%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td>CARGO</td>";
            $this->salida .= "        <td>DESCRICPION</td>";
            $this->salida .= "        <td width=\"5%\" nowrap>CANT</td>";
            $this->salida .= "        <td width=\"20%\" nowrap>PROVEEDOR</td>";
            $this->salida .= "      </tr>";

            if ($arr[$i][des_especilidad]) {
                $this->salida .= "      <tr align=\"LEFT\" class=\"modulo_table_list_title\">";
                $this->salida .= "        <td colspan=\"4\">ESPECIALIDAD:" . $arr[$i][des_especilidad] . "</td>";
                $this->salida .= "      </tr>";
            }

            $d = $i;
            if ($i % 2) {
                $estilo = "modulo_list_claro";
            } else {
                $estilo = "modulo_list_oscuro";
            }
            //para la cantidad(suma los mismos)
            $this->salida .= "      <tr class=\"$estilo\">";
            //$this->salida .= "        <td align=\"center\" width=\"10%\">".$arr[$i][tarifario_id]."</td>";
            $this->salida .= "        <td align=\"center\" width=\"10%\">" . $arr[$i][cargos] . "</td>";
            $this->salida .= "        <td>" . $arr[$i][descar] . "</td>";
            $this->salida .= "        <td align=\"center\">" . $arr[$i][cantidad] . "</td>";

            $dpto = $this->ComboDepartamento($arr[$i][cargos], $arr[$i][hc_os_solicitud_id]);

            //al proveedor se le agrega el nuevo campo que requiere para filtrar la unidad funcional del proveedor "Nicolas CAballero" <nicolas.caballero@duanaltda.com>

            $pro = $this->ComboProveedor($arr[$i][cargos], $arr[$i][hc_os_solicitud_id]);
            if (!empty($dpto) OR !empty($pro)) {
                $this->salida .= "        <td align=\"center\"><select name=\"Combo" . $arr[$i][hc_os_solicitud_id] . "\" class=\"select\">";
                $this->salida .=" <option value=\"-1\">------SELECCIONE------</option>";
                //departamentos
                for ($j = 0; $j < sizeof($dpto); $j++) {
                    $x = $arr[$i][hc_os_solicitud_id] . "," . $dpto[$j][departamento] . ",dpto," . $arr[$i][tarifario_id] . "," . $arr[$i][cargo] . "," . $arr[$i][cargos] . "," . $arr[$i][fecha] . "," . $arr[$i][fecha] . "," . $arr[$i][cantidad] . "," . $arr[$i][evento_soat];
                    if ($_REQUEST['Combo' . $arr[$i][hc_os_solicitud_id]] == $x) {
                        $this->salida .=" <option value=\"" . $arr[$i][hc_os_solicitud_id] . "," . $dpto[$j][departamento] . ",dpto," . $arr[$i][tarifario_id] . "," . $arr[$i][cargo] . "," . $arr[$i][cargos] . "," . $arr[$i][fecha] . "," . $arr[$i][fecha] . "," . $arr[$i][cantidad] . "," . $arr[$i][evento_soat] . "\" selected>" . $dpto[$j][descripcion] . "</option>";
                    } else {
                        $this->salida .=" <option value=\"" . $arr[$i][hc_os_solicitud_id] . "," . $dpto[$j][departamento] . ",dpto," . $arr[$i][tarifario_id] . "," . $arr[$i][cargo] . "," . $arr[$i][cargos] . "," . $arr[$i][fecha] . "," . $arr[$i][fecha] . "," . $arr[$i][cantidad] . "," . $arr[$i][evento_soat] . "\">" . $dpto[$j][descripcion] . "</option>";
                    }
                }
                //proveedores
                for ($j = 0; $j < sizeof($pro); $j++) {
                    $x = $arr[$i][hc_os_solicitud_id] . "," . $pro[$j][tercero_id] . "," . $pro[$j][tipo_id_tercero] . "," . $arr[$i][tarifario_id] . "," . $arr[$i][cargo] . "," . $arr[$i][cargos] . "," . $arr[$i][fecha] . "," . $pro[$j][plan_proveedor_id] . "," . $arr[$i][cantidad] . "," . $arr[$i][evento_soat];
                    if ($_REQUEST['Combo' . $arr[$i][hc_os_solicitud_id]] == $x) {
                        $this->salida .=" <option value=\"" . $arr[$i][hc_os_solicitud_id] . "," . $pro[$j][tercero_id] . "," . $pro[$j][tipo_id_tercero] . "," . $arr[$i][tarifario_id] . "," . $arr[$i][cargo] . "," . $arr[$i][cargos] . "," . $arr[$i][fecha] . "," . $pro[$j][plan_proveedor_id] . "," . $arr[$i][cantidad] . "," . $arr[$i][evento_soat] . "\" selected>" . $pro[$j][plan_descripcion] . "</option>";
                    } else {
                        $this->salida .=" <option value=\"" . $arr[$i][hc_os_solicitud_id] . "," . $pro[$j][tercero_id] . "," . $pro[$j][tipo_id_tercero] . "," . $arr[$i][tarifario_id] . "," . $arr[$i][cargo] . "," . $arr[$i][cargos] . "," . $arr[$i][fecha] . "," . $pro[$j][plan_proveedor_id] . "," . $arr[$i][cantidad] . "," . $arr[$i][evento_soat] . "," . $pro[$j][direccion] . "," . $pro[$j][telefono] . "\">" . $pro[$j][plan_descripcion] . "</option>";
                    } //Se agregan los campos de direccion, telefono
                }
                $this->salida .= "              </select></td>";
            } else {
                $this->salida .= "       <input type=\"hidden\" name=\"Trans\" value=\"1\">";
                $this->salida .= "       <input type=\"hidden\" name=\"dat\" value=\"" . $arr[$i][hc_os_solicitud_id] . ",dpto,dpto," . $arr[$i][tarifario_id] . "," . $arr[$i][cargo] . "," . $arr[$i][cargos] . "," . $arr[$i][fecha] . "," . $arr[$i][fecha] . "," . $arr[$i][cantidad] . "\">";
                $this->salida .= "       <input type=\"hidden\" name=\"solicitud\" value=\"" . $arr[$i][hc_os_solicitud_id] . "\">";
                $trans = true;
                //$accion=ModuloGetURL('app','CentroAutorizacion','user','CrearTranscripcion',array('datos'=>$arr,'solicitud'=>$arr[$i][hc_os_solicitud_id]));
                //$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "        <td class=\"label_error\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Transcripcion\" value=\"TRANSCRIPCION\"></td>";
                //  $this->salida .= "       <input type=\"hidden\" name=\"Combo".$arr[$d][hc_os_solicitud_id]."\" value=\"".$arr[$i][hc_os_solicitud_id].",".$_SESSION['CAJARAPIDA']['DPTO'].",dpto,".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha]."\">";
                //    $this->salida .= "        <td class=\"label_error\" align=\"center\"><a href=\"$accion\">TRANSCRIPCION</a></td>";
            }
            $this->salida .= "       <input type=\"hidden\" name=\"trans\" value=\"$j\">";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr>";
            $this->salida .= "      <td colspan=\"4\">";
            $this->salida .= "      <table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "        <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "            <td>CARGO</td>";
            $this->salida .= "            <td>TARIFARIO</td>";
            $this->salida .= "            <td>DESCRICPION</td>";
            $this->salida .= "            <td>PRECIO</td>";
            $this->salida .= "            <td>PUNTO DE TOMADO</td>";
            $this->salida .= "            <td></td>";
            $this->salida .= "        </tr>";
            $x = 0;


            while ($arr[$i][cargos] == $arr[$d][cargos]
            AND $arr[$i][hc_os_solicitud_id] == $arr[$d][hc_os_solicitud_id]) {
                $cont = $this->ValidarContratoEqui($arr[$d][tarifario_id], $arr[$d][cargo], $arr[$d][plan_id]);
                if ($cont > 0) {
                    $this->salida .= "      <tr class=\"$estilo\">";
                    $this->salida .= "      <td align=\"center\" width=\"10%\">" . $arr[$d][cargo] . "</td>";
                    $this->salida .= "      <td align=\"center\" width=\"10%\">" . $arr[$d][tarifario_id] . "</td>";
                    $this->salida .= "      <td width=\"40%\">" . $arr[$d][descripcion] . "</td>";
                    $cargos[] = array('tarifario_id' => $arr[$d][tarifario_id], 'cargo' => $arr[$d][cargo], 'cantidad' => 1, 'autorizacion_int' => $_SESSION['CENTROAUTORIZACION']['TODO']['NumAutorizacion'], 'autorizacion_ext' => '');
                    if (!empty($_SESSION['CENTROAUTORIZACION']['TODO'])) {
                        $liq = LiquidarCargosCuentaVirtual($cargos, '', '', '', $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'], $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_afiliado_id'], $_SESSION['CENTROAUTORIZACION']['TODO']['rango'], $_SESSION['CENTROAUTORIZACION']['TODO']['semanas'], $arr[$d][servicio]);
                    } else {
                        $liq = LiquidarCargosCuentaVirtual($cargos, '', '', '', $_SESSION['CENTROAUTORIZACION']['PLAN'], $_SESSION['CENTROAUTORIZACION']['tipo_afiliado_id'], $_SESSION['CENTROAUTORIZACION']['rango'], $_SESSION['CENTROAUTORIZACION']['semanas'], $arr[$d][servicio]);
                    }
                    $this->salida .= "      <td align=\"center\" width=\"10%\">" . FormatoValor($liq[0][valor_cargo]) . "</td>";

                    $this->salida .= "			<td align=\"center\" width=\"40%\">";
                    $this->salida .= $this->PintaCombo($arr[$d][cargos]);
                    $this->salida .= "			</td>";

                    if ($_REQUEST['Op' . $arr[$d][hc_os_solicitud_id] . $arr[$d][cargo] . $arr[$d][tarifario_id]] == $arr[$d][hc_os_solicitud_id] . "," . $arr[$d][cargo] . "," . $arr[$d][tarifario_id]) {
                        $this->salida .= "      <td width=\"5%\" align=\"center\"><input type=\"checkbox\" value=\"" . $arr[$d][hc_os_solicitud_id] . "," . $arr[$d][cargo] . "," . $arr[$d][tarifario_id] . "\" name=\"Op" . $arr[$d][hc_os_solicitud_id] . $arr[$d][cargo] . $arr[$d][tarifario_id] . "\" checked></td>";
                    } else {
                        $this->salida .= "      <td width=\"5%\" align=\"center\"><input type=\"checkbox\" value=\"" . $arr[$d][hc_os_solicitud_id] . "," . $arr[$d][cargo] . "," . $arr[$d][tarifario_id] . "\" name=\"Op" . $arr[$d][hc_os_solicitud_id] . $arr[$d][cargo] . $arr[$d][tarifario_id] . "\"></td>";
                    }
                    $this->salida .= "      </tr>";
                }
                $d++;
                $x++;
            }
            $i = $d;
            //if(!empty($trans))
            //{  $this->salida .= "</form>";  }
            $this->salida .= " </table>";
            $this->salida .= "      </td>";
            $this->salida .= "      </tr>";
            $this->salida .= " </table><br>";
        }
        //if($j!=0)
        //{
        /*
          $unidad=$this->ComboUnidadFuncional();
          if (!empty($unidad)){
          $this->salida .= "    <table width=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
          $this->salida .= "               <tr class=\"$estilo\">";
          if(!empty($_REQUEST['punto_tomado'])) {
          $this->salida .= "                       <td><input type=\"checkbox\" name=\"punto_tomado\" checked onclick=\"opc_unidad_funcional.disabled = !this.checked\" >Punto de Tomado";
          }else{
          $this->salida .= "                       <td><input type=\"checkbox\" name=\"punto_tomado\" onclick=\"opc_unidad_funcional.disabled = !this.checked\">Punto de Tomado";
          }
          if(!empty($_REQUEST['punto_tomado'])) {
          $this->salida .= "                       <select  name = \"opc_unidad_funcional\" class=\"select\">";
          }else{
          $this->salida .= "                       <select disabled name = \"opc_unidad_funcional\" class=\"select\">";
          }

          $this->salida .= " 					   <option value = \"-1\">---------SELECCIONE---------</option>";
          for($j=0; $j<sizeof($unidad); $j++)
          {
          $this->salida .= " 				   <option value = \"".$unidad[$j]['codigo']."\">".$unidad[$j]['descripcion']."</option>";
          }
          $this->salida .= "					</select>";
          $this->salida .= "		  	</td></tr></table><br>";
          }
         */
        $this->salida .= "    <table width=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
        $this->salida .= "               <tr>";
        //JONIER BOTON
        $this->salida .= "                       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar1\" value=\"ACEPTAR\"></td>";
        $this->salida .= "                       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\"></td>";
        $this->salida .= "                       </form>";
        $this->salida .= "               </tr>";
        $this->salida .= "  </table>";
        //}
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function PintaCombo($cargos) {
        $cadcom = '';
        $TipoLBP = $this->ValidarCargosLB_y_LP($cargos);

        if (count($TipoLBP) > 0) {
            $valcar = 0;
            $unidad = $this->ComboUnidadFuncionalCargos($cargos);
            if (!count($unidad) > 0) {
                $unidad = $this->ComboUnidadFuncional();
                $valcar = 1;
                $checked = "";
                $disabled = "disabled";
            } else {
                $checked = "checked";
                $disabled = "";
            }

            if (!empty($unidad)) {
                if (!empty($_REQUEST['punto_tomado' . $cargos])) {
                    $cadcom .= "                       <input type=\"checkbox\" name=\"punto_tomado" . $cargos . "\" checked onclick=\"opc_unidad_funcional" . $cargos . ".disabled = !this.checked\" $checked>";
                } else {
                    $cadcom .= "                   <input type=\"checkbox\" name=\"punto_tomado" . $cargos . "\" onclick=\"opc_unidad_funcional" . $cargos . ".disabled = !this.checked\" $checked>";
                }

                if (!empty($_REQUEST['punto_tomado' . $cargos])) {
                    $cadcom .= "                       <select  name = \"opc_unidad_funcional" . $cargos . "\" class=\"select\">";
                } else {
                    $cadcom .= "                       <select  $disabled name = \"opc_unidad_funcional" . $cargos . "\" class=\"select\">";
                }
                if ($valcar == 1) {
                    $cadcom .= "                                <option value = \"-1\">---------SELECCIONE---------</option>";
                }
                for ($j = 0; $j < sizeof($unidad); $j++) {
                    $cadcom .= "                                <option value = \"" . $unidad[$j]['codigo'] . "\">" . $unidad[$j]['descripcion'] . "</option>";
                }
                $cadcom .= "                           </select>";
            }
            return $cadcom;
        } else {
            return $cadcom;
        }
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
//          return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
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
     * Forma para los mansajes
     * @access private
     * @return void
     */
    function FormaMensaje($mensaje, $titulo, $accion, $boton) {
        $this->salida .= ThemeAbrirTabla($titulo);
        $this->salida .= "            <table width=\"60%\" align=\"center\" >";
        $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "               <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
        if ($boton) {
            $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
        } else {
            $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
        }
        $this->salida .= "           </form>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

//-----------------------------------AUTORIZACION-------------------------------
    /**
     *
     */
    function FormaAutorizacion() {
        $this->SetJavaScripts('DatosBD');
        $this->SetJavaScripts('DatosBDAnteriores');
        $this->SetJavaScripts('DatosEvolucionInactiva');
        $this->salida .= ThemeAbrirTabla('CENTRO AUTORIZACION');
        if (!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'])) {
            $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"50%\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "  <tr class=\"modulo_list_claro\">";
            $this->salida .= "   <td align=\"center\">" . RetornarWinOpenDatosBD($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'], $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'], $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']) . "</td>";
            $x = $this->CantidadMeses($_SESSION['CENTROAUTORIZACION']['PLAN']);
            if ($x > 1) {
                $this->salida .= "   <td align=\"center\">" . RetornarWinOpenDatosBDAnteriores($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'], $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'], $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'], $x) . "</td>";
            }
            $this->salida .= "  </tr>";
            $this->salida .= "</table>";
        }
        $sw = $this->BuscarSwHc();
        if (!empty($sw)) {
            $dat = $this->BuscarEvolucion();
            if ($dat) {//1139
                $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"30%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "  <tr class=\"modulo_list_claro\">";
                $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'] = 'CentroAutorizacion';
                $_SESSION['HISTORIACLINICA']['RETORNO']['metodo'] = 'FormaAutorizacion';
                $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'] = 'user';
                $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'] = 'app';
                $accion = ModuloHCGetURL($dat, '', '', '', '');
                $this->salida .= "   <td align=\"center\"><a href=\"$accion\">HISTORIA CLINICA</a></td>";
                $this->salida .= "  </tr>";
                $this->salida .= "</table><BR>";
            }
        }

        /* $m=$this->CantidadMeses($_SESSION['CENTROAUTORIZACION']['PLAN']);
          if($m>1)
          {
          $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"30%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= "  <tr class=\"modulo_list_claro\">";
          $this->salida .= "   <td align=\"center\">".RetornarWinOpenDatosBDAnteriores("'".$_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']."'","'".$_SESSION['CENTROAUTORIZACION']['paciente_id']."'",$_SESSION['CENTROAUTORIZACION']['PLAN'],$m)."</td>";
          $this->salida .= "  </tr>";
          $this->salida .= "</table><BR>";
          } */
        //mensaje
        $this->salida .= "<div align=\"center\" class=\"label_error\">" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg'] . "</div><br>";
        $this->salida .= "          <table width=\"90%\" align=\"center\" border=\"0\" cellpadding=\"3\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "          </table>";
        //llamar en encabezado datos paciente
        $this->FormaDatosPaciente();
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'InsertarAutorizacion');
        $this->salida .= "      <form name=\"forma\" action=\"$accion\" method=\"post\">";
        if ($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'] != 'FACTURACION'
                AND $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan'] != 2) {   //tipo afiliado y rango
            $this->FormaDatosAfiliado();
        }
        if ($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan'] == 2
                OR $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan'] == 1) {
            $dat = $this->DatosPlanUnico($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']);
            $_SESSION['AUTORIZACIONES']['SEMANAS'] = 0;
            $_SESSION['AUTORIZACIONES']['AFILIADO'] = $dat[tipo_afiliado_id];
            $_SESSION['AUTORIZACIONES']['RANGO'] = $dat[rango];
        }
        //otros datos de la bd
        if (!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'])) {
            $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "          <tr>";
            $this->salida .= "            <td  width=\"10%\" class=\"" . $this->SetStyle("TipoAfiliado") . "\">EMPLEADOR: </td>";
            $this->salida .= "            <td align=\"left\" width=\"35%\">" . $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador'] . "</td>";
            $this->salida .= "            <td></td>";
            $this->salida .= "             <td width=\"7%\" class=\"" . $this->SetStyle("Nivel") . "\">EDAD: </td>";
            $this->salida .= "            <td align=\"left\" width=\"5%\">" . $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_edad'] . "</td>";
            $this->salida .= "            <td></td>";
            $this->salida .= "            <td width=\"10%\" class=\"" . $this->SetStyle("Semanas") . "\" width=\"23%\">ESTADO: </td>";
            if ($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd'] == 'SUSPENDIDO'
                    OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd'] == 'INACTIVO'
                    OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd'] == 'URGENCIAS'
                    OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd'] == 'PROTECCION') {
                $x = 'label_error';
            } else {
                $x = 'label';
            }
            $this->salida .= "            <td align=\"left\" width=\"10%\" class=\"$x\">" . $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd'] . "</td>";
            $this->salida .= "            <td width=\"12%\" class=\"" . $this->SetStyle("Semanas") . "\" width=\"23%\">URGENCIAS: </td>";
            if ($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_urgencias'] == 1) {
                $ur = 'MES URG';
            }
            $this->salida .= "            <td align=\"left\" width=\"10%\">" . $ur . "</td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          <tr>";
            $this->salida .= "            <td  width=\"10%\" class=\"" . $this->SetStyle("TipoAfiliado") . "\">RADICACION BD: </td>";
            $this->salida .= "            <td align=\"left\" width=\"35%\">" . $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['fecha_radicacion'] . "</td>";
            $this->salida .= "            <td></td>";
            $this->salida .= "             <td width=\"7%\" class=\"" . $this->SetStyle("Nivel") . "\">VENCIMIENTO BD: </td>";
            $this->salida .= "            <td align=\"left\" width=\"5%\">" . $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['fecha_vencimiento'] . "</td>";
            $this->salida .= "            <td></td>";
            $this->salida .= "            <td width=\"10%\" class=\"" . $this->SetStyle("Semanas") . "\" width=\"23%\"></td>";
            $this->salida .= "            <td align=\"left\" width=\"10%\"></td>";
            $this->salida .= "            <td width=\"12%\" class=\"" . $this->SetStyle("Semanas") . "\" width=\"23%\"></td>";
            $this->salida .= "            <td align=\"left\" width=\"10%\"></td>";
            $this->salida .= "          </tr>";
            $this->salida .= "       </table>";
        }
        $this->CargosSolicitadosAutorizacion();
        //autorizaciones que tienen tramite
        if (!empty($_SESSION['AUTORIZACIONES']['TRAMITE'])) {
            $this->salida .= "   <BR> <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
            foreach ($_SESSION['AUTORIZACIONES']['TRAMITE'] as $k => $v) {
                $s = '';
                foreach ($v as $key => $value) {
                    $s.=$key . ' - ';
                }
                $this->salida .= "             <tr class=\"modulo_table_list_title\">";
                $this->salida .= "                 <td colspan=\"8\" align=\"LEFT\">TRAMITES SOLICITUD: &nbsp;$s</td>";
                $this->salida .= "             </tr>";
                $this->salida .= "          <tr>";
                if (!empty($value[sw_personalmente])) {
                    $value[nombre] = 'Personalmente';
                }
                if (!empty($value[sw_telefonica])) {
                    $value[sw_telefonica] = 'Si';
                } else {
                    $value[sw_telefonica] = 'No';
                }
                $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"12%\">RECIBIO: </td>";
                $this->salida .= "                 <td class=\"modulo_list_claro\">" . $value[nombre] . "</td>";
                $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"7%\">FECHA: </td>";
                $this->salida .= "                 <td class=\"modulo_list_claro\" width=\"18%\">" . $this->FechaStamp($value[fecha_resgistro]) . " " . $this->HoraStamp($value[fecha_resgistro]) . "</td>";
                $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"7%\">USUARIO: </td>";
                $this->salida .= "                 <td class=\"modulo_list_claro\" width=\"25%\">" . $value[usuario] . "</td>";
                $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"4%\">TELE: </td>";
                $this->salida .= "                 <td class=\"modulo_list_claro\" width=\"2%\">" . $value[sw_telefonica] . "</td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          <tr>";
                $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\">OBSERVACION : </td>";
                $this->salida .= "                 <td colspan=\"7\" class=\"modulo_list_claro\">" . $value[observacion_autorizador] . "</td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          <tr>";
                $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\">OBS. PACIENTE: </td>";
                $this->salida .= "                 <td colspan=\"7\" class=\"modulo_list_claro\">" . $value[observacion_paciente] . "</td>";
                $this->salida .= "          </tr>";
            }
            $this->salida .= "       </table>";
        }
        //TIPO AUTORIZACION
        $this->salida .= "     <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"33%\">SELECCIONE TIPO AUTORIZACION: </td>";
        $this->salida .= "      <td class=\"modulo_list_claro\"><select name=\"TipoAutorizacion\" class=\"select\">";
        $TiposAuto = $this->CallMetodoExterno('app', 'CentroAutorizacion', 'user', 'TiposAuto');
        $this->BuscarTipoAutorizacion($TiposAuto, $_REQUEST['TipoAutorizacion']);
        $this->salida .= "      </select></td>";
        //$accion=ModuloGetURL('app','Autorizacion','user','PedirAutorizacion');
        $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
        $this->salida .= "      </tr>";
        $this->salida .= "     </table><BR>";
        //fecha de la autorizacion
        $this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\" class=\"normal_10\">";
        $this->salida .= "      </tr>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("FechaAuto") . "\">FECHA AUTORIZACION: </td>";
        if (!$FechaAuto) {
            $FechaAuto = date("d/m/Y");
        }
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"FechaAuto\" size=\"12\" value=\"" . $FechaAuto . "\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
        $this->salida .= "&nbsp;&nbsp;" . ReturnOpenCalendario('forma', 'FechaAuto', '/') . "</td>";
        if (!$HoraAuto) {
            $HoraAuto = date('H');
        }
        if (!$MinAuto) {
            $MinAuto = date('i');
        }
        $this->salida .= "  <td class=\"" . $this->SetStyle("HoraAuto") . "\">HORA AUTORIZACION: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"HoraAuto\" size=\"4\" value=\"" . $HoraAuto . "\" maxlength=\"2\">&nbsp;:&nbsp;<input type=\"text\" class=\"input-text\" name=\"MinAuto\" size=\"4\" value=\"" . $MinAuto . "\" maxlength=\"2\"></td>";
        $this->salida .= "      </tr>";
        $this->salida .= "     </table>";
        //OBSERVACIONES
        $this->salida .= " <table border=\"0\" width=\"80%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
        $observacion = $this->Observaciones();
        if ($observacion != ' ' AND $observacion != '') {
            $this->salida .= "  <tr>";
            $this->salida .= "  <td  width=\"30%\" class=\"" . $this->SetStyle("Observaciones") . "\">OBSERVACIONES DE LAS AUTORIZACION REALIZADAS: </td>";
            $this->salida .= "  <td><textarea  cols=\"80\" rows=\"4\" class=\"textarea\" name=\"ObservacionesT\" readonly>$observacion</textarea></td>";
            $this->salida .= "  </tr><br>";
        }
        $this->salida .= "  <tr>";
        $this->salida .= "  <td  width=\"30%\" class=\"" . $this->SetStyle("Observaciones") . "\">OBSERVACIONES AUTORIZACION: </td>";
        $obs = '';
        if (!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_urgencias'])) {
            $obs = 'PACIENTE EN MES DE URGENCIAS<br>';
        }
        $this->salida .= "  <td><textarea  cols=\"80\" rows=\"4\" class=\"textarea\" name=\"ObservacionesA\">$obs" . $_SESSION['AUTORIZACIONES']['ObservacionesA'] . "</textarea></td>";
        $this->salida .= "  </tr><br>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td  width=\"30%\" class=\"" . $this->SetStyle("ObservacionesO") . "\">OBSERVACIONES DE LA ORDEN: </td>";
        $this->salida .= "  <td><textarea  cols=\"80\" rows=\"4\" class=\"textarea\" name=\"ObservacionesOS\"></textarea></td>";
        $this->salida .= "  </tr><br>";
        $this->salida .= "     </table><BR>";
        //url protocolo
        if (!empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['protocolo'])) {
            if (file_exists("protocolos/" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['protocolo'] . "")) {
                $Protocolo = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['protocolo'];
                $this->salida .= "<script>";
                $this->salida .= "function Protocolo(valor){";
                $this->salida .= "window.open('protocolos/'+valor,'PROTOCOLO','');";
                $this->salida .= "}";
                $this->salida .= "</script>";
                $accion = "javascript:Protocolo('$Protocolo')";
            }
            $this->salida .= "          <br><table width=\"40%\" align=\"center\" border=\"0\" class=\"normal_10\" cellpadding=\"3\">";
            $this->salida .= "             <tr class=\"modulo_list_claro\">";
            $this->salida .= "                 <td width=\"30%\" class=\"label\">PROTOCOLO</td>";
            $this->salida .= "                 <td><a href=\"$accion\">$Protocolo</a></td>";
            $this->salida .= "             </tr>";
            $this->salida .= "            </table><br>";
        }
        $this->salida .= "<br><table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"AUTORIZAR\"></td>";
        $this->salida .= "  <td align=\"center\" width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"NoAutorizar\" value=\"NO AUTORIZAR\"></td>";
        $this->salida .= "      </form>";
        if ($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'] != 'CAJARAPIDA') {
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'FormaTramite');
            $this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
            $this->salida .= "  <td align=\"center\" width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"TRAMITE\"></td>";
            $this->salida .= "      </form>";
        }
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'RetornarAutorizacion');
        $this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\" width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
        $this->salida .= "      </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "      </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaTramite() {
        $this->salida .= ThemeAbrirTabla('TRAMITE DE REQUISITOS PARA LA AUTORIZACION');
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'Tramite');
        $this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("nombre") . "\">NOMBRE:</td><td><input type=\"text\" class=\"input-text\" name=\"nombre\" maxlength=\"30\" size=\"30\" value=\"" . $_REQUEST['nombre'] . "\"></td>";
        if (!empty($_REQUEST['personal'])) {
            $this->salida .= "  <td class=\"label\">PERSONALMENTE&nbsp;&nbsp;  <input type=\"checkbox\" name=\"personal\" maxlength=\"30\" value=\"1\" checked></td>";
        } else {
            $this->salida .= "  <td class=\"label\">PERSONALMENTE&nbsp;&nbsp;  <input type=\"checkbox\" name=\"personal\" maxlength=\"30\" value=\"1\"></td>";
        }
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("observacion") . "\">OBSERVACIONES:</td><td colspan=\"2\"><textarea  cols=\"60\" rows=\"4\" class=\"textarea\"name=\"observacion\">" . $_REQUEST['observacion'] . "</textarea></td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("observacionp") . "\">RESPUESTA PACIENTE:</td><td colspan=\"2\"><textarea  cols=\"60\" rows=\"4\" class=\"textarea\"name=\"observacionp\">" . $_REQUEST['observacionp'] . "</textarea></td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"label\">TELEFONICA:</td>";
        $this->salida .= "  <td class=\"label\" colspan=\"2\"><input type=\"checkbox\" name=\"tele\" maxlength=\"30\" value=\"1\"></td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        //botones
        $this->salida .= "<br><table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"GUARDAR\"></td>";
        $this->salida .= "  </form>";
        if (empty($_SESSION['AUTORIZACIONES']['TRAMITEX']['SINAUTO'])) {
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarFormaAutorizacion');
        } else {
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'FormaAfiliado');
        }
        $this->salida .= "  <td align=\"center\"><form name=\"forma2\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
        $this->salida .= "      </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaDatosPaciente() {
        $this->salida .= " <table border=\"0\" width=\"70%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION:</td><td width=\"20%\" class=\"modulo_list_claro\">" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'] . " " . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'] . "</td>";
        $nombre = $this->NombrePaciente($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'], $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']);
        $this->salida .= "  <td class=\"modulo_table_list_title\" width=\"20%\">PACIENTE:</td><td class=\"modulo_list_claro\">" . $nombre[nombre] . "</td>";
        $this->salida .= "  </tr>";
        $plan = $this->DatosPlan();
        $this->salida .= "  <td class=\"modulo_table_list_title\" width=\"20%\">PLAN:</td><td class=\"modulo_list_claro\">" . $plan[plan_descripcion] . "</td>";
        $this->salida .= "  <td class=\"modulo_table_list_title\" width=\"20%\">RESPONSABLE:</td><td class=\"modulo_list_claro\">" . $plan[nombre_tercero] . "</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
    }

    /**
     *
     */
    function FormaDatosAfiliado() {
        if (!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'])
                AND !empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel'])) {
            //tipo afiliado
            if (empty($_SESSION['AUTORIZACIONES']['AFILIADO'])) {
                $tipo = $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'];
                $_SESSION['AUTORIZACIONES']['AFILIADO'] = $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'];
            } else {
                $tipo = $_SESSION['AUTORIZACIONES']['AFILIADO'];
            }
            //rango
            if (empty($_SESSION['AUTORIZACIONES']['RANGO'])) {
                $Nivel = $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel'];
                $_SESSION['AUTORIZACIONES']['RANGO'] = $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel'];
            } else {
                $Nivel = $_SESSION['AUTORIZACIONES']['RANGO'];
            }
            //semanas
            if (empty($_SESSION['AUTORIZACIONES']['SEMANAS'])) {
                $s = $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas'];
                $_SESSION['AUTORIZACIONES']['SEMANAS'] = $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas'];
            } else {
                $s = $_SESSION['AUTORIZACIONES']['SEMANAS'];
            }
            if (empty($s)) {
                $_SESSION['AUTORIZACIONES']['SEMANAS'] = 0;
                $s = $_SESSION['AUTORIZACIONES']['SEMANAS'];
            }
            $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "          <tr>";
            $this->salida .= "            <td  width=\"15%\" class=\"" . $this->SetStyle("TipoAfiliado") . "\">TIPO AFILIADO: </td>";
            $NomAfi = $this->NombreAfiliado($tipo);
            $this->salida .= "            <td align=\"left\" width=\"20%\"><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"" . $tipo . "\">" . $NomAfi[tipo_afiliado_nombre] . "</td>";
            $this->salida .= "            <td></td>";
            $this->salida .= "             <td width=\"10%\" class=\"" . $this->SetStyle("Nivel") . "\">RANGO: </td>";
            $this->salida .= "            <td align=\"left\" width=\"7%\"><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"" . $Nivel . "\">" . $Nivel . "</td>";
            $this->salida .= "            <td></td>";
            $this->salida .= "            <td width=\"20%\" class=\"" . $this->SetStyle("Semanas") . "\" width=\"23%\">SEMANAS COTIZADAS: </td>";
            $this->salida .= "            <td align=\"left\" width=\"10%\"><input type=\"text\" name=\"Semanas\" size=\"8\" value=\"" . $s . "\" readonly></td>";
            $this->salida .= "            <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"CAMBIAR\"></td>";
            $this->salida .= "          </tr>";
            $this->salida .= "       </table>";
        } else {
            $this->salida .= "    <input type=\"hidden\" name=\"Si\" value=\"1\">";
            $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
            $tipo_afiliado = $this->Tipo_Afiliado();
            $this->salida .= "          <tr>";
            $TipoAfiliado = $_SESSION['AUTORIZACIONES']['AFILIADO'];
            if (sizeof($tipo_afiliado) > 1) {
                $this->salida .= "               <td class=\"" . $this->SetStyle("TipoAfiliado") . "\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
                $this->BuscarIdTipoAfiliado($tipo_afiliado, $TipoAfiliado);
                $this->salida .= "              </select></td>";
            } else {
                $this->salida .= "            <td class=\"" . $this->SetStyle("TipoAfiliado") . "\">TIPO AFILIADO: </td>";
                $NomAfi = $this->NombreAfiliado($tipo_afiliado[0][tipo_afiliado_id]);
                $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"" . $tipo_afiliado[0][tipo_afiliado_id] . "\">" . $NomAfi[tipo_afiliado_nombre] . "</td>";
                $this->salida .= "            <td></td>";
            }
            $niveles = $this->Niveles();
            $Nivel = $_SESSION['AUTORIZACIONES']['RANGO'];
            if (sizeof($niveles) > 1) {
                $this->salida .= "               <td class=\"" . $this->SetStyle("Nivel") . "\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
                $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                for ($i = 0; $i < sizeof($niveles); $i++) {
                    if ($niveles[$i][rango] == $Nivel) {
                        $this->salida .=" <option value=\"" . $niveles[$i][rango] . "\" selected>" . $niveles[$i][rango] . "</option>";
                    } else {
                        $this->salida .=" <option value=\"" . $niveles[$i][rango] . "\">" . $niveles[$i][rango] . "</option>";
                    }
                }
            } else {
                $this->salida .= "             <td class=\"" . $this->SetStyle("Nivel") . "\">RANGO: </td>";
                $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"" . $niveles[0][rango] . "\">" . $niveles[0][rango] . "</td>";
                $this->salida .= "            <td></td>";
            }
            $this->salida .= "            <td class=\"" . $this->SetStyle("Semanas") . "\" width=\"23%\">SEMANAS COTIZADAS: </td>";
            $s = $_SESSION['AUTORIZACIONES']['SEMANAS'];
            if (empty($s)) {
                $s = 0;
            }
            $this->salida .= "            <td><input type=\"text\" name=\"Semanas\" size=\"8\" value=\"" . $s . "\"></td>";
            $this->salida .= "          </tr>";
            $this->salida .= "       </table>";
        }
    }

    /**
     * Crear el combo de tipos de afiliados
     * @access private
     * @return string
     * @param array arreglo con los tipos de afiliados
     * @param int tipo de afiliado
     */
    function BuscarIdTipoAfiliado($tipo_afiliado, $TipoAfiliado='') {
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
        for ($i = 0; $i < sizeof($tipo_afiliado); $i++) {
            if ($tipo_afiliado[$i][tipo_afiliado_id] == $TipoAfiliado) {
                $this->salida .=" <option value=\"" . $tipo_afiliado[$i][tipo_afiliado_id] . "\" selected>" . $tipo_afiliado[$i][tipo_afiliado_nombre] . "</option>";
            } elseif ($tipo_afiliado[$i][tipo_afiliado_id] == $_SESSION['SOLICITUDAUTORIZACION']['AFILIADO'][$tipo_afiliado[$i][tipo_afiliado_id]]) {
                $this->salida .=" <option value=\"" . $tipo_afiliado[$i][tipo_afiliado_id] . "\" selected>" . $tipo_afiliado[$i][tipo_afiliado_nombre] . "</option>";
            } else {
                $this->salida .=" <option value=\"" . $tipo_afiliado[$i][tipo_afiliado_id] . "\">" . $tipo_afiliado[$i][tipo_afiliado_nombre] . "</option>";
            }
        }
    }

    /**
     *
     */
    function FormaSolicitud($cargos) {
        IncludeLib("tarifario_cargos");
        unset($_SESSION['AUTORIZACIONES']['TRAMITE']);
        unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['SEMANAS']);
        if (!empty($cargos)) {
            $this->salida .= "            <br><table width=\"90%\" align=\"center\" border=\"0\"  cellpadding=\"3\" class=\"modulo_table_list\">";
            $this->salida .= "             <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "                 <td colspan=\"5\">CARGOS ORDEN SERVICIO..</td>";
            $this->salida .= "             </tr>";
            for ($i = 0; $i < sizeof($cargos);) {
                $this->salida .= "             <tr align=\"center\" class=\"modulo_table_list_title\">";
                $this->salida .= "                 <td width=\"10%\">CODIGO</td>";
                $this->salida .= "                 <td>CARGO</td>";
                $this->salida .= "                 <td colspan=\"2\">SERVICIO</td>";
                $this->salida .= "                <td width=\"7%\">CANT</td>";
                $this->salida .= "             </tr>";
                $d = $i;
                if ($cargos[$i][cargoc] == $cargos[$d][cargoc]) {
                    $this->salida .= "             <tr class=\"modulo_list_claro\">";
                    $this->salida .= "                 <td align=\"center\">" . $cargos[$d][cargoc] . "</td>";
                    $this->salida .= "                 <td>" . $cargos[$d][descripcion] . "</td>";
                    $this->salida .= "                 <td align=\"center\" colspan=\"2\">" . $cargos[$d][descserv] . "</td>";
                    $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarFormaCambiarCantidad', array('ant' => $cargos[$d][cantidad], 'solicitud' => $cargos[$d][hc_os_solicitud_id], 'cargo' => $cargos[$d][cargo], 'tarifario' => $cargos[$d][tarifario_id]));
                    $this->salida .= "                 <td align=\"center\">" . $cargos[$d][cantidad] . "&nbsp;&nbsp;&nbsp;<a href=\"$accion\"><img src=\"" . GetThemePath() . "/images/pmodificar.png\" border=\"0\"></a></td>";
                    $this->salida .= "             </tr>";
                    //los cargos equivalentes
                    $this->salida .= "   <tr class=\"modulo_list_oscuro\">";
                    $this->salida .= "      <td colspan=\"5\">";
                    $this->salida .= "         <table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\">";
                    $this->salida .= "          <tr align=\"center\" class=\"modulo_table_list_title\">";
                    $this->salida .= "            <td width=\"10%\">CARGO</td>";
                    $this->salida .= "            <td width=\"12%\">TARIFARIO</td>";
                    $this->salida .= "            <td>DESCRICPION</td>";
                    $this->salida .= "            <td width=\"10%\" align=\"center\">SEMANAS</td>";
                    $this->salida .= "          </tr>";
                    while ($cargos[$d][cargoc] == $cargos[$i][cargoc]) {
                        $cont = $this->ValidarContratoEqui($cargos[$d][tarifario_id], $cargos[$d][cargo], $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']);
                        if ($cont > 0) {
                            $this->salida .= "  <tr>";
                            $this->salida .= "   <tr class=\"modulo_list_claro\">";
                            $this->salida .= "      <td align=\"center\">" . $cargos[$d][cargo] . "</td>";
                            $this->salida .= "      <td align=\"center\">" . $cargos[$d][tarifario_id] . "</td>";
                            $this->salida .= "      <td align=\"center\">" . $cargos[$d][desc] . "</td>";
                            $sem = GetSemanasCotizadasCargo($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'], $cargos[$d][tarifario_id], $cargos[$d][cargo]);
                            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['SEMANAS'][$cargos[$d][desc]][$sem] = $sem;
                            $this->salida .= "      <td align=\"center\">" . $sem . "</td>";
                            $this->salida .= "  </tr>";
                            $var = $this->DatosTramite($cargos[$d][hc_os_solicitud_id]);
                            if (!empty($var)) {
                                for ($x = 0; $x < sizeof($var); $x++) {
                                    $_SESSION['AUTORIZACIONES']['TRAMITE'][$var[$x][autorizaciones_os_solicitudes_id]][$var[$x][hc_os_solicitud_id]] = $var[$x];
                                }
                            }
                        }
                        $d++;
                    }
                    $this->salida .= "        </table>";
                    $this->salida .= "      </td>";
                    $this->salida .= "   </tr>";
                    //fin varios cargos equivalentes
                    $this->salida .= "             <tr class=\"modulo_list_claro\">";
                    $this->salida .= "                 <td align=\"center\" class=\"modulo_table_list_title\">MEDICO: </td>";
                    if (!empty($cargos[$i][evolucion_id])) {
                        $this->salida .= "                 <td colspan=\"1\">" . $cargos[$i][nombre_tercero] . "&nbsp;&nbsp;  (" . $cargos[$i][descpro] . ")</td>";
                        $this->salida .= "                 <td colspan=\"1\" align=\"center\">" . RetornarWinOpenDatosEvolucionInactiva($cargos[$i][evolucion_id], 'EVOLUCION', '') . "</td>";
                    } else {
                        $med = $this->BuscarMedico($cargos[$i][hc_os_solicitud_id]);
                        $this->salida .= "<td colspan=\"2\">$med</td>";
                    }
                    $this->salida .= "                 <td align=\"center\" class=\"modulo_table_list_title\">SOLICITUD No. </td>";
                    $this->salida .= "                 <td align=\"center\" class=\"modulo_list_oscuro\">" . $cargos[$i][hc_os_solicitud_id] . "</td>";
                    $this->salida .= "             </tr>";
                }
                $this->salida .= "             <tr>";
                $this->salida .= "                 <td align=\"center\" class=\"modulo_table_list_title\">DIAGNOSTICO..:</td>";
                $this->salida .= "                 <td  colspan=\"4\" class=\"modulo_list_oscuro\">";
                $this->salida .= "                 <table width=\"100%\" align=\"left\" border=\"0\"  cellpadding=\"3\" class=\"normal_10\">";
                $arr = $this->BuscarDiagnostico2($cargos[$i][hc_os_solicitud_id]);
                if (!empty($arr)) {
                    for ($l = 0; $l < sizeof($arr); $l++) {
                        $this->salida .= "             <tr class=\"modulo_list_claro\">";
                        $this->salida .= "                 <td>" . $arr[$l][diagnostico_nombre] . "</td>";
                        $this->salida .= "             </tr>";
                    }
                } elseif (!empty($cargos[$i][evolucion_id])) {
                    $var = $this->BuscarDiagnostico($cargos[$i][evolucion_id]);
                    for ($j = 0; $j < sizeof($var); $j++) {
                        $this->salida .= "             <tr class=\"modulo_list_claro\">";
                        $this->salida .= "                 <td>" . $var[$j][diagnostico_nombre] . "</td>";
                        $this->salida .= "             </tr>";
                    }
                } else {
                    $this->salida .= "             <tr class=\"modulo_list_claro\">";
                    $this->salida .= "                 <td></td>";
                    $this->salida .= "             </tr>";
                }
                $this->salida .= "            </table>";
                $this->salida .= "                 </td>";
                $this->salida .= "             </tr>";
                $this->salida .= "             <tr>";
                $this->salida .= "      <td colspan=\"5\"></td>";
                $this->salida .= "   </tr>";
                $i = $d;
            }
            $this->salida .= "            </table>";
        }
    }

    /**
     *
     */
    function FormaCambiarCantidad($ant, $solicitud, $cargo, $tarifario) {
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'CambiarCantidad', array('ant' => $ant, 'solicitud' => $solicitud, 'cargo' => $cargo, 'tarifario' => $tarifario));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= ThemeAbrirTabla('CAMBIAR CANTIDAD');
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        $this->salida .= "            <table width=\"55%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td class=\"label\" colspan=\"2\" align=\"center\">CAMBIO DE CANTIDAD A AUTORIZAR</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td class=\"label\" width=\"35%\">CANTIDAD ACTUAL: </td>";
        $this->salida .= "                  <td align=\"left\" width=\"10%\">$ant</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td class=\"" . $this->SetStyle("Cantidad") . "\" width=\"23%\">CANTIDAD AUTORIZADA: </td>";
        $this->salida .= "                  <td align=\"left\" width=\"10%\"><input type=\"text\" name=\"Cantidad\" size=\"8\" value=\"" . $_REQUEST['Cantidad'] . "\" ></td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $this->salida .= "                  <td class=\"" . $this->SetStyle("Observacion") . "\" width=\"23%\">OBSERVACION: </td>";
        $this->salida .= "                  <td align=\"center\"><textarea name=\"Observacion\" cols=\"65\" rows=\"3\" class=\"textarea\">" . $_REQUEST['Observacion'] . "</textarea></td>";
        $this->salida .= "                  </tr>";
        $this->salida .= "       </table>";
        $this->salida .= "<br><table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"GUARDAR\"></td>";
        $this->salida .= "  </form>";
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarFormaAutorizacion');
        $this->salida .= "  <td align=\"center\"><form name=\"forma2\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
        $this->salida .= "      </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function BuscarTipoAutorizacion($TiposAuto, $Tipo) {
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
        for ($i = 0; $i < sizeof($TiposAuto); $i++) {
            if ($TiposAuto[$i][tipo_autorizacion] == $Tipo) {
                $this->salida .=" <option value=\"" . $TiposAuto[$i][tipo_autorizacion] . "\" selected>" . $TiposAuto[$i][descripcion] . "</option>";
            } else {
                $this->salida .=" <option value=\"" . $TiposAuto[$i][tipo_autorizacion] . "\">" . $TiposAuto[$i][descripcion] . "</option>";
            }
        }
    }

    /**
     *
     */
    function FormaAutorizacionTipo($Tipo) {
        $this->salida .= ThemeAbrirTabla('CENTRO AUTORIZACIONES - AUTORIZACION');
        $this->salida .= "    <table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "      <tr>";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "      </tr>";
        $this->salida .= "    </table>";
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'InsertarTipoAutorizacion', array('Tipo' => $Tipo));
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        //elige el tipo de autorizacion
        if ($Tipo == '01') {
            $this->AutorizacionTele();
        }
        if ($Tipo == '02') {
            $this->AutorizacionEscrita();
        }
        if ($Tipo == '04') {
            $usu = $this->BuscarUsuarios($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']);
            $this->AutorizacionInterna();
        }
        if ($Tipo == '05') {
            $this->AutorizacionElectronica();
        }
        if ($Tipo == '06') {
            $this->AutorizacionCertificadoCartera();
        }
        $this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr align=\"center\">";
        if ($Tipo == '04' AND empty($usu)) {
            
        } else {
            $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"ACEPTAR\"></td>";
        }
        $this->salida .= "      </form>";
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarFormaAutorizacion');
        $this->salida .= "      <td><form name=\"forma2\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></form></td>";
        $this->salida .= "      </tr>";
        $this->salida .= "     </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function AutorizacionCertificadoCartera() {
        $var = $this->BuscarAutorizaciones('autorizaciones_certificados');
        if ($var) {
            $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">";
            $this->salida .= "  <td align=\"center\">COD. AUTORIZACION</td>";
            $this->salida .= "  <td align=\"center\">RESPONSABLE</td>";
            $this->salida .= "  <td>OBSERVACIONES</td>";
            $this->salida .= "  <td></td>";
            $this->salida .= "  </tr>";
            for ($i = 0; $i < sizeof($var); $i++) {
                if ($i % 2)
                    $estilo = 'modulo_list_claro';
                else
                    $estilo = 'modulo_list_oscuro';
                $this->salida .= "  <tr class=\"$estilo\">";
                $this->salida .= "  <td>" . $var[$i][codigo_autorizacion] . "</td>";
                $this->salida .= "  <td>" . $var[$i][responsable] . "</td>";
                $this->salida .= "  <td>" . $var[$i][observaciones] . "</td>";
                $msg = 'Esta seguro que desea Eliminar La Autorización.';
                $arreglo = array('tabla' => 'autorizaciones_certificados', 'campo' => 'autorizacion_certificado_id', 'id' => $var[$i][autorizacion_certificado_id], 'TipoAutorizacion' => '06');
                $accion = ModuloGetURL('app', 'Autorizacion', 'user', 'LlamaConfirmarAccion', array('c' => 'app', 'm' => 'Autorizacion', 'me2' => 'LlamarFormaAutorizacionTipo', 'me' => 'EliminarAutorizaciones', 'mensaje' => $msg, 'titulo' => 'ELIMINAR AUTORIZACION CERTIFICADO DE CARTERA', 'arreglo' => $arreglo, 'boton1' => 'ACEPTAR', 'boton2' => 'CANCELAR'));
                $this->salida .= "  <td width=\"3%\" align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"" . GetThemePath() . "/images/elimina.png\"></a></td>";
                $this->salida .= "  </tr>";
            }
            $this->salida .= "  </table><br>";
        }
        $this->salida .= "<table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"modulo_table_list_title\">DATOS AUTORIZACION CERTIFICADO CARTERA</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td>";
        $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("CodAuto") . "\">COD. AUTORIZACION: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"CodAuto\" size=\"12\" value=\"" . $_REQUEST['CodAuto'] . "\"></td>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("Responsable") . "\">RESPONSABLE: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"Responsable\" size=\"20\" value=\"" . $_REQUEST['Responsable'] . "\"></td>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("Validez") . "\">VALIDEZ: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"Validez\" size=\"12\" value=\"" . $_REQUEST['Validez'] . "\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
        $this->salida .= "&nbsp;&nbsp;" . ReturnOpenCalendario('forma', 'Validez', '/') . "</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("Observaciones") . "\">OBSERVACIONES: </td>";
        $this->salida .= "  <td colspan=\"4\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">" . $_REQUEST['Observaciones'] . "</textarea></td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
    }

    /**
     *
     */
    function AutorizacionTele() {
        $var = $this->BuscarAutorizaciones('autorizaciones_telefonicas');
        if ($var) {
            $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">";
            $this->salida .= "  <td align=\"center\">COD. AUTORIZACION</td>";
            $this->salida .= "  <td align=\"center\">RESPONSABLE</td>";
            $this->salida .= "  <td>OBSERVACIONES</td>";
            $this->salida .= "  <td></td>";
            $this->salida .= "  </tr>";
            for ($i = 0; $i < sizeof($var); $i++) {
                if ($i % 2)
                    $estilo = 'modulo_list_claro';
                else
                    $estilo = 'modulo_list_oscuro';
                $this->salida .= "  <tr class=\"$estilo\">";
                $this->salida .= "  <td>" . $var[$i][codigo_autorizacion] . "</td>";
                $this->salida .= "  <td>" . $var[$i][responsable] . "</td>";
                $this->salida .= "  <td>" . $var[$i][observaciones] . "</td>";
                $msg = 'Esta seguro que desea Eliminar La Autorización.';
                $arreglo = array('tabla' => 'autorizaciones_telefonicas', 'campo' => 'autorizacion_telefonica_id', 'id' => $var[$i][autorizacion_telefonica_id], 'TipoAutorizacion' => '01');
                $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamaConfirmarAccion', array('c' => 'app', 'm' => 'Autorizacion', 'me2' => 'LlamarFormaAutorizacionTipo', 'me' => 'EliminarAutorizaciones', 'mensaje' => $msg, 'titulo' => 'ELIMINAR AUTORIZACION TELEFONICA', 'arreglo' => $arreglo, 'boton1' => 'ACEPTAR', 'boton2' => 'CANCELAR'));
                $this->salida .= "  <td width=\"3%\" align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"" . GetThemePath() . "/images/elimina.png\"></a></td>";
                $this->salida .= "  </tr>";
            }
            $this->salida .= "  </table><br>";
        }
        $this->salida .= "<table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"modulo_table_list_title\">DATOS AUTORIZACION TELEFONICA</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td>";
        $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("CodAuto") . "\">COD. AUTORIZACION: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"CodAuto\" size=\"12\" value=\"" . $_REQUEST['CodAuto'] . "\"></td>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("Responsable") . "\">RESPONSABLE: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"Responsable\" size=\"20\" value=\"" . $_REQUEST['Responsable'] . "\"></td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("Observaciones") . "\">OBSERVACIONES: </td>";
        $this->salida .= "  <td colspan=\"3\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">" . $_REQUEST['Observaciones'] . "</textarea></td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
    }

    /**
     *
     */
    function AutorizacionEscrita() {
        $var = $this->BuscarAutorizaciones('autorizaciones_escritas');
        if ($var) {
            $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">";
            $this->salida .= "  <td>COD. AUTORIZACION</td>";
            $this->salida .= "  <td>VALIDEZ</td>";
            $this->salida .= "  <td>OBSERVACIONES</td>";
            $this->salida .= "  <td></td>";
            $this->salida .= "  </tr>";
            for ($i = 0; $i < sizeof($var); $i++) {
                if ($i % 2)
                    $estilo = 'modulo_list_claro';
                else
                    $estilo = 'modulo_list_oscuro';
                $this->salida .= "  <tr class=\"$estilo\">";
                $this->salida .= "  <td align=\"center\">" . $var[$i][codigo_autorizacion] . "</td>";
                $this->salida .= "  <td align=\"center\">" . $var[$i][validez] . "</td>";
                $this->salida .= "  <td>" . $var[$i][observaciones] . "</td>";
                $msg = 'Esta seguro que desea Eliminar La Autorización.';
                $arreglo = array('tabla' => 'autorizaciones_escritas', 'campo' => 'autorizacion_escrita_id', 'id' => $var[$i][autorizacion_escrita_id], 'TipoAutorizacion' => '02');
                $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamaConfirmarAccion', array('c' => 'app', 'm' => 'Autorizacion', 'me2' => 'LlamarFormaAutorizacionTipo', 'me' => 'EliminarAutorizaciones', 'mensaje' => $msg, 'titulo' => 'ELIMINAR AUTORIZACION ESCRITA', 'arreglo' => $arreglo, 'boton1' => 'ACEPTAR', 'boton2' => 'CANCELAR'));
                $this->salida .= "  <td width=\"3%\" align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"" . GetThemePath() . "/images/elimina.png\"></a></td>";
                $this->salida .= "  </tr>";
            }
            $this->salida .= "  </table><br>";
        }
        $this->salida .= "<br><table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"modulo_table_list_title\">DATOS AUTORIZACION ESCRITA</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td>";
        $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("CodAuto") . "\">COD. AUTORIZACION: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"CodAuto\" size=\"12\" value=\"" . $_REQUEST['CodAuto'] . "\"></td>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("Validez") . "\">VALIDEZ: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"Validez\" size=\"12\" value=\"" . $_REQUEST['Validez'] . "\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
        $this->salida .= "&nbsp;&nbsp;" . ReturnOpenCalendario('forma', 'Validez', '/') . "</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("Observaciones") . "\">OBSERVACIONES: </td>";
        $this->salida .= "  <td colspan=\"3\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">" . $_REQUEST['Observaciones'] . "</textarea></td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
    }

    /**
     *
     */
    function AutorizacionInterna() {
        $var = $this->BuscarAutorizaciones('autorizaciones_por_sistema');
        if ($var) {
            $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">";
            $this->salida .= "  <td>COD. AUTORIZACION</td>";
            $this->salida .= "  <td>USUARIO</td>";
            $this->salida .= "  <td>OBSERVACIONES</td>";
            $this->salida .= "  <td></td>";
            $this->salida .= "  </tr>";
            for ($i = 0; $i < sizeof($var); $i++) {
                if ($i % 2)
                    $estilo = 'modulo_list_claro';
                else
                    $estilo = 'modulo_list_oscuro';
                $this->salida .= "  <tr class=\"$estilo\">";
                $this->salida .= "  <td align=\"center\" width=\"10%\">" . $var[$i][autorizacion_por_sistema_id] . "</td>";
                $this->salida .= "  <td align=\"center\">" . $var[$i][nombre] . "</td>";
                $this->salida .= "  <td>" . $var[$i][observaciones] . "</td>";
                $msg = 'Esta seguro que desea Eliminar La Autorización.';
                $arreglo = array('tabla' => 'autorizaciones_por_sistema', 'TipoAutorizacion' => '04', 'campo' => 'autorizacion_por_sistema_id', 'id' => $var[$i][autorizacion_por_sistema_id]);
                $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamaConfirmarAccion', array('c' => 'app', 'm' => 'Autorizacion', 'me2' => 'LlamarFormaAutorizacionTipo', 'me' => 'EliminarAutorizaciones', 'mensaje' => $msg, 'titulo' => 'ELIMINAR AUTORIZACION ESCRITA', 'arreglo' => $arreglo, 'boton1' => 'ACEPTAR', 'boton2' => 'CANCELAR'));
                $this->salida .= "  <td width=\"3%\" align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"" . GetThemePath() . "/images/elimina.png\"></a></td>";
                $this->salida .= "  </tr>";
            }
            $this->salida .= "  </table><br>";
        }
        $this->salida .= "<br><table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"modulo_table_list_title\">DATOS AUTORIZACION INTERNA</td>";
        $this->salida .= "  </tr>";
        //usuarios
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"label_error\" align=\"center\">";
        $usu = $this->BuscarUsuarios($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']);
        if ($usu) {
            $this->salida .= "      <table border=\"0\" width=\"30%\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .= "          <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "            <td>USUARIOS AUTORIZADORES</td>";
            $this->salida .= "            <td></td>";
            $this->salida .= "          </tr>";
            for ($i = 0; $i < sizeof($usu); $i++) {
                $this->salida .= "          <tr class=\"modulo_list_claro\">";
                $this->salida .= "            <td>" . $usu[$i][nombre] . "</td>";
                $this->salida .= "            <td align=\"center\"><input type=\"radio\" value=\"" . $usu[$i][usuario_id] . "\" name=\"Responsable\"></td>";
                $this->salida .= "          </tr>";
            }
            $this->salida .= "       </table><br>";
            $this->salida .= "  </td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td>";
            $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td class=\"" . $this->SetStyle("Observaciones") . "\">OBSERVACIONES: </td>";
            $this->salida .= "  <td colspan=\"3\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">" . $_REQUEST['Observaciones'] . "</textarea></td>";
            $this->salida .= "  </tr>";
            $this->salida .= "  </table>";
        } else {
            $this->salida .= "NO HAY USUARIO AUTORIZADORES PARA ESTE PLAN";
        }
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
    }

    /**
     *
     */
    function AutorizacionElectronica() {
        $var = $this->BuscarAutorizaciones('autorizaciones_electronicas');
        if ($var) {
            $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">";
            $this->salida .= "  <td>COD. AUTORIZACION</td>";
            $this->salida .= "  <td>VALIDEZ</td>";
            $this->salida .= "  <td>OBSERVACIONES</td>";
            $this->salida .= "  <td></td>";
            $this->salida .= "  </tr>";
            for ($i = 0; $i < sizeof($var); $i++) {
                if ($i % 2)
                    $estilo = 'modulo_list_claro';
                else
                    $estilo = 'modulo_list_oscuro';
                $this->salida .= "  <tr class=\"$estilo\">";
                $this->salida .= "  <td align=\"center\">" . $var[$i][codigo_autorizacion] . "</td>";
                $this->salida .= "  <td align=\"center\">" . $var[$i][validez] . "</td>";
                $this->salida .= "  <td>" . $var[$i][observaciones] . "</td>";
                $msg = 'Esta seguro que desea Eliminar La Autorización.';
                $arreglo = array('tabla' => 'autorizaciones_electronicas', 'campo' => 'autorizacion_electronica_id', 'id' => $var[$i][autorizacion_electronica_id], 'TipoAutorizacion' => '05');
                $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamaConfirmarAccion', array('c' => 'app', 'm' => 'Autorizacion', 'me2' => 'LlamarFormaAutorizacionTipo', 'me' => 'EliminarAutorizaciones', 'mensaje' => $msg, 'titulo' => 'ELIMINAR AUTORIZACION ESCRITA', 'arreglo' => $arreglo, 'boton1' => 'ACEPTAR', 'boton2' => 'CANCELAR'));
                $this->salida .= "  <td width=\"3%\" align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"" . GetThemePath() . "/images/elimina.png\"></a></td>";
                $this->salida .= "  </tr>";
            }
            $this->salida .= "  </table><br>";
        }
        $this->salida .= "<table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"modulo_table_list_title\">DATOS AUTORIZACION ELECTRONICA</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td>";
        $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("CodAuto") . "\">COD. AUTORIZACION: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"CodAuto\" size=\"12\" value=\"" . $_REQUEST['CodAuto'] . "\"></td>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("Validez") . "\">VALIDEZ: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"Validez\" size=\"12\" value=\"" . $_REQUEST['Validez'] . "\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
        $this->salida .= "&nbsp;&nbsp;" . ReturnOpenCalendario('forma', 'Validez', '/') . "</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("Observaciones") . "\">OBSERVACIONES: </td>";
        $this->salida .= "  <td colspan=\"3\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">" . $_REQUEST['Observaciones'] . "</textarea></td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
    }

    /**
     *
     */
    function FormaCambiar($TipoAfiliado, $Nivel, $s) {
        $this->salida .= ThemeAbrirTabla('CENTRO AUTORIZACIONES - CAMBIAR DATOS AFILIADO');
        if (!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'])) {
            $this->FormaCamposBD();
            /* $a=ImplodeArrayAssoc($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
              $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
              $this->salida .= "  <tr>";
              $this->salida .= "  <td colspan=\"2\">";
              $this->salida .= "            <table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
              $this->salida .= "               <tr>";
              $this->salida .= "                  <td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\">DATOS AFILIADO EN LA BASE DE DATOS DE LA ENTIDAD</td>";
              $this->salida .= "               </tr>";
              $arreglon=ExplodeArrayAssoc($a);
              $i=0;
              foreach($arreglon as $k => $v)
              {
              if($i % 2) {  $estilo="modulo_list_claro";  }
              else {  $estilo="modulo_list_oscuro";   }
              $this->salida .= "         <tr class=\"$estilo\">";
              $this->salida .= "            <td align=\"center\">$k</td>";
              $this->salida .= "            <td align=\"center\">$v</td>";
              $this->salida .= "        </tr>";
              $i++;
              }
              $this->salida .= "           </table>";
              $this->salida .= "               </td>";
              $this->salida .= "               </tr>";
              $this->salida .= "           </table><BR>"; */
        }
        $this->salida .= "            <table width=\"50%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "               </tr>";
        $this->salida .= "           </table>";
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'GuardarCambiosAfiliado');
        $this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
        $tipo_afiliado = $this->Tipo_Afiliado();
        $this->salida .= "          <tr>";
        if (sizeof($tipo_afiliado) > 1) {
            $this->salida .= "               <td class=\"" . $this->SetStyle("TipoAfiliado") . "\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
            $this->BuscarIdTipoAfiliado($tipo_afiliado, $TipoAfiliado);
            $this->salida .= "              </select></td>";
        } else {
            $this->salida .= "            <td class=\"" . $this->SetStyle("TipoAfiliado") . "\">TIPO AFILIADO: </td>";
            $NomAfi = $this->NombreAfiliado($TipoAfiliado);
            $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"" . $_SESSION['SOLICITUDAUTORIZACION']['tipo_afiliado_id'] . "\">" . $NomAfi[tipo_afiliado_nombre] . "</td>";
            $this->salida .= "            <td></td>";
        }
        $niveles = $this->Niveles();
        if (sizeof($niveles) > 1) {
            $this->salida .= "               <td class=\"" . $this->SetStyle("Nivel") . "\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
            $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
            for ($i = 0; $i < sizeof($niveles); $i++) {
                if ($niveles[$i][rango] == $Nivel) {
                    $this->salida .=" <option value=\"" . $niveles[$i][rango] . "\" selected>" . $niveles[$i][rango] . "</option>";
                } else {
                    $this->salida .=" <option value=\"" . $niveles[$i][rango] . "\">" . $niveles[$i][rango] . "</option>";
                }
            }
        } else {
            $this->salida .= "             <td class=\"" . $this->SetStyle("Nivel") . "\">RANGO: </td>";
            $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"" . $niveles[0][rango] . "\">" . $niveles[0][rango] . "</td>";
            $this->salida .= "            <td></td>";
        }
        $this->salida .= "            <td class=\"" . $this->SetStyle("Semanas") . "\" width=\"23%\">SEMANAS COTIZADAS: </td>";
        $this->salida .= "            <td align=\"left\" width=\"10%\"><input type=\"text\" name=\"Semanas\" size=\"8\" value=\"" . $s . "\" ></td>";
        $this->salida .= "          </tr>";
        $this->salida .= "          <tr>";
        $this->salida .= "            <td colspan=\"6\" align=\"center\">OBSERVACION: <textarea name=\"Observacion\" cols=\"65\" rows=\"3\" class=\"textarea\"></textarea></td>";
        $this->salida .= "          </tr>";
        $this->salida .= "       </table><br>";
        $this->salida .= "<br><table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"GUARDAR\"></td>";
        $this->salida .= "  </form>";
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarFormaAutorizacion');
        $this->salida .= "  <td align=\"center\"><form name=\"forma2\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
        $this->salida .= "      </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaJustificar($auto) {
        $this->salida .= ThemeAbrirTabla('CENTRO AUTORIZACIONES - JUSTIFICAR NO AUTORIAZCION');
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'JustificarNoAutorizacion', array('auto' => $auto));
        $this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $jus = $this->CallMetodoExterno('app', 'Autorizacion_Solicitud', 'user', 'Justificacion');
        $this->ComboJustificacion();
        $this->salida .= " <table border=\"0\" width=\"90%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"label_error\" colspan=\"2\" align=\"center\">DEBE JUSTIFICAR PORQUE NO AUTORIZO</td>";
        $this->salida .= "  </tr>";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"" . $this->SetStyle("Observaciones") . "\">OBSERVACIONES: </td>";
        $this->salida .= "  <td class=\"label\">JUSTIFICACION: </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td><textarea  cols=\"85\" rows=\"7\" class=\"textarea\" name=\"Observaciones\">$Observaciones</textarea></td>";
        $this->salida .= "  <td><select name=\"Tipo\" class=\"select\" onchange=\"ComboJustificacion(this.value,this.form)\">";
        $this->salida .=" <option value=\"-1\">-----SELECCIONE-----</option>";
        for ($j = 0; $j < sizeof($jus); $j++) {
            $f = $r = '';
            if ($jus[$j][justificacion]) {
                $f = 'JUSTIFICACION: ' . $jus[$j][justificacion];
            }
            if ($jus[$j][recomendaciones]) {
                $r = 'RECOMENDACIONES: ' . $jus[$j][recomendaciones];
            }
            $this->salida .=" <option value=\"" . $f . "\n\n" . $r . "\">" . $jus[$j][nombre] . "</option>";
        }
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><BR>";
        $this->salida .= "<br><table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"GUARDAR\"></td>";
        $this->salida .= "  </form>";
        if (!empty($_SESSION['AUTORIZACIONES']['TRAMITEX']['SINAUTO'])) {
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'FormaAfiliado');
        } else {
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarFormaAutorizacion');
        }
        $this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"CANCELAR\"></td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

//---------------------------------ORDENES SERVICIO-------------------------------------

    /*
     * Esta funcion realiza la busqueda de cumplimientos de ordenes de servicio
     * según filtros como tipo, documento, nombre y plan
     * @return boolean
     */
    function FormaMetodoBuscarOS($arr) {
        $this->salida.= ThemeAbrirTabla('LISTA ORDENES DE SERVICIO');
        if (empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'BuscarOrden');
            $this->Encabezado();
        } else {
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'BuscarOrdenTodos');
        }
        $dateDEA = date("Y-m-d");
        $this->salida .= "<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA: -22- </td>";
        $this->salida .= "<td align = left >SELECCIONE LA FECHA:</td>";
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
        $this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
        $this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\"></td></tr>";
        $this->salida .= "<tr><td class=\"label\">ORDEN No.</td><td><input type=\"text\" class=\"input-text\" name=\"Orden\"></td></tr>";
        
        if(empty($_REQUEST['DiaEspe'])){
            $this->salida .= "<tr><td class=\"label\">FECHA</td><td><input type=\"text\" readonly class=\"input-text\" name=\"Fecha\" value = " . $dateDEA . "></td></tr>";
        }else{
            $this->salida .= "<tr><td class=\"label\">FECHA</td><td><input type=\"text\" readonly class=\"input-text\" name=\"Fecha\" value = " . $_REQUEST['DiaEspe'] . "></td></tr>";
        }
        
        
        $this->salida .= "<tr class=\"label\">";
        $this->salida .= "<td align = left >TODAS LAS FECHAS</td>";
        $this->salida.="  <td align=\"left\"><input type = checkbox name= 'allfecha' onclick=Revisar(this.form,this.checked)></td>";
        $this->salida .= "</tr>";

        $responsables = $this->Planes();
        if (!empty($responsables)) {
            $this->salida .= "				       <tr><td class=\"" . $this->SetStyle("plan") . "\">PLAN: </td><td><select name=\"plan\" class=\"select\">";
            if ($responsables[0][todos] == 1) {
                $this->salida .=" <option value=\"-1\">-------TODOS LOS PLANES-------</option>";
            }
            for ($i = 0; $i < sizeof($responsables); $i++) {
                if ($responsables[$i][plan_id] == $_REQUEST['plan']) {
                    $this->salida .=" <option value=\"" . $responsables[$i][plan_id] . "\" selected>" . $responsables[$i][plan_descripcion] . "</option>";
                } else {
                    $this->salida .=" <option value=\"" . $responsables[$i][plan_id] . "\">" . $responsables[$i][plan_descripcion] . "</option>";
                }
            }
            $this->salida .= "              </select></td></tr>";
        } else {
            $this->salida .= "				       <tr><td class=\"" . $this->SetStyle("plan") . "\">PLAN: </td><td>";
            $this->salida .="NO TIENE PLANES ASOCIADOS</td></tr>";
        }

        $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
        $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
        $this->salida .= "</form>";
        /* if(empty($_SESSION['CENTROAUTORIZACION']['TODOS']))
          { */ $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'FormaMenus');  //}
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
            $a = explode("-", $_SESSION['CITASMES'][0]);
            $year = $_REQUEST['year'] = $a[0];
            $this->AnosAgenda(True, $_REQUEST['year']);
        } else {
            $this->AnosAgenda(true, $_REQUEST['year']);
            $year = $_REQUEST['year'];
        }
        $this->salida .= "</select></td>";
        $this->salida .="<td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
        if (empty($_REQUEST['meses'])) {
            $a = explode("-", $_SESSION['CITASMES'][0]);
            if (empty($a[0])) {
                $mes = $_REQUEST['meses'] = date("m");
                $year = date("Y");
            } else {
                $mes = $_REQUEST['meses'] = $a[1];
            }
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
        $this->salida .= "       </td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>";
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        if (!empty($arr)) {
            $this->salida .= "     <br><table width=\"70%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            if (empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
                $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
                $this->salida .= "        <td colspan=\"3\">PLAN  " . $_SESSION['CENTROAUTORIZACION']['PLANDES'] . " RESPONSABLE " . $_SESSION['CENTROAUTORIZACION']['RESPONSABLE'] . "</td>";
                $this->salida .= "      </tr>";
            }
            $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td width=\"25%\">IDENTIFICACION</td>";
            $this->salida .= "        <td width=\"45%\">PACIENTE</td>";
            $this->salida .= "        <td width=\"8%\"></td>";
            $this->salida .= "      </tr>";
            for ($i = 0; $i < sizeof($arr); $i++) {
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td align=\"center\">" . $arr[$i][tipo_id_paciente] . " " . $arr[$i][paciente_id] . "</td>";
                $this->salida .= "        <td>" . $arr[$i][nombre] . "</td>";
                $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleOS', array('tipoid' => $arr[$i][tipo_id_paciente], 'pacienteid' => $arr[$i][paciente_id]));
                $this->salida .= "        <td align=\"center\"><a href=\"$accion\"><img src=\"" . GetThemePath() . "/images/flecha.png\" border=\"0\">&nbsp;&nbsp;VER</a></td>";
                $this->salida .= "      </tr>";
            }
            $this->salida .= "  </table>";
            $this->conteo = $_SESSION['SPY3'];
            $this->salida .=$this->RetornarBarraOS();
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function RetornarBarraOS() {
        $this->conteo;
        $this->limit;

        if ($this->limit >= $this->conteo) {
            return '';
        }
        $paso = $_REQUEST['pasoOS'];
        if (is_null($paso)) {
            $paso = 1;
        }
        $vec = '';
        foreach ($_REQUEST as $v => $v1) {
            if ($v != 'modulo' and $v != 'metodo' and $v != 'SIIS_SID' and $v != 'Of') {
                $vec[$v] = $v1;
            }
        }

        if (empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'BuscarOrden', $vec);
        } else {
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'BuscarOrdenTodos', $vec);
        }
        //$accion=ModuloGetURL('app','CentroAutorizacion','user','BuscarSolicitud',$vec);
        $barra = $this->CalcularBarra($paso);
        $numpasos = $this->CalcularNumeroPasos($this->conteo);
        $colspan = 1;

        $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if ($paso > 1) {
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset(1) . "&pasoOS=1'>&lt;</a></td>";
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso - 1) . "&pasoOS=" . ($paso - 1) . "'>&lt;&lt;</a></td>";
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
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($i) . "&pasoOS=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso + 1) . "&pasoOS=" . ($paso + 1) . "' >&gt;&gt;</a></td>";
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($numpasos) . "&pasoOS=$numpasos'>&gt;</a></td>";
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
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($i) . "&pasoOS=$i' >$i</a></td>";
                }
                $colspan++;
            }
            if ($paso != $numpasos) {
                $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso + 1) . "&pasoOS=" . ($paso + 1) . "' >&gt;&gt;</a></td>";
                $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($numpasos) . "&pasoOS=$numpasos'>&gt;</a></td>";
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
        $ano = $anoActual;
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

    /**
     *
     */
    function FormaDetalleOS($var, $vars, $datos='', $control='') {
        $this->salida .= ThemeAbrirTabla(' ');

        $this->salida.=$control;

        $mostrar = "\n<script language='javascript'>\n";
        $mostrar.="  function JavaRef(accionF, AgruOrdenes)\n";
        $mostrar.="  {\n";
        $mostrar.="         accionF = accionF + '&AgruOrdenes=' + AgruOrdenes;\n";
        $mostrar.="         window.location = accionF;\n";
        $mostrar.="  }\n";

        $mostrar.="  function AgruparFecha(obj,cant)\n";
        $mostrar.="  {\n";
        //
        $mostrar .= "       elementos = document.getElementsByName(obj.name);\n";
        $mostrar .= "       elementosS = document.getElementsByName('AgruNumeroS');\n";
        $mostrar .= "       AgruOrdenes.value = '0';\n";
        $mostrar .= "       for(da=0; da < cant; da++)\n";
        $mostrar .= "       {\n";
        $mostrar .= "           if(elementos[da].checked == true)\n";
        $mostrar .= "           {\n";
        $mostrar .= "               if(da==0)\n";
        $mostrar .= "               {\n";
        $mostrar .= "                   AgruOrdenes.value = elementos[da].value;\n";
        $mostrar.="                 }else{\n";
        $mostrar .= "                   if(AgruOrdenes.value == '0')\n";
        $mostrar .= "                   {\n";
        $mostrar .= "                       AgruOrdenes.value = elementos[da].value;\n";
        $mostrar.="                     }else{\n";
        $mostrar .= "                       AgruOrdenes.value = AgruOrdenes.value + ', ' + elementos[da].value;\n";
        $mostrar.="                     }\n";
        $mostrar.="                 }\n";
        $mostrar.="             }\n";
        $mostrar .= "       }\n";
        $mostrar.="  }\n";

        if (!empty($datos)) {
            if ($control == 3) {
                $RUTA = $_ROOT . "cache/ordenservicio" . $datos['orden'] . ".pdf";
            }
            $mostrar.="var rem=\"\";\n";
            $mostrar.="  function abreVentana()\n";
            $mostrar.="  {\n";
            $mostrar.="     var nombre='';\n";
            $mostrar.="     var url2='';\n";
            $mostrar.="     var str='';\n";
            $mostrar.="     var ALTO=screen.height;\n";
            $mostrar.="     var ANCHO=screen.width;\n";
            $mostrar.="     var nombre='REPORTE';\n";
            $mostrar.="     var str ='ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes';\n";
            $mostrar.="     var url2 ='$RUTA';\n";
            $mostrar.="     rem = window.open(url2, nombre, str);\n";
            $mostrar.="  };\n";
        }
        $mostrar.="</script>\n";



        $this->salida.="$mostrar";
        $this->salida.="<BODY onload=abreVentana();>";
        if (empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            $this->Encabezado();
        }
        $reporte = new GetReports();
        if (!empty($var) AND !empty($var[0][plan_id])) {
            $this->salida .= "     <table width=\"70%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr>";
            $this->salida .= "        <td class=\"modulo_table_list_title\" colspan=\"4\" align=\"left\">DATOS PACIENTE </td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr>";
            $this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION: </td><td width=\"20%\" class=\"modulo_list_claro\">" . $var[0][tipo_id_paciente] . " " . $var[0][paciente_id] . "</td>";
            $this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">PACIENTE:</td><td width=\"40%\" class=\"modulo_list_claro\">" . $var[0][nombre] . "</td>";
            $this->salida .= "      </tr>";
            $this->salida .= "       </table><BR>";
            $bd = $this->BuscarInfoPlan($var[0][plan_id], $var[0][tipo_id_paciente], $var[0][paciente_id]);
            if (!empty($bd)) {
                $this->SetJavaScripts('DatosBD');
                $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"50%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "  <tr>";
                $this->salida .= "   <td align=\"center\">" . RetornarWinOpenDatosBD($var[0][tipo_id_paciente], $var[0][paciente_id], $var[0][plan_id]) . "</td>";
                $this->salida .= "  </tr>";
                $this->salida .= "</table><BR>";
            }
            $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
            $this->salida .= "          <tr><td><td><input type = 'hidden' name = 'AgruOrdenes' id = 'AgruOrdenes' value = 0><td><tr>";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "       </table>";
            $f = 0;
            for ($i = 0; $i < sizeof($var);) {
                $d = $i;
                if ($var[$i][plan_id] == $var[$d][plan_id]) {
                    $this->salida .= ThemeAbrirTabla('ORDENES SERVICIO PLAN ' . $var[$d][plan_descripcion], 850);
                }
                while ($var[$i][plan_id] == $var[$d][plan_id]) {
                    
                    
                    $this->salida .= "   <table width=\"95%\" border=\"1\" align=\"center\" >";
                    $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "      </tr>";
                    $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "        <td colspan=\"5\" align=\"left\">
                                              <input type='checkbox' name='ref' id='" . $d . "' value=" . $var[$d][orden_servicio_id] . "//" . $var[$d][numero_orden_id] . " onclick = \"AgruparFecha(this, " . count($var) . ");\">Seleccione Orden de Servicio Número " . $var[$d][orden_servicio_id] . " para refrendar.</td>";
                    $this->salida .= "      </tr>";
                    $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "        <td colspan=\"5\" align=\"left\">NUMERO DE ORDEN DE SERVICIO " . $var[$d][orden_servicio_id] . "</td>";
                    $this->salida .= "      </tr>";
                    $this->salida .= "      <tr>";
                    $this->salida .= "        <td colspan=\"5\" class=\"modulo_list_claro\">";
                    $this->salida .= "            <table width=\"100%\" border=\"1\" align=\"center\" class=\"\">";
                    $this->salida .= "                <tr>";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">TIPO AFILIADO: </td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][tipo_afiliado_nombre] . "</td>";
                    $this->salida .= "                    <td width=\"7%\" class=\"modulo_table_list_title\">RANGO: </td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][rango] . "</td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">SEMANAS COT.: </td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][semanas_cotizadas] . "</td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">SERVICIO: </td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][desserv] . "</td>";
                    $this->salida .= "                </tr>";
                    $this->salida .= "                <tr>";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">AUT. INT.: </td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][autorizacion_int] . "</td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">AUT. EXT: </td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][autorizacion_ext] . "</td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">AUTORIZADOR: </td>";
                    $this->salida .= "                    <td width=\"5%\" colspan=\"3\" class=\"hc_table_submodulo_list_title\">" . $var[$d][autorizador] . "</td>";
                    $this->salida .= "                </tr>";
                    $this->salida .= "                <tr>";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">FECHA AUTO.: </td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $this->FechaStamp($var[$d][fecha_registro]) . "</td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">FECHA. REC: </td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\"></td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">QUIEN RECIBE: </td>";
                    $this->salida .= "                    <td width=\"5%\" colspan=\"3\" class=\"hc_table_submodulo_list_title\"></td>";
                    $this->salida .= "                </tr>";
                    $this->salida .= "             </table>";
                    $this->salida .= "        </td>";
                    $this->salida .= "      </tr>";
                    while ($var[$f][orden_servicio_id] == $var[$d][orden_servicio_id]) {
                        $tipsol = "";
                        $obser = $this->ObtenerTipoSolicitud($var[$f][hc_os_solicitud_id]);
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
                            $obser = $this->ObtenerObservacionSolicitud($var[$f][hc_os_solicitud_id], $tabla);
                            if (count($obser) > 0)
                                $cadobse = $obser[0][observacion];
                        }

                        $this->salida .= "      <tr>";
                        $this->salida .= "        <td colspan=\"5\">";
                        $this->salida .= "        <table width=\"99%\" border=\"0\" align=\"center\">";
                        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                        $this->salida .= "        <td width=\"6%\">ITEM</td>";
                        $this->salida .= "        <td width=\"6%\">FECHA.</td>";
                        $this->salida .= "        <td width=\"6%\">CANT.</td>";
                        $this->salida .= "        <td width=\"10%\">CARGO</td>";
                        $this->salida .= "        <td width=\"45%\">DESCRICPION</td>";
                        $this->salida .= "        <td width=\"20%\">PROVEEDOR</td>";
                        $this->salida .= "      </tr>";
                        if ($f % 2) {
                            $estilo = "modulo_list_claro";
                        } else {
                            $estilo = "modulo_list_oscuro";
                        }
                        $this->salida .= "      <tr class=\"$estilo\">";
                        $this->salida .= "        <td align=\"center\">" . $var[$f][numero_orden_id] . "</td>";
                        $this->salida .= "        <td align=\"center\">" . $this->FechaStamp($var[$f][fecha_solicitud]) . "</td>";
                        /* elseif(!empty($var[$f][fechamanu]))
                          {  $this->salida .= "        <td align=\"center\">".$this->FechaStamp($var[$f][fechamanu])."</td>";  }
                          elseif(!empty($var[$f][fechacita]))
                          {  $this->salida .= "        <td align=\"center\">".'es cita'."</td>";  }
                          else
                          {  $this->salida .= "        <td align=\"center\">".'es cita'."</td>";  }
                         */
                        $this->salida .= "        <td align=\"center\">" . $var[$f][cantidad] . "</td>";
                        /* if(!empty($var[$f][cargo])){  $cargo=$var[$f][cargo];  }
                          else {  $cargo=$var[$f][cargoext];   } */
                        $cargo = $var[$f][cargo_cups];
                        $this->salida .= "        <td align=\"center\">" . $cargo . "</td>";
                        $this->salida .= "        <td>" . $var[$f][descripcion] . " " . $var[$f][desc_especialidad] . "</td>";
                        $p = '';
                        if (!empty($var[$f][departamento])) {
                            $p = 'DPTO. ' . $var[$f][desdpto];
                            $id = $var[$f][departamento];
                            $tipo = 'i';
                        } else {
                            $p = $var[$f][planpro];
                            $id = $var[$f][plan_proveedor_id];
                            $tipo = 'e';
                        }

                        $bdDA = $this->TraerPlanD($var[$f][hc_os_solicitud_id]);
                        $tarifario_iddea="";
                        $tarifario_dedea="";
                        $subtarifario_iddea="";
                        $subtarifario_dedea="";
                        if (count($bdDA) > 0){
                            $tarifario_iddea = $bdDA['grupo_tarifario_id'];
                            $tarifario_dedea = $bdDA['grupo_tarifario_descripcion'];
                            $subtarifario_iddea = $bdDA['subgrupo_tarifario_id'];
                            $subtarifario_dedea = $bdDA['subgrupo_tarifario_descripcion'];
                        }
 
                        
                        $this->salida .= "        <td align=\"center\">" . $p . "</td>";
                        $this->salida .= "      </tr>";
                        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
                        $this->salida .= "        <td colspan=\"6\">";
                        $this->salida .= "            <table width=\"100%\" border=\"0\" align=\"center\">";
                        
                        $this->salida .= "                <tr class=\"$estilo\">";
                        $this->salida .= "                    <td colspan=\"4\" width=\"5%\" class=\"modulo_table_list_title\">CÓDIGO TARIFARIO </td>";
                        $this->salida .= "                    <td colspan=\"4\" width=\"5%\" class=\"modulo_table_list_title\">DESCRIPCION TARIFARIO </td>";
                        $this->salida .= "                </tr>";
                        $this->salida .= "                <tr class=\"$estilo\">";
                        $this->salida .= "                    <td  colspan=\"4\" >" . $tarifario_iddea . "</td>";
                        $this->salida .= "                    <td  colspan=\"4\" >" . $tarifario_dedea . "</td>";
                        $this->salida .= "                </tr>";

                        $this->salida .= "                <tr class=\"$estilo\">";
                        $this->salida .= "                    <td colspan=\"4\" width=\"5%\" class=\"modulo_table_list_title\">CÓDIGO SUBGRUPO TARIFARIO </td>";
                        $this->salida .= "                    <td colspan=\"4\" width=\"5%\" class=\"modulo_table_list_title\">DESCRIPCION SUBGRUPO TARIFARIO </td>";
                        $this->salida .= "                </tr>";
                        $this->salida .= "                <tr class=\"$estilo\">";
                        $this->salida .= "                    <td  colspan=\"4\" >" . $subtarifario_iddea . "</td>";
                        $this->salida .= "                    <td  colspan=\"4\" >" . $subtarifario_dedea . "</td>";
                        $this->salida .= "                </tr>";
    
                        
/*
                        $this->salida .= "                <tr class=\"$estilo\">";
                        $this->salida .= "                    <td width=\"20%\" align=\"left\" class=\"modulo_table_list_title\">CÓDIGO TARIFARIO: </td>";
                        $this->salida .= "                    <td  colspan=\"7\" >" . $tarifario_iddea . "</td>";
                        $this->salida .= "                </tr>";
                        $this->salida .= "                <tr class=\"$estilo\">";
                        $this->salida .= "                    <td width=\"20%\" align=\"left\" class=\"modulo_table_list_title\">DESCRIPCIÓN TARIFARIO: </td>";
                        $this->salida .= "                    <td  colspan=\"7\" >" . $tarifario_dedea . "</td>";
                        $this->salida .= "                </tr>";

                        $this->salida .= "                <tr class=\"$estilo\">";
                        $this->salida .= "                    <td width=\"20%\" align=\"left\" class=\"modulo_table_list_title\">CÓDIGO SUBGRUPO TARIFARIO: </td>";
                        $this->salida .= "                    <td  colspan=\"7\" >" . $subtarifario_iddea . "</td>";
                        $this->salida .= "                </tr>";
                        $this->salida .= "                <tr class=\"$estilo\">";
                        $this->salida .= "                    <td width=\"20%\" align=\"left\" class=\"modulo_table_list_title\">DESCRIPCIÓN SUBGRUPO TARIFARIO: </td>";
                        $this->salida .= "                    <td  colspan=\"7\" >" . $subtarifario_dedea . "</td>";
                        $this->salida .= "                </tr>";

*/                         
                        
                        $this->salida .= "                <tr>";
                        $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">OBSERVACIONES: </td>";
                        $this->salida .= "                    <td width=\"5%\" colspan=\"7\" class=\"hc_table_submodulo_list_title\">" . $cadobse . "</td>";
                        $this->salida .= "                </tr>";
                        $this->salida .= "                <tr class=\"modulo_list_claro\">";
                        $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">ACTIVACION: </td>";
                        $this->salida .= "                    <td width=\"5%\" colspan=\"2\" nowrap>" . $this->FechaStamp($var[$f][fecha_activacion]) . "</td>";
                        $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\" nowrap>VENC.: </td>";
                        $x = '';
                        $vecimiento = $var[$f][fecha_vencimiento];
                        $arr_fecha = explode(" ", $vecimiento);
                        if (strtotime(date("Y-m-d")) > strtotime($arr_fecha[0]))
                            $x = 'VENCIDA';
                        if (strtotime(date("Y-m-d")) == strtotime($arr_fecha[0]))
                            $x = '';
                        $this->salida .= "                    <td width=\"5%\" >" . $this->FechaStamp($var[$f][fecha_vencimiento]) . "</td>";
                        $this->salida .= "                    <td width=\"5%\" class=\"label_error\" align=\"center\">" . $x . "</td>";
                        $this->salida .= "                    <td width=\"7%\" class=\"modulo_table_list_title\">REFRENDAR HASTA: </td>";
                        if ($x AND $var[$f][fecha_refrendar] > date("Y-m-d")
                                AND $var[$f][fecha_refrendar] != $var[$f][fecha_vencimiento]) {
                            $msg = 'Esta seguro que desea Refrendar la Orden con Fecha de Vencimiento ' . $this->FechaStamp($var[$f][fecha_vencimiento]) . ' Hasta ' . $this->FechaStamp($var[$f][fecha_refrendar]);
                            $arreglo = array('tipoid' => $var[0][tipo_id_paciente], 'pacienteid' => $var[0][paciente_id], 'orden' => $var[$f][orden_servicio_id], 'numor' => $var[$f][numero_orden_id], 'fecha' => $var[$f][fecha_refrendar]);
                            $accionR = ModuloGetURL('app', 'CentroAutorizacion', 'user', '');
                            $accionR = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'ConfirmarAccion', array('c' => 'app', 'm' => 'CentroAutorizacion', 'me2' => 'DetalleOS', 'me' => 'Refrendar', 'mensaje' => $msg, 'titulo' => 'REFRENDAR ORDEN', 'arreglo' => $arreglo, 'boton1' => 'ACEPTAR', 'boton2' => 'CANCELAR'));
                            $this->salida .= "        <td width=\"10%\">" . $this->FechaStamp($var[$f][fecha_refrendar]) . "&nbsp;&nbsp;<a href=\"$accionR\">REFRENDAR</a></td>";
                        } else {
                            $this->salida .= "                    <td width=\"5%\">" . $this->FechaStamp($var[$f][fecha_refrendar]) . "</td>";
                        }
                        $this->salida .= "                </tr>";
                        $this->salida .= "             </table>";
                        $this->salida .= "    <table width=\"100%\" border=\"0\" align=\"center\">";
                        $this->salida .= "      <tr class=\"modulo_list_claro\" align=\"center\">";
                        $this->salida .= "                    <td width=\"7%\" class=\"modulo_table_list_title\">ESTADO: </td>";
                        $this->salida .= "                    <td width=\"7%\" class=\"hc_table_submodulo_list_title\">" . $var[$d][estado] . "</td>";
                        $accionA = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarFormaAnular', array('tipoid' => $var[0][tipo_id_paciente], 'pacienteid' => $var[0][paciente_id], 'orden' => $var[$f][orden_servicio_id], 'num' => $var[$f][numero_orden_id], 'plan' => $var[$f][plan_id]));
                        $accionF = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarFormaCambiarFecha', array('tipoid' => $var[0][tipo_id_paciente], 'pacienteid' => $var[0][paciente_id], 'orden' => $var[$f][orden_servicio_id], 'numor' => $var[$f][numero_orden_id], 'fecha' => $var[$f][fecha_refrendar], 'fechaV' => $var[$f][fecha_vencimiento], 'fechaA' => $var[$f][fecha_activacion], 'plan' => $var[$f][plan_id]));
                        $accionP = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'ValidacionCambioProveedor', array('tipoid' => $var[0][tipo_id_paciente], 'pacienteid' => $var[0][paciente_id], 'orden' => $var[$f][orden_servicio_id], 'numor' => $var[$f][numero_orden_id], 'proveedor' => $id, 'cargo' => $cargo, 'plan' => $var[$f][plan_id], 'tipop' => $tipo, 'solicitud' => $var[$f][hc_os_solicitud_id]));
                        $this->salida .= "        <td width=\"10%\"><a href=\"$accionA\"><img src=\"" . GetThemePath() . "/images/pincumplimiento_citas.png\" border='0'> ANULAR</a></td>";

                        $this->salida .= "        <td width=\"10%\"><a href=\"#\" onClick = \" JavaRef('$accionF', AgruOrdenes.value); \"><img src=\"" . GetThemePath() . "/images/fecha_fin.png\" border='0'> CAMBIAR FECHA</a></td>";

                        $this->salida .= "        <td width=\"10%\"><a href=\"$accionP\"><img src=\"" . GetThemePath() . "/images/proveedor.png\" border='0'> CAMBIAR PROVEEDOR</a></td>";
                        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'ReporteOrdenServicio', array('regreso2' => 'DetalleOS', 'orden' => $var[$d][orden_servicio_id], 'plan' => $var[$d][plan_id], 'tipoid' => $var[$d][tipo_id_paciente], 'paciente' => $var[$d][paciente_id], 'afiliado' => $var[$d][tipo_afiliado_id], 'pos' => 1));
                        $this->salida .= "        <td width=\"10%\"><a href=\"$accion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";
                        $mostrar = $reporte->GetJavaReport('app', 'CentralImpresionHospitalizacion', 'ordenservicioHTM', array('orden' => $var[$d][orden_servicio_id]), array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                        $funcion = $reporte->GetJavaFunction();
                        $this->salida .=$mostrar;
                        $this->salida .= "        <td width=\"10%\"><a href=\"javascript:$funcion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'> IMPRIMIR</a></td>";
                        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'ReporteOrdenServicio', array('regreso2' => 'DetalleOS', 'orden' => $var[$d][orden_servicio_id], 'plan' => $var[$d][plan_id], 'tipoid' => $var[$d][tipo_id_paciente], 'paciente' => $var[$d][paciente_id], 'afiliado' => $var[$d][tipo_afiliado_id], 'pos' => 0));
                        $this->salida .= "                <td  align=\"center\" width=\"12%\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'>&nbsp;<a href=\"$accion\"> IMPRIMIR MEDIA CARTA</a></td>";
                        $this->salida .= "      </tr>";
                        $this->salida .= "       </table>";
                        $this->salida .= "        </td>";
                        $this->salida .= "      </tr>";
                        $this->salida .= "       </table>";
                        $this->salida .= "        </td>";
                        $this->salida .= "      </tr>";
                        $f++;
                    }//while orden_id
                    $d = $f;
                }
                $i = $d;
                $this->salida .= "       </table>";
                $this->salida .= ThemeCerrarTabla();
                $this->salida .= "       <br>";
            }//fin for
        }

        $this->FormaOSVencidas($var[0][tipo_id_paciente], $var[0][paciente_id]);
        /* if(empty($_SESSION['CENTROAUTORIZACION']['TODOS']))
          {
          $this->FormaOsOtrosPlanes($var[0][tipo_id_paciente],$var[0][paciente_id]);
          } */
        $this->FormaOSAnuladas($var[0][tipo_id_paciente], $var[0][paciente_id]);

        $_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE4'] = $vars;
        $this->ListadoOsNoAuto('DetalleOS', &$reporte);
        //else
        //{
        //    $this->salida .= "<br><p align=\"center\" class=\"label_error\">NO TIENE ORDENES</p>";
        // }
        $this->salida .= "<table width=\"50%\" border=\"0\" align=\"center\">";
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarBuscarOS');
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<tr><td align=\"center\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></td>";
        $this->salida .= "</form>";
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'FormaMenus');
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td>";
        $this->salida .= "</form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaOSVencidas($tipo, $paciente) {
        $var = $this->OsVencidas($tipo, $paciente);
        if (!empty($var) AND empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            $datos = $this->DatosEncabezado();
            $this->salida .= ThemeAbrirTabla('ORDENES SERVICIO ANULADAS POR VENCIMIENTO PLAN ' . $datos[plan_descripcion] . ' RESPONSABLE ' . $_SESSION['CENTROAUTORIZACION']['RESPONSABLE'], 850);
            $this->salida .= "    <table width=\"95%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\">";
            $this->salida .= "      <td>ORDEN No.</td>";
            $this->salida .= "      <td>CARGO</td>";
            $this->salida .= "      <td>DESCRIPCION</td>";
            $this->salida .= "      <td></td>";
            $this->salida .= "      </tr>";
            for ($i = 0; $i < sizeof($var); $i++) {
                if ($d % 2) {
                    $estilo = "modulo_list_claro";
                } else {
                    $estilo = "modulo_list_oscuro";
                }
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "      <td align=\"center\">" . $var[$i][orden_servicio_id] . "</td>";
                $this->salida .= "      <td align=\"center\">" . $var[$i][cargo] . "</td>";
                $this->salida .= "      <td>" . $var[$i][descripcion] . "</td>";
                $action = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarFormaInformacionOs', array('orden' => $var[$i][orden_servicio_id], 'num' => $var[$i][numero_orden_id], 'cargo' => $var[$i][cargo], 'tipoid' => $tipo, 'pacienteid' => $paciente));
                $this->salida .= "      <td align=\"center\"><a href=\"$action\"><img src=\"" . GetThemePath() . "/images/informacion.png\" border=\"0\"></a></td>";
                $this->salida .= "      </tr>";
            }
            $this->salida .= "</table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
        } elseif (!empty($var) AND !empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            for ($i = 0; $i < sizeof($var);) {
                $d = $i;
                $this->salida .= ThemeAbrirTabla('ORDENES SERVICIO ANULADAS POR VENCIMIENTO PLAN ' . $var[$i][plan_descripcion], 850);
                $this->salida .= "    <table width=\"95%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                $this->salida .= "      <td>ORDEN No.</td>";
                $this->salida .= "      <td>CARGO</td>";
                $this->salida .= "      <td>DESCRIPCION</td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                while ($var[$i][plan_id] = $var[$d][plan_id]) {
                    if ($d % 2) {
                        $estilo = "modulo_list_claro";
                    } else {
                        $estilo = "modulo_list_oscuro";
                    }
                    $this->salida .= "      <tr class=\"$estilo\">";
                    $this->salida .= "      <td align=\"center\">" . $var[$d][orden_servicio_id] . "</td>";
                    $this->salida .= "      <td align=\"center\">" . $var[$d][cargo] . "</td>";
                    $this->salida .= "      <td>" . $var[$d][descripcion] . "</td>";
                    $action = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarFormaInformacionOs', array('orden' => $var[$d][orden_servicio_id], 'num' => $var[$d][numero_orden_id], 'cargo' => $var[$d][cargo], 'tipoid' => $tipo, 'pacienteid' => $paciente));
                    $this->salida .= "      <td align=\"center\"><a href=\"$action\"><img src=\"" . GetThemePath() . "/images/informacion.png\" border=\"0\"></a></td>";
                    $this->salida .= "      </tr>";
                    $d++;
                }
                $i = $d;
            }
            $this->salida .= "</table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
        }
    }

    /**
     *
     */
    function FormaOSAnuladas($tipo, $paciente) {
        $var = $this->OsAnuladas($tipo, $paciente);
        if (!empty($var) AND empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            $datos = $this->DatosEncabezado();
            $this->salida .= ThemeAbrirTabla('ORDENES SERVICIO ANULADAS PLAN ' . $datos[plan_descripcion] . ' RESPONSABLE ' . $_SESSION['CENTROAUTORIZACION']['RESPONSABLE'], 850);
            $this->salida .= "    <table width=\"95%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr class=\"modulo_table_list_title\">";
            $this->salida .= "      <td>ORDEN No.</td>";
            $this->salida .= "      <td>CARGO</td>";
            $this->salida .= "      <td>DESCRIPCION</td>";
            $this->salida .= "      <td></td>";
            $this->salida .= "      </tr>";
            for ($i = 0; $i < sizeof($var); $i++) {
                if ($d % 2) {
                    $estilo = "modulo_list_claro";
                } else {
                    $estilo = "modulo_list_oscuro";
                }
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "      <td align=\"center\">" . $var[$i][orden_servicio_id] . "</td>";
                $this->salida .= "      <td align=\"center\">" . $var[$i][cargo] . "</td>";
                $this->salida .= "      <td>" . $var[$i][descripcion] . "</td>";
                $action = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarFormaInformacionOs', array('orden' => $var[$i][orden_servicio_id], 'num' => $var[$i][numero_orden_id], 'cargo' => $var[$i][cargo], 'tipoid' => $tipo, 'pacienteid' => $paciente));
                $this->salida .= "      <td align=\"center\"><a href=\"$action\"><img src=\"" . GetThemePath() . "/images/informacion.png\" border=\"0\"></a></td>";
                $this->salida .= "      </tr>";
            }
            $this->salida .= "</table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
        } elseif (!empty($var) AND !empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            for ($i = 0; $i < sizeof($var);) {
                $d = $i;
                $this->salida .= ThemeAbrirTabla('ORDENES SERVICIO ANULADAS PLAN ' . $var[$i][plan_descripcion], 850);
                $this->salida .= "    <table width=\"95%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                $this->salida .= "      <td>ORDEN No.</td>";
                $this->salida .= "      <td>CARGO</td>";
                $this->salida .= "      <td>DESCRIPCION</td>";
                $this->salida .= "      <td></td>";
                $this->salida .= "      </tr>";
                while ($var[$i][plan_id] = $var[$d][plan_id]) {
                    if ($d % 2) {
                        $estilo = "modulo_list_claro";
                    } else {
                        $estilo = "modulo_list_oscuro";
                    }
                    $this->salida .= "      <tr class=\"$estilo\">";
                    $this->salida .= "      <td align=\"center\">" . $var[$d][orden_servicio_id] . "</td>";
                    $this->salida .= "      <td align=\"center\">" . $var[$d][cargo] . "</td>";
                    $this->salida .= "      <td>" . $var[$d][descripcion] . "</td>";
                    $action = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarFormaInformacionOs', array('orden' => $var[$d][orden_servicio_id], 'num' => $var[$d][numero_orden_id], 'cargo' => $var[$d][cargo], 'tipoid' => $tipo, 'pacienteid' => $paciente));
                    $this->salida .= "      <td align=\"center\"><a href=\"$action\"><img src=\"" . GetThemePath() . "/images/informacion.png\" border=\"0\"></a></td>";
                    $this->salida .= "      </tr>";
                    $d++;
                }
                $i = $d;
            }
            $this->salida .= "</table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
        }
    }

    /**
     *
     */
    function FormaOsOtrosPlanes($tipo, $paciente) {
        $var = $this->OsOtrosPlanes($tipo, $paciente);
        if (!empty($var)) {
            $this->salida .= ThemeAbrirTabla('ORDENES SERVICIO DE OTROS PLANES', 850);
            $f = 0;
            for ($i = 0; $i < sizeof($var);) {
                $d = $i;
                if ($var[$i][plan_id] == $var[$d][plan_id]) {
                    $this->salida .= "    <table width=\"95%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
                    $this->salida .= "      <tr class=\"modulo_table_title\">";
                    $this->salida .= "        <td colspan=\"4\" align=\"left\">ORDENES PLAN   " . $var[$d][plan_descripcion] . "</td>";
                    $this->salida .= "      </tr>";
                    $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "      <td>ORDEN</td>";
                    $this->salida .= "      <td>CARGO</td>";
                    $this->salida .= "      <td>DESCRIPCION</td>";
                    $this->salida .= "      <td></td>";
                    $this->salida .= "      </tr>";
                }
                while ($var[$i][plan_id] == $var[$d][plan_id]) {
                    if ($d % 2) {
                        $estilo = "modulo_list_claro";
                    } else {
                        $estilo = "modulo_list_oscuro";
                    }
                    $this->salida .= "      <tr class=\"$estilo\">";
                    $this->salida .= "      <td align=\"center\">" . $var[$d][orden_servicio_id] . "</td>";
                    $this->salida .= "      <td align=\"center\">" . $var[$d][cargo] . "</td>";
                    $this->salida .= "      <td>" . $var[$d][descripcion] . "</td>";
                    $action = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarFormaInformacionOs', array('orden' => $var[$d][orden_servicio_id], 'num' => $var[$d][numero_orden_id], 'cargo' => $var[$d][cargo], 'tipoid' => $tipo, 'pacienteid' => $paciente));
                    $this->salida .= "      <td align=\"center\"><a href=\"$action\"><img src=\"" . GetThemePath() . "/images/informacion.png\" border=\"0\"></a></td>";
                    $this->salida .= "      </tr>";
                    $d++;
                }
                $i = $d;
            }
            $this->salida .= "</table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
        }
    }

    /**
     *
     */
    function FormaCambiarFecha($tipo, $paciente, $orden, $num, $ref, $venc, $act, $plan, $AgruOrdenes) {


        $this->salida .= ThemeAbrirTabla('CAMBIAR FECHA ORDEN DE SERVICIO');
        if (empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            $this->Encabezado();
        }
        $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'CambiarFecha', array('tipoid' => $tipo, 'pacienteid' => $paciente, 'orden' => $orden, 'numor' => $num, 'fecha' => $ref, 'fechaV' => $venc, 'fechaA' => $act, 'plan' => $plan));
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "<tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td width=\"40%\" class=\"" . $this->SetStyle("Activacion") . "\">FECHA ACTIVACION: </td>";
        $this->salida .= "      <td><input type=\"hidden\" name=\"AgruOrdenes\" value=\"" . $AgruOrdenes . "\" readonly>";
        $this->salida .= "      <input type=\"text\" name=\"Activacion\" value=\"" . $this->FechaStamp($act) . "\" class=\"input-text\" readonly></td>";
        $this->salida .= "</tr>";
        $this->salida .= "<tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"" . $this->SetStyle("Vencimiento") . "\">FECHA VENCIMIENTO: </td>";
        $this->salida .= "      <td><input type=\"text\" name=\"Vencimiento\" value=\"" . $this->FechaStamp($venc) . "\" class=\"input-text\" readonly></td>";
        $this->salida .= "</tr>";
        $this->salida .= "<tr class=\"modulo_list_claro\">";
        $this->salida .= "      <td class=\"" . $this->SetStyle("Refrendar") . "\">FECHA REFRENDAR: </td>";
        $this->salida .= "      <td><input type=\"text\" name=\"Refrendar\" value=\"" . $this->FechaStamp($ref) . "\" class=\"input-text\"></td>";
        $this->salida .= "</tr>";
        $this->salida .= "<tr>";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CAMBIAR\"><br></td>";
        $this->salida .= "</form>";
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleOS', array('tipoid' => $tipo, 'pacienteid' => $paciente));
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"><br></td>";
        $this->salida .= "</form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaCambiarProveedor($tipo, $paciente, $orden, $num, $cargo, $proveedor, $tipop, $solicitud) {
        if (empty($tipo)) {
            $tipo = $_REQUEST['tipoid'];
            $paciente = $_REQUEST['pacienteid'];
            $orden = $_REQUEST['orden'];
            $num = $_REQUEST['numor'];
            $cargo = $_REQUEST['cargo'];
            $proveedor = $_REQUEST['proveedor'];
            $tipop = $_REQUEST['tipop'];
            $solicitud = $_REQUEST['solicitud'];
        }
        $this->salida .= ThemeAbrirTabla('CAMBIAR PROVEEDOR ORDEN DE SERVICIO');
        if (empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            $this->Encabezado();
        }
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'CambiarProveedor', array('act' => $proveedor, 'tipop' => $tipop));
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "       <input type=\"hidden\" name=\"cargo\" value=\"" . $cargo . "\"></td>";
        $this->salida .= "       <input type=\"hidden\" name=\"proveedor\" value=\"" . $proveedor . "\"></td>";
        $this->salida .= "       <input type=\"hidden\" name=\"tipoid\" value=\"" . $tipo . "\"></td>";
        $this->salida .= "       <input type=\"hidden\" name=\"pacienteid\" value=\"" . $paciente . "\"></td>";
        $this->salida .= "       <input type=\"hidden\" name=\"orden\" value=\"" . $orden . "\"></td>";
        $this->salida .= "       <input type=\"hidden\" name=\"numor\" value=\"" . $num . "\"></td>";
        $this->salida .= "<tr class=\"modulo_list_claro\">";
        $this->salida .= "        <td align=\"center\">PROVEEDOR:  </td>";
        $dpto = $this->ComboDepartamento($cargo, $solicitud);
        $pro = $this->ComboProveedor($cargo);
        $suma = 0;
        $suma = sizeof($pro) + sizeof($dpto);
        if (!empty($dpto) OR !empty($pro)) {
            $this->salida .= "        <td align=\"center\"><select name=\"Combo\" class=\"select\">";
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
            $this->salida .= "        <td class=\"label_error\" align=\"center\">El Cargo No lo Presta Nigun Departamento o Proveedor</td>";
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
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleOS', array('tipoid' => $tipo, 'pacienteid' => $paciente));
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"><br></td>";
        $this->salida .= "</form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaInformacionOs($var) {
        $this->salida .= ThemeAbrirTabla('DETALLE ORDENES');
        if ($var[plan_id] == $_SESSION['CENTROAUTORIZACION']['PLAN']) {
            $this->Encabezado();
        }
        $this->salida .= "     <table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\" colspan=\"4\" align=\"left\">DATOS PACIENTE </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION: </td><td width=\"20%\" class=\"modulo_list_claro\">" . $var[tipo_id_paciente] . " " . $var[paciente_id] . "</td>";
        $this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">PACIENTE:</td><td width=\"40%\" class=\"modulo_list_claro\">" . $var[nombre] . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "       </table>";
        $this->salida .= "    <br><table width=\"80%\" border=\"1\" align=\"center\" >";
        $this->salida .= "      <tr class=\"modulo_table_title\">";
        $this->salida .= "        <td colspan=\"5\" align=\"left\">NUMERO DE ORDEN DE SERVICIO No. " . $var[orden_servicio_id] . "   ----  PLAN  [ " . $var[plan_descripcion] . " ]</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td colspan=\"5\" class=\"modulo_list_claro\">";
        $this->salida .= "            <table width=\"100%\" border=\"1\" align=\"center\" class=\"\">";
        $this->salida .= "                <tr>";
        $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">TIPO AFILIADO: </td>";
        $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[tipo_afiliado_nombre] . "</td>";
        $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">RANGO: </td>";
        $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[rango] . "</td>";
        $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">SEMANAS COT.: </td>";
        $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[semanas_cotizadas] . "</td>";
        $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">SERVICIO: </td>";
        $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[desserv] . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">AUT. INT.: </td>";
        $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[autorizacion_int] . "</td>";
        $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">AUT. EXT: </td>";
        $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">" . $var[autorizacion_ext] . "</td>";
        $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">AUTORIZADOR: </td>";
        $this->salida .= "                    <td width=\"5%\" colspan=\"3\" class=\"hc_table_submodulo_list_title\">" . $var[autorizador] . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">OBSERVACIONES: </td>";
        $this->salida .= "                    <td width=\"5%\" colspan=\"7\" class=\"hc_table_submodulo_list_title\">" . $var[observacion] . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "             </table>";
        $this->salida .= "        </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td colspan=\"5\">";
        $this->salida .= "        <table width=\"99%\" border=\"0\" align=\"center\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "        <td width=\"6%\">ITEM</td>";
        $this->salida .= "        <td width=\"6%\">CANT.</td>";
        $this->salida .= "        <td width=\"10%\">CARGO</td>";
        $this->salida .= "        <td width=\"45%\">DESCRICPION</td>";
        $this->salida .= "        <td width=\"20%\">PROVEEDOR</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "        <td align=\"center\">" . $var[numero_orden_id] . "</td>";
        $this->salida .= "        <td align=\"center\">" . $var[cantidad] . "</td>";
        $this->salida .= "        <td align=\"center\">" . $var[cargo] . "</td>";
        $this->salida .= "        <td>" . $var[descripcion] . "</td>";
        $p = '';
        if (!empty($var[departamento])) {
            $p = 'DPTO. ' . $var[desdpto];
            $id = $var[departamento];
        } else {
            $p = $var[plan_descripcion];
            $id = $var[plan_proveedor_id];
        }
        $this->salida .= "        <td align=\"center\">" . $p . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "             </table>";
        $this->salida .= "        </td>";
        $this->salida .= "      </tr>";
        if (!empty($var[desanulada])) {
            $this->salida .= "      <tr>";
            $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"20%\">MOTIVO ANULACION: </td>";
            $this->salida .= "        <td colspan=\"4\" class=\"modulo_list_oscuro\">" . $var[desanulada] . "</td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <tr>";
            $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"20%\">OBSERVACION ANULACION: </td>";
            $this->salida .= "        <td colspan=\"4\" class=\"modulo_list_oscuro\">" . $var[obsanulada] . "</td>";
            $this->salida .= "      </tr>";
        }
        $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "        <td colspan=\"5\">";
        $this->salida .= "            <table width=\"100%\" border=\"0\" align=\"center\">";
        $this->salida .= "                <tr class=\"modulo_list_claro\">";
        $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">ACTIVACION: </td>";
        $this->salida .= "                    <td width=\"10%\" colspan=\"2\">" . $this->FechaStamp($var[fecha_activacion]) . "</td>";
        $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">VENC.: </td>";
        $x = '';
        if (date("Y-m-d") > $var[fecha_vencimiento])
            $x = 'VENCIDA';
        if (date("Y-m-d") == $var[fecha_vencimiento])
            $x = '';
        $this->salida .= "                    <td width=\"10%\" >" . $this->FechaStamp($var[fecha_vencimiento]) . "</td>";
        $this->salida .= "                    <td width=\"10%\" class=\"label_error\" align=\"center\">" . $x . "</td>";
        $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">ESTADO: </td>";
        $this->salida .= "                    <td width=\"30%\" class=\"hc_table_submodulo_list_title\">" . $var[estado] . "</td>";
        $this->salida .= "                </tr>";
        if ($var[sw_estado] == 8) {
            $this->salida .= "                <tr class=\"modulo_list_claro\">";
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'ActivarOS', array('tipoid' => $var[tipo_id_paciente], 'pacienteid' => $var[paciente_id], 'orden' => $var[orden_servicio_id], 'numorden' => $var[numero_orden_id]));
            $this->salida .= "                    <td colspan=\"8\" align=\"center\"><a href=\"$accion\">LIBERAR ORDEN PARA REFRENDAR</a></td>";
            $this->salida .= "                </tr>";
        }
        $this->salida .= "             </table>";
        $this->salida .= "        </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "             </table>";
        $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "<tr>";
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleOS', array('tipoid' => $var[tipo_id_paciente], 'pacienteid' => $var[paciente_id]));
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"><br></td>";
        $this->salida .= "</form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaAnular($tipo, $paciente, $orden, $num, $plan) {
        $this->salida .= ThemeAbrirTabla('ANULAR ORDEN');
        if (empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            $this->Encabezado();
        }
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'AnularOs', array('tipoid' => $tipo, 'pacienteid' => $paciente, 'orden' => $orden, 'num' => $num, 'plan' => $plan));
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        $this->salida .= "<table width=\"60%\" border=\"0\" align=\"center\">";
        $this->salida .="<tr>";
        if ($_REQUEST['Opcion'] == 1) {
            $this->salida .="<td class=\"label\">ANULAR <input type=\"radio\" name=\"Opcion\" value=\"1\" checked></td>";
        } else {
            $this->salida .="<td class=\"label\">ANULAR <input type=\"radio\" name=\"Opcion\" value=\"1\"></td>";
        }
        if ($_REQUEST['Opcion'] == 2) {
            $this->salida .="<td class=\"label\">LIBERAR SOLICITUD <input type=\"radio\" name=\"Opcion\" value=\"2\" checked></td>";
        } else {
            $this->salida .="<td class=\"label\">LIBERAR SOLICITUD <input type=\"radio\" name=\"Opcion\" value=\"2\"></td>";
        }
        $this->salida .="</tr>";
        $combo = $this->ComboJustificarAnuladas();
        $this->salida .= "               <tr><td class=\"" . $this->SetStyle("CJ") . "\" width=\"20%\">JUSTIFICACION: </td><td><select name=\"CJ\" class=\"select\">";
        $this->salida .= "                     <option value=\"-1\">----------SELECCIONE----------</option>";
        for ($i = 0; $i < sizeof($combo); $i++) {
            $this->salida .= "                     <option value=\"" . $combo[$i][os_anulada_justificicacion_id] . "\">" . $combo[$i][descripcion] . "</option>";
        }
        $this->salida .= "              </select></td></tr>";
        $this->salida .= "<tr>";
        $this->salida .= "<td class=\"label\">0BSERVACION: </td>";
        $this->salida .= "<td ><textarea name=\"Justificacion\" cols=\"65\" rows=\"3\" class=\"textarea\">" . $_REQUEST['Justificacion'] . "</textarea></td>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "<tr>";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ANULAR\"><br></td>";
        $this->salida .= "</form>";
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleOS', array('tipoid' => $tipo, 'pacienteid' => $paciente));
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"><br></td>";
        $this->salida .= "</form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

//------------------------BUSCAR TODOS----------------------------------------------

    /*
     * Esta funcion realiza la busqueda de cumplimientos de ordenes de servicio
     * según filtros como tipo, documento, nombre y plan
     * @return boolean
     */
    function FormaBuscarTodos($arr) {
        $this->salida.= ThemeAbrirTabla('BUSCAR SOLICITUDES');
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'BuscarSolicitud');
        $this->salida .= "<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA: -23- </td>";
        $this->salida .= "</tr>";
        $this->salida .= "<tr class=\"modulo_list_claro\" >";
        $this->salida .= "<td width=\"40%\" >";
        $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr><td>";
        $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
        $tipo_id = $this->tipo_id_paciente();
        $this->BuscarIdPaciente($tipo_id, '');
        $this->salida .= "</select></td></tr>";
        $this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
        $this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\"></td></tr>";
        $this->salida .= "<tr><td class=\"label\">No. SOLICITUD: </td><td><input type=\"text\" class=\"input-text\" name=\"Solicitud\" maxlength=\"32\"></td></tr>";
        $this->salida .= "                <tr><td class=\"label\">TIPO SOLICITUD: </td><td><select name=\"Tipo\" class=\"select\">";
        $tipo = $this->TiposSolicitud();
        $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
        for ($i = 0; $i < sizeof($tipo); $i++) {
            if ($tipo[$i][os_tipo_solicitud_id] == $_REQUEST[Tipo]) {
                $this->salida .=" <option value=\"" . $tipo[$i][os_tipo_solicitud_id] . "\" selected>" . $tipo[$i][descripcion] . "</option>";
            } else {
                $this->salida .=" <option value=\"" . $tipo[$i][os_tipo_solicitud_id] . "\">" . $tipo[$i][descripcion] . "</option>";
            }
        }
        $this->salida .= "                  </select></td></tr>";
        $this->salida .= "                <tr><td class=\"label\">TIPO SERVICIO: </td><td><select name=\"Servicio\" class=\"select\">";
        $tipo = $this->TiposServicios();
        $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
        for ($i = 0; $i < sizeof($tipo); $i++) {
            if ($tipo[$i][servicio] == $_REQUEST[Servicio]) {
                $this->salida .=" <option value=\"" . $tipo[$i][servicio] . "\" selected>" . $tipo[$i][descripcion] . "</option>";
            } else {
                $this->salida .=" <option value=\"" . $tipo[$i][servicio] . "\">" . $tipo[$i][descripcion] . "</option>";
            }
        }
        $this->salida .= "                  </select></td></tr>";

        $responsables = $this->Planes();
        if (!empty($responsables)) {
            $this->salida .= "				       <tr><td class=\"" . $this->SetStyle("plan") . "\">PLAN: </td><td><select name=\"plan\" class=\"select\">";
            if ($responsables[0][todos] == 1) {
                $this->salida .=" <option value=\"-1\">-------TODOS LOS PLANES-------</option>";
            }
            for ($i = 0; $i < sizeof($responsables); $i++) {
                if ($responsables[$i][plan_id] == $_REQUEST['plan']) {
                    $this->salida .=" <option value=\"" . $responsables[$i][plan_id] . "\" selected>" . $responsables[$i][plan_descripcion] . "</option>";
                } else {
                    $this->salida .=" <option value=\"" . $responsables[$i][plan_id] . "\">" . $responsables[$i][plan_descripcion] . "</option>";
                }
            }
            $this->salida .= "              </select></td></tr>";
        } else {
            $this->salida .= "				       <tr><td class=\"" . $this->SetStyle("plan") . "\">PLAN: </td><td>";
            $this->salida .="NO TIENE PLANES ASOCIADOS</td></tr>";
        }

        $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
        $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
        $this->salida .= "</form>";
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'main2');
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table></td></tr>";
        $this->salida .= "</td></tr></table>";
        $this->salida .= "</td>";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "<td>";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= "       </td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>";
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        if (!empty($arr)) {
            $d = 0;
            $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td width=\"25%\">IDENTIFICACION</td>";
            $this->salida .= "        <td width=\"45%\">PACIENTE</td>";
            $this->salida .= "        <td width=\"50%\">PROCESO AUTORIZACION</td>";
            $this->salida .= "        <td width=\"10%\"></td>";
            $this->salida .= "      </tr>";
            for ($i = $d; $i < sizeof($arr); $i++) {
                if ($i % 2) {
                    $estilo = "modulo_list_claro";
                } else {
                    $estilo = "modulo_list_oscuro";
                }
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td>" . $arr[$i][tipo_id_paciente] . " " . $arr[$i][paciente_id] . "</td>";
                $this->salida .= "        <td>" . $arr[$i][nombres] . "" . $arr[$i][evolucion_id] . "</td>";
                /* if($arr[$i][usuario_id]!=NULL)
                  {
                  $this->salida .= "        <td align=\"center\" class=\"normal_10AN\">\n";
                  $this->salida .= "          <b class=\"label_error\">EN PROCESO</b> - USUARIO: ".$arr[$i]['nombre_usuario']."\n";
                  $this->salida .= "        </td>\n";
                  }
                  else
                  {  $this->salida .= "        <td align=\"center\"></td>";  }
                  if($arr[$i][usuario_id]==NULL)
                  {
                  $accion=ModuloGetURL('app','CentroAutorizacion','user','DetalleSolicituTodos',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombres]));
                  $this->salida .= "        <td align=\"center\"><a href=\"$accion\">VER</a></td>";
                  }
                  else
                  {  $this->salida .= "        <td align=\"center\"></td>";  } */
                $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleSolicituTodos', array('tipoid' => $arr[$i][tipo_id_paciente], 'paciente' => $arr[$i][paciente_id], 'nombre' => $arr[$i][nombres]));
                $this->salida .= "        <td align=\"center\"><a href=\"$accion\">VER</a></td>";
                $this->salida .= "      </tr>";
            }
            $this->salida .= " </table>";
            $this->conteo = $_SESSION['SPY2'];
            $this->salida .=$this->RetornarBarra();
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
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
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'BuscarSolicitud', $vec);
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
     *
     */
    function FormaDetalleSolicitud($datos='', $control='') {
        $mostrar = "\n<script language='javascript'>\n";
        $mostrar.="  function AbrirReport(cant)\n";
        $mostrar.="  {\n";
        $mostrar .= "       elementos = document.getElementsByName('AgrupaOrdenDA');\n";
        $mostrar .= "       AgrupaDA.value = 0;\n";
        $mostrar .= "       for(da=0; da < cant; da++)\n";
        $mostrar .= "       {\n";
        $mostrar .= "           if(elementos[da].value > 0)\n";
        $mostrar .= "           {\n";
        $mostrar .= "               if(da==0)\n";
        $mostrar .= "               {\n";
        $mostrar .= "                   AgrupaDA.value = elementos[da].value;\n";
        $mostrar.="                 }else{\n";
        $mostrar .= "                   if(AgrupaDA.value == 0)\n";
        $mostrar .= "                   {\n";
        $mostrar .= "                       AgrupaDA.value = elementos[da].value;\n";
        $mostrar.="                     }else{\n";
        $mostrar .= "                       AgrupaDA.value = AgrupaDA.value + ', ' + elementos[da].value;\n";
        $mostrar.="                     }\n";
        $mostrar.="                 }\n";
        $mostrar.="             }\n";
        $mostrar .= "       }\n";
        $mostrar.="  }\n";
        $mostrar.="  function VentanaAgrupadaDA(ruta, AgrupaDA){\n";
        $mostrar.="    ruta = ruta + '&datos[ordenDA]='+AgrupaDA;\n";        
        $mostrar.="    var nombre='';\n";
        $mostrar.="    var width='400';\n";
        $mostrar.="    var height='300';\n";
        $mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
        $mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
        $mostrar.="    var nombre='Printer_Mananger';\n";
        $mostrar.="    var str ='width='+width+',height='+height+',left='+winX+',top='+winY+',resizable=no,status=no,scrollbars=yes,location=no';\n";
        $mostrar.="    var url =ruta;\n";
        $mostrar.="    window.open(url, nombre, str).focus();\n";
        $mostrar.="  }\n";
        
        
        if (!empty($datos)) {
            if ($control == 3) {
                $RUTA = $_ROOT . "cache/ordenservicio" . $datos['orden'] . ".pdf";
            }
            $mostrar.="var rem=\"\";\n";
            $mostrar.="  function abreVentana(){\n";
            $mostrar.="    var nombre='';\n";
            $mostrar.="    var url2='';\n";
            $mostrar.="    var str='';\n";
            $mostrar.="    var ALTO=screen.height;\n";
            $mostrar.="    var ANCHO=screen.width;\n";
            $mostrar.="    var nombre='REPORTE';\n";
            $mostrar.="    var str ='ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes';\n";
            $mostrar.="    var url2 ='$RUTA';\n";
            $mostrar.="    rem = window.open(url2, nombre, str)}\n";
        }
        $mostrar.="</script>\n";
        $this->salida.="$mostrar";
        $this->salida.="<BODY onload=abreVentana();>";

        $reporte = new GetReports();
        $arr = $_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE'];

        $this->salida .= ThemeAbrirTabla('DETALLE SOLICITUDES MODIFICACION'); //----------------------------------------------------------------CORREGIR NICOLAS CABALLERO
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= "      <tr align=\"center\">";
        $this->salida .= "        <td colspan=\"8\">";
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'PedirAutorizacionTodos');
        $this->salida .= "     <table width=\"70%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\" colspan=\"6\" align=\"left\">DATOS PACIENTE </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION: </td><td width=\"20%\" class=\"modulo_list_claro\">" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . " " . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "</td>";
        $this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">PACIENTE:</td><td width=\"40%\" class=\"modulo_list_claro\" colspan=\"3\">" . $_SESSION['CENTROAUTORIZACION']['TODO']['nombre_paciente'] . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "       </table>";
        $this->salida .= "        </td>";
        $this->salida .= "      </tr>";
        //links bd
        $plan = $this->Planes();
        for ($i = 0; $i < sizeof($plan); $i++) {
            //$p=$this->ClasificarPlan($plan[$i][plan_id]);
            //if(($p[sw_tipo_plan]==0 AND $p[sw_afiliacion]==1) OR ($p[sw_tipo_plan]==3))
            if ($plan[$i][sw_afiliacion] == 1) {
                $bd = '';
                $bd = $this->DatosBD($arr[0][tipo_id_paciente], $arr[0][paciente_id], $plan[$i][plan_id]);
                if (!empty($bd)) {
                    $this->salida .= "      <tr><td colspan=\"8\">";
                    $this->SetJavaScripts('DatosBD');
                    $this->SetJavaScripts('DatosBDAnteriores');
                    $this->SetJavaScripts('DatosEvolucionInactiva');
                    $this->salida .= "<br><table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"50%\" align=\"center\" class=\"normal_10\">";
                    $this->salida .= "  <tr class=\"modulo_list_claro\">";
                    $this->salida .= "   <td align=\"center\" colspan=\"2\" class=\"label\">" . $plan[$i][plan_descripcion] . "</td>";
                    $this->salida .= "  </tr>";
                    $this->salida .= "  <tr class=\"modulo_list_claro\">";
                    $this->salida .= "   <td align=\"center\">" . RetornarWinOpenDatosBD($_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'], $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'], $plan[$i][plan_id]) . "</td>";
                    $x = $plan[$i][meses_consulta_base_datos];
                    if ($x > 1) {
                        $this->salida .= "   <td align=\"center\">" . RetornarWinOpenDatosBDAnteriores($_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'], $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'], $plan[$i][plan_id], $x) . "</td>";
                    }
                    $this->salida .= "  </tr>";
                    $this->salida .= "</table>";
                    $sw = $this->BuscarSwHc();
                    if (!empty($sw)) {
                        $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso'] = $arr[0][ingreso];
                        $dat = $this->BuscarEvolucion();
                        if ($dat) {
                            $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"30%\" align=\"center\" class=\"normal_10\">";
                            $this->salida .= "  <tr class=\"modulo_list_claro\">";
                            $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'] = 'CentroAutorizacion';
                            $_SESSION['HISTORIACLINICA']['RETORNO']['metodo'] = 'FormaDetalleSolicitud';
                            $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'] = 'user';
                            $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'] = 'app';
                            $accion = ModuloHCGetURL($dat, '', '', '', '');
                            $this->salida .= "   <td align=\"center\"><a href=\"$accion\">HISTORIA CLINICA</a></td>";
                            $this->salida .= "  </tr>";
                            $this->salida .= "</table><BR>";
                        }
                    }
                    $this->salida .= "      </td></tr>";
                }
            }
        }
        //fin links bd  
        $this->salida .= "<input type = 'hidden' name = 'AgrupaDA' id = 'AgrupaDA' value = 0>";
        for ($i = 0; $i < sizeof($arr);) {
            $f = 0;
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'PedirAutorizacionTodos', array('plan' => $arr[$i][plan_id], 'empresa' => $arr[$i][empresa_id], 'servicio' => $arr[$i][servicio]));
            $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $d = $i;
            if ($arr[$i][plan_id] == $arr[$d][plan_id] AND $arr[$i][servicio] == $arr[$d][servicio]) {

                $this->salida .= "      <tr><td colspan=\"9\"><br></td></tr>";
                $this->salida .= "      <tr><td colspan=\"9\" class=\"modulo_table_list_title\">PLAN:" . $arr[$i][plan_descripcion] . "</td></tr>";

                if ($arr[$i][sw_afiliados] == '1') {
                    $InfoAfiliado = $this->InformacionAfiliado($_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'], $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'], $arr[$i][plan_id]);
                    $this->salida .= "      <tr><td colspan=\"9\" class=\"modulo_table_title\">Informacion Afiliado</td></tr>";
                    $this->salida .= "      <tr>";
                    $this->salida .= "        <td class=\"modulo_table_title\" width=\"12%\">Tipo Afiliado: </td>";
                    $this->salida .= "        <td class=\"modulo_list_claro\" width=\"13%\" colspan=\"2\">" . $InfoAfiliado[tipo_afiliado_nombre] . "</td>";
                    $this->salida .= "        <td class=\"modulo_table_title\" width=\"11%\">Rango: </td>";
                    $this->salida .= "        <td class=\"modulo_list_claro\" align=\"left\" colspan=\"5\">" . $InfoAfiliado[rango] . "</td>";
                    $this->salida .= "      </tr>";
                    $this->salida .= "      <tr>";
                    $this->salida .= "        <td class=\"modulo_table_title\" width=\"11%\">Punto Atencion: </td>";
                    $this->salida .= "        <td class=\"modulo_list_claro\" align=\"left\" colspan=\"8\">" . $InfoAfiliado[eps_punto_atencion_nombre] . "</td>";
                    $this->salida .= "      </tr>";
                }

                $this->salida .= "      <tr>";
                $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"12%\">SERVICIO: </td>";
                $this->salida .= "        <td class=\"modulo_list_claro\" width=\"13%\" colspan=\"2\">" . $arr[$i][desserv] . "</td>";
                $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"11%\">DEPARTAMENTO: </td>";
                $this->salida .= "        <td class=\"modulo_list_claro\" align=\"left\" colspan=\"5\">" . $arr[$i][despto] . "</td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                $this->salida .= "        <td>FECHA</td>";
                $this->salida .= "        <td width=\"10%\">SOLICITUD</td>";
                $this->salida .= "        <td width=\"10%\">CARGO</td>";
                $this->salida .= "        <td colspan=\"2\" width=\"50%\">DESCRIPCION</td>";
                $this->salida .= "        <td width=\"7%\">CANTIDAD</td>";
                $this->salida .= "        <td width=\"10%\">TIPO</td>";
                $this->salida .= "        <td width=\"10%\"></td>";
                $this->salida .= "        <td width=\"10%\"></td>";
                $this->salida .= "      </tr>";
            }

            while ($arr[$i][plan_id] == $arr[$d][plan_id] AND $arr[$i][servicio] == $arr[$d][servicio]) {
                if ($d % 2) {
                    $estilo = "modulo_list_claro";
                } else {
                    $estilo = "modulo_list_oscuro";
                }
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td>" . $this->FechaStamp($arr[$d][fecha]) . " " . $this->HoraStamp($arr[$d][fecha]) . "</td>";
                //JONIER
                $this->salida .= "        <td align=\"center\">" . $arr[$d][hc_os_solicitud_id] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$d][cargos] . "</td>";
                $this->salida .= "        <td colspan=\"2\">" . $arr[$d][descripcion] . " " . $arr[$d][desc_especialidad] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$d][cantidad] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$d][desos] . "</td>";
                $this->salida .= "        <td align=\"center\" class=\"label_error\">";
                $accionhref = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'FormaAnularSolicitud', array('solicitud' => $arr[$d][hc_os_solicitud_id], 'descripcion' => $arr[$d][descripcion]));
                $this->salida .= "<a class=\"label_mark\" href=\"$accionhref\" class=\"label_mark\"><BR>ANULAR</a>";
                $this->salida .= "        </td>";

                $equi = $this->ValidarEquivalencias($arr[$d][cargos]);
                $cont = $this->ValidarContrato($arr[$d][cargos], $arr[$d][plan_id]);
                if ($_SESSION['CENTROAUTORIZACION']['TODO']['nivel'] < $arr[$d][nivel]) {
                    $this->salida .= "        <td align=\"center\" width=\"7%\">Necesita Nivel " . $arr[$d][nivel] . "";
                } elseif ($equi >= 1 AND $cont > 0 AND $_SESSION['CENTROAUTORIZACION']['TODO']['nivel'] >= $arr[$d][nivel]) {
                    $s = '';
                    $de = $this->ComboDepartamento($arr[$d][cargos], $arr[$d][hc_os_solicitud_id]);
                    if (empty($de)) {
                        $p = $this->ComboProveedor($arr[$d][cargos], $arr[$d][hc_os_solicitud_id]);
                        if (empty($p))
                            $s = 'NO PROVEEDOR -temporal <BR>'; //-------------------------------------------------------------------------CORREGIR NICOLAS CABALLERO  
                    }
                    $this->salida .= "        <td align=\"center\" class=\"label_error\">$s<input type = 'hidden' name = 'AgrupaOrdenDA' id = '".$d."' value = 0><input type=\"checkbox\" value=\"" . $arr[$d][cargos] . "," . $arr[$d][tarifario_id] . "," . $arr[$d][ingreso] . "," . $arr[$d][servicio] . "," . $arr[$d][hc_os_solicitud_id] . "," . $arr[$d][cargos] . "," . $arr[$d][cargos] . "\" name=\"Auto" . $arr[$d][hc_os_solicitud_id] . "\" id=\"Auto" . $arr[$d][hc_os_solicitud_id] . "\" 
                                                    onclick = \" 
                                                                if(Auto".$arr[$d][hc_os_solicitud_id].".checked == true)
                                                                {
                                                                    document.getElementById('".$d."').value = " . $arr[$d][hc_os_solicitud_id] . "; 
                                                                }else{
                                                                    document.getElementById('".$d."').value = 0; 
                                                                }
                                                                AbrirReport(".count($arr).");
                                                              \"
                                                              >";
                    $f++;
                }
                elseif ($cont == 0) {
                    $this->salida .= "        <td align=\"center\" class=\"label_error\" width=\"7%\">NO ESTA CONTRATADO";
                } elseif ($equi == 0) {
                    $this->salida .= "        <td align=\"center\" class=\"label_error\" width=\"7%\">NO TIENE EQUIVALENCIAS";
                }

                /* $accionhref=ModuloGetURL('app','CentroAutorizacion','user','FormaAnularSolicitud',array('solicitud'=>$arr[$d][hc_os_solicitud_id],'descripcion'=>$arr[$d][descripcion]));
                  $this->salida .= "<a class=\"label_mark\" href=\"$accionhref\" class=\"label_mark\"><BR>ANULAR</a>"; */

                $this->salida .= "      </td>";

                $this->salida .= "      </tr>";


                $d++;
            }
            $i = $d;
            if ($f == 0) {
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td class=\"label_error\" align=\"center\" colspan=\"9\">NINGUN CARGO PUEDER SER AUTORIZADO</td>";
                $this->salida .= "      </tr>";
            }
            if ($f > 0) {
/*                
                $mostrar = $reporte->GetJavaReport('app', 'CentralImpresionHospitalizacion', 'ordenservicioHTMAgrupado', array('orden' => 1927564), array('rpt_name' => 'orden', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                $funcion = $reporte->GetJavaFunction();
                $this->salida .=$mostrar;
*/                
                $rutaDA = $reporte->GetJavaRuta('app', 'CentralImpresionHospitalizacion', 'ordenservicioHTMAgrupado', array('orden' => '0'), array('rpt_name' => 'orden', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida.="            <td align=\"center\" colspan=\"5\" width=\"33%\"></td>";
//                $this->salida.="            <td align=\"right\" width=\"33%\"><a href=\"javascript:$funcion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'> IMPRIMIR</a></td>";
                $this->salida.="            <td align='right' width='33%'><input class='input-submit' type='button' name='ImpGrupo' value='IMPRIMIR' onclick = \"VentanaAgrupadaDA('$rutaDA', AgrupaDA.value);\"</td>";
                $this->salida .= "          <td align=\"right\" colspan=\"3\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"AUTORIZAR\"></td>";
                $this->salida .= "      </tr>";
            }
            $this->salida .= "        </form>";
        }
        $this->salida .= "      <tr><td colspan=\"7\"><br></td></tr>";
        $this->salida .= " </table>";
        if (!empty($_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE3'])) {
            $this->ListadoOsAuto('FormaDetalleSolicitud', &$reporte);
        }
        if (!empty($_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE4'])) {
            $this->ListadoOsNoAuto('FormaDetalleSolicitud', &$reporte);
        }
        unset($reporte);
        $this->salida .= "     <table width=\"50%\" border=\"0\" align=\"center\">";
        $this->salida .= "               <tr>";
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarBuscarSolicitud');
        $this->salida .= "             <form name=\"forma1\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "                       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></td>";
        $this->salida .= "                       </form>";
        $actionM = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'main2');
        $this->salida .= "             <form name=\"forma2\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "                       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></td>";
        $this->salida .= "                       </form>";
        $this->salida .= "               </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaAfiliado($TipoAfiliado, $Nivel, $s) {
        $this->salida .= ThemeAbrirTabla('CENTRO AUTORIZACIONES - DATOS AFILIADO');
        $this->SetJavaScripts('DatosEvolucionInactiva');
        $_SESSION['AUTORIZACIONES']['TRAMITEX']['SINAUTO'] = 1;
        if (!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'])) {
            $this->FormaCamposBD();
            //$a=ImplodeArrayAssoc($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
            $_SESSION['AUTORIZACIONES']['AFILIADO'] = $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'];
            $_SESSION['AUTORIZACIONES']['RANGO'] = $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel'];
            $_SESSION['AUTORIZACIONES']['SEMANAS'] = $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas'];
        }
        $this->salida .= "            <table width=\"50%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "               </tr>";
        $this->salida .= "           </table>";

        if (($_SESSION['AUTORIZACIONES']['AFILIADO'] === NULL)) {
            if ($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan'] == 2
                    OR $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan'] == 1) {
                $dat = $this->DatosPlanUnico($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']);
                $_SESSION['AUTORIZACIONES']['SEMANAS'] = 0;
                $_SESSION['AUTORIZACIONES']['AFILIADO'] = $dat[tipo_afiliado_id];
                $_SESSION['AUTORIZACIONES']['RANGO'] = $dat[rango];
            }
        }

        //otros datos de la bd
        if (!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'])) {

            $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "          <tr>";
            $this->salida .= "            <td  width=\"10%\" class=\"" . $this->SetStyle("TipoAfiliado") . "\">EMPLEADOR: </td>";
            $this->salida .= "            <td align=\"left\" width=\"35%\">" . $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador'] . "</td>";
            $this->salida .= "            <td></td>";
            $this->salida .= "             <td width=\"7%\" class=\"" . $this->SetStyle("Nivel") . "\">EDAD: </td>";
            $this->salida .= "            <td align=\"left\" width=\"5%\">" . $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_edad'] . "</td>";
            $this->salida .= "            <td></td>";
            $this->salida .= "            <td width=\"10%\" class=\"" . $this->SetStyle("Semanas") . "\" width=\"23%\">ESTADO: </td>";
            if ($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd'] == 'SUSPENDIDO'
                    OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd'] == 'INACTIVO'
                    OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd'] == 'URGENCIAS'
                    OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd'] == 'PROTECCION') {
                $x = 'label_error';
            } else {
                $x = 'label';
            }
            $this->salida .= "            <td align=\"left\" width=\"10%\" class=\"$x\">" . $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd'] . "</td>";
            $this->salida .= "            <td width=\"12%\" class=\"" . $this->SetStyle("Semanas") . "\" width=\"23%\">URGENCIAS: </td>";
            if ($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_urgencias'] == 1) {
                $ur = 'MES URG';
            }
            $this->salida .= "            <td align=\"left\" width=\"10%\">" . $ur . "</td>";
            $this->salida .= "          </tr>";
            $this->salida .= "          <tr>";
            $this->salida .= "            <td  width=\"10%\" class=\"" . $this->SetStyle("TipoAfiliado") . "\">RADICACION BD: </td>";
            $this->salida .= "            <td align=\"left\" width=\"35%\">" . $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['fecha_radicacion'] . "</td>";
            $this->salida .= "            <td></td>";
            $this->salida .= "             <td width=\"7%\" class=\"" . $this->SetStyle("Nivel") . "\">VENCIMIENTO BD: </td>";
            $this->salida .= "            <td align=\"left\" width=\"5%\">" . $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['fecha_vencimiento'] . "</td>";
            $this->salida .= "            <td></td>";
            $this->salida .= "            <td width=\"10%\" class=\"" . $this->SetStyle("Semanas") . "\" width=\"23%\"></td>";
            $this->salida .= "            <td align=\"left\" width=\"10%\"></td>";
            $this->salida .= "            <td width=\"12%\" class=\"" . $this->SetStyle("Semanas") . "\" width=\"23%\"></td>";
            $this->salida .= "            <td align=\"left\" width=\"10%\"></td>";
            $this->salida .= "          </tr>";
            $this->salida .= "       </table>";
        }

        $this->CargosSolicitadosAutorizacion();
        //autorizaciones que tienen tramite
        if (!empty($_SESSION['AUTORIZACIONES']['TRAMITE'])) {

            $this->salida .= "   <BR> <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
            foreach ($_SESSION['AUTORIZACIONES']['TRAMITE'] as $k => $v) {
                $s = '';
                foreach ($v as $key => $value) {
                    $s.=$key . ' - ';
                }
                $this->salida .= "             <tr class=\"modulo_table_list_title\">";
                $this->salida .= "                 <td colspan=\"8\" align=\"LEFT\">TRAMITES SOLICITUD: &nbsp;$s</td>";
                $this->salida .= "             </tr>";
                $this->salida .= "          <tr>";
                if (!empty($value[sw_personalmente])) {
                    $value[nombre] = 'Personalmente';
                }
                if (!empty($value[sw_telefonica])) {
                    $value[sw_telefonica] = 'Si';
                } else {
                    $value[sw_telefonica] = 'No';
                }
                $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"12%\">RECIBIO: </td>";
                $this->salida .= "                 <td class=\"modulo_list_claro\">" . $value[nombre] . "</td>";
                $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"7%\">FECHA: </td>";
                $this->salida .= "                 <td class=\"modulo_list_claro\" width=\"18%\">" . $value[fecha_resgistro] . "</td>";
                $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"7%\">USUARIO: </td>";
                $this->salida .= "                 <td class=\"modulo_list_claro\" width=\"25%\">" . $value[usuario] . "</td>";
                $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"4%\">TELE: </td>";
                $this->salida .= "                 <td class=\"modulo_list_claro\" width=\"2%\">" . $value[sw_telefonica] . "</td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          <tr>";
                $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\">OBSERVACION : </td>";
                $this->salida .= "                 <td colspan=\"7\" class=\"modulo_list_claro\">" . $value[observacion_autorizador] . "</td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          <tr>";
                $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\">OBS. PACIENTE: </td>";
                $this->salida .= "                 <td colspan=\"7\" class=\"modulo_list_claro\">" . $value[observacion_paciente] . "</td>";
                $this->salida .= "          </tr>";
            }
            $this->salida .= "       </table>";
        }
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'GuardarAfiliado');
        $this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <br>  <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";

        $Plan_Tem = $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'];
        $TipoId_Tem = $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'];
        $PacienteId_Tem = $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'];
        
        if (empty($Plan_Tem)){
            if(isset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'])){
                $Plan_Tem = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];
                $TipoId_Tem = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
                $PacienteId_Tem = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
            }
        }
        $DatosTem = $this->BuscarPlanTem($Plan_Tem, $TipoId_Tem, $PacienteId_Tem);

        $sw_afiliados = 0;

//        $_SESSION['AUTORIZACIONES']['ESTADO_AFILIACION'] = $DatosTem[0]['descripcion_estado'];
        if (!empty($_SESSION['AUTORIZACIONES']['ESTADO_AFILIACION'])) {
            if (count($DatosTem) > 0) {
                if (!empty($DatosTem[0]['tipo_afiliado_atencion'])){
                    $_SESSION['AUTORIZACIONES']['AFILIADO'] = $DatosTem[0]['tipo_afiliado_atencion'];
                }
                if (!empty($DatosTem[0]['rango_afiliado_atencion'])){
                    $_SESSION['AUTORIZACIONES']['RANGO'] = $DatosTem[0]['rango_afiliado_atencion'];
                }
                if (!($DatosTem[0]['descripcion_estado'])){
                    $_SESSION['AUTORIZACIONES']['ESTADO_AFILIACION'] = $DatosTem[0]['descripcion_estado'];
                }
                $_SESSION['AUTORIZACIONES']['ESTADO_AFILIACION'] = $DatosTem[0]['descripcion_estado'];
            }
        }else {
            if (count($DatosTem) > 0) {
                if (count($_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE']) > 0) {
                    $sw_afiliados = $_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE'][0]['sw_afiliados'];
                    if ($sw_afiliados == 1) {
                        $_SESSION['AUTORIZACIONES']['ESTADO_AFILIACION'] = "ACTIVO";
                    }
                }
            }
        }
        
        $tipo_afiliado = $this->Tipo_Afiliado();

        if ($_SESSION['AUTORIZACIONES']['ESTADO_AFILIACION']) {
            $this->salida .= "          <tr>";
            $this->salida .= "               <td colspan=\"6\" align=\"center\" class=\"label_error\">ESTADO AFILIADO: " . $_SESSION['AUTORIZACIONES']['ESTADO_AFILIACION'] . "</td>";
            $this->salida .= "          </tr>";
        }
        $this->salida .= "          <tr>";

        if (sizeof($tipo_afiliado) > 1) {
            //VALIDACIÓN DE PACIENE 52 CC 1130611087
            $this->salida .= "              <td class=\"" . $this->SetStyle("TipoAfiliado") . "\">TIPO AFILIADO: </td><td>";
            $this->salida .= "                <select name=\"TipoAfiliado\" class=\"select\">";
            $this->BuscarIdTipoAfiliado($tipo_afiliado, $_SESSION['AUTORIZACIONES']['AFILIADO']);
            $this->salida .= "                </select>";
            $this->salida .= "              </td>";
        } else {
            $this->salida .= "            <td class=\"" . $this->SetStyle("TipoAfiliado") . "\">TIPO AFILIADO: </td>";
            $NomAfi = $this->NombreAfiliado($tipo_afiliado[0]['tipo_afiliado_id']);
            $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"" . $NomAfi[tipo_afiliado_id] . "\">" . $NomAfi[tipo_afiliado_nombre] . "</td>";
            $this->salida .= "            <td></td>";
        }

        $niveles = $this->Niveles();
        if (sizeof($niveles) > 1) {
            $this->salida .= "               <td class=\"" . $this->SetStyle("Nivel") . "\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
            $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
            for ($i = 0; $i < sizeof($niveles); $i++) {
                if ($niveles[$i][rango] == $_SESSION['AUTORIZACIONES']['RANGO']) {
                    $this->salida .=" <option value=\"" . $niveles[$i][rango] . "\" selected>" . $niveles[$i][rango] . "</option>";
                } else {
                    $this->salida .=" <option value=\"" . $niveles[$i][rango] . "\">" . $niveles[$i][rango] . "</option>";
                }
            }
        } else {
            $this->salida .= "             <td class=\"" . $this->SetStyle("Nivel") . "\">RANGO: </td>";
            $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"" . $niveles[0][rango] . "\">" . $niveles[0][rango] . "</td>";
            $this->salida .= "            <td></td>";
        }
        if (empty($_SESSION['AUTORIZACIONES']['SEMANAS'])) {
            $_SESSION['AUTORIZACIONES']['SEMANAS'] = 0;
        }
        $this->salida .= "            <td class=\"" . $this->SetStyle("Semanas") . "\" width=\"23%\">SEMANAS COTIZADAS: </td>";
        $this->salida .= "            <td align=\"left\" width=\"10%\"><input type=\"text\" name=\"Semanas\" size=\"8\" value=\"" . $_SESSION['AUTORIZACIONES']['SEMANAS'] . "\" ></td>";
        $this->salida .= "          </tr>";
        if (!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'])) {
            $this->salida .= "          <tr>";
            $this->salida .= "            <td colspan=\"6\" align=\"center\" class=\"label\">OBSERVACION: &nbsp;&nbsp;<textarea name=\"Observacion\" cols=\"65\" rows=\"3\" class=\"textarea\"></textarea></td>";
            $this->salida .= "          </tr>";
        }
        $this->salida .= "       </table><br>";
        $this->salida .= "<br><table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"40%\" align=\"center\">";
        $this->salida .= "  <tr>";
        //$this->salida .= "  <td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"ACEPTAR\"></td>";
        //$this->salida .= "  </form>";
        if ($_SESSION['AUTORIZACIONES']['ESTADO_AFILIACION'] == "RETIRO") {
            $this->salida .= "    <td align=\"center\" width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"AUTORIZAR\" disabled></td>";
        } else {
            $this->salida .= "    <td align=\"center\" width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"AUTORIZAR\"></td>";
        }


        $this->salida .= "      <td align=\"center\" width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"NoAutorizar\" value=\"NO AUTORIZAR\"></td>";
        $this->salida .= " </form>";
        if ($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'] != 'CAJARAPIDA') {
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'FormaTramite');
            $this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
            if ($_SESSION['AUTORIZACIONES']['ESTADO_AFILIACION'] == "RETIRO") {
                $this->salida .= "  <td align=\"center\" width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"TRAMITE\" disabled></td>";
            } else {
                $this->salida .= "  <td align=\"center\" width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"TRAMITE\"></td>";
            }
            $this->salida .= "      </form>";
        }

        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'RetornarAutorizacion');
        $this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
        $this->salida .= "      </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function FormaAnularSolicitud() {
        $this->salida .= ThemeAbrirTabla('ANULAR SOLICITUD No. ' . $_REQUEST['solicitud']);
        $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'AnularSolicitud', array('solicitud' => $_REQUEST['solicitud'], 'descripcion' => $_REQUEST['descripcion']));
        $this->salida .= "       <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "     </table>";
        $this->salida .= "       <table border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= "           <tr>";
        $this->salida .= "    					<td  align=\"center\" colspan=\"2\" class=\"label_mark\">" . $_REQUEST['descripcion'] . "</td>";
        $this->salida .= "            </tr>";
        $this->salida .= "           <tr>";
        $this->salida .= "              <td  width=\"30%\" class=\"" . $this->SetStyle("Observaciones") . "\">OBSERVACIONES ANULACION: </td>";
        $this->salida .= "              <td><textarea  cols=\"80\" rows=\"4\" class=\"textarea\" name=\"observacion\"></textarea></td>";
        $this->salida .= "            </tr>";
        $this->salida .= "       </table>";
        $this->salida .= "       <table align=\"center\" border=\"0\" width=\"50%\">";
        $this->salida .= "    <tr>";
        $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"ACEPTAR\"></form></td>";
        if (empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleSolicitud');
        } else {
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleSolicituTodos');
        }
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"CANCELAR\"></form></td>";
        $this->salida .= "    </tr>";
        $this->salida .= "       </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function FormaCamposBD() {
        $a = ImplodeArrayAssoc($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
        $arreglon = ExplodeArrayAssoc($a);
        $plantilla = $this->PlantilaBD($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']);
        $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td colspan=\"2\">";
        $this->salida .= "            <table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\">DATOS AFILIADO EN LA BASE DE DATOS DE LA ENTIDAD</td>";
        $this->salida .= "               </tr>";
        $i = 0;
        foreach ($arreglon as $k => $v) {
            $mostrar = '';
            $mostrar = $this->CamposMostrarBD($k, $plantilla);
            if (!empty($mostrar[sw_mostrar])) {
                if ($i % 2) {
                    $estilo = "modulo_list_claro";
                } else {
                    $estilo = "modulo_list_oscuro";
                }
                $this->salida .= "         <tr class=\"$estilo\">";
                $this->salida .= "            <td align=\"center\">$k</td>";
                $this->salida .= "            <td align=\"center\">$v</td>";
                $this->salida .= "        </tr>";
                $i++;
            }
        }
        $this->salida .= "           </table>";
        $this->salida .= "               </td>";
        $this->salida .= "               </tr>";
        $this->salida .= "           </table><BR>";
    }

//-----------------------------------------------------------------------------------
}

//fin clase
?>