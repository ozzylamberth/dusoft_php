<?php

/**
 * $Id: app_CajaGeneral_userclasses_HTML.php,v 1.8 2010/11/25 18:24:14 johanna Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de una caja general(hospitalizacion,conceptos,c.externa,punto de venta)
 */
/**
 *  app_CajaGeneral_userclasses_HTML.php
 *
 * Clase para procesar los datos del formulario mediante la operaciones de consulta ,captura y de insercion.
 * del modulo caja general en la parte d hospitalizacion, se extiende la clase Caja General y asi pueden ser
 * utilizados los metodos de esta clase en la anterior.
 */
IncludeClass("ClaseHTML");

class app_CajaGeneral_userclasses_HTML extends app_CajaGeneral_user {

    /**
     * Constructor de la clase app_Facturacion_userclasses_HTML
     * El constructor de la clase app_Facturacion_userclasses_HTML se encarga de llamar
     * a la clase app_Facturacion_user quien se encarga de el tratamiento
     * de la base de datos.
     * @return boolean
     */
    function app_CajaGeneral_userclasses_HTML() {
        $this->salida = '';
        $this->app_CajaGeneral_user();
        return true;
    }

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

    /**
     * Muestra los datos del responsable(tercero) del paciente y los datos basicos del paciente
     * nombres, identificacion,numero de ingreso y la fecha y hora de apertura de la cuenta.
     * @access private
     * @return void
     * @param int plan_id
     * @param string tipo documento
     * @param int numero documento
     * @param int ingreso
     * @param string nivel
     * @param date fecha de registro de la cuenta
     */
    function EncabezadoOrdenServicio() {

        if (!empty($_SESSION['CAJA']['EXTERNA'])) {
            $plan = $this->BuscarInfoPlan($_SESSION['CAJA']['AUX']['plan_id']);
            //esta en bd
            if (!empty($plan)) {
                $this->SetJavaScripts('DatosBD');
                $link = "	 <td class=\"modulo_list_claro\" align=\"left\">" . RetornarWinOpenDatosBD($_SESSION['CAJA']['AUX']['tipo_id_paciente'], $_SESSION['CAJA']['AUX']['paciente_id'], $_SESSION['CAJA']['AUX']['plan_id']) . "</td>";
            }
        }
        $arreglo = $this->BuscarNombreTercero($_SESSION['CAJA']['AUX']['plan_id']);
        $PlanId = $_SESSION['CAJA']['AUX']['paciente_id'];
        $this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
        $this->salida .= "        <tr>";
        $this->salida .= "           <td width=\"40%\">";
        $this->salida .= "      <table border=\"0\" width=\"93%\" align=\"center\">";
        $this->salida .= "            <tr><td><fieldset><legend class=\"field\">RESPONSABLE</legend>";
        $this->salida .= "              <table height=\"74\" border=\"1\" width=\"95%\" align=\"center\" cellspacing=\"1\">";
        $this->salida .= "                <tr><td class=\"modulo_table_title\" width=\"24%\">RESPONSABLE: </td><td class=\"modulo_list_oscuro\">" . $arreglo[0][nombre_tercero] . "</td></tr>";
        $this->salida .= "                <tr><td class=\"modulo_table_title\" 24%\">IDENTIFICACION: </td><td class=\"modulo_list_claro\">" . $arreglo[0][tipo_id_tercero] . " " . $arreglo[0][tercero_id] . "</td></tr>";
        $this->salida .= "                <tr><td class=\"modulo_table_title\" width=\"24%\">PLAN: </td><td class=\"modulo_list_oscuro\">" . $arreglo[0][plan_descripcion] . "</td></tr>";
        //$this->salida .= "                <tr><td class=\"modulo_table_title\" width=\"24%\">NIVEL: </td><td class=\"modulo_list_claro\">$Nivel</td></tr>";
        $this->salida .= "                   </table>";
        $this->salida .= "              </fieldset></td></tr></table>";
        $this->salida .= "           </td>";
        $this->salida .= "           <td>";
        $this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= "            <tr><td><fieldset><legend class=\"field\">DATOS DEL PACIENTE</legend>";
        $this->salida .= "              <table border=\"1\" width=\"97%\" align=\"center\" cellspacing=\"1\" >";
        $this->salida .= "                <tr><td class=\"modulo_table_title\" width=\"35%\">PACIENTE: </td><td class=\"modulo_list_oscuro\">" . $_SESSION['CAJA']['AUX']['nom'] . "</td></tr>";
        $this->salida .= "                <tr><td class=\"modulo_table_title\">IDENTIFICACION: </td><td class=\"modulo_list_claro\">" . $_SESSION['CAJA']['AUX']['tipo_id_paciente'] . "  " . $_SESSION['CAJA']['AUX']['paciente_id'] . "</td></tr>";
        if (file_exists("protocolos/" . $_SESSION['CAJA']['AUX']['protocolo'] . "")) {
            $Protocolo = $_SESSION['CAJA']['AUX']['protocolo'];
            $this->salida .= "<script>";
            $this->salida .= "function Protocolo(valor){";
            $this->salida .= "window.open('protocolos/'+valor,'PROTOCOLO','');";
            $this->salida .= "}";
            $this->salida .= "</script>";
            $accion = "javascript:Protocolo('$Protocolo')";
            $this->salida .= "                <tr><td class=\"modulo_table_title\" width=\"24%\" >PROTOCOLO: </td><td class=\"modulo_list_claro\" align='left'><a href=\"$accion\">$Protocolo</a></td></tr>";
        }
        if ($link) {
            $this->salida .= "                <tr><td  class=\"modulo_table_title\">DATOS DE BD: </td> $link</tr>";
        }
        $this->salida .= "                    </table>";
        $this->salida .= "              </fieldset></td></tr></table>";
        $this->salida .= "           </td>";
        $this->salida .= "        </tr>";
        $this->salida .= "    </table>";
    }

    /**
     * Muestra los datos del responsable(tercero) del paciente y los datos basicos del paciente
     * nombres, identificacion,numero de ingreso y la fecha y hora de apertura de la cuenta.
     * @access private
     * @return void
     * @param int plan_id
     * @param string tipo documento
     * @param int numero documento
     * @param int ingreso
     * @param string nivel
     * @param date fecha de registro de la cuenta
     */
    function EncabezadoConsultaExt($TipoId, $PacienteId, $PlanId) {
        $PlanId = $_SESSION['CAJA']['PLAN'];
        $TipoId = $_SESSION['CAJA']['TIPO_ID_PACIENTE'];
        $PacienteId = $_SESSION['CAJA']['PACIENTEID'];
        $Nivel = $_SESSION['CAJA']['NIVEL'];
        //$datos=$this->CallMetodoExterno('app','Triage','user','BuscarPlanes',$argumentos=array('PlanId'=>$PlanId));
        //$tercero=$this->CallMetodoExterno('app','Facturacion','user','BuscarTercero',$argumentos=array('Tercero'=>$datos[tercero_id],'TerceroId'=>$datos[tipo_id_tercero]));
        $Nombres = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'BuscarNombresPaciente', $argumentos = array('TipoId' => $TipoId, 'PacienteId' => $PacienteId));
        $Apellidos = $this->CallMetodoExterno('app', 'Facturacion', 'user', 'BuscarApellidosPaciente', $argumentos = array('TipoId' => $TipoId, 'PacienteId' => $PacienteId));
        $this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
        $this->salida .= "        <tr>";
        $this->salida .= "           <td width=\"40%\">";
        $this->salida .= "      <table border=\"0\" width=\"93%\" align=\"center\">";
        $this->salida .= "            <tr><td><fieldset><legend class=\"field\">RESPONSABLE</legend>";
        $this->salida .= "              <table height=\"74\" border=\"1\" width=\"95%\" align=\"center\" cellspacing=\"1\">";
        $this->salida .= "                <tr><td class=\"modulo_table_title\" width=\"24%\">RESPONSABLE: </td><td class=\"modulo_list_oscuro\">" . $_SESSION['CAJA']['NOMBRE_TERCERO'] . "</td></tr>";
        $this->salida .= "                <tr><td class=\"modulo_table_title\" 24%\">IDENTIFICACION: </td><td class=\"modulo_list_claro\">" . $_SESSION['CAJA']['TIPO_ID_TERCERO'] . " " . $_SESSION['CAJA']['TERCEROID'] . "</td></tr>";
        $this->salida .= "                <tr><td class=\"modulo_table_title\" width=\"24%\">PLAN: </td><td class=\"modulo_list_oscuro\">$PlanId</td></tr>";
        $this->salida .= "                <tr><td class=\"modulo_table_title\" width=\"24%\">NIVEL: </td><td class=\"modulo_list_claro\">$Nivel</td></tr>";
        $this->salida .= "                   </table>";
        $this->salida .= "              </fieldset></td></tr></table>";
        $this->salida .= "           </td>";
        $this->salida .= "           <td>";
        $this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= "            <tr><td><fieldset><legend class=\"field\">DATOS DEL PACIENTE</legend>";
        $this->salida .= "              <table border=\"1\" width=\"97%\" align=\"center\" cellspacing=\"1\" >";
        $this->salida .= "                <tr><td class=\"modulo_table_title\" width=\"35%\">PACIENTE: </td><td class=\"modulo_list_oscuro\">$Nombres $Apellidos</td></tr>";
        $this->salida .= "                <tr><td class=\"modulo_table_title\">IDENTIFICACION: </td><td class=\"modulo_list_claro\">$TipoId  $PacienteId</td></tr>";
        $this->salida .= "                    </table>";
        $this->salida .= "              </fieldset></td></tr></table>";
        $this->salida .= "           </td>";
        $this->salida .= "        </tr>";
        $this->salida .= "    </table>";
    }

    //					$this->EncabezadoPvta($Cuenta,$TipoId,$PacienteId,"",$NombrePaciente,$PagareNumero,$Empresa,$Prefijo);
    function EncabezadoPvta($Cuenta, $TipoId, $PacienteId, $PlanId, $nombrepaciente, $pagarenumero, $empresa, $prefijo) {
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
            if (!empty($empresa) AND !empty($prefijo) AND !empty($pagarenumero)) {
                $_SESSION['CAJA']['emp'] = $empresa;
                $_SESSION['CAJA']['pre'] = $prefijo;
                $_SESSION['CAJA']['pag'] = $pagarenumero;
                //$_SESSION['CAJA']['nombrepac']=$nombrepaciente;
            } else {
                $empresa = $_SESSION['CAJA']['emp'];
                $prefijo = $_SESSION['CAJA']['pre'];
                $pagarenumero = $_SESSION['CAJA']['pag'];
                //$nombrepaciente=$_SESSION['CAJA']['nombrepac'];
            }
            $datos = $this->ConsultaCabeceraPagare($empresa, $prefijo, $pagarenumero);
            $this->salida .= "<BR><table border=\"0\" class='modulo_table_title' width=\"80%\" align=\"center\">";
            $this->salida .= "  <tr class='modulo_table_title'><td align=\"center\" colspan=\"3\">DATOS PACIENTE</td></tr>";
            $this->salida .= "  <tr class='modulo_table_title'><td width=\"15%\">IDENTIFICACION</td><td width=\"65%\" align=\"center\">NOMBRE PACIENTE</td><td width=\"20%\">PAGARE NUMERO</td></tr>";
            $this->salida .= "  <tr align=\"center\"><td class=\"modulo_list_claro\">" . $TipoId . "-" . $PacienteId . "</td><td class=\"modulo_list_claro\" align=\"left\" >" . $nombrepaciente . "</td><td class=\"modulo_list_claro\">" . $prefijo . "-" . $pagarenumero . "</td></tr>";
            $this->salida .= "</table>";
            if (sizeof($datos) > 0) {
                $this->salida .= "<BR><table border=\"0\" class='modulo_table_title' width=\"80%\" align=\"center\">";
                $this->salida .= " <tr class='modulo_table_title' width=\"100%\"><td align=\"center\" colspan=\"3\">DATOS RESPONSABLES</td></tr>";
                $this->salida .= " <tr class='modulo_table_title'><td width=\"20%\">IDENTIFICACION</td><td width=\"80%\" align=\"center\">NOMBRE</td></tr>";
                for ($i = 0; $i < sizeof($datos); $i++) {
                    if ($i % 2) {
                        $estilo = 'modulo_list_claro';
                    } else {
                        $estilo = 'modulo_list_oscuro';
                    }
                    $tipotercero = $datos[$i][tipo_id_tercero];
                    $idtercero = $datos[$i][tercero_id];
                    $nombre = $datos[$i][nombre_tercero];
                    $this->salida .= "                <tr class=\"$estilo\" align=\"center\"><td>" . $tipotercero . "-" . $idtercero . "</td><td align=\"left\">" . $nombre . "</td></tr>";
                }
                $this->salida .= "              </table>";
            }
        } else {
            $datos = $this->ConsultaCabeceraPvta($Cuenta);
            for ($i = 0; $i < sizeof($datos); $i++) {
                $planv = $datos[$i][plan_pv_id];
                $tipotercero = $datos[$i][tipo_id_tercero];
                $idtercero = $datos[$i][tercero_id];
                $nombre = $datos[$i][nombre_tercero];
                $razon = $datos[$i][razon_social];
                $descripcion = $datos[$i][descripcion];
            }

            $this->salida .= "  <table  border=\"0\" width=\"95%\" align=\"center\" >";
            $this->salida .= "        <tr>";
            $this->salida .= "           <td width=\"50%\">";
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= "            <tr><td><fieldset><legend class=\"field\">RESPONSABLE</legend>";
            $this->salida .= "              <table border=\"0\" width=\"99%\" align=\"center\" cellspacing=\"1\">";
            $this->salida .= "                <tr><td class=\"modulo_table_title\"width=\"32%\">RESPONSABLE: </td><td class=\"modulo_list_oscuro\">$nombre</td></tr>";
            $this->salida .= "                <tr><td class=\"modulo_table_title\">IDENTIFICACION: </td><td class=\"modulo_list_claro\">$idtercero</td></tr>";
            $this->salida .= "                <tr><td class=\"modulo_table_title\">PLAN: </td><td class=\"modulo_list_claro\">$planv</td></tr>";
            $this->salida .= "                <tr><td class=\"modulo_table_title\">NIVEL: </td><td class=\"modulo_list_oscuro\">$Nivel</td></tr>";
            $this->salida .= "                   </table>";
            $this->salida .= "              </fieldset></td></tr></table>";
            $this->salida .= "           </td>";
            $this->salida .= "           <td>";
            $this->salida .= "      <table border=\"0\" width=\"98%\" align=\"center\">";
            $this->salida .= "            <tr><td><fieldset><legend class=\"field\">DATOS DE LA EMPRESA</legend>";
            $this->salida .= "              <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= "                <tr><td class=\"modulo_table_title\" width=\"30%\">EMPRESA: </td><td class=\"modulo_list_oscuro\">$razon</td></tr>";
            $this->salida .= "                <tr class=\"modulo_list_claro\"><td  class=\"modulo_table_title\">CENTRO UTILIDAD: </td><td class=\"modulo_list_claro\">$descripcion</td></tr>";
            $this->salida .= "                    </table>";
            $this->salida .= "              </fieldset></td></tr></table>";
            $this->salida .= "           </td>";
            $this->salida .= "        </tr>";
            $this->salida .= "    </table>";
        }
    }

    function EncabezadoConceptos($dat) {
        $nombre = $_SESSION['CAJA']['NOMBRE_TERCERO'];
        $idtercero = $_SESSION['CAJA']['TIPO_ID_TERCERO'];
        $tercero = $_SESSION['CAJA']['TERCEROID'];
        $direccion = $_SESSION['CAJA']['DIRECCION'];
        $telefono = $_SESSION['CAJA']['TELEFONO'];
        $email = $_SESSION['CAJA']['MAIL'];

        $this->salida .= "      <table  border=\"0\" width=\"90%\" align=\"center\">  ";
        $this->salida .= "            <tr><td><fieldset><legend class=\"field\">RESPONSABLE</legend>";
        $this->salida .= "              <table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "                <tr><td class=\"modulo_table_title\" class=\"label\" width=\"24%\">RESPONSABLE: </td><td class=\"modulo_list_oscuro\">" . $idtercero . "&nbsp;-&nbsp;" . $tercero . "&nbsp&nbsp; " . $nombre . "</td></tr>";
        $this->salida .= "                <tr><td class=\"modulo_table_title\" class=\"label\" width=\"24%\">DIRECCI�N: </td><td class=\"modulo_list_claro\">$direccion</td></tr>";
        $this->salida .= "                <tr><td class=\"modulo_table_title\" class=\"label\" width=\"24%\">TELEFONO: </td><td class=\"modulo_list_oscuro\">$telefono</td></tr>";
        $this->salida .= "                <tr><td class=\"modulo_table_title\" class=\"label\" width=\"24%\">E-MAIL: </td><td class=\"modulo_list_claro\">$email</td></tr>";
        $this->salida .= "                   </table>";
        $this->salida .= "              </fieldset></td></tr></table>";
    }

//AUTORIZACION PAGARES
    function autorizacionPagares($spy, $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId) {
        /*            if(!$PlanId)
          {
          $PlanId=$_REQUEST['PlanId'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Nivel=$_REQUEST['Nivel'];
          $FechaC=$_REQUEST['FechaC'];
          $Cuenta=$_REQUEST['Cuenta'];
          $Efectivo=$_REQUEST['efectivo'];
          $Total=$_REQUEST['valorpagar'];
          $spy=$_REQUEST['spy'];
          $Cajaid=$_REQUEST['Cajaid'];
          } */
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Cuenta = $_REQUEST['Cuenta'];
        $spy = $_REQUEST['spy'];
        $Cajaid = $_REQUEST['Cajaid'];
        $NombrePaciente = $_REQUEST['NombrePaciente'];
        $PagareNumero = $_REQUEST['PagareNumero'];
        $Empresa = $_REQUEST['Empresa'];
        $Prefijo = $_REQUEST['Prefijo'];
        $Valor = $_REQUEST['Valor'];
        $DocumentoId = $_REQUEST['DocumentoId'];

        $this->salida .= ThemeAbrirTabla('AUTORIZACIONES');

        if ($spy == '2') { // esto es por si es credito...
            $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarConfirmaC', array('spy' => 2, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Cajaid' => $Cajaid, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente, 'DocumentoId' => $DocumentoId)) . '" method="post">';
        } elseif ($spy == '3') { //esto es por si es cheque...
            $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarConfirmaT', array('spy' => 3, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Cajaid' => $Cajaid, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente, 'DocumentoId' => $DocumentoId)) . '" method="post">';
        }

        //$this->ReturnMetodoExterno('app','Facturacion','user','LlamadaFormaEncabezado',array('PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$FechaC));
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
            $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, '', $NombrePaciente, $PagareNumero, $Empresa, $Prefijo);
            //$this->ReturnMetodoExterno('app','Facturacion','user','LlamadaFormaEncabezado',array('PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$FechaC));
            $accion1 = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaPagares', array('spy' => 2, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Cajaid' => $Cajaid, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente, 'DocumentoId' => $DocumentoId));
        }

        $this->salida .='<BR>';
        $this->salida.="<table border=\"0\" align=\"center\" width=\"40%\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida.=" <tr>";
        $this->salida.="<td class=\"" . $this->SetStyle("entconfirma") . "\">Entidad Confirma :</td>";
        $datos = $this->ComboEntidadConfirma();
        $this->salida.="<td><select name='entconfirma' class='select'>";
        for ($i = 0; $i < sizeof($datos); $i++) {
            $this->salida.="<option value=" . $datos[$i][entidad_confirma] . ">" . $datos[$i][descripcion] . "</option>";
        }
        $this->salida.="</select></td>";

        //$this->salida.="<td> <input name=\"entconfirma\" maxlength='4'  class=\"input-text\"  type=\"text\"></td>";
        $this->salida.=" </tr>";
        $this->salida.=" <tr>";
        $this->salida.="<td class=\"" . $this->SetStyle("funconfirma") . "\">Funcionario Confirma :</td>";
        $this->salida.="<td><input name=\"funconfirma\" class=\"input-text\" type=\"text\" maxlength=\"40\" value=" . $_REQUEST['funconfirma'] . "></td>";
        $this->salida.=" </tr>";
        $this->salida.=" <tr>";
        $this->salida.=" <td class=\"" . $this->SetStyle("numconfirma") . "\"> <p>Numero de Confirmaci�n :</p></td>";
        $this->salida.="  <td><input name=\"numconfirma\" class=\"input-text\" type=\"text\" size='20' maxlength=\"15\" value=" . $_REQUEST['numconfirma'] . "></td>";
        $this->salida.=" </tr>";
        $this->salida.=" <tr>";
        $this->salida.="   <td class=\"" . $this->SetStyle("fechaconfirma") . "\">Fecha :</td>";
        //$this->salida.="   <td><input type=\"text\" class=\"input-text\" name=\"fechaconfirma\" value=".date("Y/m/d")."></td>";
        if (empty($_REQUEST['fechaconfirma'])) {
            $_REQUEST['fechaconfirma'] = date("d-m-Y");
        };
        $this->salida .= "<td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"fechaconfirma\" size='11' maxlength=\"10\" value=\"" . $_REQUEST['fechaconfirma'] . "\">" . ReturnOpenCalendario('forma2', 'fechaconfirma', '-') . "</td>";
        $this->salida.=" </tr>";
        $this->salida.=" </table>";
        $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
        $this->salida.=" <tr>";
        $this->salida.=" <td align=\"center\">";
        $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\"></form>";
        $this->salida.=" </td>";
        $this->salida .="<form name=forma action=" . $accion1 . " method=post>";
        $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form></td>";
        $this->salida.=" </tr>";
        $this->salida.=" </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

//FIN AUTORIZACION PARAGES
    /**
     *
     */
    function autorizacion($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy, $spy) {
        if (!$PlanId) {
            $PlanId = $_REQUEST['PlanId'];
            $TipoId = $_REQUEST['TipoId'];
            $PacienteId = $_REQUEST['PacienteId'];
            $Ingreso = $_REQUEST['Ingreso'];
            $Nivel = $_REQUEST['Nivel'];
            $FechaC = $_REQUEST['FechaC'];
            $Cuenta = $_REQUEST['Cuenta'];
            $Efectivo = $_REQUEST['efectivo'];
            $Total = $_REQUEST['valorpagar'];
            $spy = $_REQUEST['spy'];
            $Cajaid = $_REQUEST['Cajaid'];
        }
        $this->salida .= ThemeAbrirTabla('AUTORIZACIONES');

        if ($spy == '2') { // esto es por si es credito...
            $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarConfirmaC', array('spy' => 1, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid)) . '" method="post">';
        } elseif ($spy == '3') { //esto es por si es cheque...
            $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarConfirmaT', array('spy' => 1, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid)) . '" method="post">';
        }

        //$this->ReturnMetodoExterno('app','Facturacion','user','LlamadaFormaEncabezado',array('PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$FechaC));
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $FechaC));
            $accion1 = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid));
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            $this->EncabezadoConsultaExt($TipoId, $PacienteId, $PlanId);
            $accion1 = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConsultaExterna', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
        }

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
            $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, $PlanId);
            $accion1 = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            $this->EncabezadoConceptos();
            $accion1 = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConceptos', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
            $this->EncabezadoOrdenServicio();
            $accion1 = ModuloGetURL('app', 'CajaGeneral', 'user', 'RetornarFormaOrdenesServicio', array('paso' => $_SESSION['CAJA']['PASO']));
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '08') {
            $this->EncabezadoConceptos();
            $accion1 = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConceptos', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
        }

        $this->salida .='<BR>';
        $this->salida.="<table border=\"0\" align=\"center\" width=\"40%\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida.=" <tr>";
        $this->salida.="<td class=\"" . $this->SetStyle("entconfirma") . "\">Entidad Confirma :</td>";
        $datos = $this->ComboEntidadConfirma();
        $this->salida.="<td><select name='entconfirma' class='select'>";
        for ($i = 0; $i < sizeof($datos); $i++) {
            $this->salida.="<option value=" . $datos[$i][entidad_confirma] . ">" . $datos[$i][descripcion] . "</option>";
        }
        $this->salida.="</select></td>";

        //$this->salida.="<td> <input name=\"entconfirma\" maxlength='4'  class=\"input-text\"  type=\"text\"></td>";
        $this->salida.=" </tr>";
        $this->salida.=" <tr>";
        $this->salida.="<td class=\"" . $this->SetStyle("funconfirma") . "\">Funcionario Confirma :</td>";
        $this->salida.="<td><input name=\"funconfirma\" class=\"input-text\" type=\"text\" maxlength=\"40\" value=" . $_REQUEST['funconfirma'] . "></td>";
        $this->salida.=" </tr>";
        $this->salida.=" <tr>";
        $this->salida.=" <td class=\"" . $this->SetStyle("numconfirma") . "\"> <p>Numero de Confirmaci�n :</p></td>";
        $this->salida.="  <td><input name=\"numconfirma\" class=\"input-text\" type=\"text\" size='20' maxlength=\"15\" value=" . $_REQUEST['numconfirma'] . "></td>";
        $this->salida.=" </tr>";
        $this->salida.=" <tr>";
        $this->salida.="   <td class=\"" . $this->SetStyle("fechaconfirma") . "\">Fecha :</td>";
        //$this->salida.="   <td><input type=\"text\" class=\"input-text\" name=\"fechaconfirma\" value=".date("Y/m/d")."></td>";
        if (empty($_REQUEST['fechaconfirma'])) {
            $_REQUEST['fechaconfirma'] = date("d-m-Y");
        };
        $this->salida .= "<td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"fechaconfirma\" size='11' maxlength=\"10\" value=\"" . $_REQUEST['fechaconfirma'] . "\">" . ReturnOpenCalendario('forma2', 'fechaconfirma', '-') . "</td>";
        $this->salida.=" </tr>";
        $this->salida.=" </table>";
        $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
        $this->salida.=" <tr>";
        $this->salida.=" <td align=\"center\">";
        $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\"></form>";
        $this->salida.=" </td>";
        $this->salida .="<form name=forma action=" . $accion1 . " method=post>";
        $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form></td>";
        $this->salida.=" </tr>";
        $this->salida.=" </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     * La funcion FormaEfectivo visualiza los pagos de efectivos
     *
     * @access public
     * @return boolean
     */
    function FormaEfectivo() {
        $PlanId = $_REQUEST['PlanId'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Nivel = $_REQUEST['Nivel'];
        $FechaC = $_REQUEST['FechaC'];
        $Cuenta = $_REQUEST['Cuenta'];
        $Efectivo = $_REQUEST['efectivo'];
        $Total = $_REQUEST['valorpagar'];

        if (!$Efectivo || !$Total) {
            if (!$Efectivo) {
                $this->frmError["efectivo"] = 1;
            }
            if (!$Total) {
                $this->frmError["valorpagar"] = 1;
            }
            $this->frmError["MensajeError"] = "Debe ingresar los valores.";
            if (!$this->CapturaHospitalizacion('1', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Total)) {
                return false;
            }
            return true;
        }

        if ($Efectivo < $Total) {
            $this->frmError["efectivo"] = 1;
            $this->frmError["MensajeError"] = "Debe ingresar un valor mayor o igual al total a pagar.";
            if (!$this->CapturaHospitalizacion('1', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Total)) {
                return false;
            }
            return true;
        }

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03' or $_SESSION['CAJA']['TIPOCUENTA'] == '04' or $_SESSION['CAJA']['TIPOCUENTA'] == '05') {
            $res = $Total + $_SESSION['CAJA']['SUBTOTAL'];
            if ($res > $_SESSION['CAJA']['SAL'] or ($_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL']) == 0) {
                $this->frmError["efectivo"] = 1;
                $this->frmError["MensajeError"] = "El pago execede el valor del saldo o el saldo es 0";
                if (!$this->CapturaHospitalizacion('1', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Total)) {
                    return false;
                }
                return true;
            }
        }

        $Devuelta = abs($Total - $Efectivo);
        $this->salida .= ThemeAbrirTabla('PAGOS EN EFECTIVO');
        $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarTemporales', array('spy' => 1, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy)) . '" method="post">';
        //$this->ReturnMetodoExterno('app','Facturacion','user','LlamadaFormaEncabezado',array('PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$FechaC));
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $FechaC));
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            $this->EncabezadoConsultaExt($TipoId, $PacienteId, $PlanId);
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
            $Cuenta = $_SESSION['CAJA']['CUENTA'];
            $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, $PlanId);
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            $this->EncabezadoConceptos();
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
            $this->EncabezadoOrdenServicio();
        }
        $this->salida .='<BR>';
        $this->salida .='<table align="center" width="45%" border="0">';
        $this->salida .='<tr>';
        $this->salida .='<td class="' . $this->SetStyle("valorpagar") . '">VALOR EFECTIVO: </td>';
        $this->salida .='<td><input name="valorpagar" type="text" class="input-text" value="' . $Total . '" readonly></td>';
        $this->salida .='</tr>';
        /* $this->salida .='<tr>';
          $this->salida .='<td class="'.$this->SetStyle("efectivo").'">TOTAL EFECTIVO: </td>';
          $this->salida .='<td><input name="efectivo" type="text" class="input-text" value="'.$Efectivo.'" readonly></td>';
          $this->salida .='</tr>';
          $this->salida .='<tr>';
          $this->salida .='<td class="'.$this->SetStyle("efectivo").'">DEVOLUCION: </td>';
          $this->salida .='<td><input name="efectivo" type="text" class="input-text" value="'.$Devuelta.'" readonly></td>';
          $this->salida .='</tr>';
         */$this->salida .='</table>';
        //$this->ConsultaHospitalizacion('1',$Cuenta,$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC,$FechaHoy);
        $this->salida.="<br><table align=\"center\">";
        $this->salida.="<tr>";
        $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Guardar\" class=\"input-submit\"></form></td>";
        $this->salida .='<td>&nbsp;</td>';
        $this->salida .= "<td >";
        $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'DecisionHospitalizacion', array('spy' => 1, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy)) . '" method="post">';
        $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Atras\" class=\"input-submit\"></form></td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

    /**
     * La funcion ConsultaCajaHospitalizacion visualiza la consulta de los pagos anteriores de
     * la caja  a nivel de hospitalizacion, los distintos recibos creados por esa cuenta.
     * @access public
     * @return boolean
     */
    function ConsultaCajaHospitalizacion($Cuenta, $Recibo, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo) {
        $datos = $this->ConsultaPagosCaja($Cuenta, $Recibo, $Prefijo, $PagareNumero);
        if ($datos) {
            $RUTA = $_ROOT . "cache/Recibo" . UserGetUID() . ".pdf";
            $DIR = "printer.php?ruta=$RUTA";
            $DIR = "cache/Recibo" . UserGetUID() . ".pdf";
            $RUTA1 = GetBaseURL() . $DIR;
            //$RUTA = $_ROOT ."classes/classbuscador/buscador.php?tipo=$tipo";

            $mostrar = "\n<script language='javascript'>\n";
            $mostrar.="var rem=\"\";\n";
            $mostrar.="  function abreVentana(){\n";
            $mostrar.="    var nombre=\"\"\n";
            $mostrar.="    var url2=\"\"\n";
            $mostrar.="    var str=\"\"\n";
            $mostrar.="    var width=\"400\"\n";
            $mostrar.="    var height=\"300\"\n";
            $mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
            $mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
            $mostrar.="    var nombre=\"Printer_Mananger\";\n";
            $mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
            $mostrar.="    var url2 ='$RUTA1';\n";
            $mostrar.="    rem = window.open(url2, nombre, str)};\n";

            $mostrar.="function mOvr(src,clrOver) {;\n";
            $mostrar.="src.style.background = clrOver;\n";
            $mostrar.="}\n";

            $mostrar.="function mOut(src,clrIn) {\n";
            $mostrar.="src.style.background = clrIn;\n";
            $mostrar.="}\n";
            $mostrar.="</script>\n";
            $this->salida .="$mostrar";

            if ($_SESSION['CAJA']['PARAM'] == "ShowReport") {
                $this->salida.="<BODY onload=abreVentana();>";
                unset($_SESSION['CAJA']['PARAM']);
            }

            $backgrounds = array('modulo_list_claro' => '#DDDDDD', 'modulo_list_oscuro' => '#CCCCCC');

            $this->salida.="$mostrar";
            $this->salida.=" <br><table border=\"0\" width=\"90%\" align=\"center\">";
            $this->salida.=" <tr><td><fieldset><legend class=\"field\">CONSULTAS ANTERIORES CAJA</legend>";
            $this->salida.="<br><table  align=\"center\" border=\"0\" width=\"85%\">";
            $this->salida.="<tr class=\"modulo_table_list_title \">";
            $this->salida.="  <td>Recibo de Caja</td>";
            $this->salida.="  <td>Fecha de Registro</td>";
            $this->salida.="  <td>Total Efectivos</td>";
            $this->salida.="  <td>Total Cheques</td>";
            $this->salida.="  <td>Total Tarjetas</td>";
            $this->salida.="  <td>Total Bonos</td>";
            $this->salida.="  <td>Total</td>";

            if (!$Recibo) {
                if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06')
                    $this->salida.="  <td>Info</td>";
                $this->salida.="  <td>PDF</td>";
                $this->salida.="  <td>POS</td>";
            }
            $this->salida.="</tr>";
            for ($i = 0; $i < sizeof($datos); $i++) {
                $rcaja = $datos[$i][recibo_caja];
                $empresa = $datos[$i][empresa_id];
                $centro = $datos[$i][centro_utilidad];
                $fech = $datos[$i][fecha_registro];
                $Te = FormatoValor($datos[$i][total_efectivo]);
                $Tc = FormatoValor($datos[$i][total_cheques]);
                $Tt = FormatoValor($datos[$i][total_tarjetas]);
                $Tb = FormatoValor($datos[$i][total_bonos]);

                $TOTAL = FormatoValor($datos[$i][total_abono]);
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB'); align=\"center\">";
                $this->salida.="  <td>" . $datos[$i][prefijo] . $rcaja . "</td>";
                $this->salida.="  <td>$fech</td>";
                $this->salida.="  <td>$Te</td>";
                $this->salida.="  <td>$Tc</td>";
                $this->salida.="  <td>$Tt</td>";
                $this->salida.="  <td>$Tb</td>";
                $this->salida.="  <td class=\"label_error\">$TOTAL</td>";
                $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'DetalleRecibo', array('Cuenta' => $Cuenta, 'Recibo' => $rcaja, 'Empresa' => $empresa, 'CU' => $centro, 'Prefijo' => $datos[$i][prefijo], 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'PagareNumero' => $PagareNumero));
                $accion2 = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarDatos', array('prefijo' => $datos[$i][prefijo], 'cajaid' => $datos[$i][caja_id], 'Recibo' => $rcaja, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'empresa' => $empresa, 'cu' => $centro, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'NombrePaciente' => $NombrePaciente));
                $url_pos = ModuloGetURL('app', 'CajaGeneral', 'user', 'Imprimir_POS_Recibo_Hosp', array('prefijo' => $datos[$i][prefijo], 'cajaid' => $datos[$i][caja_id], 'Recibo' => $rcaja, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'empresa' => $empresa, 'cu' => $centro, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'NombrePaciente' => $NombrePaciente));

                if (!$Recibo) {
                    if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06')
                        $this->salida.="  <td><a href=\"$accion\">VER</a></td>";

                    $this->salida.="  <td><a href=\"$accion2\">IMPRIMIR</a></td>";
                    $this->salida.="  <td><a href=\"$url_pos\">IMPRIMIR POS</a></td>";
                }
                $this->salida.="</tr>";
            }
            $this->salida.="</table>";
            //*****************************
            //*****************************
            //*****************************
            //CONSULTA CIERRE DEVOLUCIONES
            $datosdev = $this->ConsultaDevolucionesCaja($Cuenta, $Recibo, $Prefijo, $PagareNumero);
            $tmp = sizeof($datosdev);

            if ($tmp > 0 AND $_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                $this->salida.="<br><table  align=\"center\" border=\"0\" width=\"85%\">";
                $this->salida.="<tr class=\"modulo_table_list_title \">";
                $this->salida.="  <td>Recibo de Devoluciones</td>";
                $this->salida.="  <td>Fecha de Registro</td>";
                $this->salida.="  <td>Total Devoluci�n</td>";
                $this->salida.="  <td>Total</td>";
                if (!$Recibo) {
                    $this->salida.="  <td>PDF</td>";
                    if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06')
                        $this->salida.="  <td>POS</td>";
                }
                $this->salida.="</tr>";
                for ($i = 0; $i < sizeof($datosdev); $i++) {
                    $rcaja = $datosdev[$i][recibo_caja];
                    $empresa = $datosdev[$i][empresa_id];
                    $centro = $datosdev[$i][centro_utilidad];
                    $fech = $datosdev[$i][fecha_registro];
                    $Te = FormatoValor($datosdev[$i][total_devolucion]);

                    $TOTAL = FormatoValor($datosdev[$i][total_devolucion]);
                    if ($i % 2) {
                        $estilo = 'modulo_list_claro';
                    } else {
                        $estilo = 'modulo_list_oscuro';
                    }
                    $this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB'); align=\"center\">";
                    $this->salida.="  <td>" . $datosdev[$i][prefijo] . "-" . $rcaja . "</td>";
                    $this->salida.="  <td>$fech</td>";
                    $this->salida.="  <td>$Te</td>";
                    $this->salida.="  <td class=\"label_error\">$TOTAL</td>";
                    $accion2 = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarDatos', array('swd' => 'DV', 'prefijo' => $datosdev[$i][prefijo], 'cajaid' => $datosdev[$i][caja_id], 'Recibo' => $rcaja, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'empresa' => $empresa, 'cu' => $centro, 'Nivel' => $Nivel, 'FechaC' => $FechaC));
                    $url_pos = ModuloGetURL('app', 'CajaGeneral', 'user', 'Imprimir_POS_Recibo_Hosp', array('swd' => 'DV', 'prefijo' => $datosdev[$i][prefijo], 'cajaid' => $datosdev[$i][caja_id], 'Recibo' => $rcaja, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'empresa' => $empresa, 'cu' => $centro, 'Nivel' => $Nivel, 'FechaC' => $FechaC));

                    if (!$Recibo) {
                        //$this->salida.="  <td><a href=\"$accion\">VER</a></td>";

                        /*  if($rcaja==$_SESSION['CAJA']['PARAM'])
                          {
                          $this->salida.="  <td><a href=\"javascript:abreVentana()\">IMPRIMIR</a></td>";
                          unset($_SESSION['CAJA']['PARAM']);
                          }
                          else
                          { */
                        $this->salida.="  <td><a href=\"$accion2\">IMPRIMIR</a></td>";
                        // }
                        if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06')
                            $this->salida.="  <td><a href=\"$url_pos\">IMPRIMIR POS</a></td>";
                    }

                    $this->salida.="</tr>";
                }
                $this->salida.="</table>";
            }
            //FIN CONSULTA CIERRE DEVOLUCIONES
            //*********************************
            //*********************************
            //*********************************
            $this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
            $this->salida.="</table>";
            $this->salida.="</fieldset></td></tr></table><br>";
        }
        return true;
    }

    /**
     *
     */
    function FormaDetalleRecibo($Prefijo, $Empresa, $CU, $Recibo, $Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $PagareNumero) {
        $this->salida.= ThemeAbrirTabla('DETALLE RECIBO DE CAJA No. ' . $Recibo);
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $FechaC));
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'Cajaid' => $Cajaid));
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
            $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, $PlanId);
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'Cajaid' => $Cajaid));
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            $this->EncabezadoConceptos($Cuenta, $TipoId, $PacienteId, $PlanId);
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConceptos', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'Cajaid' => $Cajaid));
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            $this->EncabezadoConsultaExt($Cuenta, $TipoId, $PacienteId, $PlanId);
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConsultaExterna', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'Cajaid' => $Cajaid));
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
            $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, '', $NombrePaciente, $PagareNumero, $Empresa, $Prefijo);
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaPagares', array('spy' => 2, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Cajaid' => $Cajaid, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente, 'DocumentoId' => $DocumentoId));
        }
        //ConsultaCajaHospitalizacion($Cuenta,$Recibo,$TipoId,$PacienteId,$Nivel,$PlanId,$Pieza,$Cama,$FechaC,$Ingreso,$PagareNumero,$Prefijo)
        $this->ConsultaCajaHospitalizacion($Cuenta, $Recibo, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $PagareNumero, $Prefijo);
        $this->ConsultaGeneralHospitalizacion('2', $Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Recibo, $PagareNumero, $Prefijo);
        $this->ConsultaGeneralHospitalizacion('3', $Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Recibo, $PagareNumero, $Prefijo);
        $this->ConsultaGeneralHospitalizacion('4', $Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Recibo);
        $this->ConsultaGeneralHospitalizacion('5', $Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Recibo);
        $this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
        $this->salida.="<tr>";
        $this->salida.="  <td align=\"center\">";
        $this->salida .='<form name="forma" action="' . $accion . '" method="post">';
        $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
        $this->salida.="</td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

    /**
     * La funcion ConsultaGeneralHospitalizacion
     * se consulta todos los tipos de pagos efectuados con ese numero de cuenta
     * pagos tarjetas debitos,creditos,cheques,se diferenciara 'por casos' mediante
     * una variable spia esta consulta es general de todos los pagos efectuados
     * con los diferentes recibos de caja existentes
     *
     * @access public
     * @return boolean
     */
    function ConsultaGeneralHospitalizacion($spia, $Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Recibo, $PagareNumero, $Prefijo) {
        if (empty($PagareNumero) || empty($Prefijo)) {
            $PagareNumero = $_SESSION['PAGARE']['NUMERO'];
            $Prefijo = $_SESSION['PAGARE']['PREFIJO'];
        }

        IncludeLib("tarifario");
        if (!$spia) {
            $spia = $_REQUEST['spia'];
            $Cuenta = $_REQUEST['Cuenta'];
            $PlanId = $_REQUEST['PlanId'];
            $TipoId = $_REQUEST['TipoId'];
            $PacienteId = $_REQUEST['PacienteId'];
            $Ingreso = $_REQUEST['Ingreso'];
            $Nivel = $_REQUEST['Nivel'];
            $FechaC = $_REQUEST['FechaC'];
        }

        switch ($spia) {
            case "2": {

                    $datos = $this->MovCheques($Cuenta, $Recibo, $PagareNumero, $Prefijo);


                    if ($datos) {
                        $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"85%\">";
                        $this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"7\">PAGOS CON CHEQUE</td></tr>";
                        $this->salida.="<tr class=\"modulo_table_list_title\">";
                        $this->salida.="  <td>Numero de Cheque</td>";
                        $this->salida.="  <td>Banco</td>";
                        $this->salida.="  <td>Girador</td>";
                        $this->salida.="  <td>Fecha</td>";
                        $this->salida.="  <td>Cta Cte</td>";
                        $this->salida.="  <td>Fecha de Cheque</td>";
                        $this->salida.="  <td>Valor</td>";
                        $this->salida.="</tr>";
                        for ($i = 0; $i < sizeof($datos[$i]); $i++) {
                            $NoCheque = $datos[$i][cheque];
                            $Banco = $datos[$i][descripcion];
                            $Girador = $datos[$i][girador];
                            $Fecha = $datos[$i][fecha];
                            $CtaCte = $datos[$i][cta_cte];
                            $FechaCheque = $datos[$i][fecha_cheque];
                            $vl = FormatoValor($datos[$i][total]);

                            if ($i % 2) {
                                $estilo = 'modulo_list_claro';
                            } else {
                                $estilo = 'modulo_list_oscuro';
                            }
                            $this->salida.="<tr class=\"$estilo\" align=\"center\">";
                            $this->salida.="  <td>$NoCheque</td>";
                            $this->salida.="  <td>$Banco</td>";
                            $this->salida.="  <td>$Girador</td>";
                            $this->salida.="  <td>$Fecha</td>";
                            $this->salida.="  <td>$CtaCte</td>";
                            $this->salida.="  <td>$FechaCheque</td>";
                            $this->salida.="  <td>$vl</td>";
                            $this->salida.="</tr>";
                        }
                        $this->salida.="</table><br>";
                    }
                    break;
                }

            case "3": {
                    $datos = $this->MovTarjetasCredito($Cuenta, $Recibo, $PagareNumero, $Prefijo);
                    if ($datos) {
                        $this->salida.="<table align=\"center\" border=\"0\" width=\"85%\">";
                        $this->salida.="<tr class=\"modulo_table_title\"><td  colspan=\"8\">PAGOS TARJETA CREDITO</td></tr>";
                        $this->salida.="<tr class=\"modulo_table_list_title\">";
                        $this->salida.="  <td>No. Tarjeta</td>";
                        $this->salida.="  <td>Tarjeta</td>";
                        $this->salida.="  <td>No. Autorizacion</td>";
                        $this->salida.="  <td>Fecha</td>";
                        $this->salida.="  <td>Socio</td>";
                        $this->salida.="  <td>Autorizado por</td>";
                        $this->salida.="  <td>Fecha de Expiracion</td>";
                        $this->salida.="  <td>Valor</td>";
                        $this->salida.="</tr>";
                        for ($i = 0; $i < sizeof($datos[$i]); $i++) {
                            $noTarjeta = $datos[$i][tarjeta_numero];
                            $Tarjeta = $datos[$i][descripcion];
                            $NTarjeta = $datos[$i][tarjeta_numero];
                            $FechaExp = $datos[$i][fecha_expira];
                            $Fecha = $datos[$i][fecha];
                            $Socio = $datos[$i][socio];
                            $AutoPor = $datos[$i][autorizado_por];
                            $Valor = FormatoValor($datos[$i][total]);
                            $Auto = $datos[$i][autorizacion];
                            //$Recibo=$datos[$i][recibo_caja];
                            //=$datos[$i][];
                            if ($i % 2) {
                                $estilo = 'modulo_list_claro';
                            } else {
                                $estilo = 'modulo_list_oscuro';
                            }
                            $this->salida.="<tr class=\"$estilo\" align=\"center\">";
                            //$this->salida.="  <td>$Recibo</td>";
                            $this->salida.="  <td>$noTarjeta</td>";
                            $this->salida.="  <td>$Tarjeta</td>";
                            $this->salida.="  <td>$Auto</td>";
                            $this->salida.="  <td>$Fecha</td>";
                            $this->salida.="  <td>$Socio</td>";
                            $this->salida.="  <td>$AutoPor</td>";
                            $this->salida.="  <td>$FechaExp</td>";
                            $this->salida.="  <td>$Valor</td>";
                            $this->salida.="</tr>";
                        }
                        $this->salida.="</table><br>";
                    }
                    break;
                }

            case "4": {
                    $datos = $this->MovTarjetasDebito($Cuenta, $Recibo, $PagareNumero, $Prefijo);
                    if ($datos) {
                        $this->salida.="<table align=\"center\" border=\"0\"  width=\"85%\">";
                        $this->salida.="<tr class=\"modulo_table_title\"><td  colspan=\"5\">PAGOS TARJETA DEBITO</td></tr>";
                        $this->salida.="<tr class=\"modulo_table_list_title\">";
                        $this->salida.="  <td>Recibo Caja</td>";
                        $this->salida.="  <td>No. Tarjeta</td>";
                        $this->salida.="  <td>Tarjeta</td>";
                        $this->salida.="  <td>No. Autorizacion</td>";
                        $this->salida.="  <td>Valor</td>";
                        $this->salida.="</tr>";
                        for ($i = 0; $i < sizeof($datos[$i]); $i++) {
                            $Tarjeta = $datos[$i][descripcion];
                            $Auto = $datos[$i][autorizacion];
                            $Valor = FormatoValor($datos[$i][total]);
                            $Recibo = $datos[$i][recibo_caja];
                            $NTarjeta = $datos[$i][tarjeta_numero];
                            if ($i % 2) {
                                $estilo = 'modulo_list_claro';
                            } else {
                                $estilo = 'modulo_list_oscuro';
                            }
                            $this->salida.="<tr class=\"$estilo\"align=\"center\">";
                            $this->salida.="  <td>$Recibo</td>";
                            $this->salida.="  <td>$NTarjeta</td>";
                            $this->salida.="  <td>$Tarjeta</td>";
                            $this->salida.="  <td>$Auto</td>";
                            $this->salida.="  <td>$Valor</td>";
                            $this->salida.="</tr>";
                        }
                        $this->salida.="</table><br>";
                    }
                    break;
                }

            case "5": {
                    if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                        $datos = $this->MovBonos($Cuenta, $Recibo);
                        if ($datos) {
                            $this->salida.="<table align=\"center\" border=\"0\"  width=\"85%\">";
                            $this->salida.="<tr  align=\"center\" class=\"modulo_table_title\"><td  colspan=\"3\">PAGOS BONOS</td></tr>";
                            $this->salida.="<tr class=\"modulo_table_list_title\">";
                            $this->salida.="  <td>Recibo Caja</td>";
                            $this->salida.="  <td>Descripcion</td>";
                            $this->salida.="  <td>Valor</td>";
                            $this->salida.="</tr>";
                            for ($i = 0; $i < sizeof($datos[$i]); $i++) {
                                $desc = $datos[$i][descripcion];
                                $Valor = FormatoValor($datos[$i][valor_bono]);
                                $Recibo = $datos[$i][recibo_caja];
                                if ($i % 2) {
                                    $estilo = 'modulo_list_claro';
                                } else {
                                    $estilo = 'modulo_list_oscuro';
                                }
                                $this->salida.="<tr class=\"$estilo\"align=\"center\">";
                                $this->salida.="  <td>$Recibo</td>";
                                $this->salida.="  <td>$desc</td>";
                                $this->salida.="  <td>$Valor</td>";
                                $this->salida.="</tr>";
                            }
                            $this->salida.="</table><br>";
                        }
                        break;
                    }
                }
        }//fin switch
        return true;
    }

    /**
     * La funcion ConsultaHospitalizacion
     * Esta funcion trabaja con los pagos efectuados con ese numero de cuenta.
     * pagos tarjetas debitos,creditos,cheques,se diferenciara 'por casos' mediante
     * una variable $spy, esta consulta es de los cheques o tarjetas de los pagos efectuados
     * en el momento, ya que cuando se crea el recibo se eliminara la consulta.
     *
     * @access public
     * @return boolean
     */
    function ConsultaHospitalizacion($spy, $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy) {
        IncludeLib("tarifario");
        if (!$Cuenta) {
            $Cuenta = $_SESSION['CAJA']['CUENTA'];
        }
        switch ($spy) {
            case "2": {
                    $datos = $this->MovChequesTmp($Cuenta);
                    if ($datos) {
                        $this->salida.=" <BR><br><table border=\"0\" width=\"85%\" align=\"center\">";
                        $this->salida.=" <tr><td><fieldset><legend class=\"field\">CONSULTA DE PAGOS ANTERIORES DE CHEQUES</legend>";
                        $this->salida.="<table  align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"85%\">";
                        $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                        $this->salida.="  <td>Numero de Cheque</td>";
                        $this->salida.="  <td>Banco</td>";
                        $this->salida.="  <td>Girador</td>";
                        $this->salida.="  <td>Fecha</td>";
                        $this->salida.="  <td>Cta Cte</td>";
                        $this->salida.="  <td>Fecha de Cheque</td>";
                        $this->salida.="  <td>Total</td>";
                        $this->salida.="</tr>";
                        for ($i = 0; $i < sizeof($datos[$i]); $i++) {
                            $NoCheque = $datos[$i][cheque];
                            $Banco = $datos[$i][descripcion];
                            $Girador = $datos[$i][girador];
                            $Fecha = $datos[$i][fecha];
                            $CtaCte = $datos[$i][cta_cte];
                            $FechaCheque = $datos[$i][fecha_cheque];
                            $to = FormatoValor($datos[$i][total]);
                            if ($i % 2) {
                                $estilo = 'modulo_list_claro';
                            } else {
                                $estilo = 'modulo_list_oscuro';
                            }
                            $this->salida.="<tr class=\"$estilo\" align=\"center\">";
                            $this->salida.="  <td>$NoCheque</td>";
                            $this->salida.="  <td>$Banco</td>";
                            $this->salida.="  <td>$Girador</td>";
                            $this->salida.="  <td>$Fecha</td>";
                            $this->salida.="  <td>$CtaCte</td>";
                            $this->salida.="  <td>$FechaCheque</td>";
                            $this->salida.="  <td>$to</td>";
                            $this->salida.="</tr>";
                        }
                        $this->salida.="</table>";
                        $this->salida.="</fieldset></td></tr></table>";
                    }
                    break;
                }

            case "3": {
                    $datos = $this->MovTarjetasCreditoTmp($Cuenta);
                    if ($datos) {
                        $this->salida.=" <BR><br><table border=\"0\" width=\"85%\" align=\"center\">";
                        $this->salida.=" <tr><td><fieldset><legend class=\"field\">CONSULTA DE PAGOS ANTERIORES DE CUENTAS CREDITOS</legend>";
                        $this->salida.="<table align=\"center\" border=\"1\" class=\"hc_table_list\">";
                        $this->salida.="<tr class=\"hc_table_submodulo_list_title\" width=\"85%\" >";
                        $this->salida.="  <td>No. Tarjeta</td>";
                        $this->salida.="  <td>Tarjeta</td>";
                        $this->salida.="  <td>No. Autorizacion</td>";
                        $this->salida.="  <td>Fecha</td>";
                        $this->salida.="  <td>Socio</td>";
                        $this->salida.="  <td>Autorizado por</td>";
                        $this->salida.="  <td>Fecha de Expiracion</td>";
                        $this->salida.="  <td>Valor</td>";
                        $this->salida.="</tr>";
                        for ($i = 0; $i < sizeof($datos[$i]); $i++) {
                            $NoTar = $datos[$i][tarjeta_numero];
                            $Tarjeta = $datos[$i][descripcion];
                            $FechaExp = $datos[$i][fecha_expira];
                            $Fecha = $datos[$i][fecha];
                            $Socio = $datos[$i][socio];
                            $AutoPor = $datos[$i][autorizado_por];
                            $Valor = FormatoValor($datos[$i][total]);
                            $Auto = $datos[$i][autorizacion];
                            //=$datos[$i][];
                            if ($i % 2) {
                                $estilo = 'modulo_list_claro';
                            } else {
                                $estilo = 'modulo_list_oscuro';
                            }
                            $this->salida.="<tr class=\"$estilo\" align=\"center\">";
                            $this->salida.="  <td>$NoTar</td>";
                            $this->salida.="  <td>$Tarjeta</td>";
                            $this->salida.="  <td>$Auto</td>";
                            $this->salida.="  <td>$Fecha</td>";
                            $this->salida.="  <td>$Socio</td>";
                            $this->salida.="  <td>$AutoPor</td>";
                            $this->salida.="  <td>$FechaExp</td>";
                            $this->salida.="  <td>$Valor</td>";
                            $this->salida.="</tr>";
                        }
                        $this->salida.="</table>";
                        $this->salida.="</fieldset></td></tr></table>";
                    }
                    break;
                }

            case "4": {
                    $datos = $this->MovTarjetasDebitoTmp($Cuenta);
                    if ($datos) {
                        $this->salida.=" <BR><br><table border=\"0\" width=\"85%\" align=\"center\">";
                        $this->salida.=" <tr><td><fieldset><legend class=\"field\">CONSULTA DE PAGOS ANTERIORES DE CUENTAS DEBITOS</legend>";
                        $this->salida.="<table align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"50%\">";
                        $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                        $this->salida.="  <td>No.Tarjeta</td>";
                        $this->salida.="  <td>Tarjeta</td>";
                        $this->salida.="  <td>No. Autorizacion</td>";
                        $this->salida.="  <td>Valor</td>";
                        $this->salida.="</tr>";
                        for ($i = 0; $i < sizeof($datos[$i]); $i++) {
                            $noTarjeta = $datos[$i][tarjeta_numero];
                            $Tarjeta = $datos[$i][descripcion];
                            $Auto = $datos[$i][autorizacion];
                            $Valor = FormatoValor($datos[$i][total]);
                            if ($i % 2) {
                                $estilo = 'modulo_list_claro';
                            } else {
                                $estilo = 'modulo_list_oscuro';
                            }
                            $this->salida.="<tr class=\"$estilo\"align=\"center\">";
                            $this->salida.="  <td>$noTarjeta</td>";
                            $this->salida.="  <td>$Tarjeta</td>";
                            $this->salida.="  <td>$Auto</td>";
                            $this->salida.="  <td>$Valor</td>";
                            $this->salida.="</tr>";
                        }
                        $this->salida.="</table>";
                        $this->salida.="</fieldset></td></tr></table>";
                    }
                    break;
                }
        }//fin switch
        return true;
    }

    /**
     * La funcion DecisionHospitalizacion
     * Esta funcion decide segun la variable spia($spy) a que forma de captura debe ir,
     * esto logicamente segun el evento que se le haya dado al boton. ej: pagos/abonos cheques.
     *
     *
     *
     * @access public
     * @return boolean
     */
    function DecisionHospitalizacion($spy, $FechaHoy, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $Cuenta) {
        if (!$Cuenta) {
            //$FechaHoy=$_REQUEST['FechaHoy'];
            $PlanId = $_REQUEST['PlanId'];
            $TipoId = $_REQUEST['TipoId'];
            $PacienteId = $_REQUEST['PacienteId'];
            $Ingreso = $_REQUEST['Ingreso'];
            $Nivel = $_REQUEST['Nivel'];
            $FechaC = $_REQUEST['FechaC'];
            $Cuenta = $_REQUEST['Cuenta'];
            $spy = $_REQUEST['spy'];
            $Cajaid = $_REQUEST['Cajaid'];
        }

        switch ($spy) {
            case "1": {
                    // $this->ConsultaHospitalizacion('1',$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC,$FechaHoy);
                    $this->CapturaHospitalizacion('1', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Valor);
                    break;
                }

            case "2": {
                    $this->CapturaHospitalizacion('2', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Valor);
                    break;
                }

            case "3": {
                    $this->CapturaHospitalizacion('3', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Valor);
                    break;
                }

            case "4": {
                    $this->CapturaHospitalizacion('4', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Valor);
                    break;
                }
            case "5": {
                    $this->CapturaHospitalizacion('5', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Valor);
                    break;
                }

            case "6": {
                    $this->CapturaHospitalizacion('6', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Valor);
                    break;
                }
        }//fin switch
        return true;
    }

    /**
     * La funcion CapturaHospitalizacion
     * visualiza la forma segun el caso(pagos cheques,tarjetas creditos,debitos), para guardar
     * nuevos pagos.
     *
     * @access public
     * @return boolean
     */
    function CapturaHospitalizacion($spy, $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Valor) {
        includelib('tarifario');

        $espia = $FechaHoy['0'];
        $fun = $FechaHoy['1'];
        $num = $FechaHoy['2'];
        $kia = $FechaHoy['3'];
        $consecutivo = $FechaHoy['4'];

        switch ($spy) {
            case "1": {


                    if ($_SESSION['CAJA']['TOTAL_EFECTIVO'] >= $_SESSION['CAJA']['SAL']) {//ojo con esto...
                        //$_SESSION['CAJA']['SUBTOTAL']=$_SESSION['CAJA']['SUBTOTAL']-$_SESSION['CAJA']['TOTAL_EFECTIVO'];
                        $_SESSION['CAJA']['TOTAL_EFECTIVO'] = 0; //reseteamos debido a que por ejemplo el
                        //saldo=10 y el efectivo=10 entonces pa q p&&&s entro...a lo q se refiere es
                        //q no se va a cancelar todo con efectivo.....
                    }
                    $this->salida .= ThemeAbrirTabla('PAGOS EN EFECTIVO');
                    //$this->salida .='<form name="forma2" action="'.ModuloGetURL('app','CajaGeneral','user','FormaEfectivo',array('spy'=>1,'Cuenta'=>$Cuenta,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'FechaC'=>$FechaC,'FechaHoy'=>$FechaHoy,'Cajaid'=>$Cajaid)).'" method="post">';
                    $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarTemporales', array('spy' => 1, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy)) . '" method="post">';
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
                        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $FechaC));
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
                        $this->EncabezadoConsultaExt($TipoId, $PacienteId, $PlanId);
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE REVISAR PRIMERO LAS CITAS QUE VA A PAGAR</td>';
                        } else {

                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConsultaExterna', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                        $this->EncabezadoOrdenServicio();
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE REVISAR PRIMERO LAS CITAS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'RetornarFormaOrdenesServicio');
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                        $this->EncabezadoConceptos();
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE ASIGNAR PRIMERO LOS CONCEPTOS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConceptos', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
                        $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, $PlanId);
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '08') {    //$this->EncabezadoPvta($Cuenta,$TipoId,$PacienteId,$PlanId);
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaCuentaInventarios', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    $this->salida .='<BR>';
                    $this->salida .='<table align="center" width="45%" border="0">';
                    //$this->salida .=$deuda;  //ventana donde guardaba el valor descontado.
                    $this->salida .='<tr>';
                    $this->salida .= $this->SetStyle("MensajeError");
                    $this->salida .='<td class="' . $this->SetStyle("valorpagar") . '">VALOR EFECTIVO: </td>';
                    $this->salida .='<td><input name="valorpagar" type="text" class="input-text" value="' . $Valor . '"></td>';
                    $this->salida .='</tr>';
                    /* $this->salida .='<tr>';
                      $this->salida .='<td class="'.$this->SetStyle("efectivo").'">TOTAL EFECTIVO: </td>';
                      $this->salida .='<td><input name="efectivo" type="text" class="input-text"></td>';
                      $this->salida .='</tr>';
                     */
                    $this->salida .='</table>';
                    //$this->ConsultaHospitalizacion('1',$Cuenta,$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC,$FechaHoy);
                    $this->salida.="<br><table align=\"center\">";
                    $this->salida.="<tr>";
                    $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Aceptar\" class=\"input-submit\"></form></td>";
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .= "<td>";
                    $this->salida .='<form name="forma" action="' . $accion . '" method="post">';
                    $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
                    $this->salida.= ThemeCerrarTabla();
                    break;
                }

            case "2": {
                    $this->salida .= ThemeAbrirTabla('PAGOS CON CHEQUE');
                    $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarTemporales', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'kia' => $kia, 'num' => $num, 'consecutivo' => $consecutivo, 'Cajaid' => $Cajaid)) . '" method="post">';

                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                        $this->EncabezadoOrdenServicio();
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE REVISAR PRIMERO LAS CITAS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'RetornarFormaOrdenesServicio', array('paso' => $_SESSION['CAJA']['PASO']));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
                        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $FechaC));
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
                        $this->EncabezadoConsultaExt($TipoId, $PacienteId, $PlanId);
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE REVISAR PRIMERO LAS CITAS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConsultaExterna', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                        $this->EncabezadoConceptos();
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td  align="center" class="label_error">DEBE ASIGNAR PRIMERO LOS CONCEPTOS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td  align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }
                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConceptos', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
                        $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, $PlanId);
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '08') {    //$this->EncabezadoPvta($Cuenta,$TipoId,$PacienteId,$PlanId);
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaCuentaInventarios', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }

                    $this->salida .='<BR>';
                    $this->salida .='<table align="center" width="60%" border="0">';
                    //$this->salida .=$deuda;
                    $this->salida .='<tr>';
                    $this->salida .= $this->SetStyle("MensajeError");
                    $this->salida .='</tr>';
                    $this->salida .='</table>';
                    $this->salida .='<table align="center" width="65%" border="0">';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("nocheque") . '">N�mero Cheque: </td>';
                    $this->salida .='<td><input name="nocheque" type="text" class="input-text"  maxlength=10 value=' . $_REQUEST['nocheque'] . '></td>';
                    $this->salida .='<td width="5%">&nbsp;</td>';
                    $this->salida .='<td class="' . $this->SetStyle("banco") . '">Banco: </td>';
                    $this->salida .="<td><select name=\"banco\" class=\"select\">";
                    $Bancos = $this->ComboBancos();
                    $this->BuscarBancos($Bancos, $Banco = '');
                    $this->salida .='</select></td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("ctac") . '">No. Cta. Corriente: </td>';
                    $this->salida .='<td><input name="ctac" type="text" id="ctac" class="input-text" maxlength=40  value=' . $_REQUEST['ctac'] . '></td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td class="' . $this->SetStyle("girador") . '">Girador: </td>';
                    $this->salida .='<td><input name="girador" type="text" class="input-text" maxlength=30  value=' . $_REQUEST['girador'] . '></td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';

                    //if(empty($_REQUEST['fechaconfirma'])){$_REQUEST['fechaconfirma']=date("d-m-Y");};

                    $this->salida .='<td class="' . $this->SetStyle("fechacheque") . '">Fecha de Cheque: </td>';
                    $this->salida .='<td><input name="fechacheque" type="text" id="fechacheque" class="input-text"  size="11" maxlength="10" value=' . $_REQUEST['fechacheque'] . '>' . ReturnOpenCalendario('forma2', 'fechacheque', '-') . '</td>';

                    $this->salida .='<td>&nbsp;</td>';

                    if (empty($_REQUEST['fech'])) {
                        $_REQUEST['fech'] = date("d-m-Y");
                    };
                    $this->salida .='<td class="' . $this->SetStyle("fech") . '">Fecha Transaccion: </td>';
                    $this->salida .='<td><input name="fech" type="text" id="fech" class="input-text" size="11" maxlength="10" value=' . $_REQUEST['fech'] . '>' . ReturnOpenCalendario('forma2', 'fech', '-') . '</td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="label">Total</td>';
                    $this->salida .='<td><input name="totalc" type="text" class="input-text"  value=' . $_REQUEST['totalc'] . '></td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='</tr>';
                    $this->salida .='</table>';
                    //$this->ConsultaHospitalizacion('2',$Cuenta,$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC,$FechaHoy);
                    $this->salida.="<br><table align=\"center\" >";
                    $this->salida.="<tr>";
                    $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Guardar\" class=\"input-submit\"></form></td>";
                    $this->salida .='<td>&nbsp;</td>';
//                                                     if($espia=='si')
//                                                     {
//                                                   }
//                                                     else
//                                                     {
//                                                             $this->salida .='<form name="formax" action="'.ModuloGetURL('app','CajaGeneral','user','autorizacion',array('spy'=>2,'Cuenta'=>$Cuenta,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'FechaC'=>$FechaC,'FechaHoy'=>$FechaHoy,'Cajaid'=>$Cajaid)).'" method="post">';
//                                                             $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Autorizar\" class=\"input-submit\"></form></td>";
//                                                             $this->salida .='<td>&nbsp;</td>';
//                                                     }

                    $this->salida .= "<td >";
                    $this->salida .='<form name="forma" action="' . $accion . '" method="post">';
                    $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
                    $this->salida.= ThemeCerrarTabla();
                    break;
                }

            case "3": {
                    $this->salida.= ThemeAbrirTabla('PAGOS CON TARJETAS DE CREDITOS..');
                    $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarTemporales', array('spy' => 3, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'kia' => $kia, 'num' => $num, 'consecutivo' => $consecutivo, 'Cajaid' => $Cajaid)) . '" method="post">';

                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                        $this->EncabezadoOrdenServicio();
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE REVISAR PRIMERO LAS CITAS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'RetornarFormaOrdenesServicio', array('paso' => $_SESSION['CAJA']['PASO']));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
                        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $FechaC));
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('spy' => 3, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
                        $this->EncabezadoConsultaExt($TipoId, $PacienteId, $PlanId);
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE REVISAR PRIMERO LAS CITAS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConsultaExterna', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                        $this->EncabezadoConceptos();
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td align="center" class="label_error">DEBE ASIGNAR PRIMERO LOS CONCEPTOS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td  align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }
                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConceptos', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
                        $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, $PlanId);
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '08') {
                        $this->EncabezadoConceptos();
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td align="center" class="label_error">DEBE ASIGNAR PRIMERO LOS PRODUCTOS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td  align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }
                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConceptos', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }

                    //$this->ReturnMetodoExterno('app','Facturacion','user','LlamadaFormaEncabezado',array('PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$FechaC));
                    $this->salida .='<BR>';
                    $this->salida .='<table align="center" width="60%" border="0">';
                    $this->salida .=$deuda;
                    $this->salida .='<tr>';
                    $this->salida .= $this->SetStyle("MensajeError");
                    $this->salida .='</tr>';
                    $this->salida .='</table>';
                    $this->salida .='<table align="center" border="0" width="70%" >';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("tarjeta") . '">Tarjeta: </td>';
                    $this->salida .="<td><select name=\"tarjeta\" class=\"select\">";
                    $Tarjetas = $this->ComboTarjetas();
                    $this->BuscarTarjetas($Tarjetas, $Tarjeta = '', 'C');
                    $this->salida .='</select></td>';
                    //$this->salida .='<td> <input name="notarjeta" type="text" class="input-text"></td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td class="' . $this->SetStyle("numtarjeta") . '">No. Tarjeta: </td>';
                    $this->salida .='<td><input name="numtarjeta" type="text" class="input-text" maxlength=20 value=' . $_REQUEST['numtarjeta'] . '></td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("noautorizacion") . '">Numero de Autorizacion: </td>';
                    if ($_REQUEST['noautorizacion']) {
                        $a = $_REQUEST['noautorizacion'];
                    } else {
                        $a = $num;
                    }
                    $this->salida .='<td><input name="noautorizacion" type="text"  class="input-text" READONLY  value=' . $a . ' ></td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td class="' . $this->SetStyle("socio") . '">Socio: </td>';
                    $this->salida .='<td><input name="socio" type="text" class="input-text" maxlength=40 value=' . $_REQUEST['socio'] . '></td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("fechaexp") . '">Fecha de Expiracion: </td>';
                    $this->salida .='<td><input name="fechaexp" type="text" class="input-text" size="11" maxlength="10" value=' . $_REQUEST['fechaexp'] . ' >' . ReturnOpenCalendario('forma2', 'fechaexp', '-') . '</td>';
                    $this->salida .='<td>&nbsp;</td>';
                    if ($_REQUEST['autoriza']) {
                        $x = $_REQUEST['autoriza'];
                    } else {
                        $x = $fun;
                    }
                    $this->salida .='<td class="' . $this->SetStyle("autoriza") . '">Autorizado Por: </td>';
                    $this->salida .='<td><input name="autoriza" type="text" class="input-text" READONLY value=' . str_replace("+", '&nbsp;', $x) . '></td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("fecha") . '">Fecha Transaccion: </td>';
                    if (empty($_REQUEST['fecha'])) {
                        $_REQUEST['fecha'] = date("d-m-Y");
                    }
                    $this->salida .='<td><input name="fecha" type="text" class="input-text" size="11" maxlength="10" value=' . $_REQUEST['fecha'] . '>' . ReturnOpenCalendario('forma2', 'fecha', '-') . '</td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td class="' . $this->SetStyle("valor") . ' class="label" height="27">Total: </td>';
                    $this->salida .='<td><input name="total" type="text" class="input-text" value=' . $_REQUEST['total'] . '></td>';
                    $this->salida .='</tr>';
                    $this->salida .='</table>';
                    //$this->ConsultaHospitalizacion('3',$Cuenta,$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC,$FechaHoy);
                    $this->salida.="<br><table align=\"center\" >";
                    $this->salida.="<tr>";
                    $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Guardar\" class=\"input-submit\"></form></td>";
                    $this->salida .='<td>&nbsp;</td>';
//                                                     if($espia=='si')
//                                                     {
//                                                   }
//                                                     else
//                                                     {
//                                                             $this->salida .='<form name="formax" action="'.ModuloGetURL('app','CajaGeneral','user','autorizacion',array('spy'=>3,'Cuenta'=>$Cuenta,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'FechaC'=>$FechaC,'FechaHoy'=>$FechaHoy,'Cajaid'=>$Cajaid)).'" method="post">';
//                                                             $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Autorizar\" class=\"input-submit\"></form></td>";
//                                                             $this->salida .='<td>&nbsp;</td>';
//                                                     }
                    $this->salida .= "<td >";
                    $this->salida .='<form name="forma" action="' . $accion . '" method="post">';
                    $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
                    $this->salida.= ThemeCerrarTabla();
                    break;
                }

            case "4": {
                    $this->salida.= ThemeAbrirTabla('PAGOS CON TARJETAS DEBITOS');
                    $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarTemporales', array('spy' => 4, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid)) . '" method="post">';
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                        $this->EncabezadoOrdenServicio();
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE REVISAR PRIMERO LAS CITAS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'RetornarFormaOrdenesServicio', array('paso' => $_SESSION['CAJA']['PASO']));
                    }

                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
                        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $FechaC));
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('spy' => 4, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
                        $this->EncabezadoConsultaExt($TipoId, $PacienteId, $PlanId);
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE REVISAR PRIMERO LAS CITAS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConsultaExterna', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                        $this->EncabezadoConceptos();
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE ASIGNAR PRIMERO LOS CONCEPTOS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }
                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConceptos', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
                        $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, $PlanId);
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    //$this->ReturnMetodoExterno('app','Facturacion','user','LlamadaFormaEncabezado',array('PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$FechaC));
                    $this->salida .='<BR>';
                    $this->salida.= '<table align="center" border="0">';
                    //$this->salida .=$deuda;
                    //$this->SetStyle("MensajeError");
                    $this->salida .='<tr>';
                    $this->salida .='<td colspan="5">';
                    $this->salida.= '<table align="center" border="0" width="100%">';
                    $this->salida .=$this->SetStyle("MensajeError");
                    $this->salida.= '</table>';
                    //$this->salida .='<td colspan="5" align="center">'.$this->SetStyle("MensajeError").'</td>';
                    $this->salida .='</td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("tarjeta") . '">Tarjeta: </td>';
                    $this->salida .="<td><select name=\"tarjeta\" class=\"select\">";
                    $Tarjetas = $this->ComboTarjetas();
                    $this->BuscarTarjetas($Tarjetas, $Tarjeta = '', 'D');
                    $this->salida .='</select></td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td class="' . $this->SetStyle("numtarjeta") . '">No. Tarjeta: </td>';
                    $this->salida .='<td><input name="numtarjeta" type="text" class="input-text" maxlength=20  value=' . $_REQUEST['numtarjeta'] . '></td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("noautorizad") . '">No. de Autorizacion: </td>';
                    $this->salida .='<td><input name="noautorizad" type="text" class="input-text" maxlength=15 value=' . $_REQUEST['noautorizad'] . '></td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td class="' . $this->SetStyle("totald") . '">Total</td>';
                    $this->salida .='<td><input name="totald" type="text" id="totald" class="input-text" value=' . $_REQUEST['totald'] . '></td>';
                    $this->salida .='</tr>';
                    $this->salida .='</table>';
                    //    $this->ConsultaHospitalizacion('4',$Cuenta,$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC);
                    $this->salida.="<br><table align=\"center\" >";
                    $this->salida.="<tr>";
                    $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Guardar\" class=\"input-submit\"></form></td>";
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .= "<td >";
                    $this->salida .='<form name="forma" action="' . $accion . '" method="post">';
                    $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
                    $this->salida.= ThemeCerrarTabla();
                    break;
                }


            case "5": {
                    $this->salida .= ThemeAbrirTabla('PAGOS EN BONOS');
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                        $this->EncabezadoOrdenServicio();
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE REVISAR PRIMERO LAS CITAS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'RetornarFormaOrdenesServicio', array('paso' => $_SESSION['CAJA']['PASO']));
                    }
                    $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarTemporales', array('spy' => 5, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid)) . '" method="post">';
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
                        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $FechaC));
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
                        $this->EncabezadoConsultaExt($TipoId, $PacienteId, $PlanId);
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE REVISAR PRIMERO LAS CITAS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConsultaExterna', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                        $this->EncabezadoConceptos();
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE ASIGNAR PRIMERO LOS CONCEPTOS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConceptos', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
                        $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, $PlanId);
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    $this->salida .='<BR>';
                    $this->salida .='<table align="center" width="45%" border="0">';
                    //$this->salida .=$deuda;
                    $this->salida .='<tr>';
                    $this->salida .= $this->SetStyle("MensajeError");
                    $this->salida .='<td class="' . $this->SetStyle("pagabono") . '">VALOR A PAGAR BONO: </td>';
                    $this->salida .='<td><input name="pagabono" type="text" class="input-text" value="' . $Valor . '"></td>';
                    $this->salida .='</tr>';
                    $this->salida .='</table>';
                    //$this->ConsultaHospitalizacion('5',$Cuenta,$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC);
                    //$this->ConsultaHospitalizacion('1',$Cuenta,$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC,$FechaHoy);
                    $this->salida.="<br><table align=\"center\">";
                    $this->salida.="<tr>";
                    $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Aceptar\" class=\"input-submit\"></form></td>";
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .= "<td>";
                    $this->salida .='<form name="forma" action="' . $accion . '" method="post">';
                    $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
                    $this->salida.= ThemeCerrarTabla();
                    break;
                }




            case "6": {
                    $this->salida .= ThemeAbrirTabla('DESCUENTOS');
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                        $this->EncabezadoOrdenServicio();
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE REVISAR PRIMERO LAS CITAS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'RetornarFormaOrdenesServicio', array('paso' => $_SESSION['CAJA']['PASO']));
                    }
                    $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarTemporales', array('spy' => 5, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid)) . '" method="post">';
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
                        $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $FechaC));
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
                        $this->EncabezadoConsultaExt($TipoId, $PacienteId, $PlanId);
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE REVISAR PRIMERO LAS CITAS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConsultaExterna', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                        $this->EncabezadoConceptos();
                        $deuda .="<tr class=\"hc_table_submodulo_list_title\">";
                        if (empty($_SESSION['CAJA']['SAL'])) {
                            $deuda .='<td colspan="5" align="center" class="label_error">DEBE ASIGNAR PRIMERO LOS CONCEPTOS QUE VA A PAGAR</td>';
                        } else {
                            $res = $_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL'];
                            $deuda .='<td colspan="5" align="center" class="label">Total Deuda :&nbsp;&nbsp;' . FormatoValor($res) . '</td>';
                        }

                        $deuda .='</tr>';
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConceptos', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
                        $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, $PlanId);
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid));
                    }
                    $this->salida .='<BR>';
                    $this->salida .='</form>';
                    //revisamos a ver si esta autorizado
                    $conteo_user = $this->AutorizadorDescuento();
                    $ac = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarDescuento', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['CAJA']['PASO'], 'PlanId' => $PlanId, 'Cajaid' => $Cajaid, 'conteo_user' => $conteo_user));
                    $this->salida .='<form name="forma" action=' . $ac . ' method="post">';
                    $this->salida .='<table align="center" width="45%" border="0">';
                    //$this->salida .=$deuda;
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("descuento") . '">VALOR DESCUENTO: </td>';
                    $this->salida .='<td><input name="descuento" type="text" class="input-text" value="' . $Valor . '"></td>';
                    $this->salida .='</tr>';
                    $this->salida .='</table>';
                    if ($conteo_user < 1) {
                        $this->salida.="<br><br><table border=\"0\"  align=\"center\"   width=\"45%\" >";
                        $this->salida .="" . $this->SetStyle("MensajeError") . "";
                        $this->salida.="<tr>";
                        $this->salida .= "<td  colspan=\"2\"  align=\"center\" class=\"modulo_table_title\" >Autenticaci�n de Usuario</td>";
                        $this->salida.="</tr>";
                        $this->salida.="<tr>";
                        $this->salida.="<tr class=\"modulo_list_claro\">";
                        $this->salida .= "<td   width=\"35%\" align=\"center\" class=\"" . $this->SetStyle("usuario") . "\">Usuario :</td>";
                        $this->salida .= "<td  align=\"center\"><input class=\"input-text\" type=\"text\" align=\"center\" name=\"usuario\"</td>";
                        $this->salida.="</tr>";
                        $this->salida.="<tr class=\"modulo_list_claro\">";
                        $this->salida .= "<td   width=\"35%\"  align=\"center\"  class=\"" . $this->SetStyle("pass") . "\">Password :</td>";
                        $this->salida .= "<td  align=\"center\"><input class=\"input-text\" type=\"password\" align=\"center\" name=\"pass\"</td>";
                        $this->salida.="</tr>";
                        $this->salida.="</table>";
                    }
                    $this->salida.="<br><table align=\"center\">";
                    $this->salida.="<tr>";
                    $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Aceptar\" class=\"input-submit\"></form></td>";
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .= "<td>";
                    $this->salida .='<form name="forma" action="' . $accion . '" method="post">';
                    $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
                    $this->salida.= ThemeCerrarTabla();
                    break;
                }
            //CASO DE PAGARES
        //
            }//fin switch
        return true;
    }

    function FormaValidarUsuarioDevoluciones($TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Cuenta, $Tiponumeracion, $FechaHoy, $Devolucion, $Saldo, $Cajaid) {
        /* 			$_REQUEST['Cajaid']=$_SESSION['tmp']['Cajaid'];
          $_REQUEST['devol']=$_SESSION['tmp']['devol'];
          $_REQUEST['sal']=$_SESSION['tmp']['sal'];
          $_REQUEST['TipoCuenta']=$_SESSION['tmp']['TipoCuenta']; */

        $this->salida.= ThemeAbrirTabla('VALIDAR USUARIO DEVOLUCIONES', '40%');
        $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'ValidarUsuario', array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Pieza' => $Pieza, 'Cama' => $Cama, 'FechaC' => $FechaC, 'Ingreso' => $Ingreso, 'Cuenta' => $Cuenta, 'Tiponumeracion' => $Tiponumeracion, 'FechaHoy' => $FechaHoy, 'Devolucion' => $Devolucion, 'Saldo' => $Saldo, 'Cajaid' => $Cajaid)) . '" method="post">';
        $this->salida.="<br><br><table border=\"0\"  align=\"center\"   width=\"65%\" >";
        $this->salida .="" . $this->SetStyle("MensajeError") . "";
        $this->salida.="<tr>";
        $this->salida .= "<td  colspan=\"2\"  align=\"center\" class=\"modulo_table_title\" >Autenticaci�n de Usuario</td>";
        $this->salida.="</tr>";
        $this->salida.="<tr>";
        $this->salida.="<tr class=\"modulo_list_claro\">";
        $this->salida .= "<td   width=\"35%\" align=\"center\" class=\"" . $this->SetStyle("usuario") . "\">Usuario :</td>";
        $this->salida .= "<td  align=\"center\"><input class=\"input-text\" type=\"text\" align=\"center\" name=\"usuario\"</td>";
        $this->salida.="</tr>";
        $this->salida.="<tr class=\"modulo_list_claro\">";
        $this->salida .= "<td   width=\"35%\"  align=\"center\"  class=\"" . $this->SetStyle("pass") . "\">Password :</td>";
        $this->salida .= "<td  align=\"center\"><input class=\"input-text\" type=\"password\" align=\"center\" name=\"pass\"</td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
        $this->salida.="<tr>";
        $this->salida.="  <td align=\"center\">";
        $this->salida .="<input type=\"submit\" align=\"center\" name=\"Guardar\" value=\"Guardar\" class=\"input-submit\"></form></td>";
        $this->salida.="  <td align=\"left\">";
        $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array("show" => 1)) . '" method="post">';
        $this->salida .="<input type=\"submit\" align=\"left\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form></td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

//DECIDIR Y FORMAS PARA LOS ABONOS DE LOS PAGARES
    /**
     * La funcion DecisionPagares
     * Esta funcion decide segun la variable spia($spy) a que forma de captura debe ir,
     * esto logicamente segun el evento que se le haya dado al boton. ej: pagos/abonos cheques.
     *
     *
     *
     * @access public
     * @return boolean
     */
    function DecisionPagares() {
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Cuenta = $_REQUEST['Cuenta'];
        $spy = $_REQUEST['spy'];
        $Cajaid = $_REQUEST['Cajaid'];
        $NombrePaciente = $_REQUEST['NombrePaciente'];
        $PagareNumero = $_REQUEST['PagareNumero'];
        $Empresa = $_REQUEST['Empresa'];
        $Prefijo = $_REQUEST['Prefijo'];
        $Valor = $_REQUEST['Valor'];
        $DocumentoId = $_REQUEST['DocumentoId'];

        switch ($spy) {
            case "1": {
                    $this->CapturaPagares('1', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId, $FechaHoy = '');
                    break;
                }

            case "2": {
                    $this->CapturaPagares('2', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId, $FechaHoy = '');
                    break;
                }

            case "3": {
                    $this->CapturaPagares('3', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId, $FechaHoy = '');
                    break;
                }
            case "4": {
                    $this->CapturaPagares('4', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId, $FechaHoy = '');
                    break;
                }
        }//fin switch
        return true;
    }

    //******************************
    /**
     * La funcion CapturaPagares
     * visualiza la forma segun el caso(pagos cheques,tarjetas creditos,debitos), para guardar
     * nuevos pagos.
     *
     * @access public
     * @return boolean
     */
    //$spy,$TipoId,$PacienteId,$Cuenta,$Valor,$Cajaid,$NombrePaciente,$PagareNumero,$Empresa,$Prefijo,$Valor,$DocumentoId,$FechaHoy
    function CapturaPagares($spy, $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId, $FechaHoy) {
        $espia = $FechaHoy['0'];
        $fun = $FechaHoy['1'];
        $num = $FechaHoy['2'];
        $kia = $FechaHoy['3'];
        $consecutivo = $FechaHoy['4'];

        switch ($spy) {
            case "1": {
                    $this->salida .= ThemeAbrirTabla('PAGOS EN EFECTIVO');
                    //$this->salida .='<form name="forma2" action="'.ModuloGetURL('app','CajaGeneral','user','FormaEfectivo',array('spy'=>1,'Cuenta'=>$Cuenta,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'FechaC'=>$FechaC,'FechaHoy'=>$FechaHoy,'Cajaid'=>$Cajaid)).'" method="post">';
                    $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaPagares', array('spy' => 1, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Cuenta' => $Cuenta, 'Cajaid' => $Cajaid, 'NombrePaciente' => $NombrePaciente, 'PagareNumero' => $PagareNumero, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'DocumentoId' => $DocumentoId)) . '" method="post">'; //
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
                        $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, '', $NombrePaciente, $PagareNumero, $Empresa, $Prefijo);
                        //$this->EncabezadoPvta($Cuenta,$TipoId,$PacienteId,$NombrePaciente,$PagareNumero,$Empresa,$Prefijo);
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaPagares', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Cajaid' => $Cajaid, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente, 'DocumentoId' => $DocumentoId)); //
                    }
                    $this->salida .='<BR>';
                    $this->salida .='<table align="center" width="45%" border="0">';
                    //$this->salida .=$deuda;  //ventana donde guardaba el valor descontado.
                    $this->salida .='<tr>';
                    $this->salida .= $this->SetStyle("MensajeError");
                    $this->salida .='<td class="' . $this->SetStyle("valorpagar") . '">VALOR EFECTIVO: </td>';
                    $this->salida .='<td><input name="valorpagar" type="text" class="input-text" value="' . $_REQUEST['saldo'] . '"></td>';
                    $this->salida .='</tr>';
                    /* $this->salida .='<tr>';
                      $this->salida .='<td class="'.$this->SetStyle("efectivo").'">TOTAL EFECTIVO: </td>';
                      $this->salida .='<td><input name="efectivo" type="text" class="input-text"></td>';
                      $this->salida .='</tr>';
                     */
                    $this->salida .='</table>';
                    //$this->ConsultaHospitalizacion('1',$Cuenta,$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC,$FechaHoy);
                    $this->salida.="<br><table align=\"center\">";
                    $this->salida.="<tr>";
                    $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Aceptar\" class=\"input-submit\"></form></td>";
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .= "<td>";
                    $this->salida .='<form name="forma" action="' . $accion . '" method="post">';
                    $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
                    $this->salida.= ThemeCerrarTabla();
                    break;
                }
            case "2": {
                    $this->salida .= ThemeAbrirTabla('PAGOS CON CHEQUE');
                    $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarTemporales', array('spy' => 2, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente, 'kia' => $kia, 'num' => $num, 'consecutivo' => $consecutivo, 'Cajaid' => $Cajaid, 'FechaHoy' => $FechaHoy)) . '" method="post">';
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
                        $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, '', $NombrePaciente, $PagareNumero, $Empresa, $Prefijo);
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaPagares', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Cajaid' => $Cajaid, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente, 'DocumentoId' => $DocumentoId));
                    }
                    $this->salida .='<BR>';
                    $this->salida .='<table align="center" width="60%" border="0">';
                    //$this->salida .=$deuda;
                    $this->salida .='<tr>';
                    $this->salida .= $this->SetStyle("MensajeError");
                    $this->salida .='</tr>';
                    $this->salida .='</table>';
                    $this->salida .='<table align="center" width="65%" border="0">';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("nocheque") . '">N�mero Cheque: </td>';
                    $this->salida .='<td><input name="nocheque" type="text" class="input-text"  maxlength=10 value=' . $_REQUEST['nocheque'] . '></td>';
                    $this->salida .='<td width="5%">&nbsp;</td>';
                    $this->salida .='<td class="' . $this->SetStyle("banco") . '">Banco: </td>';
                    $this->salida .="<td><select name=\"banco\" class=\"select\">";
                    $Bancos = $this->ComboBancos();
                    $this->BuscarBancos($Bancos, $Banco = '');
                    $this->salida .='</select></td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("ctac") . '">No. Cta. Corriente: </td>';
                    $this->salida .='<td><input name="ctac" type="text" id="ctac" class="input-text" maxlength=10  value=' . $_REQUEST['ctac'] . '></td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td class="' . $this->SetStyle("girador") . '">Girador: </td>';
                    $this->salida .='<td><input name="girador" type="text" class="input-text" maxlength=30  value=' . $_REQUEST['girador'] . '></td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';

                    //if(empty($_REQUEST['fechaconfirma'])){$_REQUEST['fechaconfirma']=date("d-m-Y");};

                    $this->salida .='<td class="' . $this->SetStyle("fechacheque") . '">Fecha de Cheque: </td>';
                    $this->salida .='<td><input name="fechacheque" type="text" id="fechacheque" class="input-text"  size="11" maxlength="10" value=' . $_REQUEST['fechacheque'] . '>' . ReturnOpenCalendario('forma2', 'fechacheque', '-') . '</td>';

                    $this->salida .='<td>&nbsp;</td>';

                    if (empty($_REQUEST['fech'])) {
                        $_REQUEST['fech'] = date("d-m-Y");
                    };
                    $this->salida .='<td class="' . $this->SetStyle("fech") . '">Fecha Transaccion: </td>';
                    $this->salida .='<td><input name="fech" type="text" id="fech" class="input-text" size="11" maxlength="10" value=' . $_REQUEST['fech'] . '>' . ReturnOpenCalendario('forma2', 'fech', '-') . '</td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="label">Total</td>';
                    $this->salida .='<td><input name="totalc" type="text" class="input-text"  value=' . $_REQUEST['totalc'] . '></td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='</tr>';
                    $this->salida .='</table>';
                    //$this->ConsultaHospitalizacion('2',$Cuenta,$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC,$FechaHoy);
                    $this->salida.="<br><table align=\"center\" >";
                    $this->salida.="<tr>";
                    $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Guardar\" class=\"input-submit\"></form></td>";
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .= "<td >";
                    $this->salida .='<form name="forma" action="' . $accion . '" method="post">';
                    $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
                    $this->salida.= ThemeCerrarTabla();
                    break;
                }

            case "3": {
                    $this->salida.= ThemeAbrirTabla('PAGOS CON TARJETAS DE CREDITOS');
                    $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarTemporales', array('spy' => 3, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'kia' => $kia, 'NombrePaciente' => $NombrePaciente, 'num' => $num, 'consecutivo' => $consecutivo, 'Cajaid' => $Cajaid)) . '" method="post">';

                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
                        $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, '', $NombrePaciente, $PagareNumero, $Empresa, $Prefijo);
                        //$this->EncabezadoPvta($Cuenta,$TipoId,$PacienteId,$PlanId,$PagareNumero,$Empresa,$Prefijo);
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaPagares', array('spy' => 3, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Cajaid' => $Cajaid, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente, 'DocumentoId' => $DocumentoId));
                    }

                    //$this->ReturnMetodoExterno('app','Facturacion','user','LlamadaFormaEncabezado',array('PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$FechaC));
                    $this->salida .='<BR>';
                    $this->salida .='<table align="center" width="60%" border="0">';
                    $this->salida .=$deuda;
                    $this->salida .='<tr>';
                    $this->salida .= $this->SetStyle("MensajeError");
                    $this->salida .='</tr>';
                    $this->salida .='</table>';
                    $this->salida .='<table align="center" border="0" width="70%" >';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("tarjeta") . '">Tarjeta: </td>';
                    $this->salida .="<td><select name=\"tarjeta\" class=\"select\">";
                    $Tarjetas = $this->ComboTarjetas();
                    $this->BuscarTarjetas($Tarjetas, $Tarjeta = '', 'C');
                    $this->salida .='</select></td>';
                    //$this->salida .='<td> <input name="notarjeta" type="text" class="input-text"></td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td class="' . $this->SetStyle("numtarjeta") . '">No. Tarjeta: </td>';
                    $this->salida .='<td><input name="numtarjeta" type="text" class="input-text" maxlength=20 value=' . $_REQUEST['numtarjeta'] . '></td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("noautorizacion") . '">Numero de Autorizacion: </td>';
                    if ($_REQUEST['noautorizacion']) {
                        $a = $_REQUEST['noautorizacion'];
                    } else {
                        $a = $num;
                    }
                    $this->salida .='<td><input name="noautorizacion" type="text"  class="input-text" READONLY  value=' . $a . ' ></td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td class="' . $this->SetStyle("socio") . '">Socio: </td>';
                    $this->salida .='<td><input name="socio" type="text" class="input-text" maxlength=40 value=' . $_REQUEST['socio'] . '></td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("fechaexp") . '">Fecha de Expiracion: </td>';
                    $this->salida .='<td><input name="fechaexp" type="text" class="input-text" size="11" maxlength="10" value=' . $_REQUEST['fechaexp'] . ' >' . ReturnOpenCalendario('forma2', 'fechaexp', '-') . '</td>';
                    $this->salida .='<td>&nbsp;</td>';
                    if ($_REQUEST['autoriza']) {
                        $x = $_REQUEST['autoriza'];
                    } else {
                        $x = $fun;
                    }
                    $this->salida .='<td class="' . $this->SetStyle("autoriza") . '">Autorizado Por: </td>';
                    $this->salida .='<td><input name="autoriza" type="text" class="input-text" READONLY value=' . str_replace("+", '&nbsp;', $x) . '></td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("fecha") . '">Fecha Transaccion: </td>';
                    if (empty($_REQUEST['fecha'])) {
                        $_REQUEST['fecha'] = date("d-m-Y");
                    }
                    $this->salida .='<td><input name="fecha" type="text" class="input-text" size="11" maxlength="10" value=' . $_REQUEST['fecha'] . '>' . ReturnOpenCalendario('forma2', 'fecha', '-') . '</td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td class="' . $this->SetStyle("totalcr") . ' class="label" height="27">Total: </td>';
                    $this->salida .='<td><input name="totalcr" type="text" class="input-text" value=' . $_REQUEST['totalcr'] . '></td>';
                    $this->salida .='</tr>';
                    $this->salida .='</table>';
                    //$this->ConsultaHospitalizacion('3',$Cuenta,$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC,$FechaHoy);
                    $this->salida.="<br><table align=\"center\" >";
                    $this->salida.="<tr>";
                    $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Guardar\" class=\"input-submit\"></form></td>";
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .= "<td >";
                    $this->salida .='<form name="forma" action="' . $accion . '" method="post">';
                    $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
                    $this->salida.= ThemeCerrarTabla();
                    break;
                }

            case "4": {
                    $this->salida.= ThemeAbrirTabla('PAGOS CON TARJETAS DEBITOS');
                    $this->salida .='<form name="forma2" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarTemporales', array('spy' => 4, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Cajaid' => $Cajaid, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente, 'DocumentoId' => $DocumentoId)) . '" method="post">';

                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
                        $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, '', $NombrePaciente, $PagareNumero, $Empresa, $Prefijo);
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaPagares', array('spy' => 4, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Cajaid' => $Cajaid, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente, 'DocumentoId' => $DocumentoId));
                    }
                    //$this->ReturnMetodoExterno('app','Facturacion','user','LlamadaFormaEncabezado',array('PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$FechaC));
                    $this->salida.= '<table align="center" border="0">';
                    //$this->salida .=$deuda;
                    //$this->SetStyle("MensajeError");
                    $this->salida .='<tr>';
                    $this->salida .='<td colspan="5">';
                    $this->salida.= '<table align="center" border="0" width="100%">';
                    $this->salida .=$this->SetStyle("MensajeError");
                    $this->salida.= '</table>';
                    //$this->salida .='<td colspan="5" align="center">'.$this->SetStyle("MensajeError").'</td>';
                    $this->salida .='</td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("tarjeta") . '">Tarjeta: </td>';
                    $this->salida .="<td><select name=\"tarjeta\" class=\"select\">";
                    $Tarjetas = $this->ComboTarjetas();
                    $this->BuscarTarjetas($Tarjetas, $Tarjeta = '', 'D');
                    $this->salida .='</select></td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td class="' . $this->SetStyle("numtarjeta") . '">No. Tarjeta: </td>';
                    $this->salida .='<td><input name="numtarjeta" type="text" class="input-text" maxlength=20  value=' . $_REQUEST['numtarjeta'] . '></td>';
                    $this->salida .='</tr>';
                    $this->salida .='<tr>';
                    $this->salida .='<td class="' . $this->SetStyle("noautorizad") . '">No. de Autorizacion: </td>';
                    $this->salida .='<td><input name="noautorizad" type="text" class="input-text" maxlength=15 value=' . $_REQUEST['noautorizad'] . '></td>';
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .='<td class="' . $this->SetStyle("totald") . '">Total</td>';
                    $this->salida .='<td><input name="totald" type="text" id="totald" class="input-text" value=' . $_REQUEST['totald'] . '></td>';
                    $this->salida .='</tr>';
                    $this->salida .='</table>';
                    //    $this->ConsultaHospitalizacion('4',$Cuenta,$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC);
                    $this->salida.="<br><table align=\"center\" >";
                    $this->salida.="<tr>";
                    $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Guardar\" class=\"input-submit\"></form></td>";
                    $this->salida .='<td>&nbsp;</td>';
                    $this->salida .= "<td >";
                    $this->salida .='<form name="forma" action="' . $accion . '" method="post">';
                    $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
                    $this->salida.= ThemeCerrarTabla();
                    break;
                }
        }//fin switch
        return true;
    }

//FIN DECIDIR Y FORMAS PARA LOS ABONOS DE LOS PAGARE
    /**
     * La funcion CapturaHospitalizacion
     * visualiza la forma principal en donde se llama el encabezado de la informacion del paciente
     * y el responsable,los totales de la cuenta, las consultas generales y los pagos.
     *
     * @access public
     * @return boolean
     */
    function FormaCuenta($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy, $Tiponumeracion, $PagareNumero, $Cajaid, $NombrePaciente, $Empresa, $Prefijo, $Valor, $DocumentoId, $PlanId, $MensajeWs) {

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
            if (empty($PagareNumero) || empty($Empresa) || empty($Prefijo) || empty($Cuenta) || empty($TipoId) || empty($PlanId)) {
                $DocumentoId = $_SESSION['PAGARE']['TIPODOC'];
                $PagareNumero = $_SESSION['PAGARE']['NUMERO'];
                $Prefijo = $_SESSION['PAGARE']['PREFIJO'];
                $Cuenta = $_SESSION['PAGARE']['CUENTA'];
                $Empresa = $_SESSION['PAGARE']['EMPRESA'];
                $TipoId = $_SESSION['PAGARE']['TIPOID'];
                $Cajaid = $_SESSION['PAGARE']['CAJAID'];
                $PlanId = $_SESSION['PAGARE']['PLANID'];
            } else {
                $_SESSION['PAGARE']['TIPODOC'] = $DocumentoId;
                $_SESSION['PAGARE']['NUMERO'] = $PagareNumero;
                $_SESSION['PAGARE']['PREFIJO'] = $Prefijo;
                $_SESSION['PAGARE']['CUENTA'] = $Cuenta;
                $_SESSION['PAGARE']['EMPRESA'] = $Empresa;
                $_SESSION['PAGARE']['TIPOID'] = $TipoId;
                $_SESSION['PAGARE']['CAJAID'] = $Cajaid;
                $_SESSION['PAGARE']['PLANID'] = $PlanId;
            }
            if (empty($DocumentoId) OR empty($_SESSION['PAGARE']['TIPODOC']))
                $DocumentoId = $_REQUEST['DocumentoId'];
            if (empty($Cajaid) OR empty($_SESSION['PAGARE']['CAJAID']))
                $Cajaid = $_REQUEST['Cajaid'];
            if (empty($PagareNumero))
                $PagareNumero = $_REQUEST['PagareNumero'];
            if (empty($Prefijo))
                $Prefijo = $_REQUEST['Prefijo'];
        }

        //global $_BOOKMARK_;
        //$_BOOKMARK_='PRUEBA';
        IncludeLib("tarifario");
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            $this->salida .= ThemeAbrirTabla('CAJA HOSPITALIZACION');
            $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $FechaC));
            //$this->ReturnMetodoExterno('app','Facturacion','user','LlamaTotalesCuenta',array('Cuenta'=>$Cuenta));
            //$this->salida .= "<br>";
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
            $this->salida .= ThemeAbrirTabla('CAJA PUNTO DE VENTA');
            $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, $PlanId);
            $this->salida.=" <br><table border=\"0\" width=\"88%\" align=\"center\">";
            $this->salida.=" <tr><td><fieldset><legend class=\"field\">TOTALES</legend>";
            $TotalCuenta = $this->TotalesPvta($Cuenta);
            $Abonos = $this->Abonos($Cuenta);
            $Total = $Abonos[abono_efectivo] + $Abonos[abono_tarjetas] + $Abonos[abono_cheque];
            $this->salida .= "  <table  border=\"0\" width=\"55%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
            //class=\"modulo_table\"
            $this->salida .= "<tr>";
            $this->salida .= "<td class=\"modulo_list_oscuro\" width=\"40%\">VALOR/GRAVAMEN :</td><td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"gravamen\" class=\"input-text\" value=\"" . FormatoValor($TotalCuenta[valor_gravamen]) . "\"  READONLY ></td>";
            //$this->salida .= "        <td colspan=\"2\"></td></tr>";
            $this->salida .= "</tr>";
            $this->salida .= "        <tr>";
            $this->salida .= "<td class=\"modulo_list_claro\" width=\"40%\">DESCUENTO :</td><td class=\"modulo_list_oscuro\" ><input type=\"text\" name=\"descuento\" class=\"input-text\" value=\"" . FormatoValor($TotalCuenta[descuento]) . "\"  READONLY ></td>";
            //$this->salida .= "        <td colspan=\"2\"></td></tr>";
            $this->salida .= "</tr>";
            $this->salida .= "        <tr>";
            $this->salida .= "<td class=\"modulo_list_oscuro\"  width=\"40%\">TOTALES CUENTA:</td><td  class=\"modulo_list_oscuro\"><input type=\"text\" name=\"total\" class=\"input-text\" value=\"" . FormatoValor($TotalCuenta[total]) . "\"  READONLY ></td>";
            //$this->salida .= "        <td colspan=\"2\"></td></tr>";
            $this->salida .= "</tr>";
            $this->salida .= "    <tr></tr>";
            $this->salida .= "        <tr>";
            $this->salida .= "<td class=\"modulo_list_claro\"  width=\"40%\" >PAGOS EFECTIVO: </td><td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"efectivo\"class=\"input-text\" value=\"" . FormatoValor($Abonos[abono_efectivo]) . "\" READONLY></td>";
            $this->salida .= "        </tr>";
            $this->salida .= "        <tr>";
            $this->salida .= "<td class=\"modulo_list_oscuro\" >PAGOS CHEQUE: </td><td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"cheque\" class=\"input-text\" value=\"" . FormatoValor($Abonos[abono_cheque]) . "\"  READONLY >&nbsp;&nbsp</td>";
            $this->salida .= "        </tr>";
            $this->salida .= "        <tr>";
            $this->salida .= "<td class=\"modulo_list_claro\" >PAGOS TARJETA: </td><td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"tarjetad\" class=\"input-text\" value=\"" . FormatoValor($Abonos[abono_tarjetas]) . "\"  READONLY ></td>";
            //$this->salida .= "&nbsp;&nbsp<input type=\"submit\" name=\"tarjetas\" value=\"Consulta Cr�dito\" class=\"input-submit\"></form></td>";
            $this->salida .= "        </tr>";
            $this->salida .= "        <tr>";
            //$this->salida .= '<td></td><td width="33%"></td><td><form name="forma3" action="'.ModuloGetURL('app','CajaGeneral','user','ConsultaGeneralHospitalizacion',array('spia'=>4,'Cuenta'=>$Cuenta,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'FechaC'=>$FechaC,'FechaHoy'=>$FechaHoy)).'" method="post">';
            //$this->salida .= "<input type=\"submit\" name=\"tarjetas\" value=\"Consulta  D�bito\" class=\"input-submit\"></form></td>";
            $this->salida .= "</tr>";
            $this->salida .= "        <tr>";
            $this->salida .= "<td class=\"modulo_list_oscuro\"  >TOTAL: </td><td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"total\" class=\"input-text\" value=\"" . FormatoValor($Total) . "\"  READONLY></td>";
            $this->salida .= "        </tr>";
            $this->salida .= "        <tr><td  colspan=\"2\">&nbsp;&nbsp;</td></tr>";
            $this->salida .= "        <tr>";
            $this->salida .= "<td class=\"modulo_list_claro\" ><font color=\"red\">SALDO :</font></td><td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"total\" class=\"input-text\" value=\"" . FormatoValor(0) . "\"  READONLY></td>";
            $this->salida .= "        </tr>";
            $this->salida .= "     </table>";
            $this->salida.="</fieldset></td></tr></table>";
        }
        //CAJA PAGARES
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
            if (empty($_POST['valorpagar'])) {
                $_POST['valorpagar'] = $_SESSION['PAGARE']['VALORPAGAR'];
            } else {
                $_SESSION['PAGARE']['VALORPAGAR'] = $_POST['valorpagar'];
            }
            if (!empty($Valor))
                $_SESSION['PAGARE']['TOTAL'] = $Valor;
            else
                $Valor = $_SESSION['PAGARE']['TOTAL'];

            $this->salida = ThemeAbrirTabla('CAJA PAGARES');
            $this->EncabezadoEmpresa($Cajaid);
            $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, "", $NombrePaciente, $PagareNumero, $Empresa, $Prefijo);
            IncludeLib('funciones_pagares');
            $totalabono = AbonosPagares($Empresa, $Prefijo, $PagareNumero);
            if ($totalabono > $Valor) {
                $this->frmError["MensajeError"] = 'EL VALOR DE LOS ABONOS ES MAYOR AL TOTAL DEL PAGARE.';
            }

            $this->salida .='<table align="center" width="88%" border="0">';
            $this->salida .='<tr>';
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .='</tr>';
            //$abonoshechos = BuscarAbonosPagares($Cuenta);
            if (sizeof($abonoshechos) > 0) {
                $this->salida .= "<BR><BR><table border=\"0\" class='modulo_table_title' width=\"80%\" align=\"center\">";
                $this->salida .= " <tr class='modulo_table_title' width=\"100%\"><td align=\"center\" colspan=\"7\">ABONOS REALIZADOS</td></tr>";
                $this->salida .= " <tr class='modulo_table_title'><td width=\"5%\">RECIBO</td><td width=\"20%\" align=\"center\">FECHA</td><td width=\"20%\" align=\"center\">TOTAL ABONO</td><td width=\"20%\" align=\"center\">EFECTIVO</td><td width=\"20%\" align=\"center\">CHEQUES</td><td width=\"15%\" align=\"center\">TARJETAS</td></tr>";
                for ($i = 0; $i < sizeof($abonoshechos); $i++) {
                    if ($i % 2) {
                        $estilo = 'modulo_list_claro';
                    } else {
                        $estilo = 'modulo_list_oscuro';
                    }
                    $recibo = $abonoshechos[$i][prefijo] . $abonoshechos[$i][recibo_caja];
                    $fechaing = $abonoshechos[$i][fecha_ingcaja];
                    $total = $abonoshechos[$i][total_abono];
                    $total_efectivo = $abonoshechos[$i][total_efectivo];
                    $total_cheques = $abonoshechos[$i][total_cheques];
                    $total_tarjetas = $abonoshechos[$i][total_tarjetas];
                    $this->salida .= "                <tr class=\"$estilo\" align=\"center\"><td>" . $recibo . "</td><td align=\"left\">" . $fechaing . "</td><td align=\"left\">" . $total . "</td><td align=\"left\">" . $total_efectivo . "</td><td align=\"left\">" . $total_cheques . "</td><td align=\"left\">" . $total_tarjetas . "</td></tr>";
                }
                $this->salida .= "              </table><BR><BR>";
            }
            //$this->salida .= "<form name=forma4 action='".ModuloGetURL('app','CajaGeneral','user','InsertarPagos',array('spy'=>1,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Cuenta'=>$Cuenta,'Cajaid'=>$Cajaid,'NombrePaciente'=>$NombrePaciente,'PagareNumero'=>$PagareNumero,'Empresa'=>$Empresa,'Prefijo'=>$Prefijo,'Valor'=>$Valor,'DocumentoId'=>$DocumentoId))."' method=post>";
            $this->salida .= "<form name=forma4 action='" . ModuloGetURL('app', 'CajaGeneral', 'user', 'VentanaGuardarRecibo', array('spy' => 1, 'Cuenta' => $Cuenta, 'TipoId' => $_TipoId, 'PacienteId' => $PacienteId, 'PagareNumero' => $PagareNumero, 'Cajaid' => $Cajaid, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente, 'DocumentoId' => $DocumentoId, 'PlanId' => $PlanId)) . "' method=post>";
            $this->salida.=" <br><table border=\"0\" width=\"88%\" align=\"center\">";
            $this->salida.=" <tr><td><fieldset><legend class=\"field\">TOTALES</legend>";
            //$TotalCuenta=$this->TotalesPvta($Cuenta);
            //$Abonos = $this->Abonos("",$_REQUEST['pagarenumero'],$_REQUEST['empresa'],$_REQUEST['prefijo']);
            //$totales = AbonosPagares($Empresa,$_SESSION['PAGARE']['centro_utilidad'],$Prefijo,$PagareNumero,$Cuenta);
            $efectivo = $_POST['valorpagar'];
            //$cheque=$_POST['totalc'];
            $cheque = $_SESSION['PAGARE']['valorc'];
            //$tarjetac=$_POST['totalcr'];
            $tarjetac = $_SESSION['PAGARE']['valorcr'];
            //$tarjetad=$_POST['totald'];
            $tarjetad = $_SESSION['PAGARE']['valord'];
            $this->salida .= "  <table  border=\"0\" width=\"65%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
            //class=\"modulo_table\"
            /* $this->salida .= "        <tr>";
              $this->salida .= "<td class=\"modulo_list_oscuro\"  width=\"40%\">TOTALES CUENTA:</td><td  class=\"modulo_list_oscuro\"><input type=\"text\" name=\"total\" class=\"input-text\" value=\"".FormatoValor($totales[totales])."\"  READONLY ></td>";
              //$this->salida .= "        <td colspan=\"2\"></td></tr>";
              $this->salida .= "</tr>"; */
            $this->salida .= "    <tr></tr>";
            $this->salida .= "        <tr>";
            //$this->salida .= "<td class=\"modulo_table_title\"  width=\"40%\" >PAGOS EFECTIVO: </td><td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"efectivo\"class=\"input-text\" value=\"".FormatoValor($totales[efectivo])."\" READONLY>&nbsp;&nbsp<a href=\"".ModuloGetURL('app','CajaGeneral','user','DecisionHospitalizacion',array('spy'=>7,'Cuenta'=>$Cuenta,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'FechaC'=>$FechaC,'FechaHoy'=>'','Cajaid'=>$Cajaid))."\"><img src=\"". GetThemePath() ."/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a></td>";
            $this->salida .= "<td class=\"modulo_table_title\"  width=\"40%\" >VALOR: </td><td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"valor\"class=\"input-text\" value=\"" . FormatoValor($Valor) . "\" READONLY></td>";
            $this->salida .= "        </tr>";
            $this->salida .= "        <tr>";
            $saldo1 = $Valor - $totalabono;
            //$this->salida .= "<td class=\"modulo_table_title\"  width=\"40%\" >PAGOS EFECTIVO: </td><td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"efectivo\"class=\"input-text\" value=\"".FormatoValor($totales[efectivo])."\" READONLY>&nbsp;&nbsp<a href=\"".ModuloGetURL('app','CajaGeneral','user','DecisionHospitalizacion',array('spy'=>7,'Cuenta'=>$Cuenta,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'FechaC'=>$FechaC,'FechaHoy'=>'','Cajaid'=>$Cajaid))."\"><img src=\"". GetThemePath() ."/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a></td>";
            if ($totalabono >= $Valor)
                $this->salida .= "<td class=\"modulo_table_title\"  width=\"40%\" >PAGOS EFECTIVO: </td><td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"efectivo\"class=\"input-text\" value=\"" . $efectivo . "\" READONLY></td>";
            else
                $this->salida .= "<td class=\"modulo_table_title\"  width=\"40%\" >PAGOS EFECTIVO: </td><td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"efectivo\"class=\"input-text\" value=\"" . $efectivo . "\" READONLY>&nbsp;&nbsp<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'DecisionPagares', array('spy' => 1, 'Cuenta' => $_REQUEST['Cuenta'], 'TipoId' => $_REQUEST['TipoId'], 'PacienteId' => $_REQUEST['PacienteId'], 'NombrePaciente' => $NombrePaciente, 'PagareNumero' => $PagareNumero, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Cajaid' => $Cajaid, 'saldo' => $saldo1, 'Valor' => $Valor, 'DocumentoId' => $DocumentoId)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a></td>";
            $this->salida .= "        </tr>";
            //NUEVAS OPCIONES
            $this->salida .= "        <tr>";
            $this->salida .= '<td class=modulo_table_title>PAGOS CHEQUE</td><td class=modulo_list_claro>';

            if ($totalabono >= $Valor)
                $this->salida .= "<input type=\"text\" name=\"cheque\" class=\"input-text\" value=\"" . $cheque . "\" READONLY></td>";
            else
            if ($cheque > 0) {
                $this->salida .= "<input type=\"text\" name=\"cheque\" class=\"input-text\" value=\"" . $cheque . "\" READONLY>&nbsp;&nbsp<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'autorizacionPagares', array('spy' => 2, 'Cuenta' => $_REQUEST['Cuenta'], 'TipoId' => $_REQUEST['TipoId'], 'PacienteId' => $_REQUEST['PacienteId'], 'NombrePaciente' => $NombrePaciente, 'PagareNumero' => $PagareNumero, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Cajaid' => $Cajaid, 'Valor' => $Valor, 'DocumentoId' => $DocumentoId)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a>&nbsp;&nbsp;|&nbsp;<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'RevisarTemp', array('spy' => 2, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'NombrePaciente' => $NombrePaciente, 'PagareNumero' => $PagareNumero, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Cajaid' => $Cajaid, 'Valor' => $Valor, 'DocumentoId' => $DocumentoId)) . "\">CONSULTA</a></td>";
            } else {
                $this->salida .= "<input type=\"text\" name=\"cheque\" class=\"input-text\" value=\"" . $cheque . "\" READONLY>&nbsp;&nbsp<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'autorizacionPagares', array('spy' => 2, 'Cuenta' => $_REQUEST['Cuenta'], 'TipoId' => $_REQUEST['TipoId'], 'PacienteId' => $_REQUEST['PacienteId'], 'NombrePaciente' => $NombrePaciente, 'PagareNumero' => $PagareNumero, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Cajaid' => $Cajaid, 'Valor' => $Valor, 'DocumentoId' => $DocumentoId)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a></td>";
            }

            $this->salida .= "        </tr>";
            $this->salida .= "        <tr class=modulo_list_oscuro>";
            $this->salida .= '<td class=modulo_table_title>PAGOS TARJETA CREDITO</td><td>';

            if ($totalabono >= $Valor)
                $this->salida .= "<input type=\"text\" name=\"tarjetad\" class=\"input-text\" value=\"" . $tarjetac . "\" READONLY></td>";
            else
            if ($tarjetac > 0) {
                $this->salida .= "<input type=\"text\" name=\"tarjetad\" class=\"input-text\" value=\"" . $tarjetac . "\" READONLY>&nbsp;&nbsp<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'autorizacionPagares', array('spy' => 3, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'NombrePaciente' => $NombrePaciente, 'PagareNumero' => $PagareNumero, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Cajaid' => $Cajaid, 'Valor' => $Valor, 'DocumentoId' => $DocumentoId)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a>&nbsp;&nbsp;|&nbsp;<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'RevisarTemp', array('spy' => 3, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'NombrePaciente' => $NombrePaciente, 'PagareNumero' => $PagareNumero, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Cajaid' => $Cajaid, 'Valor' => $Valor, 'DocumentoId' => $DocumentoId)) . "\">CONSULTA</a></td>";
            } else {
                $this->salida .= "<input type=\"text\" name=\"tarjetad\" class=\"input-text\" value=\"" . FormatoValor($tarjetac) . "\" READONLY>&nbsp;&nbsp<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'autorizacionPagares', array('spy' => 3, 'Cuenta' => $Cuenta, 'TipoId' => $_TipoId, 'PacienteId' => $PacienteId, 'NombrePaciente' => $NombrePaciente, 'PagareNumero' => $PagareNumero, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Cajaid' => $Cajaid, 'Valor' => $Valor, 'DocumentoId' => $DocumentoId)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a></td>";
            }
            $this->salida .= "        </tr>";
            $this->salida .= "        <tr class=modulo_list_claro>";
            $this->salida .= '<td class=modulo_table_title>PAGOS TARJETA DEBITO</td><td>';

            if ($totalabono >= $Valor)
                $this->salida .= "<input type=\"text\" name=\"tarjetac\" class=\"input-text\" value=\"" . $tarjetad . "\" READONLY></td>";
            else
            if ($tarjetad > 0) {
                $this->salida .= "<input type=\"text\" name=\"tarjetac\" class=\"input-text\" value=\"" . $tarjetad . "\" READONLY>&nbsp;&nbsp<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'DecisionPagares', array('spy' => 4, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'NombrePaciente' => $NombrePaciente, 'PagareNumero' => $PagareNumero, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Cajaid' => $Cajaid, 'Valor' => $Valor, 'DocumentoId' => $DocumentoId)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a>&nbsp;&nbsp;|&nbsp;<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'RevisarTemp', array('spy' => 4, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'NombrePaciente' => $NombrePaciente, 'PagareNumero' => $PagareNumero, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Cajaid' => $Cajaid, 'Valor' => $Valor, 'DocumentoId' => $DocumentoId)) . "\">CONSULTA</a></td>";
            } else {
                $this->salida .= "<input type=\"text\" name=\"tarjetac\" class=\"input-text\" value=\"" . $tarjetad . "\" READONLY>&nbsp;&nbsp<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'DecisionPagares', array('spy' => 4, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'NombrePaciente' => $NombrePaciente, 'PagareNumero' => $PagareNumero, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Cajaid' => $Cajaid, 'Valor' => $Valor, 'DocumentoId' => $DocumentoId)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a></td>";
            }
            $this->salida .= "        </tr>";
            //FIN - NUEVAS OPCIONES
            $totalabonosactuales = $efectivo + $cheque + $tarjetac + $tarjetad;
            $totalabonosanteriores = $totalabono;
            $totalabonos = $efectivo + $cheque + $tarjetac + $tarjetad;
            $this->salida .= "        <tr>";
            $this->salida .= "<td class=\"modulo_table_title\"  >TOTAL ABONOS: </td><td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"totalabonos\" class=\"input-text\" value=\"" . FormatoValor($totalabonos) . "\"  READONLY></td>";
            $this->salida .= "        </tr>";
            $this->salida .= "        <tr><td  colspan=\"2\">&nbsp;&nbsp;</td></tr>";
            $saldo = $_SESSION['CAJA']['TOTAL'] = $Valor - $totalabonosanteriores;
            $this->salida .= "        <tr>";
            $this->salida .= "<td class=\"modulo_table_title\" ><font color=\"red\">SALDO :</font></td><td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"total\" class=\"input-text\" value=\"" . FormatoValor($saldo) . "\"  READONLY></td>";
            $this->salida .= "        </tr>";
            //$this->salida .= "     </table>";
            //$this->salida .= "</table>";
            $this->salida .= "        <tr>";
            //$this->salida.=" <table border=\"0\" width=\"88%\" align=\"center\">";
            $this->salida .= "<td  align=\"center\">";
            $this->salida .= "<input type=\"submit\" name=\"guardar\" value=\"GUADAR\" class=\"input-submit\">";
            $this->salida .= "</td>";
            $this->salida .= "</form>";
            $this->salida .= "<form name=forma3 action='" . ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarCriterios') . "' method=post>";
            $this->salida .= "<td align=\"center\">";
            $this->salida .= "<input type=\"submit\" name=\"volver\" value=\"VOLVER\" class=\"input-submit\">";
            $this->salida .= "</td>";
            $this->salida .= "</form>";
            $this->salida .= "        </tr>";
            //$this->salida .= "     </table>";
            //$this->salida .= "     </table>";
            $this->salida.="</table></fieldset></td></tr>";
            $this->salida .= "     </table>";
            //ConsultaCajaHospitalizacion($Cuenta,'',$TipoId,$PacienteId,$Nivel,$PlanId,$Pieza,$Cama,$FechaC,$Ingreso,$NombrePaciente,$PagareNumero,$Empresa,$Prefijo);
            $this->ConsultaCajaHospitalizacion($Cuenta, '', $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo);
        } else {
            //FIN CAJAS PAGARES
            //llama la forma de abonos
            $this->FormaAbonos($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy);
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
                $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamaTotalesCuenta', array('Cuenta' => $Cuenta));
                $Abonos = $this->Abonos($Cuenta);
                $Total = $Abonos[abono_efectivo] + $Abonos[abono_tarjetas] + $Abonos[abono_cheque];
                //$Abonos=$this->AbonosCuenta($Cuenta);
                //$Total=$Abonos[totalefectivo]+$Abonos[totaltarjetas]+$Abonos[totalcheques];


                IncludeLib("funciones_facturacion");
                //$totales=$this->CallMetodoExterno('app','Facturacion','user','BuscarTotales',array('Cuenta'=>$Cuenta));
                $saldo = SaldoCuentaPaciente($Cuenta); //;($totales[valor_nocubierto]+$totales[valor_cuota_paciente])-$Total;
                $this->salida .= "        <BR>";
                $this->salida .= "  <table border=\"0\" width=\"75%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
                $this->salida .= "        <tr>";
                $this->salida .= '<td class="label" width="27%" >PAGOS EFECTIVO: </td><td colspan="2"><form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', '', array('spy' => 1, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy)) . '" method="post">';
                //<input type=\"submit\" name=\"efectivos\" value=\"Consulta Pagos\" class=\"input-submit\" >
                //$this->salida .= "<input type=\"text\" name=\"efectivo\"class=\"input-text\" value=\"".FormatoValor($Abonos[totalefectivo])."\" READONLY>&nbsp;&nbsp</form></td>";
                $this->salida .= "<input type=\"text\" name=\"efectivo\"class=\"input-text\" value=\"" . FormatoValor($Abonos[abono_efectivo]) . "\" READONLY>&nbsp;&nbsp</form></td>";
                $this->salida .= "        </tr>";
                $this->salida .= "        <tr>";
                $this->salida .= '<td class="label">PAGOS CHEQUE: </td><td colspan="2"><form name="forma1" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'ConsultaGeneralHospitalizacion', array('spia' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy)) . '" method="post">';
                $this->salida .= "<input type=\"text\" name=\"cheque\" class=\"input-text\" value=\"" . FormatoValor($Abonos[abono_cheque]) . "\" READONLY ></td>";
                //$this->salida .= "<input type=\"text\" name=\"cheque\" class=\"input-text\" value=\"".FormatoValor($Abonos[totalcheques])."\" READONLY ></td>";
                //$this->salida .= "&nbsp;&nbsp<input type=\"submit\" name=\"cheques\" value=\"Consulta  Pagos\" class=\"input-submit\" ></form></td>";
                $this->salida .= "        </tr>";
                $this->salida .= "        <tr>";
                $this->salida .= '<td class="label">PAGOS TARJETA: </td><td colspan="2">';
                //$this->salida .= '<form name="forma2" action="'.ModuloGetURL('app','CajaGeneral','user','ConsultaGeneralHospitalizacion',array('spia'=>3,'Cuenta'=>$Cuenta,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'FechaC'=>$FechaC,'FechaHoy'=>$FechaHoy)).'" method="post">';
                $this->salida .= "<input type=\"text\" name=\"tarjetad\" class=\"input-text\" value=\"" . FormatoValor($Abonos[abono_tarjetas]) . "\" READONLY >";
                //$this->salida .= "<input type=\"text\" name=\"tarjetad\" class=\"input-text\" value=\"".FormatoValor($Abonos[totaltarjetas])."\" READONLY >";
                //$this->salida .= "&nbsp;&nbsp<input type=\"submit\" name=\"tarjetas\" value=\"Consulta Cr�dito\" class=\"input-submit\"></form></td>";
                $this->salida .= "        </tr>";
                $this->salida .= "        <tr>";
                //$this->salida .= '<td></td><td width="32%"></td><td><form name="forma3" action="'.ModuloGetURL('app','CajaGeneral','user','ConsultaGeneralHospitalizacion',array('spia'=>4,'Cuenta'=>$Cuenta,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'FechaC'=>$FechaC,'FechaHoy'=>$FechaHoy)).'" method="post">';
                //$this->salida .= "<input type=\"submit\" name=\"tarjetas\" value=\"Consulta  D�bito\" class=\"input-submit\"></form></td>";
                $this->salida .= "</tr>";
                $this->salida .= "        <tr>";
                $this->salida .= "<td class=\"label\">TOTAL: </td><td  colspan=\"2\"><input type=\"text\" name=\"total\" class=\"input-text\" value=\"" . FormatoValor($Total) . "\"  READONLY></td>";
                $this->salida .= "        </tr>";

                //Traer el valor total de la devolucion
                $devolucion = $this->GetDevolucion();
                //fin traer valor de la devolucion

                /* 										if($saldo < 0)
                  {
                  //revisamos cuantas devoluciones tiene esta caja.
                  $devolucion=$this->GetDevolucion();
                  $resultado=abs($saldo) - $devolucion;
                  if($resultado > 0)
                  {
                  $resultado='-'.$resultado;
                  }

                  }
                  else
                  {
                  $resultado=$saldo;
                  } */
                $resultado = $saldo;

                $this->salida .= "        <tr>";
                $this->salida .= "<td class=\"label_error\">DEVOLUCION: </td><td  colspan=\"2\"><input type=\"text\" name=\"dev\" class=\"input-text\" value=\"" . FormatoValor($devolucion) . "\"  READONLY></td>";
                $this->salida .= "        </tr>";

                $this->salida .= "        <tr>";
                $this->salida .= "<td class=\"label_error\">SALDO: </td><td  colspan=\"2\"><input type=\"text\" name=\"total\" class=\"input-text\" value=\"" . FormatoValor($resultado) . "\"  READONLY></td>";
                $this->salida .= "        </tr>";
                $this->salida .= "     </table>";
                $this->salida.="</fieldset></td></tr></table>";


                //SACAMOS ESTA PANTALLITA SI EL SALDO ESTA NEGATIVO.....
                //IMPLICA QUE PAGO DE MAS EL PACIENTE...
                //$saldo=($totales[valor_nocubierto]+$totales[valor_cuota_paciente])-$Total;

                /* 									 if($resultado < 0)
                  { */
                $this->salida .= '</form>';
                $this->salida .= '<form name="formas" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarDevolucion', array('sal' => $resultado, 'Cuenta' => $Cuenta, 'Cama' => $Cama, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Nivel' => $Nivel, 'Tiponumeracion' => $Tiponumeracion, 'TipoCuenta' => $TipoCuenta, 'Cajaid' => $Cajaid)) . '" method="post">';
                /* $this->salida .= "<script language='javascript'>\n";
                  $this->salida .= "function revisar(a){\n";
                  $this->salida .= "var b=".abs($resultado).";\n";
                  $this->salida .= "if(a > b){\n;";
                  $this->salida .= "alert('El valor de la devolucion debe ser menor o igual  a:'+ b)};\n";
                  $this->salida .= "}\n";
                  $this->salida .= "</script>\n"; */

                $this->salida.=" <br><table border=\"0\" width=\"88%\" align=\"center\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida.=" <tr><td><fieldset><legend class=\"field\">DEVOLUCIONES</legend>";
                $this->salida .= "  <table  border=\"1\" bordercolor='#DDDDDD'  width=\"70%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
                $this->salida .= "<tr>";
                $this->salida .= "<td class=\"modulo_list_oscuro\" width=\"40%\"><label class='label_mark'>VALOR DE LA DEVOLUCION:</label></td><td class=\"modulo_list_oscuro\"><input type=\"text\" name=\"devol\" class=\"input-text\" value=\"\"> <input type=\"button\" name=\"limpiar\" class=\"input-submit\" value=Borrar onclick=javascript:devol.value='';>&nbsp;&nbsp<input type=\"submit\" name=\"devolucion\" class=\"input-submit\" value=Guardar></td>";
                $this->salida .= "</tr>";
                $this->salida .= "     </table>";
                $this->salida .= '</form>';
                $this->salida.="</fieldset></td></tr></table>";
// 										}
            }
        }//FIN ELSE DE TOPOCUENTA =06  
        //$this->salida .="<A NAME = 'PRUEBA'></A>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaAbonos($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Cajaid) {
        IncludeLib("tarifario");
        unset($_SESSION['CAJA']['CONSECUTIVO']); //serial de consec de creacion de che
        unset($_SESSION['CAJA']['KIA']); //var de session de autorizacion de che
        unset($_SESSION['CAJA']['NUM']); //var de session de autorizacion de che o tar
        unset($_SESSION['CAJA']['CONSECUTIVOS']); //serial de consec de creacion de tar
        unset($_SESSION['CAJA']['KIAS']); //var de session de autorizacion de tar
        unset($_SESSION['CAJA']['NUMS']); //var de session de autorizacion de tar
        $this->salida.=" <br><table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida.=" <tr><td><fieldset><legend class=\"field\"> REGISTRAR PAGOS/ABONOS</legend>";
        $this->salida .= "  <table border=\"0\" width=\"55%\" align=\"center\" class=\"modulo_table_list\" >";
        $AbonosNew = $this->TotalAbonos($Cuenta);
        $tarjetac = $AbonosNew[tarjeta_credito];
        $tarjetad = $AbonosNew[tarjeta_debito];
        //$abono=$AbonosNew[caja_bono];
        $Tefectivo = $_SESSION['CAJA']['TOTAL_EFECTIVO'];
        $abono = $_SESSION['CAJA']['BONO'];
        $cheque = $AbonosNew[cheque];
        if (!$Tefectivo) {
            $Tefectivo = 0;
        }
        if (!$tarjetad) {
            $tarjetad = 0;
        }
        if (!$tarjetac) {
            $tarjetac = 0;
        }
        if (!$abono) {
            $abono = 0;
        }
        if (empty($cheque)) {
            $cheque = 0;
        }
        //$this->salida .= '<form name="elimina">';
        //$mostrar ="\n<script language='javascript'>\n";
        //$mostrar.="  function mirar(formin){\n";
        //$mostrar.="    alert(formin);\n";
        //$mostrar.="    document.forma1.efectivo.value=''};\n";
        //$mostrar.="</script>\n";
        //$this->salida.="$mostrar";
        $total = $Tefectivo + $AbonosNew[tarjeta_credito] + $AbonosNew[tarjeta_debito] + $AbonosNew[cheque] + $abono;
        $this->salida .= "        <tr >";
        $this->salida .= '<td class=modulo_table_title width=\"43%\" >PAGOS EFECTIVO</td><td class=modulo_list_oscuro >';

        //esta linea permite pagos de abonos en la forma clasica..dejo esto por si despues lo cambian.
        $this->salida .= "<input type=\"text\" name=\"efectivo\"class=\"input-text\" value=\"" . FormatoValor($Tefectivo) . "\"  size=\"15\" READONLY>&nbsp;&nbsp<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'DecisionHospitalizacion', array('spy' => 1, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => '', 'Cajaid' => $Cajaid)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a></form></td>";
        //$this->salida .= "<input type=\"text\" name=\"efectivo\"class=\"input-text\" value=\"".$this->FormatoValor($Tefectivo)."\"  size=\"15\">&nbsp;&nbsp</td>";
        $this->salida .= "        </tr>";
        $this->salida .= "        <tr>";
        $this->salida .= '<td class=modulo_table_title>PAGOS CHEQUE</td><td class=modulo_list_claro>'; 

        if ($cheque > 0) {
            $this->salida .= "<input type=\"text\" name=\"cheque\" class=\"input-text\" value=\"" . FormatoValor($cheque) . "\"  size=\"15\" READONLY>&nbsp;&nbsp<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'autorizacion', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a>&nbsp;&nbsp;|&nbsp;<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'RevisarTemp', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid)) . "\">CONSULTA</a></td>";
        } else {
            $this->salida .= "<input type=\"text\" name=\"cheque\" class=\"input-text\" value=\"" . FormatoValor($cheque) . "\"  size=\"15\" READONLY>&nbsp;&nbsp<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'autorizacion', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a></td>";
        }
        $this->salida .= "        </tr>";
        $this->salida .= "        <tr class=modulo_list_oscuro>";
        $this->salida .= '<td class=modulo_table_title>PAGOS TARJETA CREDITO</td><td>';

        if ($tarjetac > 0) {
            $this->salida .= "<input type=\"text\" name=\"tarjetad\" class=\"input-text\" value=\"" . FormatoValor($tarjetac) . "\"  size=\"15\" READONLY>&nbsp;&nbsp<a style='display:none;' href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'autorizacion', array('spy' => 3, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a>&nbsp;&nbsp;|&nbsp;<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'RevisarTemp', array('spy' => 3, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid)) . "\">CONSULTA</a></td>";
        } else {
            $this->salida .= "<input type=\"text\" name=\"tarjetad\" class=\"input-text\" value=\"" . FormatoValor($tarjetac) . "\"  size=\"15\" READONLY>&nbsp;&nbsp<a style='display:none;' href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'autorizacion', array('spy' => 3, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a></td>";
        }
        $this->salida .= "        </tr>";
        $this->salida .= "        <tr class=modulo_list_claro>";
        $this->salida .= '<td class=modulo_table_title>PAGOS TARJETA DEBITO</td><td>';

        if ($tarjetad > 0) {
            $this->salida .= "<input type=\"text\" name=\"tarjetac\" class=\"input-text\" value=\"" . FormatoValor($tarjetad) . "\" size=\"15\" READONLY>&nbsp;&nbsp<a  style='display:none;' href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'DecisionHospitalizacion', array('spy' => 4, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => '', 'Cajaid' => $Cajaid)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a>&nbsp;&nbsp;|&nbsp;<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'RevisarTemp', array('spy' => 4, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'Cajaid' => $Cajaid)) . "\">CONSULTA</a></td>";
        } else {
            $this->salida .= "<input type=\"text\" name=\"tarjetac\" class=\"input-text\" value=\"" . FormatoValor($tarjetad) . "\" size=\"15\" READONLY>&nbsp;&nbsp<a style='display:none;' href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'DecisionHospitalizacion', array('spy' => 4, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => '', 'Cajaid' => $Cajaid)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a></td>";
        }
        $this->salida .= "        </tr>";
        $this->salida .= "        <tr class=modulo_list_claro >";
        $this->salida .= "<td class=modulo_table_title>PAGOS BONOS</td><td><input type=\"text\" name=\"bono\" class=\"input-text\" value=\"" . FormatoValor($abono) . "\"  size=\"15\" READONLY>&nbsp;&nbsp<a href=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'DecisionHospitalizacion', array('spy' => 5, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => '', 'Cajaid' => $Cajaid)) . "\"><img src=\"" . GetThemePath() . "/images/plata.png\" border='0' width='20' height='12'>&nbsp;ABONOS</a></td>";
        $this->salida .= "        </tr>";
        $this->salida .= "        <tr class=modulo_list_claro >";

        if (FormatoValor($_SESSION['CAJA']['SAL'] - $total) == 0) {
            $this->salida .= "<td class=modulo_table_title>DESCUENTOS ($)</td><td><input type=\"text\" name=\"bono\" class=\"input-text\" value=\"" . FormatoValor($_SESSION['CAJA']['ARRAY_DESCUENTO']['valor_cargo']) . "\"  size=\"15\" READONLY></td>";
        } else {
//										$this->salida .= "<td class=modulo_table_title>DESCUENTOS ($)</td><td><input type=\"text\" name=\"bono\" class=\"input-text\" value=\"".FormatoValor($_SESSION['CAJA']['ARRAY_DESCUENTO']['valor_cargo'])."\"  size=\"15\" READONLY>&nbsp;&nbsp<a href=\"".ModuloGetURL('app','CajaGeneral','user','DecisionHospitalizacion',array('spy'=>6,'Cuenta'=>$Cuenta,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'FechaC'=>$FechaC,'FechaHoy'=>'','Cajaid'=>$Cajaid))."\"><img src=\"". GetThemePath() ."/images/descuento.png\" border='0' width='20' height='12'>&nbsp;DESCUENTOS</a></td>";
        }
//[duvan]
        $this->salida .= "        </tr>";
        $this->salida .= "        <tr class=modulo_list_oscuro >";
        $this->salida .= "<td class=modulo_table_title>TOTAL</td><td><input type=\"text\" name=\"total\" class=\"input-text\" value=\"" . FormatoValor($total) . "\"  size=\"15\" READONLY></td>";
        $this->salida .= "        </tr>";
        $this->salida .= "        <tr >";
        $_SESSION['CAJA']['TOTAL'] = $total;
        
        
        $_SESSION['CAJA']['BONO'] = $abono;
        $_SESSION['CAJA']['SUBTOTAL'] = $total - $Tefectivo; //arreglo de 2005-09-01 [duvan] Para la s.o.s



        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03' || $_SESSION['CAJA']['TIPOCUENTA'] == '04' || $_SESSION['CAJA']['TIPOCUENTA'] == '05' || $_SESSION['CAJA']['TIPOCUENTA'] == '08') {
           
            $this->salida .= "<script>\n";
            $this->salida.= "  function Eval(total,valor2,forma)\n";
            $this->salida.= "  {\n";
            $this->salida.= "     if(total<valor2)\n";
            $this->salida.= "     {\n";
            $this->salida.= "        alert('TOTAL DEBE SER IGUAL O MAYOR AL TOTAL PACIENTES');\n";
            $this->salida.= "     }\n";
            $this->salida.= "     else\n";
            $this->salida.= "     {\n";
            $this->salida .= "    forma.action = \"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'VentanaGuardarRecibo', array('spy' => 1, 'Cuenta' => $Cuenta, 'Cama' => $Cama, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'efectivo' => $Tefectivo, 'cheque' => $cheque, 'tarjetad' => $tarjetad, 'tarjetac' => $tarjetac, 'Tiponumeracion' => $Tiponumeracion, 'TipoCuenta' => $TipoCuenta, 'Cajaid' => $Cajaid)) . "\";\n";
            $this->salida .= "    forma.submit();\n";
            $this->salida.= "     }\n";
            $this->salida .= "  }\n";
            $this->salida .= "</script>\n";
            //$this->salida .= '<form name="f" action="'.ModuloGetURL('app','CajaGeneral','user','VentanaGuardarRecibo',array('spy'=>1,'Cuenta'=>$Cuenta,'Cama'=>$Cama,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'FechaC'=>$FechaC,'FechaHoy'=>$FechaHoy,'efectivo'=>$Tefectivo,'cheque'=>$cheque,'tarjetad'=>$tarjetad,'tarjetac'=>$tarjetac,'Tiponumeracion'=>$Tiponumeracion,'TipoCuenta'=>$TipoCuenta,'Cajaid'=>$Cajaid)).'" method="post">';
            $this->salida .= "<form name=\"forma_pago\" action=\"javascript:Eval('" . $total . "','" . $_SESSION['CAJA']['AUX']['liq']['valor_total_paciente'] . "',document.forma_pago)\"  method=\"post\">";
        } else {
            
            $this->salida .= "<script>\n";
            $this->salida.= "  function Eval1(total,valor2,forma)\n";
            $this->salida.= "  {\n";
            $this->salida.= "     if(total<valor2)\n";
            $this->salida.= "     {\n";
            $this->salida.= "        alert('TOTAL DEBE SER IGUAL O MAYOR AL TOTAL PACIENTES');\n";
            $this->salida.= "     }\n";
            $this->salida.= "     else\n";
            $this->salida.= "     {\n";
            $this->salida .= "    forma.action = \"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarHospitalizacion', array('spy' => 1, 'Cuenta' => $Cuenta, 'Cama' => $Cama, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'efectivo' => $Tefectivo, 'cheque' => $cheque, 'tarjetad' => $tarjetad, 'tarjetac' => $tarjetac, 'Tiponumeracion' => $Tiponumeracion, 'TipoCuenta' => $TipoCuenta, 'Cajaid' => $Cajaid)) . "\";\n";
            $this->salida .= "    forma.submit();\n";
            $this->salida.= "     }\n";
            $this->salida .= "  }\n";
            $this->salida .= "</script>\n";
            //$this->salida .= '<form name="f" action="'.ModuloGetURL('app','CajaGeneral','user','InsertarHospitalizacion',array('spy'=>1,'Cuenta'=>$Cuenta,'Cama'=>$Cama,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'FechaC'=>$FechaC,'FechaHoy'=>$FechaHoy,'efectivo'=>$Tefectivo,'cheque'=>$cheque,'tarjetad'=>$tarjetad,'tarjetac'=>$tarjetac,'Tiponumeracion'=>$Tiponumeracion,'TipoCuenta'=>$TipoCuenta,'Cajaid'=>$Cajaid)).'" method="post">';
            $this->salida .= "<form name=\"forma_pago1\" action=\"javascript:Eval1('" . $total . "','" . $_SESSION['CAJA']['AUX']['liq']['valor_total_paciente'] . "',document.forma_pago1)\" method=\"post\">";
        }

        //si la caja es hospitalaria no permitiremos pagar en 0.
        //por eso preguntamos aqui si tipocuenta='01'
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            if ($total < 1) {
                $this->salida .= "<td class=\"modulo_list_claro\" align=\"CENTER\"><img src=\"" . GetThemePath() . "/images/informacion.png\" border='0'>&nbsp;<label class='label_mark'><SUB>DEBE REALIZAR UN ABONO <BR> ANTES DE GUARDAR</SUB></label></form></td>";
            } else {
                $this->salida .= "<td class=\"modulo_list_claro\" align=\"right\"><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\" *btn* Guardar\" class=\"input-submit\"></form></td>";
            }
        } else {
            $this->salida .= "<td class=\"modulo_list_claro\" align=\"right\"><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Guardar\" class=\"input-submit\"></form></td>";
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'BorrarTemporales', array('Caja' => $_SESSION['CAJA']['CAJAID'], 'Empresa' => $_SESSION['CAJA']['EMPRESA'], 'CentroUtilidad' => $_SESSION['CAJA']['CENTROUTILIDAD'], 'Tiponumeracion' => $Tiponumeracion, 'TipoCuenta' => $_SESSION['CAJA']['TIPOCUENTA'], 'CU' => $_SESSION['CAJA']['CU'], 'SWCUENTAS' => 'Cuentas'));
            //cambio dar
            if (!empty($_SESSION['CAJA']['RETORNO'])) {
                $nom = 'Volver';
            } else {
                $nom = 'Buscar Cuenta';
            }
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'BorrarTemporales', array('Caja' => $_SESSION['CAJA']['CAJAID'], 'Empresa' => $_SESSION['CAJA']['EMPRESA'], 'CentroUtilidad' => $_SESSION['CAJA']['CENTROUTILIDAD'], 'Tiponumeracion' => $Tiponumeracion, 'TipoCuenta' => $_SESSION['CAJA']['TIPOCUENTA'], 'CU' => $_SESSION['CAJA']['CU'], 'SWCUENTAS' => 'Cuentas'));
            $nom = 'Buscar Cuenta';
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'BorrarTemporales', array('Caja' => $_SESSION['CAJA']['CAJAID'], 'Empresa' => $_SESSION['CAJA']['EMPRESA'], 'CentroUtilidad' => $_SESSION['CAJA']['CENTROUTILIDAD'], 'Tiponumeracion' => $Tiponumeracion, 'TipoCuenta' => $_SESSION['CAJA']['TIPOCUENTA']));
            $nom = 'Buscar Cuenta';
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03' OR $_SESSION['CAJA']['TIPOCUENTA'] == '08') {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'BorrarTemporales', array('Caja' => $_SESSION['CAJA']['CAJAID'], 'Empresa' => $_SESSION['CAJA']['EMPRESA'], 'CentroUtilidad' => $_SESSION['CAJA']['CENTROUTILIDAD'], 'Tiponumeracion' => $Tiponumeracion, 'TipoCuenta' => $_SESSION['CAJA']['TIPOCUENTA']));
            $nom = 'Volver';
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'BorrarTemporales', array('Caja' => $_SESSION['CAJA']['CAJAID'], 'Empresa' => $_SESSION['CAJA']['EMPRESA'], 'CentroUtilidad' => $_SESSION['CAJA']['CENTROUTILIDAD'], 'Tiponumeracion' => $Tiponumeracion, 'TipoCuenta' => $_SESSION['CAJA']['TIPOCUENTA']));
            $nom = 'Volver';
        }
        $this->salida .= '<form name="f" action="' . $accion . '" method="post">';
        $this->salida .= "<td class=\"modulo_list_claro\"  align=\"center\"><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"$nom\" class=\"input-submit\"></form></td>";
        $this->salida .= "        </tr>";
        $this->salida .= "     </table>";
        $this->salida .= "</fieldset></td></tr></table>";
        //si es conceptos no debo mostrar los pagos anteriores
        if ($_SESSION['CAJA']['TIPOCUENTA'] != '03') {
            $this->ConsultaCajaHospitalizacion($Cuenta, '', $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso);
            return true;
        }
    }

    function FormaMetodoBuscar() {
        unset($_SESSION['CAJA']['ORDENPAGO']);
        unset($_SESSION['CAJA']['CUENTA']);
        unset($_SESSION['CAJA']['TERCEROID']);
        unset($_SESSION['CAJA']['TIPO_ID_TERCERO']);
        unset($_SESSION['CAJA']['NOMBRE_TERCERO']);

        if (empty($_SESSION['CAJA']['EMPRESA'])) {
            $this->Empresa = $_REQUEST['Caja']['empresa_id'];
            $this->Cutilidad = $_REQUEST['Caja']['centro_utilidad'];
            $_SESSION['CAJA']['EMPRESA'] = $this->Empresa;
            $_SESSION['CAJA']['CENTROUTILIDAD'] = $this->Cutilidad;
            $this->Cutilidad = $_SESSION['CAJA']['CENTROUTILIDAD'];
            $this->Empresa = $_SESSION['CAJA']['EMPRESA'];
            $_SESSION['CAJA']['TIPONUMERACION'] = $_REQUEST['Caja']['tipo_numeracion'];
            $_SESSION['CAJA']['TIPOCUENTA'] = $_REQUEST['Caja']['cuenta_tipo_id'];
            $_SESSION['CAJA']['CAJAID'] = $_REQUEST['Caja']['caja_id'];
            $_SESSION['CAJA']['CU'] = $_REQUEST['CU'];
            $_SESSION['CAJA']['CUENTA'] = $_REQUEST['Cuenta'];
        }

        $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaMetodoBuscar');
        $this->salida .= ThemeAbrirTabla('BUSCAR PACIENTE');
        $this->EncabezadoEmpresa($_SESSION['CAJA']['CAJAID']);
        $this->salida .= "                  <br><br>";
        $this->salida .= "                  <table width=\"60%\" align=\"center\" border=\"0\">";
        $this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "                       <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"tipodoc\" class=\"select\">";
        $tipo_id = $this->CallMetodoExterno('app', 'Triage', 'user', 'tipo_id_paciente', '');
        foreach ($tipo_id as $value => $titulo) {
            if ($value == $TipoId) {
                $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
            } else {
                $this->salida .=" <option value=\"$value\">$titulo</option>";
            }
        }
        $this->salida .= "              </select></td></tr>";
        $this->salida .= "                       <tr><td class=\"" . $this->SetStyle("Documento") . "\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"$PacienteId\"></td></tr>";
        $this->salida .= "                       <tr ><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"><br></td></form>";
        $this->salida.="</tr>";
        $this->salida.="</table><br>";
        //    $this->salida .= "<td colspan=\"2\">";
        $doc = $_REQUEST['Documento'];
        $dat = $this->TraerOrdenConsultaExterna($_REQUEST['Documento'], $_REQUEST['tipodoc']);
        if ($dat) {
            $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarTempDetalleExt', array('Documento' => $_REQUEST['Documento'], 'tipodoc' => $_REQUEST['tipodoc'], 'datos' => $dat, '    Plan' => $dat[0][plan_id], 'Nivel' => $dat[0][nivel]));
            $this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
            $this->salida.="<table  width=\"80%\" border=\"1\"  align=\"center\"  class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .= "            <tr class=\"modulo_table_title\">";
            $this->salida .= "                 <td align=\"center\">Id.Paciente</td>";
            $this->salida .= "                 <td align=\"center\">Nombre</td>";
            $this->salida .= "                 <td align=\"center\">Plan</td>";
            $this->salida .= "                 <td align=\"center\">Rango</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr align=\"center\">";
            $this->salida.="<td class=\"modulo_list_claro\">" . $dat[0][paciente_id] . "</td>";
            $this->salida.="<td class=\"modulo_list_claro\" >" . $dat[0][nombrep] . "</td>";
            $this->salida.="<td class=\"modulo_list_claro\" >" . $dat[0][rango] . "</td>";
            $this->salida.="<td class=\"modulo_list_claro\">" . $dat[0][plan_id] . "</td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
            IncludeLib("tarifario");
            /* $Var=$this->CallMetodoExterno('app','Facturacion','user','CoutaPaciente',array('PlanId'=>$dat[0][plan_id],'Nivel'=>$dat[0][rango]));
              $PorPaciente=$Var[porcentaje];
              $Maximo=$Var[valor_maximo];
              $Minimo=$Var[valor_minimo]; */
            for ($i = 0; $i < sizeof($dat); $i++) {
                if ($i % 2) {
                    $estilo = 'modulo_list_oscuro';
                } else {
                    $estilo = 'modulo_list_claro';
                }

                $orden = $dat[$i][orden_pago];
                $plan = $dat[$i][plan_id];
                //$nivel=$dat[$i][nivel]; //nivel sale de la tabla.
//LiquidarCargo($plan,$tarifario,$cargo,$porcentaje_copago=0,$copago_max=0,$copago_min=0,$cantidad=1,$precio=0)
//EN ESTE MOMENTO HAY UN CONFLICTO DE FUNCIONES, YA QUE LA FUNCION LIQUIDAR_CARGO() TIENE
//TODOS LOS DATOS NECESARIOS EXCEPTO LOS DE RANGO Y TIPO_ID_AFILIADO,PERO LA OTRA FUNCION
//LIQUIDARCARGOCUENTA() SI ,LOS TIENE ESTO HYA QUE PREGUNTARLO.....

                $rango = $dat[$i][rango]; //este fue el cambio nuevo cuando se quito el nivel.
                $tipo_afiliado = $dat[$i][tipo_id_afiliado]; //este fue el cambio nuevo cuando se quito el nivel.
//LiquidarCargoCuenta($cuenta ,$tarifario ,$cargo ,$cantidad=1 ,$descuento_manual_empresa=0 ,$descuento_manual_paciente=0 ,$aplicar_descuento_empresa=true ,$aplicar_descuento_paciente=true ,$precio=0 ,$Servicio='' ,$planId='' ,$tipo_afiliado_id='' ,$rango='' ,$semanas_cotizacion=0)

                $tarifario = $dat[$i][tarifario_id];
                $cargo = $dat[$i][cargo];
                $tiposerv = $dat[$i][tipo_servicio];
                $idpaciente = $dat[$i][paciente_id];
                $tipoidpaciente = $dat[$i][tipo_id_paciente];
                $fecha = $dat[$i][fecha];
                $cita = $dat[$i][agenda_cita_asignada_id];
                $nombrep = $dat[$i][nombrep];
                $desc = $dat[$i][descripcion];
                $tarifariodes = $dat[$i][tarifariodes];
                $nombre_profesional = $dat[$i][nombre];
                $consultorio = $dat[$i][consultorio];
                $servicio = $dat[$i][servicio];

                $Liq = LiquidarCargoCuenta('', $tarifario, $cargo, 1, 0, 0, true, true, 0, $servicio, $plan, $tipo_afiliado, $rango, '');
                //$Liq=LiquidarCargo($plan,$tarifario,$cargo,$PorPaciente,$Maximo,$Minimo,1,'');
                $Precio = $Liq[precio_plan];
                $ValorCargo = $Liq[valor_cargo];
                $GravamenEmp = $Liq[gravamen_empresa];
                $GravamenPac = $Liq[gravamen_paciente];
                $ValorPac = $Liq[copago];
                $ValorNo = $Liq[valor_no_cubierto];
                $ValorCub = $Liq[valor_cubierto];
                $ValEmpresa = $Liq[valor_empresa];
                $op = '';
                $this->salida.="<table width=\"80%\" border=\"1\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\" class=\"modulo_table_list\" >";
                $this->salida.="<tr>";
                $this->salida.="<td   width=\"20%\"  class=\"modulo_table_title\">Fecha :</td><td class=\"$estilo\" >$fecha</td><td class=\"modulo_table_title\">Tipo Servicio :</td><td class=\"$estilo\" >$desc</td><td class=\"$estilo\"  width=\"2%\">";
                $salida = "</tr>";
                $salida.="<tr>";
                $salida.="<td  class=\"modulo_table_title\">Codigo Cargo :</td><td class=\"$estilo\">$cargo</td><td class=\"modulo_table_title\">Liquidaci�n Cargo: </td><td class=\"$estilo\" colspan=\"2\">" . FormatoValor($Precio) . "</td>";
                $salida.="</tr>";
                $salida.="<tr>";
                $salida.="<td class=\"modulo_table_title\">Cargo :</td><td class=\"$estilo\" colspan=\"4\">$tarifariodes</td>";
                $salida.="</tr>";
                $salida.="</table>";
                /*
                  $this->salida.="</tr>";
                  $this->salida.="<tr>";
                  $this->salida.="<td class=\"modulo_table_list_title\">Codigo Cargo :</td><td class=\"$estilo\">$cargo</td><td class=\"modulo_table_list_title\">Liquidaci�n Cargo: </td><td class=\"$estilo\" colspan=\"2\">".FormatoValor($Precio)."</td>";
                  $this->salida.="</tr>";
                  $this->salida.="<tr>";
                  $this->salida.="<td class=\"modulo_table_list_title\">Cargo :</td><td class=\"$estilo\" colspan=\"4\">$tarifariodes</td>";
                  $this->salida.="</tr>";
                  $this->salida.="</table>"; */
                if (!empty($cita)) {
                    $salida.=$this->MostrarDatosMedico($consultorio, $nombre_profesional, $fecha, $estilo);
                } else {
                    $salida.="<br><br>";
                }
                $op = $i . ',';
                $ini = $i;
                for ($j = $i + 1; $j < sizeof($dat); $j++) {
                    //$op.=$j.',';
                    if ($dat[$j][agenda_cita_id] == ($dat[$i][agenda_cita_id] + 1)) {
                        $i++;
                        $op.=$i . ',';
                    }
                }
                if (($ini - 1) == $i) {
                    $fin = 0;
                } else {
                    $fin = $i;
                }
                $op.='/' . $fin;

                $this->salida.="<input type=\"checkbox\" value=\"$op\" name=\"checo[]\"></td>";
                $this->salida.="<input type=\"hidden\" value=\"$fin\" name=\"cantidadcheco$i\">";
                $this->salida.=$salida;
            }
            //$this->salida.="  <td><a href=".ModuloGetURL('app','CajaGeneral','user','CajaConceptos',array('Cajaid'=>$_REQUEST['Cajaid'],'nombre'=>urlencode($nombre),'arreglo'=>$dat[$i])).">VER</a></td>";
            //$this->salida.="<td></a></td>";
            //    $this->salida.="<td><a href=".ModuloGetURL('app','CajaGeneral','user','FormaCuentaConceptos',array('concepto'=>$group)).">Pago</a></td>";
            $this->salida.="<table width=\"80%\" border=\"0\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida.="<tr>";
            $this->salida.="<td align='center'><input type='submit' class=\"input-submit\"name='pago' value='PAGOS'></td></tr>";
            $this->salida.="</form>";
            $this->salida.="</table><br>";

            //$this->salida.="</td></tr>";
        } else {
            $this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
            if ($doc) {
                $this->salida.="<tr><td>&nbsp;&nbsp;</td></tr><br>";
                $this->salida.="<tr>";
                $this->salida.="<td colspan=\"2\"  align=\"center\" class=\"label_error\">El paciente '" . $doc . "' " . " no tiene ordenes de pagos pendientes.</td>";
                $this->salida .= "</tr>";
            } else {
                $this->salida.="<tr><td>&nbsp;&nbsp;</td></tr><br>";
                $this->salida.="<td colspan=\"2\"  align=\"center\" class=\"label_error\">La busqueda no arrojo resultados.</td>";
                $this->salida .= "</tr>";
            }
            $this->salida .= "                 </table>";
        }

        //  $this->salida .= "</td></tr>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function MostrarDatosMedico($consultorio, $nombre_profesional, $fecha, $estilo) {
        $salida.="<table border=\"1\" align=\"center\"  width=\"80%\"  class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        $salida.="<tr align=\"left\">";
        $salida.="<td width=\"20%\" class=\"modulo_table_title\">Fecha :</td>";
        $salida.="<td class=\"$estilo\">$fecha</td>";
        $salida.="<td width=\"22%\" class=\"modulo_table_title\">Consultorio:</td>";
        $salida.="<td width=\"36%\"class=\"$estilo\">$consultorio</td>";
        $salida.="</tr>";
        $salida.="<tr align=\"left\">";
        $salida.="<td class=\"modulo_table_title\">M�dico:</td>";
        $salida.="<td  colspan=\"3\" class=\"$estilo\">$nombre_profesional</td>";
        $salida.="</tr>";
        $salida.="</table><br><br>";
        return $salida;
    }

    /**
     * Metodo para la caja rapida
     *
     * @return boolean
     */
    function CajaRapida() {
        $request = $_REQUEST;
        if ($request['auto'])
            SessionSetVar("AutorizacionCE", $request['auto']);

        //vector que contiene las insasistidas para concatenarse al vector de liquidacion.
        unset($_SESSION['CAJA']['INASISTENCIAS']);
        unset($_SESSION['CAJA']['VALORCUOTAMODERADORA']);
        unset($_SESSION['CAJA']['VALORCOPAGO']);
        unset($_SESSION['CAJA']['TIPOCAMBIO']);

        IncludeLib('funciones_facturacion');
        IncludeLib('tarifario_cargos');

        //cambio para el boton de asignar cita es en sos------------------------------------------
        if (!empty($_SESSION['AsignacionCitas']['Informacion']['datos'])) {
            $_REQUEST['datos'] = $_SESSION['AsignacionCitas']['Informacion']['datos'];
            unset($_SESSION['AsignacionCitas']['Informacion']['datos']);
        }

        //vector que contiene el descuento ya sea por cuota moderadora o por copago.
        unset($_SESSION['VECTOR_DESC']);

        if (!EMPTY($_REQUEST['Caja'])) {
            //caso de jaime andres..
            //unset($_SESSION['CAJA']['TIPONUMERACION']['FACTURA']);
            $_SESSION['LABORATORIO']['EMPRESA_ID'] = $this->Empresa = $_SESSION['CAJA']['EMPRESA'] = $_REQUEST['Caja']['empresa_id'];
            $_SESSION['LABORATORIO']['CENTROUTILIDAD'] = $this->Cutilidad = $_SESSION['CAJA']['CENTROUTILIDAD'] = $_REQUEST['Caja']['centro_utilidad'];
            //arreglo de las numeraciones
            //para no da�ar esta parte esta variable de session tendr� el valor
            //del campo prefijo_fac_contado de la tabla cajas_rapidas $_SESSION['LABORATORIO']['TIPOFACTURA']==prefijo contado.
            //$_SESSION['CAJA']['TIPONUMERACION']['FACTURA']==prefijo contado
            $_SESSION['LABORATORIO']['TIPOFACTURA'] = $_REQUEST['Caja']['prefijo_fac_contado'];
            $_SESSION['CAJAX']['TIPONUMERACION']['FACTURA'] = $_REQUEST['Caja']['prefijo_fac_contado'];
            $_SESSION['LABORATORIO']['TIPOFACTURA_CRE'] = $_SESSION['CAJAX']['TIPONUMERACION']['FACTURA_CRE'] = $_REQUEST['Caja']['prefijo_fac_credito'];
            //final del arreglo de las numeraciones
            $_SESSION['LABORATORIO']['TIPOFACTURACION'] = $_SESSION['CAJA']['TIPONUMERACION']['TIPOFACTURACION'] = $_REQUEST['Caja']['tipo_factura_id'];
            $_SESSION['LABORATORIO']['TIPORECIBO'] = $_SESSION['CAJA']['TIPONUMERACION']['RECIBO'] = $_REQUEST['Caja']['tipo_num_recibos'];

            if (!$_SESSION['LABORATORIO']['DPTO']) {
                $_SESSION['LABORATORIO']['DPTO'] = $_REQUEST['Caja']['datoscaja']['depto'];
            }
            if (!empty($_REQUEST['depto'])) {
                $_SESSION['LABORATORIO']['DPTO'] = $_REQUEST['depto'];
            }
            if (!empty($_REQUEST['Caja']['datoscaja']['depto'])) {
                $_SESSION['LABORATORIO']['DPTO'] = $_REQUEST['Caja']['datoscaja']['depto'];
            }

            $_SESSION['CAJA']['TIPOCUENTA'] = 05;
            $_SESSION['LABORATORIO']['CAJAID'] = $_SESSION['CAJA']['CAJAID'] = $_REQUEST['Caja']['caja_id'];
            $_SESSION['CAJA']['CU'] = $_REQUEST['Caja']['centro_utilidad'];
            //jaime andres consulta externa
            if (!empty($_REQUEST['Caja']['datoscaja']['id'])) {
                $_REQUEST['id'] = $_REQUEST['Caja']['datoscaja']['id'];
                $_REQUEST['tipoid'] = $_REQUEST['Caja']['datoscaja']['tipoid'];
                $_REQUEST['nom'] = $_REQUEST['Caja']['datoscaja']['nom'];
                $_REQUEST['plan'] = $_REQUEST['Caja']['datoscaja']['plan'];
                $_REQUEST['afiliado'] = $_REQUEST['Caja']['datoscaja']['afiliado'];
                $_REQUEST['rango'] = $_REQUEST['Caja']['datoscaja']['rango'];
                $_REQUEST['sem'] = $_REQUEST['Caja']['datoscaja']['sem'];
                $_REQUEST['auto'] = $_REQUEST['Caja']['datoscaja']['auto'];
                $_REQUEST['servicio'] = $_REQUEST['Caja']['datoscaja']['servicio'];


                $_REQUEST['liq'] = $_SESSION['CAJA']['liq'];
                $_REQUEST['arr'] = $_SESSION['CAJA']['arr'];

                UNSET($_SESSION['CAJA']['liq']);
                UNSET($_SESSION['CAJA']['arr']);
            }
            if (!empty($_SESSION['CAJA']['op'])) {
                $_REQUEST['op'] = $_SESSION['CAJA']['op'];
            }

            if (!empty($_REQUEST['liq']) AND empty($_SESSION['CAJA']['liq']['caja_os'])) {
                //cambio dar
                $emp = '';
                $emp = BuscarEmpleadorOrden($_REQUEST['arr'][0][numero_orden_id]);
                $cargo_fact = LiquidarCargosCuentaVirtual($_REQUEST['liq'], array(), array(), array(), $_REQUEST['plan'], $_REQUEST['afiliado'], $_REQUEST['rango'], $_REQUEST['sem'], $_REQUEST['servicio'], $_REQUEST['tipoid'], $_REQUEST['id'], $emp['tipo_id_empleador'], $emp['empleador_id']);
                $_SESSION['CAJA']['AUX']['liq'] = $cargo_fact;
                $_REQUEST['arr'][0]['cantidad'] = $cargo_fact[cargos][0][cantidad];
                $_REQUEST['arr'][0]['valor_cargo'] = $cargo_fact[cargos][0][valor_cargo];
                $_REQUEST['arr'][0]['valor_no_cubierto'] = $cargo_fact[cargos][0][valor_no_cubierto];
                //fin cambio dar
                //$_SESSION['CAJA']['AUX']['liq']=$_REQUEST['liq'];
            }

            //CARGOS LIQUIDADOS EN OS ATENCION (CONTIENE INSUMOS Y MEDICAMENTOS ADICIONADOS)
            if (!empty($_SESSION['CAJA']['caja_os']['liq'])) {
                $_SESSION['CAJA']['AUX']['liq'] = $_SESSION['CAJA']['caja_os']['liq'];
            }

            //CASO DE LOS INSUMOS Y MEDICAMENTO ADICIONADOS EN OS ATENCION
            if (!empty($_SESSION['CAJA']['caja_os']['imd_liq'])) {
                $_SESSION['CAJA']['AUX']['imd_liq'] = $_SESSION['CAJA']['caja_os']['imd_liq'];
            }

            unset($_SESSION['CAJA']['liq']['caja_os']);
            unset($_SESSION['CAJA']['liq']['imd_liq']);

            if (!empty($_SESSION['CAJA']['vector'])) {
                $_REQUEST['vector'] = $_SESSION['CAJA']['vector'];
            }
            if (!empty($_REQUEST['datos'])) {
                $_SESSION['CAJA']['AUX']['datos'] = $_REQUEST['datos'];
            }
            if (!empty($_SESSION['CAJA']['datos'])) {
                $_SESSION['CAJA']['AUX']['datos'] = $_SESSION['CAJA']['datos'];
            }
            if (!empty($_SESSION['CAJA']['arr'])) {
                $_REQUEST['arr'] = $_SESSION['CAJA']['arr'];
            }


            //********************************************************
            //VALIDAR QUE LA CAJA NO ESTE UTILIZADA POR OTRO USUARIO
            //********************************************************
            $existenciarecibosincuadre = $this->ReciboSinCuadre($_SESSION['LABORATORIO']['EMPRESA_ID'], $_SESSION['LABORATORIO']['CENTROUTILIDAD'], $_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['cuenta_tipo_id'], 'dept');

            if ($existenciarecibosincuadre) {
                //$this->frmError["MensajeError"]="EXISTEN RECIBOS DE OTROS USUARIOS SIN CUADRAR.";
                if (!empty($_SESSION['LABORATORIO']['CAJARAPIDA'])) {
                    $cont = $_SESSION['LABORATORIO']['RETORNO']['contenedor'];
                    $mod = $_SESSION['LABORATORIO']['RETORNO']['modulo'];
                    $tipo = $_SESSION['LABORATORIO']['RETORNO']['tipo'];
                    $metodo = $_SESSION['LABORATORIO']['RETORNO']['metodo'];
                } elseif (!empty($_SESSION['CONSULTAEXT']['RETORNO'])) {
                    $cont = $_SESSION['CONSULTAEXT']['RETORNO']['contenedor'];
                    $mod = $_SESSION['CONSULTAEXT']['RETORNO']['modulo'];
                    $tipo = $_SESSION['CONSULTAEXT']['RETORNO']['tipo'];
                    $metodo = $_SESSION['CONSULTAEXT']['RETORNO']['metodo'];
                    $arreglo1 = array('Documento' => $_SESSION['CAJA']['AUX']['paciente_id'], 'TipoDocumento' => $_SESSION['CAJA']['AUX']['tipo_id_paciente']);
                } else {
                    $cont = 'app';
                    $mod = 'Os_Atencion';
                    $tipo = 'user';
                    $metodo = 'FormaMetodoBuscar';
                }
                $accion = ModuloGetURL($cont, $mod, $tipo, $metodo);



                if (!empty($_SESSION['CAJA']['FACTURA']['EMPRESA'])) {
                    $a = 1;
                } else {
                    $a = 0;
                }

                if (empty($boton)) {
                    $boton = '';
                    $msg = 'EXISTEN RECIBOS DE OTROS USUARIOS SIN CUADRAR.';
                } else {
                    $boton = 'factura';
                    $msg = 'FACTURA GENERADA SATISFACTORIAMENTE';
                }

                //limpiar session del retorno
                unset($_SESSION['LABORATORIO']['CAJARAPIDA']);
                unset($_SESSION['CONSULTAEXT']['RETORNO']);
                //finlimpiar session del retorno
                $arreglo = array('cuenta' => $_SESSION['CAJA']['AUX']['CUENTA'], 'switche_emp' => $a);
                $this->FormaMensaje($msg, 'CONFIRMACION', $accion, 'Volver', $boton, $arreglo, $arreglo1);
                return true;
                /* 		$this->uno=1;
                  $this->FrmRecibosCajaSinCuadreHoy($existenciarecibosincuadre,1,$_REQUEST['Caja']['caja_id'],'','','','05','');
                  return true; */
            }
            //********************************************************
            //FIN VALIDAR QUE LA CAJA NO ESTE UTILIZADA POR OTRO USUARIO
            //********************************************************
        } else {
            //caso de darling liliana..
            $this->Empresa = $_SESSION['LABORATORIO']['EMPRESA_ID'];
            $this->Cutilidad = $_SESSION['LABORATORIO']['CENTROUTILIDAD'];
            $_SESSION['CAJA']['EMPRESA'] = $this->Empresa;
            $_SESSION['CAJA']['CENTROUTILIDAD'] = $this->Cutilidad;
            $this->Cutilidad = $_SESSION['CAJA']['CENTROUTILIDAD'];
            $this->Empresa = $_SESSION['CAJA']['EMPRESA'];



            //arreglo de las numeraciones
            $_SESSION['CAJAX']['TIPONUMERACION']['FACTURA'] = $_SESSION['LABORATORIO']['TIPOFACTURA'];
            $_SESSION['CAJAX']['TIPONUMERACION']['FACTURA_CRE'] = $_SESSION['LABORATORIO']['TIPOFACTURA_CRE'];
            //fin arreglo de las numeraciones
            $_SESSION['CAJA']['TIPONUMERACION']['RECIBO'] = $_SESSION['LABORATORIO']['TIPORECIBO'];
            $_SESSION['CAJA']['TIPOCUENTA'] = 05;
            $_SESSION['CAJA']['CAJAID'] = $_SESSION['LABORATORIO']['CAJAID'];
            $_SESSION['CAJA']['CU'] = $_REQUEST['CU'];
            //********************************************************
            //VALIDAR QUE LA CAJA NO ESTE UTILIZADA POR OTRO USUARIO
            //********************************************************
            //$emp,$cu,$Caja,$tipocuenta,$dp
            $existenciarecibosincuadre = $this->ReciboSinCuadre($this->Empresa, $this->Cutilidad, $_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['cuenta_tipo_id'], 'dept');
            if ($existenciarecibosincuadre) {
                //$this->frmError["MensajeError"]="EXISTEN RECIBOS DE OTROS USUARIOS SIN CUADRAR.";
                if (!empty($_SESSION['LABORATORIO']['CAJARAPIDA'])) {
                    $cont = $_SESSION['LABORATORIO']['RETORNO']['contenedor'];
                    $mod = $_SESSION['LABORATORIO']['RETORNO']['modulo'];
                    $tipo = $_SESSION['LABORATORIO']['RETORNO']['tipo'];
                    $metodo = $_SESSION['LABORATORIO']['RETORNO']['metodo'];
                } elseif (!empty($_SESSION['CONSULTAEXT']['RETORNO'])) {
                    $cont = $_SESSION['CONSULTAEXT']['RETORNO']['contenedor'];
                    $mod = $_SESSION['CONSULTAEXT']['RETORNO']['modulo'];
                    $tipo = $_SESSION['CONSULTAEXT']['RETORNO']['tipo'];
                    $metodo = $_SESSION['CONSULTAEXT']['RETORNO']['metodo'];
                    $arreglo1 = array('Documento' => $_SESSION['CAJA']['AUX']['paciente_id'], 'TipoDocumento' => $_SESSION['CAJA']['AUX']['tipo_id_paciente']);
                } else {
                    $cont = 'app';
                    $mod = 'Os_Atencion';
                    $tipo = 'user';
                    $metodo = 'LLamarOrdenar';
                }
                $accion = ModuloGetURL($cont, $mod, $tipo, $metodo);
                if (!empty($_SESSION['CAJA']['FACTURA']['EMPRESA'])) {
                    $a = 1;
                } else {
                    $a = 0;
                }

                if (empty($boton)) {
                    $boton = '';
                    $msg = 'EXISTEN RECIBOS DE OTROS USUARIOS SIN CUADRAR.';
                } else {
                    $boton = 'factura';
                    $msg = 'FACTURA GENERADA SATISFACTORIAMENTE';
                }

                $arreglo = array('cuenta' => $_SESSION['CAJA']['AUX']['CUENTA'], 'switche_emp' => $a);

                $this->FormaMensaje($msg, 'CONFIRMACION', $accion, 'Volver', $boton, $arreglo, $arreglo1);
                return true;
                /* 		$this->uno=1;
                  $this->FrmRecibosCajaSinCuadreHoy($existenciarecibosincuadre,1,$_REQUEST['Caja']['caja_id'],'','','','05','');
                  return true; */
            }
            //********************************************************
            //FIN VALIDAR QUE LA CAJA NO ESTE UTILIZADA POR OTRO USUARIO
            //********************************************************
        }
        $_SESSION['CAJA']['AUX']['paciente_id'] = $_REQUEST['id'];
        $_SESSION['CAJA']['AUX']['tipo_id_paciente'] = $_REQUEST['tipoid'];
        $_SESSION['CAJA']['AUX']['nom'] = $_REQUEST['nom'];
        $_SESSION['CAJA']['AUX']['plan_id'] = $_REQUEST['plan'];
        $_SESSION['CAJA']['AUX']['op'] = $_REQUEST['op'];
        //$_SESSION['CAJA']['AUX']['liq']=$_REQUEST['liq'];
        // $_SESSION['CAJA']['AUX']['vector']=$_REQUEST['vector'];
        $_SESSION['CAJA']['AUX']['afiliado'] = $_REQUEST['afiliado'];
        $_SESSION['CAJA']['AUX']['rango'] = $_REQUEST['rango'];
        $_SESSION['CAJA']['AUX']['sem'] = $_REQUEST['sem'];
        $_SESSION['CAJA']['AUX']['auto'] = $_REQUEST['auto'];
        $_SESSION['CAJA']['AUX']['serv'] = $_REQUEST['servicio'];
        // $_SESSION['CAJA']['AUX']['datos']=$_REQUEST['datos'];

        if (empty($_SESSION['CAJA']['ARRAY_PAGO'])) {
            $_SESSION['CAJA']['ARRAY_PAGO'] = $_SESSION['CAJA']['ARRAY_PAGO_TMP'] = $_REQUEST['arr'];
        }
        //vector q  contiene el listado de
        //las citas incumplidas....
        unset($_SESSION['ARREGLO_CITAS_INCUMPLIDAS']);
        //esta variable de session me identifica que el vector de citas
        //inasistidas se va a crear por primera vez....
        $_SESSION['SW_ARR_CITA'] = 0;
        //Este array_descuento es para mostrar los descuentos del paciente..
        unset($_SESSION['CAJA']['ARRAY_DESCUENTO']);
        //variable que contiene las llaves de agendas_citas,para despues
        //de que se facture sean insertadas
        unset($_SESSION['ARR_UPDATE_AGENDA']);

        //variable que determina en q caja se esta trabajando
        //1 si es en caja general
        //2 si es en caja rapida
        $_SESSION['CAJA']['AUX']['RUTA_CAJA'] = 2;
        if (!$this->FormaOrdenesServicio($_SESSION['CAJA']['AUX']['tipo_id_paciente'], $_SESSION['CAJA']['AUX']['paciente_id'], $_SESSION['CAJA']['AUX']['plan_id'])) {
            return false;
        }
        return true;
    }

    function FormaOrdenesServicio($TipoId, $PacienteId, $plan) {
        //IncludeLib("tarifario");
        $this->salida .= ThemeAbrirTabla('CAJA ORDENES DE SERVICIO');

        //--GVILLOTA [RQ 5364]----------------------------------------------------------

        $tipo_id_paciente = $_SESSION['CAJA']['AUX']['tipo_id_paciente'];
        $paciente_id = $_SESSION['CAJA']['AUX']['paciente_id'];

        $v_paciente = $this->ObtenerDatosPaciente($tipo_id_paciente, $paciente_id);
        //echo "vp-1:".$v_paciente[0]."<br>";
        if ($v_paciente[0] == "sisoat") {
            $saldo = $this->ObtenerSaldoAseguradoraPaciente($tipo_id_paciente, $paciente_id);
            $msj_saldo_asegura = "EL SALDO DE ASEGURADORA DEL PACIENTE ES DE $ " . number_format($saldo, 2);

            $this->salida .= "  <table width=\"50%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"0\" cellpadding=\"0\">\n";
            $this->salida .= "      <tr>\n";
            $this->salida .= "          <td id=\"td_msj_saldo_asegura\" class='label_error' colspan='3' align='center'>\n";
            $this->salida .= "              $msj_saldo_asegura";
            $this->salida .= "          </td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "  </table>\n";
            $this->salida .= "  <br>\n";
        }
        //------------------------------------------------------------------------------

        $this->EncabezadoOrdenServicio($TipoId, $PacienteId, $plan);
        /* parte de citas inasistida */
        $arr = array();
        if (!empty($_SESSION['ARREGLO_CITAS_INCUMPLIDAS'])) {
            $arr = $_SESSION['ARREGLO_CITAS_INCUMPLIDAS'];
        } elseif (empty($_SESSION['ARREGLO_CITAS_INCUMPLIDAS']) AND $_SESSION['SW_ARR_CITA'] == 0) {
            $arr = $this->CitasIncumplidasPaciente($TipoId, $PacienteId, $plan);
            $_SESSION['ARREGLO_CITAS_INCUMPLIDAS'] = $arr;
        } elseif (empty($_SESSION['ARREGLO_CITAS_INCUMPLIDAS']) AND $_SESSION['SW_ARR_CITA'] == 1) {
            $arr = '';
        }
        if (!empty($arr)) {
            $this->salida .= "           <form name=\"formascript\"  action=" . ModuloGetURL('app', 'CajaGeneral', 'user', 'liquidarCargo') . " method=\"post\">";
            $this->salida .= "<SCRIPT>";
            $this->salida .= "function chequeoTotal(frm,x){";
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
            $this->salida .= "       <table width=\"90%\"  align=\"center\">";
            $this->salida .="" . $this->SetStyle("MensajeError") . "";
            $this->salida .= "       </table>";
            $this->salida .= "<table  class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\">";
            $this->salida.="<tr><td colspan=\"4\" class=\"modulo_table_title\">LISTADO DE CITAS INASISTIDAS";
            $this->salida.="</td></tr>";

            //$this->salida.="<tr><td><table  align=\"center\" border=\"0\"  width=\"100%\">";
            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";

            $this->salida.="  <td width=\"10%\">FECHA CITA</td>";
            $this->salida.="  <td width=\"35%\">NOMBRE</td>";
            $this->salida.="  <td width=\"35%\">CONSULTA</td>";
            $this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
            $this->salida.="</tr>";
            $i = 0;
            foreach ($arr as $k => $b) {
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida.="<tr class='$estilo' align='center'>";
                $this->salida.="  <td >" . $b[fecha] . "</td>";
                $this->salida.="  <td >" . $b[nombre_tercero] . "</td>";
                $agenda_id = $b[agenda_cita_asignada_id];
                $numero_orden = $b[numero_orden_id];
                $this->salida.="  <td >" . $b[descripcion] . "</td>";
                $this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=op[$i] value=$agenda_id,$numero_orden></td>";
                //	$subT=$subT+$_SESSION['CAJA']['AUX']['liq'][$i][total_paciente];
                $this->salida.="</tr>";
                $i++;
            }

            $this->salida .= " <td colspan=\"4\"  class='$estilo' align=\"right\"><label class='label_mark'>USTED TIENE " . sizeof($arr) . "&nbsp;CITAS INASISTIDAS</label>&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Cumplir\"></form></td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
        }
        $this->salida .= "<br><table border=\"1\" width=\"90%\" align=\"center\">";
        $this->salida.="<tr><td class=\"modulo_table_title\">DETALLES ORDENES DE SERVICIO";
        $this->salida.="</td></tr>";

        $arr = array();
        $cubierto = 0;
        $nocubierto = 0;
        $arr = $_SESSION['CAJA']['ARRAY_PAGO']; //este arreglo se ira al otro lado para el pago.

        if (!empty($arr)) {

            $this->IraFormaOrden(); //pedimos la asignacion de la cuenta virtual.
            $a = 0;
            for ($i = 0; $i < sizeof($arr); $i++) {
                //esta funcion trae los datos del medico,la fecha,solo es en caso de
                //q se haya asignado una cita medica....
                $EXTERNA = $this->BuscarInformacionCita($arr[$i][numero_orden_id]);
                if ($a == $i) {
                    if ($EXTERNA > 0) { //si tiene alguna cita asignada entrara a este if
                        $this->salida.="<tr><td><table  align=\"center\" border=\"0\"  width=\"100%\">";
                        $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                        $this->salida.="  <td width=\"10%\">CITA</td>";
                        $this->salida.="  <td width=\"20%\">MEDICO</td>";
                        $this->salida.="  <td width=\"20%\">ASIGNO</td>";
                    } else {
                        $this->salida.="<tr><td><table  align=\"center\" border=\"0\"  width=\"100%\">";
                        $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                        $this->salida.="  <td width=\"10%\">CITA</td>";
                        $this->salida.="  <td width=\"20%\">MEDICO</td>";
                        $this->salida.="  <td width=\"20%\">ASIGNO</td>";
                    }

                    $this->salida.="  <td width=\"5%\">ORDEN</td>";
                    $this->salida.="  <td width=\"5%\">CARGO</td>";
                    $this->salida.="  <td width=\"20%\">DESCRIPCION</td>";
                    $this->salida.="  <td width=\"5%\">CANT</td>";
                    $this->salida.="  <td width=\"5%\">V.CARGO</td>";
                    $this->salida.="  <td width=\"5%\">V.NO CUBIERTO</td>";

                    $this->salida.="</tr>";
                }
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida.="<tr class='$estilo' align='center'>";
                if ($EXTERNA > 0) {//si tiene alguna cita asignada entrara a este if
                    $this->salida.="  <td>" . $EXTERNA[fecha] . "</td>";
                    $this->salida.="  <td>" . $EXTERNA[nombre_tercero] . "</td>";
                    $this->salida.="  <td>" . $EXTERNA[nombre] . "</td>";
                    UNSET($_SESSION['CAJA']['PROFESIONAL']);
                    $_SESSION['CAJA']['PROFESIONAL']['tercero_id'] = $EXTERNA[tercero_id];
                    $_SESSION['CAJA']['PROFESIONAL']['tipo_id_tercero'] = $EXTERNA[tipo_id_tercero];
                } else {
                    $this->salida.="  <td><label class='label_mark'>No es cita</label></td>";
                    $this->salida.="  <td><label class='label_mark'>No es cita</label></td>";
                    $this->salida.="  <td><label class='label_mark'>No es cita</label></td>";
                }
                $this->salida.="  <td >" . $arr[$i][numero_orden_id] . "</td>";
                $this->salida.="  <td >" . $arr[$i][cargo] . "</td>";
                $desc = urldecode($arr[$i][descripcion]);
                $desc = substr($desc, 0, 35);
                $desc.='..';
                $cubierto+=$arr[$i][valor_cubierto];
                $nocubierto+=$arr[$i][valor_no_cubierto];
                $this->salida.="  <td >" . $desc . "</td>";
                $this->salida.="  <td >" . $arr[$i][cantidad] . "</td>";
                $this->salida.="  <td >" . FormatoValor($arr[$i][valor_cargo]) . "</td>";
                $this->salida.="  <td >" . FormatoValor($arr[$i][valor_no_cubierto]) . "</td>";
                $subT = $_SESSION['CAJA']['AUX']['liq'][valor_total_paciente];
                //	$variable=0;
                //	$variable=$_SESSION['CAJA']['AUX']['liq'][$i][cuota_moderadora]+$_SESSION['CAJA']['AUX']['liq'][$i][copago]+$_SESSION['CAJA']['AUX']['liq'][$i][valor_no_cubierto];
                //	$subT=$subT+$_SESSION['CAJA']['AUX']['liq'][$i][cuota_moderadora]+$_SESSION['CAJA']['AUX']['liq'][$i][copago]+$_SESSION['CAJA']['AUX']['liq'][$i][valor_no_cubierto];
                // $this->salida.="  <td >".FormatoValor($variable)."</td>";
                //	$this->salida.="  <td >".FormatoValor($_SESSION['CAJA']['AUX']['liq'][$i][valor_empresa])."</td>";
                $this->salida.="</tr>";
            }

            /* if($_SESSION['CAJA']['ARRAY_DESCUENTO'])
              {
              $this->salida.="<tr class='$estilo'><td  colspan='3'>&nbsp;</td><td colspan='2' align='center'><label class='label_mark'>DESCUENTOS ($)</label>&nbsp;&nbsp;</td><td align='center'><label class='label_mark'>".FormatoValor($_SESSION['CAJA']['ARRAY_DESCUENTO']['valor_cargo'])."</label></td><td>&nbsp;</td></tr>";
              } */
            $this->salida.="</td></tr>";
            $this->salida.="</table>";
            //*************************************
            //TABLA PARA LOS INSUMOS Y MEDICAMENTOS
            //*************************************
            //FALTA UNSET A $_SESSION['CAJA']['IMD_OS']
            /* 								$arr=$_SESSION['CAJA']['IMD_OS'];//este arreglo se ira al otro lado para el pago.
              if(!empty($arr))
              {
              //$this->IraFormaOrden();//pedimos la asignacion de la cuenta virtual.
              $a=0;
              for($i=0;$i<sizeof($arr);$i++)
              {
              //esta funcion trae los datos del medico,la fecha,solo es en caso de
              //q se haya asignado una cita medica....
              $EXTERNA=$this->BuscarInformacionCita($arr[$i][numero_orden_id]);
              if($a==$i)
              {
              $this->salida.="<tr><td><table  align=\"center\" border=\"0\"  width=\"100%\">";
              $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
              $this->salida.="  <td width=\"5%\">CITA</td>";
              $this->salida.="  <td width=\"8%\">INSUMO/MED</td>";
              $this->salida.="  <td width=\"58%\">DESCRIPCION</td>";
              $this->salida.="  <td width=\"5%\">CANT</td>";
              $this->salida.="  <td width=\"5%\">V.CARGO</td>";
              $this->salida.="  <td width=\"5%\">V.NO CUBIERTO</td>";

              $this->salida.="</tr>";
              }
              if( $i % 2){ $estilo='modulo_list_claro';}
              else {$estilo='modulo_list_oscuro';}
              $this->salida.="<tr class='$estilo' align='center'>";
              $this->salida.="  <td><label class='label_mark'>No es cita</label></td>";
              $this->salida.="  <td >".$arr[$i][codigo_producto]."</td>";
              $desc=urldecode($arr[$i][descripcion]);
              $desc=substr($desc,0,70);
              $cubierto+=$arr[$i][valor_cubierto];
              $nocubierto+=$arr[$i][valor_nocubierto];
              $this->salida.="  <td align=\"left\">".$desc."</td>";
              $this->salida.="  <td >".$arr[$i][cantidad]."</td>";
              $this->salida.="  <td >".FormatoValor($arr[$i][valor_cargo])."</td>";
              $this->salida.="  <td >".FormatoValor($arr[$i][valor_nocubierto])."</td>";
              $this->salida.="</tr>";
              }
              $this->salida.="</td></tr>";
              $this->salida.="</table>";
              } */
            //*****************************************
            //FIN TABLA PARA LOS INSUMOS Y MEDICAMENTOS
            //*****************************************
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"50%\">";
            $this->salida.="<tr>";
            $this->salida.="<td colspan='8'>&nbsp;</td>";
            $this->salida.="</tr>";
            $valorcubierto = $valornocubierto = $valorempresa = 0;
            /* for($i=0; $i<sizeof($_SESSION['CAJA']['AUX']['liq'][cargos]);$i++)
              {
              $valorcubierto+=$_SESSION['CAJA']['AUX']['liq'][cargos][$i][valor_cubierto];
              $valornocubierto+=$_SESSION['CAJA']['AUX']['liq'][cargos][$i][valor_no_cubierto];
              $valorempresa+=$_SESSION['CAJA']['AUX']['liq'][cargos][$i][valor_cubierto];
              }
              if(!empty($_SESSION['CAJA']['AUX']['imd_liq']))
              {
              for($i=0; $i<sizeof($_SESSION['CAJA']['AUX']['imd_liq']);$i++)
              {
              $valorcubierto+=$_SESSION['CAJA']['AUX']['imd_liq'][$i][valor_cubierto];
              $valornocubierto+=$_SESSION['CAJA']['AUX']['imd_liq'][$i][valor_nocubierto];
              $valorempresa+=$_SESSION['CAJA']['AUX']['imd_liq'][$i][valor_cubierto];
              }
              } */
            $nombres = $this->BuscarNombreCop($_SESSION['CAJA']['AUX']['plan_id']);
            /* 															$this->salida.="<tr class=\"modulo_table_list_title\">";
              $this->salida.="  <td align='left' >Valor No cubierto:&nbsp;</td>";
              $this->salida.="<td align='right' >".FormatoValor($valornocubierto)."</td>";
              $this->salida.="  <td class=\"modulo_list_oscuro\" width='10%' colspan='1'>&nbsp;</td>";
              $this->salida.="</tr>";
              $this->salida.="<tr class=\"modulo_table_list_title\">";
              $this->salida.="  <td align='left' >Valor cubierto:&nbsp;</td>";
              $this->salida.="<td align='right' >".FormatoValor($valorcubierto)."</td>";
              $this->salida.="  <td class=\"modulo_list_oscuro\" width='10%' colspan='1'>&nbsp;</td>";
              $this->salida.="</tr>"; */

            if ($_SESSION['CAJA']['AUX']['liq'][valor_no_cubierto] > 0) {
                $this->salida.="<tr class=\"modulo_table_list_title\">";
                $this->salida.="  <td align='left' >Valor No Cubierto:&nbsp;</td>";
                $this->salida.="<td align='right' >" . FormatoValor($_SESSION['CAJA']['AUX']['liq'][valor_no_cubierto]) . "</td>";
                $this->salida.="</tr>";
            }

            if ($_SESSION['CAJA']['AUX']['liq'][valor_cubierto] > 0) {
                $this->salida.="<tr class=\"modulo_table_list_title\">";
                $this->salida.="  <td align='left' >Valor Cubierto:&nbsp;</td>";
                $this->salida.="<td align='right' >" . FormatoValor($_SESSION['CAJA']['AUX']['liq'][valor_cubierto]) . "</td>";
                $this->salida.="</tr>";
            }
//														if($_SESSION['CAJA']['AUX']['liq'][valor_cuota_moderadora]>0)
//														{
            if ($nombres[tipo_liquidacion_cargo] != 3) {
                $url = ModuloGetURL('app', 'CajaGeneral', 'user', 'ModificarDescuento', array('sw_pago' => 1, 'valor_pago' => $_SESSION['CAJA']['AUX']['liq'][valor_cuota_moderadora]));
                $this->salida.="<tr class=\"modulo_table_list_title\">";
                $this->salida.="  <td align='left' >" . $nombres[nombre_cuota_moderadora] . ":&nbsp;</td>";
                if (!empty($_SESSION['CAJA']['AUX']['liq'][descuentos][cuota_moderadora][valormodi])) {
                    $this->salida.="<td align='right' >" . FormatoValor($_SESSION['CAJA']['AUX']['liq'][descuentos][cuota_moderadora][valormodi]) . "</td>";
                    //$_SESSION['CAJA']['VALORCUOTAMODERADORA']=$_SESSION['CAJA']['AUX']['liq'][valor_cuota_moderadora];
                } else {
                    $this->salida.="<td align='right'>" . FormatoValor($_SESSION['CAJA']['AUX']['liq'][valor_cuota_moderadora]) . "</td>";
                    //$_SESSION['CAJA']['VALORCUOTAMODERADORA']=$_SESSION['CAJA']['AUX']['liq'][valor_cuota_moderadora];
                }
                $this->salida.="  <td class=\"modulo_list_oscuro\" width='10%' colspan='1'><a href='$url'>[Modificar]</a></td>";
                $this->salida.="</tr>";
            }
//														}

            /* 														if($_SESSION['CAJA']['AUX']['liq'][descuentos][cuota_moderadora][valor]>0)
              {
              $this->salida.="<tr align='left'>";
              $this->salida.="  <td class=\"modulo_table_list_title\" colspan='1'>Descuento Cuota Moderadora  </td>";
              $this->salida.="<td align='right'>".FormatoValor($_SESSION['CAJA']['AUX']['liq'][descuentos][cuota_moderadora][valor])."</td>	";
              //$this->salida.="  <td class=\"modulo_table_list_title\" colspan='1'>".FormatoValor($_SESSION['CAJA']['AUX']['liq'][descuentos][cuota_moderadora][valor])."</td>";
              $this->salida.="</tr>";
              } */

//														if($_SESSION['CAJA']['AUX']['liq'][valor_cuota_paciente]>0)
//														{
            $url = ModuloGetURL('app', 'CajaGeneral', 'user', 'ModificarDescuento', array('sw_pago' => 2, 'valor_pago' => $_SESSION['CAJA']['AUX']['liq'][valor_cuota_paciente]));
            $this->salida.="<tr class=\"modulo_table_list_title\">";
            $this->salida.="  <td align='left' >" . $nombres[nombre_copago] . ":&nbsp;</td>";
            if (!empty($_SESSION['CAJA']['AUX']['liq'][descuentos][cuota_paciente][valormodi])) {
                $this->salida.="<td align='right' >" . FormatoValor($_SESSION['CAJA']['AUX']['liq'][descuentos][cuota_paciente][valormodi]) . "</td>";
                //$_SESSION['CAJA']['VALORCOPAGO']=$_SESSION['CAJA']['AUX']['liq'][descuentos][cuota_paciente][valormodi];
            } else {
                $this->salida.="<td align='right' >" . FormatoValor($_SESSION['CAJA']['AUX']['liq'][valor_cuota_paciente]) . "</td>";
                //$_SESSION['CAJA']['VALORCOPAGO']=$_SESSION['CAJA']['AUX']['liq'][descuentos][cuota_paciente][valormodi];
            }
            $this->salida.="  <td class=\"modulo_list_oscuro\" width='10%' colspan='1'><a href='$url'>[Modificar]</a></td>";
            $this->salida.="</tr>";
//														}
// 														if($_SESSION['CAJA']['AUX']['liq'][descuentos][cuota_paciente][valor]>0)
// 														{
// 																$this->salida.="<tr align='left'>";
// 																$this->salida.="  <td class=\"modulo_table_list_title\" colspan='1'>Descuento Cuota Paciente </td>"; 
// 																$this->salida.="<td align='right' >".FormatoValor($_SESSION['CAJA']['AUX']['liq'][descuentos][cuota_paciente][valor])."</td>";
// 		//													$this->salida.="  <td class=\"modulo_table_list_title\" colspan='2'>".FormatoValor($_SESSION['CAJA']['AUX']['liq'][descuentos][cuota_paciente][valor])."</td>";
// 		//													$this->salida.="  <td class=\"modulo_list_oscuro\" colspan='1'>&nbsp;</td>";
// 																$this->salida.="</tr>";
// 														}

            if ($_SESSION['CAJA']['AUX']['liq'][valor_gravamen_paciente] > 0) {
                $this->salida.="<tr class=\"modulo_table_list_title\">";
                $this->salida.="  <td align='left' >IVA Paciente:&nbsp;</td>";
                $this->salida.="<td align='right' >" . FormatoValor($_SESSION['CAJA']['AUX']['liq'][valor_gravamen_paciente]) . "</td>";
                $this->salida.="</tr>";
            }

            /* 					if($_SESSION['CAJA']['ARRAY_DESCUENTO'])
              {
              $this->salida.="<tr class=\"modulo_table_list_title\">";
              $this->salida.="  <td align='left' >Descuento:&nbsp;</td>";
              $this->salida.="<td align='right' >".FormatoValor($_SESSION['CAJA']['ARRAY_DESCUENTO']['valor_cargo'])."</td>";
              $this->salida.="</tr>";
              } */

            if ($_SESSION['CAJA']['AUX']['liq'][valor_descuento_paciente] > 0) {
                $this->salida.="<tr class=\"modulo_table_list_title\">";
                $this->salida.="  <td align='left' >Descuento:&nbsp;</td>";
                $this->salida.="<td align='right' >" . FormatoValor($_SESSION['CAJA']['AUX']['liq'][valor_descuento_paciente] + $_SESSION['CAJA']['AUX']['liq'][valor_descuento_empresa]) . "</td>";
                $this->salida.="</tr>";
            }

            // [duvan] para aplicar descuento por empresa.
            //sw_pago =4 este switche nos ayuda para determinar q es total_empresa
            if ($_SESSION['CAJA']['AUX']['liq'][valor_total_empresa] > 0) {
                $url = ModuloGetURL('app', 'CajaGeneral', 'user', 'RealizarDescuento', array('sw_pago' => 4, 'valor_pago' => $_SESSION['CAJA']['AUX']['liq'][valor_total_empresa]));
                $this->salida.="<tr class=\"modulo_table_list_title\">";
                $this->salida.="  <td align='left' >Valor Total Empresa:&nbsp;</td>";
                $this->salida.="<td align='right' >" . FormatoValor($_SESSION['CAJA']['AUX']['liq'][valor_total_empresa]) . "</td>";
                //modificacion lorena pues no debe realizarse el descuento de la empresa desde aqui
                $this->salida.="  <td class=\"modulo_list_oscuro\" width='10%' colspan='1'>&nbsp;</td>";
                //$this->salida.="  <td class=\"modulo_list_oscuro\" width='10%' colspan='1'><a href='$url'>[Descuento]</a></td>";
                //fin lorena
                $this->salida.="</tr>";
            }
            // [duvan] para aplicar descuento por cliente o paciente
            //sw_pago=3 este switche nos ayuda para determinar q es total_paciente	
            if ($_SESSION['CAJA']['AUX']['liq'][valor_total_paciente] > 0) {
                $url = ModuloGetURL('app', 'CajaGeneral', 'user', 'RealizarDescuento', array('sw_pago' => 3, 'valor_pago' => $_SESSION['CAJA']['AUX']['liq'][valor_total_paciente]));
                $this->salida.="<tr class=\"modulo_table_list_title\">";
                $this->salida.="  <td align='left' >Valor Total Paciente:&nbsp;</td>";
                $this->salida.="<td align='right' >" . FormatoValor($_SESSION['CAJA']['AUX']['liq'][valor_total_paciente]) . "</td>";
                //modificacion lorena pues esta pendiente el descuento del paciente
                $this->salida.="  <td class=\"modulo_list_oscuro\" width='10%' colspan='1'>&nbsp;</td>";
                //$this->salida.="  <td class=\"modulo_list_oscuro\" width='10%' colspan='1'><a href='$url'>[Descuento]</a></td>";
                //fin lorena
                $this->salida.="</tr>";
            }
// 														$this->salida.="<tr class=\"modulo_table_list_title\">";
// 														if($_SESSION['CAJA']['ARRAY_DESCUENTO'])
// 														{
// 															$subT=$subT-$_SESSION['CAJA']['ARRAY_DESCUENTO']['valor_cargo'];
// 														}
// 														$this->salida.="<td align='left' colspan='1'>TOTAL:&nbsp;</td>";
// 														$this->salida.="<td align='right' colspan='1'>".FormatoValor($subT)."</td>";
//                             $this->salida.="</tr>";


            $this->salida.="</table>";
            $_SESSION['CAJA']['SAL'] = $subT;
            if (empty($_SESSION['CAJA']['OTRAVEZ'])) {
                $_SESSION['CAJA']['TOTAL_EFECTIVO'] = $subT; //str_replace(".","",$subT);
                $_SESSION['CAJA']['OTRAVEZ'] = 'full'; //la primera vez q entre a esta interfaz
                //tendar el valor por defecto, pero si  cambian efectivo no se podra el vlor default
                //a menos que se salga de la caja.....ojo con esto
            }
        } else {
            $this->salida.="<tr>";
            $this->salida.="<td align=\"center\" class=\"label_error\">No existen citas para pagar.</td>";
            $this->salida.="</tr>";
            $this->salida.="</tr>";
            $this->salida.="</table><br>";
        }
        $this->salida.="</table>";
        $Cama = $subT;
        $this->FormaAbonos($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Cajaid);
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function RealizarDescuento($nom, $id, $tipo, $sw_pago, $valor, $vector, $op, $plan) {

        $this->salida .= ThemeAbrirTabla('DESCUENTOS');

        //revisamos a ver si esta autorizado
        //$conteo_user=$this->AutorizadorDescuento();
        $this->EncabezadoOrdenServicio($TipoId, $PacienteId, $plan);
        //$this->Encabezado();
        if (empty($tipo) or empty($id)) {
            $nom = $_REQUEST['nom'];
            $id = $_REQUEST['id'];
            $tipo = $_REQUEST['id_tipo'];
            $sw_pago = $_REQUEST['sw_pago'];
            $valor = $_REQUEST['valor_pago'];
            $vector = $_REQUEST['vector'];
            $plan = $_REQUEST['plan_id'];
            $op = $_REQUEST['op'];
        }


        $ac = ModuloGetURL('app', 'CajaGeneral', 'user', 'GuardarDesc', array('sw_pago' => $sw_pago, 'valor_pago' => $valor));
        $this->salida .='<form name="forma" action=' . $ac . ' method="post">';

        if ($sw_pago == 1) {
            $vari = "Cuota Moderadora";
        } elseif ($sw_pago == 2) {
            $vari = "Cuota Paciente";
        } elseif ($sw_pago == 3) {
            $vari = "Total Paciente";
        } elseif ($sw_pago == 4) {
            $vari = "Total Empresa";
        }
        $this->salida .='<table align="center" width="45%" border="0">';
        $this->salida .='<tr>';
        $this->salida .= "<td  align=\"center\"><label class='label_mark'>$vari</label>&nbsp;:&nbsp;<label class='label'>$" . FormatoValor($valor) . "</label></td>";
        $this->salida .='</tr>';
        $this->salida .='</table>';

        $dat = $this->TraerTipoDesc($sw_pago);
        $this->salida.="<br><table border=\"0\"  align=\"center\"   width=\"75%\" >";
        $this->salida .="" . $this->SetStyle("MensajeError") . "";
        $this->salida.="<tr>";
        $this->salida .= "<td  colspan=\"2\"  align=\"center\" class=\"modulo_table_title\" >Seleccion Descuento</td>";
        $this->salida.="</tr>";

        $this->salida.="<tr  class=\"modulo_list_claro\">";
        $this->salida .= "	<td width=\"35%\" >TIPO DESCUENTO</td><td class=\"modulo_list_oscuro\"><select name=\"tipode\" class=\"select\">";
        for ($i = 0; $i < sizeof($dat); $i++) {
            $this->salida .=" <option value=\"" . $dat[$i][tipo_desc_id] . "\">" . $dat[$i][descripcion] . "</option>";
        }

        $this->salida .= "       </select></td></tr>";

        $this->salida.="<tr  class=\"modulo_list_claro\">";
        $this->salida .='<td>VALOR DESCUENTO: </td>';
        $this->salida .='<td align=\"left\"><input name="descuento" type="text" class="input-text" value="' . $_REQUEST['descuento'] . '"></td>';
        $this->salida.="</tr>";

        $this->salida.="<tr class=\"modulo_list_claro\">";
        $this->salida .= "<td   width=\"35%\"  >OBSERVACION :</td>";
        $this->salida .= "<td  align=\"left\"><TEXTAREA name=obs cols=50 rows=8>" . $_REQUEST['obs'] . "</TEXTAREA></td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";


        $this->salida.="<br><table align=\"center\">";
        $this->salida.="<tr>";
        $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Guardar\" class=\"input-submit\"></form></td>";
        $this->salida .='<td>&nbsp;</td>';
        $this->salida .= "<td>";
        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'RetornarOrdenesServicio', array());
        $this->salida .='<form name="forma" action="' . $accion . '" method="post">';
        $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

//MODIFICAR COPAGO CUOTA MODERADORA
    function ModificarDescuento($nom, $id, $tipo, $sw_pago, $valor, $vector, $op, $plan) {

        if ($_REQUEST['sw_pago'] == 1)
            $this->salida .= ThemeAbrirTabla('MODIFICAR CUOTA MODERADORA');
        elseif ($_REQUEST['sw_pago'] == 2)
            $this->salida .= ThemeAbrirTabla('MODIFICAR COPAGO');


        //revisamos a ver si esta autorizado
        //$conteo_user=$this->AutorizadorDescuento();
        $this->EncabezadoOrdenServicio($TipoId, $PacienteId, $plan);
        //$this->Encabezado();
        if (empty($tipo) or empty($id)) {
            $nom = $_REQUEST['nom'];
            $id = $_REQUEST['id'];
            $tipo = $_REQUEST['id_tipo'];
            $sw_pago = $_REQUEST['sw_pago'];
            $valor = $_REQUEST['valor_pago'];
            $vector = $_REQUEST['vector'];
            $plan = $_REQUEST['plan_id'];
            $op = $_REQUEST['op'];
        }


        $ac = ModuloGetURL('app', 'CajaGeneral', 'user', 'GuardarDesc', array('sw_pago' => $sw_pago, 'valor_pago' => $valor));
        $this->salida .='<form name="forma" action=' . $ac . ' method="post">';

        /* 			if($sw_pago==1)
          {$vari="Cuota Moderadora";}elseif($sw_pago==2){$vari="Cuota Paciente";}
          elseif($sw_pago==3){$vari="Total Paciente";}
          elseif($sw_pago==4){$vari="Total Empresa";} */
        $vari = "Cuota Liquidada";

        $this->salida .='<table align="center" width="45%" border="0">';
        $this->salida .='<tr>';
        $this->salida .= "<td  align=\"center\"><label class='label_mark'>$vari</label>&nbsp;:&nbsp;<label class='label'>$" . FormatoValor($valor) . "</label></td>";
        $this->salida .='</tr>';
        $this->salida .='</table>';

        $dat = $this->TraerTipoCambio($sw_pago);
        $this->salida.="<br><table border=\"0\"  align=\"center\"   width=\"75%\" >";
        $this->salida .="" . $this->SetStyle("MensajeError") . "";
        $this->salida.="<tr>";
        $this->salida .= "<td  colspan=\"2\"  align=\"center\" class=\"modulo_table_title\" >Seleccion Descuento</td>";
        $this->salida.="</tr>";

        $this->salida.="<tr  class=\"modulo_list_claro\">";
        $this->salida .= "	<td width=\"35%\" >CONCEPTO DEL CAMBIO</td><td class=\"modulo_list_oscuro\"><select name=\"tipode\" class=\"select\">";

        if ($sw_pago == 1) {
            for ($i = 0; $i < sizeof($dat); $i++) {
                $this->salida .=" <option value=\"" . $dat[$i][motivo_cambio_cuota_moderadora_id] . "\">" . $dat[$i][descripcion] . "</option>";
            }
        } elseif ($sw_pago == 2) {
            for ($i = 0; $i < sizeof($dat); $i++) {
                $this->salida .=" <option value=\"" . $dat[$i][motivo_cambio_copago_id] . "\">" . $dat[$i][descripcion] . "</option>";
            }
        }

        $this->salida .= "       </select></td></tr>";

        $this->salida.="<tr  class=\"modulo_list_claro\">";
        if ($sw_pago == 1)
            $this->salida .='<td>VALOR CUOTA MODERADORA: </td>';
        elseif ($sw_pago == 2)
            $this->salida .='<td>VALOR COPAGO: </td>';
        $this->salida .='<td align=\"left\"><input name="valormodificado" type="text" class="input-text" value="' . $_REQUEST['descuento'] . '"></td>';
        $this->salida.="</tr>";

        $this->salida.="<tr class=\"modulo_list_claro\">";
        $this->salida .= "<td   width=\"35%\"  >OBSERVACION :</td>";
        $this->salida .= "<td  align=\"left\"><TEXTAREA name=obs cols=50 rows=8>" . $_REQUEST['obs'] . "</TEXTAREA></td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";


        $this->salida.="<br><table align=\"center\">";
        $this->salida.="<tr>";
        $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Guardar\" class=\"input-submit\"></form></td>";
        $this->salida .='<td>&nbsp;</td>';
        $this->salida .= "<td>";
        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'RetornarOrdenesServicio', array());
        $this->salida .='<form name="forma" action="' . $accion . '" method="post">';
        $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

//FIN MODIFICAR COPAGO CUOTA MODERADORA

    /**
     *
     */
    function FormaCuentaExterna($TipoId, $PacienteId, $PlanId) {
        IncludeLib("tarifario");
        $this->salida .= ThemeAbrirTabla('CAJA CONSULTA AMBULATORIOS');
        $this->EncabezadoConsultaExt($TipoId, $PacienteId, $PlanId);
        $this->salida .= "<br>";
        $this->salida .= "<br><table border=\"1\" width=\"88%\" align=\"center\">";
        $this->salida.="<tr><td class=\"modulo_table_title\">DETALLES AMBULATORIOS";
        $this->salida.="</td></tr>";
        $dats = $this->TraerDetalleTmpCexterna();
        if ($dats) {
            $this->salida.="<tr><td><br>";
            $this->salida.="<table  align=\"center\" border=\"0\" width=\"85%\">";
            $this->salida.="<tr class=\"modulo_table_list_title\">";
            $this->salida.="  <td>Fecha</td>";
            $this->salida.="  <td>Cod.Cargo</td>";
            $this->salida.="  <td>Cargo</td>";
            $this->salida.="  <td>Valor</td>";
            $this->salida.="  <td></td>";
            $this->salida.="</tr>";
            for ($i = 0; $i < sizeof($dats); $i++) {
                $fecha = $dats[$i][fecha];
                $desc = $dats[$i][descripcion];
                $valor = $dats[$i][valor_cargo];
                $cargo = $dats[$i][cargo];
                if (strlen($desc) > 45) {
                    $desc = substr($desc, 0, 45);
                    $desc.='...';
                }
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida.="<tr class=\"$estilo\" align=\"center\">";
                $this->salida.="  <td>$fecha</td>";
                $this->salida.="  <td>$cargo</td>";
                $this->salida.="  <td>$desc</td>";
                $this->salida.="  <td>" . FormatoValor($valor) . "</td>";
                $this->salida.="  <td><img src=\"" . GetThemePath() . "/images/checkS.gif\"></td>";
                $subT = $subT + $valor;
                $this->salida.="</tr>";
            }
            $this->salida.="<tr>";
            $this->salida.="<td colspan='5'>&nbsp;</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr class=\"modulo_table_list_title\">";
            $this->salida.="  <td colspan='5' align='center' >TOTAL:&nbsp;" . FormatoValor($subT) . "</td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
            $this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
            $_SESSION['CAJA']['SAL'] = $subT;
            /*    $this->salida.="<tr>";
              $this->salida.="  <td align=\"center\">";
              $this->salida .='<form name="forma" action="'.ModuloGetURL('app','CajaGeneral','user','BuscarDetalleC',array('Cajaid'=>$Cajaid,'arx'=>$valores)).'" method="post">';
              $this->salida .="<input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Adicionar Conceptos\" class=\"input-submit\"></form></td>";
              $this->salida.="</td>";
              $this->salida.="</tr>"; */
            $this->salida.="</table>";
            $this->salida.="</td></tr>";
        } else {
            $this->salida.="<tr>";
            $this->salida.="<td align=\"center\" class=\"label_error\">No existen citas para pagar.</td>";
            $this->salida.="</tr>";
            $this->salida.="</td></tr>";
            $this->salida.="</table><br>";
            $this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
            /*        $this->salida.="<tr>";
              $this->salida.="  <td align=\"center\">";
              $this->salida .='<form name="forma" action="'.ModuloGetURL('app','CajaGeneral','user','BuscarDetalleC',array('Cajaid'=>$Cajaid,'arx'=>$valores)).'" method="post">';
              $this->salida .="<input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Adicionar Conceptos\" class=\"input-submit\"></form></td>";
              $this->salida.="</td>";
              $this->salida.="</tr>"; */
        }
        $this->salida.="</table>";
        $Cama = $subT;
        $this->FormaAbonos($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Cajaid);
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

//function BusquedaTercer()
    function BuscarTercero() {
        
        unset($_SESSION['CAJA']['var']);
        unset($_SESSION['CAJA']['FACTURACONCEPTO']);
        unset($_SESSION['CAJA']['PREFIJOCONCEPTO']);
        // $this->BorrarTemporales();
        unset($_SESSION['CAJA']['CUENTA']);
        unset($_SESSION['CAJA']['TERCEROID']);
        unset($_SESSION['CAJA']['TIPO_ID_TERCERO']);
        unset($_SESSION['CAJA']['NOMBRE_TERCERO']);

        //varibles para el total
        unset($_SESSION['CAJA']['SAL']);
        unset($_SESSION['CAJA']['SUBTOTAL']);
        unset($_SESSION['CAJA']['TOTAL_EFECTIVO']);
        unset($_SESSION['CAJA']['BONO']);

        $factura = $_REQUEST['factura'];
        if (!empty($factura))
            $_SESSION['CAJA']['FACTURA'] = $factura;
        else
            $factura = $_SESSION['CAJA']['FACTURA'];

        if (!empty($_REQUEST['grupo_tarifario'])) {
            $_SESSION['CAJA']['GRUPO_TARIFARIO'] = $_REQUEST['grupo_tarifario'];
            $_SESSION['CAJA']['SUBGRUPO_TARIFARIO'] = $_REQUEST['subgrupo_tarifario'];
        }
        if (empty($_SESSION['CAJA']['EMPRESA'])) {
            $this->Empresa = $_REQUEST['Caja']['empresa_id'];
            $this->Cutilidad = $_REQUEST['Caja']['centro_utilidad'];
            $_SESSION['CAJA']['EMPRESA'] = $this->Empresa;
            $_SESSION['CAJA']['CENTROUTILIDAD'] = $this->Cutilidad;
            $this->Cutilidad = $_SESSION['CAJA']['CENTROUTILIDAD'];
            $this->Empresa = $_SESSION['CAJA']['EMPRESA'];
            $_SESSION['CAJA']['TIPONUMERACION'] = $_REQUEST['Caja']['tipo_numeracion'];
            $_SESSION['CAJA']['TIPOCUENTA'] = $_REQUEST['Caja']['cuenta_tipo_id'];
            $_SESSION['CAJA']['CAJAID'] = $_REQUEST['Caja']['caja_id'];
            $_SESSION['CAJA']['CU'] = $_REQUEST['CU'];
            $_SESSION['CAJA']['CUENTA'] = $_REQUEST['Cuenta'];
        }

        if (!empty($_REQUEST['TipoCuenta']))
            $_SESSION['CAJA']['TIPO_CUENTA'] = $_REQUEST['TipoCuenta'];
        else
            $_REQUEST['TipoCuenta'] = $_SESSION['CAJA']['TIPO_CUENTA'];
        if (!empty($_REQUEST['bodega']))
            $_SESSION['CAJA']['BODEGA'] = $_REQUEST['bodega'];
        else
            $_REQUEST['bodega'] = $_SESSION['CAJA']['BODEGA'];

        $this->salida .= ThemeAbrirTabla('BUSCAR TERCERO');
        $this->EncabezadoEmpresa($_SESSION['CAJA']['CAJAID'], '', $_REQUEST['TipoCuenta']);
        $TipoCuenta = $_REQUEST['TipoCuenta'];
        $datos = $this->CallMetodoExterno('app', 'Triage', 'user', 'tipo_id_terceros', '');
        $backgrounds = array('modulo_list_claro' => '#DDDDDD', 'modulo_list_oscuro' => '#CCCCCC');

        $mostrar = "\n<script language='javascript'>\n";
        $mostrar.="function mOvr(src,clrOver) {;\n";
        $mostrar.="src.style.background = clrOver;\n";
        $mostrar.="}\n";

        $mostrar.="function mOut(src,clrIn) {\n";
        $mostrar.="src.style.background = clrIn;\n";
        $mostrar.="}\n";
        $mostrar.="</script>\n";
        $this->salida .="$mostrar";
        $this->salida .= " <br><table border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "  <tr><td><fieldset><legend class=\"field\">BUSCAR</legend>";
        $this->salida.="<table  width='80%' align='center' border='0'>";
        $this->salida.="<tr><td align='left'  class=\"label\">";
        $this->salida .='<form name="recarga" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarTercero', array('tercero' => $value, 'Cajaid' => $Cajaid)) . '" method="post">';
//$this->salida .='<form name="recarga" action="'.ModuloGetURL('app','CajaGeneral','user','BusquedaTercer',array('tercero'=>$value,'Cajaid'=>$Cajaid)).'" method="post">';
        $this->salida.="Tipo Identificaci�n:</td>";
        $this->salida.="<td>    <select name='TipoDocum' class='select'>";
        $this->salida.="<option value=\"-1\" selected>TODAS</option>";
        foreach ($datos as $value => $desc) {
            $this->salida.="<option value=\"" . $value . "\">" . $desc . "</option>";
        }
        $this->salida.="</select>";
        $this->salida.="</td></tr>";
        $this->salida.="<tr><td class=\"label\">No. Identificaci�n: </td>";
        $this->salida.="<td><input type=\"text\" name=\"Documento\" size=\"10\" maxlength=\"30\" class=\"input-text\"></td>";
        $this->salida.=" </tr>";
        $this->salida.="<tr><td class=\"label\">Nombre: </td>";
        $this->salida.="<td><input type=\"text\" name=\"busnom\" size=\"20\" maxlength=\"30\" class=\"input-text\"></td>";
        $this->salida.=" </tr>";
        //$this->salida.="<tr><td colspan=\"2\">&nbsp;&nbsp;</td></tr>";
        $this->salida.=" <tr>";
        $this->salida.=" <td colspan=\"1\" align=\"center\"><br>";
        $this->salida.=" <input name=\"Buscar\" type=\"submit\" class=\"input-submit\"  value=\"Buscar\"></form>";
        $this->salida.="</form>";
        $this->salida.=" </td>";
        $this->salida .='<form name="recarga" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'main') . '" method="post">';
        $this->salida .="<td  class=normal_10 align=\"center\"><input name=\"Buscar\" type=\"submit\" class=\"input-submit\"  value=\"Volver\"></td>";
        $this->salida.="</form>";
        //$this->salida .="<td colspan=\"2\" class=normal_10 align=\"center\"><br><a href=".ModuloGetURL('app','CajaGeneral','user','main').">VOLVER</a></td>";
        $this->salida.=" </tr>";
        $this->salida.="</table>";
        $this->salida .= "          </fieldset></td></tr></table><BR>";

        $Busquedaid = $_REQUEST['Documento'];
        $Busquedanom = $_REQUEST['busnom'];
       // echo print_r($_REQUEST);
        $dat = $this->TraerBusqTercero($Busquedaid, $Busquedanom);  
        if ($dat) {
            //$this->salida.="<tr><td><br>";
            $this->salida.="<table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"80%\">";
            $this->salida.="<tr class=\"modulo_table_list_title\">";
            $this->salida.="  <td width='15%'>Identificaci�n</td>";
            $this->salida.="  <td width='27%'>Nombre</td>";
            $this->salida.="  <td width='15%'>Direccion</td>";
            $this->salida.="  <td width='10%'>Telefono</td>";
            $this->salida.="  <td width='10%'>Email</td>";
            $this->salida.="  <td width='3%'></td>";
            $this->salida.="</tr>";
            for ($i = 0; $i < sizeof($dat); $i++) {
                $iden = $dat[$i][tipo_id_tercero] . '-' . $dat[$i][tercero_id];
                $nombre = $dat[$i][nombre_tercero];
                $dir = $dat[$i][direccion];
                $tel = $dat[$i][telefono];
                $email = $dat[$i][email];
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida.="<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
                $this->salida.="  <td>$iden</td>";
                $this->salida.="  <td>$nombre</td>";
                $this->salida.="  <td>$dir</td>";
                $this->salida.="  <td>$tel</td>";
                $this->salida.="  <td>$email</td>";
                $this->salida.="  <td><a href=" . ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConceptos', array('Cajaid' => $_REQUEST['Cajaid'], 'nombre' => urlencode($nombre), 'arreglo' => $dat[$i], 'factura' => $factura, 'TipoCuenta' => $TipoCuenta)) . ">VER</a></td>";

                //$this->salida.="<td></a></td>";
                //    $this->salida.="<td><a href=".ModuloGetURL('app','CajaGeneral','user','FormaCuentaConceptos',array('concepto'=>$group)).">Pago</a></td>";
                $this->salida.="</tr>";
            }
            $this->salida.="</table><br>";
            //$this->salida.="</td></tr>";
        } else {
            //$acc=$this->CallMetodoExterno('app','Terceros','user','ValidarBusquedaTercer','');
            SessionDelVar("BodegaInv");
            SessionDelVar("FacturaInv");
            if ($_REQUEST[bodega]) {
                SessionSetVar("BodegaInv", $_REQUEST[bodega]);
                SessionSetVar("FacturaInv", $_REQUEST[factura]);
            }
            $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'CreaTerceros', array('tercero' => $_REQUEST['Documento'], 'tipoid' => $_REQUEST['TipoDocum']));
            $this->salida .="<form name='recarga' action=\"$acc\" method='post'>";
            $this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
            if (!empty($Busquedaid) OR empty($Busquedanom)) {
                $this->salida.="<tr>";
                $this->salida.="<td align=\"center\" class=\"label_error\">La busqueda '" . "$Busquedaid" . "' '" . $Busquedanom . "' no arrojo resultados.</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr align=\"center\" ><td>";
                $this->salida.=" <input name=\"NUEVO\" type=\"submit\" class=\"input-submit\" value=\"CREAR\">";
                $this->salida.="</td></tr>";
            } else
            if ($_REQUEST['TipoDocum'] != -1 AND !empty($_REQUEST['TipoDocum'])) {
                $this->salida.="<tr>";
                $this->salida.="<td align=\"center\" class=\"label_error\">La busqueda no arrojo resultados.</td>";
                $this->salida.="</tr>";
            }
            $this->salida.="</table><br>";
            $this->salida.="</form>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function CapturaDetalle() {
        $group = $_REQUEST['concepto'];
        $dat = $_REQUEST['arx'];
        $Cajaid = $_REQUEST['Cajaid'];
        $this->salida .= ThemeAbrirTabla('BUSCAR GRUPOS');
        $datos = $this->ConsultaDetalleConceptos($group);
        $this->salida .= " <br><table border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "  <tr><td><fieldset><legend class=\"field\">DATOS CONCEPTO</legend>";
        $this->salida.="<table  width='80%' align='center' border='0'>";
        $this->salida.=$this->SetStyle("MensajeError");
        $this->salida.=" <tr class='label'>";
        $this->salida.=" <td>CONCEPTO: </td>";
        $this->salida.="<td align='left'>";
        $this->salida .="<form name=\"recarga\" action=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarDetalle', array('concepto' => $group, 'Cajaid' => $Cajaid, 'arx' => $dat)) . "\" method=\"post\">";
        $this->salida.="<select name='conceptos' class='select'>";
        for ($i = 0; $i < sizeof($datos); $i++) {
            $this->salida.="<option value=" . urlencode($datos[$i][concepto_id] . "^" . $datos[$i][concepto]) . ">" . $datos[$i][concepto] . "</option>";
        }
        $this->salida.="</select>";
        $this->salida.="</td></tr>";

        $this->salida.=" <tr>";
        $this->salida.=" <td class=\"" . $this->SetStyle("valor") . "\">VALOR: </td>";
        $this->salida.=" <td align='left'><input type='text' name='valor' size='10' class=\"input-text\"></td>";
        $this->salida.=" </tr>";
        $this->salida.=" <tr>";
        $this->salida.=" <td class=\"" . $this->SetStyle("observacion") . "\">OBSERVACION: </td>";
        $this->salida.=" <td align='left'><textarea name='observacion' class=\"textearea\" class='textarea'></textarea></td>";
        $this->salida.=" </tr>";
        $this->salida.="</table>";

        $this->salida.="<table  width='45%' align='center' border='0'   cellpading=\"3\" cellspacing=\"3\">";
        $this->salida.=" <tr>";
        $this->salida.=" <td align=\"center\">";
        $this->salida.=" <input name=\"Guardar\" type=\"submit\" class=\"input-submit\"  value=\"Guardar\"></form>";
        $this->salida.="</form>";
        $this->salida.=" </td>";
        $this->salida.=" <td align=\"center\">";
        $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaCuentaConceptos', array('Cajaid' => $Cajaid, 'arx' => $dat)) . '" method="post">';
        $this->salida .="<input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form>";
        $this->salida.=" </td>";
        $this->salida.=" </tr>";
        $this->salida.="</table>";
        $this->salida .= "          </fieldset>";
        $this->salida .= "</td></tr></table><BR>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function BuscarDetalleC() {
        $dat = $_REQUEST['arx'];
        $Cajaid = $_REQUEST['Cajaid'];

        $this->salida .= ThemeAbrirTabla('BUSCAR GRUPOS');
        $datos = $this->ComboConceptos();
        $this->salida .= " <br><table border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "  <tr><td><fieldset><legend class=\"field\">ELEGIR CONCEPTO</legend>";
        $this->salida.="<table  width='70%' align='center' border='0'>";
        $this->salida.="<tr><td align='left' colspan=\"2\">";
        $this->salida .='<form name="recarga" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'CapturaDetalle', array('concepto' => $group, 'Cajaid' => $Cajaid, 'arx' => $dat)) . '" method="post">';
        $this->salida.="<font size=1 color=#000000><b>CONCEPTOS</b></font>: &nbsp;&nbsp;<select name='concepto' class='select'>";
        for ($i = 0; $i < sizeof($datos); $i++) {
            $this->salida.="<option value=" . $datos[$i][grupo_concepto] . ">" . $datos[$i][descripcion] . "</option>";
        }
        $this->salida.="</select>";
        $this->salida.="</td></tr>";
        $this->salida.="<tr><td colspan=\"2\">&nbsp;&nbsp;</td></tr>";
        //$this->salida.="<tr><td colspan=\"2\">&nbsp;&nbsp;</td></tr>";
        $this->salida.=" <tr>";
        $this->salida.=" <td align=\"center\">";
        $this->salida.=" <input name=\"Buscar\" type=\"submit\" class=\"input-submit\"  value=\"Buscar\"></form>";
        $this->salida.="</form>";
        //$this->salida.=" </td>";
        $this->salida.=" </td>";
        $this->salida.=" <td align=\"center\">";
        $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaCuentaConceptos', array('Cajaid' => $Cajaid, 'arx' => $dat)) . '" method="post">';
        $this->salida .="<input type=\"submit\" align=\"center\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form>";
        $this->salida.=" </td>";
        $this->salida.=" </tr>";
        $this->salida.="</table>";
        $this->salida .= "          </fieldset></td></tr></table><BR>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
     *
     */
    function FormaCuentaConceptos($valores, $factura, $var, $var2) {
        if (empty($factura))
            $factura = $_SESSION['CAJA']['FACTURA'];
        IncludeLib("tarifario");
        if (!$valores) {
            $valores = $_REQUEST['arx'];
        }
        $this->salida = ThemeAbrirTabla('CAJA CONCEPTOS');
        $ruta = ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaCuentaConceptos');
        $mostrar = "\n<script language='javascript'>\n";
        $mostrar.="function mOvr(src,clrOver) {;\n";
        $mostrar.="src.style.background = clrOver;\n";
        $mostrar.="}\n";

        $mostrar.="function mOut(src,clrIn) {\n";
        $mostrar.="src.style.background = clrIn;\n";
        $mostrar.="}\n";

        $mostrar.="  function load_page(obj){\n";
        $mostrar.="    var url ='$ruta';\n";
        $mostrar.="    var es = obj.options[obj.selectedIndex].value;\n";
        $mostrar.='    var url2 = url+"&grupo="+es;';
        $mostrar.="    window.location.href=url2;};\n";

        $mostrar.="  function load_page2(obj){\n";
        $mostrar.="    var url ='$ruta';\n";
        $mostrar.="    var es = obj.options[obj.selectedIndex].value;\n";
        $mostrar.='    var url2 = url+"&concepto="+es;';
        $mostrar.="    window.location.href=url2;};\n";
        $mostrar.="</script>\n";
        $this->salida .="$mostrar";
        $backgrounds = array('modulo_list_claro' => '#DDDDDD', 'modulo_list_oscuro' => '#CCCCCC');
        $this->EncabezadoConceptos($valores);
        $this->salida .= "<br><table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table\">";
        $this->salida.="<tr><td class=\"modulo_table_title\">DETALLE CONCEPTOS -21-1- FACTURA $factura"; //FACTURA CREDITO O CONTADO
        $this->salida.="</td></tr>";
        $this->salida.="</table>";
        //NUEVO PARA LA CAJA DE CONCEPTOS-20102005
        $datos = $this->ConsultaGrupoConceptos($factura);
        $this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "  <tr><td><fieldset><legend class=\"field\">DATOS CONCEPTO</legend>";
        $this->salida.="<table  width='80%' align='center' border='0'>";
        $this->salida.=$this->SetStyle("MensajeError");
        $this->salida.=" <tr class='label'>";
        $this->salida.=" <td>GRUPO: </td>";
        $this->salida.="<td align='left'>";
        $this->salida .="<form name=\"recarga\" action=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarDetalle', array('concepto' => $group, 'Cajaid' => $Cajaid, 'arx' => $dat)) . "\" method=\"post\">";
        $this->salida.="<select name='grupos' class='select' OnChange=\"load_page(this);\">";
        /* 			for($i=0;$i<sizeof($datos);$i++)
          {
          $this->salida.="<option value=".urlencode($datos[$i][concepto_id]."^".$datos[$i][concepto]).">".$datos[$i][concepto]."</option>";
          } */
        $this->salida.="<option value=\"-1\" selected>-------TODAS------</option>";
        for ($i = 0; $i < sizeof($datos); $i++) {
            $_SESSION['CAJA']['GRUPO'] = $_REQUEST['grupo'];
            if ($_REQUEST['grupo'] == $datos[$i][grupo_concepto] OR $_SESSION['CAJA']['var'] == $datos[$i][grupo_concepto])
                $this->salida.="<option value=\"" . $datos[$i][grupo_concepto] . "\" selected>" . $datos[$i][grupo_concepto] . '-' . $datos[$i][descripcion] . "</option>";
            else
                $this->salida.="<option value=\"" . $datos[$i][grupo_concepto] . "\">" . $datos[$i][grupo_concepto] . '-' . $datos[$i][descripcion] . "</option>";
        }
        $this->salida.="</select>";
        $this->salida.="</td>";
        $this->salida.="</tr>";
//CONCEPTOS	
        /* 						if(empty($datos))
          $var=$this->BuscarConceptos($factura);
          else
          $var=$datos; */
        if (!empty($_REQUEST['grupo']))
            $_SESSION['CAJA']['var'] = $_REQUEST['grupo'];
        else
            $_REQUEST['grupo'] = $_SESSION['CAJA']['var'];

        //if(!empty($var))
        if (!empty($_REQUEST['grupo'])) {
            $sql = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
            $tipoPagos=$sql->tipo_pago();
            $_SESSION['CAJA']['CONCEPTO'] = $_REQUEST['concepto'];
            $var = $this->BuscarConceptos($factura, $_REQUEST['grupo']);
            $this->salida.=" <tr class='label'>";
            $this->salida.=" <td>CONCEPTOS: </td>";
            $datos = explode('*', $_REQUEST['concepto']);
            $this->salida.="<td align='left'>";
            $this->salida.="<select name='conceptos' class='select' OnChange=\"load_page2(this);\">";
            /* 						for($i=0;$i<sizeof($datos);$i++)
              {
              $this->salida.="<option value=".urlencode($datos[$i][concepto_id]."^".$datos[$i][concepto]).">".$datos[$i][concepto]."</option>";
              } */
            $this->salida.="<option value=\"\">----------SELECCCIONE----------</option>";
            for ($i = 0; $i < sizeof($var); $i++) {
                if ($datos[1] == $var[$i][concepto_id] AND $datos[0] == $var[$i][grupo_concepto])
                    $this->salida.="<option value=\"" . $var[$i][grupo_concepto] . '*' . $var[$i][concepto_id] . "\" selected>" . $var[$i][concepto_id] . '-' . $var[$i][descripcion] . "</option>";
                else
                    $this->salida.="<option value=\"" . $var[$i][grupo_concepto] . '*' . $var[$i][concepto_id] . "\">" . $var[$i][concepto_id] . '-' . $var[$i][descripcion] . "</option>";
            }
            $this->salida.="</select>";
            $this->salida.="</td>";
            $this->salida.="</tr>";
            
            $this->salida.="<tr class='label'>";
            $this->salida.="<td name='forma_pago'>TIPO PAGO: </td>";
            $this->salida.="<td align='left'>";
            $this->salida.="<select id=\"forma_pago\" name=\"forma_pago\" class=\"select\">";
            $this->salida.="<option value=\"-1\">-- seleccionar --</option>";
            foreach ($tipoPagos as $k => $valores) {
            $this->salida.="<option value=\"" . $valores['tipo_pago_id'] . "\">" . $valores['descripcion'] . "</option>";
            }
            $this->salida.="</select>";
            $this->salida.="</td>";
            $this->salida.="</tr>";
        }
//FIN CONCEPTOS
        if (!empty($_REQUEST['concepto'])) {
            $concep = explode('*', $_REQUEST['concepto']);
            
            
            $var2 = $this->BuscarConceptos1($factura, $concep[1], $_REQUEST['grupo']);
            $this->salida.=" <tr>";
            //--------hideen con el gravamen
            $this->salida .= "       <input type=\"hidden\" name=\"gravamen\" value=\"" . $var2[0][porcentaje_gravamen] . "\" class=\"input-text\" ></td>";
            //-----------------------------
           // $this->salida.=" <td class=\"" . $this->SetStyle("cantidad") . "\">CANTIDAD: </td>";
            $this->salida .= "<td></td> ";
            if ($var2[0][sw_cantidad] == 0)
                $this->salida.=" <td align='left'><input type='hidden'  name='cantidad' size='10' class=\"input-text\" value=\"1\" readonly></td>";
            else
                $this->salida.=" <td align='left'><input type='hidden' name='cantidad' size='10' class=\"input-text\" value=\"1\"></td>";
            $_SESSION['CAJA']['CANTIDAD'] = $_REQUEST['cantidad'];
            $this->salida.=" </tr>";
            $this->salida.=" <tr>";
            $this->salida.=" <td class=\"" . $this->SetStyle("precio") . "\">PRECIO: </td>";
            if ($var2[0][sw_precio_manual] == 0)
                $this->salida.=" <td align='left'><input type='text' name='precio' size='15' class=\"input-text\" value=\"" . Formatovalor($var2[0][precio]) . "\" readonly></td>";
            else
                $this->salida.=" <td align='left'><input type='text' name='precio' size='15' class=\"input-text\" value=\"" . Formatovalor($var2[0][precio]) . "\"></td>";
            $_SESSION['CAJA']['PRECIO'] = $_REQUEST['precio'];
            $this->salida.=" </tr>";
//CAMPO PARA EL GRAVAMEN
            $this->salida.=" <tr>";
            $this->salida.=" <td class=\"" . $this->SetStyle("gravamen") . "\">GRAVAMEN: </td>";
            if ($var2[0][sw_modificar_gravamen] == 0)
                $this->salida.=" <td align='left'><input type='text' name='gravamen' size='15' class=\"input-text\" value='0' readonly></td>";
            else
                $this->salida.=" <td align='left'><input type='text' name='gravamen' size='15' class=\"input-text\" value='0'></td>";
            $_SESSION['CAJA']['PRECIO'] = $_REQUEST['precio'];
            $this->salida.=" </tr>";
//FIN CAMPO PARA EL GRAVAMEN
            $this->salida.=" <tr>";
            $this->salida.=" <td class=\"" . $this->SetStyle("observacion") . "\">OBSERVACION: </td>";
            $this->salida.=" <td align='left'><textarea name='observacion' id='observacion' value=' '></textarea></td>";
            $this->salida.=" </tr>";
            $this->salida.="</table>";


            $this->salida.="<table  width='45%' align='center' border='0'   cellpading=\"3\" cellspacing=\"3\">";
            $this->salida.=" <tr>";
            $this->salida.=" <td align=\"center\">";
            $this->salida.=" <input name=\"Guardar\" type=\"submit\" class=\"input-submit\"  value=\"Guardar\">";
            $this->salida.=" </td>";
            /* 						$this->salida.=" <td align=\"center\">";
              $this->salida .='<form name="forma" action="'.ModuloGetURL('app','CajaGeneral','user','FormaCuentaConceptos',array('Cajaid'=>$Cajaid,'arx'=>$dat)).'" method="post">';
              $this->salida .="<input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form>";
              $this->salida.=" </td>"; */
        }
        $this->salida.=" </tr>";
        $this->salida.="</table>";
        $this->salida .= "          </fieldset>";
        $this->salida .= "</td></tr></table><BR>";
        $dats = $this->ConsultaInsertadosDetalle();
        
       
        if (!empty($dats)) {
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
            $this->salida.="<tr class=\"modulo_table_list_title\" align=\"center\">";
            $this->salida.="  <td colspan=8>CONCEPTOS GUARDADOS</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr class=\"modulo_table_list_title\">";
            $this->salida.="  <td>GRUPO</td>";
            $this->salida.="  <td>CONCEPTO</td>";
            $this->salida.="  <td>TIPO PAGO</td>";
            $this->salida.="  <td>DESCRIPCION</td>";
            $this->salida.="  <td width=\"15%\">VALOR UNITARIO</td>";
           // $this->salida.="  <td>% GRAVAMEN</td>";
            $this->salida.="  <td>VALOR GRAVAMEN</td>";
          //  $this->salida.="  <td width=\"15%\">TOTAL</td>";
            $this->salida.="  <td></td>";
            $this->salida.="</tr>";
            $_SESSION['CAJA']['SAL'] = 0;
            $retencion_fuente = 0;
            $retencion_ica = 0;
            $valor_subtotal = 0;
            $total_iva = 0;
            
            for ($i = 0; $i < sizeof($dats); $i++) {
                $grupoid = $dats[$i][grupo_concepto];
                $valor_total = $dats[$i][valor_total];
                $porcentaje_gravamen = $dats[$i][porcentaje_gravamen];
                $valor_gravamen = $dats[$i][valor_gravamen];
                $TOTAL+=$dats[$i][valor_total];
                $precio = $dats[$i][precio];
                $cantidad = $dats[$i][cantidad];
                $descripcion = $dats[$i]['descripcion'];
                $conceptoid = $dats[$i][concepto_id];
                $descripcionpago = $dats[$i]['descripcionpago'];
                $tipoPago = $dats[$i]['tipo_pago_id'];
                $concepto = $dats[$i][desconcepto];
                $grupo = $dats[$i][desgrupo];
                $detalle = $dats[$i][descripcion];
                $total_iva += $valor_gravamen;
                
              //  echo $porcentaje_gravamen. " ========= " .  (($dats[$i][valor_total] * $porcentaje_gravamen) / 100) ."</br>";
                //echo $dats[$i][valor_total] ."</br>";
                

                $valor_subtotal += $dats[$i][valor_total] - $valor_gravamen;
                                
                if (strlen($detalle) > 45) {
                    $detalle = substr($detalle, 0, 45);
                    $detalle.='...';
                }
                if (strlen($desc) > 45) {
                    $desc = substr($desc, 0, 45);
                    $desc.='...';
                }
                //si es credito no debe sumar porque no va a pagar
                if ($_SESSION['CAJA']['FACTURA'] == 'contado') {
                    $_SESSION['CAJA']['SAL'] += $valor_total;
                }
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida.="<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB'); >";
                $this->salida.="  <td>" . $grupoid . "-" . $grupo . "</td>";
                $this->salida.="  <td>" . $conceptoid . "-" . $concepto . "</td>";
                $this->salida.="  <td>" . $descripcionpago ."</td>";
                $this->salida.="  <td>{$descripcion}</td>";
                $this->salida.="  <td>" . FormatoValor($dats[$i][valor_total] - $valor_gravamen) . "</td>";
             //   $this->salida.="  <td>" . FormatoValor($porcentaje_gravamen) . "</td>";
                $this->salida.="  <td>" . FormatoValor($valor_gravamen) . "</td>";
               // $this->salida.="  <td>" . FormatoValor($valor_total) . "</td>";
                $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'EliminarConcepto', array('grupoid' => $grupoid, 'conceptoid' => $conceptoid, 'rc_concepto' => $dats[$i][rc_concepto_id]));
                $this->salida.="  <td><a href=\"$acc\"><img src=\"" . GetThemePath() . "/images/elimina.png\" TITLE=\"ELIMINAR CONCEPTO\"></a></td>";
                //$this->salida.="  <td><img src=\"".GetThemePath()."/images/checkS.gif\"></td>";
                $subT = $subT + $valor;
                $this->salida.="</tr>";
            }
            
            
               $impuestos = $this->TraerPorcentajeImpuestos($_SESSION['CAJA']['EMPRESA'],$_SESSION['CAJA']['TERCEROID'], $_SESSION['CAJA']['TIPO_ID_TERCERO']);
            
              if ($impuestos['porcentaje_rtf'] > 0) {      
                  //echo $valor_subtotal;
                    if ($valor_subtotal >= $impuestos['base_rtf']) {
                        //echo print_r($impuestos);
                        $retencion_fuente = $valor_subtotal  * ($impuestos['porcentaje_rtf'] / 100);
                        if ($retencion_fuente > 0) {
                            $retencion_fuente = (int) $retencion_fuente;
                        }
                    }
                }

                
                if ($impuestos['porcentaje_ica'] > 0) {
                    if ($valor_subtotal >= $impuestos['base_ica']) {
                        $retencion_ica = $valor_subtotal * ($impuestos['porcentaje_ica'] / 1000);
                        if ($retencion_ica > 0) {
                            $retencion_ica = (int) $retencion_ica;
                        }
                    }

                }    
                
                
                $totalgeneral =  $valor_subtotal + $total_iva - ($retencion_fuente + $retencion_ica);
               // $_SESSION['CAJA']['TOTAL_GENERAL'] = $totalgeneral;
                
                if( $_SESSION['CAJA']['SAL'] > 0){
                    $_SESSION['CAJA']['SAL'] =  $_SESSION['CAJA']['SAL'] - ($retencion_fuente + $retencion_ica);
                    
                }
                
            
       /*     $this->salida.="<tr class=\"modulo_table_list_title\" align=\"center\">";
            $this->salida.="<td colspan=\"6\"align=\"right\">TOTAL $ &nbsp;</td>";
            $this->salida.="<td>" . FormatoValor($TOTAL) . "</td>";
            $this->salida.="<td></td>";
            $this->salida.="</tr>";*/
            $this->salida .= "</table></br>";
            $this->salida .="<table  align=\"center\" border=\"0\"  width=\"85%\"
                                            <tr  align=\"center\">
                                                <td class=\"modulo_table_list_title\">SUBTOTAL</td>
                                                <td class=\"modulo_table_list_title\">RETEFUENTE</td>
                                                 <td class=\"modulo_table_list_title\">RETEICA</td>
                                                 <td class=\"modulo_table_list_title\">TOTAL</td>
                                             </tr>
                                             
                                             <tr class='modulo_list_oscuro'  align='center'>
                                                    <td>".FormatoValor($valor_subtotal)."</td>
                                                    <td>".FormatoValor($retencion_fuente)."</td>
                                                    <td>".FormatoValor($retencion_ica)."</td>
                                                    <td>".FormatoValor($totalgeneral)."</td>
                                             </tr>
                                    </table></br>
            ";
        }
        //FIN NUEVO PARA LA CAJA DE CONCEPTOS-20102005
        $Cama = $subT;
        $this->salida.="</form>";
        if ($factura == 'contado')
            $this->FormaAbonos($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Cajaid);
        else
        if ($factura == 'credito') {
            $this->salida.="<table  width='45%' align='center' border='0'   cellpading=\"3\" cellspacing=\"3\">";
            $this->salida.=" <tr>";
            $this->salida.=" <td align=\"center\">";
            $acc1 = ModuloGetURL('app', 'CajaGeneral', 'user', 'VentanaGuardarRecibo', array('Cajaid' => $Cajaid, 'arx' => $dat,'tipoPago' => $tipoPago));
            $this->salida .='<form name="forma" action=' . $acc1 . ' method="post">';
            $this->salida .="<input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Guardar Fact\" class=\"input-submit\"></form>";
            $this->salida.=" </td>";
            $this->salida.=" <td align=\"center\">";
            $acc2 = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarTercero', array('Cajaid' => $Cajaid, 'arx' => $dat));
            $this->salida .='<form name="forma" action=' . $acc2 . ' method="post">';
            $this->salida .="<input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Volver\" class=\"input-submit\"></form>";
            $this->salida.=" </td>";
            $this->salida.=" </tr>";
            $this->salida.="</table>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function FormaCuentaInventarios($valores, $factura, $var, $var2) {
        if (empty($factura))
            $factura = $_SESSION['CAJA']['FACTURA'];
        //IncludeLib("tarifario");
        if (!$valores) {
            $valores = $_REQUEST['arx'];
        }
        $this->salida = ThemeAbrirTabla('CAJA INVENTAROS');
        $this->EncabezadoConceptos($valores);
        $this->salida .= "<br><table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table\">";
        if ($factura == 'credito')
            $this->salida.="<tr><td class=\"modulo_table_title\">DETALLE - FACTURA CR�DITO</td></tr>";
        elseif ($factura == 'contado')
            $this->salida.="<tr><td class=\"modulo_table_title\">DETALLE - FACTURA CONTADO</td></tr>";
        $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscadorProductoInv', array('grupoid' => $grupoid, 'conceptoid' => $conceptoid, 'rc_concepto' => $dats[$i][rc_concepto_id]));
        $this->salida.="<tr><td align=\"right\" class=\"modulo_list_claro\"><a href=\"$acc\" title=\"ADICIONAR PRODUCTOS DEL INVENTARIOS\">ADICIONAR PRODUCTOS</a>";
        $this->salida.="</td></tr>";
        $this->salida.="</table>";
        $this->salida.="<table  width='45%' align='center' border='0'   cellpading=\"3\" cellspacing=\"3\">";
        $this->salida.=" <tr>";
        $this->salida.=" <td align=\"center\">";
        $acc2 = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarTercero', array('Cajaid' => $Cajaid, 'arx' => $dat));
        $this->salida .='<form name="forma" action=' . $acc2 . ' method="post">';
        $this->salida .="<input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form>";
        $this->salida.=" </td>";
        $this->salida.=" </tr>";
        $this->salida.="</table>";

        $dats = $this->ConsultaInsertadosDetalleInventarios();
        if (!empty($dats)) {
            $this->salida.="<BR><table  align=\"center\" border=\"0\"  width=\"95%\">";
            $this->salida.="<tr class=\"modulo_table_list_title\" align=\"center\">";
            $this->salida.="  <td colspan=8>CONCEPTOS GUARDADOS</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr class=\"modulo_table_list_title\">";
            $this->salida.="  <td width=\"10%\">PRODUCTO</td>";
            $this->salida.="  <td width=\"30%\">DESCRIPCION</td>";
            $this->salida.="  <td width=\"8%\">DESCRIPCION</td>";
            $this->salida.="  <td width=\"7%\">PRECIO</td>";
           // $this->salida.="  <td width=\"7%\">% GRAVAMEN</td>";
            $this->salida.="  <td width=\"8%\">VALOR GRAVAMEN</td>";
            $this->salida.="  <td width=\"15%\">TOTAL</td>";
            $this->salida.="  <td width=\"5%\"></td>";
            $this->salida.="</tr>";
            $_SESSION['CAJA']['SAL'] = 0;
            for ($i = 0; $i < sizeof($dats); $i++) {
                //$grupoid=$dats[$i][codigo_producto];
                $valor_total = $dats[$i][valor_total];
                $porcentaje_gravamen = $dats[$i][porcentaje_gravamen];
                $valor_gravamen = $dats[$i][valor_gravamen];
                $TOTAL+=$dats[$i][valor_total];
                $precio = $dats[$i][precio];
                $cantidad = $dats[$i][cantidad];
                $descripcion = $dats[$i]['descripcion'];
                $conceptoid = $dats[$i][codigo_producto];
                //$concepto=$dats[$i][desconcepto];
                $grupo = $dats[$i][desgrupo];
                //$detalle=$dats[$i][descripcion];
                if (strlen($detalle) > 45) {
                    $detalle = substr($detalle, 0, 45);
                    $detalle.='...';
                }
                if (strlen($desc) > 45) {
                    $desc = substr($desc, 0, 45);
                    $desc.='...';
                }
                //si es credito no debe sumar porque no va a pagar
                if ($_SESSION['CAJA']['FACTURA'] == 'contado') {
                    $_SESSION['CAJA']['SAL'] += $valor_total;
                }
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida.="<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB'); >";
                //$this->salida.="  <td>".$grupoid."-".$grupo."</td>";
                $this->salida.="  <td>" . $conceptoid . "</td>";
                $this->salida.="  <td>" . $grupo . "</td>";
                $this->salida.="  <td>$descripcion</td>";
                $this->salida.="  <td>" . FormatoValor($precio) . "</td>";
               // $this->salida.="  <td>" . $porcentaje_gravamen . "</td>";
                $this->salida.="  <td>" . $valor_gravamen . "</td>";
                $this->salida.="  <td>" . FormatoValor($valor_total) . "</td>";
                $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'EliminarTmpInventario', array('rc_inventario_id' => $dats[$i][rc_inventario_id]));
                $this->salida.="  <td><a href=\"$acc\"><img src=\"" . GetThemePath() . "/images/elimina.png\" TITLE=\"ELIMINAR PRODUCTO\"></a></td>";
                //$this->salida.="  <td><img src=\"".GetThemePath()."/images/checkS.gif\"></td>";
                $subT = $subT + $valor;
                $this->salida.="</tr>";
            }
            $this->salida.="<tr class=\"modulo_table_list_title\" align=\"center\">";
            $this->salida.="<td colspan=\"6\"align=\"right\">TOTAL $ &nbsp;</td>";
            $this->salida.="<td>" . FormatoValor($TOTAL) . "</td>";
            $this->salida.="<td></td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
        }
        //FIN NUEVO PARA LA CAJA DE INVENTARIOS-16122005
        if ($factura == 'contado' AND !empty($dats))
            $this->FormaAbonos($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Cajaid);
        else
        if ($factura == 'credito' AND !empty($dats)) {
            $this->salida.="<table  width='45%' align='center' border='0'   cellpading=\"3\" cellspacing=\"3\">";
            $this->salida.=" <tr>";
            $this->salida.=" <td align=\"center\">";
            $acc1 = ModuloGetURL('app', 'CajaGeneral', 'user', 'VentanaGuardarRecibo', array('Cajaid' => $Cajaid, 'arx' => $dat));
            $this->salida .='<form name="forma" action=' . $acc1 . ' method="post">';
            $this->salida .="<input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Guardar Fact\" class=\"input-submit\"></form>";
            $this->salida.=" </td>";
            $this->salida.=" <td align=\"center\">";
            $acc2 = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarTercero', array('Cajaid' => $Cajaid, 'arx' => $dat));
            $this->salida .='<form name="forma" action=' . $acc2 . ' method="post">';
            $this->salida .="<input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Volver\" class=\"input-submit\"></form>";
            $this->salida.=" </td>";
            $this->salida.=" </tr>";
            $this->salida.="</table>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

//FORMA PARA LA BUSQUEDA DE PRODUCTOS DE INENTARIO
    /**
     * 		BuscadorProductoInv
     *
     *   Funcion que muestra la consulta de los productos en el inventario
     * 		@Author Lorena Arag�n G.
     * 		@access Private
     * 		@return boolean
     */
    function BuscadorProductoInv($NoLiquidacion, $TipoDocumento, $Documento, $nombrePaciente, $cuenta, $ingreso, $codigoBus, $DescripcionBus, $bodega, $ProductosBodega) {
        $this->salida .= ThemeAbrirTabla('BUSCADOR PRODUCTOS INVENTARIOS');
        $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'ProductosInventariosBodega', array("NoLiquidacion" => $NoLiquidacion, "TipoDocumento" => $TipoDocumento, "Documento" => $Documento, "nombrePaciente" => $nombrePaciente, "cuenta" => $cuenta, "ingreso" => $ingreso, "bodega" => $_SESSION['CAJA']['BODEGA']));
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
        //$this->Encabezado();
        $this->EncabezadoEmpresa($_SESSION['CAJA']['CAJAID'], '', $_SESSION['CAJA']['TIPOCUENTA'], $tmp);
        $this->salida .= "    <BR><table width=\"80%\" border=\"0\" align=\"center\">";
        $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PARAMENTROS DE BUSQUEDA</td></tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "     <td class=\"label\">CODIGO</td>";
        $this->salida .= "		  <td><input type=\"text\" class=\"input-text\" name=\"codigoBus\" size=\"10\" value=\"$codigoBus\"></td>";
        $this->salida .= "     <td class=\"label\">DESCRIPCION</td>";
        $this->salida .= "     <td><input size=\"70\" type=\"text\" name=\"DescripcionBus\" value=\"" . $DescripcionBus . "\" class=\"input-submit\"></td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\"><td align=\"center\" colspan=\"4\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
        $this->salida .= "    </td></tr>";
        $this->salida .= "	  </table><BR>";
        $this->salida .= "		</form>";
        //$ProductosBodega=$this->ProductosInventariosBodega($codigoBus,$DescripcionBus,$bodega );
        if ($ProductosBodega) {
            $_SESSION['CAJA']['PRODUCTOS'] = $ProductosBodega;
            $actionSelect = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarProductoTmpInventario', array("producto" => $ProductosBodega[$i]['codigo_producto'], "descripcion" => $ProductosBodega[$i]['descripcion'], "existencia" => $ProductosBodega[$i]['existencia'],
                "NoLiquidacion" => $NoLiquidacion, "TipoDocumento" => $TipoDocumento, "Documento" => $Documento, "nombrePaciente" => $nombrePaciente, "cuenta" => $cuenta, "ingreso" => $ingreso));
            $this->salida .= "    <form name=\"forma\" action=\"$actionSelect\" method=\"post\">";
            $this->salida .= "    <table width=\"80%\" border=\"0\" align=\"center\">";
            $this->salida .= "    <tr class=\"modulo_table_list_title\">";
            $this->salida .= "    <td width=\"20%\">CODIGO</td>";
            $this->salida .= "    <td width=\"45%\">DESCRIPCION</td>";
            $this->salida .= "    <td width=\"15%\">EXISTENCIAS</td>";
            $this->salida .= "    <td width=\"14%\">PRECIO VENTA/UNIDAD</td>";
            $this->salida .= "    <td width=\"5%\">%IVA</td>";
            $this->salida .= "    <td width=\"1%\">CANTIDAD</td>";
            //$this->salida .= "    <td width=\"5%\">&nbsp;</td>";
            $this->salida .= "    </tr>";
            for ($i = 0; $i < sizeof($ProductosBodega); $i++) {
                if ($y % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida .= "    <tr class=\"$estilo\">";
                $this->salida .= "    <td>" . $ProductosBodega[$i]['codigo_producto'] . "</td>";
                $this->salida .= "    <td>" . $ProductosBodega[$i]['descripcion'] . "</td>";
                $this->salida .= "    <td>" . $ProductosBodega[$i]['existencia'] . "</td>";
                $this->salida .= "    <td align=\"right\">$" . FormatoValor($ProductosBodega[$i]['precio_venta']) . "</td>";
                $this->salida .= "    <td align=\"right\">" . $ProductosBodega[$i]['porc_iva'] . "</td>";
                $this->salida.="<td width=\"1%\"> <input name=\"producto" . $ProductosBodega[$i]['codigo_producto'] . "\" maxlength='5'  size='5' class=\"input-text\" type=\"text\"></td>";
                //$this->salida .= "    <td align=\"center\" width=\"5%\"><a href=\"$actionSelect\"><img title=\"Seleccionar Producto\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
                $this->salida .= "    </tr>";
                $y++;
            }
            $this->salida .= "    <tr>";
            $this->salida .= "    <td align=\"center\" colspan=\"5\">";
            $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"adicionar\" value=\"ADICIONAR\" class=\"input-submit\">";
            $this->salida .= "    </td>";
            $this->salida .= "   </tr>";
            $this->salida .= "	  </table><BR>";
            $this->salida .= "		</form>";
            //$Paginador = new ClaseHTML();
            $this->actionPaginador = ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamaBuscadorProductoInv', array("NoLiquidacion" => $NoLiquidacion, "TipoDocumento" => $TipoDocumento, "Documento" => $Documento, "nombrePaciente" => $nombrePaciente, "cuenta" => $cuenta, "ingreso" => $ingreso, "codigoBus" => $codigoBus, "DescripcionBus" => $DescripcionBus));
            //$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
        } else {
            $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
            $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
            $this->salida .= "	  </table><BR>";
        }
        //$this->salida .= "		</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

//FIN FORMA PARA LA BUSQUEDA DE PRODUCTOS DE INENTARIO

    /**
     * Se utilizada listar en el combo los diferentes tipos de identificacion de los pacientes
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

    function BuscarBancos($Bancos, $Banco='') {
        for ($i = 0; $i < sizeof($Bancos); $i++) {
            $des = $Bancos[$i][descripcion];
            $cod = $Bancos[$i][banco];
            if ($Bancos[$i][banco] == $Banco) {
                $this->salida .=" <option value=\"$cod\" selected>$des</option>";
            } else {
                $this->salida .=" <option value=\"$cod\">$des</option>";
            }
        }
    }

    function BuscarTarjetas($Tarjetas, $Tarjeta='', $Tipo) {
        for ($i = 0; $i < sizeof($Tarjetas); $i++) {
            $des = $Tarjetas[$i][descripcion];
            $cod = $Tarjetas[$i][tarjeta];
            if ($Tarjetas[$i][sw_tipo] == $Tipo) {
                if ($Tarjetas[$i][tarjeta] == $Tarjeta) {
                    $this->salida .=" <option value=\"$cod\" selected>$des</option>";
                } else {
                    $this->salida .=" <option value=\"$cod\">$des</option>";
                }
            }
        }
    }

    function VentanaGuardarRecibo() {

       
        $Cuenta = $_REQUEST['Cuenta'];
        $spy = $_REQUEST['spy'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Nivel = $_REQUEST['Nivel'];
        $PlanId = $_REQUEST['PlanId'];
        $Pieza = $_REQUEST['Pieza'];
        $Cama = $_REQUEST['Cama'];
        $FechaC = $_REQUEST['FechaC'];
        $Ingreso = $_REQUEST['Ingreso'];
        $banco = $_REQUEST['banco'];
        $girador = $_REQUEST['girador'];
        $cheque = $_REQUEST['cheque'];
        $efectivo = $_REQUEST['efectivo'];
        $tarjetad = $_REQUEST['tarjetad'];
        $tarjetac = $_REQUEST['tarjetac'];
        $obsevacion = $_REQUEST['observacion'];
        $Ttarjeta = $tarjetac + $tarjetad;
        $PagareNumero = $_REQUEST['PagareNumero'];
        $Cajaid = $_REQUEST['Cajaid'];
        $Empresa = $_REQUEST['$Empresa'];
        $Prefijo = $_REQUEST['$Prefijo'];
        $Valor = $_REQUEST['Valor'];
        $NombrePaciente = $_REQUEST['NombrePaciente'];
        $DocumentoId = $_REQUEST['DocumentoId'];
        $PlanId = $_REQUEST['PlanId'];
        $tipoPago = $_REQUEST['tipoPago'];        
        $TotalAbono = $Ttarjeta + $efectivo + $cheque + $_SESSION['CAJA']['BONO'];
      
//'Cuenta'=>$Cuenta,'TipoId'=>$_TipoId,'PacienteId'=>$PacienteId,'PagareNumero'=>$PagareNumero,'Cajaid'=>$Cajaid,'Empresa'=>$Empresa,'Prefijo'=>$Prefijo,'Valor'=>$Valor,'NombrePaciente'=>$NombrePaciente,'DocumentoId'=>$DocumentoId,'PlanId'=>$PlanId

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03' || $_SESSION['CAJA']['TIPOCUENTA'] == '04' || $_SESSION['CAJA']['TIPOCUENTA'] == '05'
                || $_SESSION['CAJA']['TIPOCUENTA'] == '06' || $_SESSION['CAJA']['TIPOCUENTA'] == '08') {
            //$aPagar=$_REQUEST['Cama'];
            $aPagar = $_SESSION['CAJA']['TOTAL'];
            //se incluye los impuestos
            //$aPagar =  $_SESSION['CAJA']['TOTAL_GENERAL'];
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '03' OR $_SESSION['CAJA']['TIPOCUENTA'] == '08') {
                $aPagar = $_SESSION['CAJA']['SAL'];
            }
            
           // echo print_r($_SESSION['CAJA']['PRECREDITO']);
            if (!$aPagar) {
                $Devuelta = 0;  //revisar esto antes tenia -1, ahora se controla con 0 por q se puede pagar asi...con 0
            } else {
                $Devuelta = round($TotalAbono - $aPagar);
            }
            
            $dat = $_REQUEST['arx'];
            $this->salida .= ThemeAbrirTabla('GENERAR RECIBO DE CAJA');
            $this->salida .= " <br><table  border=\"0\"  width=\"65%\" align=\"center\">";
            $this->salida .= "  <tr><td><fieldset><legend class=\"field\"> *l2* PAGO DE CAJA</legend>";
            $this->salida.="<table  width='70%' align='center' border='0'>";
            $this->salida.="<tr><td align='left' colspan=\"2\">";

//            echo'<pre>';
//            print_r($_SESSION['CAJA']['TIPOCUENTA']);
//            echo'<pre>';
//            
//            echo $_SESSION['CAJA']['SAL'];;
     
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                $sql = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
                $ip=$sql->getRealIP();
                 if($ip==""){
                     $this->mensajeDeError = 'LA IP NO TIENE PERMISO PARA REALIZAR LA PETICI�N';
                    return;
                   }
                $total = str_replace(".", "", $aPagar);
                $this->salida .='<form name="recarga" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarConceptos'
                                , array('spy' => 1, 'Cuenta' => $Cuenta, 'Cama' => $Cama, 'PlanId' => $PlanId, 'TipoId' => $TipoId
                            , 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy
                            , 'efectivo' => $efectivo, 'cheque' => $cheque, 'tarjetad' => $tarjetad, 'tarjetac' => $tarjetac
                            , 'Tiponumeracion' => $Tiponumeracion, 'TipoCuenta' => $TipoCuenta, 'Apagar' => $total, 'arx' => $dat, 'aPagar' => $aPagar
                            , 'Devuelta' => $Devuelta, 'observacion' => $observacion, 'tipoPago' => $tipoPago)) . '" method="post">';
            } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '08') {

                $total = str_replace(".", "", $aPagar);
                $this->salida .='<form name="recarga" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarInventarios', array('spy' => 1
                            , 'Cuenta' => $Cuenta, 'Cama' => $Cama, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId
                            , 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'efectivo' => $efectivo
                            , 'cheque' => $cheque, 'tarjetad' => $tarjetad, 'tarjetac' => $tarjetac, 'Tiponumeracion' => $Tiponumeracion
                            , 'TipoCuenta' => $TipoCuenta, 'Apagar' => $total, 'arx' => $dat, 'aPagar' => $aPagar, 'Devuelta' => $Devuelta
                            , 'observacion' => $observacion)) . '" method="post">';
            } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {

                $total = str_replace(".", "", $aPagar);
                $this->salida .='<form name="recarga" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarPagos'
                                , array('spy' => 1, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Cuenta' => $Cuenta, 'Cajaid' => $Cajaid
                            , 'NombrePaciente' => $NombrePaciente, 'PagareNumero' => $PagareNumero, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo
                            , 'Valor' => $Valor, 'DocumentoId' => $DocumentoId, 'efectivo' => $efectivo, 'cheque' => $cheque, 'tarjetad' => $tarjetad
                            , 'tarjetac' => $tarjetac, 'devuelta' => $Devuelta)) . '" method="post">';
            } else if ($_SESSION['CAJA']['TIPOCUENTA'] != '05') {

                $total = str_replace(".", "", $aPagar);
                $this->salida .='<form name="recarga" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarHospitalizacion'
                   , array('spy' => 1, 'Cuenta' => $Cuenta, 'Cama' => $Cama, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId
                            , 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'efectivo' => $efectivo
                            , 'cheque' => $cheque, 'tarjetad' => $tarjetad, 'tarjetac' => $tarjetac, 'Tiponumeracion' => $Tiponumeracion
                            , 'TipoCuenta' => $TipoCuenta, 'Apagar' => $total, 'arx' => $dat)) . '" method="post">';
            } else {

                $total = str_replace(".", "", $aPagar);
                $this->salida .='<form name="recarga" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarHospitalizacion2'
                   , array('spy' => 1, 'Cuenta' => $Cuenta, 'Cama' => $Cama, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId
                            , 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'FechaHoy' => $FechaHoy, 'efectivo' => $efectivo
                            , 'cheque' => $cheque, 'tarjetad' => $tarjetad, 'tarjetac' => $tarjetac, 'Tiponumeracion' => $Tiponumeracion
                            , 'TipoCuenta' => $TipoCuenta, 'Apagar' => $total, 'arx' => $dat)) . '" method="post">';
            }

            $this->salida.="</td></tr>";
            if ($Devuelta >= 0) {
                $this->salida.="<tr><td class=\"label\" align='center' colspan=\"2\">";
                $this->salida.=" Total a Pagar :&nbsp;$aPagar";
                $this->salida.="</td></tr>";
                $this->salida.="<tr><td class=\"label\" align='center' colspan=\"2\">";
                $this->salida.="Cancela :&nbsp;$TotalAbono ";
                $this->salida.="</td></tr>";
                $this->salida.="<tr><td class=\"label_error\"  align='center' colspan=\"2\">";
                $this->salida.="Cambio :&nbsp;" . abs($Devuelta) . " ";
                $this->salida.="</td></tr>";
                $this->salida.="<tr><td colspan=\"2\">&nbsp;&nbsp;</td></tr>";
                $this->salida.=" <tr>";
                $this->salida.=" <td align=\"center\">";
                $this->salida.=" <input name=\"Guardar\" type=\"submit\" class=\"input-submit\"  value=\"Guardar-21-1\"></form>";
                $this->salida.="</form>";
                $this->salida.=" </td>";
                $this->salida.=" <td align=\"center\">";

                if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                    $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaCuentaConceptos'
                                    , array('arx' => $dat)) . '" method="post">';
                } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
                    $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaCuentaExterna'
                                    , array('arx' => $dat)) . '" method="post">';
                } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                    $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'RetornarOrdenServicio') . '" method="post">';
                } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
                    $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaPagares'
                                    , array('Cuenta' => $Cuenta, 'TipoId' => $_TipoId, 'PacienteId' => $PacienteId, 'PagareNumero' => $PagareNumero
                                , 'Cajaid' => $Cajaid, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente
                                , 'DocumentoId' => $DocumentoId, 'PlanId' => $PlanId)) . '" method="post">';
                } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '08') {
                    $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaCuentaInventarios'
                                    , array('arx' => $dat)) . '" method="post">';
                }
                $this->salida .="<input type=\"submit\" align=\"center\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form>";
                $this->salida.=" </td>";
                $this->salida.=" </tr>";
                $this->salida.="</form>";
            } else if ($Devuelta < 0 AND $_SESSION['CAJA']['TIPOCUENTA'] == '06') {
                $this->salida.="<tr><td class=\"label\" align='center' colspan=\"2\">";
                $this->salida.=" Total a Pagar :&nbsp;$TotalAbono";
                $this->salida.="</td></tr>";
                $this->salida.="<tr><td class=\"label\" align='center' colspan=\"2\">";
                $this->salida.="Cancela :&nbsp;$TotalAbono ";
                $this->salida.="</td></tr>";
                $this->salida.="<tr><td colspan=\"2\">&nbsp;&nbsp;</td></tr>";
                $this->salida.=" <tr>";
                $this->salida.=" <td align=\"center\">";
                $this->salida.=" <input name=\"Guardar\" type=\"submit\" class=\"input-submit\"  value=\"Guardar\"></form>";
                $this->salida.="</form>";
                $this->salida.=" </td>";
                $this->salida.=" <td align=\"center\">";
                $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaPagares'
                                , array('Cuenta' => $Cuenta, 'TipoId' => $_TipoId, 'PacienteId' => $PacienteId, 'PagareNumero' => $PagareNumero
                            , 'Cajaid' => $Cajaid, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente
                            , 'DocumentoId' => $DocumentoId, 'PlanId' => $PlanId)) . '" method="post">';
                $this->salida .="<input type=\"submit\" align=\"center\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form>";
                $this->salida.=" </td>";
                $this->salida.=" </tr>";
                $this->salida.="</form>";
            } else {
                $this->salida.="</form>";
                $this->salida.="<tr><td align='center' colspan=\"2\" class=\"label_error\">";
                $this->salida.="EL VALOR QUE USTED PAGO NO CUBRE EL TOTAL DE LA DEUDA";
                $this->salida.="</td></tr>";
                $this->salida.="<tr><td align='center' colspan=\"2\">";
                $this->salida.="</td></tr>";
                $this->salida.=" <tr>";
                $this->salida.="<BR><td align=\"center\">";
                $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'BorrarTemporales'
                                , array('Cuenta' => $Cuenta, 'arx' => $dat)) . '" method="post">';
                $this->salida.="<BR><BR><input name=\"Cancelar\" type=\"submit\" class=\"input-submit\"  value=\"Cancelar Proceso\"></form>";
                $this->salida.="</form>";
                $this->salida.=" </td>";
                $this->salida.=" <td align=\"center\">";
                if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                    $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaCuentaConceptos'
                                , array('arx' => $dat)) . '" method="post">';
                } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
                    $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaCuentaExterna'
                                , array('arx' => $dat)) . '" method="post">';
                } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                    $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'RetornarOrdenServicio') . '" method="post">';
                } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
                    $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaPagares'
                                    , array('Cuenta' => $Cuenta, 'TipoId' => $_TipoId, 'PacienteId' => $PacienteId, 'PagareNumero' => $PagareNumero
                                , 'Cajaid' => $Cajaid, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente
                                , 'DocumentoId' => $DocumentoId, 'PlanId' => $PlanId)) . '" method="post">';
                } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '08') {
                    $this->salida .='<form name="forma" action="' . ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaCuentaInventarios'
                                    , array('arx' => $dat)) . '" method="post">';
                }
                $this->salida .="<BR><BR><input type=\"submit\" align=\"center\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form>";
                $this->salida.=" </td>";
                $this->salida.=" </tr>";
                $this->salida.="</form>";
            }

            $this->salida.="</table>";
            $this->salida .= "          </fieldset></td></tr></table><BR>";
            $this->salida .= ThemeCerrarTabla();
            return true;
        }
    }

    function EncabezadoEmpresa($Caja, $ancho, $tipocuenta, $tmp) {
        $datos = $this->DatosEncabezadoEmpresa($Caja, $tipocuenta, $tmp);
        if (!$Caja) {
            $var = 'CENTRO DE FACTURACION';
        } else {
            $var = 'CAJA';
        }

        if ($ancho) {
            $width = "width=$ancho%";
        } else {
            $width = "width=80%";
        }
        $this->salida .= "<br><table  border=\"0\"  class='modulo_table_title' $width align=\"center\" >";
        $this->salida .= " <tr class='modulo_table_title'>";
        $this->salida .= " <td>EMPRESA</td>";
        $this->salida .= " <td>CENTRO UTILIDAD</td>";
        $this->salida .= " <td>$var</td>";
        $this->salida .= " </tr>";
        $this->salida .= " <tr align=\"center\">";
        $this->salida .= " <td class=\"modulo_list_claro\" >" . $datos[razon_social] . "</td>";
        $this->salida .= " <td class=\"modulo_list_claro\">" . $datos[descripcion] . "</td>";
        $this->salida .= " <td class=\"modulo_list_claro\" >" . $datos[descuenta] . "</td>";
        $this->salida .= " </tr>";
        $this->salida .= " </table>";
        $_SESSION['CAJA']['nomempresa'] = $datos[razon_social];
        $_SESSION['PAGARE']['centro_utilidad'] = $datos[centro_utilidad];
    }

//Menu Caja Conceptos, cuando el usuario autorizado tiene los dos tipos de 
//facturaci�n
    function MenuCajaConceptos($Caja, $empresa, $centro, $tipo, $tipocuenta, $cu='', $factura) {
        UNSET($_SESSION['CIERRE']);
        if (empty($Caja)) {
            $_SESSION['caja']['Caja'] = $_REQUEST['Caja'];
            $Caja = $_REQUEST['Caja'];
            $empresa = $_REQUEST['Empresa'];
            $centro = $_REQUEST['CentroUtilidad'];
            $tipo = $_REQUEST['Tiponumeracion'];
            $tipocuenta = $_REQUEST['TipoCuenta'];
            $factura = $_SESSION['CAJA']['FACTURA'];
            $bodega = $_REQUEST['bodega'];
            $cu = $_REQUEST['CU'];
            if (empty($cu)) {
                $cu = '';
            }
        }
        $factura = $_REQUEST['criterio'];
        /* 						$empresa=$_SESSION['caja']['empresa'];
          $centro=$_SESSION['caja']['centro'];
          $tipo=$_SESSION['caja']['tipo'];
          $tipocuenta=$_SESSION['caja']['tipocuenta'];
          $cu=$_SESSION['caja']['cu']; */
        if ($tipocuenta == '03')
            $this->salida.= ThemeAbrirTabla('MENU DE CAJA CONCEPTOS', '65%');
        else
        if ($tipocuenta == '08')
            $this->salida.= ThemeAbrirTabla('MENU DE CAJA INVENTARIOS', '65%');
        $this->EncabezadoEmpresa($Caja, '', $tipocuenta);

        /* 						$accContado=ModuloGetURL('app','CajaGeneral','user','BusquedaTercer',array('Cajaid'=>$Caja,'Empresa'=>$empresa,'CentroUtilidad'=>$centro,'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta,'factura'=>'contado'));
          $accCredito=ModuloGetURL('app','CajaGeneral','user','BusquedaTercer',array('Cajaid'=>$Caja,'Empresa'=>$empresa,'CentroUtilidad'=>$centro,'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta,'factura'=>'credito')); */
        $accContado = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarTercero', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'factura' => 'contado', 'bodega' => $bodega));
        $accCredito = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarTercero', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'factura' => 'credito', 'bodega' => $bodega));
        //$accCuadre=ModuloGetURL('app','CajaGeneral','user','FrmCuadreCaja',array('Cajaid'=>$Caja,'Empresa'=>$empresa,'CentroUtilidad'=>$centro,'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta,'factura'=>'credito'));
        $ir = ModuloGetURL('app', 'CajaGeneral', 'user', 'IrListadoCierre', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));

        $this->salida .= "             <form name=\"formabuscar\"  method=\"post\">";
        $mostrar = "\n<script language='javascript'>\n";    
        $mostrar.="function mOvr(src,clrOver) {;\n";
        $mostrar.=" if (!src.contains(event.fromElement)) {\n";
        $mostrar.="src.style.cursor = 'hand';\n";
        $mostrar.="src.bgColor = clrOver;\n";
        $mostrar.="}\n";
        $mostrar.="}\n";

        $mostrar.="function mOut(src,clrIn) {\n";
        $mostrar.="if (!src.contains(event.toElement)) {\n";
        $mostrar.="src.style.cursor = 'default';\n";
        $mostrar.="src.bgColor = clrIn;\n";
        $mostrar.="}\n";
        $mostrar.="}\n";
        $mostrar.="function mClk(src) {\n";
        $mostrar.="if(event.srcElement.tagName=='TD'){\n";
        $mostrar.="src.children.tags('A')[0].click();\n";
        $mostrar.="}\n";
        $mostrar.="}\n";
        $mostrar.="</script>\n";
        $this->salida .="$mostrar";

        if ($this->uno == 1) {
            $this->salida .= "<BR><BR><table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
            $this->uno = "";
        }

        $this->salida.="<br><br><table border=\"0\"   align=\"center\"   width=\"50%\" >";
        $this->salida.="<tr align=\"center\">";
        if ($tipocuenta == '03') {
            $cajatipo = 'CONCEPTO';
        } elseif ($tipocuenta == '08') {
            $cajatipo = 'INVENTARIO';
        }
        $this->salida .= "<td  align=\"center\" class=\"modulo_table_title\" >EVENTOS FACTURA $cajatipo</td>";
        $this->salida.="</tr>";
        /* 						$datos=$this->ConsultarConceptos();
          for($i=0;$i<sizeof($datos);$i++)
          {
          $acc.$datos[$i][subgrupo_tarifario_id]=ModuloGetURL('app','CajaGeneral','user','BuscarTercero',array('Cajaid'=>$Caja,'Empresa'=>$empresa,'CentroUtilidad'=>$centro,'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta,'grupo_tarifario'=>$datos[$i][grupo_tarifario_id],'subgrupo_tarifario'=>$datos[$i][subgrupo_tarifario_id]));
          $this->salida.="<tr class=modulo_list_oscuro>";
          $this->salida .= "<td align=\"center\" onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a title=\"".$datos[$i][subgrupo_tarifario_descripcion]."\" href=\"".$acc.$datos[$i][subgrupo_tarifario_id]."\">".$datos[$i][subgrupo_tarifario_descripcion]."</a>&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/plata.png\"";
          $this->salida.="</td>";
          $this->salida.="</tr>";
          } */
        if (empty($factura) and $tipocuenta == '03') {

            //$UsuarioConceptos=$this->TraerUsuariosConceptos();
            $UsuarioCpto = new app_Facturacion_ConceptosHTML();
            $UsuarioConceptos = $UsuarioCpto->TraerUsuarios($Caja);
            
               /* $urlsubirplano  = ModuloGetURL('app', 'CajaGeneral', 'user', 'subirFacturasPorPlano');
                $this->salida.="<tr class=modulo_list_oscuro>";
                $this->salida.="<td align=\"center\" onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a title='AQUI SE PODRAN ELABORAR LAS FACTURAS A CREDITO.' href=\"$urlsubirplano\">SUBIR FACTURAS</a>&nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/plata.png\"";
                $this->salida.="</td>";
                $this->salida.="</tr>";*/ 
            
            
            if ($UsuarioConceptos[sw_credito] == 1 AND $UsuarioConceptos[sw_contado] == 1) {
                $this->salida.="<tr class=modulo_list_oscuro>";
                $this->salida.="<td align=\"center\" onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a title='AQUI SE PODRAN ELABORAR LAS FACTURAS A CREDITO.' href=\"$accCredito\">FACTURAS CREDITO</a>&nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/plata.png\"";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr class=modulo_list_oscuro>";
                $this->salida.="<td align=\"center\" onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a title='AQUI SE PODRAN ELABORAR LAS FACTURAS DE CONTADO.' href=\"$accContado\">FACTURAS CONTADO</a>&nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/plata.png\"";
                $this->salida.="</td>";
                $this->salida.="</tr>";
            } else
            if ($UsuarioConceptos[sw_credito] == 1)
                $factura = 'credito';
            else
            if ($UsuarioConceptos[sw_contado] == 1)
                $factura = 'contado';
            $dat = array('accion' => ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamaFormaMenuFacturacionConceptos', array('empresa' => $empresa)));
            //$dat = array('accion' => ModuloGetURL('app','CajaGeneral','user','FormaBusquedaFacturasConceptos',array('empresa'=>$empresa)));
            $this->salida.=$UsuarioCpto->ManejoFacturasHTML($dat);
        }
        else
        if (empty($factura) and $tipocuenta == '08') {
            $UsuarioConceptos = $this->TraerUsuariosCajaInventarios();
            if ($UsuarioConceptos[sw_credito] == 1 AND $UsuarioConceptos[sw_contado] == 1) {
                $this->salida.="<tr class=modulo_list_oscuro>";
                $this->salida .= "<td align=\"center\" onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a title='AQUI SE PODRAN ELABORAR LAS FACTURAS A CREDITO.' href=\"$accCredito\">FACTURAS CREDITO</a>&nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/plata.png\"";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr class=modulo_list_oscuro>";
                $this->salida .= "<td align=\"center\" onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a title='AQUI SE PODRAN ELABORAR LAS FACTURAS DE CONTADO.' href=\"$accContado\">FACTURAS CONTADO</a>&nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/plata.png\"";
                $this->salida.="</td>";
                $this->salida.="</tr>";
            } else
            if ($UsuarioConceptos[sw_credito] == 1)
                $factura = 'credito';
            else
            if ($UsuarioConceptos[sw_contado] == 1)
                $factura = 'contado';
        }

        if ($factura == 'credito') {
            $this->salida.="<tr class=modulo_list_oscuro>";
            $this->salida .= "<td align=\"center\" onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a title='AQUI SE PODRAN ELABORAR LAS FACTURAS A CREDITO..' href=\"$accCredito\">FACTURAS CREDITO</a>&nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/plata.png\"";
            $this->salida.="</td>";
            $this->salida.="</tr>";
        } else
        if ($factura == 'contado') {
            $this->salida.="<tr class=modulo_list_oscuro>";
            $this->salida .= "<td align=\"center\" onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a title='AQUI SE PODRAN ELABORAR LAS FACTURAS DE CONTADO...' href=\"$accContado\">FACTURAS CONTADO</a>&nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/plata.png\"";
            $this->salida.="</td>";
            $this->salida.="</tr>";
        }
        if ($factura != 'credito') {
            $this->salida.="<tr class=modulo_list_oscuro>";
            $this->salida .= "<td    align=\"center\" onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a TITLE='AQUI SE PODRAN EFECTUAR LOS CUADRES DE CAJA CONCEPTOS' href=\"$ir\">CUADRE DE CAJA</a>&nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/entregabolsa.png\">";
            $this->salida.="</td>";
            $this->salida.="</tr>";
        }
        $this->salida.="</form>";
        $this->salida.="</table>";

        $this->salida .='<form name="format" action=' . ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarPermisosUser') . ' method="post">';
        $this->salida.="<table align='center' border=\"0\"><tr>";
        $this->salida .="<td><br><input type=\"submit\" align=\"left\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form></td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

//FIN - Menu Caja Conceptos, cuando el usuario autorizado tiene los dos tipos de 
//facturaci�n

    function Menu($Caja, $empresa, $centro, $tipo, $tipocuenta, $cu='', $Caja_empresa, $tmp) {
        UNSET($_SESSION['CIERRE']);
        if (empty($Caja)) {
            $_SESSION['caja']['Caja'] = $_REQUEST['Caja'];
            $Caja = $_REQUEST['Caja'];
            $empresa = $_REQUEST['Empresa'];
            $centro = $_REQUEST['CentroUtilidad'];
            $tipo = $_REQUEST['Tiponumeracion'];
            $tipocuenta = $_REQUEST['TipoCuenta'];
            $cu = $_REQUEST['CU'];
            if (empty($cu)) {
                $cu = '';
            }
        }

        $this->salida.= ThemeAbrirTabla('MENU DE CAJA GENERAL ', '65%');
        if (!empty($Caja_empresa))
            $Caja = $Caja_empresa;
        $this->EncabezadoEmpresa($Caja, '', $tipocuenta, $tmp);
        if ($tipocuenta == '01' or $tipocuenta == '02') {
            $accion = ModuloGetURL('app', 'Facturacion', 'user', 'main', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'arreglo' => $tipo, 'TipoCuenta' => $tipocuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas'));
            $ir = ModuloGetURL('app', 'CajaGeneral', 'user', 'IrListadoCierre', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'arreglo' => $tipo, 'TipoCuenta' => $tipocuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas'));
        } elseif ($tipocuenta == '03') {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarTercero', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));
            //$accion=ModuloGetURL('app','CajaGeneral','user','BusquedaTercer',array('Cajaid'=>$Caja,'Empresa'=>$empresa,'CentroUtilidad'=>$centro,'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta));
            $ir = ModuloGetURL('app', 'CajaGeneral', 'user', 'IrListadoCierre', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));
        } elseif ($tipocuenta == '04') {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConsulta', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));
            $ir = ModuloGetURL('app', 'CajaGeneral', 'user', 'IrListadoCierre', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));
        } elseif ($tipocuenta == '05') {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaOrdenes', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'CU' => $cu, 'TipoCuenta' => $tipocuenta));
            $ir = ModuloGetURL('app', 'CajaGeneral', 'user', 'IrListadoCierre', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'CU' => $cu, 'TipoCuenta' => $tipocuenta));
        } elseif ($tipocuenta == '06') {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaPagares', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'CU' => $cu, 'TipoCuenta' => $tipocuenta));
            $ir2 = ModuloGetURL('app', 'CajaGeneral', 'user', 'IrListadoCierre', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'arreglo' => $tipo, 'TipoCuenta' => $tipocuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas'));
            $ir = ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaMetodoBuscarPagare', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'CU' => $cu, 'TipoCuenta' => $tipocuenta));
        }
        $this->salida .= "             <form name=\"formabuscar\"  method=\"post\">";
        $mostrar = "\n<script language='javascript'>\n";
        $mostrar.="function mOvr(src,clrOver) {;\n";
        $mostrar.=" if (!src.contains(event.fromElement)) {\n";
        $mostrar.="src.style.cursor = 'hand';\n";
        $mostrar.="src.bgColor = clrOver;\n";
        $mostrar.="}\n";
        $mostrar.="}\n";

        $mostrar.="function mOut(src,clrIn) {\n";
        $mostrar.="if (!src.contains(event.toElement)) {\n";
        $mostrar.="src.style.cursor = 'default';\n";
        $mostrar.="src.bgColor = clrIn;\n";
        $mostrar.="}\n";
        $mostrar.="}\n";
        $mostrar.="function mClk(src) {\n";
        $mostrar.="if(event.srcElement.tagName=='TD'){\n";
        $mostrar.="src.children.tags('A')[0].click();\n";
        $mostrar.="}\n";
        $mostrar.="}\n";
        $mostrar.="</script>\n";
        $this->salida .="$mostrar";

        if ($this->uno == 1) {
            $this->salida .= "<BR><BR><table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
            $this->uno = "";
        }

        $this->salida.="<br><br><table border=\"0\"   align=\"center\"   width=\"70%\" >";
        $this->salida.="<tr>";
        $this->salida .= "<td  align=\"center\" class=\"modulo_table_title\" >EVENTOS DE LA CAJA GENERAL</td>";
        $this->salida.="</tr>";

        $this->salida.="<tr class=modulo_list_oscuro>";
        //$this->salida.="  <td align=\"center\">";
        if ($tipocuenta != '05' AND $tipocuenta != '06') {
            $this->salida .= "<td align=\"center\" onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a title='AQUI SE PODRAN EFECTUAR LOS DIFERENTES TIPOS DE PAGOS O ABONOS' href=\"$accion\">PAGOS DE CAJA</a>&nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/plata.png\"";
        }
        //$this->salida .='<form name="forma" action="'.$accion.'" method="post">';
        //$this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></td>";
        $this->salida.="</td>";
        $this->salida.="</tr>";

        $this->salida.="<tr class=modulo_list_oscuro>";
        if ($tipocuenta != '06') {
           /* $this->salida .= "<td    align=\"center\" onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a TITLE='AQUI SE PODRAN EFECTUAR LOS CIERRES DE CAJA DEL DIA' href=\"$ir\">CUADRE DE CAJA</a>&nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/entregabolsa.png\">";*/
        }
        $this->salida.="</td>";
//*********
        $this->salida.="<tr class=modulo_list_oscuro>";
        if ($tipocuenta == '06') {
            $this->salida .= "<td    align=\"center\" onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a TITLE='AQUI SE PODRAN BUSCAR LOS PAGARES' href=\"$ir\">PAGOS PAGARES</a>&nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/entregabolsa.png\">";
        }
        $this->salida.="</td>";
        $this->salida.="<tr class=modulo_list_oscuro>";
        if ($tipocuenta == '06') {
            $this->salida .= "<td    align=\"center\" onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a TITLE='AQUI SE PODRAN EFECTUAR LOS CIERRES DE CAJA PAGARES' href=\"$ir2\">CIERRE DE PAGARES</a>&nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/entregabolsa.png\">";
        }
        $this->salida.="</td>";

        $this->salida.="</form>";
        $this->salida.="</table>";

        $this->salida .='<form name="format" action=' . ModuloGetURL('app', 'CajaGeneral', 'user', 'main') . ' method="post">';
        $this->salida.="<table align='center' border=\"0\"><tr>";
        $this->salida .="<td><br><input type=\"submit\" align=\"left\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form></td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

    //FORMA BUSQUEDA PAGARES
    function FormaMetodoBuscarPagare($arr) {
        if (empty($_REQUEST['Cajaid']))
            $_REQUEST['Cajaid'] = $_SESSION['CUENTAS']['CAJA_ID'];
        else
            $_SESSION['CUENTAS']['CAJA_ID'] = $_REQUEST['Cajaid'];

        $Caja = $_REQUEST['Cajaid'];
        IncludeLib("tarifario");
        if (!$Busqueda) {
            $Busqueda = 1;
        }
        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarCriterios', array('Caja' => $Caja, 'arreglo' => $arreglo, 'Empresa' => $Empresa, 'CentroUtilidad' => $CU, 'TipoCuenta' => $TipoCuenta));
        $Empresa = $this->Empresa;
        $CU = $this->CentroUtilidad;
        $this->salida .= "";
        $this->salida .= ThemeAbrirTabla('BUSCAR CUENTA PAGARES');
        $this->EncabezadoEmpresa($Caja);
        $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
        $this->salida .= "</tr>";
        $this->salida .= "<tr class=\"modulo_list_claro\" >";
        $this->salida .= "<td width=\"90%\" >";
        $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
        $this->salida .= "<tr><td>";
        $this->salida .= "<table width=\"100%\" align=\"center\" border=\"0\">";
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        //
        $mostrar2 = "\n<script language='javascript'>\n";
        $mostrar2.="  function cambioHTML(obj,frm){\n";
        $mostrar2.="  if(obj.selectedIndex==1)\n";
        $mostrar2.="  {document.getElementById('cambio').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.evo_oculto.value=this.value' name='evolucion' value=''>\";\n";
        $mostrar2.="   for(i=0;i<frm.elements.length;i++){\n";
        $mostrar2.="     if(frm.elements[i].name=='TipoDocumentop'){\n";
        $mostrar2.="						frm.elements[i].disabled=true\n";
        $mostrar2.="				}\n";
        $mostrar2.="		}\n";
        $mostrar2.="	document.getElementById('cambio1').innerHTML=\"\"\n";
        $mostrar2.="	}\n";

        $mostrar2.="  else if(obj.selectedIndex==2)\n";
        $mostrar2.="  {document.getElementById('cambio').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.ing_oculto.value=this.value' name='Ingreso' value=''>\"\n";
        $mostrar2.="   for(i=0;i<frm.elements.length;i++){\n";
        $mostrar2.="     if(frm.elements[i].name=='TipoDocumentop'){\n";
        $mostrar2.="						frm.elements[i].disabled=true\n";
        $mostrar2.="				}\n";
        $mostrar2.="		}\n";
        $mostrar2.="	document.getElementById('cambio1').innerHTML=\"\"\n";
        $mostrar2.="	}\n";

        $mostrar2.="  else if(obj.selectedIndex==3)\n";
        $mostrar2.="  {document.getElementById('cambio').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.cuenta_oculto.value=this.value' name='Cuenta' value=''>\"\n";
        $mostrar2.="   for(i=0;i<frm.elements.length;i++){\n";
        $mostrar2.="     if(frm.elements[i].name=='TipoDocumentop'){\n";
        $mostrar2.="						frm.elements[i].disabled=true\n";
        $mostrar2.="				}\n";
        $mostrar2.="		}\n";
        $mostrar2.="	document.getElementById('cambio1').innerHTML=\"\"\n";
        $mostrar2.="	}\n";

        $mostrar2.="  else if(obj.selectedIndex==4)\n";
        $mostrar2.="  {document.getElementById('cambio').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.pre_oculto.value=this.value' size='4' name='Factura' value=''> -- <input type='text' class='input-text' OnChange='document.data.fac_oculto.value=this.value' name='factura' value=''>\"\n";
        $mostrar2.="   for(i=0;i<frm.elements.length;i++){\n";
        $mostrar2.="     if(frm.elements[i].name=='TipoDocumentop'){\n";
        $mostrar2.="						frm.elements[i].disabled=true\n";
        $mostrar2.="				}\n";
        $mostrar2.="		}\n";
        $mostrar2.="	document.getElementById('cambio1').innerHTML=\"\"\n";
        $mostrar2.="	}\n";

        $mostrar2.="  else if(obj.selectedIndex==5)\n";
        $mostrar2.="  {document.getElementById('cambio').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.fac_oculto.value=this.value' size='20' name='Nombres' value=''>\"\n";
        $mostrar2.="   for(i=0;i<frm.elements.length;i++){\n";
        $mostrar2.="     if(frm.elements[i].name=='TipoDocumentop'){\n";
        $mostrar2.="						frm.elements[i].disabled=true\n";
        $mostrar2.="				}\n";
        $mostrar2.="		}\n";
        $mostrar2.="	document.getElementById('cambio1').innerHTML=\"\"\n";
        $mostrar2.="	}\n";

        $mostrar2.="  else if(obj.selectedIndex==6)\n";
        $mostrar2.="  {\n";
        $mostrar2.="	document.getElementById('cambio').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.fac_oculto.value=this.value' size='10' name='Documento' value=''>\"\n";
        $mostrar2.="   for(i=0;i<frm.elements.length;i++){\n";
        $mostrar2.="     if(frm.elements[i].name=='TipoDocumentop'){\n";
        $mostrar2.="						frm.elements[i].disabled=false\n";
        $mostrar2.="				}\n";
        $mostrar2.="		}\n";
        $mostrar2.="	}\n";

        $mostrar2.="  else if(obj.selectedIndex==0)\n";
        $mostrar2.="	{";
        $mostrar2.="   for(i=0;i<frm.elements.length;i++){\n";
        $mostrar2.="     if(frm.elements[i].name=='TipoDocumentop'){\n";
        $mostrar2.="						frm.elements[i].disabled=true\n";
        $mostrar2.="				}\n";
        $mostrar2.="		}\n";
        $mostrar2.="		document.getElementById('cambio').innerHTML=\"\"";
        $mostrar2.="	}\n";
        $mostrar2.=" };\n";

        $mostrar2.="</script>\n";
        $this->salida .="$mostrar2";

//
        $backgrounds = array('modulo_list_claro' => '#DDDDDD', 'modulo_list_oscuro' => '#CCCCCC');
        $mostrar3 = "\n<script language='javascript'>\n";
        $mostrar3.="  function cambioHTML2(obj,frm){\n";
        $mostrar3.="  if(obj.selectedIndex==1)\n";
        $mostrar3.="  {document.getElementById('cambio2').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.evo_oculto.value=this.value' name='NombresRes' value=''>\";\n";
        $mostrar3.="   for(i=0;i<frm.elements.length;i++){\n";
        $mostrar3.="     if(frm.elements[i].name=='TipoDocumentoRes'){\n";
        $mostrar3.="						frm.elements[i].disabled=true\n";
        $mostrar3.="				}\n";
        $mostrar3.="		}\n";
        $mostrar3.="	document.getElementById('cambio1').innerHTML=\"\"\n";
        $mostrar3.="	}\n";

        $mostrar3.="  else if(obj.selectedIndex==2)\n";
        $mostrar3.="  {document.getElementById('cambio2').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.ing_oculto.value=this.value' name='DocumentoRes' value=''>\"\n";
        $mostrar3.="   for(i=0;i<frm.elements.length;i++){\n";
        $mostrar3.="     if(frm.elements[i].name=='TipoDocumentoRes'){\n";
        $mostrar3.="						frm.elements[i].disabled=false\n";
        $mostrar3.="				}\n";
        $mostrar3.="		}\n";
        $mostrar3.="	document.getElementById('cambio1').innerHTML=\"\"\n";
        $mostrar3.="	}\n";

        $mostrar3.="  else if(obj.selectedIndex==0)\n";
        $mostrar3.="	{";
        $mostrar3.="   for(i=0;i<frm.elements.length;i++){\n";
        $mostrar3.="     if(frm.elements[i].name=='TipoDocumentoRes'){\n";
        $mostrar3.="						frm.elements[i].disabled=true\n";
        $mostrar3.="				}\n";
        $mostrar3.="		}\n";
        $mostrar3.="		document.getElementById('cambio2').innerHTML=\"\"";
        $mostrar3.="	}\n";

        $mostrar3.=" };\n";
//
        $mostrar3.="function mOvr(src,clrOver) {;\n";
        $mostrar3.="src.style.background = clrOver;\n";
        $mostrar3.="}\n";

        $mostrar3.="function mOut(src,clrIn) {\n";
        $mostrar3.="src.style.background = clrIn;\n";
        $mostrar3.="}\n";

//
        $mostrar3.="</script>\n";
        $this->salida .="$mostrar3";

//			
        //".$_REQUEST['evo_oculto']." ".$_REQUEST['ing_oculto']." ".$_REQUEST['cuenta_oculto']." ".$_REQUEST['pre_oculto']." ".$_REQUEST['fac_oculto']."

        $this->salida .= "<input type=\"hidden\" name=\"evo_oculto\" value=\"\" class=\"input-text\">";
        $this->salida .= "<input type=\"hidden\" name=\"ing_oculto\" value=\"\" class=\"input-text\">";
        $this->salida .= "<input type=\"hidden\" name=\"cuenta_oculto\" value=\"\" class=\"input-text\">";
        $this->salida .= "<input type=\"hidden\" name=\"pre_oculto\" value=\"\" class=\"input-text\">";
        $this->salida .= "<input type=\"hidden\" name=\"fac_oculto\" value=\"\" class=\"input-text\">";

        $this->salida .= "<tr><td class=\"label_mark\" colspan=\"2\">DATOS PACIENTE</td>";
        $this->salida .="</tr>";
        $this->salida .= "<tr><td class=\"label\">OPCION: </td><td><select name=\"parametros\" class=\"select\" OnChange=\"cambioHTML(this, this.form);\">";
        $this->salida .= "<option value=\"0\" selected>--  SELECCIONE --</option>";
        $this->salida .= "<option value=\"1\"> EVOLUCION</option>";
        $this->salida .= "<option value=\"2\"> INGRESO</option>";
        $this->salida .= "<option value=\"3\"> CUENTA</option>";
        $this->salida .= "<option value=\"4\"> FACTURA</option>";
        $this->salida .= "<option value=\"5\"> NOMBRES</option>";
        $this->salida .= "<option value=\"6\"> DOCUMENTO</option>";
        $this->salida .= "</select></td>";
        $this->salida .= "<td>";
        $this->salida .= "<select disabled=\"true\" name=\"TipoDocumentop\" class=\"select\">";
        $this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
        if ($_SESSION['CUENTAS']['TIPOCUENTA'] != '02') {
            $tipo_id = $this->tipo_id_paciente();
            $this->BuscarIdPaciente($tipo_id, '');
        } else {
            $tipo_id_terceros = $this->tipo_id_terceros();
            $this->BuscarIdTerceros($tipo_id_terceros, '');
        }
        $this->salida .= "</select>";
        $this->salida .= "</td><td class=\"label\"><div id=\"cambio\" name=\"valor\"></div></td>";
        $this->salida .= "</tr>";
        //
        /*      $this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
          $this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
          if($_SESSION['CUENTAS']['TIPOCUENTA']!='02')
          {
          $tipo_id=$this->tipo_id_paciente();
          $this->BuscarIdPaciente($tipo_id,'');
          }
          else
          {
          $tipo_id_terceros=$this->tipo_id_terceros();
          $this->BuscarIdTerceros($tipo_id_terceros,'');
          } */
//			s<td class=\"".$this->SetStyle("Pieza")."\">No. PIEZA</td><td><input type=\"text\" class=\"input-text\" name=\"Pieza\" maxlength=\"32\"></td>
// 			$this->salida .= "<tr><td class=\"".$this->SetStyle("Cama")."\">No. CAMA</td><td><input type=\"text\" class=\"input-text\" name=\"Cama\" maxlength=\"32\"></td>";
// 			$this->salida .= "<td class=\"label\">PREFIJO</td><td><input type=\"text\" class=\"input-text\" name=\"prefijo\" maxlength=\"32\"></td></tr>";
// 			$this->salida .= "<tr><td class=\"".$this->SetStyle("Historia")."\">NUMERO HISTORIA</td><td><input type=\"text\" class=\"input-text\" name=\"historia\" maxlength=\"32\"></td>";
//       if($_SESSION['CUENTAS']['TIPOCUENTA']!='02')
//       {
// 					$this->salida .= "<td class=\"label\">DEPARTAMENTO: </td><td><select name=\"Departamento\" class=\"select\">";
// 					$departamento=$this->Departamentos();
// 					$this->BuscarDepartamento($departamento,'','');
// 					$this->salida .= "                  </select></td></tr>";
// 			}

        /* 			$this->salida .= "</select></td>";
          $this->salida .= "<td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
          $this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\" size=\"30\"></td>";
          $this->salida .= "<td class=\"label\">No. CUENTA: </td><td><input type=\"text\" class=\"input-text\" name=\"Cuenta\" maxlength=\"32\"></td></tr>";
          $this->salida .= "<tr><td class=\"label\">No. INGRESO: </td><td><input type=\"text\" class=\"input-text\" name=\"Ingreso\" maxlength=\"32\"></td>";
          $this->salida .= "<td class=\"label\">No. PAGARE: </td><td><input type=\"text\" class=\"input-text\" name=\"Pagare\" maxlength=\"32\"></td>";
          $this->salida .= "</tr>"; */
        $this->salida .= "<tr><td class=\"label_mark\" colspan=\"2\">DATOS RESPONSABLE</td>";
        $this->salida .="</tr>";
        $this->salida .= "<input type=\"hidden\" name=\"evo_oculto\" value=\"\" class=\"input-text\">";
        $this->salida .= "<input type=\"hidden\" name=\"ing_oculto\" value=\"\" class=\"input-text\">";
        $this->salida .= "<input type=\"hidden\" name=\"cuenta_oculto\" value=\"\" class=\"input-text\">";
        $this->salida .= "<input type=\"hidden\" name=\"pre_oculto\" value=\"\" class=\"input-text\">";
        $this->salida .= "<input type=\"hidden\" name=\"fac_oculto\" value=\"\" class=\"input-text\">";

        $this->salida .= "<tr><td class=\"label\">OPCION: </td><td><select name=\"parametrosRes\" class=\"select\" OnChange=\"cambioHTML2(this, this.form);\">";
        $this->salida .= "<option value=\"0\" selected>--  SELECCIONE --</option>";
        $this->salida .= "<option value=\"1\"> NOMBRES</option>";
        $this->salida .= "<option value=\"2\"> DOCUMENTO</option>";
        $this->salida .= "</select></td>";
        $this->salida .= "<td>";
        $this->salida .= "<select disabled=\"true\" name=\"TipoDocumentoRes\" class=\"select\">";
        $this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
        if ($_SESSION['CUENTAS']['TIPOCUENTA'] != '02') {
            $tipo_id = $this->tipo_id_paciente();
            $this->BuscarIdPaciente($tipo_id, '');
        } else {
            $tipo_id_terceros = $this->tipo_id_terceros();
            $this->BuscarIdTerceros($tipo_id_terceros, '');
        }
        $this->salida .= "</select>";
        $this->salida .= "</td><td class=\"label\"><div id=\"cambio2\" name=\"valor\"></div></td>";
        $this->salida .= "</tr>";

        /* 			$this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumentoRes\" class=\"select\">";
          $this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
          if($_SESSION['CUENTAS']['TIPOCUENTA']!='02')
          {
          $tipo_id=$this->tipo_id_paciente();
          $this->BuscarIdPaciente($tipo_id,'');
          }
          else
          {
          $tipo_id_terceros=$this->tipo_id_terceros();
          $this->BuscarIdTerceros($tipo_id_terceros,'');
          }
          $this->salida .= "</select></td>";
          $this->salida .= "<td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"DocumentoResponsable\" maxlength=\"32\"></td></tr>";
          $this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"NombreResponsable\" maxlength=\"64\" size=\"30\"></td></tr>"; */
        $this->salida .= "<tr><td colspan = 4 align=\"center\" >";
        $this->salida .= "<tr><td align=\"right\" colspan = 2 ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
        $this->salida .= "</form>";
        if ($_SESSION['CUENTAS']['CAJA']) {
            $actionM = ModuloGetURL('app', 'CajaGeneral', 'user', 'main');
        } elseif (!empty($_SESSION['CUENTAS']['RETORNO'])) {
            $_SESSION['CUENTAS']['RETORNO']['volver'] = true;
            $Contenedor = $_SESSION['CUENTAS']['RETORNO']['contenedor'];
            $Modulo = $_SESSION['CUENTAS']['RETORNO']['modulo'];
            $Tipo = $_SESSION['CUENTAS']['RETORNO']['tipo'];
            $Metodo = $_SESSION['CUENTAS']['RETORNO']['metodo'];
            $arg = $_SESSION['CUENTAS']['RETORNO']['argumentos'];
            $actionM = ModuloGetURL($Contenedor, $Modulo, $Tipo, $Metodo, $arg);
        } else {
            $actionM = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenudeCaja');
        }
        $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "<td align=\"center\" colspan = 2 ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= "</td></tr></table>";
        $this->salida .= "</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "</table>";
        /* $this->salida .= "       </td>";
          $this->salida .= "    </tr>";
          $this->salida .= "  </table>"; */
        $this->salida .= "            </form>";
        //mensaje
        $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </table>";
        if ($_SESSION['CUENTAS']['TIPOCUENTA'] != '01' || $_SESSION['CUENTAS']['CAJA'] != '01') {
            if ($arr) {
                $this->salida .= "       <br>";
                $this->salida .= "    <table width=\"98%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
                $this->salida .= "        <td width=\"3%\">No. PAGARE</td>";
                $this->salida .= "        <td width=\"5%\">PREFIJO</td>";
                $this->salida .= "        <td width=\"7%\">No CUENTA</td>";
                $this->salida .= "        <td width=\"10%\">IDENTIFICACI�N</td>";
                $this->salida .= "        <td width=\"45%\">PACIENTE</td>";
                $this->salida .= "        <td width=\"5%\">VENCIMIENTO</td>";
                $this->salida .= "        <td width=\"10%\">FORMA PAGO</td>";
                $this->salida .= "        <td width=\"10%\">VALOR</td>";
                $this->salida .= "        <td width=\"5%\">FECHA REGISTRO</td>";
                $this->salida .= "        <td width=\"5%\">OPCI�N</td>";
                /* 							$this->salida .= "        <td>RANGO</td>";
                  $this->salida .= "        <td>FECHA APERTURA</td>";
                  $this->salida .= "        <td>HORA APERTURA</td>";
                  $this->salida .= "        <td>VALOR NO CUBIERTO</td>";
                  $this->salida .= "        <td>TOTAL CUENTA</td>";
                  $this->salida .= "        <td>E</td>";
                  $this->salida .= "        <td></td>"; */
                $this->salida .= "      </tr>";
                for ($i = 0; $i < sizeof($arr); $i++) {
                    $prefijo = $arr[$i][prefijo];
                    $pagarenumero = $arr[$i][numero];
                    $empresa = $arr[$i][empresa_id];
                    $DocumentoId = $arr[$i][documento_id];
                    $numerodecuenta = $arr[$i][numerodecuenta];
                    $datospaciente = $this->DatosPaciente($numerodecuenta);
                    $documentopaciente = $datospaciente[tipo_id_paciente] . ' ' . $datospaciente[paciente_id];
                    $nombrepaciente = $datospaciente[primer_nombre] . ' ' . $datospaciente[segundo_nombre] . ' ' . $datospaciente[primer_apellido] . ' ' . $datospaciente[segundo_apellido];
                    $vencimiento = $arr[$i][vencimiento];
                    $formapago_id = $arr[$i][tipo_forma_pago_id];
                    $formapagodes = $this->FormaPago($formapago_id);
                    $formapago = $formapagodes[descripcion];
                    $valor = $arr[$i][valor];
                    $var = explode(' ', $arr[$i][fecha_registro]);
                    $planid = $arr[$i][plan_id];
                    $fecha_registro = $var[0];
                    /* 									$datos=$this->BuscarPlanes($PlanId,$Ingreso);
                      $Fechas=$this->FechaStamp($Fecha);
                      $Horas=$this->HoraStamp($Fecha); */
                    /* 									if($LinkCargo==1) {$accionHRef=ModuloGetURL('app','Facturacion','user','Cargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Ingreso'=>$Ingreso,'Fecha'=>$Fecha));}
                      else{ $accionHRef=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));} */
                    if ($_SESSION['CUENTAS']['CAJA']) {
                        $accionHRef = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'pagarenumero' => $pagarenumero, 'Pieza' => $Pieza, 'Cama' => $Cama, 'FechaC' => $Fecha, 'Ingreso' => $Ingreso, 'numero' => $_SESSION['CUENTAS']['arreglo'][numero], 'prefijo' => $arreglo[prefijo], 'TipoCuenta' => $_SESSION['CUENTAS']['TIPOCUENTA'], 'Tiponumeracion' => $_SESSION['CUENTAS']['arreglo'][Tiponumeracion], 'Empresa' => $_SESSION['CUENTAS']['EMPRESA'], 'CentroUtilidad' => $_SESSION['CUENTAS']['CENTROUTILIDAD'], 'Cajaid' => $_SESSION['CUENTAS']['CAJA']));
                    } elseif (!empty($_SESSION['CUENTAS']['RETORNO'])) {
                        $Contenedor = $_SESSION['CUENTAS']['RETORNO']['contenedor'];
                        $Modulo = $_SESSION['CUENTAS']['RETORNO']['modulo'];
                        $Tipo = $_SESSION['CUENTAS']['RETORNO']['tipo'];
                        $Metodo = $_SESSION['CUENTAS']['RETORNO']['metodo'];
                        $arg = $_SESSION['CUENTAS']['RETORNO']['argumentos'];
                        $actionM = ModuloGetURL($Contenedor, $Modulo, $Tipo, $Metodo, $arg);
                    }
                    $accionHRef = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaPagares', array('Cuenta' => $numerodecuenta, 'TipoId' => $datospaciente[tipo_id_paciente], 'PacienteId' => $datospaciente[paciente_id], 'NombrePaciente' => $nombrepaciente, 'Empresa' => $empresa, 'Prefijo' => $prefijo, 'PagareNumero' => $pagarenumero, 'Valor' => $valor, 'Cajaid' => $Caja, 'DocumentoId' => $DocumentoId, 'PlanId' => $planid));
                    if ($i % 2) {
                        $estilo = 'modulo_list_claro';
                    } else {
                        $estilo = 'modulo_list_oscuro';
                    }
                    $this->salida .= "      <tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
                    $this->salida .= "        <td align=\"center\">$pagarenumero</td>";
                    $this->salida .= "        <td align=\"center\">	$prefijo</td>";
                    $this->salida .= "        <td align=\"center\">$numerodecuenta</td>";
                    $this->salida .= "        <td align=\"center\">$documentopaciente</td>";
                    $this->salida .= "        <td align=\"center\" >$nombrepaciente</td>";
                    $this->salida .= "        <td align=\"center\">$vencimiento</td>";
                    $this->salida .= "        <td align=\"center\">$formapago</td>";
                    $this->salida .= "        <td align=\"center\">$valor</td>";
                    $this->salida .= "        <td align=\"center\">$fecha_registro</td>";
                    $this->salida .= "        <td align=\"center\"><a href=\"$accionHRef\">VER</a></td>";
                    /* 									$this->salida .= "        <td align=\"center\">".$datos[plan_descripcion]."</td>";
                      $this->salida .= "        <td align=\"center\">$Nivel</td>";
                      $this->salida .= "        <td align=\"center\">$Fechas</td>";
                      $this->salida .= "        <td align=\"center\">$Horas</td>";
                      if($_SESSION['CUENTAS']['SWCUENTAS']=='Cerradas')
                      {
                      $this->salida .= "        <td align=\"center\">".$arr[$i][nombre]."</td>";
                      $this->salida .= "        <td align=\"center\">".$arr[$i][factura]."</td>";
                      }
                      else
                      {
                      $this->salida .= "        <td align=\"center\">".FormatoValor($ValorNo)."</td>";
                      $this->salida .= "        <td align=\"center\">".FormatoValor($Total)."</td>";
                      }
                      $this->salida .= "        <td align=\"center\">".$Estado."</td>";
                      $this->salida .= "        <td align=\"center\"><a href=\"$accionHRef\">VER</a></td>"; */
                    $this->salida .= "      </tr>";
                }//fin for
                $this->salida .= " </table>";
                $this->conteo = $_SESSION['SPY'];
                //$this->salida .=$this->RetornarBarra();
            }//if
            if (!$f) {
                $Pendientes = $this->DatosTmpCuentasPendientes();
                if ($Pendientes && !$Caja) {
                    $this->FormaCuentaPendientes($Pendientes);
                }
            }
        }
        $this->salida .= "<p>&nbsp;</p>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    //FIN FORMA BUSQUEDA PAGARES

    /*
     *
     * funcion que trae los departamentos alos cuales pertenece el cajero,
     * para poder efectuar el cierre de la caja.
     */
//function MenuDpto($Caja,$empresa,$centro,$tipo,$tipocuenta,$cu='',$obs,$criterio)
    function MenuDpto($Caja, $empresa, $centro, $tipo, $tipocuenta, $cu='', $criterio) {
        $dats = $this->TraerDpto($tipocuenta);
        $this->salida.="<center>\n";
        if ($dats) {
            $this->salida .= ThemeMenuAbrirTabla("SELECCION CAJA RAPIDA DEL DEPARTAMENTO PARA CIERRE", "50%");
            for ($i = 0; $i < sizeof($dats); $i++) {

                $dpto = $dats[$i][departamento];
                $desc = strtoupper($dats[$i][descripcion]);
                $centroU = $dats[$i][centro_utilidad];
                $Caja = $dats[$i][caja_id];

                $this->salida.="<table border='0' width='100%'>";
                $this->salida.="	<tr>";
                $this->salida.="		<td align='left' class='normal_10N'>";
                //$this->Busqueda($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta']);
                //$URL=ModuloGetURL('app','CajaGeneral','user','ListadoCerrarCaja',array("Cajaid"=>$Caja,"Empresa"=>$empresa,"dpto"=>$dpto,"CentroUtilidad"=>$centro,"Tiponumeracion"=>$tipo,"TipoCuenta"=>$tipocuenta,"CU"=>$cu));
                $URL = ModuloGetURL('app', 'CajaGeneral', 'user', 'Busqueda', array("Cajaid" => $Caja, "Empresa" => $empresa, "departamento" => $dpto, "CentroUtilidad" => $centroU, "Tiponumeracion" => $tipo, "TipoCuenta" => $tipocuenta, "CU" => $cu, 'criterio' => $criterio));
                $this->salida.="			<img src=\"" . GetThemePath() . "/images/editar.gif\">&nbsp;&nbsp;<a href=\"" . $URL . "\">$desc</a>";
                $this->salida.="	<tr>";
                $this->salida.="		<td align='left'>";
                $this->salida.="			<div class='normal_10_menu' valign='middle'><img src=\"" . GetThemePath() . "/images/flecha_der.gif\" width='10' height='10'>Codigo&nbsp;:" . $dpto . "</div>";
                $this->salida.="		</td>";
                $this->salida.="	</tr>";
                $this->salida.="</table>";
                $this->salida .="<br>";
            }


            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));
            $this->salida.="<table width='100%'>";
            $this->salida.="<tr>";
            $this->salida.="  <td align=\"center\">";
            $this->salida .='<form name="formatop" action=' . $volver . ' method="post">';
            $this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
            $this->salida.="</td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
            $this->salida .= ThemeMenuCerrarTabla();
        } else {
            $this->salida .= ThemeMenuAbrirTabla("PERMISOS DEPARTAMENTOS", "50%");
            $this->salida.="<table  align=\"center\" border=\"0\" width=\"85%\">\n";
            $this->salida.="	<tr>\n";
            $this->salida.="		<td align=\"center\" class=\"label_error\">EL USUARIO NO ESTA ASOCIADO A DEPARTAMENTOS.</td>\n";
            $this->salida.="	</tr>\n";
            $this->salida.="</table>\n";

            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));
            $this->salida.="<table width='100%'>";
            $this->salida.="<tr>";
            $this->salida.="  <td align=\"center\">";
            $this->salida .='<form name="formatop" action=' . $volver . ' method="post">';
            $this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
            $this->salida.="</td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
            $this->salida .= ThemeMenuCerrarTabla();
        }
        //$this->salida.="</center>\n";
        return true;
    }

    //MENU DE BODEGAS QUE ESTAN ASOCIADAS A CAJAS
    function MenuBodega($Caja, $empresa, $centro, $tipo, $tipocuenta, $cu='', $criterio) {
        $dats = $this->TraerBodega($tipocuenta);
        $this->salida.="<center>\n";
        if ($dats) {
            $this->salida .= ThemeMenuAbrirTabla("SELECCION BODEGA DE CAJA", "50%");
            for ($i = 0; $i < sizeof($dats); $i++) {

                $bodega = $dats[$i][bodega];
                $_SESSION['CAJA']['BODEGA'] = $bodega;
                $desc = strtoupper($dats[$i][desbodega]);
                $centroU = $dats[$i][centro_utilidad];
                $Caja = $dats[$i][caja_id];

                $this->salida.="<table border='0' width='100%'>";
                $this->salida.="	<tr>";
                $this->salida.="		<td align='left' class='normal_10N'>";
                //$this->Busqueda($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta']);
                //$URL=ModuloGetURL('app','CajaGeneral','user','ListadoCerrarCaja',array("Cajaid"=>$Caja,"Empresa"=>$empresa,"dpto"=>$dpto,"CentroUtilidad"=>$centro,"Tiponumeracion"=>$tipo,"TipoCuenta"=>$tipocuenta,"CU"=>$cu));
                $URL = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array("Caja" => $Caja, "Empresa" => $empresa, "bodega" => $bodega, "CentroUtilidad" => $centroU, "Tiponumeracion" => $tipo, "TipoCuenta" => $tipocuenta, "CU" => $cu, 'criterio' => $criterio));
                $this->salida.="			<img src=\"" . GetThemePath() . "/images/editar.gif\">&nbsp;&nbsp;<a href=\"" . $URL . "\">$desc</a>";
                $this->salida.="	<tr>";
                $this->salida.="		<td align='left'>";
                $this->salida.="			<div class='normal_10_menu' valign='middle'><img src=\"" . GetThemePath() . "/images/flecha_der.gif\" width='10' height='10'>Codigo&nbsp;Bodega:" . $bodega . "</div>";
                $this->salida.="		</td>";
                $this->salida.="	</tr>";
                $this->salida.="</table>";
                $this->salida .="<br>";
            }


            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));
            $this->salida.="<table width='100%'>";
            $this->salida.="<tr>";
            $this->salida.="  <td align=\"center\">";
            $this->salida .='<form name="formatop" action=' . $volver . ' method="post">';
            $this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
            $this->salida.="</td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
            $this->salida .= ThemeMenuCerrarTabla();
        } else {
            $this->salida .= ThemeMenuAbrirTabla("PERMISOS DEPARTAMENTOS", "50%");
            $this->salida.="<table  align=\"center\" border=\"0\" width=\"85%\">\n";
            $this->salida.="	<tr>\n";
            $this->salida.="		<td align=\"center\" class=\"label_error\">EL USUARIO NO ESTA ASOCIADO A DEPARTAMENTOS.</td>\n";
            $this->salida.="	</tr>\n";
            $this->salida.="</table>\n";

            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));
            $this->salida.="<table width='100%'>";
            $this->salida.="<tr>";
            $this->salida.="  <td align=\"center\">";
            $this->salida .='<form name="formatop" action=' . $volver . ' method="post">';
            $this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
            $this->salida.="</td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
            $this->salida .= ThemeMenuCerrarTabla();
        }
        //$this->salida.="</center>\n";
        return true;
    }

    //MENU DE BODEGAS QUE ESTAN ASOCIADAS A CAJAS
//LISTADO CUADRE CAJA
    /*
     * funcion q revisa los cierres de caja que se van a efectuar el dia de hoy 
     * solo como informativo, se puede escoger el tipo de caja que se va a buscar
     * $sw es para determinar si es facturadora o otra caja
     */
    function BusquedaCajasHoy($vect='', $sw, $Caja, $Empresa, $CentroUtilidad, $arreglo, $TipoCuenta, $CU, $dpto, $caja_emp) {
        UNSET($_SESSION['CIERRE']['ef']);
        UNSET($_SESSION['CIERRE']['che']);
        UNSET($_SESSION['CIERRE']['tar']);
        UNSET($_SESSION['CIERRE']['tbon']);
        UNSET($_SESSION['CIERRE']['ta']);
        UNSET($_SESSION['CIERRE']['totaldev']);
        UNSET($_SESSION['CIERRE']['caja']);
        UNSET($_SESSION['CIERRE']['cont']);
        if (empty($Caja))
            $Caja = $_SESSION['CIERRE']['caja'];
        else
            $_SESSION['CIERRE']['caja'] = $Caja;
        $this->salida.= ThemeAbrirTabla("CUADRE DE CAJAS..");
        //$this->Encabezado();
        $_SESSION['CAJA']['VECT_FACT_HOY'] = $vect;

        //TABLA CUANDO NO HAY DATOS
        if ($vect == 'show') {
            $this->salida.="<table align=\"center\" width='40%' border=\"0\">";
            $this->salida .= "<tr><td  align=\"center\"><label class=label_mark>NO HAY MOVIMIENTOS DE FACTURAS EL DIA DE HOY</label></td></tr>";
            $this->salida.="</table>";
            $this->salida.="<table align=\"center\" width='40%' border=\"0\">";
            if ($TipoCuenta == '03' OR $TipoCuenta == '08')
                $action2 = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $TipoCuenta));
            else
                $action2 = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $arreglo, 'TipoCuenta' => $TipoCuenta, 'CU' => $CU));
            //$action2=ModuloGetURL('app','Control_Cierre','user','RetornarA');
            $this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
            $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
            $this->salida .= "</tr>";
            $this->salida.="</table><br>";
            $this->salida.= ThemeCerrarTabla();
            return true;
        }
        //FIN TABLA NO DATOS
        //$accion=ModuloGetURL('app','CajaGeneral','user','Busqueda');
        $this->salida .= "            <form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
        $_SESSION['CAJA']['SW'] = $sw;
        $_SESSION['CAJA']['DEPTO'] = $dpto;
        //RECIBOS DE CAJA CUADRADOS
        UNSET($_SESSION['CIERRE']['totaldev']);
        if (!empty($vect) AND $vect != 'show') {
            $backgrounds = array('modulo_list_claro' => '#addce3', 'modulo_list_oscuro' => '#CCCCCC');

            $mostrar = "\n<script language='javascript'>\n";
            $mostrar.="function mOvr(src,clrOver) {;\n";
            $mostrar.="src.style.background = clrOver;\n";
            $mostrar.="}\n";

            $mostrar.="function mOut(src,clrIn) {\n";
            $mostrar.="src.style.background = clrIn;\n";
            $mostrar.="}\n";
            $mostrar.="</script>\n";
            $this->salida .="$mostrar";
            $efectivo = $abono = $cheque = $tarjeta = $efectivo = $cont = $bonos = 0;
            /* 				if(!empty($caja_emp))
              $this->EncabezadoEmpresa($caja_emp);
              else */
            $this->EncabezadoEmpresa($Caja, '', $TipoCuenta);
            $this->salida.="<br><br>";
            $_SESSION['CAJA']['CAJAIDCIERRE'] = $Caja;
            $vectcuadre = $this->BusquedaUsuariosCuadrados($Caja, $sw, $dpto, $TipoCuenta);
            $_SESSION['CAJA']['VECT_CIERRE_DE_CAJA'] = $vectcuadre;
            //$arrdevt=0;
            //$_SESSION['CAJA']['VECTOR_CUADRE_DEV1']=0;
            $d = 0;
            for ($i = 0; $i < sizeof($vectcuadre);) {
                $descriptivo_caja = str_replace("CAJA", "", $vectcuadre[$i][descripcion]);
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                $this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"9\">CAJA &nbsp;" . $descriptivo_caja . "&nbsp;-&nbsp;RECIBOS CUADRADOS</td></tr>";
                $this->salida.="<tr class=\"modulo_table_list_title\">";
                $this->salida.="  <td width=\"28%\">Usuario</td>"; //td width=\"40%\"
                $this->salida.="  <td width=\"5%\"># Facturas</td>"; //td width=\"10%\"
                $this->salida.="  <td width=\"10%\">T Efectivo</td>";
                $this->salida.="  <td width=\"10%\">T Cheque</td>";
                $this->salida.="  <td width=\"10%\">T Tarjetas</td>";
                $this->salida.="  <td width=\"10%\">T Bonos</td>";
                $this->salida.="  <td width=\"10%\" >Sub Total</td>";
                //NUEVO DEVOLUCIONES
                $this->salida.="  <td widt$=\"7%\" >Dev</td>";
                $this->salida.="  <td width=\"10%\" >Total</td>";
                $this->salida.="</tr>";
                $k = $i;
                $_SESSION['CIERRE']['cierres'] = $this->TraerCierresCajaCuadrada($vectcuadre[$i][usuario_id], $vectcuadre[$i][caja_id], $sw, $vectcuadre[$i][departamento], $TipoCuenta);
                while ($vectcuadre[$i][descripcion] == $vectcuadre[$k][descripcion]) {
                    $totaldev = 0;
                    if ($i % 2) {
                        $estilo = 'modulo_list_claro';
                    } else {
                        $estilo = 'modulo_list_oscuro';
                    }
                    $this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#fcf3e2');>";
                    $this->salida.="  <td align=\"left\"><div title='Descripcion : " . $vectcuadre[$k][des] . "'><img TITLE='DATOS CUADRADOS'  src=\"" . GetThemePath() . "/images/checkS.gif\" border='0'>&nbsp&nbsp&nbsp;" . $vectcuadre[$k][usuario_id] . "&nbsp;-&nbsp;" . $vectcuadre[$k][nombre] . "</div></td>";
                    if ($sw == 1) { //*****************FACTURAS**************************
                        $arr = $this->TraerTotales($vectcuadre[$k][usuario_id], $vectcuadre[$k][caja_id], $vectcuadre[$k][cierre_caja_id]);
                    } //*****************FACTURAS**************************
                    elseif ($sw == 2) { //*****************RECIBOS DE CAJA CUADRADOS*******************
                        $arr = $this->TraerTotalesRecibosCuadrados($vectcuadre[$k][usuario_id], $vectcuadre[$k][caja_id], $vectcuadre[$k][cierre_caja_id]);
                        //$arr=$this->TraerTotalesRecibos($vect[$k][usuario_id],$vect[$k][caja_id]);
                        //*****************DEVOLUCIONES*******************
                        //$arrdev=$this->TraerTotalesDevolucionesCuadradas($vectcuadre[$k][usuario_id],$vectcuadre[$k][caja_id],$vectcuadre[$k][cierre_caja_id]);
                        $arrdev = $this->TraerDevoluciones($vectcuadre[$k][caja_id], $vectcuadre[$k][usuario_id], $sw, $vectcuadre[$k][cierre_caja_id]);
                        for ($l = 0; $l < sizeof($arrdev); $l++) {
                            $totaldev = $totaldev + $arrdev[$l][total_devolucion];
                            $_SESSION['CAJA']['VECTOR_CUADRE_DEV1'][$d] = $arrdev[$l];
                            $d++;
                        }
                    } //*****************DEVOLUCIONES*******************

                    for ($n = 0; $n < sizeof($arr); $n++) {
                        //ASIGNA LOS VALORES DE LOS RECIBOS DE CAJA NO ANULADOS
                        if ($arr[$n][total_abono] != -1) {
                            $efectivo = $efectivo + $arr[$n][total_efectivo];
                            $cheque = $cheque + $arr[$n][total_cheques];
                            $tarjeta = $tarjeta + $arr[$n][total_tarjetas];
                            //$abono=$abono+$arr[$n][total_abono];
                            $bonos = $bonos + $arr[$n][total_bonos];
                        }
                    }
                    $abono = $abono + ($efectivo + $cheque + $tarjeta + $bonos);
                    $cont = $cont + sizeof($arr);
                    $te = $te + $efectivo;
                    $che = $che + $cheque;
                    $tar = $tar + $tarjeta;
                    $tbon = $tbon + $bonos;
                    $ta = $ta + $abono;
                    //$totaldev=$totaldev+$arrdev[0][total_devolucion];
                    //$_SESSION['CIERRE']['cierres'][$k]=$vectcuadre[$k][cierre_caja_id];
                    if ($sw == 1) {//FACTURAS
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'ListadoCerrarCaja', array('descripcion' => $vectcuadre[$k][descripcion], 'sw_recibo' => 1, 'Caja' => $vectcuadre[$k][caja_id], "Empresa" => $Empresa, "dpto" => $dpto, 'usuario_id' => $vectcuadre[$k][usuario_id], 'dpto' => $vectcuadre[$k][departamento], "CentroUtilidad" => $CentroUtilidad, "TipoCuenta" => $TipoCuenta, 'sw_cuadrada' => '1', 'cierre' => $vectcuadre[$k][cierre_caja_id], 'criterio' => $sw, "Caja_empresa" => $Caja));
                        /* 							$accion=ModuloGetURL('app','CajaGeneral','user','RevisarFacturasHoy',
                          array('descripcion'=>$vectcuadre[$k][descripcion],'sw_recibo'=>1,'caja'=>$vectcuadre[$k][caja_id],'id'=>$vectcuadre[$k][usuario_id],'dpto'=>$vectcuadre[$k][departamento],)); */
                    } elseif ($sw == 2) {//RECIBOS DE CAJA
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'ListadoCerrarCaja', array("Caja" => $Caja, "Empresa" => $Empresa, "dpto" => $dpto, "CentroUtilidad" => $CentroUtilidad, "Tiponumeracion" => $tipo, "TipoCuenta" => $TipoCuenta, "CU" => $CU, 'usuario_id' => $vectcuadre[$k][usuario_id], 'sw_cuadrada' => '1', 'cierre' => $vectcuadre[$k][cierre_caja_id], 'criterio' => $sw));
                        //$accion=ModuloGetURL('app','Control_Cierre','user','RevisarRecibosHoy',
                        //array('descripcion'=>$vect[$k][descripcion],'sw_recibo'=>2,'caja'=>$vect[$k][caja_id],'id'=>$vect[$k][usuario_id]));
                    }
                    $this->salida.="  <td><a href='$accion'>[ver..]</a></td>";
                    $this->salida.="  <td align=\"right\">" . FormatoValor($efectivo) . "</td>";
                    $this->salida.="  <td align=\"right\">" . FormatoValor($cheque) . "</td>";
                    $this->salida.="  <td align=\"right\">" . FormatoValor($tarjeta) . "</td>";
                    $this->salida.="  <td align=\"right\">" . FormatoValor($bonos) . "</td>";
                    $this->salida.="  <td align=\"right\">" . FormatoValor($abono) . "</td>";
                    //DEVOLUCIONES
                    $this->salida.="  <td align=\"right\">" . FormatoValor($totaldev) . "</td>";
                    $this->salida.="  <td align=\"right\">" . FormatoValor($abono - $totaldev) . "</td>";
                    $this->salida.="</tr>";
                    $totaldevabono = $totaldevabono + ($abono - $totaldev);
                    $_SESSION['CIERRE']['totaldev']+=$totaldev;
                    UNSET($total);
                    UNSET($cheque);
                    UNSET($tarjeta);
                    UNSET($efectivo);
                    UNSET($abono);
                    UNSET($bonos);
                    $k++;
                }
                $_SESSION['CIERRE']['ef']+=$te;
                $_SESSION['CIERRE']['che']+=$che;
                $_SESSION['CIERRE']['tar']+=$tar;
                $_SESSION['CIERRE']['tbon']+=$tbon;
                $_SESSION['CIERRE']['ta']+=$ta;
                $_SESSION['CIERRE']['caja'] = $Caja;
                $_SESSION['CIERRE']['cont'] = $cont;
                $this->salida.="<tr class=\"$estilo\" >";
                /* 					$this->salida.=" <td align=\"right\"><label class='label_mark'>Totales Caja :</label>
                  </td><td><label class='label_mark'>$cont</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($te)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($che)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($tar)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($tbon)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($ta)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($totaldev)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($ta-$totaldev)."</label></td>"; */
                /* 					$this->salida.="<tr class=\"$estilo\" >";
                  $this->salida.=" <td colspan='9' align=\"left\"><label class='label_mark'>Estado:</label>";
                  $this->salida.=" <img TITLE='RECIBOS CUADRADOS'  src=\"". GetThemePath() ."/images/caja_cerrada.png\" border='0' width=32 height=32 ></td>";
                  $this->salida.="</tr>"; */
                unset($cont);
                unset($te);
                unset($ta);
                unset($tar);
                unset($che);
                unset($tbon);
                unset($totaldev);
                $this->salida.="</tr>";
                $i = $k;
//********************************
                $this->salida.="</table>";
//********************************
                $this->salida .="</form>";
            }
        }
//FIN RECIBOS DE CAJA CUADRADOS
//*****************************************
//*****************************************
//*****************************************
//RECIBOS DE CAJA SIN CUADRAR
        $UserDescuadrado = $this->BusquedaUsuariosDesCuadrados($Caja, $sw, $dpto, $TipoCuenta);
        if (!empty($UserDescuadrado))
            $vect = $UserDescuadrado;
        else
            $vect = '';
        if (!empty($vect) AND $vect != 'show') {
            $backgrounds = array('modulo_list_claro' => '#f7f7ff', 'modulo_list_oscuro' => '#CCCCCC');

            $mostrar = "\n<script language='javascript'>\n";
            $mostrar.="function mOvr(src,clrOver) {;\n";
            $mostrar.="src.style.background = clrOver;\n";
            $mostrar.="}\n";

            $mostrar.="function mOut(src,clrIn) {\n";
            $mostrar.="src.style.background = clrIn;\n";
            $mostrar.="}\n";
            $mostrar.="</script>\n";
            $this->salida .="$mostrar";
            $abono = $cheque = $tarjeta = $efectivo = $cont = 0;
            for ($i = 0; $i < sizeof($vect);) {
                $descriptivo_caja = str_replace("CAJA", "", $vect[$i][descripcion]);
//********************************
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
//********************************
                if (sizeof($vectcuadre) == 0) {
                    $this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"9\">CAJA &nbsp;" . $descriptivo_caja . "&nbsp;-&nbsp;RECIBOS SIN CUADRAR</td></tr>";
                    $this->salida.="<tr class=\"modulo_table_list_title\">";
                    $this->salida.="  <td width=\"28%\">Usuario</td>"; //td width=\"40%\"
                    $this->salida.="  <td width=\"5%\"># Facturas</td>"; //td width=\"10%\"
                    $this->salida.="  <td width=\"10%\">T Efectivo</td>";
                    $this->salida.="  <td width=\"10%\">T Cheque</td>";
                    $this->salida.="  <td width=\"10%\">T Tarjetas</td>";
                    $this->salida.="  <td width=\"10%\">T Bonos</td>";
                    $this->salida.="  <td width=\"10%\" >Sub Total</td>";
                    //NUEVO DEVOLUCIONES
                    $this->salida.="  <td width=\"7%\" >Dev</td>";
                    $this->salida.="  <td width=\"10%\" >Total</td>";
                    $this->salida.="</tr>";
                }
                $k = $i;

                $sincuadrar = false;
                while ($vect[$i][descripcion] == $vect[$k][descripcion]) {
                    if ($i % 2) {
                        $estilo = 'modulo_list_claro';
                    } else {
                        $estilo = 'modulo_list_oscuro';
                    }
                    $this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#52f3ff');>";
                    $this->salida.="  <td align=\"left\" width=\"28%\"><div title='Descripcion : " . $vect[$k][des] . "'><img TITLE='DATOS SIN CUADRADRAR'  src=\"" . GetThemePath() . "/images/checkN.gif\" border='0'>&nbsp&nbsp&nbsp;" . $vect[$k][usuario_id] . "&nbsp;-&nbsp;" . $vect[$k][nombre] . "</div></td>";
                    if ($sw == 1) { //*****************FACTURAS**************************
                        $arr = $this->TraerTotales($vect[$k][usuario_id], $vect[$k][caja_id]);
                    } //*****************FACTURAS**************************
                    elseif ($sw == 2) { //*****************RECIBOS DE CAJA*******************
                        //$arr=$this->TraerTotalesRecibosCuadrados($vect[$k][usuario_id],$vect[$k][caja_id]);
                        $arr = $this->TraerTotalesRecibos($vect[$k][usuario_id], $vect[$k][caja_id], $TipoCuenta);
                        //*****************RECIBOS DE CAJA*******************
                        //*****************DEVOLUCIONES*******************
                        //$arrdev=$this->TraerTotalesDevoluciones($vect[$k][usuario_id],$vect[$k][caja_id]);
                        $arrdev = $this->TraerDevoluciones($vect[$k][caja_id], $vect[$k][usuario_id], ''); //DOCUMENTOS SIN CUADRAR
                        $totaldev = 0;
                        for ($l = 0; $l < sizeof($arrdev); $l++) {
                            $totaldev = $totaldev + $arrdev[$l][total_devolucion];
                        }
                    } //*****************DEVOLUCIONES*******************
                    $_SESSION['CAJA']['VECT_CUADRE_DE_CAJA'] = $vect;
                    $_SESSION['CAJA']['VECTOR_CUADRE_DEV1'] = $arrdev;
                    for ($n = 0; $n < sizeof($arr); $n++) {
                        $sincuadrar = true;
                        if ($arr[$n][total_abono] != -1) {
                            $abono = $abono + $arr[$n][total_abono];
                            $efectivo = $efectivo + $arr[$n][total_efectivo];
                            $cheque = $cheque + $arr[$n][total_cheques];
                            $tarjeta = $tarjeta + $arr[$n][total_tarjetas];
                            $bonos = $bonos + $arr[$n][total_bonos];
                        }
                    }
                    $cont = $cont + sizeof($arr);
                    $te = $te + $efectivo;
                    $che = $che + $cheque;
                    $tar = $tar + $tarjeta;
                    $tbon = $tbon + $bonos;
                    $ta = $ta + $abono;
                    //$totaldev=$totaldev+$arrdev[0][total_devolucion];

                    if ($sw == 1) {//*****************FACTURAS**************************
//							$accion=ModuloGetURL('app','CajaGeneral','user','ListadoCerrarCaja',
//							array('descripcion'=>$vectcuadre[$k][descripcion],'sw_recibo'=>1,'Caja'=>$vectcuadre[$k][caja_id],"Empresa"=>$Empresa,"dpto"=>$dpto,'usuario_id'=>$vectcuadre[$k][usuario_id],'dpto'=>$vectcuadre[$k][departamento],"CentroUtilidad"=>$CentroUtilidad,"TipoCuenta"=>$TipoCuenta,'sw_cuadrada'=>'1','cierre'=>$vectcuadre[$k][cierre_caja_id],'criterio'=>$sw));
                        $acc1 = ModuloGetURL('app', 'CajaGeneral', 'user', 'ListadoCerrarCaja', array('descripcion' => $vect[$k][descripcion], 'Caja' => $vect[$k][caja_id], "Empresa" => $Empresa, "dpto" => $dpto, 'usuario_id' => $vect[$k][usuario_id], 'dpto' => $vect[$k][departamento], "CentroUtilidad" => $CentroUtilidad, "TipoCuenta" => $TipoCuenta, 'criterio' => $sw, 'Caja_empresa' => $Caja));
                        /* 								$acc1=ModuloGetURL('app','CajaGeneral','user','RevisarFacturasHoy',
                          array('descripcion'=>$vect[$k][descripcion],'sw_recibo'=>1,'caja'=>$vect[$k][caja_id],'id'=>$vect[$k][usuario_id],'dpto'=>$vect[$k][departamento])); */
                    } elseif ($sw == 2) {//*****************RECIBOS DE CAJA*******************//$Caja,$Empresa,$CentroUtilidad,$arreglo,$TipoCuenta,$CU
                        $acc1 = ModuloGetURL('app', 'CajaGeneral', 'user', 'ListadoCerrarCaja', array("Caja" => $Caja, "Empresa" => $Empresa, "dpto" => $dpto, "CentroUtilidad" => $CentroUtilidad, "Tiponumeracion" => $tipo, "TipoCuenta" => $TipoCuenta, "CU" => $CU, 'usuario_id' => $vect[$k][usuario_id], 'criterio' => $sw));
                        //$accion=ModuloGetURL('app','Control_Cierre','user','RevisarRecibosHoy',
                        //array('descripcion'=>$vect[$k][descripcion],'sw_recibo'=>2,'caja'=>$vect[$k][caja_id],'id'=>$vect[$k][usuario_id]));
                    }
                    $this->salida.="  <td width=\"5%\" align=\"left\"><a href='$acc1'>[" . sizeof($arr) . "]</a></td>";
                    $this->salida.="  <td width=\"10%\" align=\"right\">" . FormatoValor($efectivo) . "</td>";
                    $this->salida.="  <td width=\"10%\" align=\"right\">" . FormatoValor($cheque) . "</td>";
                    $this->salida.="  <td width=\"10%\" align=\"right\">" . FormatoValor($tarjeta) . "</td>";
                    $this->salida.="  <td width=\"10%\" align=\"right\">" . FormatoValor($bonos) . "</td>";
                    $this->salida.="  <td width=\"10%\" align=\"right\">" . FormatoValor($abono) . "</td>";
                    //DEVOLUCIONES
                    $this->salida.="  <td width=\"7%\" align=\"right\">" . FormatoValor($totaldev) . "</td>";
                    $this->salida.="  <td width=\"10%\" align=\"right\">" . FormatoValor($abono - $totaldev) . "</td>";
                    $_SESSION['CIERRE']['totaldev']+=$totaldev;
                    $this->salida.="</tr>";
                    //$totaldevabono=$totaldevabono+($abono-$arrdev[0][total_devolucion]);
                    $k++;
                    unset($total);
                    unset($cheque);
                    unset($tarjeta);
                    unset($efectivo);
                    unset($abono);
                    unset($bonos);
                }
                $this->salida.="<tr class=\"$estilo\" >";
                /* 						$this->salida.=" <td align=\"right\"><label class='label_mark'>Totales Caja :</label>
                  </td><td><label class='label_mark'>$cont</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($te)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($che)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($tar)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($tbon)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($ta)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($totaldev)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($ta-$totaldev)."</label></td>"; */
                /* 						$this->salida.="<tr class=\"$estilo\" >";
                  $this->salida.=" <td colspan='9' align=\"left\"><label class='label_mark'>Estado:</label>";
                  $this->salida.=" <img TITLE='RECIBOS SIN CUADRAR'  src=\"". GetThemePath() ."/images/caja_abierta.png\" border='0' width=32 height=32 ></td>";
                  $this->salida.="</tr>"; */
                $_SESSION['CIERRE']['che']+=$che;
                $_SESSION['CIERRE']['tar']+=$tar;
                $_SESSION['CIERRE']['tbon']+=$tbon;
                $_SESSION['CIERRE']['ta']+=$ta;
                $_SESSION['CIERRE']['caja'] = $Caja;
                $_SESSION['CIERRE']['cont']+=$cont;
                $_SESSION['CIERRE']['ef']+=$te;
                unset($cont);
                unset($te);
                unset($ta);
                unset($tar);
                unset($che);
                unset($tbon);
                unset($totaldev);
                $this->salida.="</tr>";
                $i = $k;
                $this->salida.="</table><br>";
            }
        }
        //**************************************
        //TOTALES EN CAJA CUADRADOS+SIN CUADRAR
        //**************************************
        $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
        //$this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"9\" align=\"center\">TOTALES CAJA</td></tr>";
        $this->salida.="<tr class=\"modulo_table_list_title\">";
        $this->salida.="  <td width=\"28%\">TOTALES</td>"; //td width=\"40%\"
        //$this->salida.="  <td width=\"5%\">".$_SESSION['CIERRE']['cont']."</td>";//td width=\"10%\"
        $this->salida.="  <td width=\"5%\">&nbsp;</td>";
        $this->salida.="  <td width=\"10%\" align=\"right\">$&nbsp;" . FormatoValor($_SESSION['CIERRE']['ef']) . "</td>";
        $this->salida.="  <td width=\"10%\" align=\"right\">$&nbsp;" . FormatoValor($_SESSION['CIERRE']['che']) . "</td>";
        $this->salida.="  <td width=\"10%\" align=\"right\">$&nbsp;" . FormatoValor($_SESSION['CIERRE']['tar']) . "</td>";
        $this->salida.="  <td width=\"10%\" align=\"right\">$&nbsp;" . FormatoValor($_SESSION['CIERRE']['tbon']) . "</td>";
        $this->salida.="  <td width=\"10%\" align=\"right\">$&nbsp;" . FormatoValor($_SESSION['CIERRE']['ta']) . "</td>";
        $this->salida.="  <td width=\"7%\" align=\"right\">$&nbsp;" . FormatoValor($_SESSION['CIERRE']['totaldev']) . "</td>";
        $t = $_SESSION['CIERRE']['ta'] - $_SESSION['CIERRE']['totaldev'];
        $this->salida.="  <td width=\"10%\" align=\"right\">$&nbsp;" . FormatoValor($t) . "</td>";
        $this->salida.="</tr>";
        $this->salida.="<tr class=\"modulo_list_claro\" >";
        if (empty($vect)) {
            //$acc=ModuloGetURL('app','CajaGeneral','user','InsertarCuadreCaja',array('Caja'=>$Caja,'Empresa'=>$Empresa,'CentroUtilidad'=>$CentroUtilidad,'arreglo'=>$tipo,'CU'=>$CU,'TipoCuenta'=>$TipoCuenta,'tef'=>$te,'ttar'=>$tar,'tche'=>$che,'tbon'=>$tbon,'ta'=>$ta,'totaldev'=>$totaldev,'user'=>$vec[0][usuario_id],'tefd'=>$tefd));
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CerrarCaja', array("Caja" => $Caja, "Empresa" => $Empresa, "dpto" => $dpto, "CentroUtilidad" => $CentroUtilidad, 'arreglo' => $tipo, "TipoCuenta" => $TipoCuenta, "CU" => $CU, 'tef' => $te, 'ttar' => $tar, 'tche' => $che, 'tbon' => $tbon, 'ta' => $ta, 'totaldev' => $totaldev, 'user' => $vec[0][usuario_id], 'tefd' => $tefd, 'tbon' => $bonos));
            $this->salida.=" <td colspan='9' align=\"center\"><label class='label_mark'>Cerrar:&nbsp&nbsp</label>";
            $this->salida.="<a href=\"$accion\"><img TITLE='CIERRE DE CAJA'  src=\"" . GetThemePath() . "/images/entregabolsa.png\" border='0' width=20 height=20 ></a></td>";
            $this->salida.="</tr>";
        }
        $this->salida.="</table><br>";
        //******************************************
        //FIN TOTALES EN CAJA CUADRADOS+SIN CUADRAR
        //******************************************
        //
			//VERIFICACION DE USUARIO
        if ($TipoCuenta == '01' or $TipoCuenta == '02') {
            //$accion=ModuloGetURL('app','CajaGeneral','user','InsertarCierreCaja',array('Caja'=>$Caja,'Empresa'=>$vec[0][empresa_id],'CentroUtilidad'=>$vec[0][centro_utilidad],'arreglo'=>$tipo,'TipoCuenta'=>$tipocuenta,'tef'=>$tef,'ttar'=>$ttar,'tche'=>$tche,'user'=>$vec[0][usuario_id],'tefd'=>$tefd));
            $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCuadreCaja', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'CU' => $CU, 'TipoCuenta' => $TipoCuenta, 'tef' => $te, 'ttar' => $tar, 'tche' => $che, 'tbon' => $tbon, 'ta' => $ta, 'totaldev' => $totaldev, 'user' => $vec[0][usuario_id], 'tefd' => $tefd));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'TipoCuenta' => $TipoCuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas'));
        }
        //$Caja,$Empresa,$CentroUtilidad,$arreglo,$TipoCuenta,$CU
        elseif ($TipoCuenta == '03') {//$sw,$Caja,$Empresa,$CentroUtilidad,$arreglo,$TipoCuenta,$CU
            //$acc=ModuloGetURL('app','CajaGeneral','user','InsertarCierreCaja',array('Cajaid'=>$Caja,'Empresa'=>$vec[0][empresa_id],'CentroUtilidad'=>$vec[0][centro_utilidad],'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta,'user'=>$vec[0][usuario_id],'tef'=>$tef,'ttar'=>$ttar,'tche'=>$tche,'tefd'=>$tefd));
            $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCuadreCaja', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $TipoCuenta, 'user' => $vec[0][usuario_id], 'tef' => $tef, 'ttar' => $ttar, 'tche' => $tche, 'tefd' => $tefd));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $TipoCuenta));
        } elseif ($TipoCuenta == '04') {
            //$acc=ModuloGetURL('app','CajaGeneral','user','InsertarCierreCaja',array('Cajaid'=>$Caja,'Empresa'=>$vec[0][empresa_id],'CentroUtilidad'=>$vec[0][centro_utilidad],'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta,'user'=>$vec[0][usuario_id],'tef'=>$tef,'ttar'=>$ttar,'tche'=>$tche,'tefd'=>$tefd));
            $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCuadreCaja', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'CU' => $CU, 'TipoCuenta' => $TipoCuenta, 'tef' => $te, 'ttar' => $tar, 'tche' => $che, 'tbon' => $tbon, 'ta' => $ta, 'totaldev' => $totaldev, 'user' => $vec[0][usuario_id], 'tefd' => $tefd));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $CentroUtilidad, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $TipoCuenta));
        } elseif ($TipoCuenta == '05') {
            //$acc=ModuloGetURL('app','CajaGeneral','user','InsertarCierreCaja',array('Cajaid'=>$Caja,'Empresa'=>$vec[0][empresa_id],'CentroUtilidad'=>$vec[0][centro_utilidad],'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta,'user'=>$vec[0][usuario_id],'tef'=>$tef,'ttar'=>$ttar,'tche'=>$tche,'tefd'=>$tefd));
            $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCuadreCaja', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'CU' => $CU, 'TipoCuenta' => $TipoCuenta, 'tef' => $te, 'ttar' => $tar, 'tche' => $che, 'tbon' => $tbon, 'ta' => $ta, 'totaldev' => $totaldev, 'user' => $vec[0][usuario_id], 'tefd' => $tefd));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $TipoCuenta));
        } elseif ($TipoCuenta == '06') {
            $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCierreCaja', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'TipoCuenta' => $TipoCuenta, 'tef' => $tef, 'ttar' => $ttar, 'tche' => $tche, 'user' => $vec[0][usuario_id], 'tefd' => $tefd));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'TipoCuenta' => $TipoCuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas'));
        } elseif ($TipoCuenta == '08') {//$sw,$Caja,$Empresa,$CentroUtilidad,$arreglo,$TipoCuenta,$CU
            //$acc=ModuloGetURL('app','CajaGeneral','user','InsertarCierreCaja',array('Cajaid'=>$Caja,'Empresa'=>$vec[0][empresa_id],'CentroUtilidad'=>$vec[0][centro_utilidad],'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta,'user'=>$vec[0][usuario_id],'tef'=>$tef,'ttar'=>$ttar,'tche'=>$tche,'tefd'=>$tefd));
            $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCuadreCaja', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $TipoCuenta, 'user' => $vec[0][usuario_id], 'tef' => $tef, 'ttar' => $ttar, 'tche' => $tche, 'tefd' => $tefd));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $TipoCuenta));
        }

        /* 				if(empty($vect))
          {
          $this->salida .='<form name="forma" action='.$acc.' method="post">';
          $this->salida.="<br><br><table border=\"0\"  align=\"center\"   width=\"50%\" >";
          $this->salida .="".$this->SetStyle("MensajeError")."";
          $this->salida.="<tr>";
          $this->salida .= "<td  colspan=\"2\"  align=\"center\" class=\"modulo_table_title\" >Autenticaci�n de Usuario</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr>";
          $this->salida .= "<td   width=\"35%\" align=\"center\" class=\"modulo_list_claro\"\" >Observaciones :</td>";
          $this->salida .= "<td  align=\"center\" class=\"modulo_list_claro\" ><textarea class=\"textarea\"  name=\"observa\"  rows=\"5\"  cols=\"45\" >$obs</textarea></td>";
          $this->salida.="</tr>";
          $this->salida.="<tr>";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida .= "<td   width=\"35%\" align=\"center\" class=\"".$this->SetStyle("usuario")."\">Usuario :</td>";
          $this->salida .= "<td  align=\"center\"><input class=\"input-text\" type=\"text\" align=\"center\" name=\"usuario\"</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida .= "<td   width=\"35%\"  align=\"center\"  class=\"".$this->SetStyle("pass")."\">Password :</td>";
          $this->salida .= "<td  align=\"center\"><input class=\"input-text\" type=\"password\" align=\"center\" name=\"pass\"</td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
          $this->salida.="<tr>";
          $this->salida.="  <td align=\"right\">";
          $this->salida .="<input type=\"submit\" align=\"right\" name=\"Guardar\" value=\"Guardar\" class=\"input-submit\"></form></td>";
          $this->salida.="  <td align=\"left\">";
          $this->salida .='<form name="forma" action='.$volver.' method="post">';
          $this->salida .="<input type=\"submit\" align=\"left\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form></td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
          }
          else
          { */
        $this->salida .="</form>";
        $this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
        $this->salida.="<tr>";
        $this->salida.="  <td align=\"center\">";
        $this->salida .='<form name="forma" action=' . $volver . ' method="post">';
        $this->salida .="<input type=\"submit\" align=\"center\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form></td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        //}
        //FIN VERIFICACION DE USUARIO
        /*         * Parte de volver* */
        /* 				$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
          $action2=ModuloGetURL('app','CajaGeneral','user','Menu',array('caja'=>$caja));
          $action2=ModuloGetURL('app','Control_Cierre','user','RetornarA');
          $this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
          $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
          $this->salida .= "</tr>";
          $this->salida.="</table><br>"; */
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

//FIN LISTADO CUADRE CAJA
//VENTANA AUTENTICACI�N CERRAR CAJA
    function CerrarCaja($Caja, $Empresa, $CentroUtilidad, $arreglo, $TipoCuenta, $CU, $dpto) {
        if (empty($_REQUEST['TipoCuenta'])) {
            $_REQUEST['TipoCuenta'] = $TipoCuenta;
            $_REQUEST['Caja'] = $Caja;
            $_REQUEST['Empresa'] = $Empresa;
            $_REQUEST['CentroUtilidad'] = $CentroUtilidad;
        } else {
            $TipoCuenta = $_REQUEST['TipoCuenta'];
            $Caja = $_REQUEST['Caja'];
            $Empresa = $_REQUEST['Empresa'];
            $CentroUtilidad = $_REQUEST['CentroUtilidad'];
            $dpto = $_REQUEST['dpto'];
        }

        if ($TipoCuenta == '05') {
            $user = $this->UserUltimoCuadre($Caja, $TipoCuenta, $dpto);
        } else {
            $maxfecha = $this->UserUltimoCuadre($Caja, $TipoCuenta, $dpto);
            $user = $this->UltimoCuadre($maxfecha, $TipoCuenta);
        }
        if ($user[usuario_id] != UserGetUID()) {
            if ($TipoCuenta == '01' or $TipoCuenta == '02') {
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'TipoCuenta' => $TipoCuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas'));
            } elseif ($TipoCuenta == '03') {
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $TipoCuenta));
            } elseif ($TipoCuenta == '04') {
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));
            } elseif ($TipoCuenta == '05') {
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $TipoCuenta));
            } elseif ($TipoCuenta == '06') {
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'arreglo' => $tipo, 'TipoCuenta' => $tipocuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas'));
            } elseif ($TipoCuenta == '08') {
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $TipoCuenta));
            }
            $this->FormaMensaje('EL USUARIO QUE DEBE  CERRAR LA CAJA ES ' . $user[usuario_id] . '-' . $user[nombre], 'CONFIRMACION', $action, 'Volver');
            return true;
        }
        $this->salida.= ThemeAbrirTabla("CERRAR CAJA");
        $this->EncabezadoEmpresa($Caja, '', $TipoCuenta);

        //
        //VERIFICACION DE USUARIO
        if ($TipoCuenta == '01' or $TipoCuenta == '02') {
            //$accion=ModuloGetURL('app','CajaGeneral','user','InsertarCierreCaja',array('Caja'=>$Caja,'Empresa'=>$vec[0][empresa_id],'CentroUtilidad'=>$vec[0][centro_utilidad],'arreglo'=>$tipo,'TipoCuenta'=>$tipocuenta,'tef'=>$tef,'ttar'=>$ttar,'tche'=>$tche,'user'=>$vec[0][usuario_id],'tefd'=>$tefd));
            $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCuadreCaja', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'CU' => $CU, 'TipoCuenta' => $TipoCuenta, 'tef' => $te, 'ttar' => $tar, 'tche' => $che, 'tbon' => $tbon, 'ta' => $ta, 'totaldev' => $totaldev, 'user' => $vec[0][usuario_id], 'tefd' => $tefd));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'TipoCuenta' => $TipoCuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas'));
        }
        //$Caja,$Empresa,$CentroUtilidad,$arreglo,$TipoCuenta,$CU
        elseif ($TipoCuenta == '03' OR $TipoCuenta == '08') {//$sw,$Caja,$Empresa,$CentroUtilidad,$arreglo,$TipoCuenta,$CU
            //$acc=ModuloGetURL('app','CajaGeneral','user','InsertarCierreCaja',array('Cajaid'=>$Caja,'Empresa'=>$vec[0][empresa_id],'CentroUtilidad'=>$vec[0][centro_utilidad],'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta,'user'=>$vec[0][usuario_id],'tef'=>$tef,'ttar'=>$ttar,'tche'=>$tche,'tefd'=>$tefd));
            $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCuadreCaja', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'CU' => $CU, 'TipoCuenta' => $TipoCuenta, 'tef' => $te, 'ttar' => $tar, 'tche' => $che, 'tbon' => $tbon, 'ta' => $ta, 'totaldev' => $totaldev, 'user' => $vec[0][usuario_id], 'tefd' => $tefd));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $TipoCuenta));
        } elseif ($TipoCuenta == '04') {
            //$acc=ModuloGetURL('app','CajaGeneral','user','InsertarCierreCaja',array('Cajaid'=>$Caja,'Empresa'=>$vec[0][empresa_id],'CentroUtilidad'=>$vec[0][centro_utilidad],'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta,'user'=>$vec[0][usuario_id],'tef'=>$tef,'ttar'=>$ttar,'tche'=>$tche,'tefd'=>$tefd));
            $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCuadreCaja', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'CU' => $CU, 'TipoCuenta' => $TipoCuenta, 'tef' => $te, 'ttar' => $tar, 'tche' => $che, 'tbon' => $tbon, 'ta' => $ta, 'totaldev' => $totaldev, 'user' => $vec[0][usuario_id], 'tefd' => $tefd));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));
        } elseif ($TipoCuenta == '05') {
            //$acc=ModuloGetURL('app','CajaGeneral','user','InsertarCierreCaja',array('Cajaid'=>$Caja,'Empresa'=>$vec[0][empresa_id],'CentroUtilidad'=>$vec[0][centro_utilidad],'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta,'user'=>$vec[0][usuario_id],'tef'=>$tef,'ttar'=>$ttar,'tche'=>$tche,'tefd'=>$tefd));
            $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCuadreCaja', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'CU' => $CU, 'TipoCuenta' => $TipoCuenta, 'tef' => $te, 'ttar' => $tar, 'tche' => $che, 'tbon' => $tbon, 'ta' => $ta, 'totaldev' => $totaldev, 'user' => $vec[0][usuario_id], 'tefd' => $tefd, 'dpto' => $dpto));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $TipoCuenta));
        } elseif ($TipoCuenta == '06') {
            //$acc=ModuloGetURL('app','CajaGeneral','user','InsertarCierreCaja',array('Caja'=>$Caja,'Empresa'=>$vec[0][empresa_id],'CentroUtilidad'=>$vec[0][centro_utilidad],'arreglo'=>$tipo,'TipoCuenta'=>$tipocuenta,'tef'=>$tef,'ttar'=>$ttar,'tche'=>$tche,'user'=>$vec[0][usuario_id],'tefd'=>$tefd));
            $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCuadreCaja', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'CU' => $CU, 'TipoCuenta' => $TipoCuenta, 'tef' => $te, 'ttar' => $tar, 'tche' => $che, 'tbon' => $tbon, 'ta' => $ta, 'totaldev' => $totaldev, 'user' => $vec[0][usuario_id], 'tefd' => $tefd, 'dpto' => $dpto));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'arreglo' => $tipo, 'TipoCuenta' => $TipoCuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas'));
        }

        $this->salida .='<form name="forma" action=' . $acc . ' method="post">';
        $this->salida.="<br><br><table border=\"0\"  align=\"center\"   width=\"50%\" >";
        $this->salida .="" . $this->SetStyle("MensajeError") . "";
        $this->salida.="<tr>";
        $this->salida .= "<td  colspan=\"2\"  align=\"center\" class=\"modulo_table_title\" >Autenticaci�n de Usuario</td>";
        $this->salida.="</tr>";
        $this->salida.="<tr>";
        $this->salida .= "<td   width=\"35%\" align=\"center\" class=\"modulo_list_claro\"\" >Observaciones :</td>";
        $this->salida .= "<td  align=\"center\" class=\"modulo_list_claro\" ><textarea class=\"textarea\"  name=\"observa\"  rows=\"5\"  cols=\"45\" >$obs</textarea></td>";
        $this->salida.="</tr>";
        $this->salida.="<tr>";
        $this->salida.="<tr class=\"modulo_list_claro\">";
        $this->salida .= "<td   width=\"35%\" align=\"center\" class=\"" . $this->SetStyle("usuario") . "\">Usuario :</td>";
        $this->salida .= "<td  align=\"center\"><input class=\"input-text\" type=\"text\" align=\"center\" name=\"usuario\"</td>";
        $this->salida.="</tr>";
        $this->salida.="<tr class=\"modulo_list_claro\">";
        $this->salida .= "<td   width=\"35%\"  align=\"center\"  class=\"" . $this->SetStyle("pass") . "\">Password :</td>";
        $this->salida .= "<td  align=\"center\"><input class=\"input-text\" type=\"password\" align=\"center\" name=\"pass\"</td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
        $this->salida.="<tr>";
        $this->salida.="  <td align=\"right\">";
        $this->salida .="<input type=\"submit\" align=\"right\" name=\"Guardar\" value=\"Guardar\" class=\"input-submit\"></form></td>";
        $this->salida.="  <td align=\"left\">";
        $this->salida .='<form name="forma" action=' . $volver . ' method="post">';
        $this->salida .="<input type=\"submit\" align=\"left\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form></td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

//FIN LISTADO CUADRE CAJA
//FIN VENTANA CUTENTICACION CERRAR CAJA
//RECIBOS DE CAJA SIN CUADRAR POR USUARIOS DISTINTOS AL ACTUAL
    function FrmRecibosCajaSinCuadreHoy($vect='', $sw, $Caja, $Empresa, $CentroUtilidad, $arreglo, $TipoCuenta, $CU) {
        $this->salida.= ThemeAbrirTabla("CUADRE DE CAJAS");
        /* 				//$this->Encabezado();
          $_SESSION['CAJA']['VECT_FACT_HOY']=$vect;
          $accion=ModuloGetURL('app','CajaGeneral','user','Busqueda');
          $this->salida .= "            <form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
          $this->EncabezadoEmpresa($Caja);
          $this->salida.="<br><br>"; */
//*****************************************
//RECIBOS DE CAJA SIN CUADRAR
        if (!empty($vect) AND $vect != 'show') {
            $backgrounds = array('modulo_list_claro' => '#f63817', 'modulo_list_oscuro' => '#CCCCCC');

            $mostrar = "\n<script language='javascript'>\n";
            $mostrar.="function mOvr(src,clrOver) {;\n";
            $mostrar.="src.style.background = clrOver;\n";
            $mostrar.="}\n";

            $mostrar.="function mOut(src,clrIn) {\n";
            $mostrar.="src.style.background = clrIn;\n";
            $mostrar.="}\n";
            $mostrar.="</script>\n";
            $this->salida .="$mostrar";
            $efectivo = $abono = $cheque = $tarjeta = $efectivo = $cont = 0;
            $this->EncabezadoEmpresa($Caja);
            if ($this->uno == 1) {
                $this->salida .= "<BR><BR><table border=\"0\" width=\"100%\" align=\"center\">";
                $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "      </table><br>";
                $this->uno = "";
            }
            for ($i = 0; $i < sizeof($vect);) {
                $descriptivo_caja = str_replace("CAJA", "", $vect[$i][descripcion]);
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                $this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"9\">CAJA &nbsp;" . $descriptivo_caja . "&nbsp;-&nbsp;RECIBOS SIN CUADRAR</td></tr>";
                $this->salida.="<tr class=\"modulo_table_list_title\">";
                $this->salida.="  <td width=\"28%\">Usuario</td>"; //td width=\"40%\"
                $this->salida.="  <td width=\"5%\"># Facturas</td>"; //td width=\"10%\"
                $this->salida.="  <td width=\"10%\">T Efectivo</td>";
                $this->salida.="  <td width=\"10%\">T Cheque</td>";
                $this->salida.="  <td width=\"10%\">T Tarjetas</td>";
                $this->salida.="  <td width=\"10%\">T Bonos</td>";
                $this->salida.="  <td width=\"10%\" >Sub Total</td>";
                //NUEVO DEVOLUCIONES
                $this->salida.="  <td width=\"7%\" >Dev</td>";
                $this->salida.="  <td width=\"10%\" >Total</td>";
                $this->salida.="</tr>";
                $k = $i;

                while ($vect[$i][descripcion] == $vect[$k][descripcion]) {
                    if ($i % 2) {
                        $estilo = 'modulo_list_claro';
                    } else {
                        $estilo = 'modulo_list_oscuro';
                    }
                    $this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#52f3ff');>";
                    $this->salida.="  <td align=\"left\"><div title='Descripcion : " . $vect[$k][des] . "'>" . $vect[$k][usuario_id] . "&nbsp;-&nbsp;" . $vect[$k][nombre] . "</div></td>";
                    if ($sw == 1) { //*****************FACTURAS**************************
                        $arr = $this->TraerTotales($vect[$k][usuario_id], $vect[$k][caja_id]);
                    } //*****************FACTURAS**************************
                    elseif ($sw == 2) { //*****************RECIBOS DE CAJA*******************
                        //$arr=$this->TraerTotalesRecibosCuadrados($vect[$k][usuario_id],$vect[$k][caja_id]);
                        $arr = $this->TraerTotalesRecibos($vect[$k][usuario_id], $vect[$k][caja_id]);
                        //*****************RECIBOS DE CAJA*******************
                        //*****************DEVOLUCIONES*******************
                        $arrdev = $this->TraerTotalesDevoluciones($vect[$k][usuario_id], $vect[$k][caja_id], 1);
                    } //*****************DEVOLUCIONES*******************
                    for ($n = 0; $n < sizeof($arr); $n++) {
                        $abono = $abono + $arr[$n][total_abono];
                        $efectivo = $efectivo + $arr[$n][total_efectivo];
                        $cheque = $cheque + $arr[$n][total_cheques];
                        $tarjeta = $tarjeta + $arr[$n][total_tarjetas];
                        $bonos = $bonos + $arr[$n][total_bonos];
                    }
                    $cont = $cont + sizeof($arr);
                    $te = $te + $efectivo;
                    $che = $che + $cheque;
                    $tar = $tar + $tarjeta;
                    $tbon = $tbon + $bonos;
                    $ta = $ta + $abono;
                    $totaldev = $totaldev + $arrdev[0][total_devolucion];

                    if ($sw == 1) {
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'RevisarFacturasHoy', array('descripcion' => $vect[$k][descripcion], 'sw_recibo' => 1, 'caja' => $vect[$k][caja_id], 'id' => $vect[$k][usuario_id], 'dpto' => $vect[$k][departamento]));
                    } elseif ($sw == 2) {//$Caja,$Empresa,$CentroUtilidad,$arreglo,$TipoCuenta,$CU
                        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'ListadoCerrarCaja', array("Cajaid" => $Caja, "Empresa" => $Empresa, "dpto" => $dpto, "CentroUtilidad" => $CentroUtilidad, "Tiponumeracion" => $tipo, "TipoCuenta" => $TipoCuenta, "CU" => $CU, 'usuario_id' => $vect[$k][usuario_id]));
                        //$accion=ModuloGetURL('app','Control_Cierre','user','RevisarRecibosHoy',
                        //array('descripcion'=>$vect[$k][descripcion],'sw_recibo'=>2,'caja'=>$vect[$k][caja_id],'id'=>$vect[$k][usuario_id]));
                    }
                    $this->salida.="  <td>[" . sizeof($arr) . "]</td>";
                    $this->salida.="  <td>" . FormatoValor($efectivo) . "</td>";
                    $this->salida.="  <td>" . FormatoValor($cheque) . "</td>";
                    $this->salida.="  <td>" . FormatoValor($tarjeta) . "</td>";
                    $this->salida.="  <td>" . FormatoValor($bonos) . "</td>";
                    $this->salida.="  <td>" . FormatoValor($abono) . "</td>";
                    //DEVOLUCIONES
                    $this->salida.="  <td>" . FormatoValor($arrdev[0][total_devolucion]) . "</td>";
                    $this->salida.="  <td>" . FormatoValor($abono - $arrdev[0][total_devolucion]) . "</td>";
                    $this->salida.="</tr>";
                    //$totaldevabono=$totaldevabono+($abono-$arrdev[0][total_devolucion]);
                    $k++;
                    unset($total);
                    unset($cheque);
                    unset($tarjeta);
                    unset($efectivo);
                    unset($abono);
                    unset($bonos);
                }
                $this->salida.="<tr class=\"$estilo\" >";
                $this->salida.=" <td align=\"right\"><label class='label_mark'>Totales Caja :</label>
									</td><td><label class='label_mark'>$cont</label></td><td><label class='label_mark'>$ &nbsp;" . FormatoValor($te) . "</label></td><td><label class='label_mark'>$ &nbsp;" . FormatoValor($che) . "</label></td><td><label class='label_mark'>$ &nbsp;" . FormatoValor($tar) . "</label></td><td><label class='label_mark'>$ &nbsp;" . FormatoValor($tbon) . "</label></td><td><label class='label_mark'>$ &nbsp;" . FormatoValor($ta) . "</label></td><td><label class='label_mark'>$ &nbsp;" . FormatoValor($totaldev) . "</label></td><td><label class='label_mark'>$ &nbsp;" . FormatoValor($ta - $totaldev) . "</label></td>";
                $this->salida.="<tr class=\"$estilo\" >";
                $this->salida.=" <td colspan='9' align=\"left\"><label class='label_mark'>Estado:</label>";
                $this->salida.=" <img TITLE='RECIBOS SIN CUADRAR'  src=\"" . GetThemePath() . "/images/caja_abierta.png\" border='0' width=32 height=32 ></td>";
                $this->salida.="</tr>";
                unset($cont);
                unset($te);
                unset($ta);
                unset($tar);
                unset($che);
                unset($tbon);
                $this->salida.="</tr>";
                $i = $k;
                $this->salida.="</table><br>";
            }
        } elseif ($vect == 'show') {
            $this->salida.="<table align=\"center\" width='40%' border=\"0\">";
            $this->salida .= "<tr><td  align=\"center\"><label class=label_mark>NO HAY MOVIMIENTOS DE FACTURAS EL DIA DE HOY</label></td></tr>";
            $this->salida.="</table>";
        }

        //VERIFICACION DE USUARIO
        $ip = GetIPAddress();
        if ($TipoCuenta == '01' or $TipoCuenta == '02') {
            //$ip=GetIPAddress();
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCierreCaja', array('Caja' => $Caja, 'Empresa' => $vec[0][empresa_id], 'CentroUtilidad' => $vec[0][centro_utilidad], 'arreglo' => $tipo, 'TipoCuenta' => $tipocuenta, 'tef' => $tef, 'ttar' => $ttar, 'tche' => $tche, 'user' => $vec[0][usuario_id], 'tefd' => $tefd));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarPermisosUser', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'TipoCuenta' => $TipoCuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas', 'ip' => $ip));
        }
        //$Caja,$Empresa,$CentroUtilidad,$arreglo,$TipoCuenta,$CU
        elseif ($TipoCuenta == '03') {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCierreCaja', array('Cajaid' => $Caja, 'Empresa' => $vec[0][empresa_id], 'CentroUtilidad' => $vec[0][centro_utilidad], 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'user' => $vec[0][usuario_id], 'tef' => $tef, 'ttar' => $ttar, 'tche' => $tche, 'tefd' => $tefd));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarPermisosUser', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'TipoCuenta' => $TipoCuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas', 'ip' => $ip));
            //$volver=ModuloGetURL('app','CajaGeneral','user','VolverMenu',array('Cajaid'=>$Caja,'Empresa'=>$empresa,'CentroUtilidad'=>$centro,'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta));
        } elseif ($TipoCuenta == '04') {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCierreCaja', array('Cajaid' => $Caja, 'Empresa' => $vec[0][empresa_id], 'CentroUtilidad' => $vec[0][centro_utilidad], 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'user' => $vec[0][usuario_id], 'tef' => $tef, 'ttar' => $ttar, 'tche' => $tche, 'tefd' => $tefd));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarPermisosUser', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'TipoCuenta' => $TipoCuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas', 'ip' => $ip));
            //$volver=ModuloGetURL('app','CajaGeneral','user','VolverMenu',array('Cajaid'=>$Caja,'Empresa'=>$empresa,'CentroUtilidad'=>$centro,'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta));
        } elseif ($TipoCuenta == '05') {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCierreCaja', array('Cajaid' => $Caja, 'Empresa' => $vec[0][empresa_id], 'CentroUtilidad' => $vec[0][centro_utilidad], 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'user' => $vec[0][usuario_id], 'tef' => $tef, 'ttar' => $ttar, 'tche' => $tche, 'tefd' => $tefd));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarPermisosUser', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'TipoCuenta' => $TipoCuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas', 'ip' => $ip));
            //$volver=ModuloGetURL('app','CajaGeneral','user','VolverMenu',array('Cajaid'=>$Caja,'Empresa'=>$empresa,'CentroUtilidad'=>$centro,'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta));
        } elseif ($TipoCuenta == '06') {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCierreCaja', array('Caja' => $Caja, 'Empresa' => $vec[0][empresa_id], 'CentroUtilidad' => $vec[0][centro_utilidad], 'arreglo' => $tipo, 'TipoCuenta' => $tipocuenta, 'tef' => $tef, 'ttar' => $ttar, 'tche' => $tche, 'user' => $vec[0][usuario_id], 'tefd' => $tefd));
            $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarPermisosUser', array('Caja' => $Caja, 'Empresa' => $Empresa, 'CentroUtilidad' => $CentroUtilidad, 'arreglo' => $tipo, 'TipoCuenta' => $TipoCuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas', 'ip' => $ip));
            //$volver=ModuloGetURL('app','CajaGeneral','user','VolverMenu',array('Caja'=>$Caja,'Empresa'=>$empresa,'CentroUtilidad'=>$centro,'arreglo'=>$tipo,'TipoCuenta'=>$tipocuenta,'facturacion'=>$fact,'CU'=>$cu,'SWCUENTAS'=>'Cuentas'));
        }
// 			$this->salida .='<form name="forma" action='.$accion.' method="post">';
// 			$this->salida.="<br><br><table border=\"0\"  align=\"center\"   width=\"50%\" >";
// 			//$this->salida .="".$this->SetStyle("MensajeError")."";
// 			$this->salida.="<tr>";
// 			$this->salida.= "<td  colspan=\"2\"  align=\"center\" class=\"modulo_table_title\" >Autenticaci�n de Usuario</td>";
// 			$this->salida.="</tr>";
// 			$this->salida.="<tr>";
// 			$this->salida.= "<td   width=\"35%\" align=\"center\" class=\"modulo_list_claro\"\" >Observaciones :</td>";
// 			$this->salida .= "<td  align=\"center\" class=\"modulo_list_claro\" ><textarea class=\"textarea\"  name=\"observa\"  rows=\"5\"  cols=\"45\" >$obs</textarea></td>";
// 			$this->salida.="</tr>";                                                                                                                  
// 			$this->salida.="<tr>";
// 			$this->salida.="<tr class=\"modulo_list_claro\">";
// 			$this->salida .= "<td   width=\"35%\" align=\"center\" class=\"".$this->SetStyle("usuario")."\">Usuario :</td>";
// 			$this->salida .= "<td  align=\"center\"><input class=\"input-text\" type=\"text\" align=\"center\" name=\"usuario\"</td>";
// 			$this->salida.="</tr>";
// 			$this->salida.="<tr class=\"modulo_list_claro\">";
// 			$this->salida .= "<td   width=\"35%\"  align=\"center\"  class=\"".$this->SetStyle("pass")."\">Password :</td>";
// 			$this->salida .= "<td  align=\"center\"><input class=\"input-text\" type=\"password\" align=\"center\" name=\"pass\"</td>";
// 			$this->salida.="</tr>";
// 			$this->salida.="</table>";
// 
// 			$this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
// 			$this->salida.="<tr>";
// 			$this->salida.="  <td align=\"right\">";
// 			$this->salida .="<input type=\"submit\" align=\"right\" name=\"Guardar\" value=\"Guardar\" class=\"input-submit\"></form></td>";
// 			$this->salida.="  <td align=\"left\">";
// 			$this->salida .='<form name="forma" action='.$volver.' method="post">';
// 			$this->salida .="<input type=\"submit\" align=\"left\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form></td>";
// 			$this->salida.="</tr>";
// 			$this->salida.="</table>";

        $this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
        $this->salida.="<tr>";
        $this->salida.="  <td align=\"center\">";
        $this->salida .='<form name="forma" action=' . $volver . ' method="post">';
        $this->salida .="<input type=\"submit\" align=\"center\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form></td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";


        //FIN VERIFICACION DE USUARIO
        /*         * Parte de volver* */
        /* 				$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
          $action2=ModuloGetURL('app','CajaGeneral','user','Menu',array('caja'=>$caja));
          $action2=ModuloGetURL('app','Control_Cierre','user','RetornarA');
          $this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
          $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
          $this->salida .= "</tr>";
          $this->salida.="</table><br>"; */
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

//FIN LISTADO CUADRE CAJA
//FIN RECIBOS DE CAJA SIN CUADRAR POR USUARIOS DISTINTOS AL ACTUAL

    function ListadoCerrarCaja($Caja, $empresa, $centro, $tipo, $tipocuenta, $cu='', $obs, $usuario_id, $Caja_empresa) {

        if (empty($Caja) or empty($tipocuenta)) {
            $Caja = $_REQUEST['Caja'];
            $Caja_empresa = $_REQUEST['Caja_empresa'];
            $empresa = $_REQUEST['Empresa'];
            $centro = $_REQUEST['CentroUtilidad'];
            $tipo = $_REQUEST['Tiponumeracion'];
            $tipocuenta = $_REQUEST['TipoCuenta'];
            $usuario_id = $_REQUEST['usuario_id'];
            //asignamos a esta variable el departamento para realizar el cierre de caja.
            $_SESSION['CAJA']['CIERRE']['DEPTO'] = $_REQUEST['dpto'];
        }

        //IncludeLib("tarifario");
        if ($tipocuenta == '06') {
            $this->salida .= ThemeAbrirTabla('RECIBOS DE CAJA PAGARES PARA CIERRE');
            $vec = $this->TraerReciboPagares($Caja, $usuario_id, $_REQUEST['sw_cuadrada']);
            $_SESSION['PAGARE']['TIPOCUENTA'] = $tipocuenta;
        } else {
            $this->salida .= ThemeAbrirTabla('RECIBOS DE CAJA PARA CIERRE');
            $vec = $this->TraerReciboCaja($Caja, $tipocuenta, $usuario_id, $_REQUEST['sw_cuadrada'], $_REQUEST['cierre']);
        }

        $this->EncabezadoEmpresa($Caja_empresa, '', $tipocuenta);
        $this->salida .= "<br>";
        $this->salida .='<table align="center" width="88%" border="0">';
        $this->salida .='<tr>';
        $this->salida.="<td>";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida.="</td></tr>";
        $this->salida .='</table>';
        if ($vec) {
            $this->salida .= "<br><table  border=\"0\"  bordercolor='#DDDDDD' class=\"modulo_table_list\" width=\"90%\" align=\"center\">";
            $this->salida.="<tr><td class=\"modulo_table_title\">LISTADO DE RECIBOS";
            $this->salida.="</td></tr>";

            /* Esta variable de session atrapa el vector que contiene los
              recibos que van a ser cerrados,cuando generamos el reporte se va
              a mostrar este vector como constancia de que se los anteriores
              recibos de caja han sido cerrados satisfactoriamente
             */
            $_SESSION['CAJA']['VECTOR_CIERRE'] = $vec;
            $this->salida.="<tr><td>";
            $this->salida.="<table  align=\"center\" border=\"3\"  bordercolor='#CCCCCC'  width=\"100%\" >";
            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
            $this->salida.="  <td width=\"9%\">Recibo No.</td>";
            $this->salida.="  <td width=\"10%\">Fecha</td>";
            if ($tipocuenta == '08')
                $this->salida.="  <td >Tercero</td>";
            else
                $this->salida.="  <td >Paciente</td>";
            $this->salida.="  <td >Total Efectivo</td>";
            $this->salida.="  <td >Total Cheque</td>";
            $this->salida.="  <td >Total Tarjetas</td>";
            $this->salida.="  <td >Total Bonos</td>";
            /* if($_SESSION['CAJA']['CIERRE']['DEPTO'])
              {
              $this->salida.="  <td >Descuentos</td>";
              } */
            $this->salida.="  <td >Sub Total</td>";
            $this->salida.="  <td></td>";
            $this->salida.="</tr>";
            $arreglo = array();
            for ($i = 0; $i < sizeof($vec); $i++) {
                $rec = $vec[$i][recibo_caja];
                $pre = $vec[$i][prefijo];
                $fech = $vec[$i][fecha_ingcaja];
                $caja = $vec[$i][caja];
                $ef = $vec[$i][total_efectivo];
                $che = $vec[$i][total_cheques];
                $tar = $vec[$i][total_tarjetas];
                $bon = $vec[$i][total_bonos];
                $su = $vec[$i][suma];
                $arreglo[$i] = $vec[$i][caja_id];
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }
                $this->salida.="<tr class=\"$estilo\" align=\"center\">";
                $this->salida.="  <td>" . $pre . "-" . $rec . "</td>";
                $this->salida.="  <td>$fech</td>";

                if ($_SESSION['CAJA']['CIERRE']['DEPTO'] AND $tipocuenta != '08') {
                    //traemos los pacientes q se pagaron medianate las cajas  rapidas.
                    $this->salida.="  <td>" . $this->TraerPaciente($rec, $pre) . "</td>";
                } else
                if ($tipocuenta == '06') {
                    //traemos los pacientes de caja PAGARES
                    $this->salida.="  <td>" . $this->TraerPacientePagare($rec, $pre) . "</td>";
                } else {
                    //traemos los pacientes de caja general
                    $this->salida.="  <td>" . $this->TraerPacienteCajaGeneral($rec, $pre, $tipocuenta) . "</td>";
                }
                $this->salida.="  <td>" . FormatoValor($ef) . "</td>";
                $this->salida.="  <td>" . FormatoValor($che) . "</td>";
                $this->salida.="  <td>" . FormatoValor($tar) . "</td>";
                $this->salida.="  <td>" . FormatoValor($bon) . "</td>";
                if ($_SESSION['CAJA']['CIERRE']['DEPTO']) {
                    $des = $this->TraerDescuento($vec[$i][numerodecuenta]);
                    //$this->salida.="  <td>".FormatoValor($des)."</td>";
                }
                //RECIBOS DE CAJA NO ANULADOS
                if ($vec[$i][total_abono] == -1) {
                    $this->salida.="  <td><font color='#ff0000'><b><u>ANULADO</u></b></font></td>";
                } else {
                    $this->salida.="  <td>" . FormatoValor($su) . "</td>";
                }
                $this->salida.="  <td><img src=\"" . GetThemePath() . "/images/checkS.gif\"></td>";
                //TOTALES DE RECIBOS DE CAJA NO ANULADOS
                if ($vec[$i][total_abono] != -1) {
                    $subT = $subT + $su;
                    $tef = $tef + $ef;
                    $tche = $tche + $che;
                    $ttar = $ttar + $tar;
                    $tbon = $tbon + $bon;
                    if ($_SESSION['CAJA']['CIERRE']['DEPTO']) {
                        $tdes = $tdes + $des;
                    }
                }
                //FIN TOTALES DE RECIBOS DE CAJA NO ANULADOS
                $this->salida.="</tr>";
            }//FIN FOR
            if ($tipocuenta == '05' OR $tipocuenta == '03' OR $tipocuenta == '08') {
                $_SESSION['CAJA']['CIERRE']['DATOS'] = $arreglo; //tienelos caja_id_para cierre
            }
            $this->salida.="<tr class=\"$estilo\">";
            $this->salida.="<td colspan='8'>&nbsp;</td>";
            $this->salida.="</tr>";
            if ($estilo == 'modulo_list_claro') {
                $estilo = 'modulo_list_oscuro';
            } else {
                $estilo = 'modulo_list_claro';
            }
            $this->salida.="<tr>";
            $moneda = "$ ";
            $this->salida.="<td  class=\"modulo_list_oscuro\"  align=\"right\" colspan='3'>Totales &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda" . FormatoValor($tef) . "</td>";
            $this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda" . FormatoValor($tche) . "</td>";
            $this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda" . FormatoValor($ttar) . "</td>";
            $this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda" . FormatoValor($tbon) . "</td>";

            //if($_SESSION['CAJA']['CIERRE']['DEPTO'])
            //	{	$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdes)."</td>";}
            $this->salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\">" . FormatoValor($subT) . "</td>";
            $this->salida.="<td class=\"modulo_table_list_title\"><img src=\"" . GetThemePath() . "/images/wtarrow.gif\"></td>";
            $this->salida.="</tr>";

            $this->salida.="<tr >";
            $this->salida.="<td colspan='9'>&nbsp;</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr>";
            $this->salida.="  <td colspan='9' align='center' class='label'>Total: &nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/bestell.gif\">" . FormatoValor($subT) . "</td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
            $this->salida.="</td>";
            $this->salida.="</tr>";
            //**************************
            //**************************
            //**************************
            //LISTADO DE DEVOLUCIONES
            //**************************
            //**************************
            //**************************
            if ($tipocuenta != '05' AND $tipocuenta != '03')
                $dev = $this->TraerDevoluciones($Caja, $usuario_id, $_REQUEST['sw_cuadrada'], $_REQUEST['cierre']);
            if ($tipocuenta <> '06' AND sizeof($dev) > 0) {
                $this->salida.="<tr>";
                $this->salida.="  <td colspan='9' align='center'>";
                $this->salida .= "<br><table  border=\"0\"  bordercolor='#DDDDDD' width=\"100%\" align=\"center\">";
                $this->salida.="<tr><td class=\"modulo_table_title\">LISTADO DE DEVOLUCIONES";
                $this->salida.="</td></tr>";

                /* Esta variable de session atrapa el vector que contiene las
                  devoluciones que van a ser cerrados,cuando generamos el reporte se va
                  a mostrar este vector como constancia de que se los anteriores
                  recibos de caja han sido cerrados satisfactoriamente
                 */
                //$vec=$this->TraerReciboCaja($Caja$usuario_id);
                $_SESSION['CAJA']['VECTOR_CIERRE_DEV'] = $dev;
                $this->salida.="<tr><td>";
                $this->salida.="<table  align=\"center\" border=\"3\"  bordercolor='#CCCCCC'  width=\"100%\" >";
                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                $this->salida.="  <td width=\"9%\">Devoluci�n No.</td>";
                $this->salida.="  <td width=\"10%\">Fecha</td>";
                $this->salida.="  <td >Caja</td>";
                $this->salida.="  <td >Paciente</td>";
                $this->salida.="  <td >Total Devoluci�n</td>";
                $this->salida.="  <td >&nbsp;</td>";
                /* if($_SESSION['CAJA']['CIERRE']['DEPTO'])
                  {
                  $this->salida.="  <td >Descuentos</td>";
                  } */
                $this->salida.="</tr>";
                $arreglodev = array();
                for ($i = 0; $i < sizeof($dev); $i++) {
                    $recd = $dev[$i][recibo_caja];
                    $pred = $dev[$i][prefijo];
                    $fechd = $dev[$i][fecha_registro];
                    $cajad = $dev[$i][descripcion];
                    $efd = $dev[$i][total_devolucion];
                    $arreglodev[$i] = $dev[$i][caja_id];
                    if ($i % 2) {
                        $estilo = 'modulo_list_claro';
                    } else {
                        $estilo = 'modulo_list_oscuro';
                    }
                    $this->salida.="<tr class=\"$estilo\" align=\"center\">";
                    $this->salida.="  <td>" . $pred . "-" . $recd . "</td>";
                    $this->salida.="  <td>$fechd</td>";
                    $this->salida.="  <td>$cajad</td>";
                    //if($_SESSION['CAJA']['CIERRE']['DEPTO'])
                    //traemos los pacientes de caja general
                    $paci = $this->TraerPacienteDevolucion($Caja, $recd, $pred, $usuario_id);
                    $this->salida.="  <td>" . $paci[0][id] . "-" . $paci[0][nombre] . "</td>";
                    $this->salida.="  <td>" . FormatoValor($efd) . "</td>";
                    $this->salida.="  <td><img src=\"" . GetThemePath() . "/images/checkS.gif\"></td>";
                    $tefd = $tefd + $efd;
                    $this->salida.="</tr>";
                }
                $_SESSION['CAJA']['CIERRE']['DEVOLUCIONES'] = $arreglodev; //tienelos caja_id_para cierre
                $this->salida.="<tr class=\"$estilo\">";
                $this->salida.="<td colspan='8'>&nbsp;</td>";
                $this->salida.="</tr>";
                if ($estilo == 'modulo_list_claro') {
                    $estilo = 'modulo_list_oscuro';
                } else {
                    $estilo = 'modulo_list_claro';
                }
                $this->salida.="<tr>";
                $moneda = "$ ";
                $this->salida.="<td  class=\"modulo_list_oscuro\"  align=\"right\" colspan='4'>Totales &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                //$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tef)."</td>";
                //if($_SESSION['CAJA']['CIERRE']['DEPTO'])
                //	{	$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdes)."</td>";}
                $this->salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\">" . FormatoValor($tefd) . "</td>";
                $this->salida.="<td class=\"modulo_table_list_title\"><img src=\"" . GetThemePath() . "/images/wtarrow.gif\"></td>";
                $this->salida.="</tr>";

                $this->salida.="<tr >";
                $this->salida.="<td colspan='9'>&nbsp;</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr>";
                $this->salida.="  <td colspan='9' align='center' class='label'>Total: &nbsp;&nbsp;<img src=\"" . GetThemePath() . "/images/bestell.gif\">" . FormatoValor($tefd) . "</td>";
                $this->salida.="</tr>";
//
                $this->salida.="<br><table align=\"center\" width=\"60%\" border=\"0\">";
                $this->salida.="<tr>";
                $this->salida.="<td colspan='9'>&nbsp;</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr>";
                $this->salida.="<td class=\"modulo_table_list_title\" colspan='3' align=\"center\">TOTALES CIERRE</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr>";
                $this->salida.="<td class=\"modulo_list_oscuro\" align=\"center\" width=\"30%\"><label class=\"label\">TOTAL RECIBOS</label></td><td class=\"modulo_list_oscuro\" align=\"center\" width=\"30%\"><label class=\"label\">TOTAL DEVOLUCI�N</label></td><td class=\"modulo_list_oscuro\" align=\"center\" width=\"40%\"><label class=\"label\">CIERRE</label></td>";
                $this->salida.="</tr>";
                $this->salida.="<tr>";
                $this->salida.="<td class=\"modulo_list_claro\" align=\"center\">" . '$&nbsp;' . FormatoValor($subT) . "</td><td class=\"modulo_list_claro\" align=\"center\">" . '$&nbsp;' . FormatoValor($tefd) . "</td><td class=\"modulo_list_claro\" align=\"center\">" . '$&nbsp;' . FormatoValor($subT - $tefd) . "</td>";
                $this->salida.="</tr>";
                $this->salida.="</table>";
//
                $this->salida.="</table>";
                $this->salida.="</td>";
                $this->salida.="</tr>";
            }
            //***************************
            //***************************
            //***************************
            //FIN LISTADO DE DEVOLUCIONES
            //***************************
            //***************************
            //***************************
            if ($tipocuenta == '01' or $tipocuenta == '02') {
                //
                $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCierreCaja', array('Caja' => $Caja, 'Empresa' => $vec[0][empresa_id], 'CentroUtilidad' => $vec[0][centro_utilidad], 'arreglo' => $tipo, 'TipoCuenta' => $tipocuenta, 'tef' => $tef, 'ttar' => $ttar, 'tche' => $tche, 'user' => $usuario_id, 'tefd' => $tefd, 'tbon' => $tbon, 'Caja_empresa' => $_REQUEST['Caja_empresa']));
                /* 													$volver=ModuloGetURL('app','CajaGeneral','user','Busqueda',array('Cajaid'=>$_REQUEST['Caja'],'Empresa'=>$_REQUEST['Empresa'],'CentroUtilidad'=>$_REQUEST['CentroUtilidad'],'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta,'criterio'=>$_REQUEST['criterio'],'departamento'=>$_REQUEST['dpto'])); */
                $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'Busqueda', array('Cajaid' => $Caja, 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'criterio' => $_REQUEST['criterio'], 'departamento' => $_REQUEST['dpto'], 'Caja_empresa' => $_REQUEST['Caja_empresa']));
                /* 												$volver=ModuloGetURL('app','CajaGeneral','user','VolverMenu',array('Caja'=>$Caja,'Empresa'=>$empresa,'CentroUtilidad'=>$centro,'arreglo'=>$tipo,'TipoCuenta'=>$tipocuenta,'facturacion'=>$fact,'CU'=>$cu,'SWCUENTAS'=>'Cuentas')); */
            } elseif ($tipocuenta == '03') {
                $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCierreCaja', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'user' => $usuario_id, 'tef' => $tef, 'ttar' => $ttar, 'tche' => $tche, 'tefd' => $tefd, 'tbon' => $tbon));
                $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'Caja_empresa' => $_REQUEST['Caja_empresa']));
            } elseif ($tipocuenta == '04') {
                $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCierreCaja', array('Cajaid' => $Caja, 'Empresa' => $vec[0][empresa_id], 'CentroUtilidad' => $vec[0][centro_utilidad], 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'user' => $usuario_id, 'tef' => $tef, 'ttar' => $ttar, 'tche' => $tche, 'tefd' => $tefd, 'tbon' => $tbon));
                //$volver=ModuloGetURL('app','CajaGeneral','user','VolverMenu',array('Caja'=>$Caja,'Empresa'=>$empresa,'CentroUtilidad'=>$centro,'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta));
                $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'Busqueda', array('Cajaid' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'criterio' => $_REQUEST['criterio'], 'departamento' => $_REQUEST['dpto']));
            } elseif ($tipocuenta == '05' || $tipocuenta == '06') {
                $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCierreCaja', array('Caja' => $Caja, 'Empresa' => $vec[0][empresa_id], 'CentroUtilidad' => $vec[0][centro_utilidad], 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'user' => $usuario_id, 'tef' => $tef, 'ttar' => $ttar, 'tche' => $tche, 'tefd' => $tefd, 'tbon' => $tbon, 'Caja_empresa' => $_REQUEST['Caja_empresa']));
                //$volver=ModuloGetURL('app','CajaGeneral','user','VolverMenu',array('Caja'=>$Caja,'Empresa'=>$empresa,'CentroUtilidad'=>$centro,'Tiponumeracion'=>$tipo,'TipoCuenta'=>$tipocuenta));
                $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'Busqueda', array('Cajaid' => $Caja, 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'criterio' => $_REQUEST['criterio'], 'departamento' => $_REQUEST['dpto'], 'Caja_empresa' => $_REQUEST['Caja_empresa']));
            } elseif ($tipocuenta == '08') {
                $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'InsertarCierreCaja', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'user' => $usuario_id, 'tef' => $tef, 'ttar' => $ttar, 'tche' => $tche, 'tefd' => $tefd, 'tbon' => $tbon));
                $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'Caja_empresa' => $_REQUEST['Caja_empresa']));
            }
            /* 												elseif($tipocuenta=='06')
              {
              } */
            if (empty($_REQUEST['sw_cuadrada']) AND $usuario_id == UserGetUID()) {
                $this->salida.="<tr>";
                $this->salida.="  <td>";
                $this->salida .='<form name="forma" action=' . $accion . ' method="post">';
                $this->salida.="<br><br><table border=\"0\"  align=\"center\"   width=\"50%\" >";
                $this->salida .="" . $this->SetStyle("MensajeError") . "";
                $this->salida.="<tr>";
                $this->salida .= "<td  colspan=\"2\"  align=\"center\" class=\"modulo_table_title\" >Autenticaci�n de Usuario</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr>";
                $this->salida .= "<td   width=\"35%\" align=\"center\" class=\"modulo_list_claro\"\" >Observaciones :</td>";
                $this->salida .= "<td  align=\"center\" class=\"modulo_list_claro\" ><textarea class=\"textarea\"  name=\"observa\"  rows=\"5\"  cols=\"45\" >$obs</textarea></td>";
                $this->salida.="</tr>";
                $this->salida.="<tr>";
                $this->salida.="<tr class=\"modulo_list_claro\">";
                $this->salida .= "<td   width=\"35%\" align=\"center\" class=\"" . $this->SetStyle("usuario") . "\">Usuario :</td>";
                $this->salida .= "<td  align=\"center\"><input class=\"input-text\" type=\"text\" align=\"center\" name=\"usuario\"</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr class=\"modulo_list_claro\">";
                $this->salida .= "<td   width=\"35%\"  align=\"center\"  class=\"" . $this->SetStyle("pass") . "\">Password :</td>";
                $this->salida .= "<td  align=\"center\"><input class=\"input-text\" type=\"password\" align=\"center\" name=\"pass\"</td>";
                $this->salida.="</tr>";
                $this->salida.="</table>";
                $this->salida.="</td>";
                $this->salida.="</tr>";

                $this->salida.="<tr>";
                $this->salida.="  <td>";
                $this->salida.="<br><table align=\"center\" border=\"0\" width=\"40%\">";
                $this->salida.="<tr>";
                $this->salida.="  <td align=\"right\">";
                $this->salida .="<input type=\"submit\" align=\"center\" name=\"cuadrar\" value=\"CUADRAR CAJA\" class=\"input-submit\"></td>"; //</form>
                //$this->salida .='<form name="forma1" action='.$acc1.' method="post">';
                if ($tipocuenta != '01' AND $tipocuenta != '02' AND $tipocuenta != '03' AND $tipocuenta != '04' AND $tipocuenta != '05' AND $tipocuenta != '06' AND $tipocuenta != '08') {
                    $this->salida.="  <td align=\"center\">";
                    $this->salida .="<input type=\"submit\" align=\"left\" name=\"cerrar\" value=\"CERRAR\" class=\"input-submit\"></td>";
                }
                $this->salida .="</form>";
                $this->salida.="  <td align=\"left\">";
                //$acc2=ModuloGetURL('app','CajaGeneral','user','InsertarCuadreCierre',array('Caja'=>$Caja,'Empresa'=>$vec[0][empresa_id],'CentroUtilidad'=>$vec[0][centro_utilidad],'arreglo'=>$tipo,'TipoCuenta'=>$tipocuenta,'tef'=>$tef,'ttar'=>$ttar,'tche'=>$tche,'user'=>$usuario_id,'tefd'=>$tefd));
                $this->salida .='<form name="form" action=' . $volver . ' method="post">';
                $this->salida .="<input type=\"submit\" align=\"left\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form></td>";
                $this->salida.="</tr>";
                $this->salida.="</table>";
                $this->salida.="</td></tr>";
            } else {
                if ($tipocuenta == '01' or $tipocuenta == '02')
                    $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'Busqueda', array('Cajaid' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'criterio' => $_REQUEST['criterio'], 'departamento' => $_REQUEST['dpto']));
                elseif ($tipocuenta == '03')
                    $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));
                elseif ($tipocuenta == '04')
                    $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'Busqueda', array('Cajaid' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'criterio' => $_REQUEST['criterio'], 'departamento' => $_REQUEST['dpto']));
                elseif ($tipocuenta == '05')
                    $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'Busqueda', array('Caja' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'criterio' => $_REQUEST['criterio'], 'departamento' => $_REQUEST['dpto'], 'Caja_empresa' => $_REQUEST['Caja_empresa']));
                elseif ($tipocuenta == '06')
                    $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'Busqueda', array('Cajaid' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta, 'criterio' => $_REQUEST['criterio'], 'departamento' => $_REQUEST['dpto'], 'Caja_empresa' => $_REQUEST['Caja_empresa']));
                $this->salida.="<tr>";
                $this->salida.="  <td>";
                $this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
                $this->salida.="<tr>";
                $this->salida.="<td align=\"center\">";
                $this->salida.='<form name="forma" action=' . $volver . ' method="post">';
                $this->salida.="<input type=\"submit\" align=\"left\" name=\"Volver\" value=\"Volver..\" class=\"input-submit\"></form></td>";
                $this->salida.="</tr>";
                $this->salida.="</table>";
                $this->salida.="</td></tr>";
            }
            $this->salida.="</table>";
        }
        else {
            $this->salida .= "<br><table border=\"0\" width=\"50%\" align=\"center\" >";

            if ($tipocuenta == '01' or $tipocuenta == '02') {
                $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'arreglo' => $tipo, 'TipoCuenta' => $tipocuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas'));
            } elseif ($tipocuenta == '03') {
                $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));
            } elseif ($tipocuenta == '04') {
                $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Cajaid' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));
            } elseif ($tipocuenta == '05') {
                $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'arreglo' => $tipo, 'TipoCuenta' => $tipocuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas', 'Caja_empresa' => $_REQUEST['Caja_empresa']));
            } elseif ($tipocuenta == '06') {
                $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'VolverMenu', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'arreglo' => $tipo, 'TipoCuenta' => $tipocuenta, 'facturacion' => $fact, 'CU' => $cu, 'SWCUENTAS' => 'Cuentas'));
            } elseif ($tipocuenta == '08') {
                $volver = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array('Caja' => $Caja, 'Empresa' => $empresa, 'CentroUtilidad' => $centro, 'Tiponumeracion' => $tipo, 'TipoCuenta' => $tipocuenta));
            }
            $this->salida.="<tr>";
            $this->salida.="<td align=\"center\"><img src=\"" . GetThemePath() . "/images/show.gif\"></td>";
            $this->salida.="</tr>";
            $this->salida.="<tr>";
            $this->salida.="<td align=\"center\" class=\"label_mark\">NO EXISTEN RECIBOS DE CAJA PARA REALIZAR EL CIERRE</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr>";
            $this->salida.="  <td align=\"center\">";
            $this->salida .='<form name="formato" action=' . $volver . ' method="post">';
            $this->salida .="<br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
            $this->salida.="</td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
        }
        $this->salida .= ThemeCerrarTabla();
        return true;
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
            if ($responsables[$i][plan_id] == $Responsable) {
                $this->salida .=" <option value=\"" . $responsables[$i][plan_id] . "\" selected>" . $responsables[$i][plan_descripcion] . "</option>";
            } else {
                $this->salida .=" <option value=\"" . $responsables[$i][plan_id] . "\">" . $responsables[$i][plan_descripcion] . "</option>";
            }
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

    /*
     * Esta funcion realiza la busqueda de las ordenes de servicio seg�n filtros como numero de orden
     * documento y plan
     * @return boolean
     */

    function FormaMetodoBuscarOrden($Busqueda, $arr, $f) {

        $this->salida.= ThemeAbrirTabla('CAJA ORDEN DE SERVICIOS MEDICOS');
        $this->EncabezadoEmpresa($_SESSION['CAJA']['CAJAID']);
        if (!$Busqueda) {
            $Busqueda = 1;
        }
        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarOrden');
        $this->salida .= "  <table border=\"0\" width=\"98%\" align=\"center\">";
        $this->salida .= "        <tr>";
        $this->salida .= "           <td width=\"62%\" >";
        $this->salida .= "      <br><table border=\"0\" width=\"90%\" align=\"center\">";
        $this->salida .= "          <tr><td><fieldset><legend class=\"field\">BUSCAR CUENTA</legend>";
        $this->salida .= "                  <table width=\"95%\" align=\"center\" border=\"0\">";
        $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        if ($Busqueda == '1') {
            $this->salida .= "                        <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
            $tipo_id = $this->tipo_id_paciente();
            $this->BuscarIdPaciente($tipo_id, '');
            $this->salida .= "                  </select></td></tr>";
            $this->salida .= "                        <tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
            $this->salida .= "                      <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
            $this->salida .= "                 <input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";
        }
        if ($Busqueda == '2') {
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "                        <tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"nombres\" maxlength=\"32\"></td></tr>";
            $this->salida .= "                        <tr><td class=\"label\">APELLIDOS</td><td><input type=\"text\" class=\"input-text\" name=\"apellidos\" maxlength=\"32\"></td></tr>";
            $this->salida .= "                     <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
            $this->salida .= "                 <input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";
        }
        if ($Busqueda == '3') {
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "                        <tr><td colspan=\"2\">&nbsp;</td></tr>";
            $this->salida .= "                       <tr><td class=\"" . $this->SetStyle("Responsable") . "\">PLAN: </td><td><select name=\"Responsable\" class=\"select\">";
            $responsables = $this->responsables();
            $this->MostrarResponsable($responsables, $Responsable);
            $this->salida .= "              </select></td></tr>";
            $this->salida .= "                <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
            $this->salida .= "                 <input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";
        }
        if ($Busqueda == '4') {
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "                        <tr><td colspan=\"2\">&nbsp;</td></tr>";
            $this->salida .= "                        <tr><td class=\"" . $this->SetStyle("IngresoId") . "\">No. ORDEN</td><td><input type=\"text\" class=\"input-text\" name=\"NumIngreso\" maxlength=\"32\"></td></tr>";
            $this->salida .= "                <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
            $this->salida .= "                 <input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";
        }

        $this->salida .= "               <tr><td align=\"$ali\" colspan=\"$col\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
        $this->salida .= "                  </form>";
        $actionM = ModuloGetURL('app', 'CajaGeneral', 'user', 'main');
        $this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\"
     method=\"post\">";
        $this->salida .= "                       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
        $this->salida .= "                       </tr>";
        $this->salida .= "          </fieldset></td></tr></table>";
        $this->salida .= "    </table>";
        $this->salida .= "           </td>";
        $this->salida .= "           <td>";
        $this->salida .= "      <BR><table border=\"0\" width=\"92%\" align=\"center\">";
        $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "          <tr><td><fieldset><legend class=\"field\">BUSQUEDA AVANZADA</legend>";
        $this->salida .= "                  <table width=\"90%\" align=\"center\" border=\"0\">";
        $this->salida .= "                        <tr><td colspan=\"2\">&nbsp;</td></tr>";
        $this->salida .= "                       <tr><td class=\"label\">TIPO BUSQUEDA: </td><td><select name=\"TipoBusqueda\" class=\"select\">";
        $this->salida .= "                   <option value=\"1\" selected>DOCUMENTO</option>";
        //$this->salida .= "                   <option value=\"2\">NOMBRE</option>";
        $this->salida .= "                   <option value=\"4\">No.ORDEN</option>";
        $this->salida .= "              </select></td></tr>";
        $this->salida .= "                       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Busc\" value=\"BUSCAR\"></td></tr>";
        $this->salida .= "                 <input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";
        $this->salida .= "                  </form>";
        $this->salida .= "                        <tr><td colspan=\"2\">&nbsp;</td></tr>";
        $this->salida .= "                     </table>";
        $this->salida .= "          </fieldset></td></tr></table>";
        $this->salida .= "           </td>";
        $this->salida .= "        </tr>";
        $this->salida .= "    </table>";
        if ($mensaje) {
            $accionT = ModuloGetURL('app', 'Facturacion', 'user', 'main', array('TipoCuenta' => $TipoCuenta));
            $this->salida .= "            <p class=\"label_error\" align=\"center\">$mensaje</p>";
            $this->salida .= "           <form name=\"formabuscar\" action=\"$accionT\" method=\"post\">";
        }
        if (!$arr) {
            //$this->BusquedaCompleta();
            //$arr=$this->BusquedaCompleta();
            //$_SESSION['SPY']=$this->RecordSearch($Caja,$TipoCuenta,$Departamento);
        }

        $this->salida.="<table border=\"0\" align=\"center\"  width=\"100%\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida.="</table><br>";

        $vector = $arr;
        for ($i = 0; $i < sizeof($vector);) {
            $k = $i;
            if ($vector[$i][plan_id] == $vector[$k][plan_id]
                    AND $vector[$i][tipo_afiliado_id] == $vector[$k][tipo_afiliado_id]
                    AND $vector[$i][rango] == $vector[$k][rango]
                    AND $vector[$i][orden_servicio_id] == $vector[$k][orden_servicio_id]) {
                $this->salida.="<BR><table  align=\"center\" border=\"0\" width=\"80%\">";
                $this->salida.="<tr class=\"modulo_table_list_title\">";
                $this->salida.="  <td align=\"left\" colspan=\"7\">PLAN&nbsp;&nbsp;" . $vector[$i][descripcion] . "&nbsp;&nbsp;" .
                        $vector[$i][plan_descripcion] . "</td>";
                $this->salida.="</tr>";
                $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                $this->salida.="  <td width=\"7%\">ORDEN</td>";
                $this->salida.="  <td width=\"8%\">ITEM</td>";
                $this->salida.="  <td width=\"10%\">CANTIDAD</td>";
                $this->salida.="  <td width=\"10%\">CARGO</td>";
                $this->salida.="  <td width=\"40%\">DESCRIPCION</td>";
                $this->salida.="  <td width=\"20%\">VENCIMIENTO</td>";
                $this->salida.="  <td width=\"8%\"></td>";
                //$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
                //form
                $this->salida .= "           <form name=\"formita\" action=\"" . ModuloGetURL('app', 'CajaGeneral', 'user', 'LiquidarCargoCuentaGeneral', array('id_tipo' => $vector[$i][tipo_id_paciente], 'nom' => $vector[$i][nombre], 'id' => $vector[$i][paciente_id], 'plan_id' => $vector[$k][plan_id])) . "\" method=\"post\">";
                $this->salida.="</tr>";
            }
            while ($vector[$i][plan_id] == $vector[$k][plan_id]
            AND $vector[$i][tipo_afiliado_id] == $vector[$k][tipo_afiliado_id]
            AND $vector[$i][rango] == $vector[$k][rango]
            AND $vector[$i][servicio] == $vector[$k][servicio]) {
                $this->salida.="<tr class='modulo_list_claro'>";
                $this->salida.="  <td  class=\"hc_table_submodulo_list_title\" width=\"7%\">" . $vector[$k][orden_servicio_id] . "</td>";
                $this->salida.="  <td colspan=\"6\">";
                $this->salida.="  <table align=\"center\" border=\"1\" width=\"100%\">";
                $l = $k;
                while ($vector[$k][orden_servicio_id] == $vector[$l][orden_servicio_id]
                AND $vector[$k][plan_id] == $vector[$l][plan_id]
                AND $vector[$k][tipo_afiliado_id] == $vector[$l][tipo_afiliado_id]
                AND $vector[$k][rango] == $vector[$l][rango]
                AND $vector[$k][servicio] == $vector[$l][servicio]) {
                    $vecimiento = $vector[$l][fecha_vencimiento];
                    $arr_fecha = explode(" ", $vecimiento);
                    if ($l % 2) {
                        $estilo = 'modulo_list_claro';
                    } else {
                        $estilo = 'modulo_list_oscuro';
                    }
                    $this->salida.="<tr align='center'>";
                    $this->salida.="  <td align='center' class=$estilo width=\"8%\"<label class='label_mark'>" . $vector[$l][numero_orden_id] . "</label></td>";
                    $this->salida.="  <td colspan=5>";
                    $this->salida.="  <table align=\"center\" border=\"0\" width=\"100%\">";
                    $m = $l;
                    while ($vector[$l][numero_orden_id] == $vector[$m][numero_orden_id]
                    AND $vector[$l][orden_servicio_id] == $vector[$m][orden_servicio_id]
                    AND $vector[$l][plan_id] == $vector[$m][plan_id]
                    AND $vector[$l][tipo_afiliado_id] == $vector[$m][tipo_afiliado_id]
                    AND $vector[$l][rango] == $vector[$m][rango]
                    AND $vector[$l][servicio] == $vector[$m][servicio]) {
                        $this->salida.="<tr class=$estilo>";
                        $this->salida.="  <td width=\"10%\" align=\"center\" >" . $vector[$m][cantidad] . "</td>";
                        $this->salida.="  <td width=\"14%\" align=\"center\" >" . $vector[$m][cargoi] . "</td>";
                        $this->salida.="  <td width=\"42%\">" . $vector[$m][des1] . "</td>";

                        if (strtotime($vector[$m][fecha_vencimiento]) > strtotime(date("Y-m-d"))) {
                            $this->salida.="  <td width=\"26%\" align=\"center\" >$arr_fecha[0]";
                            $this->salida.="  <td width=\"15%\" align=\"center\"><input type=checkbox name=op[$m] value=" . $vector[$m][numero_orden_id] . "," . $vector[$m][cargo] . "," . $vector[$m][tarifario_id] . "," . $vector[$m][autorizacion_ext] . "," . $vector[$m][autorizacion_int] . "," . $vector[$m][cantidad] . "," . urlencode($vector[$m][descargo]) . "," . $vector[$m][servicio] . "," . $vector[$m][serv_des] . "," . $vector[$k][orden_servicio_id] . "></td>";
                        } else {
                            $this->salida.="  <td width=\"26%\" align=\"center\" ><label class='label_mark'>VENCIDO</label></td>";
                            $this->salida.="  <td><img src=\"" . GetThemePath() . "/images/delete.gif\"></td>";
                        }
                        $this->salida.="</tr>";
                        $m++;
                    }
                    $this->salida.="</table>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
                    $l = $m;
                }
                //parte de alex.
                $this->salida.="<tr><td colspan='8' align=\"center\">";
                $this->salida.="<table width='100%' border='0' cellpadding='2' align=\"center\">";
                $this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >OBSERVACION</td><td class='modulo_list_claro'>" . $vector[$k][observacion] . "</td></tr>";
                $this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >SERVICIO</td><td width='80%' class='modulo_list_oscuro'>" . $vector[$k][serv_des] . "</td></tr>";
                $this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AUT. INT.</td><td width='80%' class='modulo_list_claro'>" . $vector[$k][autorizacion_int] . "</td></tr>";
                $this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AUT. EXT.</td><td width='80%' class='modulo_list_oscuro'>" . $vector[$k][autorizacion_ext] . "</td></tr>";
                $this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AFILIACION</td><td width='80%' class='modulo_list_oscuro'>" . $vector[$k][tipo_afiliado_nombre] . "</td></tr>";
                $this->salida.="</table>";
                $this->salida.="</td></tr>";
                //parte de alex.

                $this->salida.="</table>";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $k = $l;
            }
            $this->salida.="</table>";
            $this->salida.="<table align='center' width='80%'>";
            $this->salida.="<tr align='right' class=\"modulo_table_button\">";

            //este switche $_SESSION['LABORATORIO']['SW_ESTADO'] si esta en 1 es por que solo
            //esta habilitado para cumplimiento...
            if ($_SESSION['LABORATORIO']['SW_ESTADO'] == 1) {
                //este switche $vector[$i][sw_cuenta]>=1
                //permite ir a cargar la cuenta en caso de
                //q venga en 1 o > 1, mejor dicho es por q tiene una cuenta....
                //$vector[$i][sw_cargo_multidpto]=='1' es por si es de 'servicio' hospitalario..

                if ($vector[$i][sw_cuenta] >= 1 AND $vector[$i][sw_cargo_multidpto] == '1') {

                    $this->salida.="<td><input class=\"input-submit\" type=submit name=mandar$l value=Ordenar></td>";
                } else {
                    $this->salida.="<td>&nbsp;</td>";
                }
            }
            //este switche $_SESSION['LABORATORIO']['SW_ESTADO'] si esta en 0 es por
            //q esta habilitado para que se pague en caja ...
            elseif ($_SESSION['LABORATORIO']['SW_ESTADO'] == 0) {
                if ($vector[$i][sw_cuenta] < 1) {

                    $this->salida.="<td><input class=\"input-submit\" type=submit name=mandar$l value=Ordenar></td>";
                } else {
                    $this->salida.="<td>&nbsp;</td>";
                }
            }

            $this->salida.="</form>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
            $i = $k;
            $this->conteo = $_SESSION['OS']['SPY'];
        }
        //$this->salida .=$this->RetornarBarra();

        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function RevisarTemp() {
        if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
            $Cuenta = $_REQUEST['Cuenta'];
            $spy = $_REQUEST['spy'];
            $TipoId = $_REQUEST['TipoId'];
            $PacienteId = $_REQUEST['PacienteId'];
            $Nivel = $_REQUEST['Nivel'];
            $PlanId = $_REQUEST['PlanId'];
            $Pieza = $_REQUEST['Pieza'];
            $Cama = $_REQUEST['Cama'];
            $FechaC = $_REQUEST['FechaC'];
            $Ingreso = $_REQUEST['Ingreso'];
        } else {
            $TipoId = $_REQUEST['TipoId'];
            $PacienteId = $_REQUEST['PacienteId'];
            $Cuenta = $_REQUEST['Cuenta'];
            $spy = $_REQUEST['spy'];
            $Cajaid = $_REQUEST['Cajaid'];
            $NombrePaciente = $_REQUEST['NombrePaciente'];
            $PagareNumero = $_REQUEST['PagareNumero'];
            $Empresa = $_REQUEST['Empresa'];
            $Prefijo = $_REQUEST['Prefijo'];
            $Valor = $_REQUEST['Valor'];
            $DocumentoId = $_REQUEST['DocumentoId'];
            if (empty($NombrePaciente) || empty($TipoId))
                $NombrePaciente = $this->TraerPacientePagare($PagareNumero, $Prefijo);
        }

        $this->salida.= ThemeAbrirTabla('ABONOS TEMPORALES');
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'LlamadaFormaEncabezado', array('PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'Fecha' => $FechaC));
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'Cajaid' => $Cajaid));
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
            $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, $PlanId);
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaHospitalaria', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'Cajaid' => $Cajaid));
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            $this->EncabezadoConceptos($Cuenta, $TipoId, $PacienteId, $PlanId);
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConceptos', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'Cajaid' => $Cajaid));
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            $this->EncabezadoConsultaExt($Cuenta, $TipoId, $PacienteId, $PlanId);
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConsultaExterna', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'Cajaid' => $Cajaid));
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
            $this->EncabezadoOrdenServicio();
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'RetornarOrdenServicio', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'Cajaid' => $Cajaid));
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
            $this->EncabezadoPvta($Cuenta, $TipoId, $PacienteId, '', $NombrePaciente, $PagareNumero, $Empresa, $Prefijo);
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaPagares', array('spy' => $spy, 'Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Cajaid' => $Cajaid, 'Empresa' => $Empresa, 'Prefijo' => $Prefijo, 'Valor' => $Valor, 'NombrePaciente' => $NombrePaciente, 'DocumentoId' => $DocumentoId));
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '08') {
            //$this-> EncabezadoConceptos($Cuenta,$TipoId,$PacienteId,$PlanId);
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'CajaConceptos', array('spy' => 2, 'Cuenta' => $Cuenta, 'PlanId' => $PlanId, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Ingreso' => $Ingreso, 'Nivel' => $Nivel, 'FechaC' => $FechaC, 'Cajaid' => $Cajaid));
        }

        //$this->ConsultaCajaHospitalizacion($Cuenta,$Recibo,$TipoId,$PacienteId,$Nivel,$PlanId,$Pieza,$Cama,$FechaC,$Ingreso,$Recibo);

        if ($spy == '2' AND $_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
            $this->ConsultaHospitalizacion('2', $Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Recibo);
        } else
        if ($spy == '2' AND $_SESSION['CAJA']['TIPOCUENTA'] == '06') {
            $this->ConsultaHospitalizacion('2', $Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Recibo);
        }

        if ($spy == '3') {
            $this->ConsultaHospitalizacion('3', $Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Recibo);
        }

        if ($spy == '4') {
            $this->ConsultaHospitalizacion('4', $Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Recibo);
        }
        $this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
        $this->salida.="<tr>";
        $this->salida.="  <td align=\"center\">";
        $this->salida .='<form name="forma" action="' . $accion . '" method="post">';
        $this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
        $this->salida.="</td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

    //----------------------------nuevo dar--------------------		
    //se imprime cuando es la caja de conceptos porque no hay fac_facturas_cuentas		
    function FormaVentanaImpresionConceptos() {
        $this->salida.= ThemeAbrirTabla('IMPRESION FACTURA');
        $this->salida.="<table align=\"center\" border=\"0\" width=\"50%\">";
        $this->salida.="<tr>";
        $this->salida .= "<td colspan=\"3\" align=\"center\" class=\"label\">DATOS GUARDATOS CORRECTAMENTE</td>";
        $this->salida.="</tr>";
        $this->salida.="<tr>";
        //volver
        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarTercero');
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Volver\"></td>\n";
        $this->salida .= "</form>";
        //para imprimir en pos
        $accion7 = ModuloGetURL('app', 'CajaGeneral', 'user', 'ImpresionReportesConceptos');
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion7\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Imprimir POS\"></td>\n";
        $this->salida .= "</form>";
        //para imprimir en pdf
        $reporte = new GetReports();
        $this->salida .= $reporte->GetJavaReport('app', 'CajaGeneral', 'FacturaConcepto', array('empresa' => $_SESSION['CAJA']['EMPRESA'], 'prefijo' => $_SESSION['CAJA']['PREFIJOCONCEPTO'], 'factura' => $_SESSION['CAJA']['FACTURACONCEPTO']), array('rpt_dir' => 'cache', 'rpt_name' => 'recibo' . $_SESSION['CAJA']['TERCEROID'], 'rpt_rewrite' => true));
        $funcion = $reporte->GetJavaFunction();
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Imprimir PDF\"  onclick=\"javascript:$funcion\"></td></tr>\n";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }

    function FormaVentanaImpresionInventarioProducto() {
        $this->salida.= ThemeAbrirTabla('IMPRESION FACTURA');
        $this->salida.="<table align=\"center\" border=\"0\" width=\"50%\">";
        $this->salida.="<tr>";
        $this->salida .= "<td colspan=\"3\" align=\"center\" class=\"label\">DATOS GUARDATOS CORRECTAMENTE</td>";
        $this->salida.="</tr>";
        $this->salida.="<tr>";
        //volver
        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarTercero');
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Volver\"></td>\n";
        $this->salida .= "</form>";
        //para imprimir en pos
        $accion7 = ModuloGetURL('app', 'CajaGeneral', 'user', 'ImpresionReportesInventarios');
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion7\" method=\"post\">";
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Imprimir POS\"></td>\n";
        $this->salida .= "</form>";
        //para imprimir en pdf
        $reporte = new GetReports();
        $this->salida .= $reporte->GetJavaReport('app', 'CajaGeneral', 'FacturaInventario', array('empresa' => $_SESSION['CAJA']['EMPRESA'], 'prefijo' => $_SESSION['CAJA']['PREFIJOINVENTARIO'], 'factura' => $_SESSION['CAJA']['FACTURAINVENTARIO']), array('rpt_dir' => 'cache', 'rpt_name' => 'recibo' . $_SESSION['CAJA']['TERCEROID'], 'rpt_rewrite' => true));
        $funcion = $reporte->GetJavaFunction();
        $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Imprimir PDF\"  onclick=\"javascript:$funcion\"></td></tr>\n";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }


    function FormaMensaje($mensaje, $titulo, $accion, $boton, $botonC='', $arreglo) {

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
        //este boton solo lo mostraria el reporte de cierre de caja.........

        if ($botonC) {

            if ($botonC == 'factura') {
                IncludeLib("funciones_facturacion");
                $RUTA = $_ROOT . "cache/factura" . $arreglo['cuenta'] . ".pdf";
                $RUTA1 = $_ROOT . "cache/factura" . $arreglo['cuenta'] . "_paciente.pdf";

                $mostrar = "\n<script>\n";
                $mostrar.="var rem=\"\";\n";
                $mostrar.="  function abreVentanaFc(){\n";
                $mostrar.="    var nombre=\"\"\n";
                $mostrar.="    var url2=\"\"\n";
                $mostrar.="    var str=\"\"\n";
                $mostrar.="    var ALTO=screen.height\n";
                $mostrar.="    var ANCHO=screen.width\n";
                $mostrar.="    var nombre=\"REPORTE\";\n";
                $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
                $mostrar.="    var url2 ='$RUTA';\n";
                $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                $mostrar.="  function abreVentanaFpac(){\n";
                $mostrar.="    var nombre_paciente=\"\"\n";

                $mostrar.="    var url3=\"\"\n";
                $mostrar.="    var strw=\"\"\n";
                $mostrar.="    var ALTOw=screen.height\n";
                $mostrar.="    var ANCHOw=screen.width\n";
                $mostrar.="    var nombre_paciente=\"REPORTE\";\n";
                $mostrar.="    var strw =\"ANCHOw,ALTOw,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
                $mostrar.="    var url3 ='" . $RUTA1 . "';\n";
                $mostrar.="    rem = window.open(url3, nombre_paciente, strw)};\n";

                $mostrar.="  function abreVentanaFp(){\n";
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


                //para imprimir en pos
                $accion7 = ModuloGetURL('app', 'CajaGeneral', 'user', 'Reportes');
                $this->salida .= "<tr>\n";
                $this->salida .= "<form name=\"formabuscar\" action=\"$accion7\" method=\"post\">";
                $this->salida .= " <td align=\"left\">";
                $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Imprimir POS.\">";
                $this->salida .= " </td>\n";
                $this->salida .= "</form>";

                //parte nueva de sos
                $reporte = new GetReports();
                $this->salida .= $reporte->GetJavaReport('app', 'CajaGeneral', 'Factura', array('cuenta' => $arreglo['cuenta'], 'switche_emp' => $arreglo['switche_emp']), array('rpt_dir' => 'cache', 'rpt_name' => 'recibo' . $arreglo['cuenta'], 'rpt_rewrite' => FALSE));
                $recibos_caja = $this->GetDatosFactura($arreglo['cuenta']);

                $funcion = $reporte->GetJavaFunction();

                $this->salida .= " <td align=\"left\">";
                $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Imprimir RC\"  onclick=\"javascript:$funcion\">";
                $this->salida .= " </td>";
                for ($k = 1; $k < sizeof($recibos_caja); $k++) {
                    $this->salida .= " <td align=\"left\">";
                    $this->salida .= "	<font color=\"Navy\"><b>RECIBO CAJA :</b> " . $recibos_caja[$k][prefijo] . " " . $recibos_caja[$k][factura_fiscal] . " </font>";
                    $this->salida .= " </td>";
                }
                $this->salida .= "</tr>\n";
                //factura CLIENTE  
                $var = $this->DatosFactura($arreglo['cuenta'], '', '', '', $arreglo);


                $ruta_reporte = EncontrarFormatoFactura($_SESSION[CAJA][EMPRESA], null, $botonC, 'cliente');
                $ruta_reporte_paciente = EncontrarFormatoFactura($_SESSION[CAJA][EMPRESA], $_SESSION[CAJA][AUX][plan_id], 'factura', 'paciente');

                IncludeLib($ruta_reporte);
                GenerarFactura($var, null, $sw_paciente = 2);

                if ($var['sw_tipo'] == '1') {
                    $this->salida .= "<tr align=\"left\">\n";
                    $this->salida .= "<td>";
                    $this->salida .= " <input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA CLIENTE\" onclick=\"javascript:abreVentanaFc()\">";
                    $this->salida .= "</td>";
                    $this->salida .= " <td align=\"left\">";
                    $this->salida .= "	<font color=\"Navy\"><b>FACTURA CLIENTE :</b> " . $_SESSION['CAJA']['FACTURA']['EMPRESA']['prefijo'] . " " . $_SESSION['CAJA']['FACTURA']['EMPRESA']['factura'] . " </font>";
                    $this->salida .= " </td>";
                    $this->salida .= "</tr>\n";
                }

                if (!empty($_SESSION['CAJA']['FACTURA']['PACIENTE'])) {
                    $var1 = $this->DatosFactura($arreglo['cuenta'], '', '', '', $arreglo, true);
                    IncludeLib($ruta_reporte_paciente);
                    GenerarFacturaPaciente($var1, null, true);
                    $this->salida .= "<tr align=\"left\">\n";
                    $this->salida .= " <td>";
                    $this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"Consultar_paciente\" value=\"FACTURA PACIENTE\" onclick=\"javascript:abreVentanaFpac()\">";
                    $this->salida .= " </td>";
                    $this->salida .= " <td align=\"left\">";
                    $this->salida .= "	<font color=\"red\"><b>FACTURA PACIENTE :</b> " . $_SESSION['CAJA']['FACTURA']['PACIENTE']['prefijo'] . " " . $_SESSION['CAJA']['FACTURA']['PACIENTE']['factura'] . " </font>";
                    $this->salida .= " </td>";
                    $this->salida .= "</tr>\n";
                }

                $this->salida .= "</tr>\n";
                //
            }

            if ($botonC == 'reporte_cierre_de_caja') {
                $DIR = $_ROOT . "cache/cierre_de_caja_reporte2" . UserGetUID() . ".pdf";
                //$DIR="printer.php?ruta=$RUTA";
                $RUTA1 = GetBaseURL() . $DIR;
                $mostrar = "\n<script language='javascript'>\n";
                $mostrar.="var rem=\"\";\n";
                $mostrar.="  function abreVentana(){\n";
                $mostrar.="    var nombre=\"\"\n";
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
                $this->GenerarReporteCierreDeCaja();
            }
            if ($botonC == 'cierre_de_caja') {
                $RUTA = $_ROOT . "cache/cierre_de_caja_reporte" . UserGetUID() . ".pdf";
                $DIR = "printer.php?ruta=$RUTA";
                $RUTA1 = GetBaseURL() . $DIR;
                $mostrar = "\n<script language='javascript'>\n";
                $mostrar.="var rem=\"\";\n";
                $mostrar.="  function abreVentana(){\n";
                $mostrar.="    var nombre=\"\"\n";
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
                $this->GenerarListadoCierreDeCaja();
            }
            if ($botonC == 'cierre') {
                $RUTA = $_ROOT . "cache/cierre_caja" . UserGetUID() . ".pdf";
                $DIR = "printer.php?ruta=$RUTA";
                $RUTA1 = GetBaseURL() . $DIR;
                $mostrar = "\n<script language='javascript'>\n";
                $mostrar.="var rem=\"\";\n";
                $mostrar.="  function abreVentana(){\n";
                $mostrar.="    var nombre=\"\"\n";
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
                $this->GenerarListadoCuadreCaja();
            }
            if ($botonC == 'factura') {
                IncludeLib("funciones_facturacion");
                $RUTA = $_ROOT . "cache/factura" . $arreglo['cuenta'] . ".pdf";

                $mostrar = "\n<script>\n";
                $mostrar.="var rem=\"\";\n";
                $mostrar.="  function abreVentanaFc(){\n";
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


                //para imprimir en pos
                $accion7 = ModuloGetURL('app', 'CajaGeneral', 'user', 'Reportes');
                $this->salida .= "<form name=\"formabuscar\" action=\"$accion7\" method=\"post\">";
                $this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Imprimir POS.\"></td>\n";
                $this->salida .= "</form>";

                //parte nueva de sos
                $reporte = new GetReports();
                $this->salida .= $reporte->GetJavaReport('app', 'CajaGeneral', 'Factura', array('cuenta' => $arreglo['cuenta'], 'switche_emp' => $arreglo['switche_emp']), array('rpt_dir' => 'cache', 'rpt_name' => 'recibo' . $arreglo['cuenta'], 'rpt_rewrite' => FALSE));
                $funcion = $reporte->GetJavaFunction();
                $this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Imprimir PDF\"  onclick=\"javascript:$funcion\"></td>";
                //factura CLIENTE  
                $var = $this->DatosFactura($arreglo['cuenta'], '', '', '', $arreglo);

                //IncludeLib("reportes/factura");
                //GenerarFactura($var);
                $ruta_reporte = EncontrarFormatoFactura($_SESSION[CAJA][EMPRESA], null, $botonC, 'cliente');


                IncludeLib($ruta_reporte);
                GenerarFactura($var);
                if ($var['sw_tipo'] == '1') {
                    $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"FACTURA CLIENTE\" onclick=\"javascript:abreVentanaFc()\"></td>";
                }
                $this->salida .= "</tr>\n";
                //
            }
            if ($botonC == 'facturaconceptos') {
                $RUTA = $_ROOT . "cache/factura_concepto" . $arreglo['tipo_factura'] . $arreglo['prefijo'] . $arreglo['factura'] . ".pdf";
                $mostrar = "\n<script>\n";
                $mostrar.="var rem=\"\";\n";
                $mostrar.="  function abreVentanaFC(){\n";
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
                $var = $this->DatosFacturaConcepto($arreglo['empresa'], $arreglo['prefijo'], $arreglo['factura'], $arreglo['tipo_factura']);
                
                IncludeLib("reportes/factura_concepto");
                $impuestos = $this->TraerPorcentajeImpuestos($_SESSION['CAJA']['EMPRESA'],$var[0]['tercero_id'], $var[0]['tipo_id_tercero']);
                GenerarFactura($var, $arreglo['tipo_factura'], $impuestos);  
                $this->salida .= "      <td><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"Vista preliminar\" onclick=\"javascript:abreVentanaFC()\"></td>";
            } else {
                $this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Aceptar\" value=\"Imprimir\" onclick='abreVentana()'></td></tr>\n";
                //si esta seccion esta habilitada es por que es cierre de factura osea caja rapida.
                if ($_SESSION['REF_DPTO']) {
                    $url = ModuloGetURL('app', 'CajaGeneral', 'user', 'GenerarRolloFiscal', array('sw' => 1, 'go_to' => $accion));
                    $url2 = ModuloGetURL('app', 'CajaGeneral', 'user', 'GenerarRolloFiscal', array('sw' => 2, 'go_to' => $accion));
                    $this->salida .= "<tr><td colspan=\"2\" align=\"center\"><label><br><a href='$url'>Generar Rollo fiscal Contado</a></label></td>";
                    $this->salida .= "<td colspan=\"1\" align=\"center\"><label><br><a href='$url2'>Generar Rollo fiscal Crédito</a></label></td></tr>\n";
                }
            }
        } else {
            $this->salida .= "    </tr>";
        }
        $this->salida .= "</table>\n";
        $this->salida .= themeCerrarTabla();
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
        $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarCuenta', $vec);
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
            $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=" . $valor . " align='center'>P�gina $paso de $numpasos</td><tr></table><br>";
        } else {
            if ($numpasos > 10) {
                $valor = 10 + 5;
            } else {
                $valor = $numpasos + 5;
            }
            $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=" . $valor . " align='center'>P�gina $paso de $numpasos</td><tr></table><br>";
        }
    }

    /*     * ********************************************************************************
     *
     * ********************************************************************************* */

    function FormaBuscarTerceros() {
        $this->BuscarTerceros();

        $this->salida .= ThemeAbrirTabla("BUSCAR TERCEROS - CAJA CONCEPTOS");
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
            $this->salida .= "				<td width=\"24%\"><b>N� FACTURA</b></td>\n";
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
                $opcion = "	<a class=\"label_error\" href=\"javascript:BuscarFactura('" . $Celdas['prefijo'] . "'
                        ,'" . $Celdas['factura_fiscal'] . "')\" title=\"SELECCIONAR\">\n";
                $opcion .= "	<img src=\"" . GetThemePath() . "/images/checkno.png\" border=\"0\"></a>\n";

                $this->salida .= "			<tr class=\"" . $estilo . "\" height=\"21\" onmouseout=mOut(this,\"" . $background . "\"); 
                        onmouseover=mOvr(this,'#FFFFFF');>\n";
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

}

//fin clase
?>