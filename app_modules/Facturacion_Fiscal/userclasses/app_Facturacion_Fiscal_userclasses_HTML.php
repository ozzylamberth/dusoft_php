<?php

/**
 * $Id: app_Facturacion_Fiscal_userclasses_HTML.php,v 1.9 2011/02/23 21:54:04 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo visual de la facturacion.
 */
/**
 * Clase app_Facturacion_Fiscal_userclasses_HTML
 *
 * Contiene los metodos en html para la presentacion
 */
IncludeClass("ClaseHTML");

class app_Facturacion_Fiscal_userclasses_HTML extends app_Facturacion_Fiscal_user {

    /**
     * Constructor de la clase app_Facturacion_userclasses_HTML
     * El constructor de la clase app_Facturacion_userclasses_HTML se encarga de llamar
     * a la clase app_Facturacion_user quien se encarga de el tratamiento
     * de la base de datos.
     * @return boolean
     */
    function app_Facturacion_Fiscal_userclasses_HTML() {
        $this->salida = '';
        $this->app_Facturacion_Fiscal_user();
        return true;
    }

    /*     * **********************************************************************************
     * Muestra el menu de documentos
     * 
     * @return boolean
     * *********************************************************************************** */

    function FormaMostrarDocumentos() {
        $this->MostrarDocumentos();
        $url[0] = 'app';          //contenedor 
        $url[1] = 'Facturacion_Fiscal';   //m�ulo 
        $url[2] = 'user';          //clase 
        $url[3] = 'Menu'; //m�odo 
        $url[4] = 'documento';       //indice del request
        $titulo[0] = 'DOCUMENTOS';

        $action = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'main');

        if (sizeof($this->Rta) > 0)
            $this->salida .= gui_theme_menu_acceso('TIPOS DE FACTURAS', $titulo, $this->Rta, $url, $action);
        else
            $this->FormaInformacion("LA CONSULTA NO ARROJO RESULTADOS", $action);
        return true;
    }

    /*     * ******************************************************************************* 
     * Funcion que presenta una forma de informacion al usario sobre lo que ha acabado 
     * de ocurrir con la accion que realizo 
     * 
     * @params String $parametro Cadena a mostrar en la forma
     * @return boolean 
     * ******************************************************************************** */

    function FormaInformacion($parametro, $action) {
        $this->salida .= ThemeAbrirTabla('INFORMACI�');
        $this->salida .= "<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
        $this->salida .= "	<tr>\n";
        $this->salida .= "		<td class=\"label\" colspan=\"3\" align=\"center\" ><br>";
        $this->salida .= "				" . $parametro . "<br>\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "</table>\n";
        $this->salida .= "<table align=\"center\" width=\"60%\">\n";
        $this->salida .= "	<tr>\n";
        $this->salida .= "		<form name=\"formaInformacion\" action=\"" . $action . "\" method=\"post\">\n";
        $this->salida .= "			<td align=\"center\">\n";
        $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "		</form>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "</table>\n";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /*     * ******************************************************************************* 
     * 
     * ******************************************************************************** */

    function SetStyle($campo) {
        if ($this->frmError[$campo] || $campo == "MensajeError") {
            if ($campo == "MensajeError") {
                return ("<tr><td class='label_error' colspan='3' align='center'>" . $this->frmError["MensajeError"] . "</td></tr>");
            }
            return ("label_error");
        }
        return ("label");
    }

    /*     * *******************************************************************************
     *
     * ******************************************************************************** */

    function EncabezadoEmpresa() {
        $datos = $this->DatosEncabezadoEmpresa();
        $this->salida .= "<br>\n";
        $this->salida .= "	<table  border=\"0\" class=\"modulo_table_list\" width=\"80%\" align=\"center\" >\n";
        $this->salida .= " 		<tr class=\"modulo_table_title\" height=\"21\">\n";
        $this->salida .= " 			<td width=\"10%\">EMPRESA</td>\n";
        $this->salida .= " 			<td class=\"modulo_list_claro\" >" . $datos[razon_social] . "</td>\n";
        $this->salida .= " 		</tr>\n";
        $this->salida .= " </table>\n";
        //$this->salida .= " <td class=\"modulo_list_claro\">".$datos[descripcion]."</td>";
        //$this->salida .= " <td>CENTRO UTILIDAD</td>";
        //$this->salida .= " <td>PUNTO DE FACTURACION</td>";     
        //$this->salida .= " <td class=\"modulo_list_claro\" >".$datos[desfactura]."</td>";
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

    function RetornarBarraC() {
        if ($this->limit >= $this->conteo) {
            return '';
        }
        $paso = $_REQUEST['paso'];
        if (empty($paso)) {
            $paso = 1;
        }
        $vec = '';
        foreach ($_REQUEST as $v => $v1) {
            if ($v != 'modulo' and $v != 'metodo' and $v != 'SIIS_SID' and $v != 'Of') {
                $vec[$v] = $v1;
            }
        }
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarCuenta', $vec);
        $barra = $this->CalcularBarra($paso);
        $numpasos = $this->CalcularNumeroPasos($this->conteo);
        $colspan = 1;

        $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if ($paso > 1) {
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset(1) . "&paso=1'>&lt;</a></td>";
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso - 1) . "&paso=" . ($paso - 1) . "'>&lt;&lt;</a></td>";
            $colspan+=1;
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
            }
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
            $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=" . $valor . " align='center'>P�ina $paso de $numpasos</td><tr></table><br>";
        } else {
            if ($numpasos > 10) {
                $valor = 10 + 5;
            } else {
                $valor = $numpasos + 5;
            }
            $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=" . $valor . " align='center'>P�ina $paso de $numpasos</td><tr></table><br>";
        }
    }

    function RetornarBarra() {
        if ($this->limit >= $this->conteo) {
            return '';
        }
        $paso = $_REQUEST['paso'];
        if (empty($paso)) {
            $paso = 1;
        }
        $vec = '';
        foreach ($_REQUEST as $v => $v1) {
            if ($v != 'modulo' and $v != 'metodo' and $v != 'SIIS_SID' and $v != 'Of') {
                $vec[$v] = $v1;
            }
        }
        if (empty($_SESSION['FACTURACION']['CERRADAS'])) {
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarCuenta', $vec);
        } else {
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarFacturas', $vec);
        }
        $barra = $this->CalcularBarra($paso);
        $numpasos = $this->CalcularNumeroPasos($this->conteo);
        $colspan = 1;

        $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if ($paso > 1) {
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset(1) . "&paso=1'>&lt;</a></td>";
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso - 1) . "&paso=" . ($paso - 1) . "'>&lt;&lt;</a></td>";
            $colspan+=1;
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
            }
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
            $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=" . $valor . " align='center'>P�ina $paso de $numpasos</td><tr></table><br>";
        } else {
            if ($numpasos > 10) {
                $valor = 10 + 5;
            } else {
                $valor = $numpasos + 5;
            }
            $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=" . $valor . " align='center'>P�gina $paso de $numpasos</td><tr></table><br>";
        }
    }

    /**
     *
     */
    function FormaMenus() {
        unset($_SESSION['ENVIOS']['TERCERO']);
        $fct = new app_Facturacion_Permisos();
        $menu = $fct->permisos_opcionesFacturacion(SessionGetVar("EmpresaFacturacion"));

        $this->salida .= ThemeAbrirTabla('MENU FACTURACION');
        $this->salida .= "            <br>";
        $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU FACTURACION</td>";
        $this->salida .= "               </tr>";
        if (!empty($menu)) {
            if ($menu['sw_cuentas'] == '1') {
                $this->salida .= "               <tr>";
                $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarFormaMetodoBuscar');

                $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accion\">CUENTAS (Activas - Inactivas - Cuadradas)</a></td>";
                $this->salida .= "               </tr>";
            }
            if ($menu['sw_fact_agrupada'] == '1') {
                $x = $this->responsablesAgrupados();
                if (!empty($x)) {
                    $this->salida .= "               <tr>";
                    //$accionF=ModuloGetURL('app','Facturacion_Fiscal','user','LlamarFormaMetodoBuscar',array('SWCUENTAS'=>'Agrupadas'));
                    $accionF = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarFormaBuscarAgrupadas', array('SWCUENTAS' => 'Agrupadas'));
                    $this->salida .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionF\">FACTURAS AGRUPADAS</a></td>";
                    $this->salida .= "               </tr>";
                }
            }
            if ($menu['sw_factura'] == '1') {
                $this->salida .= "               <tr>";
                $accionF = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarFacturas');
                $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionF\">BUSCAR FACTURAS</a></td>";
                $this->salida .= "               </tr>";
            }
            if ($menu['sw_envios'] == '1') {
                $this->salida .= "               <tr>";
                $accionF = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaResponsable');
                $this->salida .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionF\">ENVIOS</a></td>";
                $this->salida .= "               </tr>";
            }
            if ($menu['sw_manejo_envios'] == '1') {
                $this->salida .= "               <tr>";
                
                $accionF = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarRad', array('centro_utilidad' => $_SESSION['CentroUtilidadFacturacion'], 'empresa_id' => $_SESSION['EmpresaFacturacion']));
                $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionF\">MANEJO ENVIOS</a></td>";
                $this->salida .= "               </tr>";
            }
            if ($menu['sw_reportes'] == '1') {
                $this->salida .= "               <tr>";
                $accionF = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaListadoResportes');
                $this->salida .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionF\">REPORTES</a></td>";
                $this->salida .= "               </tr>";
            }
            if ($menu['sw_rips'] == '1') {
                $this->salida .= "               <tr>";
                $accionF = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaMenuRips');
                $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionF\">GENERAR RIPS</a></td>";
                $this->salida .= "               </tr>";
            }
            if ($menu['sw_admin_sin_hc'] == '1') {
                //APERTURA DE ADMISION SIN ATENCION
                $this->salida .= "               <tr>";
                $accionapertura = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FrmAperturaAdmision');
                $this->salida .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionapertura\" title=\"Admisi�n sin Historia Cl�nica\">ADMISI�N SIN HC</a></td>";
                $this->salida .= "               </tr>";
            }
            if ($menu['sw_fact_anuladas'] == '1') {
                //IMPRIMIR FACTURAS ANULADAS
                $this->salida .= "               <tr>";
                $accionapertura = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarFacturas', array('prnAnuladas' => '1'));
                $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionapertura\" title=\"Impresi� de facturas anuladas\">FACTURAS ANULADAS</a></td>";
                $this->salida .= "               </tr>";
            }
        } else {
            $this->salida .= "               <tr>";
            $this->salida .= "                  <td align=\"center\" class=\"label_error\">USUARIO SIN PERMISO AL MENU DE FACTURACION !</td>";
            $this->salida .= "               </tr>";
        }

        $this->salida .= "           </table>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaMostrarDocumentos');
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

//---------------------------------------REPORTES-----------------------------------------------
    function FormaCuentasCobro() {
        $this->salida .= ThemeAbrirTabla('CUENTAS DE COBRO');
        $this->EncabezadoEmpresa();
        $this->salida .= "            <br>";
        $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU REPORTES</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'SeleccionarFecha');
        //$accion=ModuloGetURL('app','Facturacion_Fiscal','user','FormaBuscarUsuario');
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accion\">Facturas Generadas por Usuario</a></td>";
        $this->salida .= "               </tr>";
        $this->salida .= "           </table>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Menu');
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function FormaListadoResportes() {
        $this->salida .= ThemeAbrirTabla('FACTURACION');
        $this->EncabezadoEmpresa();
        $this->salida .= "            <br>";
        $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU REPORTES</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'SeleccionarFecha');
        //$accion=ModuloGetURL('app','Facturacion_Fiscal','user','FormaBuscarUsuario');
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accion\">Facturas Generadas por Usuario</a></td>";
        $this->salida .= "               </tr>";
        //COMPROBANTE DE INFORME DIARIO DE FACTURAS
        $this->salida .= "               <tr>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FrmComprobanteInformeDiario');
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accion\">Comprobante de informe diario</a></td>";
        $this->salida .= "               </tr>";
        UNSET($_SESSION[Listado]);
        //FIN COMPROBANTE DE INFORME DIARIO DE FACTURAS
        //INFORME DIARIO DE FACTURAS Y NOTAS DE ANULACI�
        $this->salida .= "               <tr>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FrmFacturasNotasAnulacion');
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accion\">Informe diario Facturaci� - Notas de anulaci�</a></td>";
        $this->salida .= "               </tr>";
        UNSET($_SESSION[Listado]);
        //FIN INFORME DIARIO DE FACTURAS Y NOTAS DE ANULACI�
        //COMPROBANTE DE INFORME DIARIO DE FACTURAS
        $this->salida .= "               <tr>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FrmInformDiarioRecaudoRecibos');
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accion\">Informe diario detallado recibos y notas de caja</a></td>";
        $this->salida .= "               </tr>";
        UNSET($_SESSION[Listado]);
        //FIN COMPROBANTE DE INFORME DIARIO DE FACTURAS
        $this->salida .= "           </table>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Menu');
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //FORMA DETALLADO DE RECIBOS Y NOTAS DE CAJA
    function FrmInformDiarioRecaudoRecibos() {
        $_SESSION[Listado] = $dat = $this->GetRecaudoRecibosNotas($_REQUEST['offset']);
        $Empresa = $_SESSION[FACTURACION][EMPRESA];
        $mostrar = "\n<SCRIPT>\n";
        $mostrar.="function mOvr(src,clrOver) {;\n";
        $mostrar.="src.style.background = clrOver;\n";
        $mostrar.="}\n";

        $mostrar.="function mOut(src,clrIn) {\n";
        $mostrar.="src.style.background = clrIn;\n";
        $mostrar.="}\n";
        $mostrar .= "  function myOnDragStart(ele, mx, my)\n";
        $mostrar .= "  {\n";
        $mostrar .= "    window.status = '';\n";
        $mostrar .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
        $mostrar .= "    else xZIndex(ele, hiZ++);\n";
        $mostrar .= "    ele.myTotalMX = 0;\n";
        $mostrar .= "    ele.myTotalMY = 0;\n";
        $mostrar .= "  }\n";
        $mostrar .= "  function myOnDrag(ele, mdx, mdy)\n";
        $mostrar .= "  {\n";
        $mostrar .= "    if (ele.id == titulo) {\n";
        $mostrar .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
        $mostrar .= "    }\n";
        $mostrar .= "    else {\n";
        $mostrar .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $mostrar .= "    }  \n";
        $mostrar .= "    ele.myTotalMX += mdx;\n";
        $mostrar .= "    ele.myTotalMY += mdy;\n";
        $mostrar .= "  }\n";
        $mostrar .= "  function MostrarSpan(Seccion)\n";
        $mostrar .= "  { \n";
        $mostrar .= "    e = xGetElementById(Seccion);\n";
        $mostrar .= "    e.style.display = \"\";\n";
        $mostrar .= "  }\n";
        $mostrar .= "	function comprobantediario(dir)\n";
        $mostrar .= "	{\n";
        $mostrar .= "		var url=dir;\n";
        $mostrar .= "		window.open(url,'COMPROBANTE DIARIO','screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes');\n";
        $mostrar .= "	}\n";
        $mostrar .="</SCRIPT>\n";
        $this->salida = "$mostrar";
        $backgrounds = array('modulo_list_claro' => '#DDDDDD', 'modulo_list_oscuro' => '#CCCCCC');

        //unset($_SESSION['FACTURACION_RECEPCION']['DATOS']);
        $this->salida.= ThemeAbrirTabla('COMPROBANTE', '95%');
        $this->salida.= $this->EncabezadoEmpresa();
        $this->salida.= "<table border=\"0\" align=\"center\"   width=\"100%\">";
        $this->salida.= "<tr>";
        $this->salida.= "<td width=\"100%\" align=\"right\">";
        $action2 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaListadoResportes');
        $this->salida .= " <form name=\"forma\" action=\"$action2\" method=\"post\">";
        $this->salida .= " <a href=\"$action2\" title=\"VOLVER\"><img src=\"" . GetThemePath() . "/images/boton.png\" border=\"0\" width=\"15\" height=\"15\"></a></form>";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida.= "<form name=\"formaagrupadas\" action=\"$accion\" method=\"post\">";
        $this->salida.= "</table><br>";
        $this->salida.= "<table border=\"0\" align=\"center\"   width=\"75%\">";
        $this->salida.= "<form name=\"formaagrupadas\" action=\"$accion\" method=\"post\">";
        //$this->salida.= "<tr class='formulacion_table_list'>";
        //$this->salida.= "<tr class='modulo_table_title'>";
        $this->salida.= "<tr class='modulo_table_list_title'>";
        $this->salida.= "<td width=\"100%\" align=\"CENTER\">DETALLADO RECIBOS - NOTAS DE CAJA";
        $this->salida.= "</td>";
        $this->salida.= "</tr>";
        $this->salida.= "</table><br>";

        if (is_array($dat[RECIBOS]) AND sizeof($dat[RECIBOS]) > 0) {
            $total_factura = $Tabono_efectivo = $Tabono_cheque = $Tabono_tarjetas = $Tabono_chequespf = $Tabono_letras = $valor_total = 0;

            $estilo = 'modulo_table_title';
            $backgrounds = "#DDDDDD";
            $this->salida.= "<table  border=\"0\" align=\"center\" width=\"85%\">";
            $this->salida.= "<tr class=\"$estilo\">";
            $this->salida.= " <td width=\"100%\" colspan=\"6\" align=\"center\"><b><font size=\"2\">RECIBOS DE CAJA</font></b>";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";

            //$estilo='modulo_table_title';
            $this->salida.= "<tr class=\"$estilo\">";
            $this->salida.= " <td width=\"5%\" align=\"center\">RECIBO";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"25%\" align=\"center\">CLIENTE";
            $this->salida.= " </td>";
            /* 						$this->salida.= " <td width=\"20%\" align=\"center\">PACIENTE";
              $this->salida.= " </td>"; */
            $this->salida.= " <td width=\"8%\" align=\"center\">V. EFECTIVO";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"center\">V. CHEQUES";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"center\">V. TARJETAS";
            $this->salida.= " </td>";
            /* 						$this->salida.= " <td width=\"8%\" align=\"center\">V. CHEQUESPF";
              $this->salida.= " </td>";
              $this->salida.= " <td width=\"8%\" align=\"center\">V. LETRAS";
              $this->salida.= " </td>"; */
            $this->salida.= " <td width=\"10%\" align=\"center\">V. TOTAL";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";

            $total_usuario = $abono_efectivo = $abono_cheque = $abono_tarjetas = $abono_chequespf = $abono_letras = $valor_total = 0;
            $var = $dat[RECIBOS];
            for ($i = 0; $i < sizeof($var); $i++) {

                //$numeracion_inicial = $var[$k][prefijo].$var[$k][recibo_caja];
                $abono_efectivo += $var[$i][total_efectivo];
                $abono_cheque += $var[$i][total_cheques];
                $abono_tarjetas += $var[$i][total_tarjetas];
                /* 								$abono_chequespf += $var[$k][abono_chequespf];
                  $abono_letras += $var[$k][abono_letras]; */
                $valor_total += $var[$i][total_abono];
                $total_usuario += $var[$i][total_abono];
                $estilo = 'modulo_list_oscuro';
                $backgrounds = "#DDDDDD";
                if ($i % 2 == 0) {
                    $estilo = 'modulo_list_claro';
                    $backgrounds = "#CCCCCC";
                }
                /* 								if($var[$i][sw_clase_factura] == 'CONTADO' )
                  {
                  $cambia = '#7A99BB';
                  }
                  else
                  {
                  $cambia = '#FFFFFF';
                  } */
                $this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'#FFFFFF');>";
                $this->salida.= " <td width=\"5%\" align=\"center\">" . $var[$i][prefijo] . ' ' . $var[$i][recibo_caja] . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"55%\" align=\"center\">" . $var[$i][nombre_tercero] . "";
                $this->salida.= " </td>";
                /* 								$this->salida.= " <td width=\"20%\" align=\"center\">".$var[$k][paciente]."";
                  $this->salida.= " </td>"; */
                $this->salida.= " <td width=\"10%\" align=\"right\">" . FormatoValor($var[$i][total_efectivo]) . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"10%\" align=\"right\">" . FormatoValor($var[$i][total_cheques]) . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"10%\" align=\"right\">" . FormatoValor($var[$i][total_tarjetas]) . "";
                $this->salida.= " </td>";
                /* 								$this->salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($var[$k][abono_chequespf])."";
                  $this->salida.= " </td>";
                  $this->salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($var[$k][abono_letras])."";
                  $this->salida.= " </td>"; */
                $this->salida.= " <td width=\"10%\" align=\"right\">" . FormatoValor($var[$i][total_abono]) . "";
                $this->salida.= " </td>";
                $this->salida.= "</tr>";
            }
            $total_factura += $total_usuario;
            $Tabono_efectivo += $abono_efectivo;
            $Tabono_cheque += $abono_cheque;
            $Tabono_tarjetas += $abono_tarjetas;
            /* 						$Tabono_chequespf += $abono_chequespf;
              $Tabono_letras += $abono_letras; */
            $estilo = 'modulo_table_title';
            $this->salida.= "<tr class=\"$estilo\">";
            $this->salida.= " <td width=\"100%\" colspan=\"2\" align=\"right\"><b>SUBTOTALES:</b></td>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($abono_efectivo) . "";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($abono_cheque) . "";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($abono_tarjetas) . "";
            $this->salida.= " </td>";
            /* 						$this->salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_chequespf)."";
              $this->salida.= " </td>";
              $this->salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_letras)."";
              $this->salida.= " </td>"; */
            $this->salida.= " <td width=\"100%\" align=\"center\">$&nbsp;<font color=\"red\"><b>" . FormatoValor($total_usuario) . "</font></b>";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";

// 						$estilo = 'modulo_list_oscuro'; $backgrounds = "#DDDDDD";
// 						$this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"".$backgrounds."\"); onmouseover=mOvr(this,'#FFFFFF');>";
// 						$this->salida.= " <td width=\"100%\" colspan=\"9\" align=\"left\"><b>NUMERACI� ".$var[$i][sw_clase_factura]." DEL ".$numeracion_final." AL ".$numeracion_inicial." </b></td>";
// 						$this->salida.= "</tr>";

            $this->salida.= "<tr>";
            $this->salida.= " <td colspan=\"6\"width=\"100%\" align=\"center\">&nbsp;";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";
            $this->salida.= "</table>";
        } else {
            $this->salida .= "<br><center><b>NO HAY MOVIMIENTO DE RECIBOS</b></center>";
        }

        //DEVOLUCIONES
        if (is_array($dat[DEVOLUCIONES]) AND sizeof($dat[DEVOLUCIONES]) > 0) {
            $estilo = 'modulo_table_title';
            $backgrounds = "#DDDDDD";
            $this->salida.= "<table  border=\"0\" align=\"center\" width=\"55%\">";
            $this->salida.= "<tr class=\"$estilo\">";
            $this->salida.= " <td width=\"100%\" colspan=\"3\" align=\"center\"><b><font size=\"2\">RECIBOS DE DEVOLUCIONES</font></b>";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";

            $this->salida.= "<tr class=\"$estilo\">";
            $this->salida.= " <td width=\"15%\" align=\"center\">RECIBO DV. Nro";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"75%\" align=\"center\">CLIENTE";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"10%\" align=\"center\">V. DEVOLUCI�";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";

            $total_usuario = $total_devolucion = 0;
            $var = $dat[DEVOLUCIONES];
            for ($i = 0; $i < sizeof($var); $i++) {

                $total_devolucion += $var[$i][total_devolucion];
                $total_usuario += $var[$i][total_devolucion];
                $valor_total -= $var[$i][total_devolucion];
                $estilo = 'modulo_list_oscuro';
                $backgrounds = "#DDDDDD";
                if ($i % 2 == 0) {
                    $estilo = 'modulo_list_claro';
                    $backgrounds = "#CCCCCC";
                }
                $this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'#FFFFFF');>";
                $this->salida.= " <td width=\"15%\" align=\"center\">" . $var[$i][prefijo] . ' ' . $var[$i][recibo_caja] . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"75%\" align=\"center\">" . $var[$i][nombre_tercero] . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"10%\" align=\"right\">" . FormatoValor($var[$i][total_devolucion]) . "";
                $this->salida.= " </td>";
                $this->salida.= "</tr>";
            }
            $total_factura -= $total_usuario;
            $Tdevoluciones -= $total_devolucion;
            $estilo = 'modulo_table_title';
            $this->salida.= "<tr class=\"$estilo\">";
            $this->salida.= " <td width=\"90%\" align=\"right\" colspan=\"2\"><b>SUBTOTALES:</b></td>";
            $this->salida.= " <td width=\"10%\" align=\"center\">$&nbsp;<font color=\"red\" size=\"3\"><b>-" . FormatoValor($total_usuario) . "</font></b>";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";


            $this->salida.= "<tr>";
            $this->salida.= " <td colspan=\"3\"width=\"100%\" align=\"center\">&nbsp;";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";
            $this->salida.= "</table>";
        } else {
            $this->salida .= "<br><center><b>NO HAY MOVIMIENTO DE RECIBOS DEVOLUCIONES</b></center>";
        }
        //FIN DEVOLUCIONES
        //PAGARES
        if (is_array($dat[PAGARES]) AND sizeof($dat[PAGARES]) > 0) {
            $estilo = 'modulo_table_title';
            $backgrounds = "#DDDDDD";
            $this->salida.= "<table  border=\"0\" align=\"center\" width=\"55%\">";
            $this->salida.= "<tr class=\"$estilo\">";
            $this->salida.= " <td width=\"100%\" colspan=\"4\" align=\"center\"><b><font size=\"2\">PAGARES</font></b>";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";

            $this->salida.= "<tr class=\"$estilo\">";
            $this->salida.= " <td width=\"15%\" align=\"center\">PAGARE Nro";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"65%\" align=\"center\">CLIENTE";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"10%\" align=\"center\">VALOR";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"10%\" align=\"center\">Observ.";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";

            $total_usuario = $total_devolucion = 0;
            $var = $dat[PAGARES];
            for ($i = 0; $i < sizeof($var); $i++) {

                $total_pagare += $var[$i][valor];
                $total_usuario += $var[$i][valor];
                $valor_total += $var[$i][valor];
                $estilo = 'modulo_list_oscuro';
                $backgrounds = "#DDDDDD";
                if ($i % 2 == 0) {
                    $estilo = 'modulo_list_claro';
                    $backgrounds = "#CCCCCC";
                }
                $this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'#FFFFFF');>";
                $this->salida.= " <td width=\"15%\" align=\"center\">" . $var[$i][prefijo] . ' ' . $var[$i][numero] . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"65%\" align=\"center\">" . $var[$i][nombre_tercero] . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"10%\" align=\"right\">" . FormatoValor($var[$i][valor]) . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"10%\" align=\"center\">";
                $this->salida .= "	<img src=\"" . GetThemePath() . "/images/informacion.png\" border=\"0\" title=\"" . $var[$i][observacion] . "\">\n";
                $this->salida.= " </td>";
                $this->salida.= "</tr>";
            }
            $total_factura += $total_usuario;
            $Tpagares+= $total_pagare;
            $estilo = 'modulo_table_title';
            $this->salida.= "<tr class=\"$estilo\">";
            $this->salida.= " <td width=\"90%\" align=\"right\" colspan=\"2\"><b>SUBTOTALES:</b></td>";
            $this->salida.= " <td width=\"10%\" align=\"center\">$&nbsp;<font color=\"red\" size=\"2\"><b>" . FormatoValor($total_usuario) . "</font></b>";
            $this->salida.= " <td width=\"10%\" align=\"center\">&nbsp;";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";

            $this->salida.= "<tr>";
            $this->salida.= " <td colspan=\"4\"width=\"100%\" align=\"center\">&nbsp;";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";
            $this->salida.= "</table>";
        } else {
            $this->salida .= "<br><center><b>NO HAY MOVIMIENTO DE PAGARES</b></center>";
        }
        //FIN PAGARES
//TOTALES MEDIOS DE PAGO
        $this->salida.= "<table  border=\"0\" align=\"center\" width=\"70%\">";
        $this->salida.= "<tr class=\"modulo_table_title\">";
        $this->salida.= " <td width=\"40%\" align=\"right\">TOTALES";
        $this->salida.= " </td>";
        $this->salida.= " <td width=\"10%\" align=\"center\">T. EFECTIVO";
        $this->salida.= " </td>";
        $this->salida.= " <td width=\"10%\" align=\"center\">T. CHEQUES";
        $this->salida.= " </td>";
        $this->salida.= " <td width=\"10%\" align=\"center\">T. TARJETAS";
        $this->salida.= " </td>";
        $this->salida.= " <td width=\"10%\" align=\"center\">T. DEVOLUCIONES";
        $this->salida.= " </td>";
        $this->salida.= " <td width=\"10%\" align=\"center\">T. PAGARES";
        $this->salida.= " </td>";
        $this->salida.= " <td width=\"10%\" align=\"center\">TOTALES";
        $this->salida.= " </td>";
        $this->salida.= "</tr>";
        $this->salida.= "<tr class=\"modulo_list_claro\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'#FFFFFF');>";
        $this->salida.= " <td width=\"40%\" align=\"right\">&nbsp;";
        $this->salida.= " </td>";
        $this->salida.= " <td width=\"10%\" align=\"right\">" . FormatoValor($Tabono_efectivo) . "";
        $this->salida.= " </td>";
        $this->salida.= " <td width=\"10%\" align=\"right\">" . FormatoValor($Tabono_cheque) . "";
        $this->salida.= " </td>";
        $this->salida.= " <td width=\"10%\" align=\"right\">" . FormatoValor($Tabono_tarjetas) . "";
        $this->salida.= " </td>";
        $this->salida.= " <td width=\"10%\" align=\"right\"><font color=\"red\"><b>-" . FormatoValor($Tdevoluciones) . "</b></font>";
        $this->salida.= " </td>";
        $this->salida.= " <td width=\"10%\" align=\"right\">" . FormatoValor($Tpagares) . "";
        $this->salida.= " </td>";
        $this->salida.= " <td width=\"10%\" align=\"right\">" . FormatoValor($valor_total) . "";
        $this->salida.= " </td>";
        $this->salida.= "</tr>";
        $this->salida.= "<tr class=\"modulo_list_claro\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'#5efb6e');>";
        $this->salida.= " <td width=\"100%\" colspan=\"6\" align=\"right\"><b>TOTAL FACTURACI�N: </b>";
        $this->salida.= " <td width=\"100%\" align=\"center\">$&nbsp;&nbsp;<font color=\"red\" size=\"2\"><b>" . FormatoValor($total_factura) . "</font></b>";
        $this->salida.= " </td>";
        $this->salida.= "</tr>";
//FIN TOTALES MEDIOS DE PAGO
        $this->salida.= "<tr>";
        $this->salida.= " <td align=\"center\" colspan = \"5\">&nbsp;</td>";
        $this->salida.= "</tr>";
        $this->salida.= "<tr>";
        $direccion = "app_modules/Facturacion_Fiscal/reports/html/ReporteDetalladoRecibosNotas.php?";
        $this->salida.= "  <td align=\"center\" colspan = \"9\"><input type=\"button\" value=\"Imprimir\" class=\"input-submit\" onclick=\"javascript:comprobantediario('$direccion');\"></td>";
        $this->salida.= "</tr>";
        $this->salida.= "</table>";
        //$Paginador = new ClaseHTML();
        //$this->salida .= "      <br>\n";
        //$action = ModuloGetURL("app","Facturacion_Fiscal","user","FrmComprobanteInformeDiario");
        //$this->salida .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$action);

        $this->salida.= "</form>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

    //FIN FORMA DETALLADO DE RECIBOS Y NOTAS DE CAJA
    //FORMA  FACTURAS - NOTAS DE ANULACION
    function FrmFacturasNotasAnulacion() {
        $_SESSION[Listado] = $var = $this->GetFacturacionNotasAnulacion($_REQUEST['offset']);
        $Empresa = $_SESSION[FACTURACION][EMPRESA];
        $mostrar = "\n<SCRIPT>\n";
        $mostrar.="function mOvr(src,clrOver) {;\n";
        $mostrar.="src.style.background = clrOver;\n";
        $mostrar.="}\n";

        $mostrar.="function mOut(src,clrIn) {\n";
        $mostrar.="src.style.background = clrIn;\n";
        $mostrar.="}\n";
        $mostrar .= "  function myOnDragStart(ele, mx, my)\n";
        $mostrar .= "  {\n";
        $mostrar .= "    window.status = '';\n";
        $mostrar .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
        $mostrar .= "    else xZIndex(ele, hiZ++);\n";
        $mostrar .= "    ele.myTotalMX = 0;\n";
        $mostrar .= "    ele.myTotalMY = 0;\n";
        $mostrar .= "  }\n";
        $mostrar .= "  function myOnDrag(ele, mdx, mdy)\n";
        $mostrar .= "  {\n";
        $mostrar .= "    if (ele.id == titulo) {\n";
        $mostrar .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
        $mostrar .= "    }\n";
        $mostrar .= "    else {\n";
        $mostrar .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $mostrar .= "    }  \n";
        $mostrar .= "    ele.myTotalMX += mdx;\n";
        $mostrar .= "    ele.myTotalMY += mdy;\n";
        $mostrar .= "  }\n";
        $mostrar .= "  function MostrarSpan(Seccion)\n";
        $mostrar .= "  { \n";
        $mostrar .= "    e = xGetElementById(Seccion);\n";
        $mostrar .= "    e.style.display = \"\";\n";
        $mostrar .= "  }\n";
        $mostrar .= "	function comprobantediario(dir)\n";
        $mostrar .= "	{\n";
        $mostrar .= "		var url=dir;\n";
        $mostrar .= "		window.open(url,'COMPROBANTE DIARIO','screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes');\n";
        $mostrar .= "	}\n";
        $mostrar .="</SCRIPT>\n";
        $this->salida = "$mostrar";
        $backgrounds = array('modulo_list_claro' => '#DDDDDD', 'modulo_list_oscuro' => '#CCCCCC');

        //unset($_SESSION['FACTURACION_RECEPCION']['DATOS']);
        $this->salida.= ThemeAbrirTabla('COMPROBANTE', '95%');
        $this->salida.= $this->EncabezadoEmpresa();
        $this->salida.= "<table border=\"0\" align=\"center\"   width=\"100%\">";
        $this->salida.= "<tr>";
        $this->salida.= "<td width=\"100%\" align=\"right\">";
        $action2 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaListadoResportes');
        $this->salida .= " <form name=\"forma\" action=\"$action2\" method=\"post\">";
        $this->salida .= " <a href=\"$action2\" title=\"VOLVER\"><img src=\"" . GetThemePath() . "/images/boton.png\" border=\"0\" width=\"15\" height=\"15\"></a></form>";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida.= "<form name=\"formaagrupadas\" action=\"$accion\" method=\"post\">";
        $this->salida.= "</table><br>";
        $this->salida.= "<table border=\"0\" align=\"center\"   width=\"100%\">";
        $this->salida.= "<form name=\"formaagrupadas\" action=\"$accion\" method=\"post\">";
        //$this->salida.= "<tr class='formulacion_table_list'>";
        //$this->salida.= "<tr class='modulo_table_title'>";
        $this->salida.= "<tr class='modulo_table_list_title'>";
        $this->salida.= "<td width=\"100%\" align=\"CENTER\">INFORME FACTURAS - NOTAS ANULACIÖN";
        $this->salida.= "</td>";
        $this->salida.= "</tr>";
        $this->salida.= "</table><br>";

        if (is_array($var) AND sizeof($var) > 0) {
            $cont = true;
            $total_factura = $Tabono_efectivo = $Tabono_cheque = $Tabono_tarjetas = $Tabono_chequespf = $Tabono_letras = $valor_total = 0;
            for ($i = 0; $i < sizeof($var);) {
                $k = $i;
                $estilo = 'modulo_table_title';
                $backgrounds = "#DDDDDD";
                $this->salida.= "<table  border=\"0\" align=\"center\"   width=\"100%\">";
                $this->salida.= "<tr class=\"$estilo\">";
                $this->salida.= " <td width=\"100%\" colspan=\"9\" align=\"center\"><b><font size=\"2\">FACTURACI�N " . $var[$k][sw_clase_factura] . "</font></b>";
                $this->salida.= " </td>";
                $this->salida.= "</tr>";

                //$estilo='modulo_table_title';
                $this->salida.= "<tr class=\"$estilo\">";
                $this->salida.= " <td width=\"5%\" align=\"center\">FACTURA";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"25%\" align=\"center\">CLIENTE";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"20%\" align=\"center\">PACIENTE";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"center\">EFECTIVO";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"center\">CHEQUES";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"center\">TARJETAS";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"center\">CHEQUESPF";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"center\">LETRAS";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"10%\" align=\"center\">VALOR $";
                $this->salida.= " </td>";
                $this->salida.= "</tr>";

                $total_usuario = $abono_efectivo = $abono_cheque = $abono_tarjetas = $abono_chequespf = $abono_letras = 0;
                //while($var[$i][usuario_id]==$var[$k][usuario_id])
                while ($var[$i][sw_clase_factura] == $var[$k][sw_clase_factura]) {
                    $numeracion_inicial = $var[$k][prefijo] . $var[$k][factura_fiscal];
                    if ($var[$k][estado_factura] == 3) {
                        $abono_efectivo -= $var[$k][abono_efectivo];
                        $abono_cheque -= $var[$k][abono_cheque];
                        $abono_tarjetas -= $var[$k][abono_tarjetas];
                        $abono_chequespf -= $var[$k][abono_chequespf];
                        $abono_letras -= $var[$k][abono_letras];
                        $valor_total -= $var[$k][valor];
                        $total_usuario -= $var[$k][valor];
                    } else {
                        $abono_efectivo += $var[$k][abono_efectivo];
                        $abono_cheque += $var[$k][abono_cheque];
                        $abono_tarjetas += $var[$k][abono_tarjetas];
                        $abono_chequespf += $var[$k][abono_chequespf];
                        $abono_letras += $var[$k][abono_letras];
                        $valor_total += $var[$k][valor];
                        $total_usuario += $var[$k][valor];
                    }
                    $estilo = 'modulo_list_oscuro';
                    $backgrounds = "#DDDDDD";
                    if ($k % 2 == 0) {
                        $estilo = 'modulo_list_claro';
                        $backgrounds = "#CCCCCC";
                    }
                    if ($var[$i][sw_clase_factura] == 'CONTADO') {
                        $cambia = '#7A99BB';
                    } else {
                        $cambia = '#FFFFFF';
                    }

                    $this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'$cambia');>";
                    $this->salida.= " <td width=\"5%\" align=\"center\">" . $var[$k][prefijo] . ' ' . $var[$k][factura_fiscal] . "";
                    $this->salida.= " </td>";
                    $this->salida.= " <td width=\"25%\" align=\"center\">" . $var[$k][cliente] . "";
                    $this->salida.= " </td>";
                    $this->salida.= " <td width=\"20%\" align=\"center\">" . $var[$k][paciente] . "";
                    $this->salida.= " </td>";
                    if ($var[$k][estado_factura] == 3) {
                        $this->salida.= " <td width=\"8%\" align=\"right\"><font color=\"red\">" . FormatoValor($var[$k][abono_efectivo]) . "</font>";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"8%\" align=\"right\"><font color=\"red\">" . FormatoValor($var[$k][abono_cheque]) . "</font>";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"8%\" align=\"right\"><font color=\"red\">" . FormatoValor($var[$k][abono_tarjetas]) . "</font>";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"8%\" align=\"right\"><font color=\"red\">" . FormatoValor($var[$k][abono_chequespf]) . "</font>";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"8%\" align=\"right\"><font color=\"red\">" . FormatoValor($var[$k][abono_letras]) . "</font>";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"10%\" align=\"right\"><font color=\"red\">" . FormatoValor($var[$k][valor]) . "</font>";
                        $this->salida.= " </td>";
                    } else {
                        $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($var[$k][abono_efectivo]) . "";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($var[$k][abono_cheque]) . "";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($var[$k][abono_tarjetas]) . "";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($var[$k][abono_chequespf]) . "";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($var[$k][abono_letras]) . "";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"10%\" align=\"right\">" . FormatoValor($var[$k][valor]) . "";
                        $this->salida.= " </td>";
                    }
                    $this->salida.= "</tr>";

                    //NOTAS DE ANULACION
                    if ($var[$k][estado_factura] == 3) {
                        $this->salida.= "<tr class=\"modulo_table_title\">";
                        $this->salida.= " <td width=\"100%\" align=\"left\" colspan=\"9\">NOTA DE ANULACI�N";
                        $this->salida.= " </td>";
                        $this->salida.= "</tr>";
                        $this->salida.= "<tr class=\"modulo_table_title\">";
                        $this->salida.= " <td width=\"8%\" align=\"center\">Nro. Nota";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"8%\" align=\"center\">Fecha Registro";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"45%\" align=\"center\" colspan=\"3\">Usuario";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"8%\" align=\"center\" colspan=\"4\">Valor";
                        $this->salida.= " </td>";
                        $this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'$cambia');>";
                        $this->salida.= " <td width=\"8%\" align=\"center\"><font color=\"red\">" . $var[$k][prefijo_nota] . $var[$k][nota_credito_id] . "</font>";
                        $this->salida.= " </td>";
                        $fecha_nota = explode(' ', $var[$k][fecha_registro_nota]);
                        $this->salida.= " <td width=\"8%\" align=\"center\">" . $fecha_nota[0] . "";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"45%\" align=\"center\" colspan=\"3\">" . $var[$k][usuario_nota] . "";
                        $this->salida.= " </td>";
                        $this->salida.= " <td width=\"8%\" align=\"right\" colspan=\"4\"><font color=\"red\">" . FormatoValor($var[$k][valor_nota]) . "</font>";
                        $this->salida.= " </td>";
                        $this->salida.= "</tr>";
                        $this->salida.= "<tr class=\"modulo_list_claro\">";
                        $this->salida.= " <td width=\"100%\" align=\"left\" colspan=\"9\">&nbsp;";
                        $this->salida.= " </td>";
                        $this->salida.= "</tr>";
                    }
                    //FIN NOTAS ANULACION

                    $k++;
                }
                $numeracion_final = $var[$i][prefijo] . $var[$i][factura_fiscal];
                $total_factura += $total_usuario;
                if ($cont) {
                    $Tabono_efectivo += $abono_efectivo;
                    $Tabono_cheque += $abono_cheque;
                    $Tabono_tarjetas += $abono_tarjetas;
                    $Tabono_chequespf += $abono_chequespf;
                    $Tabono_letras += $abono_letras;
                }
                $estilo = 'modulo_table_title';
                $this->salida.= "<tr class=\"$estilo\">";
                $this->salida.= " <td width=\"100%\" colspan=\"3\" align=\"right\"><b>S. TOTALALES " . $var[$i][sw_clase_factura] . ": </b></td>";
                $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($abono_efectivo) . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($abono_cheque) . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($abono_tarjetas) . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($abono_chequespf) . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($abono_letras) . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"100%\" align=\"center\">$&nbsp;<font color=\"red\"><b>" . FormatoValor($total_usuario) . "</font></b>";
                $this->salida.= " </td>";
                $this->salida.= "</tr>";

                $estilo = 'modulo_list_oscuro';
                $backgrounds = "#DDDDDD";
                $this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'#FFFFFF');>";
                $this->salida.= " <td width=\"100%\" colspan=\"9\" align=\"left\"><b>NUMERACI�N " . $var[$i][sw_clase_factura] . " DEL " . $numeracion_final . " AL " . $numeracion_inicial . " </b></td>";
                $this->salida.= "</tr>";

                $this->salida.= "<tr>";
                $this->salida.= " <td colspan=\"4\"width=\"100%\" align=\"center\">&nbsp;";
                $this->salida.= " </td>";
                $this->salida.= "</tr>";
                $cont = false;
                $i = $k;
            }
//TOTALES MEDIOS DE PAGO
            $this->salida.= "<tr class=\"modulo_table_title\">";
            $this->salida.= "<td width=\"85%\" align=\"center\" colspan=\"9\">";
            $this->salida.= "<table  border=\"0\" align=\"center\"   width=\"100%\">";
            $this->salida.= "<tr class=\"modulo_table_title\" onmouseover=mOvr(this,'#43b7ba');>";
            $this->salida.= " <td width=\"100%\" align=\"center\"colspan=\"6\">TOTALES FACTURACI�N";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";
            $this->salida.= "<tr class=\"$estilo\">";
            $this->salida.= " <td width=\"8%\" align=\"center\">T. EFECTIVO";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"center\">T. CHEQUES";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"center\">T. TARJETAS";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"center\">T. CHEQUESPF";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"center\">T. LETRAS";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"center\">S. EMPRESA + PACIENTE";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";
            $this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'$cambia');>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($Tabono_efectivo) . "";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($Tabono_cheque) . "";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($Tabono_tarjetas) . "";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($Tabono_chequespf) . "";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($Tabono_letras) . "";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($valor_total) . "";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";
            $this->salida.= "</table>";
            $this->salida.= "</td>";
            $this->salida.= "</tr>";
//FIN TOTALES MEDIOS DE PAGO

            $this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'#5efb6e');>";
            $this->salida.= " <td width=\"100%\" colspan=\"8\" align=\"right\"><b>TOTAL FACTURACI�N: </b>";
            $this->salida.= " <td width=\"100%\" align=\"center\">$&nbsp;&nbsp;<font color=\"red\"><b>" . FormatoValor($total_factura) . "</font></b>";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";

            $this->salida.= "<tr>";
            $this->salida.= " <td align=\"center\" colspan = \"6\">&nbsp;</td>";
            $this->salida.= "</tr>";
            $this->salida.= "<tr>";
            $direccion = "app_modules/Facturacion_Fiscal/reports/html/ReporteFacturasNotasAnulacion.php?";
            $this->salida.= "  <td align=\"center\" colspan = \"9\"><input type=\"button\" value=\"Imprimir\" class=\"input-submit\" onclick=\"javascript:comprobantediario('$direccion');\"></td>";
            $this->salida.= "</tr>";
            $this->salida.= "</table>";
            //$Paginador = new ClaseHTML();
            //$this->salida .= "      <br>\n";
            //$action = ModuloGetURL("app","Facturacion_Fiscal","user","FrmComprobanteInformeDiario");
            //$this->salida .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$action);
        } else {
            $this->salida .= "<br><center><b>NO HAY MOVIMIENTO</b></center>";
        }
        $this->salida.= "</form>";

        $this->salida.= ThemeCerrarTabla();
        return true;
    }

    //FIN FORMA FACTURAS - NOTAS DE ANULACION
    //FORMA COMPROBANTE INFORME DIARIO
    function FrmComprobanteInformeDiario() {
        $_SESSION[Listado] = $var = $this->GetDatosComprobanteDiario($_REQUEST['offset']);
        $Empresa = $_SESSION[FACTURACION][EMPRESA];
        $mostrar = "\n<SCRIPT>\n";
        $mostrar.="function mOvr(src,clrOver) {;\n";
        $mostrar.="src.style.background = clrOver;\n";
        $mostrar.="}\n";

        $mostrar.="function mOut(src,clrIn) {\n";
        $mostrar.="src.style.background = clrIn;\n";
        $mostrar.="}\n";
        $mostrar .= "  function myOnDragStart(ele, mx, my)\n";
        $mostrar .= "  {\n";
        $mostrar .= "    window.status = '';\n";
        $mostrar .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
        $mostrar .= "    else xZIndex(ele, hiZ++);\n";
        $mostrar .= "    ele.myTotalMX = 0;\n";
        $mostrar .= "    ele.myTotalMY = 0;\n";
        $mostrar .= "  }\n";
        $mostrar .= "  function myOnDrag(ele, mdx, mdy)\n";
        $mostrar .= "  {\n";
        $mostrar .= "    if (ele.id == titulo) {\n";
        $mostrar .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
        $mostrar .= "    }\n";
        $mostrar .= "    else {\n";
        $mostrar .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $mostrar .= "    }  \n";
        $mostrar .= "    ele.myTotalMX += mdx;\n";
        $mostrar .= "    ele.myTotalMY += mdy;\n";
        $mostrar .= "  }\n";
        $mostrar .= "  function MostrarSpan(Seccion)\n";
        $mostrar .= "  { \n";
        $mostrar .= "    e = xGetElementById(Seccion);\n";
        $mostrar .= "    e.style.display = \"\";\n";
        $mostrar .= "  }\n";
        $mostrar .= "	function comprobantediario(dir)\n";
        $mostrar .= "	{\n";
        $mostrar .= "		var url=dir;\n";
        $mostrar .= "		window.open(url,'COMPROBANTE DIARIO','screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes');\n";
        $mostrar .= "	}\n";
        $mostrar .="</SCRIPT>\n";
        $this->salida = "$mostrar";
        $backgrounds = array('modulo_list_claro' => '#DDDDDD', 'modulo_list_oscuro' => '#CCCCCC');

        //unset($_SESSION['FACTURACION_RECEPCION']['DATOS']);
        $this->salida.= ThemeAbrirTabla('COMPROBANTE', '95%');
        $this->salida.= $this->EncabezadoEmpresa();
        $this->salida.= "<table border=\"0\" align=\"center\"   width=\"100%\">";
        $this->salida.= "<tr>";
        $this->salida.= "<td width=\"100%\" align=\"right\">";
        $action2 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaListadoResportes');
        $this->salida .= " <form name=\"forma\" action=\"$action2\" method=\"post\">";
        $this->salida .= " <a href=\"$action2\" title=\"VOLVER\"><img src=\"" . GetThemePath() . "/images/boton.png\" border=\"0\" width=\"15\" height=\"15\"></a></form>";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida.= "<form name=\"formaagrupadas\" action=\"$accion\" method=\"post\">";
        $this->salida.= "</table><br>";
        $this->salida.= "<table border=\"0\" align=\"center\"   width=\"100%\">";
        $this->salida.= "<form name=\"formaagrupadas\" action=\"$accion\" method=\"post\">";
        //$this->salida.= "<tr class='formulacion_table_list'>";
        //$this->salida.= "<tr class='modulo_table_title'>";
        $this->salida.= "<tr class='modulo_table_list_title'>";
        $this->salida.= "<td width=\"100%\" align=\"CENTER\">COMPROBANTE INFORME DIARIO";
        $this->salida.= "</td>";
        $this->salida.= "</tr>";
        $this->salida.= "</table><br>";

        if (is_array($var) AND sizeof($var) > 0) {
            $cont = true;
            $total_factura = $Tabono_efectivo = $Tabono_cheque = $Tabono_tarjetas = $Tabono_chequespf = $Tabono_letras = $valor_total = 0;
            for ($i = 0; $i < sizeof($var);) {
                $k = $i;
                $estilo = 'modulo_list_oscuro';
                $backgrounds = "#DDDDDD";
                $this->salida.= "<table  border=\"0\" align=\"center\"   width=\"100%\">";
                $this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'#43b7ba');>";
                $this->salida.= " <td width=\"100%\" colspan=\"9\" align=\"center\"><b><font size=\"2\">FACTURACI�N " . $var[$k][sw_clase_factura] . "</font></b>";
                $this->salida.= " </td>";
                $this->salida.= "</tr>";

                $estilo = 'modulo_table_title';
                $this->salida.= "<tr class=\"$estilo\">";
                $this->salida.= " <td width=\"5%\" align=\"center\">FACTURA";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"25%\" align=\"center\">CLIENTE";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"20%\" align=\"center\">PACIENTE";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"center\">EFECTIVO";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"center\">CHEQUES";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"center\">TARJETAS";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"center\">CHEQUESPF";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"center\">LETRAS";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"10%\" align=\"center\">VALOR $";
                $this->salida.= " </td>";
                $this->salida.= "</tr>";

                $total_usuario = $abono_efectivo = $abono_cheque = $abono_tarjetas = $abono_chequespf = $abono_letras = 0;
                //while($var[$i][usuario_id]==$var[$k][usuario_id])
                while ($var[$i][sw_clase_factura] == $var[$k][sw_clase_factura]) {
                    $numeracion_inicial = $var[$k][prefijo] . $var[$k][factura_fiscal];
                    $abono_efectivo += $var[$k][abono_efectivo];
                    $abono_cheque += $var[$k][abono_cheque];
                    $abono_tarjetas += $var[$k][abono_tarjetas];
                    $abono_chequespf += $var[$k][abono_chequespf];
                    $abono_letras += $var[$k][abono_letras];
                    $valor_total += $var[$k][valor];
                    $total_usuario += $var[$k][valor];
                    $estilo = 'modulo_list_oscuro';
                    $backgrounds = "#DDDDDD";
                    if ($k % 2 == 0) {
                        $estilo = 'modulo_list_claro';
                        $backgrounds = "#CCCCCC";
                    }
                    if ($var[$i][sw_clase_factura] == 'CONTADO') {
                        $cambia = '#7A99BB';
                    } else {
                        $cambia = '#FFFFFF';
                    }

                    $this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'$cambia');>";
                    $this->salida.= " <td width=\"5%\" align=\"center\">" . $var[$k][prefijo] . ' ' . $var[$k][factura_fiscal] . "";
                    $this->salida.= " </td>";
                    $this->salida.= " <td width=\"25%\" align=\"center\">" . $var[$k][cliente] . "";
                    $this->salida.= " </td>";
                    $this->salida.= " <td width=\"20%\" align=\"center\">" . $var[$k][paciente] . "";
                    $this->salida.= " </td>";
                    $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($var[$k][abono_efectivo]) . "";
                    $this->salida.= " </td>";
                    $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($var[$k][abono_cheque]) . "";
                    $this->salida.= " </td>";
                    $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($var[$k][abono_tarjetas]) . "";
                    $this->salida.= " </td>";
                    $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($var[$k][abono_chequespf]) . "";
                    $this->salida.= " </td>";
                    $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($var[$k][abono_letras]) . "";
                    $this->salida.= " </td>";
                    $this->salida.= " <td width=\"10%\" align=\"right\">" . FormatoValor($var[$k][valor]) . "";
                    $this->salida.= " </td>";
                    $this->salida.= "</tr>";
                    $k++;
                }
                $numeracion_final = $var[$i][prefijo] . $var[$i][factura_fiscal];
                $total_factura += $total_usuario;
                if ($cont) {
                    $Tabono_efectivo += $abono_efectivo;
                    $Tabono_cheque += $abono_cheque;
                    $Tabono_tarjetas += $abono_tarjetas;
                    $Tabono_chequespf += $abono_chequespf;
                    $Tabono_letras += $abono_letras;
                }
                $this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'#43b7ba');>";
                $this->salida.= " <td width=\"100%\" colspan=\"3\" align=\"right\"><b>S. TOTALALES " . $var[$i][sw_clase_factura] . ": </b></td>";
                $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($abono_efectivo) . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($abono_cheque) . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($abono_tarjetas) . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($abono_chequespf) . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($abono_letras) . "";
                $this->salida.= " </td>";
                $this->salida.= " <td width=\"100%\" align=\"center\">$&nbsp;<font color=\"red\"><b>" . FormatoValor($total_usuario) . "</font></b>";
                $this->salida.= " </td>";
                $this->salida.= "</tr>";

                $this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'#FFFFFF');>";
                $this->salida.= " <td width=\"100%\" colspan=\"9\" align=\"left\"><b>NUMERACI�N " . $var[$i][sw_clase_factura] . " DEL " . $numeracion_final . " AL " . $numeracion_inicial . " </b></td>";
                $this->salida.= "</tr>";

// 						$this->salida.= "<tr class=\"modulo_table_title\">";
// 						$this->salida.= "<td width=\"85%\" align=\"center\" colspan=\"9\">";
// 						$this->salida.= "<table  border=\"0\" align=\"center\"   width=\"100%\">";
// 						$this->salida.= "<tr class=\"modulo_table_title\" onmouseover=mOvr(this,'#43b7ba');>";
// 						$this->salida.= " <td width=\"100%\" align=\"center\"colspan=\"5\">S. TOTALES FACTURACI� ".$var[$i][sw_clase_factura];
// 						$this->salida.= " </td>";
// 						$this->salida.= "</tr>";
// 						$this->salida.= "<tr class=\"$estilo\">";
// 						$this->salida.= " <td width=\"8%\" align=\"center\">T. EFECTIVO";
// 						$this->salida.= " </td>";
// 						$this->salida.= " <td width=\"8%\" align=\"center\">T. CHEQUES";
// 						$this->salida.= " </td>";
// 						$this->salida.= " <td width=\"8%\" align=\"center\">T. TARJETAS";
// 						$this->salida.= " </td>";
// 						$this->salida.= " <td width=\"8%\" align=\"center\">T. CHEQUESPF";
// 						$this->salida.= " </td>";
// 						$this->salida.= " <td width=\"8%\" align=\"center\">T. LETRAS";
// 						$this->salida.= " </td>";
// 						$this->salida.= "</tr>";
// 						$this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"".$backgrounds."\"); onmouseover=mOvr(this,'$cambia');>";
// 						$this->salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_efectivo)."";
// 						$this->salida.= " </td>";
// 						$this->salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_cheque)."";
// 						$this->salida.= " </td>";
// 						$this->salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_tarjetas)."";
// 						$this->salida.= " </td>";
// 						$this->salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_chequespf)."";
// 						$this->salida.= " </td>";
// 						$this->salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_letras)."";
// 						$this->salida.= " </td>";
// 						$this->salida.= "</tr>";
// 						$this->salida.= "</table>";
// 						$this->salida.= "</td>";
// 						$this->salida.= "</tr>";


                $this->salida.= "<tr>";
                $this->salida.= " <td colspan=\"4\"width=\"100%\" align=\"center\">&nbsp;";
                $this->salida.= " </td>";
                $this->salida.= "</tr>";
                $cont = false;
                $i = $k;
            }
//TOTALES MEDIOS DE PAGO
            $this->salida.= "<tr class=\"modulo_table_title\">";
            $this->salida.= "<td width=\"85%\" align=\"center\" colspan=\"9\">";
            $this->salida.= "<table  border=\"0\" align=\"center\"   width=\"100%\">";
            $this->salida.= "<tr class=\"modulo_table_title\" onmouseover=mOvr(this,'#43b7ba');>";
            $this->salida.= " <td width=\"100%\" align=\"center\"colspan=\"6\">TOTALES FACTURACI�N";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";
            $this->salida.= "<tr class=\"$estilo\">";
            $this->salida.= " <td width=\"8%\" align=\"center\">T. EFECTIVO";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"center\">T. CHEQUES";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"center\">T. TARJETAS";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"center\">T. CHEQUESPF";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"center\">T. LETRAS";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"center\">S. EMPRESA + PACIENTE";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";
            $this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'$cambia');>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($Tabono_efectivo) . "";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($Tabono_cheque) . "";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($Tabono_tarjetas) . "";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($Tabono_chequespf) . "";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($Tabono_letras) . "";
            $this->salida.= " </td>";
            $this->salida.= " <td width=\"8%\" align=\"right\">" . FormatoValor($valor_total) . "";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";
            $this->salida.= "</table>";
            $this->salida.= "</td>";
            $this->salida.= "</tr>";
//FIN TOTALES MEDIOS DE PAGO

            $this->salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"" . $backgrounds . "\"); onmouseover=mOvr(this,'#5efb6e');>";
            $this->salida.= " <td width=\"100%\" colspan=\"8\" align=\"right\"><b>TOTAL FACTURACI�N: </b>";
            $this->salida.= " <td width=\"100%\" align=\"center\">$&nbsp;&nbsp;<font color=\"red\"><b>" . FormatoValor($total_factura) . "</font></b>";
            $this->salida.= " </td>";
            $this->salida.= "</tr>";

            $this->salida.= "<tr>";
            $this->salida.= " <td align=\"center\" colspan = \"6\">&nbsp;</td>";
            $this->salida.= "</tr>";
            $this->salida.= "<tr>";
            //$accion = ModuloGetURL('app','Facturacion_Fiscal','user','FrmComprobanteInformeDiario');
            $direccion = "app_modules/Facturacion_Fiscal/reports/html/ReporteComprobanteDiario.php?";
            $this->salida.= "  <td align=\"center\" colspan = \"9\"><input type=\"button\" value=\"Imprimir\" class=\"input-submit\" onclick=\"javascript:comprobantediario('$direccion');\"></td>";
            $this->salida.= "</tr>";
            $this->salida.= "</table>";
            //$Paginador = new ClaseHTML();
            //$this->salida .= "      <br>\n";
            //$action = ModuloGetURL("app","Facturacion_Fiscal","user","FrmComprobanteInformeDiario");
            //$this->salida .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$action);
        } else {
            $this->salida .= "<br><center><b>NO HAY MOVIMIENTO</b></center>";
        }
        $this->salida.= "</form>";

        $this->salida.= ThemeCerrarTabla();
        return true;
    }

    //FIN FORMA COMPROBANTE INFORME DIARIO

    function SeleccionarFecha() {
        $this->salida = ThemeAbrirTabla('FACTURACION - FECHA REPORTE');
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">FECHA REPORTES</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarFormaBuscarUsuario');
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        if (empty($_REQUEST['Fecha'])) {
            $_REQUEST['Fecha'] = date("d/m/Y");
        }
        $this->salida .= "  <td width=\"100%\" align=\"center\"><input type=\"text\" class=\"input-text\" name=\"Fecha\" size=\"12\" value=\"" . $_REQUEST['Fecha'] . "\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
        $this->salida .= "&nbsp;&nbsp;" . ReturnOpenCalendario('formabuscar', 'Fecha', '/') . "</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "           </table>";
        $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></p>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaBuscarUsuario() {
        $this->salida = ThemeAbrirTabla('FACTURACION - BUSCAR USUARIO');
        $this->EncabezadoEmpresa();
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarUsuario');
        $this->salida .= "            <form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
        $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
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
        $this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
        $this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text'   name = 'busqueda'  size=\"40\" maxlength=\"40\"  value =\"" . $_REQUEST['busqueda'] . "\"></td>";
        $this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar' type=\"submit\" value=\"BUSQUEDA\"></td>";
        $this->salida.="</tr>";
        $this->salida.="</form>";
        $this->salida.="<tr class=\"modulo_table_list_title\">";
        if ($_REQUEST['busqueda']) {
            $cadena = "El buscador Avanzado: realizó la  busqueda &nbsp;'" . $_REQUEST['busqueda'] . "'&nbsp;";
        } else {
            $cadena = "Buscador Avanzado: Busqueda de todos los usuarios";
        }
        $this->salida.="  <td align=\"left\" colspan=\"5\">$cadena</td>";
        $this->salida.="</tr>";
        $this->salida.="</table><br>";
        if ($_REQUEST['buscar']) {
            $filtro = $this->GetFiltroUsuarios($_REQUEST['criterio'], $_REQUEST['busqueda']);
        } else {
            if ($_SESSION['USUARIOS']['FILTRO']) {
                $filtro = $_SESSION['USUARIOS']['FILTRO'];
            }
        }
        if ($filtro) {
            $_SESSION['USUARIOS']['FILTRO'] = $filtro;
        }//esto guarda el filtro...
        if (!empty($_REQUEST['buscar'])) {
            $var = $this->BuscarUsuariosSistema($filtro);
            if (empty($var)) {
                $this->salida .= "  <br><table width=\"100%\" border=\"0\" align=\"center\">";
                $this->salida .= "        <tr><td class=\"label_error\" align=\"center\">NO SE ENCONT� NINGUN REGISTRO</td></tr>";
                $this->salida .= "        </table>";
            } else {
                $this->salida .= "  <br><table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list_title\">";
                $this->salida .= "            <tr class=\"modulo_table_list_title\" align=\"center\">";
                $this->salida .= "               <td>UID</td>";
                $this->salida .= "               <td>LOGIN</td>";
                $this->salida .= "              <td>NOMBRE USUARIO</td>";
                $this->salida .= "              <td ></td>";
                $this->salida .= "            </tr>";
                for ($i = 0; $i < sizeof($var); $i++) {
                    if ($i % 2)
                        $estilo = 'modulo_list_claro';
                    else
                        $estilo = 'modulo_list_oscuro';
                    $this->salida .= "            <tr class=\"$estilo\">";
                    $this->salida .= "               <td align=\"center\">" . $var[$i][usuario_id] . "</td>";
                    $this->salida .= "               <td align=\"center\">" . $var[$i][usuario] . "</td>";
                    $this->salida .= "              <td>" . $var[$i][nombre] . "</td>";
                    $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaReporteCierres', array('datos' => $var[$i]));
                    $this->salida .= "              <td width=\"10%\" align=\"center\"><a href=\"$accion\">VER</a></td>";
                    $this->salida .= "            </tr>";
                }
                $this->salida .= "  </table>";
            }
            $this->salida .=$this->RetornarBarrausu($filtro);
        }
        $action3 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaListadoResportes');
        $this->salida .= "       <table align=\"center\">";
        $this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
        $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
        $this->salida .= "       </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaReporteCierres() {
        $this->salida = ThemeAbrirTabla('FACTURACION - BUSCAR USUARIO');
        $this->EncabezadoEmpresa();
        $this->salida .= "  <br><table cellspacing=\"2\"  cellpadding=\"3\" border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "            <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "               <td>UID</td>";
        $this->salida .= "               <td>LOGIN</td>";
        $this->salida .= "              <td>NOMBRE USUARIO</td>";
        $this->salida .= "            </tr>";
        $var = $_REQUEST['datos'];
        $this->salida .= "            <tr class=\"modulo_list_claro\">";
        $this->salida .= "               <td align=\"center\">" . $var[usuario_id] . "</td>";
        $this->salida .= "               <td align=\"center\">" . $var[usuario] . "</td>";
        $this->salida .= "              <td>" . $var[nombre] . "</td>";
        $this->salida .= "  </table>";
        $arr = $this->DatosReporte($var[usuario_id]);
        //$var=$this->DatosReporteCuentas($var[usuario_id]);
        if (empty($arr)) {
            $this->salida .= "     <br><table align=\"center\" width=\"80%\">";
            $this->salida .= "          <tr><td align=\"center\">EL USUARIO NO TIENE FACTURAS EL DIA DE HOY</td></tr>";
            $this->salida .= "       </table>";
        } else {
            $RUTA = $_ROOT . "cache/facturasusuarios.pdf";
            $mostrar = "\n<script>\n";
            $mostrar.="var rem=\"\";\n";
            $mostrar.="  function abreVentana(){\n";
            $mostrar.="    var nombre=\"\"\n";
            $mostrar.="    var url2=\"\"\n";
            $mostrar.="    var str=\"\"\n";
            $mostrar.="    var ALTO=screen.height\n";
            $mostrar.="    var ANCHO=screen.width\n";
            $mostrar.="    var nombre=\"REPORTE\";\n";
            $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
            $mostrar.="    var url2 ='$RUTA';\n";
            $mostrar.="    rem = window.open(url2, nombre, str)};\n";
            $mostrar.="</script>\n";
            $this->salida.="$mostrar";
            IncludeLib("tarifario");
            $this->salida.="<BR><table border=\"0\" width=\"90%\" align=\"center\"  class=\"modulo_list_claro\">";
            $total = 0;
            $cuentas = $valpagarpaciente = $valpagarcliente = $cant = 0;
            $totalfact = $totalcuenta = 0;
            for ($i = 0; $i < sizeof($arr);) {
                $this->salida.="<tr class=\"modulo_table_title\">";
                $this->salida.="<td>" . $arr[$i][nombre_tercero] . "</td>";
                $this->salida.="</tr>";
                $d = $i;
                while ($arr[$i][tipo_id_tercero] == $arr[$d][tipo_id_tercero]
                AND $arr[$i][tercero_id] == $arr[$d][tercero_id]) {
                    $this->salida.="<tr>";
                    $this->salida.="<td class=\"normal_10n\">" . $arr[$d][plan_descripcion] . "</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr>";
                    $this->salida.="<td>";
                    //tabla con el cabezote del listado
                    $this->salida.="<table border=\"0\" width=\"100%\" align=\"center\" cellspacing=\"3\"  cellpadding=\"2\">";
                    $this->salida.="<tr align=\"center\" class=\"modulo_table_list_title\">";
                    $this->salida.="<td width=\"15%\">FACTURA</td>";
                    $this->salida.="<td width=\"15%\">IDENTIFICACION</td>";
                    $this->salida.="<td width=\"15%\">PACIENTE</td>";
                    $this->salida.="<td width=\"10%\">CUENTA</td>";
                    $this->salida.="<td width=\"10%\">BONOS</td>";
                    $this->salida.="<td width=\"10%\">VAL PACIE.</td>";
                    $this->salida.="<td width=\"10%\">VAL CLIEN.</td>";
                    $this->salida.="<td width=\"10%\">TOTAL</td>";
                    $this->salida.="</tr>";
                    $j = $d;
                    $sub = $total = $valpac = $valcli = $bono0;
                    while ($arr[$d][plan_id] == $arr[$j][plan_id]) {
                        $this->salida.="<tr class=\"modulo_list_oscuro\">";
                        //es una factura
                        if (!empty($arr[$j][factura_fiscal])) {
                            if ($arr[$j][factura_fiscal] == $arr[$j - 1][factura_fiscal]
                                    AND $arr[$j][prefijo] == $arr[$j - 1][prefijo]) {
                                $this->salida.="<td align=\"center\">&nbsp;</td>";
                            } else {
                                $this->salida.="<td align=\"center\">" . $arr[$j][prefijo] . " " . $arr[$j][factura_fiscal] . "</td>";
                                $cant++;
                            }
                            $valpagarpaciente = $arr[$j][valor_total_paciente];
                            $valpagarcliente = $arr[$j][valor_total_empresa];
                            $totalfact+=str_replace(".", "", FormatoValor($arr[$j][valor_total_empresa]));
                            $sub+=$arr[$j][valor_nocubierto];
                            $total+=str_replace(".", "", FormatoValor($arr[$j][total_cuenta]));
                            $valpac+=str_replace(".", "", FormatoValor($valpagarpaciente));
                            $valcli+=str_replace(".", "", FormatoValor($valpagarcliente));
                            $bono+=$arr[$j][total_bonos];
                        } else {
                            if ($arr[$j][estado] == 3) {
                                $this->salida.="<td align=\"center\">--cuenta cuadrada-</td>";
                            } elseif ($arr[$j][estado] == 0) {
                                $this->salida.="<td align=\"center\">--cuenta cerrada-</td>";
                            }
                            $cuentas++;
                            $totalcuenta+=$arr[$j][total_cuenta];
                        }
                        $this->salida.="<td>" . $arr[$j][tipo_id_paciente] . " " . $arr[$j][paciente_id] . "</td>";
                        $this->salida.="<td>" . $arr[$j][nombre] . "</td>";
                        $this->salida.="<td align=\"center\">" . $arr[$j][numerodecuenta] . "</td>";
                        $this->salida.="<td align=\"right\">" . FormatoValor($arr[$j][total_bonos]) . "</td>";
                        $moderadora = $arr[$j][valor_cuota_moderadora];
                        //$valpagarpaciente=$arr[$j][valor_cuota_paciente]+$arr[$j][valor_nocubierto]+$moderadora;
                        //$valpagarcliente=$arr[$j][total_cuenta]-$valpagarpaciente;
                        $valpagarpaciente = $arr[$j][valor_total_paciente];
                        $valpagarcliente = $arr[$j][valor_total_empresa];
                        $this->salida.="<td align=\"right\">" . FormatoValor($valpagarpaciente) . "</td>";
                        $this->salida.="<td align=\"right\">" . FormatoValor($valpagarcliente) . "</td>";
                        $this->salida.="<td align=\"right\">" . FormatoValor($arr[$j][total_cuenta]) . "</td>";
                        $this->salida.="</tr>";
                        $j++;
                    }
                    $this->salida.="<tr class=\"modulo_list_oscuro\">";
                    $this->salida.="<td colspan=\"3\">&nbsp;</td>";
                    $this->salida.="<td align=\"center\" class=\"modulo_table_list_title\">SUB TOTAL ---></td>";
                    $this->salida.="<td class=\"normal_10n\" align=\"right\">" . FormatoValor($bono) . "</td>";
                    $this->salida.="<td class=\"normal_10n\" align=\"right\">" . FormatoValor($valpac) . "</td>";
                    $this->salida.="<td class=\"normal_10n\" align=\"right\">" . FormatoValor($valcli) . "</td>";
                    $this->salida.="<td class=\"normal_10n\" align=\"right\">" . FormatoValor($total) . "</td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
                    $d = $j;
                }
                $i = $d;
            }
            /* $this->salida.="<tr>";
              $this->salida.="<td class=\"normal_10n\">VALOR TOTAL ($):  ".FormatoValor($total)."";
              $this->salida.="</td>";
              $this->salida.="</tr>"; */
            if (!empty($cant)) {
                $this->salida.="<tr>";
                $this->salida.="<td class=\"normal_10n\">CANTIDAD FACTURAS :  $cant";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr>";
                $this->salida.="<td class=\"normal_10n\">VALOR TOTAL FACTURAS ($):  " . FormatoValor($totalfact) . "";
                $this->salida.="</td>";
                $this->salida.="</tr>";
            }
            if (!empty($cuentas)) {
                $this->salida.="<tr>";
                $this->salida.="<td class=\"normal_10n\">CANTIDAD CUENTAS  :  $cuentas";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr>";
                $this->salida.="<td class=\"normal_10n\">VALOR TOTAL CUENTAS ($):  " . FormatoValor($totalcuenta) . "";
                $this->salida.="</td>";
                $this->salida.="</tr>";
            }
            $this->salida.="</table>";
        }
        $action3 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarUsuario');
        $this->salida .= "       <table align=\"center\">";
        $this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
        $this->salida .= "    <tr>";
        $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"VOLVER\"></form></td>";
        if (!empty($arr)) {
            /*  IncludeLib("reportes/facturasusuarios");
              GenerarFacturasUsuarios($arr); */

            $reporte = new GetReports();
            $mostrar = $reporte->GetJavaReport('app', 'Facturacion_Fiscal', 'reporteusuarios', array('empresa' => $_SESSION['FACTURACION']['EMPRESA'], 'fecha' => $_SESSION['FACTURACION']['FECHAREPORTE'], 'usuario' => $var[usuario_id]), array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $funcion = $reporte->GetJavaFunction();
            $this->salida .=$mostrar;
            $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"button\" value=\"IMPRIMIR\" onclick=\"javascript:$funcion\"></td>";
            unset($reporte);
        }
        $this->salida .= "    </tr>";
        $this->salida .= "       </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function RetornarBarrausu($filtro) {
        if ($this->limit >= $this->conteo) {
            return '';
        }

        $paso = $_REQUEST['paso'];
        if (empty($paso)) {
            $paso = 1;
        }
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarUsuario', array('conteo' => $this->conteo, 'busqueda' => $_REQUEST['busqueda'], 'buscar' => true));
        $barra = $this->CalcularBarra($paso);
        $numpasos = $this->CalcularNumeroPasos($this->conteo);
        $colspan = 1;

        $this->salida .= "<br><table width='22%' border='0'  align='center' cellspacing=\"5\"  cellpadding=\"1\"><tr><td width='10%' class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if ($paso > 1) {
            $this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset(1) . "&paso=1'><img src=\"" . GetThemePath() . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
            $this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso - 1) . "&paso=" . ($paso - 1) . "'><img src=\"" . GetThemePath() . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
            $colspan+=2;
        }
        $barra++;
        if (($barra + 10) <= $numpasos) {
            for ($i = ($barra); $i < ($barra + 10); $i++) {
                if ($paso == $i) {
                    $this->salida .= "<td width='7%' bgcolor=\"#D3DCE3\">$i</td>";
                } else {
                    $this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($i) . "&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso + 1) . "&paso=" . ($paso + 1) . "' ><img src=\"" . GetThemePath() . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
            $this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($numpasos) . "&paso=$numpasos'><img src=\"" . GetThemePath() . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
            $colspan+=2;
        } else {
            $diferencia = $numpasos - 9;
            if ($diferencia < 0) {
                $diferencia = 1;
            }
            for ($i = ($diferencia); $i <= $numpasos; $i++) {
                if ($paso == $i) {
                    $this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\" >$i</td>";
                } else {
                    $this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($i) . "&paso=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if ($paso != $numpasos) {
                $this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso + 1) . "&paso=" . ($paso + 1) . "' ><img src=\"" . GetThemePath() . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
                $this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($numpasos) . "&paso=$numpasos'><img src=\"" . GetThemePath() . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
                $colspan++;
            }
            if (($_REQUEST['Of']) == 0 OR ($paso == $numpasos)) {
                if ($numpasos > 10) {
                    $valor = 10 + 3;
                } else {
                    $valor = $numpasos + 3;
                }
                $this->salida .= "</tr><tr><td  class=\"label\"  colspan=" . $valor . " align='center'>P�gina&nbsp; $paso de $numpasos</td><tr></table>";
            } else {
                if ($numpasos > 10) {
                    $valor = 10 + 5;
                } else {
                    $valor = $numpasos + 5;
                }
                $this->salida .= "</tr><tr><td   class=\"label\"  colspan=" . $valor . " align='center'>Página&nbsp; $paso de $numpasos</td><tr></table>";
            }
        }
    }

    /**
     * Funcion donde se selecciona el plan
     *
     * @return boolean
     */
    function FormaResponsable() {
        if (empty($_REQUEST['accionRips']))
            $action = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarFormaBuscarEnvios', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId));
        else
            $action = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', $_REQUEST['accionRips'], array('TipoId' => $TipoId, 'PacienteId' => $PacienteId));

        $responsables = $this->TercerosPlanes();

        $this->salida .= ThemeAbrirTabla('ELEGIR RESPONSABLE');
        $this->salida .= "<br><br>";
        $this->salida .= "<form name=\"formabuscar\" action=\"" . $action . "\" method=\"post\">";
        $this->salida .= "  <table width=\"50%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
        $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "      <td align=\"left\">RESPONSABLE: </td>\n";
        $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
        $this->salida .= "        <select name=\"Plan\" class=\"select\">\n";
        foreach ($responsables as $key => $dtl)
            $this->salida .= "          <option value=\"" . $dtl['tipo_tercero_id'] . "," . $dtl['tercero_id'] . "," . $dtl['nombre_tercero'] . "\">" . $dtl['nombre_tercero'] . "</option>\n";

        $this->salida .= "       </select>\n";
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "    <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"><br></td></form>";
        $actionM = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaMenus');
        $this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "               <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form></tr>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
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
    function FormaBuscarEnvios($arr, $adicionar) {
        $request = $_REQUEST;
        $this->salida.= ThemeAbrirTabla('BUSQUEDA DE FACTURAS PARA ENVIOS');
        $this->Todos();
        IncludeLib("tarifario");
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarEnvios');
        $this->EncabezadoEmpresa();
        $this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
        //$this->salida .= "<td align = left >SELECCIONE LA FECHA:</td>";
        $this->salida .= "</tr>";
        $this->salida .= "<tr class=\"modulo_list_claro\" >";
        $this->salida .= "<td width=\"40%\" >";
        $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr><td>";
        $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
        $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<SCRIPT>";
        $this->salida .= "function Revisar(frm,x){";
        $this->salida .= "  if(x==true){";
        $this->salida .= "frm.Fecha.value='TODAS LAS FECHAS'";
        $this->salida .= "  }";
        $this->salida .= "else{";
        $this->salida .= "frm.Fecha.value=''";
        $this->salida .= "}";
        $this->salida .= "}";
        $this->salida .= "function VerServicios(){\n";
        $this->salida .= "  if(document.getElementById('Servicios').style.display == 'block'){\n";
        $this->salida .= "    document.getElementById('Servicios').style.display = 'none';\n";
        $this->salida .= "  }else{\n";
        $this->salida .= "    document.getElementById('Servicios').style.display = 'block';\n";
        $this->salida .= " }\n";
        $this->salida .= "}";
        $this->salida .= "function VerTiposUsuarios(){\n";
        $this->salida .= "  if(document.getElementById('TiposUsuarios').style.display == 'block'){\n";
        $this->salida .= "    document.getElementById('TiposUsuarios').style.display = 'none';\n";
        $this->salida .= "  }else{\n";
        $this->salida .= "    document.getElementById('TiposUsuarios').style.display = 'block';\n";
        $this->salida .= " }\n";
        $this->salida .= "}";
        $this->salida .= "</SCRIPT>";
        $ac = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarEnvios');
        $f = explode(',', $_SESSION['ENVIOS']['TERCERO']);
        $this->salida .= "        <tr>\n";
        $this->salida .= "          <td class=\"" . $this->SetStyle("Responsable") . "\">RESPONSABLE: </td>";
        $this->salida .= "          <td class=\"normal_10AN\">" . $f[2] . "</td>\n";
        $this->salida .= "        </tr>\n";

        $fc = new app_Facturacion_Permisos();
        $rangos = $fc->ObtenerRangosNiveles($f[0], $f[1]);
        $responsables = $this->responsables($f[0], $f[1]);

        $this->salida .= "        <tr>\n";
        $this->salida .= "          <td colspan=\"2\" class=\"" . $this->SetStyle("Plan") . "\">PLANES ACTIVOS: </td>\n";
        $this->salida .= "        </tr>\n";
        $this->salida .= "        <tr>\n";
        $this->salida .= "          <td colspan=\"2\">\n";
        $this->salida .= "            <table width=\"100%\" align=\"left\" border=\"0\">";

        for ($j = 0; $j < sizeof($responsables); $j++) {
            $this->salida .= "              <tr class=\"label\">\n";
            $this->salida .= "                <td width=\"1%\">\n";
            $this->salida .= "                  <input type = checkbox name= \"plan" . $responsables[$j][plan_id] . "\" value=\"" . $responsables[$j][plan_id] . "\" " . (($request["plan" . $responsables[$j][plan_id]] == $responsables[$j][plan_id]) ? "checked" : "") . ">\n";
            $this->salida .= "                </td>\n";
            $this->salida .= "                <td class=\"normal_10AN\">" . $responsables[$j][plan_descripcion] . "</td>";
            $this->salida .= "                <td >\n";
            $this->salida .= "                  RANGO : \n";
            $this->salida .= "                  <select name=\"rango_" . $responsables[$j][plan_id] . "\" class=\"select\">\n";
            $this->salida .= "                    <option value=\"-1\">Seleccionar</option>\n";
            foreach ($rangos[$responsables[$j][plan_id]] as $key => $dtl) {
                ($request["rango_" . $responsables[$j][plan_id]] == $dtl['rango']) ? $s = "selected" : $s = "";
                $this->salida .= "                    <option value=\"" . $dtl['rango'] . "\" " . $s . ">" . $dtl['rango'] . "</option>\n";
            }
            $this->salida .= "                  </select>\n";
            $this->salida .= "                </td>\n";

            $this->salida .= "              </tr>\n";
        }
        $this->salida .= "            </table>";
        $this->salida .= "          </td>\n";
        $this->salida .= "        </tr>\n";

        $inac = $this->Inactivos($f[0], $f[1]);
        if (!empty($inac)) {
            $this->salida .= "        <tr>\n";
            $this->salida .= "          <td colspan=\"2\" class=\"" . $this->SetStyle("Plan") . "\">PLANES INACTIVOS: </td>\n";
            $this->salida .= "        </tr>\n";
            $this->salida .= "        <tr>\n";
            $this->salida .= "          <td colspan=\"2\">\n";
            $this->salida .= "            <table width=\"80%\" align=\"center\" border=\"0\">";
            for ($i = 0; $i < sizeof($inac); $i++) {
                $this->salida .= "              <tr>\n";
                $this->salida .= "                <td width=\"1%\">\n";
                $this->salida .= "                  <input type = checkbox name= inac" . $inac[$i][plan_id] . " value=\"" . $inac[$i][plan_id] . "\">\n";
                $this->salida .= "                </td>\n";
                $this->salida .= "                <td class=\"label\" width=\"65%\">" . $inac[$i][plan_descripcion] . "</td>";
                $this->salida .= "                <td >\n";
                $this->salida .= "                  RANGO : \n";
                $this->salida .= "                  <select name=\"rango_" . $inac[$i][plan_id] . "\" class=\"select\">\n";
                $this->salida .= "                    <option value=\"-1\">Seleccionar</option>\n";
                foreach ($rangos[$inac[$i][plan_id]] as $key => $dtl)
                    $this->salida .= "                    <option value=\"" . $dtl['rango'] . "\">" . $dtl['rango'] . "</option>\n";

                $this->salida .= "                  </select>\n";
                $this->salida .= "                </td>\n";

                $this->salida .= "              </tr>\n";
            }
            $this->salida .= "            </table>\n";
            $this->salida .= "          </td>\n";
            $this->salida .= "        </tr>\n";
        }
        if ($j > 1) {
            $this->salida .= "               <tr><td class=\"" . $this->SetStyle("Plan") . "\"></td><td>";
            $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
            $this->salida .= "<tr>";
            $this->salida .= "<td class=\"label\" width=\"65%\">TODOS PLANES</td>";
            $this->salida .= "<td><input type = checkbox name=Todos ></td>";
            $this->salida .= "</tr>";
            $this->salida .= "</table>";
            $this->salida .= "              </td></tr>";
        }

        $this->salida .= "<tr><td class=\"label\">DEPARTAMENTO: </td><td><select name=\"Dpto\" class=\"select\">";
        $departamento = $this->Departamentos();
        $this->BuscarDepartamento($departamento, $d, $_REQUEST['Dpto']);
        $this->salida .= "</select></td></tr>";

        //SERVICIOS
        $this->salida .= "<tr><td class=\"label\" colspan=\"2\" align=\"left\"><a href=\"javascript:VerServicios();\">SERVICIOS</a></td>";
        $this->salida .= "    <tr align=\"center\"><td class=\"modulo_table_list\" colspan=\"2\">";
        $this->salida .= "      <div id='Servicios' style=\"display:none\">";
        $this->salida .= "            <table width=\"55%\" align=\"center\" border=\"0\">";
        $DatosServicios = $this->Servicios();
        foreach ($DatosServicios AS $i => $v) {
            $this->salida .= "               <tr><td align=\"left\" class=\"label\">" . $v . "</td>&nbsp;&nbsp;<td align=\"left\" class=\"label\"><input type=\"checkbox\" name=\"Servicios$i\" value=\"" . $i . "\"></td></tr>";
        }
        $this->salida .= "            </table>";
        $this->salida .= "      </div>";
        $this->salida .= "    </td></tr>";
        //FIN SERVICIOS
        //TIPOS DE USUARIOS
        $this->salida .= "<tr><td class=\"label\" colspan=\"2\" align=\"left\"><a href=\"javascript:VerTiposUsuarios();\">TIPOS USUARIOS</a></td>";
        $this->salida .= "    <tr align=\"center\"><td class=\"modulo_table_list\" colspan=\"2\">";
        $this->salida .= "      <div id='TiposUsuarios' style=\"display:none\">";
        $this->salida .= "            <table width=\"55%\" align=\"center\" border=\"0\">";
        $DatosUsuarios = $this->TiposUsuarios();
        foreach ($DatosUsuarios AS $i => $v) {
            $this->salida .= "               <tr><td align=\"left\" class=\"label\">" . $v[descripcion] . "</td>&nbsp;&nbsp;<td align=\"left\" class=\"label\"><input type=\"checkbox\" name=\"TiposUsuarios$i\" value=\"" . $v[tipos_condicion_usuarios_planes_id] . "\"></td></tr>";
        }
        $this->salida .= "            </table>";
        $this->salida .= "      </div>";
        $this->salida .= "    </td></tr>";
        //FIN TIPOS DE USUARIOS

        $this->salida .= "                <tr>";
        $i = $_REQUEST['FechaI'];
        if (!empty($i)) {
            $f = explode('-', $_REQUEST['FechaI']);
            $i = $f[2] . '/' . $f[1] . '/' . $f[0];
        }
        /* if($arr=='si' OR !empty($arr))
          {  $i=''; } */
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaI") . "\">DESDE: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaI\" value=\"" . $i . "\">" . ReturnOpenCalendario('forma', 'FechaI', '/') . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $fi = $_REQUEST['FechaF'];
        if (!empty($i)) {
            $f = explode('-', $_REQUEST['FechaF']);
            $fi = $f[2] . '/' . $f[1] . '/' . $f[0];
        }
        /* if($arr=='si' OR !empty($arr))
          {  $fi='';  } */
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaF") . "\">HASTA: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaF\" value=\"" . $fi . "\">" . ReturnOpenCalendario('forma', 'FechaF', '/') . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $this->salida .= "                    <td class=\"" . $this->SetStyle("prefijo") . "\">PREFIJO: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"prefijo\" value=\"" . $_POST['prefijo'] . "\" size='3' maxlength=\"5\"></td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $this->salida .= "                    <td class=\"" . $this->SetStyle("numero") . "\">NUMERO: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"numero\" value=\"" . $_POST['numero'] . "\" size='6' maxlength=\"10\"></td>";
        $this->salida .= "                </tr>";
        $this->salida .= "<tr class=\"label\">";
        $this->salida .= "</tr>";
        $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
        $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSQUEDA\"></td>";
        $this->salida .= "</form>";
        $actionM = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaMenus');  //}
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"><br></td></form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table></td></tr>";
        $this->salida .= "</td></tr></table>";
        $this->salida .= "</table>";
        $this->salida .= "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>";
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        unset($_SESSION['FACTURACION']['ENVIO']['ARREGLO']);
        if (!empty($arr) AND $arr != 'si') {
            $flagRango = true;
            $_SESSION['FACTURACION']['ENVIO']['ARREGLO'] = $arr;
            $vars = $this->Facturas($_REQUEST[prefijo], $_REQUEST[numero], $_REQUEST['FechaI'], $_REQUEST['FechaF']);
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarFormaEnvio');
            $this->salida .= "    <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td width=\"10%\">FACTURA</td>";
            $this->salida .= "        <td width=\"15%\">IDENTIFICACION</td>";
            $this->salida .= "        <td width=\"30%\">PACIENTE</td>";
            $this->salida .= "        <td width=\"20%\">PLAN</td>";
            $this->salida .= "        <td width=\"25%\">USUARIO</td>";
            $this->salida .= "        <td width=\"15%\">VALOR</td>";
            $this->salida .= "        <td width=\"5%\"><input type=\"checkbox\" name=\"Todo\" onClick=\"Todos(this.form,this.checked)\"></td>";
            $this->salida .= "      </tr>";

            $rangoEnvio = $vars[0]['rango'];
            if ($adicionar != '1') {
                for ($i = 0; $i < sizeof($vars); $i++) {
                    if ($vars[$i][porqueria] == 0) {
                        if (!empty($arr[$vars[$i][prefijo]][$vars[$i][factura_fiscal]])) {
                            if ($rangoEnvio != $vars[$i]['rango'])
                                $flagRango = false;

                            if ($i % 2) {
                                $estilo = 'modulo_list_claro';
                            } else {
                                $estilo = 'modulo_list_oscuro';
                            }
                            $this->salida .= "      <tr class=\"$estilo\">";
                            $this->salida .= "        <td align=\"center\">" . $vars[$i][prefijo] . " " . $vars[$i][factura_fiscal] . "</td>";
                            $this->salida .= "        <td>" . $vars[$i][id] . "</td>";
                            $this->salida .= "        <td>" . $vars[$i][nombre] . "</td>";
                            $this->salida .= "        <td align=\"center\">" . $vars[$i][plan_descripcion] . "</td>";
                            $this->salida .= "        <td>" . $vars[$i][usuario] . "</td>";
                            $this->salida .= "        <td 	align=\"center\">" . FormatoValor($vars[$i][total_factura]) . "</td>";
                            $this->salida .= "            <td><input type=\"checkbox\" value=\"" . $vars[$i][prefijo] . "||" . $vars[$i][factura_fiscal] . "||" . $vars[$i][id] . "||" . $vars[$i][nombre] . "||" . $vars[$i][total_factura] . "||" . $vars[$i][plan_id] . "||" . $vars[$i][plan_descripcion] . "||" . $vars[$i][empresa_id] . "||" . $vars[$i][centro_utilidad] . "||" . $vars[$i][tipo_plan] . "||" . $vars[$i][tipo_tercero_id] . "||" . $vars[$i][tercero_id] . "\" name=\"Envio" . $vars[$i][prefijo] . "" . $vars[$i][factura_fiscal] . $i . "\" checked></td>";
                        }
                    }
                    $this->salida .= "      </tr>";
                }
            } else {
                $g = 0;
                foreach ($_SESSION['FACTURACION']['ENVIO']['SELECCION'] as $k => $v) {
                    if (substr_count($k, 'Envio')) {
                        $y = explode('||', $v);
                        $seleccion[$g] = $y[0] . '-' . $y[1];
                        $g++;
                    }
                }

                for ($i = 0; $i < sizeof($vars); $i++) {
                    if ($vars[$i][porqueria] == 0) {
                        if (!empty($arr[$vars[$i][prefijo]][$vars[$i][factura_fiscal]])) {
                            if ($i % 2) {
                                $estilo = 'modulo_list_claro';
                            } else {
                                $estilo = 'modulo_list_oscuro';
                            }
                            $this->salida .= "      <tr class=\"$estilo\">";
                            $this->salida .= "        <td align=\"center\">" . $vars[$i][prefijo] . " " . $vars[$i][factura_fiscal] . "</td>";
                            $this->salida .= "        <td>" . $vars[$i][id] . "</td>";
                            $this->salida .= "        <td>" . $vars[$i][nombre] . "</td>";
                            $this->salida .= "        <td align=\"center\">" . $vars[$i][plan_descripcion] . "</td>";
                            //$this->salida .= "        <td align=\"center\">".$vars[$i][fecha_registro]."</td>";
                            $this->salida .= "        <td>" . $vars[$i][usuario] . "</td>";
                            $this->salida .= "        <td align=\"center\">" . FormatoValor($vars[$i][total_factura]) . "</td>";

                            for ($k = 0; $k < sizeof($seleccion); $k++) {
                                $x = explode('-', $seleccion[$k]);
                                if ($x[0] == $vars[$i][prefijo] AND $x[1] == $vars[$i][factura_fiscal]) {
                                    $this->salida .= " <td><input type=\"checkbox\" value=\"" . $vars[$i][prefijo] . "||" . $vars[$i][factura_fiscal] . "||" . $vars[$i][id] . "||" . $vars[$i][nombre] . "||" . $vars[$i][total_factura] . "||" . $vars[$i][plan_id] . "||" . $vars[$i][plan_descripcion] . "||" . $vars[$i][empresa_id] . "||" . $vars[$i][centro_utilidad] . "\" name=\"Envio" . $vars[$i][prefijo] . "" . $vars[$i][factura_fiscal] . $i . "\" checked></td>";
                                    $k = sizeof($seleccion);
                                } elseif ($k == (sizeof($seleccion) - 1)) {
                                    $this->salida .= " <td><input type=\"checkbox\" value=\"" . $vars[$i][prefijo] . "||" . $vars[$i][factura_fiscal] . "||" . $vars[$i][id] . "||" . $vars[$i][nombre] . "||" . $vars[$i][total_factura] . "||" . $vars[$i][plan_id] . "||" . $vars[$i][plan_descripcion] . "||" . $vars[$i][empresa_id] . "||" . $vars[$i][centro_utilidad] . "\" name=\"Envio" . $vars[$i][prefijo] . "" . $vars[$i][factura_fiscal] . $i . "\"></td>";
                                }
                            }
                        }
                    }
                    $this->salida .= "      </tr>";
                }
            }
            $this->salida .= "  </table>";
            $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\" HACER ENVIO\"></p>";
            $this->salida .= "</form>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaEnvio($_REQUEST) {
        $this->salida.= ThemeAbrirTabla('ENVIOS');
        IncludeLib("tarifario");
        $this->EncabezadoEmpresa();
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'HacerEnvio');
        $this->salida .= "   <form name=\"formaenviar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "        <td width=\"30%\">FECHA DE REGISTRO DEL ENVIO</td>";
        $this->salida .= "        <td width=\"70%\" align=\"left\" colspan = \"4\" class=\"modulo_list_oscuro\">";
        $this->salida .= "          <input type=\"text\" class=\"input-text\" name=\"Fecha_Envio\" size=\"12\" value=\"" . date("d/m/Y") . "\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
        $this->salida .= "&nbsp;&nbsp;" . ReturnOpenCalendario('formaenviar', 'Fecha_Envio', '/') . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "        <td width=\"15%\">FACTURA</td>";
        $this->salida .= "        <td width=\"20%\">VALOR</td>";
        $this->salida .= "        <td width=\"15%\">IDENTIFICACION</td>";
        $this->salida .= "        <td width=\"25%\">PACIENTE</td>";
        $this->salida .= "        <td width=\"20%\">PLAN</td>";
        $this->salida .= "      </tr>";
        $i = $a = $total = 0;
        $k = 1;
        $factura = "";
        foreach ($_SESSION['FACTURACION']['ENVIO']['SELECCION'] as $k => $v) {
            if (substr_count($k, 'Envio')) {
                //0 prefijo 1 factura 2 tipoid y paciente 3 nombre
                //4 total 5 plan 6 plan_des 7empresa 8 centro
                $x = explode('||', $v);
                if ($factura != $x[0] . " " . $x[1]) {
                    $total+=$x[4];
                    $a++;
                }
                $var = '';
                $var = $this->DetalleFactura($x[0], $x[1]);
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                if (sizeof($var) > 1) {
                    $this->salida .= "      <tr class=\"$estilo\">";
                    $this->salida .= "        <td align=\"center\">" . $x[0] . " " . $x[1] . "</td>";
                    $this->salida .= "        <td align=\"center\">" . FormatoValor($x[4]) . "</td>";
                    $this->salida .= "        <td></td>";
                    $this->salida .= "        <td>AGRUPADA</td>";
                    $this->salida .= "        <td>" . $x[6] . "</td>";
                    $this->salida .= "      </tr>";
                } else {
                    $this->salida .= "      <tr class=\"$estilo\">";
                    $this->salida .= "        <td align=\"center\">" . $x[0] . " " . $x[1] . "</td>";
                    $this->salida .= "        <td align=\"center\">" . FormatoValor($var[0][total_factura]) . "</td>";
                    $this->salida .= "        <td>" . $var[0][tipo_id_paciente] . " " . $var[0][paciente_id] . "</td>";
                    $this->salida .= "        <td>" . $var[0][nombre] . "</td>";
                    $this->salida .= "        <td>" . $x[6] . "</td>";
                    $this->salida .= "      </tr>";
                }
                /* for($j=0; $j<sizeof($var); $j++)
                  {

                  $this->salida .= "      <tr class=\"$estilo\">";
                  $this->salida .= "        <td align=\"center\">".$x[0]." ".$x[1]."</td>";
                  $this->salida .= "        <td align=\"center\">".FormatoValor($x[4])."</td>";
                  $this->salida .= "        <td>".$var[$j][tipo_id_paciente]." ".$var[$j][paciente_id]."</td>";
                  $this->salida .= "        <td>".$var[$j][nombre]."</td>";
                  $this->salida .= "        <td>".$x[6]."</td>";
                  $this->salida .= "      </tr>";
                  $k++;
                  } */
                $i++;
                $factura = $x[0] . " " . $x[1];
            }
        }
        $this->salida .= "  </table>";
        $this->salida .= "     <br><table width=\"70%\" border=\"0\" cellpadding=\"2\" cellspacing=\"2\" align=\"left\" class=\"normal_10\">";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td width=\"15%\">&nbsp;</td>";
        $this->salida .= "        <td width=\"15%\">OBSERVACIONES:</td>";
        $this->salida .= "        <td><textarea name=\"Observaciones\" cols=\"45\" rows=\"3\" class=\"textarea\"></textarea></td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td width=\"15%\">&nbsp;</td>";
        $this->salida .= "        <td width=\"20%\">TOTAL DOCUMENTOS: </td>";
        $this->salida .= "        <td>$a</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td width=\"15%\">&nbsp;</td>";
        $this->salida .= "        <td width=\"20%\">TOTAL ENVIO ($): </td>";
        $this->salida .= "        <td>" . FormatoValor($total) . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "  </table><BR>";
        $this->salida .= "  <p><br></p>";
        $this->salida .= "     <BR><BR><BR><table width=\"40%\" border=\"0\" cellpadding=\"2\" cellspacing=\"2\" align=\"center\">";
        $this->salida .= "      <tr>";
        $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ENVIAR\"></td>";
        $this->salida .= "  </form>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarEnvios');
        $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
        $this->salida .= "  </form>";
        $ac = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarEnvios', array('adicionar' => '1'));
        $this->salida .= "             <form name=\"formadicionarenvio\" action=\"$ac\" method=\"post\">";
        $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ADICIONAR FACTURA\"></td>";
        $this->salida .= "  </form>";
        $this->salida .= "      </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     * Funcion que Saca los años para el calendario a partir del año actual
     * @return array
     */
    function AnosAgenda($Seleccionado='False', $ano) {

        $anoActual = date("Y");
        $anoActual1 = $anoActual;
        for ($i = 0; $i <= 10; $i++) {
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

    function MesesAgenda($Seleccionado='False', $Anyo, $Defecto) {
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
        $mesActual = date("m");
        switch ($Seleccionado) {
            case 'False': {
                    if ($anoActual == $Anyo) {
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
                    if ($anoActual == $A�) {
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

    /* Metodo javascript que abre la ventana emergente con los datos de una autorizacion
     * @access private
     */

    function ConsultaAutorizacion() {
        $this->salida .= "<SCRIPT>";
        $this->salida .= "function ConsultaAutorizacion(nombre, url, ancho, altura,Tarifario,Cargo,Cuenta,Autorizacion,Ayudas,tipo){";
        $this->salida .= " var str = 'width='+ancho+',height='+altura+',X=300,Y=800,resizable=no,status=no,scrollbars=yes';";
        $this->salida .= " var url2 = url+'?Tarifario='+Tarifario+'&Cargo='+Cargo+'&Cuenta='+Cuenta+'&Autorizacion='+Autorizacion+'&Ayudas='+Ayudas+'&Tipo='+tipo;";
        $this->salida .= " rem = window.open(url2, nombre, str);";
        $this->salida .= "  if (rem != null) {";
        $this->salida .= "     if (rem.opener == null) {";
        $this->salida .= "       rem.opener = self;";
        $this->salida .= "     }";
        $this->salida .= "  }";
        $this->salida .= "}";
        $this->salida .= "</SCRIPT>";
    }

    /**
     * Muestra el detalle de la cuenta.
     * @access private
     * @return boolean
     * @param int numero de la cuenta
     * @param string tipo documento
     * @param int numero documento
     * @param string nivel
     * @param string plan_id
     * @param int numero de cama
     * @param date fecha de la cuenta
     * @param int ingreso
     * @param array arreglo con los datos de la cuenta
     * @param int numero de transaccion
     */
    function FormaFacturas($Cuenta, $TipoId, $PacienteId, $PlanId, $Nivel, $Fecha, $Ingreso, $Transaccion, $Dev, $vars, $Estado, $arre, $tipo_factura, $verhojas, $prefijo, $numero) {
        IncludeLib("tarifario");
        IncludeLib('funciones_facturacion');
        if (empty($_SESSION['CUENTAS']['RETORNO'])) {
            $_SESSION['CUENTAS']['RETORNO']['contenedor'] = 'app';
            $_SESSION['CUENTAS']['RETORNO']['modulo'] = "Facturacion_Fiscal";
            $_SESSION['CUENTAS']['RETORNO']['tipo'] = 'user';
            $_SESSION['CUENTAS']['RETORNO']['metodo'] = 'Facturacion';
            $_SESSION['CUENTAS']['RETORNO']['argumentos'] = "'cuenta' = " . $_REQUEST['Cuenta'] . ",'TipoId'= " . $_REQUEST['TipoId'] . ",'PacienteId'=" . $_REQUEST['PacienteId'] . ",'PlanId'=" . $_REQUEST['PlanId'] . ",'Nivel'=" . $_REQUEST['Nivel'] . ",'Fecha'=" . $_REQUEST['Fecha'] . ",'Ingreso'=" . $_REQUEST['Ingreso'] . ",'Transaccion'=" . $_REQUEST['Transaccion'] . ",'Dev'=" . $Dev . ",'vars'=" . $vars . ",'Estado'=" . $_REQUEST['Estado'] . ",'tipo_factura'=" . $_REQUEST['tipo_factura'] . ",'verhojas'=" . $_REQUEST['verhojas'] . "";
        }
        global $VISTA;

        $mostrar = "\n<script>\n";
        $RUTA = $_ROOT . "cache/facturapaciente" . $Cuenta . $prefijo . $numero . ".pdf";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentana(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        $mostrar.="\n</script>\n";
        $this->salida = $mostrar;
        $Nombres = $this->BuscarNombresApellidosPaciente($Ingreso);
        if ($vars) {
            $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. ' . $Cuenta . '  TRANSACCION No. ' . $Transaccion . ' (Insumos y Medicamentos)<br>&nbsp;&nbsp;&nbsp;&nbsp;' . $Nombres[nombre]);
        }
        if ($Dev) {
            $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. ' . $Cuenta . '  TRANSACCION No. ' . $Transaccion . ' (Devolución Insumos y Medicamentos)<br>&nbsp;&nbsp;&nbsp;&nbsp;' . $Nombres[nombre]);
        }
        if (!$Dev && !$vars) {
            $this->salida .= ThemeAbrirTabla('DETALLES DE CARGOS CUENTA No.' . $Cuenta . ' ' . $Nombres[nombre]);
        }
        $this->salida .= $this->ConsultaAutorizacion();
        $this->EncabezadoEmpresa();
        //$Detalle = $this->BuscarDetalleCuenta($Cuenta);
        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $Fecha, 'Cuenta' => $Cuenta));
        $_SESSION['CUENTAS']['EMPRESA'] = $_SESSION['FACTURACION']['EMPRESA'];
        $_SESSION['CUENTAS']['CENTROUTILIDAD'] = $_SESSION['FACTURACION']['CENTROUTILIDAD'];
        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamaTotalesCuenta', array('Cuenta' => $Cuenta, 'link_modi' => '1', "factura" => $this->facturas));
        //unset($_SESSION['CUENTAS']);
        $this->salida .= "	</fieldset></td></tr></table><br>\n";
        $this->salida .= "		<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"75%\" align=\"center\"  >";
        $this->salida .= "    		<tr align=\"center\">";

        if (empty($_SESSION['FACTURACION']['CERRADAS'])) {
            $accionT = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarFormaMetodoBuscar');
        } elseif (!empty($_REQUEST['Agrupada']) AND !empty($_REQUEST['prefijo']) AND !empty($_REQUEST['numero'])) {
            $accionT = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'MostrarDetalle', array('numero' => $_REQUEST['numero'], 'prefijo' => $_REQUEST['prefijo']));
        } else {
            $accionT = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarFacturas', array('prnAnuladas' => $this->facturas[prnAnuladas]));
        }
        //para lo de salida de paciente
        if (!empty($_SESSION['FACTURACION']['RETORNO'])) {
            $contenedor = $_SESSION['FACTURACION']['RETORNO']['contenedor'];
            $modulo = $_SESSION['FACTURACION']['RETORNO']['modulo'];
            $tipo = $_SESSION['FACTURACION']['RETORNO']['tipo'];
            $metodo = $_SESSION['FACTURACION']['RETORNO']['metodo'];
            $argumentos = $_SESSION['FACTURACION']['RETORNO']['argumentos'];
            $accionT = ModuloGetURL($contenedor, $modulo, $tipo, $metodo, $argumentos);
        }
        $limite_cuenta = $this->IdentificarTotalCuentaFueraRangoSoat($Cuenta);
        if ($limite_cuenta == '1' || $limite_cuenta == '2') {
            $this->salida .= "  	<td>";
            $this->salida .= "    <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"75%\" align=\"center\"  >";
            $this->salida .= "    <tr align=\"center\"><td align=\"center\" class=\"label_error\">";
            if ($limite_cuenta == '1') {
                $this->salida .= "    EL VALOR LIMITE QUE CUBRE EL SOAT PASO EL VALOR CUBIERTO DE LA CUENTA";
            } elseif ($limite_cuenta == '2') {
                $this->salida .= "    EL VALOR LIMITE QUE CUBRE EL SOAT ES IGUAL AL VALOR CUBIERTO DE LA CUENTA";
            }
            $this->salida .= "    </td></tr>";
            $this->salida .= "    </table>";
            $this->salida .= "  	</td>";
        }
        $this->salida .= "	<form name=\"formabuscar\" action=\"$accionT\" method=\"post\">\n";
        $this->salida .= "  	<td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"BUSQUEDA\"></td>\n";
        $this->salida .= "  </form>\n";





        if (empty($this->facturas[prnAnuladas])) {
            $acchoja = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarVentanaFinal', array('numerodecuenta' => $Cuenta, 'plan_id' => $PlanId, 'tipoid' => $TipoId, 'pacienteid' => $PacienteId, 'Nivel' => $Nivel, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Transaccion' => $Transaccion, 'Dev' => $Dev, 'vars' => $vars, 'Estado' => $Estado, 'tiporeporte' => 'reportes'));
            $this->salida .= "             <form name=\"reportes\" action=\"$acchoja\" method=\"post\">";
            $this->salida .= "               <td><label class='label_mark'>Tipo Hoja Cargos: </label><select name=\"reporteshojacargos\" class=\"select\">";

            //$this->salida .=" <option value='-1'>----SELECCIONE----</option>";
            $reportes = $this->TraerReportesHojaCargos();

            for ($i = 0; $i < sizeof($reportes); $i++) {
                $this->salida .=" <option value=\"" . $reportes[$i][ruta_reporte] . "," . $reportes[$i][titulo] . "\">" . $reportes[$i][titulo] . "</option>";
            }
            $this->salida .= "              </select>";
            $this->salida .= "              <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VER\"><br></td></form>";
        }

        if (empty($this->facturas[prnAnuladas])) {
            $value = "value=\"FACTURA CONCEPTOS\"";
        } else {
            $value = "value=\"IMPRIMIR\"";
        }
        $rqs = array('numerodecuenta' => $Cuenta,
            'plan_id' => $PlanId,
            'tipoid' => $TipoId,
            'pacienteid' => $PacienteId,
            'Nivel' => $Nivel,
            'Fecha' => $Fecha,
            'Ingreso' => $Ingreso,
            'Transaccion' => $Transaccion,
            'Dev' => $Dev,
            'vars' => $vars,
            'Estado' => $Estado,
            'tipo_factura' => $_REQUEST['tipo_factura'],
            'tiporeporte' => 'conceptos',
            'prnAnuladas' => $this->facturas['prnAnuladas'],
            'prefijo' => $this->facturas['prefijo'],
            'numero' => $this->facturas['factura']
        );
        $accconceptos = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarVentanaFinal', $rqs);
        $this->salida .= "  <form name=\"formaresumen\" action=\"$accconceptos\" method=\"post\">";
        $this->salida .= "    <td>\n";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Consultar\" " . $value . ">\n";
        $this->salida .= "    </td>";
        $this->salida .= "  </form>";
        if ($tipo_factura === NULL) {
            $tipo_factura = (integer) $this->TraerTipoFactura($Cuenta);
        }
//      CAMBIO
//        if (($tipo_factura == 0 OR $tipo_factura == 2) AND empty($this->facturas[prnAnuladas])) {
        if (!empty($this->facturas[prnAnuladas])) {
            $acc = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarVentanaFinal', array('numerodecuenta' => $Cuenta, 'plan_id' => $PlanId, 'tipoid' => $TipoId, 'pacienteid' => $PacienteId, 'Nivel' => $Nivel, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Transaccion' => $Transaccion, 'Dev' => $Dev, 'vars' => $vars, 'Estado' => $Estado, 'tipo_factura' => $tipo_factura, 'tiporeporte' => 'facturapaciente', 'prefijo' => $prefijo, 'numero' => $numero));
            $this->salida .= "  <form name=\"formareport\" action=\"$acc\" method=\"post\">";
            $this->salida .= "     <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"FACTURA PACIENTE\"></td>";
            $this->salida .= "  </form>";

            $tipo_recibo = (integer) $this->TraerTipoRecibo($prefijo, $numero);
            if ($tipo_recibo == 1 OR $tipo_recibo == 2) {
                $reporte = new GetReports();
                $this->salida .= $reporte->GetJavaReport('app', 'Facturacion_Fiscal', 'FacturaRC', array('cuenta' => $Cuenta, 'prefijo' => $prefijo, 'factura_fiscal' => $numero), array('rpt_dir' => 'cache', 'rpt_name' => 'recibo' . $prefijo . $numero, 'rpt_rewrite' => FALSE));
                $funcion = $reporte->GetJavaFunction();
                $this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"RECIBO AMBULATORIO\"  onclick=\"javascript:$funcion\"></td>";
            }
        } elseif ($tipo_factura != NULL AND empty($this->facturas[prnAnuladas])) {
            //IncludeLib("reportes/factura");
            //GenerarFactura($var);
//            if (empty($this->facturas[prnAnuladas])){
                $accfactura = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarVentanaFinal', array('numerodecuenta' => $Cuenta, 'plan_id' => $PlanId, 'tipoid' => $TipoId, 'pacienteid' => $PacienteId, 'Nivel' => $Nivel, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Transaccion' => $Transaccion, 'Dev' => $Dev, 'vars' => $vars, 'Estado' => $Estado, 'tipo_factura' => $_REQUEST['tipo_factura'], 'tiporeporte' => 'factura'));
                $this->salida .= "  <form name=\"formafactura\" action=\"$accfactura\" method=\"post\">";
                $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"IMPRIMIR FACTURA\"></td>";
                $this->salida .= "  </form>";
//            }
            $imp_factura = $this->ConsultaEstadoImpFactura();

            if ($imp_factura[0]['sw_imp_copia'] == '1' AND $this->PermisoCambioEstadoImpFactura() == true) {
                $accimpfactura = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'ActualizaEstadoImpFactura', array('numerodecuenta' => $Cuenta, 'plan_id' => $PlanId, 'tipoid' => $TipoId, 'pacienteid' => $PacienteId, 'Nivel' => $Nivel, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Transaccion' => $Transaccion, 'Dev' => $Dev, 'vars' => $vars, 'Estado' => $Estado, 'tipo_factura' => $tipo_factura, 'prefijo' => $prefijo, 'numero' => $numero));
                $this->salida .= "  <form name=\"formafactura\" action=\"$accimpfactura\" method=\"post\">";
                $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"EstadoImpFactura\" value=\"ACTIVAR FACTURA ORIGINAL\"></td>";
                $this->salida .= "  </form>";
            }
        }
        //ACTIVAR - INACTIVAR
        $Estado = '';
        if ($Estado == 'A') {
            $value = "INACTIVAR";
            $acc = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'ActulizarEstado', array('numerodecuenta' => $Cuenta, 'plan_id' => $PlanId, 'tipoid' => $TipoId, 'pacienteid' => $PacienteId, 'Nivel' => $Nivel, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Transaccion' => $Transaccion, 'Dev' => $Dev, 'vars' => $vars, 'Estado' => $Estado, 'tipo_factura' => $tipo_factura, 'prefijo' => $prefijo, 'numero' => $numero));
        } elseif ($Estado == 'I') {
            $value = "ACTIVAR";
            $acc = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'ActulizarEstado', array('numerodecuenta' => $Cuenta, 'plan_id' => $PlanId, 'tipoid' => $TipoId, 'pacienteid' => $PacienteId, 'Nivel' => $Nivel, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Transaccion' => $Transaccion, 'Dev' => $Dev, 'vars' => $vars, 'Estado' => $Estado, 'tipo_factura' => $tipo_factura, 'prefijo' => $prefijo, 'numero' => $numero));
        }
        if (!empty($Estado)) {
            $this->salida .= "  <form name=\"forma\" action=\"$acc\" method=\"post\">";
            $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"$value\"></td>";
            $this->salida .= "  </form>";
        }
        //FIN ACTIVAR - INACTIVAR
        $Estado = BuscarEstadoCuenta($Cuenta);
        //$sw=$this->FacturaAgrupada($Cuenta,$PlanId);     
        if (empty($_SESSION['FACTURACION']['CERRADAS'])) {
            if ($Estado == 'C') {
                $tipo = $this->FacturaAgrupada($PlanId); //1 es agrupada

                if ($tipo == 1) {
                    $accresumen = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarVentanaFinal', array('numerodecuenta' => $Cuenta, 'plan_id' => $PlanId, 'tipoid' => $TipoId, 'pacienteid' => $PacienteId, 'Nivel' => $Nivel, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Transaccion' => $Transaccion, 'Dev' => $Dev, 'vars' => $vars, 'Estado' => $Estado, 'tipo_factura' => $_REQUEST['tipo_factura'], 'tiporeporte' => 'resumen'));
                    $this->salida .= "  <form name=\"formaresumen\" action=\"$accresumen\" method=\"post\">";
                    $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"RESUMEN FACTURA\"></td>";
                    $this->salida .= "  </form>";
                }
            }

            $rutaVolver = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'PlanId' => $PlanId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $Fecha, 'Cuenta' => $Cuenta, 'Transaccion' => $Transaccion, 'Dev' => $Dev, 'vars' => $vars, 'Estado' => $Estado, 'tipo_factura' => $_REQUEST['tipo_factura']));
            $botones = $this->ReturnModuloExterno('app', 'Facturar', 'user');
            $this->salida .= $botones->FormaMostrarBotonesFacturar($Cuenta, $_SESSION[FACTURACION][PUNTOFACTURACION]);
            $botones->SetActionVolver($rutaVolver);
            $this->salida .= "    </tr>";
        } else {
            $var = $this->BuscarFactura($Cuenta);
            if (!empty($var)) {
                $this->salida .= "    </tr>";
            }
        }
        $this->salida .= "    <tr align=\"center\"><td align=\"center\" class=\"label_error\">";
        $numero_cuenta = explode(",'TipoId'", $_SESSION['CUENTAS']['RETORNO']['argumentos']);
        $numero_cuenta1 = explode("'cuenta' =", $numero_cuenta[0]);
        $reportes_recibocaja_todos = $this->TraerRecibosCaja($Cuenta);
        $reportes_recibodev_todos = $this->TraerRecibosDevolucion($Cuenta);
        //$reportes_recibopag_todos=$this->TraerRecibosPagare($Cuenta);
        //var_dump($reportes_recibopag_todos);
        if (!empty($reportes_recibocaja_todos[0])) {
            $acchoja1 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarVentanaFinal', array('numerodecuenta' => $Cuenta, 'plan_id' => $PlanId, 'tipoid' => $TipoId, 'pacienteid' => $PacienteId, 'Nivel' => $Nivel, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'prefijo1' => $prefijo, 'empresa' => $_SESSION['CUENTAS']['EMPRESA'], 'centro_utilidad1' => $_SESSION['CUENTAS']['CENTROUTILIDAD'], 'Transaccion' => $Transaccion, 'Dev' => $Dev, 'vars' => $vars, 'Estado' => $Estado, 'tiporeporte' => 'recibo_caja'));
            $this->salida .= "<form name=\"reportes_recibocaja\" action=\"$acchoja1\" method=\"post\">";
            $this->salida .= " <td>";
            $this->salida .="  <label class='label_mark'>RECIBO CAJA: </label>";
            $this->salida .="   <select name=\"reportes_recibocaja\" class=\"select\">";
            for ($i = 0; $i < sizeof($reportes_recibocaja_todos); $i++) {

                $this->salida .=" <option value=\"" . $reportes_recibocaja_todos[$i]['recibo_caja'] . "-" . $reportes_recibocaja_todos[$i]['caja_id'] . "-" . $reportes_recibocaja_todos[$i]['centro_utilidad'] . "-" . $reportes_recibocaja_todos[$i]['prefijo'] . "\">" . $reportes_recibocaja_todos[$i]['prefijo'] . "-" . $reportes_recibocaja_todos[$i]['recibo_caja'] . "</option>";
            }
            $this->salida .= " </select>";
            //$this->salida .= " <input type=\"hidden\" name=\"caja_id\" value=\"hola".$reportes_recibocaja_todos[$i]['caja_id']."\">\n";
            $this->salida .= " <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VER\"><br></td></form>";
        }
        if (!empty($reportes_recibodev_todos[0])) {
            $acchoja1 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarVentanaFinal', array('numerodecuenta' => $Cuenta, 'plan_id' => $PlanId, 'tipoid' => $TipoId, 'pacienteid' => $PacienteId, 'Nivel' => $Nivel, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'prefijo1' => $prefijo, 'empresa' => $_SESSION['CUENTAS']['EMPRESA'], 'centro_utilidad1' => $_SESSION['CUENTAS']['CENTROUTILIDAD'], 'Transaccion' => $Transaccion, 'Dev' => $Dev, 'vars' => $vars, 'Estado' => $Estado, 'tiporeporte' => 'recibo_devolucion'));

            $this->salida .= " <form name=\"reportes_recibodev\" action=\"$acchoja1\" method=\"post\">";
            $this->salida .= " <td>";
            $this->salida .= "  <label class='label_mark'>RECIBO DEVOLUCION: </label>";
            $this->salida .="    <select name=\"reportes_recibodev\" class=\"select\">";
            for ($i = 0; $i < sizeof($reportes_recibodev_todos); $i++) {

                $this->salida .=" <option value=\"" . $reportes_recibodev_todos[$i]['recibo_caja'] . "-" . $reportes_recibodev_todos[$i]['caja_id'] . "-" . $reportes_recibodev_todos[$i]['centro_utilidad'] . "-" . $reportes_recibodev_todos[$i]['prefijo'] . "\">" . $reportes_recibodev_todos[$i]['prefijo'] . "-" . $reportes_recibodev_todos[$i]['recibo_caja'] . "</option>";
            }
            $this->salida .= "              </select>";
            $this->salida .= "              <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VER\"><br></td></form>";
        }

        $this->salida .= "    </tr>";
        $this->salida .= "    </table>";
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        //tabla pendientes cargar
        $y = BuscarPendientesCargar($Ingreso);
        if (!empty($y)) {
            $z = PendientesCargar($Ingreso);
            //$this->FormaPendientesCargar($z,$PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Cama,$Fecha,$Ingreso);
            IncludeClass('CargosPendientesPorCargarHTML', '', 'app', 'Facturacion_Fiscal');
            $obj = new CargosPendientesPorCargarHTML();
            $this->salida .= $obj->FormaPendientesCargar($z, $PlanId, $Cuenta, $TipoId, $PacienteId, $Nivel, $Cama, $Fecha, $Ingreso);
        }
        //fin pendientes cargar
        //if(!$vars && !$Dev)
        //{
        //--habitaciones
        unset($_SESSION['CUENTAS']['CAMA']['LIQ']);
        if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php")) {
            die(MsgOut("Error al incluir archivo", "El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
        }

        $liqHab = new LiquidacionHabitaciones;
        $hab = $liqHab->LiquidarCargosInternacion($Cuenta, false);

        if (is_array($hab)) {
            $_SESSION['CUENTAS']['CAMA']['LIQ'] = $hab;
            $this->FormaHabitaciones($hab, $PlanId, $Cuenta, $TipoId, $PacienteId, $Nivel, $Cama, $Fecha, $Ingreso);
        } elseif (empty($hab)) {  //ocurrio un error hay q mostrarlo
            $this->salida .= "<p align=\"center\" class=\"label_error\">" . $liqHab->Err() . "<BR>" . $liqHab->ErrMsg() . "</p>";
        }
        //---FIN HABITACIONES		

        IncludeClass('DetalleCtaHTML', '', 'app', 'Cuentas');
        $this->IncludeJS("CrossBrowser");
        $accionM = ModuloGetURL('app', 'Facturacion', 'user', 'LlamaFormaModificar');
        $accionE = ModuloGetURL('app', 'Facturacion', 'user', 'LlamarFormaEliminarCargo');
        $html = new DetalleCtaHTML();
        $this->salida .= $html->CrearFormaDetalleCta($Cuenta, $_SESSION['CUENTAS']['SWCUENTAS'], $TipoId, $PacienteId, $Nivel, $PlanId, $Fecha, $Ingreso, $accionM, $accionE, $accionDevol, NULL, NULL, NULL, $this->facturas);
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaPendientesCargar($arr, $Plan, $cuenta, $TipoId, $PacienteId, $Nivel, $Cama, $Fecha, $Ingreso) {
        IncludeLib('funciones_admision');
        IncludeLib('funciones_facturacion');
        $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\"  class=\"modulo_table_list\">";
        $this->salida .= "    <tr align=\"center\" class=\"modulo_table_title\">";
        $this->salida .= "        <td class=\"label_error\"><img src=\"" . GetThemePath() . "/images/cargar.png\" border=\"0\">&nbsp;&nbsp;PENDIENTES POR CARGAR</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr align=\"center\">";
        $this->salida .= "        <td>";
        for ($i = 0; $i < sizeof($arr); $i++) {
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'InsertarPendientesCargar', array('Cuenta' => $cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $Plan, 'Cama' => $Cama, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'departamento' => $arr[$i][departamento], 'servicio' => $arr[$i][servicio], 'empresa' => $arr[$i][empresa_id], 'cu' => $arr[$i][centro_utilidad], 'ID' => $arr[$i][procedimiento_pendiente_cargar_id]));
            $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
            $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td width=\"8%\">CUPS</td>";
            $this->salida .= "        <td width=\"43%\">CARGO</td>";
            $this->salida .= "        <td width=\"8%\">DEPARTAMENTO</td>";
            $this->salida .= "        <td>USUARIO</td>";
            $this->salida .= "        <td width=\"8%\">TIPO PROFESIONAL</td>";
            $this->salida .= "        <td width=\"8%\">FECHA</td>";
            $this->salida .= "    </tr>";
            if ($i % 2)
                $estilo = 'modulo_list_claro';
            else
                $estilo = 'modulo_list_oscuro';
            $this->salida .= "      <tr class=\"$estilo\" align=\"center\">";
            $this->salida .= "       <td>" . $arr[$i][cargo_cups] . "</td>";
            $this->salida .= "       <td>" . $arr[$i][descups] . "</td>";
            $this->salida .= "       <td>" . $arr[$i][desdpto] . "</td>";
            $this->salida .= "       <td>" . $arr[$i][nombre] . "</td>";
            $this->salida .= "       <td></td>";
            $this->salida .= "       <td>" . FechaStamp($arr[$i][fecha]) . "</td>";
            $this->salida .= "    </tr>";
            $equi = ValdiarEquivalencias($Plan, $arr[$i][cargo_cups]);
            if (!empty($equi)) {
                $this->salida .= "      <tr align=\"center\">";
                $this->salida .= "       <td colspan=\"6\">";
                $this->salida .= "     <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                $this->salida .= "        <td>TARIFARIO</td>";
                $this->salida .= "        <td>CARGO</td>";
                $this->salida .= "        <td>DESCRIPCION</td>";
                $this->salida .= "        <td>PRECIO</td>";
                $this->salida .= "        <td></td>";
                $this->salida .= "      </tr>";
                for ($j = 0; $j < sizeof($equi); $j++) {
                    if ($j % 2)
                        $estilo = 'modulo_list_oscuro';
                    else
                        $estilo = 'modulo_list_claro';
                    $this->salida .= "     <tr class=\"$estilo\">";
                    $this->salida .= "        <td align=\"center\">" . $equi[$j][tarifario_id] . "</td>";
                    $this->salida .= "        <td align=\"center\">" . $equi[$j][cargo] . "</td>";
                    $this->salida .= "        <td>" . $equi[$j][descripcion] . "</td>";
                    $this->salida .= "        <td align=\"center\">" . FormatoValor($equi[$j][precio]) . "</td>";
                    //hay varias
                    if (sizeof($equi) > 1) {
                        $x = PendientesCargarEquivalencias($arr[$i][procedimiento_pendiente_cargar_id], $equi[$j][cargo], $equi[$j][tarifario_id]);
                        if ($x == 1) {
                            $this->salida .= "        <td align=\"center\"><input type = checkbox name= cargo" . $equi[$j][tarifario_id] . "" . $equi[$j][cargo] . " value=\"" . $equi[$j][tarifario_id] . "||" . $equi[$j][cargo] . "||" . $arr[$i][cargo_cups] . "||" . $arr[$i][autorizacion_int] . "||" . $arr[$i][autorizacion_ext] . "\" checked></td>";
                        } else {
                            $this->salida .= "        <td align=\"center\"><input type = checkbox name= cargo" . $equi[$j][tarifario_id] . "" . $equi[$j][cargo] . " value=\"" . $equi[$j][tarifario_id] . "||" . $equi[$j][cargo] . "||" . $arr[$i][cargo_cups] . "||" . $arr[$i][autorizacion_int] . "||" . $arr[$i][autorizacion_ext] . "\"></td>";
                        }
                    } else {  //solo hay una equivalencia
                        $this->salida .= "        <td align=\"center\"><input type = checkbox name= cargo" . $equi[$j][tarifario_id] . "" . $equi[$j][cargo] . " value=\"" . $equi[$j][tarifario_id] . "||" . $equi[$j][cargo] . "||" . $arr[$i][cargo_cups] . "||" . $arr[$i][autorizacion_int] . "||" . $arr[$i][autorizacion_ext] . "\" checked></td>";
                    }
                    $this->salida .= "      </tr>";
                }
                $this->salida .= "     </table>";
                $this->salida .= "       </td>";
                $this->salida .= "    </tr>";
            }
            $this->salida .= "      <tr align=\"center\">";
            $this->salida .= "       <td colspan=\"6\">";
            $this->salida .= "         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CARGAR A LA CUENTA\">";
            $this->salida .= "       </td>";
            $this->salida .= "    </tr>";
            $this->salida .= "  </table><BR>";
            $this->salida .= "</form>";
        }

        $this->salida .= "        </td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
    }

    /**
     * Busca el detalle de una cuenta en la tabla cuentas_detalle.
     * @access public
     * @return array
     * @param int numero de Cuenta
     */
    function CargosNoFacturados() {
        $Transaccion = $_REQUEST['Transaccion'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Nivel = $_REQUEST['Nivel'];
        $PlanId = $_REQUEST['PlanId'];
        $Pieza = $_REQUEST['Pieza'];
        $Cama = $_REQUEST['Cama'];
        $Fecha = $_REQUEST['Fecha'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Cuenta = $_REQUEST['Cuenta'];

        $arre = $this->DetalleCuentaNoFacturados($Cuenta);
        if (!$this->FormaFacturas($Cuenta, $TipoId, $PacienteId, $PlanId, $Nivel, $Fecha, $Ingreso, $Transaccion, $Dev, $vars, $Estado, $arre)) {
        //if(!$this->FormaCuenta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Cama,$Fecha,$Ingreso,$vars,$Transaccion,$mensaje,$Dev,$Estado,$arre))
            return false;
        }
        return true;
    }

    function FormaCargosNoFacturados($arre, $Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Fecha, $Ingreso) {
        $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\"  class=\"modulo_table_list\">";
        $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_claro\">";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Transaccion' => $Transaccion, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso));
        $this->salida .= "       <td><a href=\"$accion\">OCULTAR CARGOS NO FACTURADOS</a></td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\"  class=\"modulo_table_list\">";
        $this->salida .= "    <tr align=\"center\" class=\"modulo_table_title\"><td colspan=\"14\">CARGOS NO FACTURADOS</td></tr>";
        $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "        <td>FECHA</td>";
        $this->salida .= "        <td width=\"46%\">CARGO</td>";
        $this->salida .= "        <td width=\"8%\">PRECIO UNI.</td>";
        $this->salida .= "        <td>CANT.</td>";
        $this->salida .= "        <td width=\"8%\">VALOR</td>";
        $this->salida .= "        <td width=\"8%\">VAL. NO CUBIERTO</td>";
        $this->salida .= "        <td width=\"5%\">FIRMA</td>";
        $this->salida .= "        <td>DETALLE</td>";
        if ($_SESSION['CUENTAS']['SWCUENTAS'] != 'Cerradas') {
            $this->salida .= "      <td colspan=\"2\">ACCION</td>";
        }
        $this->salida .= "        <td>INT</td>";
        $this->salida .= "        <td>EXT</td>";
        $this->salida .= "        <td></td>";
        $this->salida .= "    </tr>";
        for ($i = 0; $i < sizeof($arre);) {
            if (!empty($arre[$i][codigo_agrupamiento_id])) {
                $d = $i;
                $Cantidad = $valor = $ValorNo = $ValEmpresa = 0;
                while ($arre[$i][codigo_agrupamiento_id] == $arre[$d][codigo_agrupamiento_id]) {
                    $Cantidad+=$arre[$d][cantidad];
                    $valor+=$arre[$d][fac];
                    $ValorNo+=$arre[$d][valor_nocubierto];
                    $ValEmpresa +=$arre[$d][valor_cubierto];
                    $d++;
                }
                $des = $this->NombreCodigoAgrupamiento($arre[$i][codigo_agrupamiento_id]);
                if ($i % 2)
                    $estilo = 'modulo_list_claro';
                else
                    $estilo = 'modulo_list_oscuro';
                $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
                $this->salida .= "        <td>$FechaD</td>";
                $this->salida .= "        <td>$des[descripcion]</td>";
                $this->salida .= "        <td>" . FormatoValor($Precio) . "</td>";
                $this->salida .= "        <td>$Cantidad</td>";
                $this->salida .= "        <td>" . FormatoValor($Valor) . "</td>";
                $this->salida .= "        <td>" . FormatoValor($ValorNo) . "</td>";
                $this->salida .= "        <td>";
                $accionHRef = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'DefinirForma', array('Transaccion' => $Transaccion, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Cama' => $Cama, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Cuenta' => $Cuenta, 'codigo' => $arre[$i][codigo_agrupamiento_id], 'consecutivo' => $arre[$i][consecutivo], 'doc' => $des[bodegas_doc_id], 'numeracion' => $des[numeracion], 'des' => $des[descripcion], "filtro" => 1));
                $this->salida .= "         <a href=\"$accionHRef\">VER</a>   ";
                $this->salida .= "        </td>";
                $this->salida .= "       <td></td>";
                $this->salida .= "       <td></td>";
                $this->salida .= "       <td></td>";
                $this->salida .= "       <td></td>";
                $this->salida .= "       <td></td>";
                $i = $d;
            }//fin if
            else {
                $NomCargo = $this->BuscarNombreCargo($arre[$i][tarifario_id], $arre[$i][cargo]);
                if ($i % 2)
                    $estilo = 'modulo_list_claro';
                else
                    $estilo = 'modulo_list_oscuro';
                $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
                $this->salida .= "        <td>" . $this->FechaStamp($arre[$i][fecha_registro]) . "</td>";
                $this->salida .= "        <td>$NomCargo[0]</td>";
                $this->salida .= "        <td>" . FormatoValor($arre[$i][precio]) . "</td>";
                $this->salida .= "        <td>" . round($arre[$i][cantidad]) . "</td>";
                $this->salida .= "        <td>" . FormatoValor($arre[$i][valor_cargo]) . "</td>";
                $this->salida .= "        <td>" . FormatoValor($arre[$i][valor_nocubierto]) . "</td>";
                $res = FirmaResultado($arre[$i][transaccion]);
                $img = '';
                //hay resultado
                if (!empty($res)) {
                    $this->salida .= "        <td><img src=\"" . GetThemePath() . "/images/checksi.png\"></td>";
                } else {
                    $this->salida .= "      <td></td>";
                }
                $this->salida .= "        <td></td>";
                if ($_SESSION['CUENTAS']['SWCUENTAS'] != 'Cerradas') {
                    $accionM = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamaFormaModificar', array('Transaccion' => $Transaccion, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Datos' => $arre[$i]));
                    $this->salida .= "    <td><a href=\"$accionM\">MODI</a></td>";
                    $mensaje = 'Esta seguro que desea eliminar este cargo.';
                    $arreglo = array('Transaccion' => $Transaccion, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso);
                    $accionE = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'ConfirmarAccion', array('c' => 'app', 'm' => 'Facturacion_Fiscal', 'me2' => 'Facturacion', 'me' => 'EliminarCargo', 'mensaje' => $mensaje, 'titulo' => 'ELIMINAR CARGO DE LA CUENTA No. ' . $Cuenta . ' ' . $Nombres . ' ' . $Apellidos, 'arreglo' => $arreglo, 'boton1' => 'ACEPTAR', 'boton2' => 'CANCELAR'));
                    $this->salida .= "    <td><a href=\"$accionE\">ELIM</a></td>";
                } else {
                    $this->salida .= "       <td></td>";
                    $this->salida .= "       <td></td>";
                }
                $D = $n = 1;
                $imagenInt = $imagenExt = '';
                if ($arre[$i][autorizacion_int] === '0') {
                    $imagenInt = "no_autorizado.png";
                    $D = 1;
                } elseif ($arre[$i][autorizacion_int] > 100) {
                    $imagenInt = "autorizado.png";
                    $D = 0;
                } elseif ($arre[$i][autorizacion_int] == 1) {
                    $imagenInt = "autorizadosiis.png";
                    $D = 1;
                }
                if ($arre[$i][autorizacion_ext] === '0') {
                    $imagenExt = "no_autorizado.png";
                    $n = 1;
                } elseif ($arre[$i][autorizacion_ext] > 100) {
                    $imagenExt = "autorizado.png";
                    $n = 0;
                } elseif ($arre[$i][autorizacion_ext] == 1) {
                    $imagenExt = "autorizadosiis.png";
                    $n = 1;
                }
                $this->salida .= "       <td>";
                if ($imagenInt) {
                    $this->salida .= "       <img src=\"" . GetThemePath() . "/images/$imagenInt\">";
                }
                $this->salida .= "       </td>";
                $this->salida .= "       <td>";
                if ($imagenExt) {
                    $this->salida .= "       <img src=\"" . GetThemePath() . "/images/$imagenExt\">";
                }
                $this->salida .= "       </td>";
                if ($D == 0 OR $n == 0) {
                    $this->salida .= "       <td><a href=\"javascript:ConsultaAutorizacion('DATOS DE LA AUTORIZACION','reports/$VISTA/datosautorizacioncargo.php',1000,250,'$TarifarioId','$Cargo',$Cuenta," . $Detalle[$i][interna] . ",0,'Int')\"><img src=\"" . GetThemePath() . "/images/informacion.png\" border=\"0\"></a></td>";
                } else {
                    $this->salida .= "       <td></td>";
                }
                $i++;
            }//fin else
        }//fin for
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
    }

    /**
     * Muestra el detalle de los medicamentos e insumos de una cuenta
     * @access private
     * @return void
     * @param array con el detalle
     * @param int numero de la cuenta
     * @param string tipo documento
     * @param int numero documento
     * @param string nivel
     * @param string plan_id
     * @param int numero de cama
     * @param date fecha de la cuenta
     * @param int ingreso
     */
    /* function FormaDetalleMedicamentos($Det,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$Estado)
      {
      IncludeLib("tarifario");
      $this->salida .= " <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"80%\" align=\"center\"  class=\"modulo_table_list\">";
      $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
      $this->salida .= "        <td>CODIGO</td>";
      $this->salida .= "        <td>DESCRIPCION</td>";
      $this->salida .= "        <td>PRECIO</td>";
      $this->salida .= "        <td>CANTIDAD</td>";
      $this->salida .= "        <td>IVA</td>";
      $this->salida .= "        <td>TOTAL</td>";
      $this->salida .= "      </tr>";
      for($i=0; $i<sizeof($Det); $i++)
      {
      if( $i % 2) $estilo='modulo_list_claro';
      else $estilo='modulo_list_oscuro';
      $this->salida .= "      <tr class=\"$estilo\" align=\"center\">";
      $this->salida .= "        <td>".$Det[$i][empresa_id]." - ". $Det[$i][codigo_producto]."</td>";
      $this->salida .= "        <td>".$Det[$i][descripcion]."</td>";
      $this->salida .= "        <td>".FormatoValor($Det[$i][precio_venta])."</td>";
      $this->salida .= "        <td width=\"9%\" nowrap>".round($Det[$i][despachada])."</td>";
      $this->salida .= "        <td>".FormatoValor($Det[$i][gravamen])."</td>";
      $this->salida .= "        <td>".FormatoValor($Det[$i][total_venta])."</td>";
      $this->salida .= "      </tr>";
      }
      $this->salida .= "  </table>";
      $accion=ModuloGetURL('app','Facturacion_Fiscal','user','Facturacion',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
      $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
      $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></p><br>";
      $this->salida .= "</form>";
      } */


    /**
     * Muestra el detalle de apoyos diagnsoticos de una cuenta.
     * @access private
     * @return boolean
     * @param int numero de la cuenta
     * @param string tipo documento
     * @param int numero documento
     * @param string nivel
     * @param string plan_id
     * @param date fecha de la cuenta
     * @param int ingreso
     * @param array arreglo con los datos de la cuenta
     * @param int numero de transaccion
     */
    /* function FormaCuentaApoyos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$vars,$Transaccion,$Estado)
      {
      IncludeLib("tarifario");
      $Nombres=$this->CallMetodoExterno('app','Autorizacion_Solicitud','user','BuscarNombresApellidosPaciente',array($Ingreso));
      $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. '.$Nombres[nombre].' TRANSACCION No. '.$Transaccion.' (Apoyos Diagnosticos)');
      $this->EncabezadoEmpresa();
      $this->ReturnMetodoExterno('app','Facturacion','user','LlamadaFormaEncabezado',array('PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$Fecha));
      $this->salida .= " <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"98%\" align=\"center\"  >";
      $this->salida .= "    <tr class=\"modulo_table_title\">";
      $this->salida .= "        <td>DETALLE DE MEDIOS DIAGNOSTICOS</td>";
      $this->salida .= "    </tr>";
      $this->salida .= "    <tr>";
      $this->salida .= "        <td><br>";
      $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
      $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
      $this->salida .= "        <td>CONSECUTIVO</td>";
      $this->salida .= "        <td>CARGO</td>";
      $this->salida .= "        <td>PRECIO.</td>";
      $this->salida .= "        <td>CANTIDAD</td>";
      $this->salida .= "        <td>VALOR</td>";
      $this->salida .= "        <td>VAL. NO CUBIERTO</td>";
      $this->salida .= "        <td>VAL. PACIENTE</td>";
      $this->salida .= "        <td>VAL. EMPRESA</td>";
      $this->salida .= "        <td>DETALLE</td>";
      $this->salida .= "    </tr>";
      for($i=0; $i<sizeof($vars); $i++)
      {
      $var=$vars[$i];
      $ValEmpresa=($var[5]-$var[7])-$var[6];
      $ValTotal+=$var[5];
      $TotalNo+=$var[7];
      $TotalCopago+=$var[6];
      $TotalEmpresa+=$ValEmpresa;
      $Cantidad=round($var[4]);
      $TarifarioId=$var[15];
      $Cargo=$var[1];
      if( $i % 2) $estilo='modulo_list_claro';
      else $estilo='modulo_list_oscuro';
      $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
      $this->salida .= "        <td>$var[0]</td>";
      $this->salida .= "        <td>$var[2]</td>";
      $this->salida .= "        <td>".FormatoValor($var[3])."</td>";
      $this->salida .= "        <td>$Cantidad</td>";
      $this->salida .= "        <td>".FormatoValor($var[5])."</td>";
      $this->salida .= "        <td>".FormatoValor($var[7])."</td>";
      $this->salida .= "        <td>".FormatoValor($var[6])."</td>";
      $this->salida .= "        <td>".FormatoValor($ValEmpresa)."</td>";
      $this->salida .= "        <td>";
      if($var[9]){
      $accionHRef=ModuloGetURL('app','Facturacion_Fiscal','user','ResultadosDiagnostico',array('Transaccion'=>$Transaccion,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Cuenta'=>$Cuenta,'Datos'=>$var,'Arr'=>$vars,'Estado'=>$Estado));
      $this->salida .= "         <a href=\"$accionHRef\">VER</a>   ";
      }
      $this->salida .= "        </td>";
      $this->salida .= "    </tr>";
      }
      if( $i % 2) $estilo='modulo_list_claro';
      else $estilo='modulo_list_oscuro';
      $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
      $this->salida .= "        <td colspan=\"4\"><b>TOTALES: </b></td>";
      $this->salida .= "        <td><b>".FormatoValor($ValTotal)."</b></td>";
      $this->salida .= "        <td><b>".FormatoValor($TotalNo)."</b></td>";
      $this->salida .= "        <td><b>".FormatoValor($TotalCopago)."</b></td>";
      $this->salida .= "        <td><b>".FormatoValor($TotalEmpresa)."</b></td>";
      $this->salida .= "        <td></td>";
      $this->salida .= "    </tr>";
      $this->salida .= "  </table><br>";
      $this->salida .= "        </td>";
      $this->salida .= "    </tr>";
      $this->salida .= "  </table><br>";
      $accion=ModuloGetURL('app','Facturacion_Fiscal','user','Facturacion',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
      $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
      $this->salida .= "<p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></p>";
      $this->salida .= "</form>";
      $this->salida .= ThemeCerrarTabla();
      return true;
      } */

    /**
     * Muestra el subdetalle de apoyos diagnosticos de una cuenta.
     * @access private
     * @return boolean
     * @param int numero de la cuenta
     * @param string tipo documento
     * @param int numero documento
     * @param string nivel
     * @param string plan_id
     * @param int numero de la cama
     * @param date fecha de la cuenta
     * @param int ingreso
     * @param int numero de transaccion
     * @param array arreglo con los datos de la cuenta
     */
    function FormaResultadosDiagnostico($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Fecha, $Ingreso, $Transaccion, $Datos, $Estado) {
        $Nombres = $this->CallMetodoExterno('app', 'Autorizacion_Solicitud', 'user', 'BuscarNombresApellidosPaciente', array($Ingreso));
        $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. ' . $Cuenta . ' ' . $Nombres[nombre] . ' TRANSACCION No. ' . $Transaccion . '(Apoyos Diagnosticos)');
        $this->EncabezadoEmpresa();
        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $Fecha));
        $this->salida .= " <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"85%\" align=\"center\"  >";
        $this->salida .= "    <tr class=\"modulo_table_title\">";
        $this->salida .= "        <td>DETALLE DE MEDIOS DIAGNOSTICOS CONSECUTIVO No. $Datos[0]</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"label\"><td><br>";
        $this->salida .= " <table border=\"0\" cellspacing=\"6\" cellpadding=\"6\" width=\"86%\" align=\"center\"  class=\"modulo_table_list\">";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"16%\">RESULTADO: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\">$Datos[9]</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">PROFESIONAL: </td>";
        $this->salida .= "        <td class=\"modulo_list_oscuro\"> Cod. $Datos[10]  &nbsp;&nbsp;$Datos[12]</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">OBSERVACION: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\">$Datos[11]</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "        </td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'DetalleApoyos', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></p>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /*
     * Muestra el detalle de las cirugias de una cuenta.
     * @access private
     * @return boolean
     * @param int numero de cuenta
     * @param string tipo documento
     * @param int numero documento
     * @param string nivel
     * @param string plan_id
     * @param int numero de la cama
     * @param date fecha de la cuenta
     * @param int ingreso
     * @param array arreglo con los datos de la cuenta
     * @param int numero de transaccion
     * @param int total del paciente
     * @param int total no cubierto
     * @param int total de la empresa
     * @param int valor total (cant. x precio)
     */

    function FormaCuentaCirugias($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Ingreso, $var, $Transaccion, $Fecha, $Estado) {
        IncludeLib("tarifario");
        $Nombres = $this->CallMetodoExterno('app', 'Autorizacion_Solicitud', 'user', 'BuscarNombresApellidosPaciente', array($Ingreso));
        $var = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'DetalleCirugia', array('Transaccion' => $Transaccion));
        $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. ' . $Cuenta . ' ' . $Nombres[nombre] . ' TRANSACCION No. ' . $Transaccion . ' (Cirugías)');
        $this->EncabezadoEmpresa();
        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $Fecha));
        $Fecha1 = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'FechaStamp', array($var[0][fecha_cirugia]));
        $Hora = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'HoraStamp', array($var[0][fecha_cirugia]));
        $TotalPaciente = ($TotalNo + $TotalCopago);
        if (!$TotalNo && !$TotalCopago && !$ValTotal) {
            $vars = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'TotalesCirugia', array('Cuenta' => $Cuenta, 'Transaccion' => $Transaccion));
            $TotalNo = $vars[valor_nocubierto];
            $TotalCopago = $vars[valor_cuota_paciente];
            $ValTotal = $vars[valor_cargo];
            $TotalEmpresa = $vars[valor_cubierto] - $TotalCopago;
            $TotalPaciente = ($TotalNo + $TotalCopago);
        }
        $this->salida .= "     <table border=\"0\" width=\"90%\" align=\"center\" >";
        $this->salida .= "            <tr><td><fieldset><legend class=\"field\">DATOS CIRUGIA</legend>";
        $this->salida .= " <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"15%\">No. OPERACION: </td>";
        $this->salida .= "        <td class=\"modulo_list_oscuro\">" . $var[0][operacion] . "</td>";
        $this->salida .= "        <td width=\"15%\" class=\"modulo_table_list_title\">QUIROFANO: </td>";
        $this->salida .= "        <td class=\"modulo_list_oscuro\">" . $var[0][quirofano] . "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">FECHA CIRUGIA: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\">$Fecha1</td>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">HORA CIRUGIA: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\">$Hora</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">DIAGNOSTICO: </td>";
        $this->salida .= "        <td class=\"modulo_list_oscuro\">" . $var[0][diagnostico_nombre] . "</td>";
        $Anestesista = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'GetNombreProfesional', array($var[0][tipo_id_anestesista], $var[0][anestesista]));
        $this->salida .= "        <td class=\"modulo_table_list_title\">ANESTESISTA: </td>";
        $this->salida .= "        <td class=\"modulo_list_oscuro\">" . $Anestesista . "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">AYUDANTE: </td>";
        $Ayudante = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'GetNombreProfesional', array($var[0][tipo_id_ayudate], $var[0][ayudante]));
        $this->salida .= "        <td class=\"modulo_list_claro\">" . $Ayudante . "</td>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">INSTRUMENTISTA: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\">" . $var[0][instrumentista] . "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">CIRCULANTE 1: </td>";
        $Circulante1 = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'GetNombreProfesional', array($var[0][tipo_id_circulante1], $var[0][circulante1]));
        $this->salida .= "        <td class=\"modulo_list_oscuro\">" . $Circulante1 . "</td>";
        $this->salida .= "        <td class=\"modulo_table_list_title\">CIRCULANTE 2: </td>";
        $Circulante2 = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'GetNombreProfesional', array($var[0][tipo_id_circulante2], $var[0][circulante2]));
        $this->salida .= "        <td class=\"modulo_list_oscuro\">" . $Circulante2 . "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>";
        $this->salida .= " <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
        $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "        <td>TOTAL PACIENTE</td>";
        $this->salida .= "        <td>VALOR TOTAL</td>";
        $this->salida .= "        <td>TOTAL NO CUBIERTO</td>";
        $this->salida .= "        <td>TOTAL COPAGO</td>";
        $this->salida .= "        <td>TOTAL EMPRESA</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_oscuro\" align=\"center\">";
        $this->salida .= "        <td>" . FormatoValor($TotalPaciente) . "</td>";
        $this->salida .= "        <td>" . FormatoValor($ValTotal) . "</td>";
        $this->salida .= "        <td>" . FormatoValor($TotalNo) . "</td>";
        $this->salida .= "        <td>" . FormatoValor($TotalCopago) . "</td>";
        $this->salida .= "        <td>" . FormatoValor($TotalEmpresa) . "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "          </fieldset></td></tr></table><BR>";
        if ($var) {
            $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"98%\" align=\"center\"  >";
            $this->salida .= "    <tr class=\"modulo_table_title\">";
            $this->salida .= "        <td>DETALLE DE PROCEDIMIENTOS QUIRURGICOS</td>";
            $this->salida .= "    </tr>";
            for ($i = 0; $i < sizeof($var); $i++) {
                $this->salida .= "    <tr>";
                $this->salida .= "        <td><br>";
                $this->salida .= " <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
                $this->salida .= "    <tr>";
                $this->salida .= "        <td  width=\"15%\" class=\"modulo_table_list_title\">CONSECUTIVO: </td>";
                $this->salida .= "        <td class=\"modulo_list_claro\">" . $var[$i][consecutivo] . "</td>";
                $this->salida .= "        <td  width=\"15%\" class=\"modulo_table_list_title\">VIA: </td>";
                $this->salida .= "        <td class=\"modulo_list_claro\">" . $var[$i][descripcion] . "</td>";
                $this->salida .= "    </tr>";
                $this->salida .= "    <tr>";
                $this->salida .= "        <td width=\"15%\"  class=\"modulo_table_list_title\" width=\"15%\">PROCEDIMIENTO: </td>";
                $this->salida .= "        <td class=\"modulo_list_oscuro\">" . $var[$i][procedimiento] . "</td>";
                $this->salida .= "        <td class=\"modulo_list_oscuro\" colspan=\"2\">" . $var[$i][desc2] . "</td>";
                $this->salida .= "    </tr>";
                if ($var[$i][complicacion]) {
                    $DescripcionC = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'BuscarDiagnsotico', array($var[$i][complicacion]));
                    $this->salida .= "    <tr>";
                    $this->salida .= "        <td width=\"15%\"  class=\"modulo_table_list_title\" width=\"15%\">COMPLICACION: </td>";
                    $this->salida .= "        <td class=\"modulo_list_claro\">" . $var[$i][complicacion] . "</td>";
                    $this->salida .= "        <td class=\"modulo_list_claro\" colspan=\"2\">$DescripcionC</td>";
                    $this->salida .= "    </tr>";
                }
                $this->salida .= "    <tr>";
                $this->salida .= "        <td width=\"15%\"  class=\"modulo_table_list_title\" width=\"15%\">CIRUJANO: </td>";
                $this->salida .= "        <td class=\"modulo_list_oscuro\"  colspan=\"3\">" . $var[$i][nombre] . "</td>";
                $this->salida .= "    </tr>";
                $this->salida .= " </table><br>";
                $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
                $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
                $this->salida .= "        <td>TRANS.</td>";
                $this->salida .= "        <td>CARGO</td>";
                $this->salida .= "        <td>DESC. CARGO</td>";
                $this->salida .= "        <td>PRECIO UNI.</td>";
                $this->salida .= "        <td>CANT.</td>";
                $this->salida .= "        <td>VALOR</td>";
                $this->salida .= "        <td>VAL. NO CUBIERTO</td>";
                $this->salida .= "        <td>COPAGO</td>";
                $this->salida .= "        <td>VAL. EMPRESA</td>";
                $this->salida .= "    </tr>";
                $cant = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'CantidadConsecutivos', array($var[$i][consecutivo]));
                $TotalNo = $TotalCopago = $ValTotal = $TotalEmpresa = 0;
                if ($i % 2)
                    $estilo = 'modulo_list_claro';
                else
                    $estilo = 'modulo_list_oscuro';
                for ($d = 0; $d < $cant; $d++) {
                    if ($i % 2)
                        $estilo = 'modulo_list_claro';
                    else
                        $estilo = 'modulo_list_oscuro';
                    $NomCargo = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'BuscarNombreCargo', array($var[$i][tarifario_id], $var[$i][cargo]));
                    $ValEmpresa = ($var[$i][valor_cubierto] - $var[$i][valor_cuota_paciente]);
                    $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
                    $this->salida .= "        <td>" . $var[$i][transaccion] . "</td>";
                    $this->salida .= "        <td>" . $var[$i][cargo] . "</td>";
                    $this->salida .= "        <td>" . substr($NomCargo[0], 0, 30) . "</td>";
                    $this->salida .= "        <td>" . FormatoValor($var[$i][precio]) . "</td>";
                    $this->salida .= "        <td>" . $var[$i][cantidad] . "</td>";
                    $this->salida .= "        <td>" . FormatoValor($var[$i][valor_cargo]) . "</td>";
                    $this->salida .= "        <td>" . FormatoValor($var[$i][valor_nocubierto]) . "</td>";
                    $this->salida .= "        <td>" . FormatoValor($var[$i][valor_cuota_paciente]) . "</td>";
                    $this->salida .= "        <td>" . FormatoValor($ValEmpresa) . "</td>";
                    $this->salida .= "    </tr>";
                    $TotalEmpresa+=$ValEmpresa;
                    $TotalNo+=$var[$i][valor_nocubierto];
                    $TotalCopago+=$var[$i][valor_cuota_paciente];
                    $ValTotal+=$var[$i][valor_cargo];
                    $i++;
                }
                $i = $i - 1;
                $TotalPaciente = $TotalNo + $TotalCopago;
                if ($i % 2)
                    $estilo = 'modulo_list_claro';
                else
                    $estilo = 'modulo_list_oscuro';
                if ($d == 0)
                    $estilo = 'modulo_list_claro';
                $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
                $this->salida .= "        <td colspan=\"5\"><b>TOTALES: </b></td>";
                $this->salida .= "        <td><b>" . FormatoValor($ValTotal) . "</b></td>";
                $this->salida .= "        <td><b>" . FormatoValor($TotalNo) . "</b></td>";
                $this->salida .= "        <td><b>" . FormatoValor($TotalCopago) . "</b></td>";
                $this->salida .= "        <td><b>" . FormatoValor($TotalEmpresa) . "</b></td>";
                $this->salida .= "    </tr>";
                $this->salida .= "  </table><br>";
                $this->salida .= "        </td>";
                $this->salida .= "    </tr>";
            }
            $this->salida .= "  </table><br>";
        }
        //otros cargos cirugia
        $vars = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'DetalleCirugiaOtros', array($Transaccion));
        if ($vars) {
            $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"98%\" align=\"center\"  >";
            $this->salida .= "    <tr class=\"modulo_table_title\">";
            $this->salida .= "        <td>DETALLE DE OTROS CARGOS QUIRURGICOS</td>";
            $this->salida .= "    </tr>";
            $this->salida .= "    <tr><td><br>";
            $this->salida .= " <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
            $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td>TRANS.</td>";
            $this->salida .= "        <td>CARGO</td>";
            $this->salida .= "        <td>DESC. CARGO</td>";
            $this->salida .= "        <td>PRECIO UNI.</td>";
            $this->salida .= "        <td>CANT.</td>";
            $this->salida .= "        <td>VALOR</td>";
            $this->salida .= "        <td>VAL. NO CUBIERTO</td>";
            $this->salida .= "        <td>COPAGO</td>";
            $this->salida .= "        <td>VAL. EMPRESA</td>";
            $this->salida .= "    </tr>";
            $TotalNo = $TotalCopago = $ValTotal = $TotalEmpresa = 0;
            for ($i = 0; $i < sizeof($vars); $i++) {
                if ($i % 2)
                    $estilo = 'modulo_list_claro';
                else
                    $estilo = 'modulo_list_oscuro';
                //$NomCargo=$this->BuscarNombreCargo($vars[$i][tarifario_id],$vars[$i][cargo]);
                $ValEmpresa = ($vars[$i][valor_cubierto] - $vars[$i][valor_cuota_paciente]);
                $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
                $this->salida .= "        <td>" . $vars[$i][transaccion] . "</td>";
                $this->salida .= "        <td>" . $vars[$i][cargo] . "</td>";
                //$this->salida .= "        <td>".substr($NomCargo[0],0,30)."</td>";
                $this->salida .= "        <td>" . substr($vars[$i][descripcion], 0, 30) . "</td>";
                $this->salida .= "        <td>" . FormatoValor($vars[$i][precio]) . "</td>";
                $this->salida .= "        <td>" . $vars[$i][cantidad] . "</td>";
                $this->salida .= "        <td>" . FormatoValor($vars[$i][valor_cargo]) . "</td>";
                $this->salida .= "        <td>" . FormatoValor($vars[$i][valor_nocubierto]) . "</td>";
                $this->salida .= "        <td>" . FormatoValor($vars[$i][valor_cuota_paciente]) . "</td>";
                $this->salida .= "        <td>" . FormatoValor($ValEmpresa) . "</td>";
                $this->salida .= "    </tr>";
                $TotalEmpresa+=$ValEmpresa;
                $TotalNo+=$vars[$i][valor_nocubierto];
                $TotalCopago+=$vars[$i][valor_cuota_paciente];
                $ValTotal+=$vars[$i][valor_cargo];
            }
            $i = $i - 1;
            $TotalPaciente = $TotalNo + $TotalCopago;
            if ($i % 2)
                $estilo = 'modulo_list_claro';
            else
                $estilo = 'modulo_list_oscuro';
            if ($d == 0)
                $estilo = 'modulo_list_claro';
            $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
            $this->salida .= "        <td colspan=\"5\"><b>TOTALES: </b></td>";
            $this->salida .= "        <td><b>" . FormatoValor($ValTotal) . "</b></td>";
            $this->salida .= "        <td><b>" . FormatoValor($TotalNo) . "</b></td>";
            $this->salida .= "        <td><b>" . FormatoValor($TotalCopago) . "</b></td>";
            $this->salida .= "        <td><b>" . FormatoValor($TotalEmpresa) . "</b></td>";
            $this->salida .= "    </tr>";
            $this->salida .= "  </table><br>";
            $this->salida .= "        </td>";
            $this->salida .= "    </tr>";
            $this->salida .= "  </table><br>";
        }
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></p>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

//---------------------------------------------------------------------------------------
    /**
     *
     */
    function Cambio() {
        $this->salida .= "<SCRIPT>\n";
        $this->salida .= "function Cambio(valor,url){\n";
        $this->salida .= " window.location=url+'&aseguradora=Si&res='+valor;\n";
        $this->salida .= "}\n";
        $this->salida .= "</SCRIPT>\n";
    }

    /**
     *
     */
    //function FormaMetodoBuscar($Busqueda,$mensaje,$arr,$f,$new)
    function FormaMetodoBuscar($arr) {
        IncludeLib("tarifario");
        unset($_SESSION['CUENTAS']);
        $_SESSION['FACTURACION']['CERRADAS'] = false;
        if (!$Busqueda && $_SESSION['FACTURACION']['SWCUENTAS'] != 'Agrupadas') {
            $Busqueda = 1;
            $por = '98%';
        } elseif (!$Busqueda && $_SESSION['FACTURACION']['SWCUENTAS'] == 'Agrupadas') {
            $Busqueda = 5;
            $por = '60%';
        }
        if ($_SESSION['FACTURACION']['SWCUENTAS'] == 'Agrupadas') {
            $por = '60%';
        } else {
            $por = '98%';
        }
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarCuenta');
        $Empresa = $this->Empresa;
        $CU = $this->CentroUtilidad;
        $this->salida .= "";
        $this->Cambio();
        $this->salida .= ThemeAbrirTabla('BUSCAR CUENTA');
        $this->EncabezadoEmpresa();

        $this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
        $this->salida .= "</tr>";
        $this->salida .= "<tr class=\"modulo_list_claro\" >";
        $this->salida .= "<td width=\"40%\" >";
        $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr><td>";
        $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
        $this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
        $tipo_id = $this->tipo_id_paciente();
        $this->BuscarIdPaciente($tipo_id, '');
        $this->salida .= "</select></td>";
        $this->salida .= "<td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
        $this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\"></td>";
        $this->salida .= "<td class=\"label\">No. CUENTA: </td><td><input type=\"text\" class=\"input-text\" name=\"Cuenta\" maxlength=\"32\"></td></tr>";
        $this->salida .= "<tr><td class=\"label\">No. INGRESO: </td><td><input type=\"text\" class=\"input-text\" name=\"Ingreso\" maxlength=\"32\"></td>";
        //$this->salida .= "<tr><td class=\"".$this->SetStyle("Pieza")."\">No. PIEZA</td><td><input type=\"text\" class=\"input-text\" name=\"Pieza\" maxlength=\"32\"></td></tr>";
        //$this->salida .= "<tr><td class=\"".$this->SetStyle("Cama")."\">No. CAMA</td><td><input type=\"text\" class=\"input-text\" name=\"Cama\" maxlength=\"32\"></td></tr>";
        //$this->salida .= "<tr><td class=\"label\">PREFIJO</td><td><input type=\"text\" class=\"input-text\" name=\"prefijo\" maxlength=\"32\"></td></tr>";
        //$this->salida .= "<tr><td class=\"".$this->SetStyle("Historia")."\">NUMERO HISTORIA</td><td><input type=\"text\" class=\"input-text\" name=\"historia\" maxlength=\"32\"></td></tr>";
        $this->salida .= "<td class=\"label\">DEPARTAMENTO: </td><td><select name=\"Departamento\" class=\"select\">";
        $departamento = $this->Departamentos();
        $this->BuscarDepartamento($departamento, $d, $Dpto);
        $this->salida .= "                  </select></td></tr>";
        $this->salida .= "<tr><td colspan = 4 align=\"center\" >";
        $this->salida .= "<tr><td align=\"right\" colspan = 2  ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSQUEDA\"></td>";
        $this->salida .= "</form>";
        $actionM = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Menu');
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\" colspan = 2 ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= "</td></tr></table>";
        $this->salida .= "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "</table>";
        /* $this->salida .= "       </td>";
          $this->salida .= "    </tr>";
          $this->salida .= "  </table>"; */
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        if ($arr) {
            $this->salida .= "<br><table width=\"98%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td>No. CUENTA</td>";
            $this->salida .= "        <td>IDENTIFICACION</td>";
            $this->salida .= "        <td>PACIENTE</td>";
            $this->salida .= "        <td>RESPONSABLE</td>";
            $this->salida .= "        <td>PLAN</td>";
            $this->salida .= "        <td>NIVEL</td>";
            $this->salida .= "        <td>FECHA APERTURA</td>";
            $this->salida .= "        <td>HORA APERTURA</td>";
            $this->salida .= "        <td>TOTAL CUENTA</td>";
            //$this->salida .= "        <td>SALDO PACIENTE</td>";
            $this->salida .= "        <td>E</td>";
            $this->salida .= "        <td></td>";
            $this->salida .= "      </tr>";
            for ($i = 0; $i < sizeof($arr); $i++) {
                $Cuenta = $arr[$i][numerodecuenta];
                $PlanId = $arr[$i][plan_id];
                $Fecha = $arr[$i][fecha_registro];
                $Total = $arr[$i][total_cuenta];
                $ValorNo = $arr[$i][valor_nocubierto];
                $TipoId = $arr[$i][tipo_id_paciente];
                $PacienteId = $arr[$i][paciente_id];
                $Nombre = $arr[$i][nombre];
                $Nivel = $arr[$i][rango];
                $Estado = $arr[$i][estado];
                $Ingreso = $arr[$i][ingreso];
                $datos = $this->CallMetodoExterno('app', 'Triage', 'user', 'BuscarPlanes', array('PlanId' => $PlanId, 'Ingreso' => $Ingreso));
                $Fechas = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'FechaStamp', array($Fecha));
                $Horas = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'HoraStamp', array($Fecha));
                $accionHRef = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado, 'Nivel' => $Nivel, 'arreglo' => $arr[$i]));
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td align=\"center\">$Cuenta</td>";
                $this->salida .= "        <td>$TipoId $PacienteId</td>";
                $this->salida .= "        <td>$Nombre</td>";
                $this->salida .= "        <td align=\"center\">" . $datos[nombre_tercero] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $datos[plan_descripcion] . "</td>";
                $this->salida .= "        <td align=\"center\">$Nivel</td>";
                $this->salida .= "        <td align=\"center\">$Fechas</td>";
                $this->salida .= "        <td align=\"center\">$Horas</td>";
                $this->salida .= "        <td align=\"center\">" . FormatoValor($Total) . "</td>";
                //$this->salida .= "        <td align=\"center\">".FormatoValor($arr[$i][saldo])."</td>";
                $this->salida .= "        <td align=\"center\">" . $Estado . "</td>";
                $this->salida .= "        <td align=\"center\"><a href=\"$accionHRef\">VER</a></td>";
                $this->salida .= "      </tr>";
            }//fin for
            $this->salida .= " </table><br>";
            $this->conteo = $_SESSION['SPY'];
            $this->salida .=$this->RetornarBarraC();
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     * Se utilizada listar en el combo los diferentes tipo de identifiacion de los pacientes
     * @access private
     * @return void
     */
    function BuscarIdPaciente($tipo_id, $TipoId='') {
        $s = "";
        foreach ($tipo_id as $value => $titulo) {
            ($value == $TipoId) ? $s = "selected" : $s = "";
            $this->salida .=" <option value=\"$value\" $s >$titulo</option>\n";
        }
    }

    /**
     * Se utilizada listar en el combo los diferentes tipo de departamentos de la clinica.
     * @access private
     * @return void
     */
    function BuscarDepartamento($departamento, $d=false, $Dpto) {
        if (!$d) {
            $this->salida .=" <option value=\"-1\" selected>--TODOS--</option>";
        }
        foreach ($departamento as $value => $titulo) {
            if ($value == $Dpto) {
                $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
            } else {
                $this->salida .=" <option value=\"$value\" >$titulo</option>";
            }
        }
    }

    /**
     * Muestra el nombre del tercero con sus respectivos planes
     * @access private
     * @return string
     * @param array arreglor con los tipos de responsable
     * @param int el responsable que viene por defecto
     */
    function MostrarResponsable($responsables, $Responsable) {
        $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
        for ($i = 0; $i < sizeof($responsables); $i++) {
            /* if($_SESSION['FACTURACION']['aseguradora']=='Si')
              {
              if($responsables[$i][plan_id]=='SOAT')
              {  $this->salida .=" <option value=\"".$responsables[$i][plan_id]."\" selected>".$responsables[$i][plan_descripcion]."</option>"; }
              else
              {  $this->salida .=" <option value=\"".$responsables[$i][plan_id]."\">".$responsables[$i][plan_descripcion]."</option>"; }
              }
              else
              { */
            if ($responsables[$i][plan_id] == $Responsable) {
                $this->salida .=" <option value=\"" . $responsables[$i][plan_id] . "\" selected>" . $responsables[$i][plan_descripcion] . "</option>";
            } else {
                $this->salida .=" <option value=\"" . $responsables[$i][plan_id] . "\">" . $responsables[$i][plan_descripcion] . "</option>";
            }
            //  }
        }
    }

    /**
     *
     */
    function MostrarAseguradoras($var) {
        $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
        for ($i = 0; $i < sizeof($var); $i++) {
            $this->salida .=" <option value=\"" . $var[$i][tipo_id_tercero] . "," . $var[$i][tercero_id] . "\">" . $var[$i][nombre_tercero] . "</option>";
        }
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
    function FormaMensaje($mensaje, $titulo, $accion, $boton, $botonC, $arreglo) {
        //factura detalleda
        IncludeLib('funciones_facturacion');

        $Cuenta = $arreglo['cuenta'];
        if ($botonC == 'facturapaciente') {
            $RUTA = $_ROOT . "cache/facturapaciente" . $Cuenta . $arreglo[prefijo] . $arreglo[numero] . ".pdf";
        } else {
            $RUTA = $_ROOT . "cache/factura" . $Cuenta . ".pdf";
        }
        $mostrar = "<script>\n";
        $mostrar.= "	var rem=\"\";\n";
        $mostrar.= "  	function abreVentana()\n";
        $mostrar.= "  	{\n";
        $mostrar.= "    	var nombre = \"\"\n";
        $mostrar.= "    	var url2 = \"\"\n";
        $mostrar.= "    	var str = \"\"\n";
        $mostrar.= "    	var alto = screen.height\n";
        $mostrar.= "    	var ancho = screen.width\n";
        $mostrar.= "    	var nombre = \"REPORTE\";\n";
        $mostrar.= "    	var str = \"ancho,alto,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.= "    	var url2 = '$RUTA';\n";
        $mostrar.= "    	rem = window.open(url2, nombre, str);\n";
        $mostrar.= "    };\n";

        //factura conceptos
        $RUTA = $_ROOT . "cache/facturaconceptos" . $Cuenta . ".pdf";
        $mostrar.="		var rem=\"\";\n";
        $mostrar.="  	function abreVentana2()\n";
        $mostrar.="  	{\n";
        $mostrar.="			var nombre=\"\"\n";
        $mostrar.="			var url2=\"\"\n";
        $mostrar.="    		var str=\"\"\n";
        $mostrar.="    		var ALTO=screen.height\n";
        $mostrar.="    		var ANCHO=screen.width\n";
        $mostrar.="   		var nombre=\"REPORTE\";\n";
        $mostrar.="    		var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    		var url2 ='$RUTA';\n";
        $mostrar.="    		rem = window.open(url2, nombre, str);\n";
        $mostrar.="  	};\n";

        $RUTA = $_ROOT . "cache/hojatransaccion.pdf";
        $mostrar.="		var rem=\"\";\n";
        $mostrar.="  	function abreVentanaHT()\n";
        $mostrar.="  	{\n";
        $mostrar.="    		var nombre=\"\"\n";
        $mostrar.="    		var url2=\"\"\n";
        $mostrar.="    		var str=\"\"\n";
        $mostrar.="    		var ALTO=screen.height\n";
        $mostrar.="    		var ANCHO=screen.width\n";
        $mostrar.="    		var nombre=\"REPORTE\";\n";
        $mostrar.="    		var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    		var url2 ='$RUTA';\n";
        $mostrar.="    		rem = window.open(url2, nombre, str);\n";
        $mostrar.="  	}\n";

        //CUENTA COBRO	
        $RUTA1 = $_ROOT . "cache/cuentacobro" . $Cuenta . ".pdf";
        $mostrar.="  function abreVentanaCC(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA1';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        $mostrar.="</script>\n";
        $this->salida.="$mostrar";
        if ($botonC) {
            $this->salida .= ThemeAbrirTabla($titulo, "50%") . "<br>";
            $this->salida .= "<table width=\"68%\" align=\"center\" class=\"normal_10\" border='0'>\n";
            $this->salida .= "    <form name=\"formaMensaje\" action=\"$accion\" method=\"post\">\n";
            $this->salida .= "        <tr><td colspan=\"4\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>\n";
            if (!empty($boton)) {
                $this->salida .= "    <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"<<$boton\"></td>\n";
            } else {
                $this->salida .= "    <tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr>\n";
            }
            $this->salida .= "    </form>\n";

            //CASO CUENTAS DE COBRO
            if ($botonC == 'cuentacobro') {
                IncludeLib("reportes/cuentacobro");
                GenerarCuentaCobro(array('PlanId' => $arreglo['PlanId'], 'Fecha' => $arreglo['Fecha'],
                    'Ingreso' => $arreglo['Ingreso'], 'numero' => $arreglo['numero'],
                    'prefijo' => $arreglo['prefijo'], 'empresa' => $arreglo['empresa'],
                    'tipo_factura' => $arreglo['tipo_factura'], 'cuenta' => $arreglo['cuenta']));
                $this->salida .= "<td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"Imprimir PDF\" onclick=\"javascript:abreVentanaCC()\"></td>";
            }
            //
            if ($botonC == 'reportes') {
                $reporte = explode('/', $arreglo['ruta_hoja']);
                $RUTA = $_ROOT . "cache/" . $reporte[1] . $Cuenta . ".pdf";

                $mostrar = "<script>\n";
                $mostrar.=" var rem=\"\";\n";
                $mostrar.=" function abreVentanaHC()\n";
                $mostrar.=" {\n";
                $mostrar.="   var nombre=\"\"\n";
                $mostrar.="   var url2=\"\"\n";
                $mostrar.="   var str=\"\"\n";
                $mostrar.="   var ALTO=screen.height\n";
                $mostrar.="   var ANCHO=screen.width\n";
                $mostrar.="   var nombre=\"REPORTE\";\n";
                $mostrar.="   var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
                $mostrar.="   var url2 ='$RUTA';\n";
                $mostrar.="   rem = window.open(url2, nombre, str);\n";
                $mostrar.=" }\n";
                $mostrar.="</script>\n";
                $this->salida.="$mostrar";
                IncludeLib($arreglo['ruta_hoja']);
                $funcion = 'Generar' . $reporte[1];
                $funcion(array('numerodecuenta' => $arreglo['cuenta'], 'prefijo' => $arreglo['prefijo'], 'factura_fiscal' => $arreglo['factura_fiscal']));
                $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"Vista Preliminar\" onclick=\"javascript:abreVentanaHC()\"></td>";
            }
            //RECIBO DE CAJA
            if ($botonC == 'recibo_caja') {
                $RUTA = $_ROOT . "cache/Recibo" . UserGetUID() . ".pdf";
                $mostrar = "<script>\n";
                $mostrar.=" var rem=\"\";\n";
                $mostrar.=" function abreVentanaRC()\n";
                $mostrar.=" {\n";
                $mostrar.= "    	var nombre = \"\"\n";
                $mostrar.= "    	var url2 = \"\"\n";
                $mostrar.= "    	var str = \"\"\n";
                $mostrar.= "    	var alto = screen.height\n";
                $mostrar.= "    	var ancho = screen.width\n";
                $mostrar.= "    	var nombre = \"REPORTE\";\n";
                $mostrar.= "    	var str = \"ancho,alto,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
                $mostrar.= "    	var url2 = '$RUTA';\n";
                $mostrar.= "    	rem = window.open(url2, nombre, str);\n";
                $mostrar.=" }\n";
                $mostrar.="</script>\n";
                $this->salida.="$mostrar";
                $numero_cuenta = explode(",'TipoId'", $_SESSION['CUENTAS']['RETORNO']['argumentos']);
                $numero_cuenta1 = explode("'cuenta' =", $numero_cuenta[0]);
                $var = $this->InfoRecibosCaja($arreglo);
                IncludeLib("reportes/recibo_caja");
                GenerarReciboCaja($var);
                $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"Vista Preliminar\" onclick=\"javascript:abreVentanaRC()\"></td>";
            }
            //RECIBO DE DEVOLUCION
            if ($botonC == 'recibo_devolucion') {
                $RUTA = $_ROOT . "cache/Recibo" . UserGetUID() . ".pdf";
                $mostrar = "<script>\n";
                $mostrar.=" var rem=\"\";\n";
                $mostrar.=" function abreVentanaRD()\n";
                $mostrar.=" {\n";
                $mostrar.= "    	var nombre = \"\"\n";
                $mostrar.= "    	var url2 = \"\"\n";
                $mostrar.= "    	var str = \"\"\n";
                $mostrar.= "    	var alto = screen.height\n";
                $mostrar.= "    	var ancho = screen.width\n";
                $mostrar.= "    	var nombre = \"REPORTE\";\n";
                $mostrar.= "    	var str = \"ancho,alto,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
                $mostrar.= "    	var url2 = '$RUTA';\n";
                $mostrar.= "    	rem = window.open(url2, nombre, str);\n";
                $mostrar.=" }\n";
                $mostrar.="</script>\n";
                $this->salida.="$mostrar";
                
                $numero_cuenta = explode(",'TipoId'", $_SESSION['CUENTAS']['RETORNO']['argumentos']);
                $numero_cuenta1 = explode("'cuenta' =", $numero_cuenta[0]);
                $var = $this->InfoRecibosDevolucion($arreglo);
                //var_dump($var);
                IncludeLib("reportes/recibo_caja");
                GenerarReciboDevolucion($var);
                $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"Vista Preliminar\" onclick=\"javascript:abreVentanaRD()\"></td>";
            }
//          JONIER
            if ($botonC == 'factura') {
                //IncludeLib("reportes/factura");
                $var = $this->DatosFactura($arreglo['cuenta'], $arreglo['plan_id'], $arreglo['tipoid'], $arreglo['pacienteid']);
                $var['facturas'] = $this->facturas;
                $ruta = EncontrarFormatoFactura($_SESSION['FACTURACION']['EMPRESA'], $arreglo['plan_id'], $botonC, 'cliente');
                IncludeLib($ruta);
                GenerarFactura($var);
                $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"IMPRIMIR FACTURA\" onclick=\"javascript:abreVentana()\"></td>";
            }
            if ($botonC == 'facturapaciente') {
                $var = $this->DatosFactura($arreglo['cuenta']);
                $ruta = EncontrarFormatoFactura($_SESSION['FACTURACION']['EMPRESA'], $arreglo['plan_id'], 'factura', 'paciente');
                IncludeLib($ruta);
                GenerarFacturaPaciente($var, $swTipoFactura = 1);
                $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA PACIENTE\" onclick=\"javascript:abreVentana()\"></td>";
            }
            if ($botonC == 'resumen') {
                $var = $this->DatosResumenFactura($arreglo['cuenta'], $arreglo['plan_id'], $arreglo['tipoid'], $arreglo['pacienteid']);
                $var['facturas'] = $this->facturas;
                //IncludeLib("reportes/factura");
                $ruta = EncontrarFormatoFactura($_SESSION['FACTURACION']['EMPRESA'], $arreglo['plan_id'], $botonC);
                IncludeLib($ruta);
                GenerarFactura($var);
                $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"RESUMEN FACTURA\" onclick=\"javascript:abreVentana()\"></td>";
            }
            if ($botonC == 'conceptos') {
                $var = $this->DatosFactura($arreglo['cuenta'], $arreglo['plan_id'], $arreglo['tipoid'], $arreglo['pacienteid'], $arreglo['prnAnuladas']);
                $var['facturaAnulada'] = $arreglo['prnAnuladas'];
                $ruta = EncontrarFormatoFactura($_SESSION['FACTURACION']['EMPRESA'], $arreglo['plan_id'], $botonC);
                IncludeLib($ruta);
                GenerarFacturaConceptos($var);
                if ($arreglo['prnAnuladas']) {
                    $value = "value=\"Vista Previa\"";
                } else {
                    $value = "value=\"FACTURA CONCEPTOS\"";
                }
                $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" $value onclick=\"javascript:abreVentana2()\"></td>";
            }
            $this->salida .= "</table>\n";
            $this->salida .= themeCerrarTabla();
        } else {
            $this->salida .= ThemeAbrirTabla($titulo);
            $this->salida .= "            <table width=\"60%\" align=\"center\" >";
            $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "               <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
            $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
            $this->salida .= "           </form>";
            $this->salida .= "           </table>";
            $this->salida .= ThemeCerrarTabla();
        }
        return true;
    }

    /**
     * Forma de confirmacin para cuando los rips de un envio son generados
     * Muestra las opciones de ACEPTAR Y GENERAR RIPS el ACEPTAR lo retorna  a la pagina
     * anterior y GENERAR RIPS descarga los RIPS generados
     *
     * @param string mensaje(Mensaje de confirmaci�)
     * @param string accion(Url a donde tiene que ir la forma cuando dan ACEPTAR)
     * @param string downloadRips(Boton(HTML) para descargar los RIPS)
     */
    function FormaRipsGenerados($mensaje, $accion, $downloadRips) {
        $this->salida .= ThemeAbrirTabla("RIPS");
        $this->salida .= "            <table width=\"60%\" align=\"center\" >";
        $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "               <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr>";
        $this->salida .= "               <tr>
														<td align=\"right\" width=\"50%\">
															<input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\">
														</td>";
        $this->salida .= "           </form>";
        $this->salida .= "               	<td align=\"left\" width=\"50%\">";
        $this->salida .= $downloadRips;
        $this->salida .= "						</td>
													</tr>";

        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

//-----------------------radicacion---------------------------------------
    /**
     *
     */
    function FormaBuscarRad($arr) {
        UNSET($_SESSION['FACTURACION']['datos_periodo_soat']);
        $this->salida.= ThemeAbrirTabla('BUSQUEDA DE ENVIOS');
        $this->Todos();
        IncludeLib("tarifario");
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarEnviosRad', array('centro_utilidad' => $_REQUEST['centro_utilidad'], 'empresa_id' => $_REQUEST['empresa_id']));
        $this->EncabezadoEmpresa();
        $this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
        //$this->salida .= "<td align = left >SELECCIONE LA FECHA:</td>";
        
        $this->salida .= "</tr>";
        $this->salida .= "<tr class=\"modulo_list_claro\" >";
        $this->salida .= "<td width=\"40%\" >";
        $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr><td>";
        $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
        $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
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
        $this->salida .= "               <tr><td class=\"" . $this->SetStyle("Responsable") . "\">RESPONSABLE: </td><td><select name=\"Responsable\" class=\"select\">";
        $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
        $responsables = $this->TercerosPlanes();
        for ($i = 0; $i < sizeof($responsables); $i++) {
            $this->salida .=" <option value=\"" . $responsables[$i][tipo_tercero_id] . "," . $responsables[$i][tercero_id] . "," . $responsables[$i][nombre_tercero] . "\">" . $responsables[$i][nombre_tercero] . "</option>";
        } $this->salida .= "</select></td></tr>";
        $this->salida .= "                <tr>";
        $this->salida .= "                    <td class=\"" . $this->SetStyle("envio") . "\">ENVIO No. </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"envio\"></td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $this->salida .= "                    <td class=\"" . $this->SetStyle("prefijo") . "\">PREFIJO No. </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"prefijo\"></td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $this->salida .= "                    <td class=\"" . $this->SetStyle("factura") . "\">FACTURA No. </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"factura\"></td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $i = $_REQUEST['FechaI'];
        if ($arr == 'si' OR !empty($arr)) {
            $i = '';
        }
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaI") . "\">DESDE: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaI\" value=\"" . $i . "\">" . ReturnOpenCalendario('forma', 'FechaI', '/') . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $fi = $_REQUEST['FechaF'];
        if ($arr == 'si' OR !empty($arr)) {
            $fi = '';
        }
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaF") . "\">HASTA: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaF\" value=\"" . $fi . "\">" . ReturnOpenCalendario('forma', 'FechaF', '/') . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "<tr><td class=\"label\">DEPARTAMENTO: </td><td><select name=\"Dpto\" class=\"select\">";
        $departamento = $this->Departamentos();
        $this->BuscarDepartamento($departamento, $d, '');
        $this->salida .= "</select></td></tr>";
        $this->salida .= "<tr class=\"label\">";
        $this->salida .= "</tr>";
        $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
        $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSQUEDA\"></td>";
        $this->salida .= "</form>";
        $actionM = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaMenus');  //}
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table></td></tr>";
        $this->salida .= "</td></tr></table>";
        $this->salida .= "</table>";
        $this->salida .= "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>";
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        if (!empty($arr) and $arr[0] != 'si') {
            $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td width=\"5%\">ENVIO</td>";
            $this->salida .= "        <td width=\"10%\">DESDE</td>";
            $this->salida .= "        <td width=\"10%\">HASTA</td>";
            $this->salida .= "        <td width=\"30%\">RESPONSABLE</td>";
            $this->salida .= "        <td width=\"10%\">RADICACION</td>";
            $this->salida .= "        <td width=\"30%\">USUARIO</td>";
            $this->salida .= "        <td width=\"5%\">OPCIONES</td>";
            $this->salida .= "      </tr>";
            $reporte = new GetReports();

            $tmp_arr = $arr['print'];
            $tipo_reporte = $this->ConsultaTipoReporte($tmp_arr, 'ENVIO_FACTURA');
            unset($arr['print']);

            for ($i = 0; $i < sizeof($arr); $i++) {
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td align=\"center\">" . $arr[$i][envio_id] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $this->FechaStamp($arr[$i][fecha_inicial]) . "</td>";
                $this->salida .= "        <td align=\"center\">" . $this->FechaStamp($arr[$i][fecha_final]) . "</td>";
                $this->salida .= "        <td align=\"left\">" . $arr[$i][nombre_tercero] . "</td>";
                if (!empty($arr[$i][fecha_radicacion])) {
                    $rad = $this->FechaStamp($arr[$i][fecha_radicacion]);
                } else {
                    $rad = '';
                }
                $this->salida .= "        <td align=\"center\">" . $rad . "</td>";
                $this->salida .= "        <td align=\"left\">" . $arr[$i][nombre] . "</td>";
                $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'DetalleEnvio', array('envio' => $arr[$i][envio_id]));
                $this->salida .= "        <td align=\"center\">";
                $this->salida .= "          <table align=\"center\">";
                $this->salida .= "            <tr class=\"$estilo\">";
                $this->salida .= "              <td align=\"center\">";
                $this->salida .= "                <a href=\"$accion\" title=\"Ver e Imprimir Envio\"><img src=\"" . GetThemePath() . "/images/pconsultar.png\" border=\"0\"></a>";
                $this->salida .= "              </td>";
                $mostrar = $reporte->GetJavaReport('app', 'Facturacion_Fiscal', 'enviosHTM', array('envio' => $arr[$i][envio_id], 'tipo_reporte' => $tipo_reporte), array('rpt_name' => 'envio', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                $funcion = $reporte->GetJavaFunction();
                $this->salida .= $mostrar;
                $accionDescargarRips = ModuloGetURL("app", "Facturacion_Fiscal", "user", "DescargarRipsEnvio", array("tiporips" => "Envio", "EnvioRips" => $arr[$i][envio_id] . "/" . $_SESSION['FACTURACION']['EMPRESA'], "download" => "1"));
                /*              $this->salida .= "              <td width=\"50%\" align=\"center\">";
                  $this->salida .= "                <a href=\"javascript:$funcion\" title=\"Imprimir Envio\"><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\"></a>";
                  $this->salida .= "              </td>"; */
                $this->salida .= "              <form name=\"frmDescargarRips" . $arr[$i][envio_id] . "\" action=\"$accionDescargarRips\" method=\"post\" target=\"_blank\">";
                $this->salida .= "               <td width=\"50%\" align=\"center\">";
                $this->salida .= "                 <input type=\"image\" src=\"" . GetThemePath() . "/images/abajo.png\" name=\"btnDescargarRips\" value=\"DESCARGAR RIPS\"  class=\"input-submit\" title=\"Descargar Rips\">";
                $this->salida .= "               </td>";
                $this->salida .= "              </form>";
                if ($arr[$i][sw_tipo_plan] == '1') {
                    $accionDescargarRipsSoat = ModuloGetURL("app", "Facturacion_Fiscal", "user", "DescargarRipsEnvio", array("tiporips" => "Envio", "EnvioRips" => $arr[$i][envio_id] . "/" . $_SESSION['FACTURACION']['EMPRESA'], "download" => "1", 'ripsSoat' => 'RipsEnviosSoat'));
                    $this->salida .= "              <form name=\"frmDescargarRipsSoat" . $arr[$i][envio_id] . "\" action=\"$accionDescargarRipsSoat\" method=\"post\" target=\"_blank\">";
                    $this->salida .= "              <td width=\"50%\" align=\"center\">";
                    $this->salida .= "                 <input type=\"image\" src=\"" . GetThemePath() . "/images/uf.png\" name=\"btnDescargarRipsSoat\" value=\"DESCARGAR RIPS SOAT\"  class=\"input-submit\" title=\"Descargar Rips Soat\">";
                    $this->salida .= "              </td>";
                    $this->salida .= "              </form>";
                    $accionVerReportesSoat = ModuloGetURL("app", "Facturacion_Fiscal", "user", "FormaDatosInformeSoat", array("EnvioRips" => $arr[$i][envio_id] . "/" . $_SESSION['FACTURACION']['EMPRESA']));
                    $this->salida .= "              <td width=\"50%\" align=\"center\">";
                    $this->salida .= "                 <a href=\"$accionVerReportesSoat\"><img src=\"" . GetThemePath() . "/images/show.gif\" border=\"0\"></a>";
                    $this->salida .= "              </td>";
                    $accionDescargarFuRips = ModuloGetURL("app", "Facturacion_Fiscal", "user", "DescargarRipsEnvio", array("tiporips" => "Envio", "EnvioRips" => $arr[$i][envio_id] . "/" . $_SESSION['FACTURACION']['EMPRESA'], "download" => "1", 'furips' => '1'));
                    $this->salida .= "              <form name=\"frmDescargarRipsSoat" . $arr[$i][envio_id] . "\" action=\"$accionDescargarFuRips\" method=\"post\" target=\"_blank\">";
                    $this->salida .= "              <td width=\"50%\" align=\"center\">";
                    $this->salida .= "                 <input type=\"image\" src=\"" . GetThemePath() . "/images/pcargos.png\" name=\"btnDescargarFuRips\" value=\"DESCARGAR FURIPS\"  class=\"input-submit\" title=\"Descargar FuRips\">";
                    $this->salida .= "              </td>";
                    $this->salida .= "              </form>";
                }
                $this->salida .= "            </tr>";
                $this->salida .= "          </table>";
                $this->salida .= "        </td>";
                $this->salida .= "      </tr>";
            }

            $this->salida .= "  </table>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     * Se encarga de separar la fecha del formato timestamp
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
        }
    }

    /**
     *
     */
    function FormaDetalleEnvio($_REQUEST) {
        $arr = $_SESSION['DETALLE']['ENVIO'];
        $tmp_arr = $arr['print'];
        unset($arr['print']);

        $fct = new app_Facturacion_Permisos();
        $menu = $fct->permisos_opcionesManejoEnvios(SessionGetVar("EmpresaFacturacion"));

        $this->salida.= ThemeAbrirTabla('DETALLE ENVIO No. ' . $arr[0][envio_id]);
        IncludeLib("tarifario");
        IncludeLib("funciones_admision");
        $this->EncabezadoEmpresa();

        $this->salida .= "<br><table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table><br>";
        $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td align=\"center\">ENVIO No. " . $arr[0][envio_id] . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td align=\"center\">" . $arr[0][nombre_tercero] . "   " . $arr[0][tipo_tercero_id] . " " . $arr[0][tercero_id] . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td align=\"center\">DEBE A:</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td align=\"center\">" . $this->NombreEmpresa($arr[0][empresa_id]) . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td align=\"center\">POR SERVICIOS PRESTADOS EN:</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        IF (empty($arr[0][departamento])) {
            $dpto = 'TODOS';
        } else {
            $dpto = $this->NombreDpto($arr[0][departamento]);
        }
        $this->salida .= "        <td align=\"center\"> - " . $dpto . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "        <td width=\"15%\">FACTURA</td>";
        $this->salida .= "        <td width=\"20%\">VALOR</td>";
        $this->salida .= "        <td width=\"15%\">IDENTIFICACION</td>";
        $this->salida .= "        <td width=\"25%\">PACIENTE</td>";
        $this->salida .= "        <td width=\"20%\">PLAN</td>";
        $this->salida .= "      </tr>";
        $total = 0;
        //$arreglo='';
        $j = 0;
        for ($i = 0; $i < sizeof($arr);) {
            if ($j % 2) {
                $estilo = 'modulo_list_claro';
            } else {
                $estilo = 'modulo_list_oscuro';
            }
            $total+=$arr[$i][total_factura];
            //$arreglo[$arr[$i][prefijo]][$arr[$i][factura_fiscal]]=$arr[$i][prefijo];
            $this->salida .= "      <tr class=\"$estilo\">";
            $this->salida .= "        <td align=\"center\">" . $arr[$i][prefijo] . " " . $arr[$i][factura_fiscal] . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($arr[$i][total_factura]) . "</td>";
            $x = 0;
            $d = $i;
            while ($arr[$i][prefijo] == $arr[$d][prefijo] AND $arr[$i][factura_fiscal] == $arr[$d][factura_fiscal]) {
                $x++;
                $d++;
            }
            if ($x > 1) {
                $this->salida .= "        <td></td>";
                $this->salida .= "        <td>AGRUPADA</td>";
            } else {
                $pac = '';
                $pac = BuscarDatosPacienteIngreso($arr[$i][ingreso]);
                $this->salida .= "        <td>" . $pac[tipo_id_paciente] . " " . $pac[paciente_id] . "</td>";
                $this->salida .= "        <td>" . $pac[nombre] . "</td>";
            }
            $this->salida .= "        <td align=\"center\">" . $arr[$i][plan_descripcion] . "</td>";
            $i = $d;
            $j++;
            $this->salida .= "      </tr>";
        }
        $this->salida .= "<tr class=\"modulo_list_claro\">";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'EditarFecha', array('envio_id' => $arr[0][envio_id]));
        $arr[0][fecha_registro] = date("Y-m-d", strtotime($arr[0][fecha_registro]));
        $dat = explode('-', $arr[0][fecha_registro]);
        $_REQUEST['FechaRegistro'] = $dat[2] . '/' . $dat[1] . '/' . $dat[0];
        $this->salida .= "    <form name=\"formaeditar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <td width=\"80%\" align=\"left\" colspan=\"3\"><label class='label_mark'>FECHA REGISTRO:</label>&nbsp;&nbsp;<input type=\"text\" class=\"input-text\" name=\"FechaRegistro\" size=\"12\" value=\"" . $_REQUEST['FechaRegistro'] . "\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">&nbsp;&nbsp;" . ReturnOpenCalendario('formaeditar', 'FechaRegistro', '/') . "&nbsp;&nbsp;&nbsp;<input class=\"input-submit\" type=\"submit\" name=\"editar\" value=\"Guardar\"></td>";
        $this->salida .= "  </form>";
        $this->salida .= "  <td width=\"20%\" align=\"left\" colspan=\"2\"><label class='label_mark'>% Descuento:&nbsp;</label>" . $arr[0][porcentaje_descuento] . "</td>";
        $this->salida .= "</tr>";
        $this->salida .= "  </table>";
        $this->salida .= "     <br><table width=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"left\" class=\"normal_10\">";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td width=\"25%\"></td>";
        $this->salida .= "        <td width=\"35%\">TOTAL DOCUMENTOS: </td>";
        //$this->salida .= "        <td>".sizeof($arreglo)."</td>";
        $this->salida .= "        <td>" . $j . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td width=\"25%\"></td>";
        $this->salida .= "        <td width=\"35%\">TOTAL ENVIO ($): </td>";
        $this->salida .= "        <td>" . FormatoValor($total) . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "  </table><BR>";
        $this->salida .= "  <p><br></p>";
        $this->salida .= "     <table width=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
        $this->salida .= "      <tr>";
        if ($arr[0][sw_estado] == 0) {
            $_SESSION['ENVIOS']['ADICIONAR'] = $_SESSION['DETALLE']['ENVIO'][0]['tipo_tercero_id'] . ',' . $_SESSION['DETALLE']['ENVIO'][0]['tercero_id'] . ',' . $_SESSION['DETALLE']['ENVIO'][0]['nombre_tercero'];
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaModificarFacturasEnvios');
            $this->salida .= "    <form name=\"formaborrar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MODIFICAR FACTURAS\"></td>";
            $this->salida .= "  </form>";
        }

        if ($arr[0][sw_estado] == 3 && $menu['sw_op_despacho'] == '1') {
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaDatosDespacho');
            $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"DATOS DESPACHO\"></td>";
            $this->salida .= "  </form>";
        }
        if (!empty($arr[0][fecha_radicacion])) {
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaRadicacion', array('rad' => $arr[0][fecha_radicacion]));
            $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MODIFICAR RADICACION\"></td>";
            $this->salida .= "  </form>";

            if ($menu['sw_anulacion_radicacion'] == '1') {
                $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaEliminarRadicacion', array('fecha_radicacion' => $arr[0][fecha_radicacion], 'envio_id' => $arr[0][envio_id]));
                $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ELIMINAR RADICACION\"></td>";
                $this->salida .= "  </form>";
            }
        } elseif ($arr[0][sw_estado] == 3 AND empty($arr[0][fecha_radicacion])) {
            if ($menu['sw_radicacion'] == '1') {
                $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaRadicacion');
                $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"RADICAR\"></td>";
                $this->salida .= "  </form>";
            }
        }
        if (($arr[0][sw_estado] == 0) OR ($arr[0][sw_estado] == 3)) {
            if ($menu['sw_anulacion_envios'] == '1') {
                $msg = 'Esta seguro que desea Anular el Envio No. ' . $arr[0][envio_id];
                $arreglo = array('envio' => $arr[0][envio_id]);
                $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'ConfirmarAccion', array('c' => 'app', 'm' => 'Facturacion_Fiscal', 'me2' => 'FormaDetalleEnvio', 'me' => 'FormaAnularEnvio', 'mensaje' => $msg, 'titulo' => 'ANULAR ENVIO No. ' . $arr[0][envio_id], 'arreglo' => $arreglo, 'boton1' => 'ACEPTAR', 'boton2' => 'VOLVER'));
                $this->salida .= "    <form name=\"formaborrar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ANULAR ENVIO\"></td>";
                $this->salida .= "  </form>";
            }
            if ($menu['sw_op_despacho'] == '1') {
                $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaDespachoEnvio', array('rad' => $arr[0][fecha_radicacion]));
                $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
                $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"DESPACHAR\"></td>";
                $this->salida .= "  </form>";
            }
        }
        unset($reporte);
        $accionDescargarRips = ModuloGetURL("app", "Facturacion_Fiscal", "user", "DescargarRipsEnvio", array("tiporips" => "Envio", "EnvioRips" => $arr[0][envio_id] . "/" . $_SESSION['FACTURACION']['EMPRESA'], "download" => "1"));
        $this->salida .= "<form name=\"frmDescargarRipss\" action=\"$accionDescargarRips\" method=\"post\" target=\"_blank\">";
        $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"btnDescargarRips\" value=\"DESCARGAR RIPS\" ></td>";
        $this->salida .="</form>";
        //DESCUENTOS
        $aplicarDescuento = ModuloGetURL("app", "Facturacion_Fiscal", "user", "FormaAplicarDesuentos", array("envio_id" => $arr[0][envio_id], "Descuento" => $arr[0][porcentaje_descuento]));
        $this->salida .= "<form name=\"frmDescuento\" action=\"$aplicarDescuento\" method=\"post\" target=\"_blank\">";
        $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"descuento\" value=\"APLICAR DESCUENTO\" ></td>";
        $this->salida .="</form>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";

        //$var=$_SESSION['DETALLE']['ENVIO'];
        //IncludeLib("reportes/envios");
        //GenerarEnvio($var);


        $tipo_reporte = $this->ConsultaTipoReporte($tmp_arr, 'ENVIO_FACTURA');
        $reporte = new GetReports();
        $mostrar = $reporte->GetJavaReport('app', 'Facturacion_Fiscal', 'enviosHTM', array('envio' => $arr[0][envio_id], 'tipo_reporte' => $tipo_reporte), array('rpt_name' => 'envio', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
        $funcion = $reporte->GetJavaFunction();
        $this->salida .=$mostrar;
        //$this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"IMPRIMIR\" onclick=\"javascript:$funcion\"></td>";
        //
		$js = "<script>";
        $js .= "function accion(frm){";
        //$js .= "  frm.action = '$accion';";
        //$js .= "alert(frm.action);";
        $js .= "	frm.submit();";
        $js .= "}";
        $js .= "</script>";
        $this->salida.="$js";
        $tiposReportes = $this->GetDatosTipoReporte();
        $accion = ModuloGetURL("app", "Facturacion_Fiscal", "user", "LlamaFrmVerReporteEnvio", array('envio' => $arr[0][envio_id]));
        $this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\" target=\"_blank\">";
        $this->salida.="	<td align=\"center\" class=\"NORMAL_10N\" colspan=\"3\">Tipo de reporte";
        $this->salida.="		<select size = 1 name = 'tiporeporte'  class =\"select\">";
        foreach ($tiposReportes AS $i => $v) {
            $this->salida.="	<option value = \"$v[reportes_envios_id]\">$v[tipo_reporte]</option>";
        }
        $this->salida.="		</select>";
        $this->salida.="		<a href=\"javascript:accion(document.forma);\">VER</a>";
        $this->salida.="	</td>";
        $this->salida.="</form>";
        //
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarRad');
        $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER BUSCAR ENVIO\"></td>";
        $this->salida .= "  </form>";

        //FIN DESCUENTOS
        // $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"IMPRIMIR\" onclick=\"javascript:abreVentana()\"></td>";
        $this->salida .= "      </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaRadicacion() {
        $arr = $_SESSION['DETALLE']['ENVIO'];
        if (empty($_REQUEST['rad'])) {
            $this->salida.= ThemeAbrirTabla('RADICAR ENVIO No. ' . $arr[0][envio_id]);
        } else {
            $this->salida.= ThemeAbrirTabla('MODIFICAR FECHA DE RADICACION ENVIO No. ' . $arr[0][envio_id]);
        }
        $this->EncabezadoEmpresa();
        $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td align=\"center\">ENVIO No. " . $arr[0][envio_id] . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td align=\"center\">RESPONSABLE: " . $arr[0][nombre_tercero] . "   " . $arr[0][tipo_tercero_id] . " " . $arr[0][tercero_id] . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "       <table border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'InsertarRadicacion', array('envio' => $arr[0][envio_id], 'rad' => $_REQUEST['rad']));
        $this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\" class=\"normal_10\"";
        $this->salida .= "    <tr>";
        $this->salida .= "    <td class=\"" . $this->SetStyle("Fecha") . "\">FECHA RADICACION: </td>";
        if (!empty($_REQUEST['rad'])) {
            $this->salida .= "    <td><input type=\"text\" name=\"Fecha\" size=\"13\" class=\"input-text\" value=\"" . $this->FechaStamp($_REQUEST['rad']) . "\">&nbsp;&nbsp;";
        } else {
            $this->salida .= "    <td><input type=\"text\" name=\"Fecha\" size=\"13\" class=\"input-text\">&nbsp;&nbsp;";
        }
        $this->salida .= ReturnOpenCalendario('forma', 'Fecha', '/') . "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "     <table width=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
        $this->salida .= "      <tr>";
        $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
        $this->salida .= "  </form>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaDetalleEnvio');
        $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
        $this->salida .= "  </form>";
        $this->salida .= "      </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaAnularEnvio() {
        $arr = $_SESSION['DETALLE']['ENVIO'];
        $this->salida.= ThemeAbrirTabla('DETALLE ENVIO');
        IncludeLib("tarifario");
        $this->EncabezadoEmpresa();
        $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td align=\"center\">ENVIO No. " . $arr[0][envio_id] . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td align=\"center\">" . $arr[0][nombre_tercero] . "   " . $arr[0][tipo_tercero_id] . " " . $arr[0][tercero_id] . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td align=\"center\">DEBE A:</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td align=\"center\">" . $this->NombreEmpresa($arr[0][empresa_id]) . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td align=\"center\">POR SERVICIOS PRESTADOS EN: </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        IF (empty($arr[0][departamento])) {
            $dpto = 'TODOS';
        } else {
            $dpto = $this->NombreDpto($arr[0][departamento]);
        }
        $this->salida .= "        <td align=\"center\"> - " . $dpto . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "        <td width=\"15%\">FACTURA</td>";
        $this->salida .= "        <td width=\"20%\">VALOR</td>";
        $this->salida .= "        <td width=\"15%\">IDENTIFICACION</td>";
        $this->salida .= "        <td width=\"25%\">PACIENTE</td>";
        $this->salida .= "        <td width=\"20%\">PLAN</td>";
        $this->salida .= "      </tr>";
        $total = 0;
        for ($i = 0; $i < sizeof($arr); $i++) {
            if ($i % 2) {
                $estilo = 'modulo_list_claro';
            } else {
                $estilo = 'modulo_list_oscuro';
            }
            $total+=$arr[$i][total_factura];
            $this->salida .= "      <tr class=\"$estilo\">";
            $this->salida .= "        <td align=\"center\">" . $arr[$i][prefijo] . " " . $arr[$i][factura_fiscal] . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($arr[$i][total_factura]) . "</td>";
            $this->salida .= "        <td>" . $arr[$i][tipo_id_paciente] . " " . $arr[$i][paciente_id] . "</td>";
            $this->salida .= "        <td>" . $arr[$i][nombre] . "</td>";
            $this->salida .= "        <td align=\"center\">" . $arr[$i][plan_descripcion] . "</td>";
            $this->salida .= "      </tr>";
        }
        $this->salida .= "  </table>";
        $this->salida .= "     <br><table width=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"left\" class=\"normal_10\">";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td width=\"25%\"></td>";
        $this->salida .= "        <td width=\"35%\">TOTAL DOCUMENTOS: </td>";
        $this->salida .= "        <td>$i</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td width=\"25%\"></td>";
        $this->salida .= "        <td width=\"35%\">TOTAL ENVIO ($): </td>";
        $this->salida .= "        <td>" . FormatoValor($total) . "</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "  <p><br></p>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'AnularEnvio', array('envio' => $arr[0][envio_id]));
        $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td width=\"25%\">OBSERVACION ANULACION: </td>";
        $this->salida .= "        <td><textarea name=\"Observacion\" cols=\"65\" rows=\"4\" class=\"textarea\"></textarea></td>";
        $this->salida .= "      </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "  <p><br></p>";
        $this->salida .= "     <table width=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
        $this->salida .= "      <tr>";
        $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
        $this->salida .= "  </form>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaDetalleEnvio');
        $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
        $this->salida .= "  </form>";
        $this->salida .= "      </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaResponsableAgrupada() {
        $action = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarFormaAgrupada');
        $this->salida .= ThemeAbrirTabla('ELEGIR RESPONSABLE');
        $this->salida .= "            <br><br>";
        $this->salida .= "            <table width=\"50%\" align=\"center\" border=\"0\">";
        $this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
        $this->salida .= "               <tr><td class=\"" . $this->SetStyle("Responsable") . "\">RESPONSABLE: </td><td><select name=\"Plan\" class=\"select\">";
        $responsables = $this->responsablesAgrupados();
        for ($i = 0; $i < sizeof($responsables); $i++) {
            $this->salida .=" <option value=\"" . $responsables[$i][tipo_tercero_id] . "," . $responsables[$i][tercero_id] . "," . $responsables[$i][nombre_tercero] . "\">" . $responsables[$i][nombre_tercero] . "</option>";
        }
        $this->salida .= "              </select></td></tr>";
        $this->salida .= "              <tr>";
        $this->salida .= "               <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"><br></td></form>";
        $actionM = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaMenus');
        $this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "               <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"><br></td></form></tr>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaBuscarFacturas($arr) {
        $_SESSION['FACTURACION']['CERRADAS'] = TRUE;
        $this->salida .= "<script language=\"javascript\">";
        $this->salida .= "function acceptNum(evt)\n";
        $this->salida .= "    {\n";
        $this->salida .= "      var nav4 = window.Event ? true : false;\n";
        $this->salida .= "      var key = nav4 ? evt.which : evt.keyCode;\n";
        $this->salida .= "      return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
        $this->salida .= "    }\n";
        $this->salida .= "		function BuscarTercero()\n";
        $this->salida .= "		{\n";
        $this->salida .= "			var url=\"" . ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarTerceros', array("empresa" => $_SESSION['FACTURACION']['EMPRESA'])) . "\"\n";
        $this->salida .= "			window.open(url,'','width=750,height=550,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no');\n";
        $this->salida .= "		}\n";

        $this->salida .= "		function limpiarCampos(objeto)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			objeto.Factura.value = \"\";\n";
        $this->salida .= "			objeto.Cuenta.value = \"\";\n";
        $this->salida .= "			objeto.Ingreso.value = \"\";\n";
        $this->salida .= "			objeto.Documento.value = \"\";\n";
        $this->salida .= "			objeto.Nombres.value = \"\";\n";
        $this->salida .= "			objeto.Apellidos.value = \"\";\n";
        $this->salida .= "			objeto.DocumentoTercero.value = \"\";\n";
        $this->salida .= "			objeto.PrefijoFac.selectedIndex='0';\n";
        $this->salida .= "			objeto.TipoDocumento.selectedIndex='0';\n";
        $this->salida .= "			objeto.TipoDocumentoTercero.selectedIndex='0';\n";
        $this->salida .= "		}\n";
        $this->salida .= "    function MostrarDetalle(prefijo,factura_fiscal,empresa_id,sw_clase_factura,tipo_doc)\n";
        $this->salida .= "    {\n";
        $this->salida .= "      url = \"" . ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaMostrarInformacionFactura') . "&prefijo=\"+prefijo+\"&factura_fiscal=\"+factura_fiscal+\"&empresa=\"+empresa_id+\"&sw_clase_factura=\"+sw_clase_factura+\"&tipo_doc=\"+tipo_doc;\n";
        $this->salida .= "			window.open(url,'cargar','toolbar=no,width=700,height=400,resizable=no,scrollbars=yes').focus();\n";
        $this->salida .= "    }\n";
        $this->salida .= "</script>\n";
        if ($this->post['prnAnuladas'] || $_REQUEST[prnAnuladas]) {
            $anulada = ' ANULADAS';
        } else {
            $anulada = '';
        }
        $this->salida.= ThemeAbrirTabla('BUSQUEDA DE FACTURAS*' . $anulada);
        IncludeLib("tarifario");
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarFacturas');
        $this->EncabezadoEmpresa();
        $this->salida .= "<br>\n";
        $this->salida .= "<table border=\"0\" width=\"82%\" align=\"center\" class=\"normal_10\">\n";
        $this->salida .= "	<tr>\n";
        $this->salida .= "		<td>\n";
        $this->salida .= "			<table border=\"0\" width=\"100%\" align=\"center\">\n";
        $this->salida .= "				<tr>\n";
        $this->salida .= "					<td>\n";
        $this->salida .= "					<fieldset><legend class=\"normal_10AN\">CRITERIOS DE BUSQUEDA</legend>\n";
        $this->salida .= "						<form name=\"forma\" action=\"$accion\" method=\"post\">\n";
        $this->salida .= "							<table width=\"100%\" align=\"center\" border=\"0\">\n";
        $this->salida .= "								<tr>\n";
        $this->salida .= "									<td class=\"normal_10AN\" width=\"17%\">PREFIJO : </td>\n";
        $this->salida .= "									<td width=\"30%\">\n";
        $prefijos = $this->ObtenerPrefijos();
        $this->salida .= "										<select name=\"PrefijoFac\" class=\"select\">\n";
        $this->salida .= "											<option value=\"\">-SELECCIONE-</option>\n";
        $sel = "";
        foreach ($prefijos as $key => $pref) {
            ($this->post['PrefijoFac'] == $pref['prefijo']) ? $sel = "selected" : $sel = "";
            $this->salida .= "										<option value=\"" . $pref['prefijo'] . "\" $sel>" . $pref['prefijo'] . "</option>\n";
        }
        $this->salida .= "										</select>\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "									<td class=\"normal_10AN\" width=\"18%\">No. FACTURA: </td>\n";
        $this->salida .= "									<td>\n";
        if ($_REQUEST[prnAnuladas]) {
            $this->salida .= "										<input type=\"hidden\" name=\"prnAnuladas\"  value=\"" . $_REQUEST[prnAnuladas] . "\">\n";
        } else
        if ($this->post['prnAnuladas']) {
            $this->salida .= "										<input type=\"hidden\" name=\"prnAnuladas\"  value=\"" . $this->post['prnAnuladas'] . "\">\n";
        }

        $this->salida .= "										<input type=\"text\" class=\"input-text\" name=\"Factura\" onkeypress=\"return acceptNum(event);\" value=\"" . $this->post['Factura'] . "\">\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "								</tr>\n";
        $this->salida .= "								<tr>\n";
        $this->salida .= "									<td class=\"normal_10AN\">No. CUENTA: </td>\n";
        $this->salida .= "									<td>\n";
        $this->salida .= "										<input type=\"text\" class=\"input-text\" name=\"Cuenta\" onkeypress=\"return acceptNum(event);\" value=\"" . $this->post['Cuenta'] . "\">\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "									<td class=\"normal_10AN\">No. INGRESO: </td>\n";
        $this->salida .= "									<td>\n";
        $this->salida .= "										<input type=\"text\" class=\"input-text\" name=\"Ingreso\" onkeypress=\"return acceptNum(event);\" value=\"" . $this->post['Ingreso'] . "\">\n";
        $this->salida .= "									</td>";
        $this->salida .= "								<tr>\n";
        $this->salida .= "								<tr>\n";
        $this->salida .= "									<td class=\"normal_10AN\">TIPO ID PACIENTE: </td>\n";
        $this->salida .= "									<td>\n";
        $this->salida .= "										<select name=\"TipoDocumento\" class=\"select\">\n";
        $this->salida .= "											<option value=\"\">-------SELECCIONE-------</option>\n";
        $tipo_id = $this->tipo_id_paciente();
        $this->BuscarIdPaciente($tipo_id, $this->post['TipoDocumento']);
        $this->salida .= "										</select>\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "									<td class=\"normal_10AN\">ID PACIENTE: </td>\n";
        $this->salida .= "									<td>\n";
        $this->salida .= "										<input type=\"text\" class=\"input-text\" name=\"Documento\" value=\"" . $this->post['Documento'] . "\">\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "								</tr>\n";

        $this->salida .= "								<tr>\n";
        $this->salida .= "									<td class=\"normal_10AN\">NOMBRE PACIENTE: </td>\n";
        $this->salida .= "									<td>\n";
        $this->salida .= "										<input type=\"text\" class=\"input-text\" name=\"Nombres\" style=\"width:100%\" value=\"" . $this->post['Nombres'] . "\">\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "									<td class=\"normal_10AN\">APELLIDO PACIENTE: </td>\n";
        $this->salida .= "									<td>\n";
        $this->salida .= "										<input type=\"text\" class=\"input-text\" name=\"Apellidos\" style=\"width:100%\" value=\"" . $this->post['Apellidos'] . "\">\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "								</tr>\n";


        $this->salida .= "								<tr>\n";
        $this->salida .= "									<td class=\"normal_10AN\">TIPO ID CLIENTE: </td>\n";
        $this->salida .= "									<td>\n";
        $this->salida .= "										<select name=\"TipoDocumentoTercero\" class=\"select\">\n";
        $this->salida .= "											<option value=\"\">-------SELECCIONE-------</option>\n";
        $tipo_id = $this->ObtenerTipoIdTercero();
        $this->BuscarIdPaciente($tipo_id, $this->post['TipoDocumentoTercero']);
        $this->salida .= "										</select>\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "									<td class=\"normal_10AN\">CLIENTE ID: </td>\n";
        $this->salida .= "									<td>\n";
        $this->salida .= "										<input type=\"text\" class=\"input-text\" style=\"width:100%\" name=\"DocumentoTercero\" value=\"" . $this->post['DocumentoTercero'] . "\">\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "								</tr>\n";

        $this->salida .= "								<tr>\n";
        $this->salida .= "									<td class=\"normal_10AN\" align=\"center\" colspan=\"4\">\n";
        $this->salida .= "										<a href=\"javascript:BuscarTercero()\" class=\"label_error\">BUSCAR CLIENTE POR NOMBRE</a>\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "								</tr>\n";

        $this->salida .= "								<tr>\n";
        $this->salida .= "									<td colspan = 4>\n";
        $this->salida .= "										<table width=\"100%\">\n";
        $this->salida .= "											<tr align=\"center\" >\n";
        $this->salida .= "												<td width=\"40%\">\n";
        $this->salida .= "													<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
        $this->salida .= "												</td>\n";

        $this->salida .= "												<td width=\"20%\">\n";
        $this->salida .= "													<input class=\"input-submit\" type=\"button\" onclick=\"limpiarCampos(document.forma)\" name=\"Limpiar\" value=\"Limpiar Campos\">\n";
        $this->salida .= "												</td>\n";

        $this->salida .= "												</form>\n";
        $actionM = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaMenus');  //}
        $this->salida .= "												<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "												<td width=\"40%\">\n";
        $this->salida .= "													<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\">\n";
        $this->salida .= "												</td>\n";
        $this->salida .= "												</form>\n";
        $this->salida .= "											</td>\n";
        $this->salida .= "										</tr>\n";
        $this->salida .= "									</table>\n";
        $this->salida .= "								</td>\n";
        $this->salida .= "							</tr>\n";
        $this->salida .= "						</table>\n";
        $this->salida .= "						</fieldset>\n";
        $this->salida .= "					</td>\n";
        $this->salida .= "				</tr>\n";
        $this->salida .= "			</table>\n";
        $this->salida .= "		</td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "</table>\n";
        //$this->salida .= "</td></tr></table>";
        //$this->salida .= "<tr><td class=\"label\">PREFIJO HC: </td><td><input type=\"text\" class=\"input-text\" name=\"Prefijo\"></td></tr>";
        //$this->salida .= "<tr><td class=\"label\">No. HISTORIA: </td><td><input type=\"text\" class=\"input-text\" name=\"Historia\"></td></tr>";
        //mensaje
        $this->salida .= "	<table border=\"0\" width=\"90%\" align=\"center\">\n";
        $this->salida .= "		" . $this->SetStyle("MensajeError");
        $this->salida .= "  </table>\n";

        if (!empty($arr)) {
            $this->salida .= "       <br>";
            $this->salida .= "    <table width=\"98%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td>No. FACTURA</td>";
            //$this->salida .= "        <td>No. CUENTA</td>";
            //$this->salida .= "        <td>IDENTIFICACION</td>";
            //$this->salida .= "        <td>PACIENTE</td>";
            //$this->salida .= "        <td>PIEZA</td>";
            ////$this->salida .= "        <td>CAMA</td>";
            $this->salida .= "        <td>RESPONSABLE</td>";
            $this->salida .= "        <td>PLAN</td>";
            //$this->salida .= "        <td>RANGO</td>";
            $this->salida .= "        <td>FECHA CIERRE</td>";
            $this->salida .= "        <td>PACIENTE</td>";
            //$this->salida .= "        <td>HORA CIERRE</td>";
            $this->salida .= "        <td>USUARIO</td>";
            $this->salida .= "        <td>NC</td>";
            $this->salida .= "        <td>ND</td>";
            $this->salida .= "        <td>GL</td>";
            $this->salida .= "        <td>RC</td>";
            //$this->salida .= "        <td>No. FACTURA</td>";
            //$this->salida .= "        <td>E</td>";
            $this->salida .= "        <td></td>";
            $this->salida .= "        <td></td>";
            $this->salida .= "      </tr>";
            for ($i = 0; $i < sizeof($arr); $i++) {
                $Descripcion = $arr[$i][descripcion];
                $Pieza = $arr[$i][pieza];
                $Cama = $arr[$i][cama];
                $Cuenta = $arr[$i][numerodecuenta];
                $PlanId = $arr[$i][plan_id];
                $Nivel = $arr[$i][rango];
                $Fecha = $arr[$i][fecha_registro];
                $Total = $arr[$i][total_cuenta];
                $ValorNo = $arr[$i][valor_nocubierto];
                $TipoId = $arr[$i][tipo_id_paciente];
                $PacienteId = $arr[$i][paciente_id];
                $PApellido = $arr[$i][primer_apellido];
                $SApellido = $arr[$i][segundo_apellido];
                $PNombre = $arr[$i][primer_nombre];
                $SNombre = $arr[$i][segundo_nombre];
                $TipoFactura = $arr[$i][tipo_factura];
                //$Estado=$arr[$i][estado];
                $Ingreso = $arr[$i][ingreso];
                $datos = $this->CallMetodoExterno('app', 'Triage', 'user', 'BuscarPlanes', array('PlanId' => $PlanId, 'Ingreso' => $Ingreso));
                $Fechas = $this->FechaStamp($Fecha);
                $Horas = $this->HoraStamp($Fecha);
                $accionHRef = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'MostrarDetalle', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Pieza' => $Pieza, 'Cama' => $Cama, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'numero' => $arr[$i][factura_fiscal], 'prefijo' => $arr[$i][prefijo], 'empresa' => $arr[$i][empresa_id], 'cu' => $arr[$i][centro_utilidad], 'tipo_factura' => $arr[$i][tipo_factura], 'BusquedaPorCuenta' => $_REQUEST[Cuenta], 'prnAnuladas' => $this->post['prnAnuladas']));
                //$accionHRef=ModuloGetURL('app','Facturacion_Fiscal','user','Facturacion',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'numero'=>$arr[$i][factura_fiscal],'prefijo'=>$arr[$i][prefijo],'empresa'=>$arr[$i][empresa_id],'cu'=>$arr[$i][centro_utilidad]));
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td align=\"center\">" . $arr[$i][prefijo] . " " . $arr[$i][factura_fiscal] . "</td>";
                //$this->salida .= "        <td align=\"center\">$Cuenta</td>";
                //$this->salida .= "        <td>$TipoId $PacienteId</td>";
                //$this->salida .= "        <td>$PNombre $SNombre $PApellido $SApellido</td>";
                //$this->salida .= "        <td align=\"center\">$Pieza</td>";
                //$this->salida .= "        <td align=\"center\">$Cama</td>";
                $this->salida .= "        <td align=\"center\">" . $datos[nombre_tercero] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $datos[plan_descripcion] . "</td>";
                //$this->salida .= "        <td align=\"center\">$Nivel</td>";
                $this->salida .= "        <td align=\"center\">$Fechas</td>";
                //$this->salida .= "        <td align=\"center\">$Horas</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$i][nombre_paciente] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$i][nombre] . "</td>";
                $this->salida .= "				<td align=\"center\">\n";
                if ($arr[$i]['valor_credito'] > 0) {
                    $this->salida .= "				<a href=\"javascript:MostrarDetalle('" . $arr[$i]['prefijo'] . "','" . $arr[$i]['factura_fiscal'] . "','" . $arr[$i]['empresa_id'] . "','" . $arr[$i]['sw_clase_factura'] . "','NC')\" title=\"INFORMACI�N NOTAS CREDITO FACTURA\">\n";
                    $this->salida .= "					<img src=\"" . GetThemePath() . "/images/panulado.png\" border=\"0\">\n";
                    $this->salida .= "				</a>\n";
                }
                $this->salida .= "        </td>\n";
                $this->salida .= "				<td align=\"center\">\n";
                if ($arr[$i]['valor_debito'] > 0) {
                    $this->salida .= "				<a href=\"javascript:MostrarDetalle('" . $arr[$i]['prefijo'] . "','" . $arr[$i]['factura_fiscal'] . "','" . $arr[$i]['empresa_id'] . "','" . $arr[$i]['sw_clase_factura'] . "','ND')\" title=\"INFORMACI�N NOTAS DEBITO FACTURA\">\n";
                    $this->salida .= "					<img src=\"" . GetThemePath() . "/images/panulado.png\" border=\"0\">\n";
                    $this->salida .= "				</a>\n";
                }
                $this->salida .= "        </td>\n";

                $this->salida .= "				<td align=\"center\">\n";
                if ($arr[$i]['valor_glosa'] > 0) {
                    $this->salida .= "          <a href=\"javascript:MostrarDetalle('" . $arr[$i]['prefijo'] . "','" . $arr[$i]['factura_fiscal'] . "','" . $arr[$i]['empresa_id'] . "','" . $arr[$i]['sw_clase_factura'] . "','NG')\" title=\"INFORMACI�N GLOSAS FACTURA\">\n";
                    $this->salida .= "					  <img src=\"" . GetThemePath() . "/images/Listado.png\" border=\"0\">\n";
                    $this->salida .= "				  </a>\n";
                }
                $this->salida .= "        </td>\n";
                $this->salida .= "				<td align=\"center\">\n";
                if ($arr[$i]['valor_recibo'] > 0) {
                    $this->salida .= "				  <a href=\"javascript:MostrarDetalle('" . $arr[$i]['prefijo'] . "','" . $arr[$i]['factura_fiscal'] . "','" . $arr[$i]['empresa_id'] . "','" . $arr[$i]['sw_clase_factura'] . "','RT')\" title=\"INFORMACI�N RECIBOS DE TESORERIA\">\n";
                    $this->salida .= "						<img src=\"" . GetThemePath() . "/images/cargar.png\" border=\"0\">\n";
                    $this->salida .= "				  </a>\n";
                }
                $this->salida .= "				</td>\n";

                if ($arr[$i][estado] == 'PAGADA' AND $anulada == '') {
                    $this->salida .= "        <td align=\"center\"><a href=\"$accionHRef\"><img src=\"" . GetThemePath() . "/images/entregabolsa.png\" title = \"" . $arr[$i][estado] . "\" border=\"0\"></a></td>";
                    $this->salida .= "<td align=\"center\">&nbsp;</td>";
                } else
                if ($arr[$i][estado] == 'ANULADA' AND $anulada == '') {
                    $this->salida .= "        <td align=\"center\"><a ><img src=\"" . GetThemePath() . "/images/delete.gif\" title = \"" . $arr[$i][estado] . "\" border=\"0\"></a></td>";
                    $this->salida .= "<td align=\"center\">&nbsp;</td>";
                } else {
                    $this->salida .= "        <td align=\"center\"><a href=\"$accionHRef\" class=\"label_error\">VER</a></td>";
                    if ($arr[$i][sw_tipo_plan] == 1) {
                        $accionCC = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarVentanaFinal', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Pieza' => $Pieza, 'Cama' => $Cama, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'numero' => $arr[$i][factura_fiscal], 'prefijo' => $arr[$i][prefijo], 'empresa' => $arr[$i][empresa_id], 'cu' => $arr[$i][centro_utilidad], 'tipo_factura' => $arr[$i][tipo_factura]));
                        $this->salida .= "        <td align=\"center\"><a href=\"$accionCC\" class=\"label_error\" title=\"VER CUENTA DE COBRO\">VER CC</a></td>";
                        /* 									$reporte = new GetReports();
                          $this->salida .= $reporte->GetJavaReport('app','Facturacion_Fiscal','CuentaCobro',
                          array('PlanId'=>$PlanId,'Fecha'=>$Fecha,
                          'Ingreso'=>$Ingreso,'numero'=>$arr[$i][factura_fiscal],
                          'prefijo'=>$arr[$i][prefijo],'empresa'=>$arr[$i][empresa_id],
                          'tipo_factura'=>$arr[$i][tipo_factura],'cuenta'=>$Cuenta),array('rpt_dir'=>'cache','rpt_name'=>'cuentacobro'.$Ingreso,'rpt_rewrite'=>FALSE));
                          $funcion=$reporte->GetJavaFunction(); */
                        //$this->salida .= "<td align=\"center\"><a href=\"javascript:$funcion\" title=\"VER CUENTA DE COBRO\">VER CC</a></td>";
                    } else {
                        $this->salida .= "<td align=\"center\">&nbsp;</td>";
                    }
                }
                $this->salida .= "      </tr>";
            }//fin for
            $this->salida .= " </table>";
            $this->conteo = $_SESSION['SPY'];
            $this->salida .=$this->RetornarBarra();
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     * Se encarga de separar la hora del formato timestamp
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
        $x = explode('.', $time[3]);
        return $time[1] . ":" . $time[2] . ":" . $x[0];
    }

//--------------------ANULAR FACTURA-------------------------------------
    /**
     * *
     */
    function FormaAnular($Transaccion, $TipoId, $PacienteId, $Nivel, $PlanId, $Fecha, $Ingreso, $Cuenta, $Estado) {
        if (empty($TipoId)) {
            $Transaccion = $_REQUEST['Transaccion'];
            $TipoId = $_REQUEST['TipoId'];
            $PacienteId = $_REQUEST['PacienteId'];
            $Nivel = $_REQUEST['Nivel'];
            $PlanId = $_REQUEST['PlanId'];
            $Fecha = $_REQUEST['Fecha'];
            $Ingreso = $_REQUEST['Ingreso'];
            $Cuenta = $_REQUEST['Cuenta'];
            $Estado = $_REQUEST['Estado'];
        }
        $this->salida .= ThemeAbrirTabla('ANULAR FACTURA No. ' . $_SESSION['FACTURACION']['VAR']['prefijo'] . $_SESSION['FACTURACION']['VAR']['factura']);

        $this->EncabezadoEmpresa();
        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $Fecha));
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'AnularFactura', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
        $this->salida .= "       <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "     </table>";
        $this->salida .= "       <table border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= "           <tr>";
        $this->salida .= "              <td  width=\"30%\" class=\"" . $this->SetStyle("Observaciones") . "\">OBSERVACIONES ANULACION: </td>";
        $this->salida .= "              <td><textarea  cols=\"80\" rows=\"4\" class=\"textarea\" name=\"observacion\"></textarea></td>";
        $this->salida .= "            </tr>";
        $this->salida .= "       </table>";
        $this->salida .= "       <table align=\"center\" border=\"0\" width=\"50%\">";
        $this->salida .= "    <tr>";
        $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"ACEPTAR\"></form></td>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"CANCELAR\"></form></td>";
        $this->salida .= "    </tr>";
        $this->salida .= "       </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     * *
     */
    function FormaAnularAgrupadas() {
        $this->salida .= ThemeAbrirTabla('ANULAR FACTURA No. ' . $_REQUEST['prefijo'] . $_REQUEST['numero']);
        $this->EncabezadoEmpresa();
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'AnularFacturaAgrupada', array('numero' => $_REQUEST['numero'], 'prefijo' => $_REQUEST['prefijo'], 'datos' => $_REQUEST['datos'], 'empresa' => $_REQUEST['empresa']));
        $this->salida .= "       <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "     </table>";
        $this->salida .= "       <table border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= "           <tr>";
        $this->salida .= "              <td  width=\"30%\" class=\"" . $this->SetStyle("Observaciones") . "\">OBSERVACIONES ANULACION: </td>";
        $this->salida .= "              <td><textarea  cols=\"80\" rows=\"4\" class=\"textarea\" name=\"observacion\"></textarea></td>";
        $this->salida .= "            </tr>";
        $this->salida .= "       </table>";
        $this->salida .= "       <table align=\"center\" border=\"0\" width=\"50%\">";
        $this->salida .= "    <tr>";
        $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"ACEPTAR\"></form></td>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'MostrarDetalle', array('prefijo' => $_REQUEST['prefijo'], 'numero' => $_REQUEST['numero']));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"CANCELAR\"></form></td>";
        $this->salida .= "    </tr>";
        $this->salida .= "       </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaBuscarAgrupadas($arr, $new) {
        $request = $_REQUEST;
        $permisos = SessionGetVar('FACTURACION');

        $rangos = array();
        if ($request['terceros'] && $request['terceros'] != '-1') {
            $ter = explode("*", $request['terceros']);
            $fc = new app_Facturacion_Permisos();
            $planes = $fc->ObtenerTercerosPlanesAgrupados($permisos['EMPRESA'], $ter[0], $ter[1]);
            $rangos = $fc->ObtenerRangosNiveles($ter[0], $ter[1]);
        }

        IncludeLib("tarifario");
        $this->Todos();
        $_SESSION['FACTURACION']['CERRADAS'] = false;

        if (!$Busqueda && $_SESSION['FACTURACION']['SWCUENTAS'] != 'Agrupadas')
            $Busqueda = 1;
        elseif (!$Busqueda && $_SESSION['FACTURACION']['SWCUENTAS'] == 'Agrupadas')
            $Busqueda = 5;

        if ($_SESSION['FACTURACION']['SWCUENTAS'] == 'Agrupadas')
            $por = '84%';
        else
            $por = '98%';

        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarFacturaAgrupada');
        $Empresa = $this->Empresa;
        $CU = $this->CentroUtilidad;

        $this->Cambio();

        $action['terceros'] = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarFormaBuscarAgrupadas', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado, 'Nivel' => $Nivel));

        $this->salida .= "<script>\n";
        $this->salida .= "  function EnviarTerceros()\n";
        $this->salida .= "  {\n";
        $this->salida .= "    document.forma1.action =\"" . $action['terceros'] . "\"; \n";
        $this->salida .= "    document.forma1.submit();\n";
        $this->salida .= "  }\n";
        $this->salida .= "</script>\n";
        $this->salida .= ThemeAbrirTabla('BUSCAR CUENTA');
        $this->EncabezadoEmpresa();

        if ($_SESSION['FACTURACION']['SWCUENTAS'] != 'Agrupadas') {
            $responsables = $this->responsables();
        } elseif ($_SESSION['FACTURACION']['SWCUENTAS'] == 'Agrupadas') {
            $adicional = "onChange=\"Cambio(this.value,'" . $action['terceros'] . "')\" ";
            $responsables = $this->responsablesAgrupados();
        }

        $terceros = $this->ObtenerTercerosPlanesAgrupados($permisos['EMPRESA']);
        $this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" >";
        $this->salida .= "  <tr>\n";
        $this->salida .= "    <td>\n";
        $this->salida .= "      <table border=\"0\" width=\"$por\" align=\"center\">";
        $this->salida .= "        <tr>\n";
        $this->salida .= "          <td>\n";
        $this->salida .= "            <fieldset class=\"fieldset\">\n";
        $this->salida .= "              <legend class=\"normal_10AN\">BUSCAR CUENTA </legend>";
        $this->salida .= "              <form name=\"forma1\" action=\"" . $accion . "\" method=\"post\">";
        $this->salida .= "                <center>\n";
        $this->salida .= "                  <div class=\"label_error\">" . $this->frmError["MensajeError"] . "</div>\n";
        $this->salida .= "                </center>\n";
        $this->salida .= "                <table width=\"100%\" align=\"center\" border=\"0\">";
        $this->salida .= "                  <tr>\n";
        $this->salida .= "                    <td class=\"" . $this->SetStyle("terceros") . "\">TERCERO: </td>";
        $this->salida .= "                    <td colspan=\"2\">\n";
        $this->salida .= "                      <select name=\"terceros\" class=\"select\" onChange=\"EnviarTerceros()\">\n";
        $this->salida .= "                        <option value=\"-1\">-----SELECCIONE-----</option>\n";
        foreach ($terceros as $key => $dtl)
            $this->salida .= " <option value=\"" . $dtl['tipo_tercero_id'] . "*" . $dtl['tercero_id'] . "\" " . (($dtl['tipo_tercero_id'] . "*" . $dtl['tercero_id'] == $_REQUEST['terceros']) ? "selected" : "") . ">" . $dtl['nombre_tercero'] . "</option>\n";

        $this->salida .= "                      </select>\n";
        $this->salida .= "                    </td>\n";
        $this->salida .= "                  </tr>\n";
        $this->salida .= "                  <tr>\n";
        $this->salida .= "                    <td valign=\"top\" class=\"" . $this->SetStyle("Plan") . "\">PLAN: </td>";
        $this->salida .= "                    <td colspan=\"3\">\n";
        if (!empty($planes)) {
            $this->salida .= "                      <table width=\"100%\" align=\"center\">\n";
            foreach ($planes as $key => $dtl) {
                $this->salida .= "                      <tr>\n";
                $this->salida .= "                        <td width=\"1%\">\n";
                $this->salida .= "                          <input type=\"checkbox\" name=\"Plan[" . $dtl['plan_id'] . "]\" value=\"" . $dtl['plan_id'] . "\" " . (($request['Plan'][$dtl['plan_id']]) ? "checked" : "") . ">\n";
                $this->salida .= "                        </td>\n";
                $this->salida .= "                        <td class=\"normal_10AN\" width=\"49%\" >" . $dtl['plan_descripcion'] . "</td>\n";
                $this->salida .= "                        <td width=\"50%\" class=\"label\">RANGO:&nbsp;&nbsp;\n";
                $this->salida .= "                          <select name=\"rango[" . $key . "]\" class=\"select\">\n";
                $this->salida .= "                            <option value=\"-1\">Seleccionar</option>\n";
                foreach ($rangos[$key] as $key1 => $dt1) {
                    ($request['rango'][$key] == $dt1['rango']) ? $s = "selected" : $s = "";
                    $this->salida .= "                            <option value=\"" . $dt1['rango'] . "\" " . $s . ">" . $dt1['rango'] . "</option>\n";
                }
                $this->salida .= "                          </select>\n";
                $this->salida .= "                        </td>\n";
                $this->salida .= "                      </tr>\n";
            }
            $this->salida .= "                      </table>\n";
        }
        $this->salida .= "                    </td>\n";
        $this->salida .= "                  </tr>\n";

        if ($_SESSION['FACTURACION']['aseguradora'] == 'Si') {
            $this->salida .= "               <tr><td class=\"" . $this->SetStyle("Aseguradora") . "\">RESPONSABLE: </td>";
            $this->salida .= "               <td colspan=\"3\"><select name=\"Aseguradora\" class=\"select\">";
            $aseguradoras = $this->CallMetodoExterno('app', 'Soat', 'user', 'BuscarAseguradoraSoat');
            $this->MostrarAseguradoras($aseguradoras);
            $this->salida .= "              </select></td></tr>";
        }
        $this->salida .= "                <tr>";
        //$i=$_REQUEST['FechaI'];
        if (!empty($_REQUEST['FechaI'])) {
            $f = explode('-', $_REQUEST['FechaI']);
            $i = $f[2] . '/' . $f[1] . '/' . $f[0];
        }
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaI") . "\">DESDE: </td>";
        $this->salida .= "                    <td colspan=\"3\"><input type=\"text\" class=\"input-text\" name=\"FechaI\" value=\"" . $i . "\">" . ReturnOpenCalendario('forma1', 'FechaI', '/') . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        //$fi=$_REQUEST['FechaF'];
        if (!empty($_REQUEST['FechaF'])) {
            $f = explode('-', $_REQUEST['FechaF']);
            $fi = $f[2] . '/' . $f[1] . '/' . $f[0];
        }
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaF") . "\">HASTA: </td>";
        $this->salida .= "                    <td colspan=\"3\"><input type=\"text\" class=\"input-text\" name=\"FechaF\" value=\"" . $fi . "\">" . ReturnOpenCalendario('forma1', 'FechaF', '/') . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
        $this->salida .= "                 <input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";
        $this->salida .= "                <tr><td class=\"label\">DEPARTAMENTO: </td>\n";
        $this->salida .= "                  <td colspan=\"3\"><select name=\"Departamento\" class=\"select\">";
        $departamento = $this->Departamentos();
        $this->BuscarDepartamento($departamento, $d, $_REQUEST['Departamento']);
        $this->salida .= "                  </select></td></tr>";

        //FILTRO TIPO DE DOCUMENTO Y DOCUMENTO
        $this->salida .= "								<tr>\n";
        $this->salida .= "									<td width=\"20%\" class=\"label\">TIPO ID PACIENTE: </td>\n";
        $this->salida .= "									<td width=\"30%\">\n";
        $this->salida .= "										<select name=\"TipoDocumento\" class=\"select\">\n";
        $this->salida .= "											<option value=\"\">-------SELECCIONE-------</option>\n";
        $tipo_id = $this->tipo_id_paciente();
        $this->BuscarIdPaciente($tipo_id, $_REQUEST['TipoDocumento']);
        $this->salida .= "										</select>\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "									<td width=\"20%\" class=\"label\">ID PACIENTE: </td>\n";
        $this->salida .= "									<td width=\"30%\">\n";
        $this->salida .= "										<input type=\"text\" class=\"input-text\" name=\"Documento\" value=\"" . $_REQUEST[Documento] . "\">\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "								</tr>\n";
        //FIN FILTRO TIPO DE DOCUMENTO Y DOCUMENTO

        $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"BuscarCuentas\" value=\"BUSCAR\"></td>";
        $this->salida .= "            </form>";
        $actionM = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Menu');
        $this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "                  <td colspan=\"2\" align=\"center\">\n";
        $this->salida .= "                    <br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br>\n";
        $this->salida .= "                  </td>\n";
        $this->salida .= "              </form>\n";
        $this->salida .= "                </tr>\n";
        $this->salida .= "              </table>\n";
        $this->salida .= "            </fieldset>\n";
        $this->salida .= "          </td>\n";
        $this->salida .= "        </tr>\n";
        $this->salida .= "      </table>";
        $this->salida .= "       </td>";
        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table>";
        if (!empty($arr)) {
            unset($_SESSION['FACTURACION']['VECTOR']);
            $_SESSION['FACTURACION']['VECTOR'] = $arr;
            $this->FormaCuentasCerradas($_REQUEST['paso'], $_REQUEST['Plan'], $_REQUEST['Of'], $_REQUEST['FechaI'], $_REQUEST['FechaF'], $_REQUEST['Departamento'], $_REQUEST[TipoDocumento], $_REQUEST[Documento], $_REQUEST['rango']);
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaCuentasCerradas($paso, $new, $of, $fechaI, $fechaF, $dpto, $TipoDocumento, $Documento, $rango) {
        $this->Todos();
        if (empty($paso)) {
            $paso = 1;
        }
        UNSET($_SESSION['FACTURACION']['SELECCION']['AGRUPADA']);
        $arr = $_SESSION['FACTURACION']['VECTOR'];
        $accionF = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FacturarCuentasAgrupadas', array('paso' => $paso, 'Plan' => $new, 'Of' => $of, 'FechaI' => $fechaI, 'FechaF' => $fechaF, 'Departamento' => $dpto, 'TipoDocumento' => $TipoDocumento, 'Documento' => $Documento, 'rango' => $rango));
        $this->salida .= "<form name=\"forma\" action=\"$accionF\" method=\"post\">";
        $this->salida .= "<br><table width=\"98%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "        <td>No. CUENTA</td>";
        $this->salida .= "        <td>IDENTIFICACION</td>";
        $this->salida .= "        <td>PACIENTE</td>";
        $this->salida .= "        <td>RESPONSABLE</td>";
        $this->salida .= "        <td>PLAN</td>";
        $this->salida .= "        <td>NIVEL</td>";
        $this->salida .= "        <td>FECHA APERTURA</td>";
        $this->salida .= "        <td>HORA APERTURA</td>";
        $this->salida .= "        <td>TOTAL CUENTA</td>";
        $this->salida .= "        <td>TOTAL EMPRESA..</td>";
        $this->salida .= "        <td>E</td>";
        $this->salida .= "        <td><input type=\"checkbox\" name=\"Todo\" onClick=\"Todos(this.form,this.checked)\"></td>";
        $this->salida .= "      </tr>";
        for ($i = 0; $i < sizeof($arr); $i++) {
            $Cuenta = $arr[$i][numerodecuenta];
            //$PlanId=$arr[$i][plan_id];
            $sw = $this->FacturaAgrupada($arr[$i][plan_id]);
            //$Fecha=$arr[$i][fecha_registro];
            $Total = $arr[$i][total_cuenta];
            $ValorNo = $arr[$i][valor_nocubierto];
            $TipoId = $arr[$i][tipo_id_paciente];
            $PacienteId = $arr[$i][paciente_id];
            $Nombre = $arr[$i][nombre];
            $Nivel = $arr[$i][rango];
            //$Estado=$arr[$i][estado];
            //$Ingreso=$arr[$i][ingreso];
            $Descuento = $arr[$i][valor_descuento_paciente] + $arr[$i][valor_descuento_empresa];
            $datos = $this->CallMetodoExterno('app', 'Triage', 'user', 'BuscarPlanes', array('PlanId' => $arr[$i][plan_id], 'Ingreso' => $arr[$i][ingreso]));
            $Fechas = $this->FechaStamp($arr[$i][fecha_registro]);
            $Horas = $this->HoraStamp($arr[$i][fecha_registro]);
            $accionHRef = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $arr[$i][ingreso], 'Estado' => $arr[$i][estado], 'Nivel' => $Nivel, 'arreglo' => $arr[$i]));
            if ($i % 2) {
                $estilo = 'modulo_list_claro';
            } else {
                $estilo = 'modulo_list_oscuro';
            }
            $this->salida .= "      <tr class=\"$estilo\">";
            $this->salida .= "        <td align=\"center\">$Cuenta</td>";
            $this->salida .= "        <td>$TipoId $PacienteId</td>";
            $this->salida .= "        <td>$Nombre</td>";
            $this->salida .= "        <td align=\"center\">" . $datos[nombre_tercero] . "</td>";
            $this->salida .= "        <td align=\"center\">" . $datos[plan_descripcion] . "</td>";
            $this->salida .= "        <td align=\"center\">$Nivel</td>";
            $this->salida .= "        <td align=\"center\">$Fechas</td>";
            $this->salida .= "        <td align=\"center\">$Horas</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($Total) . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($arr[$i][valor_total_empresa]) . "</td>";
            $this->salida .= "        <td align=\"center\">" . $arr[$i][estado] . "</td>";
            $arreglo = $arr[$i][numerodecuenta] . "," . $arr[$i][plan_id] . "," . $arr[$i][valor_total_empresa] . "," . $arr[$i][valor_nocubierto] . "," . $arr[$i][ingreso] . "," . $arr[$i][valor_cuota_paciente] . "," . $arr[$i][valor_cubierto] . "," . $arr[$i][gravamen_valor_cubierto] . "," . $arr[$i][gravamen_valor_nocubierto] . "," . $Descuento;
            if ($sw == 1) {
                if (!empty($_SESSION['FACTURACION']['SELECCION'][$paso][$arr[$i][numerodecuenta]])) {
                    $this->salida .= "        <td align=\"center\"><input type=\"checkbox\" value=\"" . $arreglo . "\" name=\"seleccion$i\" checked></td>";
                } else {
                    $this->salida .= "        <td align=\"center\"><input type=\"checkbox\" value=\"" . $arreglo . "\" name=\"seleccion$i\"></td>";
                }
            } else {
                $this->salida .= "        <td align=\"center\"></td>";
            }
            $this->salida .= "      </tr>";
        }//fin for
        $this->salida .= " </table>";
        $this->salida .= " <p class=\"normal_10N\" align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Seleccionar\" value=\"SELECCIONAR\"></p><br>";
        $this->salida .= " <p class=\"normal_10N\" align=\"center\">TODAS LAS CUENTAS DE LA BUSQUEDA <input type=\"checkbox\" value=\"TodoCuentas\" name=\"Todo\"></p>";
        $this->salida .= "<table width=\"98%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" >";
        $this->salida .= "<tr><td align=\"center\">";
        $this->salida .= "<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"FACTURAR\"></form>";
        $this->salida .= "</td></tr>";
        $this->salida .= " </table>";
        $this->conteo = $_SESSION['SPY'];
        $this->salida .=$this->RetornarBarraA();
    }

    /**
     *
     */
    function RetornarBarraA() {
        if ($this->limit >= $this->conteo) {
            return '';
        }
        $paso = $_REQUEST['paso'];
        if (empty($paso)) {
            $paso = 1;
        }
        $vec = '';
        foreach ($_REQUEST as $v => $v1) {
            if ($v != 'modulo' and $v != 'metodo' and $v != 'SIIS_SID' and $v != 'Of') {
                $vec[$v] = $v1;
            }
        }

        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarFacturaAgrupada', $vec);
        $barra = $this->CalcularBarra($paso);
        $numpasos = $this->CalcularNumeroPasos($this->conteo);
        $colspan = 1;

        $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if ($paso > 1) {
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset(1) . "&paso=1'>&lt;</a></td>";
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=" . $this->CalcularOffset($paso - 1) . "&paso=" . ($paso - 1) . "'>&lt;&lt;</a></td>";
            $colspan+=1;
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
            }
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
            $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=" . $valor . " align='center'>P�ina $paso de $numpasos</td><tr></table><br>";
        } else {
            if ($numpasos > 10) {
                $valor = 10 + 5;
            } else {
                $valor = $numpasos + 5;
            }
            $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=" . $valor . " align='center'>P�ina $paso de $numpasos</td><tr></table><br>";
        }
    }

//---------------------LA NUEVA FORMA CON CODIGO DE AGRUPAMIENTO-----------------------

    /**
     * Muestra el detalle de apoyos diagnsoticos de una cuenta.
     * @access private
     * @return boolean
     * @param int numero de la cuenta
     * @param string tipo documento
     * @param int numero documento
     * @param string nivel
     * @param string plan_id
     * @param int numero de la cama
     * @param date fecha de la cuenta
     * @param int ingreso
     * @param array arreglo con los datos de la cuenta
     * @param int numero de transaccion
     * @param int total del paciente
     * @param int total no cubierto
     * @param int total de la empresa
     * @param int valor total (cant. x precio)
     */
    function FormaDetalleCodigo($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Cama, $Fecha, $Ingreso, $var, $desc, $codigo, $documento, $numeracion, $Transaccion) {
        global $VISTA;
        IncludeLib("tarifario");
        IncludeLib("funciones_facturacion");
        $Nombres = $this->BuscarNombresPaciente($TipoId, $PacienteId);
        $Apellidos = $this->BuscarApellidosPaciente($TipoId, $PacienteId);
        $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. ' . $Cuenta . ' ' . $Nombres . ' ' . $Apellidos);
        $this->EncabezadoEmpresa();
        $Detalle = $this->BuscarDetalleCuenta($Cuenta);
        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $Fecha));
        $_SESSION['CUENTAS']['EMPRESA'] = $_SESSION['FACTURACION']['EMPRESA'];
        $_SESSION['CUENTAS']['CENTROUTILIDAD'] = $_SESSION['FACTURACION']['CENTROUTILIDAD'];
        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamaTotalesCuenta', array('Cuenta' => $Cuenta));
        $this->salida .= "  </fieldset></td></tr></table><BR>";
        $this->ConsultaAutorizacion();
        //$this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
        $this->salida .= " <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"98%\" align=\"center\">";
        $this->salida .= "    <tr class=\"modulo_table_title\">";
        $this->salida .= "        <td>DETALLE DE " . $desc . "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td><br>";
        $this->salida .= " <table border=\"0\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list\">";
        $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
        if (empty($documento) AND empty($numeracion)) {
            $this->salida .= "        <td width=\"50%\">CARGO</td>";
            $this->salida .= "        <td width=\"9%\">PRECIO.</td>";
            $this->salida .= "        <td width=\"5%\">CANT.</td>";
            $this->salida .= "        <td width=\"9%\">VALOR</td>";
            $this->salida .= "        <td width=\"9%\">VAL. NO CUBIERTO</td>";
            $this->salida .= "        <td width=\"9%\">VAL. CUBIERTO</td>";
            $this->salida .= "        <td width=\"5%\">FIRMA</td>";
        } elseif (!empty($documento) AND !empty($numeracion)) {
            $this->salida .= "        <td width=\"10%\">CODIGO</td>";
            $this->salida .= "        <td width=\"40%\">CARGO</td>";
            $this->salida .= "        <td width=\"9%\">PRECIO.</td>";
            $this->salida .= "        <td width=\"5%\">CANT.</td>";
            $this->salida .= "        <td width=\"9%\">VALOR</td>";
            $this->salida .= "        <td width=\"9%\">VAL. NO CUBIERTO</td>";
            $this->salida .= "        <td width=\"9%\">VAL. CUBIERTO</td>";
        }
        $this->salida .= "        <td>INT</td>";
        $this->salida .= "        <td>EXT</td>";
        $this->salida .= "        <td></td>";
        $this->salida .= "    </tr>";
        $ValTotal = $TotalNo = $TotalCub = 0;
        for ($i = 0; $i < sizeof($var); $i++) {
            if ($i % 2)
                $estilo = 'modulo_list_claro';
            else
                $estilo = 'modulo_list_oscuro';
            $ValTotal+=$var[$i][valor_cargo];
            $TotalNo+=$var[$i][valor_nocubierto];
            $TotalCub+=$var[$i][valor_cubierto];
            $this->salida .= "    <tr class=\"$estilo\">";
            if (!empty($documento) AND !empty($numeracion)) {
                $this->salida .= "        <td align=\"center\">" . $var[$i][codigo_producto] . "</td>";
            }
            $this->salida .= "        <td>" . $var[$i][descripcion] . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($var[$i][precio]) . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($var[$i][cantidad]) . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($var[$i][valor_cargo]) . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($var[$i][valor_nocubierto]) . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($var[$i][valor_cubierto]) . "</td>";
            if (empty($documento) AND empty($numeracion)) {
                $res = FirmaResultado($var[$i][transaccion]);
                $img = '';
                //hay resultado
                if (!empty($res)) {
                    $this->salida .= "        <td align=\"center\"><img src=\"" . GetThemePath() . "/images/checksi.png\"></td>";
                } else {
                    $this->salida .= "      <td></td>";
                }
            }
            $imagenInt = $imagenExt = '';
            if ($var[$i][autorizacion_int] === '0') {
                $imagenInt = "no_autorizado.png";
                $D = 1;
            } elseif ($var[$i][autorizacion_int] > 100) {
                $imagenInt = "autorizado.png";
                $D = 0;
            } elseif ($var[$i][autorizacion_int] == 1) {
                $imagenInt = "autorizadosiis.png";
                $D = 1;
            }

            if ($var[$i][autorizacion_ext] === '0') {
                $imagenExt = "no_autorizado.png";
                $n = 1;
            } elseif ($var[$i][autorizacion_ext] > 100) {
                $imagenExt = "autorizado.png";
                $n = 0;
            } elseif ($var[$i][autorizacion_ext] == 1) {
                $imagenExt = "autorizadosiis.png";
                $n = 1;
            }
            /* if(!empty($var[$i][autorizacion_int]))
              {  $imagenInt="autorizado.png";  }
              else
              {  $imagenInt="no_autorizado.png";  }

              if(!empty($var[$i][autorizacion_ext]))
              {  $imagenExt="autorizado.png";  }
              else
              {  $imagenExt="no_autorizado.png";  } */
            $this->salida .= "       <td><img src=\"" . GetThemePath() . "/images/$imagenInt\"></td>";
            $this->salida .= "       <td><img src=\"" . GetThemePath() . "/images/$imagenExt\"></td>";
            if ($imagenInt == "autorizado.png") {
                $this->salida .= "       <td><a href=\"javascript:ConsultaAutorizacion('DATOS DE LA AUTORIZACION','reports/$VISTA/datosautorizacioncargo.php',1000,250,'" . $var[$i][tarifario_id] . "','" . $var[$i][cargo] . "',$Cuenta," . $var[$i][autorizacion_interna] . ",1,'Int')\"><img src=\"" . GetThemePath() . "/images/informacion.png\" border=\"0\"></a></td>";
            } else {
                $this->salida .= "        <td></td>";
            }
            $this->salida .= "    </tr>";
        }
        if ($i % 2)
            $estilo = 'modulo_list_claro';
        else
            $estilo = 'modulo_list_oscuro';
        $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
        $this->salida .= "        <td colspan=\"3\"><b>TOTALES: </b></td>";
        $this->salida .= "        <td><b>" . FormatoValor($ValTotal) . "</b></td>";
        $this->salida .= "        <td><b>" . FormatoValor($TotalNo) . "</b></td>";
        $this->salida .= "        <td><b>" . FormatoValor($TotalCub) . "</b></td>";
        if ($_SESSION['CUENTAS']['SWCUENTAS'] != 'Cerradas') {
            $col = 6;
        } else {
            $col = 1;
        }
        $this->salida .= "        <td colspan=\"$col\"></td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
        $this->salida .= "        </td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Cama' => $Cama, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></p>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

//---------------------------------FACTURAS AGRUPADAS--------------------------------------


    function FormaFacturasAgrupadas($arr) {
        IncludeLib('funciones_admision');
        IncludeLib('funciones_facturacion');
        $this->salida .= ThemeAbrirTabla('DETALLE FACTURAS AGRUPADAS');
        $this->EncabezadoEmpresa();
        $this->salida .= "<br><table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "        <td>FACTURA</td>";
        $this->salida .= "        <td>No. CUENTA</td>";
        $this->salida .= "        <td>IDENTIFICACION</td>";
        $this->salida .= "        <td>PACIENTE</td>";
        $this->salida .= "        <td>RESPONSABLE</td>";
        $this->salida .= "        <td>PLAN</td>";
        $this->salida .= "        <td>FECHA APERTURA</td>";
        $this->salida .= "        <td>HORA APERTURA</td>";
        $this->salida .= "        <td>TOTAL CUENTA</td>";
        $this->salida .= "        <td></td>";
        $this->salida .= "      </tr>";
        $dat = '';
        for ($i = 0; $i < sizeof($arr); $i++) {
            $datos = BuscarPlanes($arr[$i][plan_id], $arr[$i][ingreso]);
            $Fechas = $this->FechaStamp($arr[$i][fecha]);
            $Horas = $this->HoraStamp($arr[$i][fecha]);
            $accionHRef = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $arr[$i][numerodecuenta], 'TipoId' => $arr[$i][tipo_id_paciente], 'PacienteId' => $arr[$i][paciente_id], 'PlanId' => $arr[$i][plan_id], 'Fecha' => $arr[$i][fecha_registro], 'Ingreso' => $arr[$i][ingreso], 'Estado' => $Estado, 'Nivel' => $arr[$i][rango], 'arreglo' => $arr[$i], 'Agrupada' => true, 'prefijo' => $arr[0][prefijo], 'numero' => $arr[0][factura_fiscal]));
            if ($i % 2) {
                $estilo = 'modulo_list_claro';
            } else {
                $estilo = 'modulo_list_oscuro';
            }
            $this->salida .= "      <tr class=\"$estilo\">";
            $this->salida .= "        <td>" . $arr[$i][prefijo] . "" . $arr[$i][factura_fiscal] . "</td>";
            $this->salida .= "        <td align=\"center\">" . $arr[$i][numerodecuenta] . "</td>";
            $this->salida .= "        <td>" . $arr[$i][tipo_id_paciente] . " " . $arr[$i][paciente_id] . "</td>";
            $this->salida .= "        <td>" . $arr[$i][nombre] . "</td>";
            $this->salida .= "        <td align=\"center\">" . $datos[nombre_tercero] . "</td>";
            $this->salida .= "        <td align=\"center\">" . $datos[plan_descripcion] . "</td>";
            $this->salida .= "        <td align=\"center\">$Fechas</td>";
            $this->salida .= "        <td align=\"center\">$Horas</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($arr[$i][total_cuenta]) . "</td>";
            $this->salida .= "        <td align=\"center\"><a href=\"$accionHRef\">VER</a></td>";
            $this->salida .= "      </tr>";
            $dat[] = array('ingreso' => $arr[$i][ingreso], 'cuenta' => $arr[$i][numerodecuenta]);
        }//fin for
        $this->salida .= " </table><br>";

        $this->conteo = $_SESSION['SPYA'];
        $this->salida .=$this->RetornarBarraAgru();
        $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
        $this->salida .= "    <tr align=\"center\">";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarFacturas');
        $this->salida .= "    <form name=\"formaborrar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"VOLVER\"></td>";
        $this->salida .= "    </form>";
        $prnAnuladas = ($arr[0]['estado'] != '2' && $arr[0]['estado'] != '3') ? "0" : "1";

        $ruta = EncontrarFormatoFactura($_SESSION['FACTURACION']['EMPRESA'], null, 'factura_agrupada');
        $accfactura = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarVentanaFinal', array('numerodecuenta' => $arr[0][numerodecuenta], 'prefijo' => $arr[0][prefijo], 'factura_fiscal' => $arr[0][factura_fiscal], 'plan_id' => $PlanId, 'tipoid' => $TipoId, 'pacienteid' => $PacienteId, 'Nivel' => $Nivel, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Transaccion' => $Transaccion, 'Dev' => $Dev, 'vars' => $vars, 'Estado' => $Estado, 'tipo_factura' => $_REQUEST['tipo_factura'], 'tiporeporte' => 'reportes', 'reporteshojacargos' => $ruta . ',factura_agrupada', "prnAnuladas" => $prnAnuladas));
        $this->salida .= "  <form name=\"formafactura\" action=\"$accfactura\" method=\"post\">";
        $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"IMPRIMIR FACTURA\"></td>";
        $this->salida .= "  </form>";
        if ($arr[0]['estado'] != '2' && $arr[0]['estado'] != '3') {
            $accionT = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaAnularAgrupadas', array('prefijo' => $arr[0][prefijo], 'numero' => $arr[0][factura_fiscal], 'empresa' => $arr[0][empresa_id], 'datos' => $dat));
            $this->salida .= "    <form name=\"formabuscar\" action=\"$accionT\" method=\"post\">";
            $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"ANULAR FACTURA\"></td>";
            $this->salida .= "    </form>";
        }
        $this->salida .= "    </tr>";
        $this->salida .= "    </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function RetornarBarraAgru() {
        if ($this->limit >= $this->conteo) {
            return '';
        }
        $paso = $_REQUEST['paso'];
        if (empty($paso)) {
            $paso = 1;
        }
        $vec = '';
        foreach ($_REQUEST as $v => $v1) {
            if ($v != 'modulo' and $v != 'metodo' and $v != 'SIIS_SID' and $v != 'Of') {
                $vec[$v] = $v1;
            }
        }

        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'DetalleFactura', $vec);
        $barra = $this->CalcularBarra($paso);
        $numpasos = $this->CalcularNumeroPasos($this->conteo);
        $colspan = 1;

        $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if ($paso > 1) {
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofagru=" . $this->CalcularOffset(1) . "&paso=1'>&lt;</a></td>";
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofagru=" . $this->CalcularOffset($paso - 1) . "&paso=" . ($paso - 1) . "'>&lt;&lt;</a></td>";
            $colspan+=1;
        }
        $barra++;
        if (($barra + 10) <= $numpasos) {
            for ($i = ($barra); $i < ($barra + 10); $i++) {
                if ($paso == $i) {
                    $this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
                } else {
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofagru=" . $this->CalcularOffset($i) . "&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofagru=" . $this->CalcularOffset($paso + 1) . "&paso=" . ($paso + 1) . "' >&gt;&gt;</a></td>";
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofagru=" . $this->CalcularOffset($numpasos) . "&paso=$numpasos'>&gt;</a></td>";
            $colspan+=2;
        } else {
            $diferencia = $numpasos - 9;
            if ($diferencia <= 0) {
                $diferencia = 1;
            }
            for ($i = ($diferencia); $i <= $numpasos; $i++) {
                if ($paso == $i) {
                    $this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
                } else {
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofagru=" . $this->CalcularOffset($i) . "&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            if ($paso != $numpasos) {
                $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofagru=" . $this->CalcularOffset($paso + 1) . "&paso=" . ($paso + 1) . "' >&gt;&gt;</a></td>";
                $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofagru=" . $this->CalcularOffset($numpasos) . "&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            } else {
                // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
                //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
            }
        }
        if (($_REQUEST['Ofagru']) == 0 OR ($paso == $numpasos)) {
            if ($numpasos > 10) {
                $valor = 10 + 3;
            } else {
                $valor = $numpasos + 3;
            }
            $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=" . $valor . " align='center'>P�ina $paso de $numpasos</td><tr></table><br>";
        } else {
            if ($numpasos > 10) {
                $valor = 10 + 5;
            } else {
                $valor = $numpasos + 5;
            }
            $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=" . $valor . " align='center'>P�ina $paso de $numpasos</td><tr></table><br>";
        }
    }

//------------------------------------------RIPS-------------------------------------
    /**
     *
     */
    function FormaMenuRips() {
        $this->salida .= ThemeAbrirTabla('MENU GENERACION RIPS');
        $this->salida .= "            <br>";
        $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU RIPS</td>";
        $this->salida .= "               </tr>";
        /* $this->salida .= "               <tr>";
          $accionF=ModuloGetURL('app','Facturacion_Fiscal','user','FormaResponsable',array('accionRips'=>'RipsFacturas'));
          $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionF\">GENERAR RIPS FACTURAS</a></td>";
          $this->salida .= "               </tr>"; */
        $this->salida .= "               <tr>";
        $accionF = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaResponsable', array('accionRips' => 'RipsEnvios'));
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionF\">GENERAR RIPS ENVIOS</a></td>";
        $this->salida .= "               </tr>";
        $this->salida .= "           </table>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaMenus');
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     * @param array $arr Arreglo de datos con los resultados de la busqueda
     * @parma string $rangoS Rangos seleccionados en la busqueda
     *
     */
    function FormaBuscarEnviosRips($arr, $rangoSel) {
        $request = $_REQUEST;
        $this->salida.= ThemeAbrirTabla('BUSQUEDA DE ENVIOS PARA RIPS');
        $this->Todos();
        IncludeLib("tarifario");
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarEnviosRips', array('ripsSoat' => $ripsSoat));
        $this->EncabezadoEmpresa();
        $this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
        $this->salida .= "</tr>";
        $this->salida .= "<tr class=\"modulo_list_claro\" >";
        $this->salida .= "<td width=\"40%\" >";
        $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr><td>";
        $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
        $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
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
        $f = explode(',', $_SESSION['RIPS']['ENVIOS']);
        $this->salida .= "       <tr>\n";
        $this->salida .= "          <td class=\"" . $this->SetStyle("Responsable") . "\">RESPONSABLE: </td>";
        $this->salida .= "          <td class=\"normal_10AN\">" . $f[2] . "</td>\n";
        $this->salida .= "        </tr>\n";
        $this->salida .= "        <tr>\n";
        $this->salida .= "          <td colspan=\"2\" class=\"" . $this->SetStyle("Plan") . "\">PLANES ACTIVOS: </td>\n";
        $this->salida .= "        </tr>\n";
        $this->salida .= "        <tr>\n";
        $this->salida .= "          <td colspan=\"2\">\n";
        $this->salida .= "            <table width=\"100%\" align=\"left\" border=\"0\">";

        $fc = new app_Facturacion_Permisos();

        $rangos = $fc->ObtenerRangosNiveles($f[0], $f[1]);
        $responsables = $this->responsables($f[0], $f[1]);

        for ($j = 0; $j < sizeof($responsables); $j++) {
            $this->salida .= "              <tr class=\"label\">\n";
            $this->salida .= "                <td width=\"1%\">\n";
            $this->salida .= "                  <input type = checkbox name=\"plan" . $responsables[$j][plan_id] . "\" value=\"" . $responsables[$j][plan_id] . "\" " . (($request["plan" . $responsables[$j][plan_id]] == $responsables[$j][plan_id]) ? "checked" : "") . ">\n";
            $this->salida .= "                </td>\n";
            $this->salida .= "                <td class=\"normal_10AN\">" . $responsables[$j][plan_descripcion] . "</td>";
            $this->salida .= "                <td >\n";
            $this->salida .= "                  RANGO : \n";
            $this->salida .= "                  <select name=\"rango_" . $responsables[$j][plan_id] . "\" class=\"select\">\n";
            $this->salida .= "                    <option value=\"-1\">Seleccionar</option>\n";
            $s = "";
            foreach ($rangos[$responsables[$j][plan_id]] as $key => $dtl) {
                ($request["rango_" . $responsables[$j][plan_id]] == $dtl['rango']) ? $s = "selected" : $s = "";
                $this->salida .= "                    <option value=\"" . $dtl['rango'] . "\" " . $s . ">" . $dtl['rango'] . "</option>\n";
            }
            $this->salida .= "                  </select>\n";
            $this->salida .= "                </td>\n";

            $this->salida .= "              </tr>\n";
        }
        $this->salida .= "            </table>";
        $this->salida .= "          </td>\n";
        $this->salida .= "        </tr>\n";
        $inac = $this->Inactivos($f[0], $f[1]);
        if (!empty($inac)) {
            $this->salida .= "       <tr>\n";
            $this->salida .= "          <td colspan=\"2\" class=\"" . $this->SetStyle("Plan") . "\">PLANES INACTIVOS: </td>\n";
            $this->salida .= "        </tr>\n";
            $this->salida .= "        <tr>\n";
            $this->salida .= "          <td>\n";
            $this->salida .= "            <table width=\"100%\" align=\"center\" border=\"0\">";


            for ($i = 0; $i < sizeof($inac); $i++) {
                $this->salida .= "              <tr>\n";
                $this->salida .= "                <td width=\"1%\">\n";
                $this->salida .= "                  <input type = checkbox name= inac" . $inac[$i][plan_id] . " value=\"" . $inac[$i][plan_id] . "\">\n";
                $this->salida .= "                </td>\n";
                $this->salida .= "                <td class=\"label\" width=\"65%\">" . $inac[$i][plan_descripcion] . "</td>";
                $this->salida .= "                <td >\n";
                $this->salida .= "                  RANGO : \n";
                $this->salida .= "                  <select name=\"rango_" . $inac[$i][plan_id] . "\" class=\"select\">\n";
                $this->salida .= "                    <option value=\"-1\">Seleccionar</option>\n";
                foreach ($rangos[$inac[$i][plan_id]] as $key => $dtl)
                    $this->salida .= "                    <option value=\"" . $dtl['rango'] . "\">" . $dtl['rango'] . "</option>\n";

                $this->salida .= "                  </select>\n";
                $this->salida .= "                </td>\n";

                $this->salida .= "              </tr>\n";
            }
            $this->salida .= "            </table>\n";
            $this->salida .= "          </td>\n";
            $this->salida .= "        </tr>\n";
        }
        $this->salida .= "                <tr>";
        $i = $_REQUEST['FechaI'];
        if (!empty($i)) {
            $f = explode('-', $_REQUEST['FechaI']);
            $i = $f[2] . '/' . $f[1] . '/' . $f[0];
        }
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaI") . "\">DESDE: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaI\" value=\"" . $i . "\">" . ReturnOpenCalendario('forma', 'FechaI', '/') . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $fi = $_REQUEST['FechaF'];
        if (!empty($i)) {
            $f = explode('-', $_REQUEST['FechaF']);
            $fi = $f[2] . '/' . $f[1] . '/' . $f[0];
        }
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaF") . "\">HASTA: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaF\" value=\"" . $fi . "\">" . ReturnOpenCalendario('forma', 'FechaF', '/') . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaI") . "\">No. ENVIO: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Envio\"></td>";
        $this->salida .= "                </tr>";
        $this->salida .= "<tr class=\"label\">";
        $this->salida .= "</tr>";
        $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
        $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSQUEDA\"></td>";
        $this->salida .= "</form>";
        $actionM = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaMenus');  //}
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table></td></tr>";
        $this->salida .= "</td></tr></table>";
        $this->salida .= "</table>";
        $this->salida .= "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>";
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        unset($_SESSION['FACTURACION']['ENVIO']['ARREGLO']);
        if (!empty($arr) AND $arr != 'si') {
            $_SESSION['FACTURACION']['ENVIO']['ARREGLO'] = $arr;
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'GenerarRips', array('tiporips' => 'Envio', 'ripsSoat' => $ripsSoat, 'rangos' => $rangoSel));
            $this->salida .= "    <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td width=\"10%\">ENVIO</td>";
            $this->salida .= "        <td width=\"15%\">DESDE</td>";
            $this->salida .= "        <td width=\"15%\">HASTA</td>";
            $this->salida .= "        <td width=\"20%\">RESPONSABLE</td>";
            $this->salida .= "        <td width=\"15%\">RADICACION</td>";
            $this->salida .= "        <td width=\"20%\">USUARIO</td>";
            $this->salida .= "        <td width=\"5%\"></td>";
            $this->salida .= "      </tr>";
            $this->salida .= "      <input type=\"hidden\" name=\"EnvioRips\" id=\"EnvioRips\">";
            for ($i = 0; $i < sizeof($arr); $i++) {
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td align=\"center\">" . $arr[$i][envio_id] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $this->FechaStamp($arr[$i][fecha_inicial]) . "</td>";
                $this->salida .= "        <td align=\"center\">" . $this->FechaStamp($arr[$i][fecha_final]) . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$i][nombre_tercero] . "</td>";
                if (!empty($arr[$i][fecha_radicacion])) {
                    $rad = $this->FechaStamp($arr[$i][fecha_radicacion]);
                } else {
                    $rad = '';
                }
                $this->salida .= "        <td align=\"center\">" . $rad . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$i][nombre] . "</td>";
                //$this->salida .= "            <td align=\"center\"><input type=\"checkbox\" value=\"".$arr[$i][envio_id]."/".$arr[$i][empresa_id]."\" name=\"EnvioRips".$arr[$i][envio_id]."\"></td>";
                //$this->salida .= "            <td align=\"center\"><input type=\"radio\" value=\"".$arr[$i][envio_id]."/".$arr[$i][empresa_id]."\" name=\"EnvioRips\"></td>";
                $this->salida .= "            <td align=\"center\"><input type=\"submit\" value=\"GENERAR RIPS\" class=\"input-submit\" onclick = \"document.getElementById('EnvioRips').value = '" . $arr[$i][envio_id] . "/" . $arr[$i][empresa_id] . "'\" name=\"BtnGenerarRips\"></td>";

                $this->salida .= "      </tr>";
            }
            $this->salida .= "  </table>";
            //$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR RIPS\"></p>";
            $this->salida .= "</form>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaBuscarFacturasRips($arr) {
        $this->salida.= ThemeAbrirTabla('BUSQUEDA DE FACTURAS PARA RIPS');
        $this->Todos();
        IncludeLib("tarifario");
        $this->EncabezadoEmpresa();
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarFacturasRips', array('tiporips' => 'Envio'));
        $this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
        //$this->salida .= "<td align = left >SELECCIONE LA FECHA:</td>";
        $this->salida .= "</tr>";
        $this->salida .= "<tr class=\"modulo_list_claro\" >";
        $this->salida .= "<td width=\"40%\" >";
        $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr><td>";
        $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
        $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
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
        $f = explode(',', $_SESSION['RIPS']['FACTURAS']);
        $this->salida .= "               <tr><td class=\"" . $this->SetStyle("Responsable") . "\">RESPONSABLE: </td>";
        $this->salida .= "              <td class=\"label\">$f[2]</td></tr>";
        $this->salida .= "               <tr><td class=\"" . $this->SetStyle("Plan") . "\">PLANES ACTIVOS: </td><td>";

        $responsables = $this->responsables($f[0], $f[1]);
        $this->salida .= "<BR><table width=\"100%\" align=\"center\" border=\"0\">";
        for ($j = 0; $j < sizeof($responsables); $j++) {
            $this->salida .= "<tr>";
            $this->salida .= "<td class=\"label\" width=\"75%\">" . $responsables[$j][plan_descripcion] . "</td>";
            $this->salida .= "<td><input type = checkbox name= plan" . $responsables[$j][plan_id] . " value=\"" . $responsables[$j][plan_id] . "\"></td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "</table>";
        $this->salida .= "              </td></tr>";
        $inac = $this->Inactivos($f[0], $f[1]);
        if (!empty($inac)) {
            $this->salida .= "               <tr><td class=\"" . $this->SetStyle("Plan") . "\">PLANES INACTIVOS: </td><td>";
            $this->salida .= "<BR><table width=\"100%\" align=\"center\" border=\"0\">";
            for ($i = 0; $i < sizeof($inac); $i++) {
                $this->salida .= "<tr>";
                $this->salida .= "<td class=\"label\" width=\"75%\">" . $inac[$i][plan_descripcion] . "</td>";
                $this->salida .= "<td><input type = checkbox name= inac" . $inac[$i][plan_id] . " value=\"" . $inac[$i][plan_id] . "\"></td>";
                $this->salida .= "</tr>";
            }
            $this->salida .= "</table>";
            $this->salida .= "              </td></tr>";
        }
        /* $this->salida .= "<tr><td class=\"label\">DEPARTAMENTO: </td><td><select name=\"Dpto\" class=\"select\">";
          $departamento=$this->Departamentos();
          $this->BuscarDepartamento($departamento,$d,$_REQUEST['Dpto']);
          $this->salida .= "</select></td></tr>"; */
        $this->salida .= "                <tr>";
        $i = $_REQUEST['FechaI'];
        if (!empty($i)) {
            $f = explode('-', $_REQUEST['FechaI']);
            $i = $f[2] . '/' . $f[1] . '/' . $f[0];
        }
        /* if($arr=='si' OR !empty($arr))
          {  $i=''; } */
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaI") . "\">DESDE: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaI\" value=\"" . $i . "\">" . ReturnOpenCalendario('forma', 'FechaI', '/') . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $fi = $_REQUEST['FechaF'];
        if (!empty($i)) {
            $f = explode('-', $_REQUEST['FechaF']);
            $fi = $f[2] . '/' . $f[1] . '/' . $f[0];
        }
        /* if($arr=='si' OR !empty($arr))
          {  $fi='';  } */
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaF") . "\">HASTA: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaF\" value=\"" . $fi . "\">" . ReturnOpenCalendario('forma', 'FechaF', '/') . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaI") . "\">PREFIJO FACTURA: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Prefijo\"></td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaI") . "\">No. FACTURA: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Numero\"></td>";
        $this->salida .= "                </tr>";
        $this->salida .= "<tr class=\"label\">";
        $this->salida .= "</tr>";
        $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
        $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
        $this->salida .= "</form>";
        $actionM = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaMenus');  //}
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table></td></tr>";
        $this->salida .= "</td></tr></table>";
        $this->salida .= "</table>";
        $this->salida .= "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>";
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        unset($_SESSION['FACTURACION']['ENVIO']['ARREGLO']);
        if (!empty($arr) AND $arr != 'si') {
            $_SESSION['FACTURACION']['ENVIO']['ARREGLO'] = $arr;
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'GenerarRips', array('tiporips' => 'Factura'));
            $this->salida .= "    <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td width=\"10%\">FACTURA</td>";
            $this->salida .= "        <td width=\"10%\">CUENTA</td>";
            //$this->salida .= "        <td width=\"15%\">IDENTIFICACION</td>";
            //$this->salida .= "        <td width=\"25%\">PACIENTE</td>";
            $this->salida .= "        <td width=\"25%\">PLAN</td>";
            $this->salida .= "        <td width=\"17%\">FECHA CIERRE</td>";
            $this->salida .= "        <td width=\"11%\">VALOR</td>";
            $this->salida .= "        <td width=\"8%\"><input type=\"checkbox\" name=\"Todo\" onClick=\"Todos(this.form,this.checked)\"></td>";
            $this->salida .= "      </tr>";
            for ($i = 0; $i < sizeof($arr); $i++) {
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td align=\"center\">" . $arr[$i][prefijo] . " " . $arr[$i][factura_fiscal] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$i][numerodecuenta] . "</td>";
                //$this->salida .= "        <td>".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>";
                //$this->salida .= "        <td>".$arr[$i][nombre]."</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$i][plan_descripcion] . "</td>";
                $this->salida .= "        <td align=\"center\">" . $arr[$i][fecha_registro] . "</td>";
                $this->salida .= "        <td align=\"center\">" . FormatoValor($arr[$i][total_factura]) . "</td>";
                $this->salida .= "        <td align=\"center\"><input type=\"checkbox\" value=\"" . $arr[$i][prefijo] . "/" . $arr[$i][factura_fiscal] . "/" . $arr[$i][plan_id] . "/" . $arr[$i][empresa_id] . "\" name=\"FacturaRips" . $arr[$i][prefijo] . "" . $arr[$i][factura_fiscal] . "\"></td>";
                $this->salida .= "      </tr>";
            }
            $this->salida .= "  </table>";
            $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR RIPS\"></p>";
            $this->salida .= "</form>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

//-------------------------------------------------------------------------------

    function FormaDespachoEnvio() {
        $arr = $_SESSION['DETALLE']['ENVIO'];
        $this->salida .= ThemeAbrirTabla('DESPACHO DE ENVIO No. ' . $arr[0][envio_id]);
        $this->EncabezadoEmpresa();
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'DespacharEnvio', array('envio' => $arr[0][envio_id]));
        $this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <br><table width=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"11%\" class=\"label\">EMPRESA MENSAJERIA: </td>";
        $this->salida .= "     <td width=\"11%\"><input type=\"text\" name=\"mensajeria\" value=\"" . $_REQUEST['mensajeria'] . "\" size=\"40\"></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"11%\" class=\"label\">GUIA: </td>";
        $this->salida .= "     <td width=\"11%\"><input type=\"text\" name=\"guia\" value=\"" . $_REQUEST['guia'] . "\"></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"11%\" class=\"label\">RESPONSABLE: </td>";
        $this->salida .= "     <td width=\"11%\"><input type=\"text\" name=\"responsable\" value=\"" . $_REQUEST['responsable'] . "\" size=\"40\"></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"11%\" class=\"label\">OBSERVACION: </td>";
        $this->salida .= "     <td width=\"11%\"><textarea name=\"observaciones\" cols=\"80\" rows=\"3\" class=\"textarea\">" . $_REQUEST['observaciones'] . "</textarea></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "            <table width=\"60%\" align=\"center\" >";
        $this->salida .= "               <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
        $this->salida .= "           </form>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaDetalleEnvio');
        $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "              <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"VOLVER\"></td></tr>";
        $this->salida .= "           </form>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function FormaEliminarRadicacion($fecha_radicacion, $envio_id) {
        //$arr=$_SESSION['DETALLE']['ENVIO'];
        if (empty($fecha_radicacion) OR empty($envio_id)) {
            $fecha_radicacion = $_REQUEST['fecha_radicacion'];
            $envio_id = $_REQUEST['envio_id'];
        }
        $this->salida .= ThemeAbrirTabla('ELIMINAR RADICACION DEL ENVIO No. ' . $envio_id);
        $this->EncabezadoEmpresa();
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'EliminarRadicacionEnvio', array('envio_id' => $envio_id, 'fecha_radicacion' => $fecha_radicacion));
        $this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <br><table width=\"70%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"100%\" colspan=\"2\" align=\"center\"><label class='label_mark'>AL ELIMINAR LA FECHA DE RADICACI� SE PODRAN ADIONAR FACTURAS AL ENVIO.</label></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"25%\" class=\"label\">FECHA RADICACION: </td>";
        $this->salida .= "     <td width=\"75%\"><input type=\"text\" name=\"responsable\" value=\"" . $fecha_radicacion . "\" size=\"20\" readonly></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"11%\" class=\"" . $this->SetStyle("observaciones") . "\">OBSERVACION&nbsp;&nbsp;*&nbsp;&nbsp;: </td>";
        $this->salida .= "     <td width=\"11%\"><textarea name=\"observaciones\" cols=\"80\" rows=\"3\" class=\"textarea\">" . $_REQUEST['observaciones'] . "</textarea></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "            <table width=\"60%\" align=\"center\" >";
        $this->salida .= "               <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
        $this->salida .= "           </form>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaDetalleEnvio');
        $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "              <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"VOLVER\"></td></tr>";
        $this->salida .= "           </form>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function FormaAplicarDesuentos($envio_id) {
        //$arr=$_SESSION['DETALLE']['ENVIO'];
        if (empty($envio_id)) {
            $envio_id = $_REQUEST['envio_id'];
        }
        $this->salida .= ThemeAbrirTabla('APLICAR DESCUENTO AL ENVIO No. ' . $envio_id, '75%');
        $this->EncabezadoEmpresa();
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'AplicarDescuento', array('envio_id' => $envio_id));
        $this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <br><table width=\"55%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"100%\" colspan=\"2\" align=\"center\"><label class='label_mark'>Porcentaje de Descuento a aplicar sobre el valor total del envio.</label></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"50%\" class=\"label\" align=\"right\">% DESCUENTO: </td>";
        $this->salida .= "     <td width=\"50%\"><input type=\"text\" name=\"Descuento\" value=\"" . $_REQUEST['Descuento'] . "\" size=\"4\"></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "            <table width=\"60%\" align=\"center\" >";
        $this->salida .= "               <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
        $this->salida .= "           </form>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaDetalleEnvio');
        $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "              <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"VOLVER\"></td></tr>";
        $this->salida .= "           </form>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function FormaDatosDespacho() {
        $arr = $_SESSION['DETALLE']['ENVIO'];
        $dat = $this->DatosDespacho($arr[0][envio_id]);
        $this->salida .= ThemeAbrirTabla('DESPACHO DE ENVIO No. ' . $arr[0][envio_id]);
        $this->EncabezadoEmpresa();
        $this->salida .= "  <br><table width=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"11%\" class=\"label\">FECHA: </td>";
        $this->salida .= "     <td width=\"11%\">" . $this->FechaStamp($dat['fecha_registro']) . "&nbsp;  " . $this->HoraStamp($dat['fecha_registro']) . "</td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"11%\" class=\"label\">USUARIO: </td>";
        $this->salida .= "     <td width=\"11%\">" . $dat['nombre'] . "</td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"11%\" class=\"label\">EMPRESA MENSAJERIA: </td>";
        $this->salida .= "     <td width=\"11%\">" . $dat['empresa_mensajeria'] . "</td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"11%\" class=\"label\">GUIA: </td>";
        $this->salida .= "     <td width=\"11%\">" . $dat['guia'] . "</td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"11%\" class=\"label\">RESPONSABLE: </td>";
        $this->salida .= "     <td width=\"11%\">" . $dat['responsable'] . "</td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "     <td width=\"11%\" class=\"label\">OBSERVACION: </td>";
        $this->salida .= "     <td width=\"11%\"><textarea name=\"observaciones\" cols=\"80\" rows=\"3\" class=\"textarea\" readonly>" . $dat['observacion'] . "</textarea></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "            <table width=\"60%\" align=\"center\" >";
        $this->salida .= "               <tr>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaDetalleEnvio');
        $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "              <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"VOLVER\"></td></tr>";
        $this->salida .= "           </form>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function ComboConceptos() {
        $this->salida .= "<SCRIPT>\n";
        $this->salida .= "function ComboConceptos(valor,forma){\n";
        $this->salida .= "  if(valor!=-1){;\n";
        $this->salida .= "     forma.concepto.value=valor;\n";
        $this->salida .= "  }\n";
        $this->salida .= "}\n";
        $this->salida .= "</SCRIPT>\n";
    }

    /**
     *
     */
    function FormaConceptoFactura($plan, $valor, $fechaI, $fechaF, $cantidad, $CU, $dpto, $filtroFecha, $filtroDocumento, $rango) {
        if (!$valor AND !$cantidad AND $_SESSION['FACTURACION']['SELECCION']['AGRUPADA']['TOTALES']) {
            $dat = explode(',', $_SESSION['FACTURACION']['SELECCION']['AGRUPADA']['TOTALES']);
            $valor = $dat[0];
            $cantidad = $dat[1];
        }

        $this->ComboConceptos();
        $permisos = SessionGetVar('FACTURACION');
        $this->salida .= ThemeAbrirTabla('FACTURACION AGRUPADA');
        IncludeLib('funciones_facturacion');

        $planes = array();
        $tipo_plan = 0;
        $d_tercero = array();
        foreach ($plan as $k => $id) {
            $planes[$k] = DatosPlan($k);
            if ($tipo_plan != "3")
                $tipo_plan = $planes[$k]['sw_tipo_plan'];

            $d_tercero = $this->ObtenerTercerosPlanesAgrupados($permisos['EMPRESA'], $k);
        }

        if (!empty($fechaI) OR !empty($fechaF)) {
            $msg = 'FACTURADO EN UN PERIODO';
            if (!empty($fechaI))
                $msg .= '&nbsp;&nbsp;DESDE  &nbsp;' . $fechaI;
            if (!empty($fechaF))
                $msg .= '&nbsp;&nbsp;HASTA  &nbsp;' . $fechaF;
        }
        //mensaje
        $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">\n";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>\n";

        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'CrearFacturaAgrupadas', array('plan' => $plan, 'valor' => $valor, 'fechaI' => $fechaI, 'fechaF' => $fechaF, 'cantidad' => $cantidad, 'CU' => $CU, 'dpto' => $dpto, 'filtroFecha' => $filtroFecha, 'filtroDocumento' => $filtroDocumento, 'rango' => $rango));
        $ctl = AutoCarga::factory("ClaseUtil");

        $this->salida .= $ctl->AcceptNum();
        $this->salida .= $ctl->IsNumeric();
        $this->salida .= "<script>\n";
        $this->salida .= "  function ValidarDatos(frm)\n";
        $this->salida .= "  {\n";
        $this->salida .= "    msg ='';\n";
        if ($tipo_plan == 3) {
            $this->salida .= "    if(frm.concepto.value == '')\n";
            $this->salida .= "      msg = 'DEBE INDICAR EL CONCEPTO DE LA FACTURA';\n";
            $this->salida .= "    else if(!IsNumeric(frm.valorC.value))\n";
            $this->salida .= "      msg = 'EL FORMATO DE VALOR CONCEPTO DE LA FACTURA, ES INCORRECTO O ESTA VACIO';\n";
        }
        $this->salida .= "    if(msg != '')\n";
        $this->salida .= "      document.getElementById('error').innerHTML= msg;\n";
        $this->salida .= "    else\n";
        $this->salida .= "    {\n";
        $this->salida .= "      frm.action = \"" . $accion . "\";\n";
        $this->salida .= "      frm.submit();\n";
        $this->salida .= "    }\n";
        $this->salida .= "  }\n";
        $this->salida .= "</script>\n";
        $this->salida .= "<center>\n";
        $this->salida .= "  <div id=\"error\" class=\"label_error\"></div>\n";
        $this->salida .= "</center>\n";
        $this->salida .= "<form name=\"forma\" action=\"javascript:ValidarDatos(document.forma)\" method=\"post\">\n";
        $this->salida .= "  <input type=\"hidden\" name=\"tipoPlan\" value=\"" . $tipo_plan . "\">\n";
        $this->salida .= "  <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\">\n";


        $this->salida .= "    <tr>\n";
        $this->salida .= "      <td>\n";
        $this->salida .= "        <table width=\"100%\" class=\"modulo_table_list\">\n";
        $this->salida .= "          <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "            <td colspan=\"2\">CLIENTE</td>\n";
        $this->salida .= "          </tr>\n";
        $this->salida .= "          <tr class=\"normal_10AN\">\n";
        $this->salida .= "            <td width=\"30%\">" . $d_tercero[0]['tipo_tercero_id'] . " " . $d_tercero[0]['tercero_id'] . "</td>\n";
        $this->salida .= "            <td width=\"70%\">" . $d_tercero[0]['nombre_tercero'] . "</td>\n";
        $this->salida .= "          </tr>\n";
        $this->salida .= "        </table>\n";
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "      <td>\n";
        $this->salida .= "        <fieldset class=\"fieldset\">\n";
        $this->salida .= "          <legend>PLANES</legend>\n";
        $this->salida .= "          <ul>\n";
        foreach ($planes as $k => $dtl)
            $this->salida .= "            <li>" . $dtl['plan_descripcion'] . "</li>\n";
        $this->salida .= "          </ul>\n";

        $this->salida .= "        </fieldset>\n";
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "      <td>\n";
        $this->salida .= "        <table width=\"100%\" class=\"modulo_table_list\">\n";
        $this->salida .= "          <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "            <td width=\"70%\">DETALLE</td>\n";
        $this->salida .= "            <td width=\"30%\">VALOR</td>\n";
        $this->salida .= "          </tr>\n";
        $this->salida .= "          <tr class=\"label_mark\">\n";
        $this->salida .= "            <td >SE ESTAN FACTURANDO " . $cantidad . " CUENTAS</td>\n";
        $this->salida .= "            <td align=\"right\">$ " . FormatoValor($valor) . "</td>\n";
        $this->salida .= "          </tr>\n";
        if (!empty($msg)) {
            $this->salida .= "          <tr align=\"center\" class=\"label_mark\">\n";
            $this->salida .= "            <td colspan=\"2\">" . $msg . "</td>";
            $this->salida .= "          </tr>\n";
        }
        $this->salida .= "        </table>\n";
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";


        $this->salida .= "    <tr>\n";
        $this->salida .= "      <td>\n";
        $this->salida .= "        <table width=\"100%\" class=\"modulo_table_list\">\n";
        $var = $this->ConceptoFacturaAgrupada();
        if (!empty($var)) {
            $this->salida .= "          <tr class=\"label_mark\">\n";
            $this->salida .= "            <td align=\"left\" class=\"formulacion_table_list\">TIPO CONCEPTO:</td>\n";
            $this->salida .= "            <td >\n";
            $this->salida .= "              <select name=\"tipoConcepto\" class=\"select\" onchange=\"ComboConceptos(this.value,this.form)\">\n";
            $this->salida .= "                <option value=\"-1\">-------SELECCIONE-------</option>\n";
            for ($i = 0; $i < sizeof($var); $i++)
                $this->salida .= "                <option value=\"" . $var[$i][concepto] . "\">" . $var[$i][concepto] . "</option>\n";

            $this->salida .= "              </select>\n";
            $this->salida .= "            </td>\n";
            $this->salida .= "          </tr>\n";
        }
        $this->salida .= "          <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "            <td colspan=\"2\">CONCEPTO</td>\n";
        $this->salida .= "          </tr>\n";
        $this->salida .= "          <tr>\n";
        $this->salida .= "            <td colspan=\"2\">\n";
        $this->salida .= "              <textarea style=\"width:100%\" rows=\"3\" class=\"textarea\"name=\"concepto\">" . $_REQUEST['concepto'] . "</textarea>\n";
        $this->salida .= "            </td>\n";
        $this->salida .= "          </tr>\n";
        $this->salida .= "        </table>\n";
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";



        if ($tipo_plan == 3) {
            $this->salida .= "    <tr>\n";
            $this->salida .= "      <td>\n";
            $this->salida .= "        <table width=\"100%\" class=\"modulo_table_list\">\n";
            $this->salida .= "          <tr class=\"label_mark\">\n";
            $this->salida .= "            <td class=\"formulacion_table_list\">VALOR:</td>\n";
            $this->salida .= "            <td >\n";
            $this->salida .= "              <input type=\"text\" class=\"input-text\" name=\"valorC\" size=\"12\" onkeyPress=\"return acceptNum(event)\" value=\"" . $_REQUEST['valorC'] . "\">\n";
            $this->salida .= "            </td>\n";
            $this->salida .= "          </tr>\n";
            $this->salida .= "        </table>\n";
            $this->salida .= "      </td>\n";
            $this->salida .= "    </tr>\n";
        }

        //$this->salida .= "  </table>\n";
        $this->salida .= "  <br>\n";
        $this->salida .= "            <table width=\"30%\" align=\"center\" >";
        $this->salida .= "               <tr>";
        $this->salida .= "              <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
        $this->salida .= "           </form>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarFormaBuscarAgrupadas');
        $this->salida .= "             <form name=\"formabuscar1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "              <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"></td>";
        $this->salida .= "           </form>";
        $this->salida .= "              </tr>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     * Forma para la impresion de los envios generados
     *
     * @param string titulo(titulo)
     * @param array arr
     */
    function FormaImpresionEnvio($titulo, $arr) {
        $reporte = new GetReports();
        $this->salida .= ThemeAbrirTabla("ENVIOS REALIZADOS", "90%");
        $this->EncabezadoEmpresa();
        $this->salida .= "<br>";
        $this->salida .= "<table width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
        $this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "		<th>NO. ENVIO</th>\n";
        $this->salida .= "		<th>PLAN</th>\n";
        $this->salida .= "		<th>NO. FACTURAS</th>\n";
        $this->salida .= "		<th>TOTAL</th>\n";
        $this->salida .= "		<th>OPCIONES</th>\n";
        $this->salida .= "	</tr>\n";
        $i = 0;
        $tipo_reporte = $this->ConsultaTipoReporte($arr, 'ENVIO_FACTURA');
        foreach ($arr as $envio_id => $datos_envio) {
            ($i % 2 == 0) ? $clase = "modulo_list_claro" : $clase = "modulo_list_oscuro";
            $i++;
            $mostrar = $reporte->GetJavaReport('app', 'Facturacion_Fiscal', 'enviosHTM', array('envio' => $envio_id, 'tipo_reporte' => $tipo_reporte), array('rpt_name' => 'envio', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $funcion = $reporte->GetJavaFunction();
            $this->salida .=$mostrar;
            //$accion = ModuloGetURL("app","Facturacion_Fiscal","user","DescargarRipsEnvio",array("tiporips"=>"Envio","EnvioRips"=>$envio_id."/".$_SESSION['FACTURACION']['EMPRESA'],"download"=>"1"));
            $this->salida .= "	<tr class=\"$clase\">\n";
            $this->salida .= "		<td align=\"left\">" . $envio_id . "</td>\n";
            $this->salida .= "		<td align=\"left\">" . $datos_envio["plan_descripcion"] . "</td>\n";
            $this->salida .= "		<td align=\"center\">" . $datos_envio["cantidad_facturas"] . "</td>\n";
            $this->salida .= "		<td align=\"right\">" . FormatoValor($datos_envio["total_envio"]) . "</td>\n";
            $this->salida .= "		<td align=\"center\">";
            $this->salida .= "			<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"0\">";
            $this->salida .= "				<tr class=\"$clase\" >\n";
//			$this->salida .= "					<td width=\"50%\" align=\"center\">";
//			$this->salida .= "						<a href=\"javascript:$funcion\" title=\"Imprimir Envio\"><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\"></a>";
//			$this->salida .= "					</td>";
            //
				$js = "<script>";
            $js .= "function accion(frm){";
            //$js .= "  frm.action = '$accion';";
            //$js .= "alert(frm.action);";
            $js .= "	frm.submit();";
            $js .= "}";
            $js .= "</script>";
            $this->salida.="$js";
            $tiposReportes = $this->GetDatosTipoReporte();
            $accion = ModuloGetURL("app", "Facturacion_Fiscal", "user", "LlamaFrmVerReporteEnvio", array('envio' => $envio_id));
            $this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\" target=\"_blank\">";
            $this->salida.="	<td align=\"center\" class=\"NORMAL_10N\" colspan=\"3\">Tipo de reporte";
            $this->salida.="		<select size = 1 name = 'tiporeporte'  class =\"select\">";
            foreach ($tiposReportes AS $i => $v) {
                $this->salida.="	<option value = \"$v[reportes_envios_id]\">$v[tipo_reporte]</option>";
            }
            $this->salida.="		</select>";
            $this->salida.="		<a href=\"javascript:accion(document.forma);\">VER</a>";
            $this->salida.="	</td>";
            $this->salida.="</form>";
            //

            $accion = ModuloGetURL("app", "Facturacion_Fiscal", "user", "DescargarRipsEnvio", array("tiporips" => "Envio", "EnvioRips" => $envio_id . "/" . $_SESSION['FACTURACION']['EMPRESA'], "download" => "1"));
            $this->salida .= "					<form name=\"frmDescargarRips$envio_id\" action=\"$accion\" method=\"post\" target=\"_blank\">";
            $this->salida .= "						<td width=\"50%\" align=\"center\">";
            $this->salida .= "							<input type=\"image\" src=\"" . GetThemePath() . "/images/abajo.png\" name=\"btnDescargarRips\" value=\"DESCARGAR RIPS\"  class=\"input-submit\" title=\"Descargar Rips\">";
            $this->salida .= "						</td>";
            $this->salida .= "					</form>";
            $this->salida .= "				</tr>";
            $this->salida .= "			</table>";
            $this->salida .= "		</td>";
            $this->salida .= "	</tr>";
        }
        $this->salida .= "</table>";
        $this->salida .= "<br>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarEnvios');
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "	<div align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"VOLVER\"></div>";
        $this->salida .= "</form>";
        unset($reporte);
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

//Fin FormaImpresionEnvio
//--------------habitaciones

    function FormaHabitaciones($hab, $Plan, $cuenta, $TipoId, $PacienteId, $Nivel, $Cama, $Fecha, $Ingreso) {
        unset($_SESSION['CUENTAS']['MOVIMIENTOS']);
        $this->SetJavaScripts('DetalleCamas');
        $accion = ModuloGetURL('app', 'Facturacion', 'user', 'CargarHabitacion', array('Cuenta' => $cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $Plan, 'Cama' => $Cama, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<table border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"90%\" align=\"center\"  class=\"modulo_table_list\">";
        $this->salida .= "    <tr align=\"center\" class=\"modulo_table_title\">";
        $this->salida .= "    <td colspan=\"6\">HABITACIONES</td>";
        $this->salida .= "    </tr>";
        if (!empty($hab)) {
            $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "     <td width=\"8%\">TARIF.</td>";
            $this->salida .= "     <td width=\"8%\">CARGO</td>";
            $this->salida .= "     <td width=\"60%\">DESCRIPCION</td>";
            $this->salida .= "     <td width=\"8%\">PRECIO</td>";
            $this->salida .= "     <td width=\"8%\">CANTIDAD</td>";
            $this->salida .= "     <td width=\"8%\">TOTAL</td>";
            //$this->salida .= "     <td width=\"4%\"></td>";
            $this->salida .= "    </tr>";
            $total = 0;
            for ($i = 0; $i < sizeof($hab); $i++) {
                if ($i % 2)
                    $estilo = 'modulo_list_claro';
                else
                    $estilo = 'modulo_list_oscuro';
                $this->salida .= "    <tr class=\"$estilo\">";
                $this->salida .= "     <td align=\"center\">" . $hab[$i][tarifario_id] . "</td>";
                $this->salida .= "     <td align=\"center\">" . $hab[$i][cargo] . "</td>";
                $this->salida .= "     <td>" . $hab[$i][descripcion] . "</td>";
                $this->salida .= "     <td align=\"center\">" . $hab[$i][precio_plan] . "</td>";
                $this->salida .= "     <td align=\"center\">" . $hab[$i][cantidad] . "</td>";
                $this->salida .= "     <td align=\"center\">" . $hab[$i][valor_cargo] . "</td>";
                //$this->salida .= "     <td align=\"center\"><input type=\"checkbox\" name=\"HAB$i\" value=\"".$i."\"></td>";
                $this->salida .= "    </tr>";
                $total +=$hab[$i][valor_cargo];
            }
            $this->salida .= "    <tr align=\"center\">";
            $this->salida .= "    <td colspan=\"5\" align=\"right\" class=\"label\">TOTAL ESTANCIA:</td>";
            $this->salida .= "    <td colspan=\"1\" align=\"right\" class=\"label\">" . FormatoValor($total) . "</td>";
            $this->salida .= "    </tr>";
        }
        $this->salida .= "    <tr align=\"center\">";
        $camasMov = RetornarWinOpenDetalleCamas($Ingreso, $cuenta, 'VER DETALLE DE MOVIMIENTOS', 'label');
        $this->salida .= "    <td colspan=\"3\" align=\"center\" class=\"label\">$camasMov</td>";
        $egreso = $this->ValidarEgresoPaciente($Ingreso);
        if (!empty($egreso)) {
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarFormaLiquidacionManualHabitaciones', array('Cuenta' => $cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $Plan, 'Cama' => $Cama, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso));
            $this->salida .= "    <td colspan=\"3\" align=\"left\" class=\"label\"><a href=\"$accion\">LIQUIDACION MANUAL</a></td>";
            $this->salida .= "    </tr>";
            $this->salida .= "    <tr>";
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamadoCargarHabitacionCuenta', array("volverDetalle" => 1, 'Cuenta' => $cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $Plan, 'Cama' => $Cama, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso));
            $this->salida .= "    <td colspan=\"3\" align=\"center\" class=\"label\"><a href=\"$accion\">CARGAR A LA CUENTA</a></td><td colspan=\"3\"><BR>&nbsp;</td>";
            $this->salida .= "</form>";
        } else {
            $this->salida .= "  <td colspan=\"3\" align=\"center\" class=\"label_mark\">EL PACIENTE NO TIENE ORDEN DE SALIDA DE LA ESTACION</td>";
        }
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
    }

    function PasarValor() {
        $this->salida .= "<script>\n";
        $this->salida .= "		function PasarValor(forma)\n";
        $this->salida .= "		{\n";
        $this->salida .= "        var v;\n";
        $this->salida .= "        var vect;\n";
        $this->salida .= "			  v=forma.tipocama.value;\n";
        $this->salida .= "			  a=v.split('||');\n";
        $this->salida .= "				forma.excedenteN.value = a[0];\n";
        $this->salida .= "        if(a[1] > 0){\n";
        $this->salida .= "				  forma.precioN.value = a[1]; \n";
        $this->salida .= "        }\n";
        $this->salida .= "        else{\n";
        $this->salida .= "				  forma.precioN.value = (parseInt(a[2]) + (a[2]*a[3]/100)); \n";
        $this->salida .= "        }\n";
        $this->salida .= "		}\n";
        $this->salida .= "</script>\n";
    }

    function FormaLiquidacionManualHabitaciones($x) {
        $this->PasarValor();
        $this->SetJavaScripts('DetalleCamas');
        $this->salida .= ThemeAbrirTabla('LIQUIDACION MANUAL DE HABITACIONES CUENTA No. ' . $_SESSION['FACTURACION']['VARIABLES']['Cuenta']);
        $this->salida .= "            <table width=\"60%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "           </table>";
        $liq = $_SESSION['CUENTAS']['CAMA']['LIQ'];
        if (!empty($liq)) {
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'ModificarCargoHabitacionVector');
            $this->salida .= " <form name=\"formainicial\" action=\"$accion\" method=\"post\">";
            $this->salida .= "  <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"95%\" align=\"center\"  class=\"modulo_table_list_title\">";
            $this->salida .= "   <tr>";
            $this->salida .= "    <td width=\"6%\">Tarif.</td>";
            $this->salida .= "    <td width=\"6%\">Cargo</td>";
            $this->salida .= "    <td width=\"50%\" nowrap>Descripci�</td>";
            $this->salida .= "    <td width=\"15%\">Precio Uni.</td>";
            $this->salida .= "    <td width=\"6%\">D�s</td>";
            $this->salida .= "    <td width=\"20%\">Val. No Cub. Uni.(Excedente)</td>";
            $this->salida .= "    <td width=\"10%\">Total Val. Cub</td>";
            $this->salida .= "    <td width=\"10%\">Total Val. No Cub</td>";
            //$this->salida .= "    <td width=\"10%\">Excedente</td>";																
            $this->salida .= "    <td width=\"2%\"></td>";
            $this->salida .= "   </tr>";
            for ($i = 0; $i < sizeof($liq); $i++) {
                //---validacion si cambiaron lo del vector
                if (empty($_REQUEST['precio_plan' . $i])) {
                    $_REQUEST['precio_plan' . $i] = $liq[$i]['precio_plan'];
                }
                if (empty($_REQUEST['dias' . $i])) {
                    $_REQUEST['dias' . $i] = $liq[$i]['cantidad'];
                }
                if (empty($_REQUEST['noCub' . $i])) {
                    $_REQUEST['noCub' . $i] = $liq[$i]['valor_no_cubierto'];
                }
                if (empty($_REQUEST['cub' . $i])) {
                    $_REQUEST['cub' . $i] = $liq[$i]['valor_cubierto'];
                }
                if (empty($_REQUEST['excedente' . $i])) {
                    $_REQUEST['excedente' . $i] = $liq[$i]['excedente'];
                }

                $this->salida .= "   <tr class=\"modulo_list_claro\">";
                $this->salida .= "    <td align=\"center\">" . $liq[$i]['tarifario_id'] . "</td>";
                $this->salida .= "    <td align=\"center\">" . $liq[$i]['cargo'] . "</td>";
                if ($i === $x) {
                    $this->salida .= "    <td align=\"left\" class=\"label_error\">" . $liq[$i]['descripcion'] . "</td>";
                } else {
                    $this->salida .= "    <td align=\"left\">" . $liq[$i]['descripcion'] . "</td>";
                }
                $this->salida .= "    <td align=\"center\">$&nbsp;&nbsp;<input type=\"text\" class=\"input-text\" name=\"precio_plan$i\" size=\"10\" value=\"" . $_REQUEST['precio_plan' . $i] . "\" align=\"right\"></td>";
                //$this->salida .= "    <td align=\"center\">$ ".FormatoValor($liq[$i]['precio_plan'])."</td>";			
                $this->salida .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"dias$i\" size=\"3\" value=\"" . $_REQUEST['dias' . $i] . "\" align=\"center\"></td>";
                $this->salida .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"excedente$i\" size=\"10\" value=\"" . $_REQUEST['excedente' . $i] . "\" align=\"right\"></td>";
                $this->salida .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"cub$i\" readonly size=\"10\" value=\"" . $_REQUEST['cub' . $i] . "\" align=\"right\"></td>";
                $this->salida .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"noCub$i\" readonly size=\"10\" value=\"" . $_REQUEST['noCub' . $i] . "\" align=\"right\"></td>";
                //$this->salida .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"excedente$i\" size=\"10\" value=\"".$_REQUEST['excedente'.$i]."\" align=\"right\"></td>";			
                $accionE = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'EliminarCargoHabitacionVector', array('posicion' => $i));
                $this->salida .= "    <td align=\"center\"><a href=\"$accionE\"><img src=\"" . GetThemePath() . "/images/elimina.png\" border='0' title=\"Eliminar Cargo\"></a></td>";
                $this->salida .= "   </tr>";
            }
            $this->salida .= "   <tr class=\"modulo_list_claro\">";
            $this->salida .= "     <td colspan=\"9\" align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Adicionar\" value=\"MODIFICAR CARGOS\"></td>";
            $this->salida .= "   </tr>";
            $this->salida .= "  </table>";
            $this->salida .= " </form>";
        }

        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'InsertarCargoHabitacionVector');
        $this->salida .= "            <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<br><br><table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\"  class=\"modulo_table_list_title\">";
        $this->salida .= "   <tr>";
        $this->salida .= "   <td colspan=\"7\" class=\"modulo_table_title\">ADICIONAR HABITACION</td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr>";
        $this->salida .= "    <td>Tipo Cama</td>";
        $this->salida .= "    <td width=\"10%\">Precio Uni.</td>";
        $this->salida .= "    <td width=\"6%\">D�s</td>";
        //$this->salida .= "    <td width=\"10%\">Val. Cub. Uni.</td>";					
        $this->salida .= "    <td width=\"12%\">Val. No Cub. Uni. (Excedente)</td>";
        //$this->salida .= "    <td width=\"10%\">Excedente</td>";																
        $this->salida .= "    <td width=\"2%\">Copago</td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr class=\"modulo_list_claro\">";
        $this->salida .= "     <td align=\"center\"><select name=\"tipocama\" class=\"select\" onChange=\"PasarValor(document.forma)\">";
        $cons = $this->TiposCamasPlan($_SESSION['FACTURACION']['VARIABLES']['PlanId']);
        $this->salida .=" <option value=\"\">---Seleccione---</option>";
        for ($i = 0; $i < sizeof($cons); $i++) {
            if ($_REQUEST['tipocama'] == $cons[$i][tipo_cama_id]) {
                $this->salida .=" <option value=\"" . $cons[$i][valor_excedente] . "||" . $cons[$i][valor_lista] . "||" . $cons[$i][precio] . "||" . $cons[$i][porcentaje] . "||" . $cons[$i][tipo_cama_id] . "||" . $cons[$i][tarifario_id] . "||" . $cons[$i][cargo] . "||" . $cons[$i][cargo_cups] . "||" . $cons[$i][descar] . "\" selected>" . $cons[$i][descripcion] . "</option>";
            } else {
                $this->salida .=" <option value=\"" . $cons[$i][valor_excedente] . "||" . $cons[$i][valor_lista] . "||" . $cons[$i][precio] . "||" . $cons[$i][porcentaje] . "||" . $cons[$i][tipo_cama_id] . "||" . $cons[$i][tarifario_id] . "||" . $cons[$i][cargo] . "||" . $cons[$i][cargo_cups] . "||" . $cons[$i][descar] . "\">" . $cons[$i][descripcion] . "</option>";
            }
        }
        $this->salida .= "   </select></td>";
        $this->salida .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"precioN\" size=\"10\" value=\"" . $_REQUEST['precioN'] . "\" align=\"center\"></td>";
        $this->salida .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"diasN\" size=\"3\" value=\"" . $_REQUEST['diasN'] . "\" align=\"center\"></td>";
        //$this->salida .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"cubN\" size=\"10\" value=\"".$_REQUEST['cubN']."\" align=\"right\"></td>";			
        $this->salida .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"noCubN\" size=\"10\" value=\"" . $_REQUEST['noCubN'] . "\" align=\"right\"></td>";
        //$this->salida .= "    <td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"excedenteN\" size=\"10\" value=\"".$_REQUEST['excedenteN']."\" align=\"right\"></td>";		
        $this->salida .= "    <td><input type=\"checkbox\" name=\"copago\" value=\"1\"></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "   <tr class=\"modulo_list_claro\">";
        $this->salida .= "     <td align=\"left\">Departamento: &nbsp;&nbsp;<select name=\"dpto\" class=\"select\">";
        $cons = $this->DepartamentosHabitaciones();
        $this->salida .=" <option value=\"\">---Seleccione---</option>";
        for ($i = 0; $i < sizeof($cons); $i++) {
            if ($_REQUEST['dpto'] == $cons[$i][departamento]) {
                $this->salida .=" <option value=\"" . $cons[$i][departamento] . "||" . $cons[$i][servicio] . "\" selected>" . $cons[$i][descripcion] . "</option>";
            } else {
                $this->salida .=" <option value=\"" . $cons[$i][departamento] . "||" . $cons[$i][servicio] . "\">" . $cons[$i][descripcion] . "</option>";
            }
        }
        $this->salida .= "   </select></td>";

        $this->salida .= "     <td colspan=\"6\" align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Adicionar\" value=\"ADICIONAR CARGO\"></td>";
        $this->salida .= "   </tr>";
        $this->salida .= "  </table>";
        $this->salida .= " </form>";
        $this->salida .= "<br><br><table border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"50%\" align=\"center\"  class=\"normal_10\">";
        $this->salida .= "    <tr align=\"center\">";
        $camasMov = RetornarWinOpenDetalleCamas($_SESSION['FACTURACION']['VARIABLES']['Ingreso'], 'DETALLE DE MOVIMIENTOS', 'label');
        $this->salida .= "    <td colspan=\"7\" align=\"center\" class=\"label\">$camasMov</td>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamadoCargarHabitacionCuenta');
        $this->salida .= "            <form name=\"forma1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "    <td colspan=\"2\" align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CARGAR A LA CUENTA\"></td>";
        $this->salida .= "</form>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'VolverDetalle');
        $this->salida .= "            <form name=\"forma2\" action=\"$accion\" method=\"post\">";
        $this->salida .= "    <td colspan=\"2\" align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
        $this->salida .= "</form>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

//---------------------CIRUGIA-------------------
    function FormaDetalleCirugia($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Cama, $Fecha, $Ingreso, $var, $med, $desc, $codigo, $qx) {
        $Nombres = $this->BuscarNombreCompletoPaciente($TipoId, $PacienteId);
        $this->salida .= ThemeAbrirTabla('DETALLES DE LA CUENTA No. ' . $Cuenta . ' ' . $Nombres);
        $this->ConsultaAutorizacion();
        $this->EncabezadoEmpresa($Caja);
        $argu = array('Transaccion' => $Transaccion, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso);

        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $Fecha, 'Cuenta' => $Cuenta));
        $_SESSION['CUENTAS']['EMPRESA'] = $_SESSION['FACTURACION']['EMPRESA'];
        $_SESSION['CUENTAS']['CENTROUTILIDAD'] = $_SESSION['FACTURACION']['CENTROUTILIDAD'];
        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamaTotalesCuenta', array('Cuenta' => $Cuenta));
        $this->salida .= "  </fieldset></td></tr></table><BR>";
        $this->salida .= " <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"98%\" align=\"center\">";
        $this->salida .= "    <tr class=\"modulo_table_title\">";
        $this->salida .= "        <td>DETALLE DE " . $desc . "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td>";
        //------------detalle cargos cirugia
        $this->salida .= " <br><table border=\"1\" cellspacing=\"2\" cellpadding=\"2\" width=\"98%\" align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "    <tr>";
        $this->salida .= "        <td width=\"6%\">TARIF.</td>";
        $this->salida .= "        <td width=\"6%\">CARGO</td>";
        $this->salida .= "        <td width=\"50%\">DESCRIPCION</td>";
        $this->salida .= "        <td width=\"9%\">VALOR</td>";
        $this->salida .= "        <td width=\"9%\">VAL. NO CUBIERTO</td>";
        $this->salida .= "        <td width=\"9%\">VAL. CUBIERTO</td>";
        $this->salida .= "        <td width=\"2%\">INT</td>";
        $this->salida .= "        <td width=\"2%\">EXT</td>";
        $this->salida .= "    </tr>";
        $valor = $cub = $nocub = 0;
        for ($i = 0; $i < sizeof($var); $i++) {
            if ($i % 2)
                $estilo = 'modulo_list_claro';
            else
                $estilo = 'modulo_list_oscuro';
            $valor +=$var[$i][precio];
            $cub +=$var[$i][valor_cubierto];
            $nocub +=$var[$i][valor_nocubierto];
            $this->salida .= "            <tr class=\"$estilo\">";
            $this->salida .= "        <td align=\"center\">" . $var[$i][tarifario_id] . "</td>";
            $this->salida .= "        <td align=\"center\">" . $var[$i][cargo] . "</td>";
            $this->salida .= "        <td>" . $var[$i][descripcion] . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($var[$i][valor_cargo]) . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($var[$i][valor_nocubierto]) . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($var[$i][valor_cubierto]) . "</td>";
            $imagenInt = $imagenExt = '';
            if ($var[$i][autorizacion_int] === '0') {
                $imagenInt = "no_autorizado.png";
                $D = 1;
            } elseif ($var[$i][autorizacion_int] > 100) {
                $imagenInt = "autorizado.png";
                $D = 0;
            } elseif ($var[$i][autorizacion_int] == 1) {
                $imagenInt = "autorizadosiis.png";
                $D = 1;
            }

            if ($var[$i][autorizacion_ext] === '0') {
                $imagenExt = "no_autorizado.png";
                $n = 1;
            } elseif ($var[$i][autorizacion_ext] > 100) {
                $imagenExt = "autorizado.png";
                $n = 0;
            } elseif ($var[$i][autorizacion_ext] == 1) {
                $imagenExt = "autorizadosiis.png";
                $n = 1;
            }
            if (!empty($imagenInt)) {
                $this->salida .= "       <td><img src=\"" . GetThemePath() . "/images/$imagenInt\"></td>";
            } else {
                $this->salida .= "       <td></td>";
            }
            if (!empty($imagenExt)) {
                $this->salida .= "       <td><img src=\"" . GetThemePath() . "/images/$imagenExt\"></td>";
            } else {
                $this->salida .= "       <td></td>";
            }
            /* if($imagenInt=="autorizado.png")
              {  $this->salida .= "       <td><a href=\"javascript:ConsultaAutorizacion('DATOS DE LA AUTORIZACION','reports/$VISTA/datosautorizacioncargo.php',1000,250,'".$var[$i][tarifario_id]."','".$var[$i][cargo]."',$Cuenta,".$var[$i][autorizacion_interna].",1,'Int')\"><img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a></td>";  }
              else
              { $this->salida .= "        <td></td>";} */
            $this->salida .= "    </tr>";
            //---------el detalle del cargo
            $this->salida .= "    <tr class=\"$estilo\">";
            $this->salida .= "        <td colspan=\"8\">";
            $dat = $this->DatosCargosCirugia($var[$i][transaccion]);
            $this->salida .= "          <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\" align=\"center\" class=\"normal_10\">";
            if (!empty($var[$i][tercero_id]) AND !empty($var[$i][tipo_tercero_id])) {
                $col = 1;
                $honorarios = '';
                $this->salida .= "         <tr class=\"$estilo\">";
                if ($var[$i][valor] > 0) {
                    if ($var[$i][tercero_id] != $var[$i][idhono] OR $var[$i][tipo_tercero_id] != $var[$i][tipohono])
                        $profhono = $this->GetNombreProfesional($var[$i][tipohono], $var[$i][idhono]);
                    $honorarios .= "          <td width=\"50%\"> HONORARIO: $" . FormatoValor($var[$i][valor]) . " (" . FormatoVAlor($var[$i][porcentaje_honorario]) . "%) &nbsp;$profhono</td>";
                }
                else {
                    $col = 2;
                }
                $prof = $this->GetNombreProfesional($var[$i][tipo_tercero_id], $var[$i][tercero_id]);
                $this->salida .= "          <td colspan=\"$col\"> PROFESIONAL: $prof</td>";
                $this->salida .= $honorarios;
                $this->salida .= "        </tr>";
            }
            $this->salida .= "            <tr class=\"$estilo\">";
            $this->salida .= "              <td colspan=\"2\" width=\"100%\"> PROCEDIMIENTO: " . $dat[cargo] . " - " . $dat[descar] . "</td>";
            $this->salida .= "            </tr>";
            $this->salida .= "          </table>";
            $this->salida .= "        </td>";
            $this->salida .= "    </tr>";
            $this->salida .= "   <tr>";
            $this->salida .= "        <td colspan=\"8\"></td>";
            $this->salida .= "    </tr>";
            //--------fin detalle del cargo				
        }
        if ($i % 2)
            $estilo = 'modulo_list_claro';
        else
            $estilo = 'modulo_list_oscuro';
        $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
        $this->salida .= "        <td colspan=\"3\"><b>TOTALES: </b></td>";
        $this->salida .= "        <td><b>" . FormatoValor($valor) . "</b></td>";
        $this->salida .= "        <td><b>" . FormatoValor($nocub) . "</b></td>";
        $this->salida .= "        <td><b>" . FormatoValor($cub) . "</b></td>";
        $this->salida .= "        <td colspan=\"2\"></td>";
        $this->salida .= "    </tr>";

        $this->salida .= "  </table><br>";
        //------------fin detalle cargos cirugia						
        //---------si la cirugia tiene medicamentos
        if (!empty($med)) {
            $this->FormaDetalleMedicamentosQx($med, $tiposMedica = 'QX');
        }
        //-----------fin medicamentos							
        $this->salida .= "        </td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Cama' => $Cama, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></p>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function FormaDetalleMedicamentosQx($var, $tiposMedica) {
        $this->salida .= "<br><table border=\"1\" cellpadding=\"2\" width=\"99%\" align=\"center\"  class=\"modulo_table_list_title\">";
        $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
        if ($_SESSION['CUENTAS']['SWCUENTAS'] != 'Cerradas') {
            $this->salida .= "        <td colspan=\"11\" align=\"center\">MEDICAMENTOS CIRUGIA</td>";
        } else {
            $this->salida .= "        <td colspan=\"9\" align=\"center\">MEDICAMENTOS CIRUGIA</td>";
        }
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "        <td width=\"10%\">CODIGO</td>";
        $this->salida .= "        <td width=\"40%\">CARGO</td>";
        $this->salida .= "        <td width=\"9%\">PRECIO.</td>";
        $this->salida .= "        <td width=\"5%\">CANT.</td>";
        $this->salida .= "        <td width=\"9%\">VALOR</td>";
        $this->salida .= "        <td width=\"9%\">VAL. NO CUBIERTO</td>";
        $this->salida .= "        <td width=\"9%\">VAL. CUBIERTO</td>";
        if ($tiposMedica != 'QX') {
            if ($_SESSION['CUENTAS']['SWCUENTAS'] != 'Cerradas') {
                $this->salida .= "     <td colspan=\"2\" width=\"6%\">ACCION</td>";
            }
        }
        $this->salida .= "        <td width=\"2%\">INT</td>";
        $this->salida .= "        <td width=\"2%\">EXT</td>";
        $this->salida .= "        <td></td>";
        $this->salida .= "    </tr>";
        $ValTotal = $TotalNo = $TotalCub = 0;
        for ($i = 0; $i < sizeof($var); $i++) {
            if ($i % 2)
                $estilo = 'modulo_list_claro';
            else
                $estilo = 'modulo_list_oscuro';
            $ValTotal+=$var[$i][valor_cargo];
            $TotalNo+=$var[$i][valor_nocubierto];
            $TotalCub+=$var[$i][valor_cubierto];
            $this->salida .= "    <tr class=\"$estilo\">";
            $this->salida .= "        <td align=\"center\">" . $var[$i][codigo_producto] . "</td>";
            $this->salida .= "        <td>" . $var[$i][descripcion] . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($var[$i][precio]) . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($var[$i][cantidad]) . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($var[$i][valor_cargo]) . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($var[$i][valor_nocubierto]) . "</td>";
            $this->salida .= "        <td align=\"center\">" . FormatoValor($var[$i][valor_cubierto]) . "</td>";
            if ($tiposMedica != 'QX') {
                if ($_SESSION['CUENTAS']['SWCUENTAS'] != 'Cerradas' || $tiposMedica != 'QX') {
                    $accionM = ModuloGetURL('app', 'Facturacion', 'user', 'LlamaFormaModificar', array('Transaccion' => $var[$i][transaccion], 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Datos' => $var[$i], 'des' => $desc, 'codigo' => $codigo, 'doc' => $documento, 'numeracion' => $numeracion));
                    $this->salida .= "        <td><a href=\"$accionM\">MODI</a></td>";
                    $mensaje = 'Esta seguro que desea eliminar este cargo.';
                    $arreglo = array('Transaccion' => $var[$i][transaccion], 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Cama' => $Cama, 'Fecha' => $Fecha, 'des' => $desc, 'codigo' => $codigo, 'doc' => $documento, 'numeracion' => $numeracion);
                    $accionE = ModuloGetURL('app', 'Facturacion', 'user', 'ConfirmarAccion', array('c' => 'app', 'm' => 'Facturacion', 'me2' => 'DefinirForma', 'me' => 'EliminarCargo', 'mensaje' => $mensaje, 'titulo' => 'ELIMINAR CARGO DE LA CUENTA No. ' . $Cuenta . ' ' . $Nombres . ' ' . $Apellidos, 'arreglo' => $arreglo, 'boton1' => 'ACEPTAR', 'boton2' => 'CANCELAR'));
                    if (empty($documento) AND empty($numeracion)) {
                        $this->salida .= "        <td><a href=\"$accionE\">ELIM</a></td>";
                    } else {
                        $this->salida .= "        <td></td>";
                    }
                }
            }
            $imagenInt = $imagenExt = '';
            if ($var[$i][autorizacion_int] === '0') {
                $imagenInt = "no_autorizado.png";
                $D = 1;
            } elseif ($var[$i][autorizacion_int] > 100) {
                $imagenInt = "autorizado.png";
                $D = 0;
            } elseif ($var[$i][autorizacion_int] == 1) {
                $imagenInt = "autorizadosiis.png";
                $D = 1;
            }

            if ($var[$i][autorizacion_ext] === '0') {
                $imagenExt = "no_autorizado.png";
                $n = 1;
            } elseif ($var[$i][autorizacion_ext] > 100) {
                $imagenExt = "autorizado.png";
                $n = 0;
            } elseif ($var[$i][autorizacion_ext] == 1) {
                $imagenExt = "autorizadosiis.png";
                $n = 1;
            }
            if (!empty($imagenInt)) {
                $this->salida .= "       <td><img src=\"" . GetThemePath() . "/images/$imagenInt\"></td>";
            } else {
                $this->salida .= "       <td></td>";
            }
            if (!empty($imagenExt)) {
                $this->salida .= "       <td><img src=\"" . GetThemePath() . "/images/$imagenExt\"></td>";
            } else {
                $this->salida .= "       <td></td>";
            }
            if ($imagenInt == "autorizado.png") {
                $this->salida .= "       <td><a href=\"javascript:ConsultaAutorizacion('DATOS DE LA AUTORIZACION','reports/$VISTA/datosautorizacioncargo.php',1000,250,'" . $var[$i][tarifario_id] . "','" . $var[$i][cargo] . "',$Cuenta," . $var[$i][autorizacion_interna] . ",1,'Int')\"><img src=\"" . GetThemePath() . "/images/informacion.png\" border=\"0\"></a></td>";
            } else {
                $this->salida .= "        <td></td>";
            }
            $this->salida .= "    </tr>";
        }
        if ($i % 2)
            $estilo = 'modulo_list_claro';
        else
            $estilo = 'modulo_list_oscuro';
        $this->salida .= "    <tr class=\"$estilo\" align=\"center\">";
        $this->salida .= "        <td colspan=\"4\"><b>TOTALES: </b></td>";
        $this->salida .= "        <td><b>" . FormatoValor($ValTotal) . "</b></td>";
        $this->salida .= "        <td><b>" . FormatoValor($TotalNo) . "</b></td>";
        $this->salida .= "        <td><b>" . FormatoValor($TotalCub) . "</b></td>";
        if ($_SESSION['CUENTAS']['SWCUENTAS'] != 'Cerradas') {
            $col = 6;
        } else {
            $col = 1;
        }
        $this->salida .= "        <td colspan=\"$col\"></td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
    }

    //----------------------FIN CIRUGIA--------------			

    function FormaFacturarImpresion($mensaje, $Cuenta, $prefijoPac, $facturaPac, $prefijoCli, $facturaCli, $PlanId) {
        IncludeLib('funciones_facturacion');
        global $VISTA;
        //factura detalleda

        $mostrar = "\n<script>\n";

        $RUTA = $_ROOT . "cache/factura" . $Cuenta . ".pdf";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentana(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        //factura conceptos
        $RUTA = $_ROOT . "cache/facturaconceptos" . $Cuenta . ".pdf";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentana2(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";

        $RUTA = $_ROOT . "cache/factura" . $Cuenta . "" . $prefijoPac . "" . $facturaPac . ".pdf";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentana3(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        //factura conceptos
        $RUTA = $_ROOT . "cache/facturaconceptos" . $Cuenta . "" . $prefijoPac . "" . $facturaPac . ".pdf";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentana4(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";


        $RUTA = $_ROOT . "cache/hojacargos" . $Cuenta . ".pdf";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentanaHC(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        $RUTA = $_ROOT . "cache/hojacargos2" . $Cuenta . ".pdf";
        $mostrar.="var rem=\"\";\n";
        $mostrar.="  function abreVentanaHT(){\n";
        $mostrar.="    var nombre=\"\"\n";
        $mostrar.="    var url2=\"\"\n";
        $mostrar.="    var str=\"\"\n";
        $mostrar.="    var ALTO=screen.height\n";
        $mostrar.="    var ANCHO=screen.width\n";
        $mostrar.="    var nombre=\"REPORTE\";\n";
        $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
        $mostrar.="    var url2 ='$RUTA';\n";
        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
        $mostrar.="</script>\n";
        $this->salida.="$mostrar";

        $this->salida .= ThemeAbrirTabla('IMPRESIONES FACTURACION CUENTA No. ' . $Cuenta);
        $this->salida .= "            <table width=\"50%\" align=\"center\" >";
        $this->salida .= "               <tr><td class=\"label_mark\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
        $this->salida .= "               <tr>";
        IncludeLib("reportes/hojacargos");
        GenerarHojaCargos(array('numerodecuenta' => $Cuenta));
        $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"HOJA CARGOS\" onclick=\"javascript:abreVentanaHC()\"></td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        IncludeLib("reportes/hojacargos2");
        GenerarHojaCargos2(array('numerodecuenta' => $Cuenta));
        $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"HOJA CARGOS2\" onclick=\"javascript:abreVentanaHT()\"></td>";
        $this->salida .= "               </tr>";

        if (!empty($facturaPac)) {
            $_SESSION['FACTURACION']['VAR']['factura'] = $facturaPac;
            $_SESSION['FACTURACION']['VAR']['prefijo'] = $prefijoPac;
            $var = $this->DatosFactura($Cuenta, '', '', '');
            //IncludeLib("reportes/factura");
            $ruta = EncontrarFormatoFactura($_SESSION['FACTURACION']['EMPRESA'], $PlanId, 'factura');
            IncludeLib($ruta);
            GenerarFactura($var, $swTipoFactura = 1);
            $this->salida .= "               <tr>";
            $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA PACIENTE\" onclick=\"javascript:abreVentana3()\"></td>";
            $this->salida .= "               </tr>";

            //IncludeLib("reportes/facturaconceptos");
            $ruta = EncontrarFormatoFactura($_SESSION['FACTURACION']['EMPRESA'], $PlanId, 'conceptos');
            IncludeLib($ruta);
            GenerarFacturaConceptos($var, $swTipoFactura = 1);
            $this->salida .= "               <tr>";
            $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA CONCEPTOS PACIENTE\" onclick=\"javascript:abreVentana4()\"></td>";
            $this->salida .= "               </tr>";
        }
        if (!empty($facturaCli)) {
            $_SESSION['FACTURACION']['VAR']['factura'] = $facturaCli;
            $_SESSION['FACTURACION']['VAR']['prefijo'] = $prefijoCli;
            //IncludeLib("reportes/factura");
            $ruta = EncontrarFormatoFactura($_SESSION['FACTURACION']['EMPRESA'], $PlanId, 'factura');
            IncludeLib($ruta);
            $var = $this->DatosFactura($Cuenta, '', '', '');
            GenerarFactura($var);
            $this->salida .= "               <tr>";
            $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA CLIENTE\" onclick=\"javascript:abreVentana()\"></td>";
            $this->salida .= "               </tr>";

            //IncludeLib("reportes/facturaconceptos");
            $ruta = EncontrarFormatoFactura($_SESSION['FACTURACION']['EMPRESA'], $PlanId, 'conceptos');
            IncludeLib($ruta);
            GenerarFacturaConceptos($var);
            $this->salida .= "               <tr>";
            $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA CONCEPTOS CLIENTE\" onclick=\"javascript:abreVentana2()\"></td>";
            $this->salida .= "               </tr>";
        }
        //para lo de salida de paciente
        if (!empty($_SESSION['FACTURACION']['RETORNO'])) {
            $contenedor = $_SESSION['FACTURACION']['RETORNO']['contenedor'];
            $modulo = $_SESSION['FACTURACION']['RETORNO']['modulo'];
            $tipo = $_SESSION['FACTURACION']['RETORNO']['tipo'];
            $metodo = $_SESSION['FACTURACION']['RETORNO']['metodo'];
            $argumentos = $_SESSION['FACTURACION']['RETORNO']['argumentos'];
            $accion = ModuloGetURL($contenedor, $modulo, $tipo, $metodo, $argumentos);
        } else {
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'LlamarFormaMetodoBuscar');
        }
        $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td></tr>";
        $this->salida .= "           </form>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     * M�odo para descargar los rips de un envio
     * Genera los Rips y hace download en un .tar.gz de todo la carpeta
     * de Rips del envio
     *
     * @return bool
     */
    function DescargarRipsEnvio() {
        $_SESSION['RIPS']['ENVIOS'] = $_SESSION['ENVIOS']['TERCERO'];
        $rutaRips = $this->GenerarRips($_REQUEST[ripsSoat], $_REQUEST[furips]);

        if (!empty($rutaRips)) {
            $this->salida .= ThemeAbrirTabla("DESCARGA DE RIPS", "50%");
            $this->salida .= "<div align=\"center\" class=\"label_error\">";
            $this->salida .= "	RIPS GENERADOS SATISFACTORIAMENTE";
            $this->salida .= "	<input class=\"input-submit\" type=\"button\" value=\"CERRAR\" onclick=\"window.close();\">";
            $this->salida .= "</div>";
            $this->salida .= ThemeCerrarTabla();
            $this->salida .= download($rutaRips, $nombre = "", $link = false, $comprimir = true, $boton = false);
        }
        else
            $this->salida .= "<div class=\"label_error\">Error al generar rips <input class=\"input-submit\" type=\"button\" value=\"CERRAR\" onclick=\"window.close();\"></div>";
        unset($_SESSION['RIPS']['ENVIOS']);
        return true;
    }

//Fin DescargarRipsEnvio
//------------------------------------------------------------------------------------
//FORMA PARA ADICIONAR EL CARGO DE AJUSTE DE CUENTA
    function FormaCargosAjusteCuenta() {

        $PlanId = $_REQUEST['PlanId'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Nivel = $_REQUEST['Nivel'];
        $Fecha = $_REQUEST['Fecha'];
        $Cuenta = $_REQUEST['Cuenta'];
        //$Saldo=($_REQUEST['Saldo'])*(-1);
        $Saldo = ($_REQUEST['Saldo']);
        $FechaRegistro = date("Y-m-d H:i:s");
        $SystemId = UserGetUID();
        $EmpresaId = $_SESSION['FACTURACION']['EMPRESA'];
        $CUtilidad = $_SESSION['FACTURACION']['CENTROUTILIDAD'];

        $numero_cuenta = $_REQUEST['numero_cuenta'];
        $nombre_paciente = $_REQUEST['nombre_paciente'];
        $valor_cuota = $_REQUEST['valor_cuota'];
        $motivos = $this->GetCargosAjuste();
        $this->salida .= ThemeAbrirTabla("CARGOS PARA AJUSTE DE CUENTA No. $Cuenta");
        $this->EncabezadoEmpresa();
        $this->salida .= "<BR>";
        $this->salida .= "<table align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table>";
        $this->FormaAdicionarCargoAjusteCuenta($Cuenta, $_REQUEST['valor'], $motivos);
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     * Forma que pinta el formulario para adicionar un cargo de ajuste.
     *
     * @param int numero_cuenta
     * @param array motivos()
     * @param mixed tipo_cargo(puede tomar los valores de DESCUENTOS=1 O APROVECHAMIENTOS=0.)
     */
    function FormaAdicionarCargoAjusteCuenta($numero_cuenta, $valor_cuota, $motivos) {
        $accion1 = ModuloGeturl("app", "Facturacion_Fiscal", "user", "AjustarCuenta", array('Cuenta' => $_REQUEST['Cuenta'], 'TipoId' => $_REQUEST['TipoId'], 'PacienteId' => $_REQUEST['PacienteId'], 'Nivel' => $_REQUEST['Nivel'], 'PlanId' => $_REQUEST['PlanId'], 'Fecha' => $_REQUEST['Fecha'], 'Ingreso' => $_REQUEST['Ingreso'], 'Estado' => $_REQUEST['Estado'], 'Saldo' => $_REQUEST['Saldo'], 'Transaccion' => $_REQUEST['Transaccion'], 'Dev' => $_REQUEST['Dev'], 'vars' => $_REQUEST['vars'], 'arre' => $_REQUEST['arre'], 'tipo_factura' => $_REQUEST['tipo_factura'], 'verhojas' => $_REQUEST['verhojas']));
        //$accion .= "&".$_SESSION['FACTURACION']['CUENTAS']['REQUEST'];
        $this->salida .= "<form name=\"frmCargoAjuste\" action=\"$accion1\" method=\"post\">\n";
        $this->salida .= "	<table width=\"50%\" align=\"center\" class=\"label\" >";
        $this->salida .= "	<tr>";
        $this->salida .= "	<td>";
        $this->salida .= "	<input type=\"hidden\" name=\"numero_cuenta\" value = \"$numero_cuenta\">";
        $this->salida .= "		<fieldset>";
        $this->salida .= "		<legend>DATOS AJUSTE</legend>";
        $this->salida .= "		<table width=\"100%\">\n";
        $this->salida .= "			<tr>\n";
        $this->salida .= "				<td width=\"20%\" class=\"label\">VALOR</td>\n";
        $this->salida .= "				<td  width=\"80%\"><input type=\"text\" name=\"valor\" class=\"input-text\" value=\"$valor_cuota\"></td>\n";
        $this->salida .= "			</tr>\n";
        $this->salida .= "			<tr>\n";
        $this->salida .= "				<td class=\"label\">CARGO AJUSTE</td>\n";
        $this->salida .= "				<td>\n";
        $this->salida .= "					<select name=\"cargo\" class=\"select\" >\n";
        foreach ($motivos as $key => $motivo) {
            if ($_REQUEST['cargo'] == $motivo['cargo'])
                $this->salida .= "					<option value=\"{$motivo['cargo']}\" selected>{$motivo['cargo']}" . '--' . "{$motivo['descripcion']}</option>\n";
            else
                $this->salida .= "					<option value=\"{$motivo['cargo']}\">{$motivo['cargo']}" . '--' . "{$motivo['descripcion']}</option>\n";
        }
        $this->salida .= "					</select>\n";
        $this->salida .= "				</td>\n";
        $this->salida .= "			</tr>\n";
        $this->salida .= "			<tr>\n";
        $this->salida .= "				<td class=\"label\" valign=\"top\">OBSERVACI�</td>\n";
        $this->salida .= "				<td><textarea name=\"observacion\" style=\"width:100%\" class=\"textarea\">" . $_REQUEST['observacion'] . "</textarea></td>\n";
        $this->salida .= "			</tr>\n";
        $this->salida .= "		</table>\n";
        $this->salida .= "		</fieldset>\n";
        $this->salida .= "	</td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= "		<table align=\"center\">\n";
        $this->salida .= "			<tr>\n";
        $this->salida .= "				<td>\n";
        $this->salida .= "					<input type=\"submit\" class=\"input-bottom\" value=\"GUARDAR\">\n";
        $this->salida .= "				</td>\n";
        $this->salida .= "	</form>\n";
        $accion = ModuloGetUrl("app", "Facturacion_Fiscal", "user", "Facturacion", array('Cuenta' => $_REQUEST['Cuenta'], 'TipoId' => $_REQUEST['tipoid'], 'PacienteId' => $_REQUEST['pacienteid'], 'PlanId' => $_REQUEST['plan_id'], 'Nivel' => $_REQUEST['Nivel'], 'Fecha' => $_REQUEST['Fecha'], 'Ingreso' => $_REQUEST['Ingreso'], 'Transaccion' => $_REQUEST['Transaccion'], 'Estado' => $_REQUEST['Estado'], 'tipo_factura' => $_REQUEST['tipo_factura'], 'Dev' => $_REQUEST['Dev'], 'vars' => $_REQUEST['vars'], 'verhojas' => $_REQUEST['verhojas']));
        $this->salida .= "	<form name=\"frmCancelar\" action=\"$accion\" method = \"post\">\n";
        $this->salida .= "				<td>\n";
        $this->salida .= "					<input type=\"submit\" class=\"input-bottom\" value=\"CANCELAR\">\n";
        $this->salida .= "				</td>\n";
        $this->salida .= "	</form>\n";
        $this->salida .= "			</tr>\n";
        $this->salida .= "		</table>\n";
    }

    //function FormaModificarFacturasEnvios($arr,$adicionar)
    function FormaModificarFacturasEnvios($vect) {
        $arr = $_SESSION['DETALLE']['ENVIO'];
        $this->salida.= ThemeAbrirTabla('BUSQUEDA DE FACTURAS PARA ENVIOS');
        $this->Todos();
        IncludeLib("tarifario");
        IncludeLib("funciones_admision");
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarEnvios', array('modificarenvio' => '1'));
        $this->EncabezadoEmpresa();
        $this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
        //$this->salida .= "<td align = left >SELECCIONE LA FECHA:</td>";
        $this->salida .= "</tr>";
        $this->salida .= "<tr class=\"modulo_list_claro\" >";
        $this->salida .= "<td width=\"40%\" >";
        $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr><td>";
        $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
        $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
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
        $ac = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarEnvios');
        $f = explode(',', $_SESSION['ENVIOS']['ADICIONAR']);
        $this->salida .= "               <tr><td class=\"" . $this->SetStyle("Responsable") . "\">RESPONSABLE: </td>";
        $this->salida .= "              <td class=\"label\">$f[2]</td></tr>";
        $this->salida .= "               <tr><td class=\"" . $this->SetStyle("Plan") . "\">PLANES ACTIVOS: </td><td>";

        $responsables = $this->responsables($f[0], $f[1]);
        $this->salida .= "<BR><table width=\"80%\" align=\"center\" border=\"0\">";
        for ($j = 0; $j < sizeof($responsables); $j++) {
            $this->salida .= "<tr>";
            $this->salida .= "<td class=\"label\" width=\"65%\">" . $responsables[$j][plan_descripcion] . "</td>";
            $this->salida .= "<td><input type = checkbox name= plan" . $responsables[$j][plan_id] . " value=\"" . $responsables[$j][plan_id] . "\"></td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "</table>";
        $this->salida .= "              </td></tr>";
        $inac = $this->Inactivos($f[0], $f[1]);
        if (!empty($inac)) {
            $this->salida .= "               <tr><td class=\"" . $this->SetStyle("Plan") . "\">PLANES INACTIVOS: </td><td>";
            $this->salida .= "<BR><table width=\"80%\" align=\"center\" border=\"0\">";
            for ($i = 0; $i < sizeof($inac); $i++) {
                $this->salida .= "<tr>";
                $this->salida .= "<td class=\"label\" width=\"65%\">" . $inac[$i][plan_descripcion] . "</td>";
                $this->salida .= "<td><input type = checkbox name= inac" . $inac[$i][plan_id] . " value=\"" . $inac[$i][plan_id] . "\"></td>";
                $this->salida .= "</tr>";
            }
            $this->salida .= "</table>";
            $this->salida .= "              </td></tr>";
        }
        if ($j > 1) {
            $this->salida .= "               <tr><td class=\"" . $this->SetStyle("Plan") . "\"></td><td>";
            $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
            $this->salida .= "<tr>";
            $this->salida .= "<td class=\"label\" width=\"65%\">TODOS PLANES</td>";
            $this->salida .= "<td><input type = checkbox name=Todos ></td>";
            $this->salida .= "</tr>";
            $this->salida .= "</table>";
            $this->salida .= "              </td></tr>";
        }

        $this->salida .= "<tr><td class=\"label\">DEPARTAMENTO: </td><td><select name=\"Dpto\" class=\"select\">";
        $departamento = $this->Departamentos();
        $this->BuscarDepartamento($departamento, $d, $_REQUEST['Dpto']);
        $this->salida .= "</select></td></tr>";
        $this->salida .= "                <tr>";
        $i = $_REQUEST['FechaI'];
        if (!empty($i)) {
            $f = explode('-', $_REQUEST['FechaI']);
            $i = $f[2] . '/' . $f[1] . '/' . $f[0];
        }
        /* if($arr=='si' OR !empty($arr))
          {  $i=''; } */
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaI") . "\">DESDE: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaI\" value=\"" . $i . "\">" . ReturnOpenCalendario('forma', 'FechaI', '/') . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $fi = $_REQUEST['FechaF'];
        if (!empty($i)) {
            $f = explode('-', $_REQUEST['FechaF']);
            $fi = $f[2] . '/' . $f[1] . '/' . $f[0];
        }
        /* if($arr=='si' OR !empty($arr))
          {  $fi='';  } */
        $this->salida .= "                    <td class=\"" . $this->SetStyle("FechaF") . "\">HASTA: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaF\" value=\"" . $fi . "\">" . ReturnOpenCalendario('forma', 'FechaF', '/') . "</td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $this->salida .= "                    <td class=\"" . $this->SetStyle("prefijo") . "\">PREFIJO: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"prefijo\" value=\"" . $_POST['prefijo'] . "\" size='3' maxlength=\"5\"></td>";
        $this->salida .= "                </tr>";
        $this->salida .= "                <tr>";
        $this->salida .= "                    <td class=\"" . $this->SetStyle("numero") . "\">NUMERO: </td>";
        $this->salida .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"numero\" value=\"" . $_POST['numero'] . "\" size='6' maxlength=\"10\"></td>";
        $this->salida .= "                </tr>";
        $this->salida .= "<tr class=\"label\">";
        $this->salida .= "</tr>";
        $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
        $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSQUEDA\"></td>";
        $this->salida .= "</form>";
        $actionM = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaDetalleEnvio');  //}
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"><br></td></form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table></td></tr>";
        $this->salida .= "</td></tr></table>";
        $this->salida .= "</table>";
        $this->salida .= "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table>";
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"70%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        unset($_SESSION['FACTURACION']['ENVIO']['ARREGLO']);
        if (!empty($arr) AND $arr != 'si') {
            $_SESSION['FACTURACION']['ENVIO']['ARREGLO'] = $arr;
            //$vars=$this->Facturas($_REQUEST[prefijo],$_REQUEST[numero],$_REQUEST['FechaI'],$_REQUEST['FechaF']);
            $vars = $arr;

            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'ModificarEnvio');
            $this->salida .= "    <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td width=\"15%\">FACTURA</td>";
            $this->salida .= "        <td width=\"20%\">VALOR</td>";
            $this->salida .= "        <td width=\"15%\">IDENTIFICACION</td>";
            $this->salida .= "        <td width=\"25%\">PACIENTE</td>";
            $this->salida .= "        <td width=\"20%\">PLAN</td>";
            $this->salida .= "        <td width=\"5%\"><input type=\"checkbox\" name=\"Todo\" onClick=\"Todos(this.form,this.checked)\"></td>";
            $this->salida .= "      </tr>";
            $total = 0;
            //$arreglo='';
            $j = 0;
            for ($i = 0; $i < sizeof($vars) - 1; $i++) {
                /* 							if($vars[$i][porqueria]==0)
                  { */
                /* 								if(!empty($vars[$vars[$i][prefijo]][$vars[$i][factura_fiscal]]))
                  { */
                if ($j % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $total+=$vars[$i][total_factura];
                //$arreglo[$arr[$i][prefijo]][$arr[$i][factura_fiscal]]=$arr[$i][prefijo];
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td align=\"center\">" . $vars[$i][prefijo] . " " . $vars[$i][factura_fiscal] . "</td>";
                $this->salida .= "        <td align=\"center\">" . FormatoValor($vars[$i][total_factura]) . "</td>";
                /* 									$x=0;
                  $d=$i;
                  while($vars[$i][prefijo]==$vars[$d][prefijo] AND $vars[$i][factura_fiscal]==$vars[$d][factura_fiscal] AND $vars[$i][prefijo]!='' AND $vars[$i][factura_fiscal]!='')
                  {  $x++; $d++; }
                  if($x > 1)
                  {
                  $this->salida .= "        <td></td>";
                  $this->salida .= "        <td>AGRUPADA</td>";
                  }
                  else
                  { */
                $pac = '';
                $pac = BuscarDatosPacienteIngreso($vars[$i][ingreso]);
                $this->salida .= "        <td>" . $pac[tipo_id_paciente] . " " . $pac[paciente_id] . "</td>";
                $this->salida .= "        <td>" . $pac[nombre] . "</td>";
//									}
                $this->salida .= "        <td align=\"center\">" . $vars[$i][plan_descripcion] . "</td>";
                $this->salida .= " <td><input type=\"checkbox\" value=\"" . $vars[$i][prefijo] . "||" . $vars[$i][factura_fiscal] . "||" . $vars[$i][ingreso] . "||" . $vars[$i][numerodecuenta] . "||" . $vars[$i][total_factura] . "||" . $vars[$i][plan_id] . "||" . $vars[$i][plan_descripcion] . "||" . $vars[$i][empresa_id] . "||" . $pac[tipo_id_paciente] . "||" . $pac[nombre] . "\" name=\"Envio" . $vars[$i][prefijo] . "" . $vars[$i][factura_fiscal] . $i . "\" checked></td>";
//									$i=$d;
                $j++;
                $this->salida .= "      </tr>";
            }
//
            $l = 0;
            $vars = '';
            if ($vect) {
                $vars = $vect;
                $_SESSION['DETALLE']['SELECCION'] = $vect;
                foreach ($vars as $i => $v3) {
                    if ($l % 2) {
                        $estilo = 'modulo_list_claro';
                    } else {
                        $estilo = 'modulo_list_oscuro';
                    }
                    $total+=$v3[total_factura];
                    //$arreglo[$arr[$i][prefijo]][$arr[$i][factura_fiscal]]=$arr[$i][prefijo];
                    $this->salida .= "      <tr class=\"$estilo\">";
                    $this->salida .= "        <td align=\"center\">" . $v3[prefijo] . " " . $v3[factura_fiscal] . "</td>";
                    $this->salida .= "        <td align=\"center\">" . FormatoValor($v3[total_factura]) . "</td>";
                    $pac = '';
                    $pac = BuscarDatosPacienteIngreso($v3[ingreso]);
                    $this->salida .= "        <td>" . $pac[tipo_id_paciente] . " " . $pac[paciente_id] . "</td>";
                    $this->salida .= "        <td>" . $pac[nombre] . "</td>";
                    $this->salida .= "        <td align=\"center\">" . $v3[plan_descripcion] . "</td>";
                    $this->salida .= " <td><input type=\"checkbox\" value=\"" . $v3[prefijo] . "||" . $v3[factura_fiscal] . "||" . $v3[ingreso] . "||" . $v3[numerodecuenta] . "||" . $v3[total_factura] . "||" . $v3[plan_id] . "||" . $v3[plan_descripcion] . "||" . $v3[empresa_id] . "||" . $pac[tipo_id_paciente] . "||" . $pac[nombre] . "\" name=\"Envio" . $v3[prefijo] . "" . $v3[factura_fiscal] . $i . "\" ></td>";
                    $l++;
                    $this->salida .= "      </tr>";
                }
            }
//

            /* 			$g=0;
              foreach($_SESSION['FACTURACION']['ENVIO']['SELECCION'] as $k => $v)
              {
              if(substr_count($k,'Envio'))
              {
              //0 prefijo 1 factura 2 tipoid y paciente 3 nombre
              //4 total 5 plan 6 plan_des 7empresa 8 centro
              $y=explode('||',$v);
              $seleccion[$g]=$y[0].'-'.$y[1];
              $g++;
              }
              }

              for($i=0; $i<sizeof($vars); $i++)
              {
              if($vars[$i][porqueria]==0)
              {
              if(!empty($arr[$vars[$i][prefijo]][$vars[$i][factura_fiscal]]))
              {
              if( $i % 2){ $estilo='modulo_list_claro';}
              else {$estilo='modulo_list_oscuro';}
              $this->salida .= "      <tr class=\"$estilo\">";
              $this->salida .= "        <td align=\"center\">".$vars[$i][prefijo]." ".$vars[$i][factura_fiscal]."</td>";
              $this->salida .= "        <td>".$vars[$i][id]."</td>";
              $this->salida .= "        <td>".$vars[$i][nombre]."</td>";
              $this->salida .= "        <td align=\"center\">".$vars[$i][plan_descripcion]."</td>";
              //$this->salida .= "        <td align=\"center\">".$vars[$i][fecha_registro]."</td>";
              $this->salida .= "        <td>".$vars[$i][usuario]."</td>";
              $this->salida .= "        <td align=\"center\">".FormatoValor($vars[$i][total_factura])."</td>";

              for($k=0; $k<sizeof($seleccion); $k++)
              {
              $x=explode('-',$seleccion[$k]);
              if($x[0]==$vars[$i][prefijo] AND $x[1]==$vars[$i][factura_fiscal])
              {
              $this->salida .= " <td><input type=\"checkbox\" value=\"".$vars[$i][prefijo]."||".$vars[$i][factura_fiscal]."||".$vars[$i][id]."||".$vars[$i][nombre]."||".$vars[$i][total_factura]."||".$vars[$i][plan_id]."||".$vars[$i][plan_descripcion]."||".$vars[$i][empresa_id]."||".$vars[$i][centro_utilidad]."\" name=\"Envio".$vars[$i][prefijo]."".$vars[$i][factura_fiscal].$i."\" checked></td>";
              $k=sizeof($seleccion);
              }
              elseif($k==(sizeof($seleccion)-1))
              {
              $this->salida .= " <td><input type=\"checkbox\" value=\"".$vars[$i][prefijo]."||".$vars[$i][factura_fiscal]."||".$vars[$i][id]."||".$vars[$i][nombre]."||".$vars[$i][total_factura]."||".$vars[$i][plan_id]."||".$vars[$i][plan_descripcion]."||".$vars[$i][empresa_id]."||".$vars[$i][centro_utilidad]."\" name=\"Envio".$vars[$i][prefijo]."".$vars[$i][factura_fiscal].$i."\"></td>";
              }
              }
              }
              }
              $this->salida .= "      </tr>";
              } */
            $this->salida .= "  </table>";
            $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MODIFICAR ENVIO\"></p>";
            $this->salida .= "</form>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /*     * ********************************************************************************
     * Forma para capturar los datos para buscar el paciente
     * @access private
     * @return boolean
     * ******************************************************************************** */

    function FrmAperturaAdmision() {
        unset($_SESSION['AUTORIZACIONES1']);
        IncludeFile("app_modules/Facturacion_Fiscal/RemoteXajax/DetalleFacturas.php");
        $this->SetXajax(array("DatosPacienteFP", "DatosPlan", "DatosPacienteLF"),null,"ISO-8859-1");
        
        $action1 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarIngresoPaciente');
        $this->salida .= ThemeAbrirTabla('BUSCAR DATOS PACIENTE');
        $this->salida .= "<table align=\"center\" width=\"50%\">\n";
        $this->salida .= "	" . $this->SetStyle("MensajeError") . "\n";
        $this->salida .= "</table>\n";
        $this->salida .= "<form name=\"formabuscar\" action=\"" . $action1 . "\" method=\"post\" >\n";
        $this->salida .= "	<table width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
        $this->salida .= "		<input type='hidden' name='NoAutorizacion' value=''>\n";
        $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "			<td style=\"text-align:left;text-indent:11pt\">TIPO DOCUMENTO: </td>\n";
        $this->salida .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
        $this->salida .= "                          <select name=\"TipoDocumento\" id=\"TipoDocumento\" class=\"select\">\n";

        $tipo_id = $this->tipo_id_paciente();
        foreach ($tipo_id as $value => $titulo) {
            ($value == $_REQUEST['TipoDocumento']) ? $sel = "selected" : $sel = "";

            $this->salida .="                           <option value=\"$value\" $sel>$titulo</option>\n";
        }
        $this->salida .= "                          </select>\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "		<tr class=\"modulo_table_list_title\" >\n";
        $this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" >DOCUMENTO: </td>\n";
        $this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
        $this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"Documento\" id=\"Documento\" maxlength=\"32\" value=\"" . $_REQUEST['Documento'] . "\" onblur = \"xajax_DatosPacienteLF(TipoDocumento.value, Documento.value)\">";
//        $this->salida .= "			</td>\n";
//        $this->salida .= "                      <td class=\"" . $this->SetStyle("Documento") . "\">";
//        $this->salida .= "                        <input type=\"text\" class=\"input-text\" name=\"Documento\" id=\"Documento\" maxlength=\"32\" value=\"" . $_REQUEST['Documento'] . "\" onblur = \"xajax_DatosPacienteLF(Tipo.value, Documento.value)\">";
        $this->salida .= "                          <a href=\"#validar\" onclick=\"xajax_DatosPacienteFP(TipoDocumento.value, Documento.value);\"> CONSULTAR PLAN </a>    \n";
        $this->salida .= "                      </td>";
        $this->salida .= "		</tr>\n";

        $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "			<td style=\"text-align:left;text-indent:11pt\">PLAN:</td>\n";
        $this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
        $this->salida .= "				<select name=\"Responsable\" id=\"Responsable\" class=\"select\" onchange = \" 
                                                                                                                if(this.value > 0)
                                                                                                                {
                                                                                                                    xajax_DatosPlan(this.value);
                                                                                                                }else{
                                                                                                                    plan_id_est_actual_sel.value = 0;
                                                                                                                    sw_estado_plan=0;
                                                                                                                }
                                                                                                                \" 
                                                                                                                onchange = \"
                                                                                                                if(Documento.value > 0)
                                                                                                                {
                                                                                                                    Buscar.disabled = false;
                                                                                                                }    
                                                                                                                \">";
        $this->Responsables = $this->Responsables();
        $this->salida .= "				" . $this->MostrarResponsable($this->Responsables, $_REQUEST['Responsable']);
        $this->salida .= "                              </select>\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>\n";

        $this->salida .= "		<tr><td><br><br></td><td></td></tr>\n";
        
        
        $tipo_afiliado = $this->Tipo_AfiliadoS();
        $this->salida .= "              <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                  <td style=\"text-align:left;text-indent:11pt\"> TIPO AFILIADO: </td>";
        $this->salida .= "                  <td align=\"left\" class=\"modulo_list_claro\">\n";
        $this->salida .= "                        <input type = 'hidden' name = 'tip_afiliado_DA' id = 'tip_afiliado_DA' value = ".$_REQUEST['tip_afiliado_DA'].">";

        $this->salida .= "                        <div name = 'comtip_afiliado' id = 'comtip_afiliado' >   ";
        $this->salida .= "                              <select name=\"tip_afiliado\" id=\"tip_afiliado\" class=\"select\">";
//        $this->BuscarIdTipoAfiliadoS($tipo_afiliado);
        $this->salida .= "                              </select>";        
        $this->salida .= "                        </div>";
        $this->salida .= "                  </td>";
        $this->salida .= "              </tr>";
        $this->salida .= "              <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                  <td style=\"text-align:left;text-indent:11pt\">RANGO: </td>";
        $this->salida .= "                  <td align=\"left\" class=\"modulo_list_claro\">\n";
        $this->salida .= "                        <input type = 'hidden' name = 'rangoDA' id = 'rangoDA' value = ".$_REQUEST['rangoDA'].">";

        $this->salida .= "                        <div name = 'div_NivelDA' id = 'div_NivelDA' >   ";
        $this->salida .="                               <select  name=\"NivelDA\" name=\"NivelDA\" class=\"select\">";
        $this->salida .="                                   <option value=\"-1\">---Seleccione---</option>";
        $this->salida .= "                              </select>";
        $this->salida .= "                        </div>";

        $this->salida .= "                  </td>";
        $this->salida .= "              </tr>";
        $this->salida .= "              <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                  <td style=\"text-align:left;text-indent:11pt\">SEMANAS COTIZADAS: </td>";
        $this->salida .= "                  <td align=\"left\" class=\"modulo_list_claro\"><input type = 'textfield' name = 'semanacot' id = 'semanacot' value = ".$_REQUEST['semanacot']."></td>";
        $this->salida .= "              </tr>";
        
        $this->salida .= "		    <tr> ";
        $this->salida .= "                      <td align=\"right\"><br>";
        $this->salida .= "                          <input type = 'hidden' name = 'estado_paciente' id = 'estado_paciente' value = ".$_REQUEST['estado_paciente'].">";
        $this->salida .= "                          <input type = 'hidden' name = 'plan_id_pac_actual' id = 'plan_id_pac_actual'  value = ".$_REQUEST['plan_id_pac_actual'].">";
        $this->salida .= "                          <input type = 'hidden' name = 'plan_id_est_actual' id = 'plan_id_est_actual'  value = ".$_REQUEST['plan_id_est_actual'].">";
        $this->salida .= "                      </td>";
        $this->salida .= "                      <td align=\"right\">";
        $this->salida .= "                          <input type = 'hidden' name = 'plan_id_est_actual_sel' id = 'plan_id_est_actual_sel' value = ".$_REQUEST['plan_id_est_actual_sel'].">";
        $this->salida .= "                          <input type = 'hidden' name = 'sw_estado_plan' id = 'sw_estado_plan' value = ".$_REQUEST['sw_estado_plan'].">";
        $this->salida .= "                      </td>";
        $this->salida .= "		    </tr> ";
        

        /* 		if($_SESSION['AdmHospitalizacion']['menu'])
          {
          $this->salida .= "		<tr>\n";
          $this->salida .= "			<td colspan=\"2\">\n";
          $this->salida .= "				<table class=\"normal_10\" width=\"100%\">\n";
          $this->salida .= "					<tr>\n";
          $this->salida .= "						<td style=\"text-align:left;text-indent:11pt\">\n";
          $this->salida .= "							<input type=\"radio\" name=\"remision\" value=\"1\" checked><b>PACIENTE NO REMITIDO</b>\n";
          $this->salida .= "						</td>\n";
          $this->salida .= "						<td style=\"text-align:left;text-indent:11pt\">\n";
          $this->salida .= "							<input type=\"radio\" name=\"remision\" value=\"2\"><b>PACIENTE REMITIDO</b>\n";
          $this->salida .= "						</td>\n";
          if($this->triage == 1)
          {
          $this->salida .= "						<td style=\"text-align:left;text-indent:11pt\">\n";
          $this->salida .= "							<input type=\"radio\" name=\"remision\" value=\"3\"><b>PACIENTE - TRIAGE</b>\n";
          $this->salida .= "						</td>\n";
          }
          $this->salida .= "					</tr>\n";
          $this->salida .= "				</table>\n";

          $this->salida .= "			</td>\n";
          $this->salida .= "		</tr>\n";
          } */

        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td align=\"center\" class=\"label_error\" colspan=\"2\">\n";
        $this->SetJavaScripts('BuscadorBD');
        $this->salida .= RetornarWinOpenDatosBuscadorBD($_SESSION['AdmisionHospitalizacion']['deptno'], 'formabuscar');
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= "	<table border=\"0\" align=\"center\" width=\"50%\">\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td align=\"center\">\n";
        $this->salida .= "				<br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"><br>\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "		</form>\n";
        $action2 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaMenus');
        $this->salida .= "      <form name=\"forma\" action=\"" . $action2 . "\" method=\"post\">\n";
        $this->salida .= "			<td align=\"center\">\n";
        $this->salida .= "				<br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\"><br>\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "		</form>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //FORMA MOSTRAR INGRESO PACIENTE
    function FormaMostrarInfoIngreso($dat) {
        $action1 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FrmAperturaAdmision');
        /* 			$tmp=explode('*',$dat);
          $ingreso=$tmp[0];
          $cuentas=$tmp[1]; */
        $this->salida .= ThemeAbrirTabla('ADMISIONES - DATOS DEL INGRESO');
        $this->salida .= "<table align=\"center\" width=\"50%\">\n";
        $this->salida .= "	" . $this->SetStyle("MensajeError") . "\n";
        $this->salida .= "</table><br>\n";
        $this->salida .= "<table width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
        $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" width=\"20%\">INGRESO:</td>\n";
        $this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" colspan=\"2\" class=\"modulo_list_claro\">" . $dat[ingreso][0]['ingreso'] . "</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" width=\"20%\">FECHA INGRESO</td>\n";
        $this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" colspan=\"2\" class=\"modulo_list_claro\">" . $dat[ingreso][0]['fecha_ingreso'] . "</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" width=\"20%\">VIA INGRESO:</td>\n";
        $this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" colspan=\"2\" class=\"modulo_list_claro\">" . $dat[ingreso][0]['via_ingreso_nombre'] . "</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" width=\"20%\">PACIENTE:</td>\n";
        $this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" width=\"25%\" class=\"modulo_list_claro\">\n";
        $this->salida .= "				" . $dat[ingreso][0]['tipo_id_paciente'] . " " . $dat[ingreso][0]['paciente_id'] . "</td>\n";
        $this->salida .= "			<td style=\"text-align:left;text-indent:11pt\" class=\"modulo_list_claro\">\n";
        $this->salida .= "				" . $dat[ingreso][0]['nombres'] . " " . $dat[ingreso][0]['apellidos'] . "</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "</table><br>\n";

        if (sizeof($dat[cuentas]) > 0) {
            $this->salida .= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "     <tr class=\"modulo_table_list_title\" align=\"center\">\n";
            $this->salida .= "        <td width=\"15%\" >N CUENTA</td>\n";
            $this->salida .= "	  	  <td width=\"%\" >PLAN</td>\n";
            $this->salida .= "        <td width=\"15%\">TOTAL</td>\n";
            $this->salida .= "        <td width=\"15%\">ESTADO</td>\n";
            $this->salida .= "      </tr>\n";

            for ($i = 0; $i < sizeof($dat[cuentas]); $i++) {
                if ($i % 2 == 0) {
                    $estilo = 'modulo_list_oscuro';
                    $background = "#CCCCCC";
                } else {
                    $estilo = 'modulo_list_claro';
                    $background = "#DDDDDD";
                }

                $this->salida .= "			<tr class=\"" . $estilo . "\" height=\"21\" onmouseout=mOut(this,\"" . $background . "\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
                $this->salida .= "				<td align=\"center\"><b>" . $dat[cuentas][$i]['numerodecuenta'] . "</b></td>\n";
                $this->salida .= "				<td align=\"left\">" . $dat[cuentas][$i]['plan_descripcion'] . "</td>\n";
                $this->salida .= "				<td align=\"center\">" . formatoValor($dat[cuentas][$i]['total_cuenta']) . "</td>\n";
                $this->salida .= "				<td align=\"center\" ><b>" . $dat[cuentas][$i]['descripcion'] . "</b></td>\n";
                $this->salida .= "			</tr>";
            }
            $this->salida .= "            </table><br>\n";
        } else {
            $this->salida .= "<center><b class=\"label_error\">NO TIENE CUENTAS</b></center>\n";
        }

        $this->salida .= "<form name=\"forma\" action=\"" . $action1 . "\" method=\"post\">\n";
        $this->salida .= "	<table border=\"0\" align=\"center\" width=\"50%\">\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td align=\"center\">\n";
        $this->salida .= "				<br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\"><br>\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= "</form>\n";
        $this->salida .= ThemeCerrarTabla();
    }

    /**
     *
     */
    function FormaElegirDepartamento() {
        /* unset($_SESSION['ADMISIONES']);
          $_SESSION['ADMISIONES']['PACIENTE']['plan_id'] = $_SESSION['AUTORIZACIONES']['RETORNO']['plan_id'];
          $_SESSION['ADMISIONES']['PACIENTE']['paciente_id'] = $_SESSION['AUTORIZACIONES']['RETORNO']['paciente_id'];
          $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'] = $_SESSION['AUTORIZACIONES']['RETORNO']['tipo_id_paciente'];
         */
        $arreglo = array();
        $arreglo['tipo_id_paciente'] = $_REQUEST['tipo_id_paciente'];
        $arreglo['paciente_id'] = $_REQUEST['paciente_id'];
        $arreglo['plan_id'] = $_REQUEST['plan_id'];
        $arreglo['afilia'] = $_REQUEST['afilia'];

        $this->salida = ThemeAbrirTabla('ELEGIR DEPARTAMENTO', '70%');
        $this->salida .= "<table align=\"center\" width=\"50%\">\n";
        $this->salida .= "	" . $this->SetStyle("MensajeError") . "\n";
        $this->salida .= "</table>\n";

        $action1 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'AutorizarPaciente', $arreglo);
        $this->salida .= "	<table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "		<form name=\"formabuscar\" action=\"" . $action1 . "\" method=\"post\">\n";
        $this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "				<td width=\"35%\" style=\"text-indent:8pt;text-align:left\">CODIGO AUTORIZACI�N:</td>\n";
        $this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
        $this->salida .= "					<input type=\"text\" name=\"codigoau\" class=\"input-text\" style=\"width:100%\">\n";
        $this->salida .= "			  </td>\n";
        $this->salida .= "			</tr>\n";
        $this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "				<td colspan=\"2\" >DEPARTAMENTO</td>\n";
        $this->salida .= "			</tr>\n";
        $this->salida .= "			<tr class=\"modulo_list_claro\">\n";
        $this->salida .= "				<td colspan=\"2\" width=\"72%\">\n";
        $this->salida .= "					<select name=\"departamentos\" class=\"select\">\n";
        $this->salida .= " 						<option value=\"-1\">---Seleccione---</option>\n";
        $dat = $this->TraerDepartamentos();
        for ($i = 0; $i < sizeof($dat); $i++) {
            $this->salida .="							<option value=\"" . $dat[$i][centro_utilidad] . "," . $dat[$i][departamento] . "\">" . $dat[$i][descripcion] . "</option>\n";
        }

        $this->salida .= "					</select>\n";
        $this->salida .= "				</td>\n";
        $this->salida .= "			</tr>\n";
        $this->salida .= "			<tr>\n";
        $this->salida .= "				<td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "					<input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\">\n";
        $this->salida .= "				</td>\n";
        $this->salida .= "			</tr>\n";
        $this->salida .= "		</form>\n";
        $this->salida .= "	</table><br>\n";
        $action2 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FrmAperturaAdmision');
        $this->salida .= "<form name=\"formabuscar\" action=\"" . $action2 . "\" method=\"post\">";
        $this->salida .= "	<table width=\"50%\" align=\"center\">\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td align=\"center\">\n";
        $this->salida .= "					<input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Cancelar\">\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>";
        $this->salida .= "		</form>";
        $this->salida .= "	</table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function FormaReportesSoat() {
        $this->salida.= ThemeAbrirTabla('IMPRESI� REPORTES ANEXOS SOAT', '65%');
        $this->salida.="<table align=\"center\" border=\"0\" width=\"50%\">";
        $this->salida.="<tr>";
        $this->salida .= "<td colspan=\"2\" align=\"center\" class=\"label\">DATOS ANEXOS</td>";
        $this->salida.="</tr>";
        $this->salida.="<tr>";
//
        UNSET($_SESSION['FACTURACION']['datos_periodo_soat']);
        $var = $this->BuscarDatosInformeSoat();
        $_SESSION['FACTURACION']['datos_periodo_soat'] = $var;
        $reporte = new GetReports(); //FALSE
        $mostrar = $reporte->GetJavaReport('app', 'Facturacion_Fiscal', 'Soat_Anexo1', array('rpt_name' => $var['numeroradi'], 'rpt_dir' => 'cache/', 'rpt_rewrite' => TRUE));
        $funcion = $reporte->GetJavaFunction();
        $this->salida .= "$mostrar";
//
        if ($var) {//$datos
            //para imprimir en pdf
            $this->salida .= "<td align=\"right\" width=\"50%\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"IMPRIMIR ANEXOS\"  onclick=\"javascript:$funcion\"></td>\n";
        } else {
            $this->salida.= "<td align=\"center\" width=\"70%\"><label class=\"label_mark\">NO HAY DATOS PARA EL REPORTE</label></td>\n";
        }
        //volver
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarRad');
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<td align=\"left\" width=\"50%\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Volver\"></td>\n";
        $this->salida .= "</form>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

    function FormaDatosInformeSoat() {//
        UNSET($_SESSION['FACTURACION']['reportes_soat']);
        $dat = explode('/', $_REQUEST[EnvioRips]);
        $envio = $dat[0];
        $fechas = $this->TraerFechasEnvio($envio);

        $_POST['fechainici'] = $this->FormatoFecha($fechas[FECHA_INICIAL]);
        $_POST['fechafinal'] = $this->FormatoFecha($fechas[FECHA_FINAL]);

        $this->salida = ThemeAbrirTabla('SOAT - PERIODO RECLAMADO');
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'ValidarDatosInformeSoat', array('envio' => $envio));
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "  <tr><td>";
        $this->salida .= $this->EncabezadoEmpresa();
        if ($this->uno == 1) {
            $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "</table><br>";
        }
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td>";
        $this->salida .= "  <fieldset><legend class=\"field\">INFORMACI� DEL ANEXO 1 - FORECAT - CONSOLIDADO</legend>";
        $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"25%\">";
        $this->salida .= "      <label class=\"" . $this->SetStyle("fechadradi") . "\">FECHA DE RADICACI�: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"25%\">";
        if (empty($_POST['fechadradi'])) {
            $_POST['fechadradi'] = date("d/m/Y");
        }
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechadradi\" value=\"" . $_POST['fechadradi'] . "\" maxlength=\"10\" size=\"15\">";
        $this->salida .= "      " . ReturnOpenCalendario('forma', 'fechadradi', '/') . "";
        $this->salida .= "      </td>";
        /* $this->salida .= "      <td width=\"25%\">";
          $this->salida .= "      <label class=\"".$this->SetStyle("numeroradi")."\">N�ERO DE RADICADO: </label>";
          $this->salida .= "      </td>";
          $this->salida .= "      <td width=\"25%\">";
          $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"numeroradi\" value=\"".$_POST['numeroradi']."\" maxlength=\"20\" size=\"20\">";
          $this->salida .= "      </td>"; */
        $this->salida .= "      <td width=\"25%\">";
        $this->salida .= "      <label class=\"" . $this->SetStyle("fechainici") . "\">FECHA INICIAL: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"25%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechainici\" value=\"" . $_POST['fechainici'] . "\" maxlength=\"10\" size=\"15\">";
        $this->salida .= "      " . ReturnOpenCalendario('forma', 'fechainici', '/') . "";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=modulo_list_oscuro>";
        $this->salida .= "      <td width=\"25%\">";
        $this->salida .= "      <label class=\"" . $this->SetStyle("periodorec") . "\">PER�DO RECLAMADO: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"25%\">";
        if (empty($_POST['periodorec'])) {
            $_POST['periodorec'] = date("m/Y");
        }
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"periodorec\" value=\"" . $_POST['periodorec'] . "\" maxlength=\"7\" size=\"7\">";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"25%\">";
        $this->salida .= "      <label class=\"" . $this->SetStyle("fechafinal") . "\">FECHA FINAL: </label>";
        $this->salida .= "      </td>";
        $this->salida .= "      <td width=\"25%\">";
        $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechafinal\" value=\"" . $_POST['fechafinal'] . "\" maxlength=\"10\" size=\"15\">";
        $this->salida .= "      " . ReturnOpenCalendario('forma', 'fechafinal', '/') . "";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        /* $this->salida .= "      <tr class=modulo_list_oscuro>";
          $this->salida .= "      <td colspan=\"2\">";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>"; */
        $this->salida .= "      </table>";
        $this->salida .= "  </fieldset>";
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\"><br>";
        $this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "      <tr>";
        $this->salida .= "      <td align=\"center\" width=\"50%\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"generar\" value=\"GENERAR REPORTE\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </form>";
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarRad');
        $this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <td align=\"center\" width=\"50%\">";
        $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
        $this->salida .= "      </td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      </table>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /*     * ********************************************************************************
     *
     * ********************************************************************************* */

    function FormaBuscarTerceros() {
        $this->BuscarTerceros();

        $this->salida .= ThemeAbrirTabla("BUSCAR TERCEROS");
        $this->salida .= "	<script>\n";
        $this->salida .= "		function BuscarFactura(prefijo,factura)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			window.opener.document.forma.Factura.value=factura;\n";
        $this->salida .= "			pref = window.opener.document.forma.PrefijoFac;\n";
        $this->salida .= "			for(i = 0; i<pref.options.length; i++)\n";
        $this->salida .= "			{\n";
        $this->salida .= "				if(pref.options[i].value == prefijo)\n";
        $this->salida .= "					window.opener.document.forma.PrefijoFac.selectedIndex = i;\n";
        $this->salida .= "			}\n";
        $this->salida .= "			window.opener.document.forma.submit();\n";
        $this->salida .= "			Cerrar();\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function Cerrar()\n";
        $this->salida .= "		{\n";
        $this->salida .= "			window.close();\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function mOvr(src,clrOver)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			src.style.background = clrOver;\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function mOut(src,clrIn)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			src.style.background = clrIn;\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function limpiarCampos(objeto)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			objeto.nombre_tercero.value = \"\";\n";
        $this->salida .= "			objeto.tercero_id.value = \"\";\n";
        $this->salida .= "			objeto.tipo_id_tercero.selectedIndex='0';\n";
        $this->salida .= "		}\n";
        $this->salida .= "	</script>\n";
        $this->salida .= "	<table width=\"70%\" align=\"center\" >\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td align=\"center\">\n";
        $this->salida .= "				<form name=\"buscador\" action=\"" . $this->action1 . "\" method=\"post\">\n";
        $this->salida .= "					<fieldset><legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
        $this->salida .= "						<table>\n";
        $this->salida .= "							<tr><td class=\"normal_10AN\">TIPO DOCUMENTO CLIENTE</td>\n";
        $this->salida .= "									<td>\n";
        $this->salida .= "										<select name=\"tipo_id_tercero\" class=\"select\">\n";
        $this->salida .= "											<option value='0'>-----SELECCIONAR-----</option>\n";

        $Tipos = $this->ObtenerTipoIdTercero();
        $this->BuscarIdPaciente($Tipos, $this->rqs['tipo_id_tercero']);

        $this->salida .= "										</select>\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "								</tr>\n";
        $this->salida .= "								<tr>\n";
        $this->salida .= "									<td class=\"normal_10AN\">DOCUMENTO</td>\n";
        $this->salida .= "									<td>\n";
        $this->salida .= "										<input type=\"text\" class=\"input-text\" name=\"tercero_id\" size=\"30\" maxlength=\"32\" value=\"" . $this->rqs['tercero_id'] . "\">\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "								</tr>\n";
        $this->salida .= "								<tr>\n";
        $this->salida .= "									<td class=\"normal_10AN\">NOMBRE</td>\n";
        $this->salida .= "									<td>\n";
        $this->salida .= "										<input type=\"text\" class=\"input-text\" name=\"nombre_tercero\" size=\"30\" maxlength=\"100\" value=\"" . $this->rqs['nombre_tercero'] . "\">\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "								</tr>\n";
        $this->salida .= "								<tr>\n";
        $this->salida .= "									<td class=\"normal_10AN\" align=\"center\" colspan=\"2\"><br>\n";
        $this->salida .= "										<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
        $this->salida .= "										<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
        $this->salida .= "									</td>\n";
        $this->salida .= "								</tr>\n";
        $this->salida .= "							</table>\n";
        $this->salida .= "						</fieldset>\n";
        $this->salida .= "					</form>\n";
        $this->salida .= "				</td>\n";
        $this->salida .= "			</tr>\n";
        $this->salida .= "		</table>\n";
        if (sizeof($this->Terceros) > 0) {
            $this->salida .= "	<table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "			<tr class=\"modulo_table_list_title\" height=\"19\">\n";
            $this->salida .= "				<td width=\"2%\"></td>\n";
            $this->salida .= "				<td width=\"50%\"><b>NOMBRE CLIENTE</b></td>\n";
            $this->salida .= "				<td width=\"24%\"><b>N FACTURA</b></td>\n";
            $this->salida .= "				<td width=\"24%\"><b>FECHA REGISTRO</b></td>\n";
            $this->salida .= "			</tr>";
            $i = 0;
            foreach ($this->Terceros as $key => $Celdas) {
                if ($i % 2 == 0) {
                    $estilo = 'modulo_list_oscuro';
                    $background = "#CCCCCC";
                } else {
                    $estilo = 'modulo_list_claro';
                    $background = "#DDDDDD";
                }
                $i++;
                $opcion = "	<a class=\"label_error\" href=\"javascript:BuscarFactura('" . $Celdas['prefijo'] . "','" . $Celdas['factura_fiscal'] . "')\" title=\"SELECCIONAR\">\n";
                $opcion .= "	<img src=\"" . GetThemePath() . "/images/checkno.png\" border=\"0\"></a>\n";

                $this->salida .= "			<tr class=\"" . $estilo . "\" height=\"21\" onmouseout=mOut(this,\"" . $background . "\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
                $this->salida .= "				<td align=\"center\" >$opcion</td>\n";
                $this->salida .= "				<td align=\"justify\">" . $Celdas['nombre_tercero'] . "</td>\n";
                $this->salida .= "				<td align=\"left\"   >" . $Celdas['prefijo'] . " " . $Celdas['factura_fiscal'] . "</td>\n";
                $this->salida .= "				<td align=\"left\"   >" . $Celdas['fecha'] . "</td>\n";
                $this->salida .= "			</tr>\n";
            }
            $this->salida .= "	</table><br>\n";

            $Paginador = new ClaseHTML();
            $this->salida .= "		" . $Paginador->ObtenerPaginado($this->conteo, $this->paginaActual, $this->action2);
            $this->salida .= "		<br>\n";
        } else {
            $this->salida .= "	<table border=\"0\" width=\"90%\" align=\"center\">\n";
            $this->salida .= "		" . $this->SetStyle("MensajeError");
            $this->salida .= "  </table>\n";
        }
        $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td align=\"center\">\n";
        $this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Cerrar\" onclick=\"Cerrar()\" >\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     * **
     * */
    function FrmVerReporteEnvio($envio, $var) {
        $this->salida = ThemeAbrirTabla("", "65%");
        $this->salida .= "	<script>\n";
        $this->salida .= "		function Cerrar()\n";
        $this->salida .= "		{\n";
        $this->salida .= "			window.close();\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function VerOpciones()\n";
        $this->salida .= "		{\n";
        $this->salida .= "			if (document.getElementById('opciones').style.display == 'none'){";
        $this->salida .= "				document.getElementById('opciones').style.display = 'block';\n";
        $this->salida .= "			}else\n";
        $this->salida .= "			{\n";
        $this->salida .= "				document.getElementById('opciones').style.display = 'none';\n";
        $this->salida .= "			}\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function Ejecutar(funcion)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			alert(funcion);\n";
        $this->salida .= "		}\n";
        $this->salida .= "	</script>\n";
        //$tipo_reporte = $this->ConsultaTipoReporte($tmp_arr,'ENVIO_FACTURA');
        $tipo_reporte = $var[tipo_reporte];
        $reporte = new GetReports();
        $mostrar = $reporte->GetJavaReport('app', 'Facturacion_Fiscal', 'enviosHTM', array('envio' => $envio, 'tipo_reporte' => $tipo_reporte), array('rpt_name' => 'envio', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
        $funcion = $reporte->GetJavaFunction();
        $this->salida .=$mostrar;
        //$this->salida .= "         <td align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"IMPRIMIR\" onclick=\"javascript:$funcion\"></td>";
        $this->salida .= "	<table width=\"75%\" align=\"center\">\n";
        $this->salida .= "		<tr class=\"modulo_list_claro\" height=\"21\" >\n";
        $this->salida .= "			<td align=\"center\" colspan=\"2\">REPORTE $var[tipo_reporte] GENERADO SATISFACTORIAMENTE.</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td align=\"center\">\n";
        $this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Cerrar\" onclick=\"javascript:Cerrar()\" >\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "			<td align=\"center\" class=\"NORMAL_10\">";
        $this->salida .= "				<input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"IMPRIMIR\" onclick=\"javascript:$funcion\">";
        $this->salida .= "			</td>";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
    }

    /**
     *
     */
    function FormaDatosPaciente() {
        $pct = $this->ReturnModuloExterno('app', 'DatosPaciente', 'user');

        $pct->SetActionVolver($this->action['volver']);
        $pct->FormaDatosPaciente($this->action);

        $this->SetJavaScripts("Ocupaciones");
        $this->salida = $pct->salida;
        return true;
    }

    /**
     * 
     * 
     * @return boolean
     */
    function DatosPaciente() {
        return true;
    }

    /**
     * Metodo de control para mostrar el detalle de las facturas
     *
     * @return boolean
     */
    function FormaMostrarInformacionFactura() {
        $request = $_REQUEST;
        IncludeFileModulo("DetalleFacturas", "RemoteXajax", "app", "Facturacion_Fiscal");
        $this->SetXajax(array("InformacionGlosa", "InformacionRecibo", "InformacionNota", "InformacionGlosaDetalle"), null, "ISO-8859-1");
        switch ($request['tipo_doc']) {
            case "NC":
                $titulo = "NOTAS CREDITO " . (($request['sw_clase_factura'] == '1') ? "" : "DE CONTADO ");
                break;
            case "ND":
                $titulo = "NOTAS DEBITO " . (($request['sw_clase_factura'] == '1') ? "" : "DE CONTADO ");
                break;
            case "NG":
                $titulo = "GLOSAS";
                break;
            case "RT":
                $titulo = "RECIBOS DE CAJA DE TESORERIA ";
                break;
        }
        $this->salida = ThemeAbrirTabla("INFORMACION DE " . $titulo . " DE LA FACTURA");
        $this->salida .= "<div id=\"informacion_factura\"><div>\n";
        $this->salida .= "<script>\n";
        $this->salida .= "	function MostrarDetalleGlosa(prefijo,factura_fiscal,empresa)\n";
        $this->salida .= "	{\n";
        $this->salida .= "    xajax_InformacionGlosa(prefijo,factura_fiscal,empresa);\n";
        $this->salida .= "	}\n";
        $this->salida .= "	function MostrarGlosa(glosa,empresa)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		xajax_InformacionGlosaDetalle(empresa,glosa);\n";
        $this->salida .= "	}\n";
        switch ($request['tipo_doc']) {
            case "NC":
                $this->salida .= "  xajax_InformacionNota('" . $request['prefijo'] . "','" . $request['factura_fiscal'] . "','" . $request['empresa'] . "','" . $request['sw_clase_factura'] . "');\n";
                break;
            case "ND":
                $this->salida .= "  xajax_InformacionNota('" . $request['prefijo'] . "','" . $request['factura_fiscal'] . "','" . $request['empresa'] . "','" . $request['sw_clase_factura'] . "');\n";
                break;
            case "NG":
                $this->salida .= "  xajax_InformacionGlosa('" . $request['prefijo'] . "','" . $request['factura_fiscal'] . "','" . $request['empresa'] . "')\n";
                break;
            case "RT":
                $this->salida .= "  xajax_InformacionRecibo('" . $request['prefijo'] . "','" . $request['factura_fiscal'] . "','" . $request['empresa'] . "')\n";
                break;
        }

        $this->salida .= "</script>\n";
        $this->salida .= "	<center>\n";
        $this->salida .= "	  <a href=\"javascript:window.close()\" class=\"label_error\">CERRAR</a>\n";
        $this->salida .= "	</center>\n";

        $this->salida .= ThemeCerrarTabla();
        return true;
    }
    function BuscarIdTipoAfiliadoS($tipo_afiliado) {
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
        for ($i = 0; $i < sizeof($tipo_afiliado); $i++) {
//            if ($tipo_afiliado[$i][tipo_afiliado_id] == $TipoAfiliado) {
//                $this->salida .=" <option value=\"" . $tipo_afiliado[$i][tipo_afiliado_id] . "\" selected>" . $tipo_afiliado[$i][tipo_afiliado_nombre] . "</option>";
//            }
//            if ($tipo_afiliado[$i][tipo_afiliado_id] == $_SESSION['SOLICITUDAUTORIZACION']['AFILIADO'][$tipo_afiliado[$i][tipo_afiliado_id]]) {
//                $this->salida .=" <option value=\"" . $tipo_afiliado[$i][tipo_afiliado_id] . "\" selected>" . $tipo_afiliado[$i][tipo_afiliado_nombre] . "</option>";
//            } else {
                $this->salida .=" <option value=\"" . $tipo_afiliado[$i][tipo_afiliado_id] . "\">" . $tipo_afiliado[$i][tipo_afiliado_nombre] . "</option>";
//            }
        }
    }

}

//fin clase
?>