<?php

/**
 * $Id: app_CajaGeneral_user.php,v 1.7 2010/11/25 13:44:50 johanna Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de una caja general(hospitalizacion,conceptos,c.externa,punto de venta)
 */
/**
 * app_CajaGeneral_user.php
 *
 * Clase para procesar los datos del formulario mediante la operaciones de consulta ,captura y de insercion.
 * del modulo caja general en la parte d hospitalizacion, se extiende la clase Caja General y asi pueden ser
 * utilizados los metodos de esta clase en la anterior.
 */
IncludeClass('app_Facturacion_ConceptosHTML', '', 'app', 'CajaGeneral');

class app_CajaGeneral_user extends classModulo {

    var $FechaHoy = '';
    var $Cutilidad;
    var $Empresa;
    var $uno;
    var $prefijo = '';
    var $numero = '';
    var $cajaid = '';
    var $Limit = '';

    /**
     * Es el contructor de la clase
     * @return boolean
     */
    function app_CajaGeneral_user() {
        $this->limit = GetLimitBrowser();
        $this->prefijo = $_REQUEST['prefijo'];
        $this->numero = $_REQUEST['numero'];
        $this->cajaid = $_REQUEST['cajaid'];
        return true;
    }

    /**
     * La funcion main es la principal y donde se llama FormaPrincipal
     * que muestra los diferentes tipos de busqueda de una cuenta para hospitalizaci�.
     * @access public
     * @return boolean
     */
    function main() {
        unset($_SESSION['PAGARE']['TIPOCUENTA']);
        if (!$this->BuscarPermisosUser()) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    function DetalleRecibo() {
        IncludeLib("tarifario");
        $Prefijo = $_REQUEST['Prefijo'];
        $Empresa = $_REQUEST['Empresa'];
        $CU = $_REQUEST['CU'];
        $Recibo = $_REQUEST['Recibo'];
        $Cuenta = $_REQUEST['Cuenta'];
        $PlanId = $_REQUEST['PlanId'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Nivel = $_REQUEST['Nivel'];
        $FechaC = $_REQUEST['FechaC'];
        $PagareNumero = $_REQUEST['PagareNumero'];
        if (!$this->FormaDetalleRecibo($Prefijo, $Empresa, $CU, $Recibo, $Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $PagareNumero)) {
            return false;
        }
        return true;
    }

    /**
     * Llama la forma ConfirmarAccion (forma de mensaje de dos botones).
     * @ access public
     * @ return boolean
     */
    function ConfirmarAccion() {
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $PlanId = $_REQUEST['PlanId'];
        $paso = $_SESSION['PASO'];
        $AgendaId = $_REQUEST['AgendaId'];

        $arreglo = array('TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'paso' => $_SESSION['PASO'], 'PlanId' => $PlanId, 'AgendaId' => $AgendaId);
        $c = 'app';
        $m = 'CajaGeneral';
        $me = 'JustificarConsulta';
        $me2 = 'CajaConsultaExterna';
        $mensaje = 'Esta Seguro que va a justificar esta Cita.';
        $Titulo = 'CONFIRMAR JUSTIFICACION DE UNA CITA';
        $boton1 = 'ACEPTAR';
        $boton2 = 'CANCELAR';

        ConfirmarAccion($Titulo, $mensaje, $boton1, $boton2, array($c, $m, 'user', $me, $arreglo), array($c, $m, 'user', $me2, $arreglo));
        return true;
    }

    /**
     *
     */
    function JustificarConsulta() {
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $PlanId = $_REQUEST['PlanId'];
        $paso = $_SESSION['PASO'];
        $AgendaId = $_REQUEST['AgendaId'];

        if ($paso == true) {
            $tabla = "agenda_citas_asignadas";
        } else {
            $tabla = "agenda_citas_asignadas_no_pacientes";
        }

        list($dbconn) = GetDBconn();
        $query = "UPDATE $tabla SET sw_estado_cita_asignada=6
                WHERE paciente_id=$PacienteId AND tipo_id_paciente='$TipoId' AND agenda_cita_asignada_id='$AgendaId'";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$this->CajaConsultaExterna($TipoId, $PacienteId, $paso, $PlanId)) {
            return false;
        }
        return true;
    }

    function SetStyle($campo) {//Mensaje de error en caso de no encontrar los datos
        if ($this->frmError[$campo] || $campo == "MensajeError") {
            if ($campo == "MensajeError") {
                return ("<tr><td class='label_error' colspan='2' align='center'>" . $this->frmError["MensajeError"] . "</td></tr>");
            } else {
                return ("label_error");
            }
        }
        return ("label");
    }

//  function LlamaBuscadorProductoInv(){
//      if($_REQUEST['Volver']){
//          $this->FormaCuentaInventarios($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
//          return true;
//      }
//      $this->$_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['codigoBus'],$_REQUEST['DescripcionBus']);
//      return true;
//  }

    function CajaPagares() {
        if (!empty($_REQUEST['Cuenta']) || !empty($_REQUEST['PagareNumero']) || !empty($_REQUEST['Valor'])) {
            $Cuenta = $_REQUEST['Cuenta'];
            $TipoId = $_REQUEST['TipoId'];
            $PacienteId = $_REQUEST['PacienteId'];
            $PagareNumero = $_REQUEST['PagareNumero'];
            $Cajaid = $_REQUEST['Cajaid'];
            $Empresa = $_REQUEST['Empresa'];
            $Prefijo = $_REQUEST['Prefijo'];
            $Valor = $_REQUEST['Valor'];
            $NombrePaciente = $_REQUEST['NombrePaciente'];
            $DocumentoId = $_REQUEST['DocumentoId'];
            $PlanId = $_REQUEST['PlanId'];
        }
        $this->FormaCuenta($Cuenta, $TipoId, $PacienteId, "", "", "", "", "", "", "", "", $PagareNumero, $Cajaid, $NombrePaciente, $Empresa, $Prefijo, $Valor, $DocumentoId, $PlanId);
        return true;
    }

    function ValidarUsuario() {
        //$TipoId,$PacienteId,$Nivel,$PlanId,$Pieza,$Cama
        //,$FechaC,$Ingreso,$Cuenta,$Tiponumeracion,$FechaHoy,$Devolucion,$Saldo,$Cajaid
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Nivel = $_REQUEST['Nivel'];
        $PlanId = $_REQUEST['PlanId'];
        $Pieza = $_REQUEST['Pieza'];
        $Cama = $_REQUEST['Cama'];
        $FechaC = $_REQUEST['FechaC'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Cuenta = $_REQUEST['Cuenta'];
        $Tiponumeracion = $_REQUEST['Tiponumeracion'];
        $FechaHoy = $_REQUEST['FechaHoy'];
        $FechaC = $_REQUEST['FechaC'];
        $Devolucion = $_REQUEST['Devolucion'];
        $Saldo = $_REQUEST['Saldo'];
        $Tiponumeracion = $_SESSION['CAJA']['TIPONUMERACION_DEVOLUCIONES'];
        //VALORES PARA VALIDAR EL USUARIO
        $usuario = $_REQUEST['usuario'];
        $pw = $_REQUEST['pass'];
        $Cajaid = $_REQUEST['Cajaid'];
        $usuario_dev = $this->UserValidarUsuarioDevoluciones($usuario, $pw);
        if ($usuario_dev) {
            $this->InsertarDevolucion($TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Cuenta, $Tiponumeracion, $FechaHoy, $Devolucion, $Saldo, '0');
            return true;
        } else {
            $this->InsertarDevolucion($TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Cuenta, $Tiponumeracion, $FechaHoy, $Devolucion, $Saldo, '1');
            return true;
        }
    }

//VALIDAR USUARIOS DEVOLUCIONES
    function UserValidarUsuarioDevoluciones($usuario, $passwd) {

        if (empty($usuario)) {
            return false;
        }
        $caja = $_SESSION['CAJA']['CAJAID'];
        list($dbconn) = GetDBconn();

        $query = "  SELECT A.usuario_id, B.passwd,A.caja_id
                                    FROM caja_usuarios_devoluciones A, system_usuarios B, cajas C
                                    WHERE A.usuario_id=B.usuario_id
                                            AND A.caja_id=C.caja_id
                                            AND A.caja_id=$caja
                                            AND B.usuario = '$usuario' AND B.activo='1'";

        $result = $dbconn->Execute($query);

        if ($result->EOF) {
            return false;
        }

        list($usuario_id, $passwd_real, $cajaid) = $result->FetchRow();
        $result->Close();

        if (empty($usuario_id)) {
            return false;
        }

        if (UserCompararPasswd($passwd, $passwd_real)) {
            return $usuario_id;
        }

        return false;
    }

//FIN VALIDAR USUARIO DEVOLUCIONES

    /**
     * La funcion CajaHospitalaria recibe todas las variables de manejo
     * (de informacion de cabecera acerca del 'paciente' y el
     * 'responsable'(metodo extraido del modulo facturacion(creado por Darling dorado ))  y las envia a
     * a 'FormaCuenta' que es la Interfaz principal.
     * Nota: las variables pueden llegar por REQUEST o por Parametros.
     * @access private
     * @return boolean
     */
    function CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy) {
        if (!$Cuenta) {
            if (empty($_SESSION['CAJA']['EMPRESA'])) {
                $this->Empresa = $_REQUEST['Empresa'];
                $this->Cutilidad = $_REQUEST['CentroUtilidad'];
                $_SESSION['CAJA']['EMPRESA'] = $this->Empresa;
                $_SESSION['CAJA']['CENTROUTILIDAD'] = $this->Cutilidad;
                $this->Cutilidad = $_SESSION['CAJA']['CENTROUTILIDAD'];
                $this->Empresa = $_SESSION['CAJA']['EMPRESA'];
                $_SESSION['CAJA']['TIPONUMERACION'] = $_REQUEST['Tiponumeracion'];
                $_SESSION['CAJA']['TIPOCUENTA'] = $_REQUEST['TipoCuenta'];
                $_SESSION['CAJA']['CAJAID'] = $_REQUEST['Cajaid'];
                $_SESSION['CAJA']['CU'] = $_REQUEST['CU'];
                $_SESSION['CAJA']['CUENTA'] = $_REQUEST['Cuenta'];
                //********************************************************
                //VALIDAR QUE LA CAJA NO ESTE UTILIZADA POR OTRO USUARIO
                //********************************************************
                $existenciarecibosincuadre = $this->ReciboSinCuadre($this->Empresa, $this->Cutilidad, $_SESSION['CAJA']['CAJAID'], $_SESSION['CAJA']['TIPOCUENTA'], '');
                if ($existenciarecibosincuadre) {
                    if (is_array($_SESSION['CAJA']['RETORNO'])) {
                        $accion = ModuloGetUrl($_SESSION['CAJA']['RETORNO']['contenedor'], $_SESSION['CAJA']['RETORNO']['modulo'], $_SESSION['CAJA']['RETORNO']['tipo'], $_SESSION['CAJA']['RETORNO']['metodo'], $_SESSION['CAJA']['RETORNO']['argumentos']);
                        unset($_SESSION['CAJA']['RETORNO']);
                    } else {
                        $accion = ModuloGetUrl('app', 'Triage', 'user', 'LlamadoCaja');
                    }
                    $this->FormaMensaje('EXISTEN RECIBOS DE OTROS USUARIOS SIN CUADRAR!!', 'CONFIRMACION', $accion, 'Volver');
                    return true;
                }
                //********************************************************
                //FIN VALIDAR QUE LA CAJA NO ESTE UTILIZADA POR OTRO USUARIO
                //********************************************************
            } else {
                $this->Cutilidad = $_SESSION['CAJA']['CENTROUTILIDAD'];
                $this->Empresa = $_SESSION['CAJA']['EMPRESA'];
                if (empty($_SESSION['CAJA']['TIPONUMERACION'])) {
                    $_SESSION['CAJA']['TIPONUMERACION'] = $_REQUEST['Tiponumeracion'];
                }
                if (empty($_SESSION['CAJA']['TIPOCUENTA'])) {
                    $_SESSION['CAJA']['TIPOCUENTA'] = $_REQUEST['TipoCuenta'];
                }

                if (empty($_SESSION['CAJA']['CAJAID'])) {
                    $_SESSION['CAJA']['CAJAID'] = $_REQUEST['Cajaid'];
                }
                if (empty($_SESSION['CAJA']['CU'])) {
                    $_SESSION['CAJA']['CU'] = $_REQUEST['CU'];
                }
                if (empty($_SESSION['CAJA']['CUENTA'])) {
                    $_SESSION['CAJA']['CUENTA'] = $_REQUEST['Cuenta'];
                }
                //********************************************************
                //VALIDAR QUE LA CAJA NO ESTE UTILIZADA POR OTRO USUARIO
                //********************************************************
                $existenciarecibosincuadre = $this->ReciboSinCuadre($this->Empresa, $this->Cutilidad, $_SESSION['CAJA']['CAJAID'], $_SESSION['CAJA']['TIPOCUENTA'], '');
                if ($existenciarecibosincuadre) {
                    if (is_array($_SESSION['CAJA']['RETORNO'])) {
                        $accion = ModuloGetUrl($_SESSION['CAJA']['RETORNO']['contenedor'], $_SESSION['CAJA']['RETORNO']['modulo'], $_SESSION['CAJA']['RETORNO']['tipo'], $_SESSION['CAJA']['RETORNO']['metodo'], $_SESSION['CAJA']['RETORNO']['argumentos']);
                        unset($_SESSION['CAJA']['RETORNO']);
                    } else {
                        $accion = ModuloGetUrl('app', 'Triage', 'user', 'LlamadoCaja');
                    }
                    $this->FormaMensaje('EXISTEN RECIBOS DE OTROS USUARIOS SIN CUADRAR!!', 'CONFIRMACION', $accion, 'Volver');
                    return true;
                }
                //********************************************************
                //FIN VALIDAR QUE LA CAJA NO ESTE UTILIZADA POR OTRO USUARIO
                //********************************************************
            }

            $FechaHoy = date("Y/m/d");
            $Cuenta = $_REQUEST['Cuenta'];
            $TipoId = $_REQUEST['TipoId'];
            $PacienteId = $_REQUEST['PacienteId'];
            $Nivel = $_REQUEST['Nivel'];
            $PlanId = $_REQUEST['PlanId'];
            $Pieza = $_REQUEST['Pieza'];
            $Cama = $_REQUEST['Cama'];
            $FechaC = $_REQUEST['FechaC'];
            $Ingreso = $_REQUEST['Ingreso'];
        }
        //$this->BorrarTemporales();
        //'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'FechaC'=>$FechaC,'Ingreso'=>$Ingreso,'Cuenta'=>$Cuenta,'Valor'=>$Valor,'Cajaid'=>$Cajaid,'Tiponumeracion'=>$Tiponumeracion,'FechaHoy'=>$FechaHoy
        //if(empty($Cuenta) or empty($TipoId) or empty($PacienteId) or empty($Nivel) or empty($PlanId) or empty($Pieza) or empty($Camaor) OR empty($FechaC) or empty($Ingreso) or empty($FechaHoy) or empty($Tiponumeracion))
        if ($_REQUEST['show']) {
            $TipoId = $_SESSION['tmp']['TipoId'];
            $PacienteId = $_SESSION['tmp']['PacienteId'];
            $Nivel = $_SESSION['tmp']['Nivel'];
            $PlanId = $_SESSION['tmp']['PlanId'];
            $Pieza = $_SESSION['tmp']['Pieza'];
            $Cama = $_SESSION['tmp']['Cama'];
            $FechaC = $_SESSION['tmp']['FechaC'];
            $Ingreso = $_SESSION['tmp']['Ingreso'];
            $Cuenta = $_SESSION['tmp']['Cuenta'];
            $Tiponumeracion = $_SESSION['CAJA']['TIPONUMERACION_DEVOLUCIONES'];
            $FechaHoy = $_SESSION['tmp']['FechaHoy'];
        }
        if (!$this->FormaCuenta($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy, $Tiponumeracion)) {
            return false;
        }
        return true;
    }

    function RetornarOrdenesServicio($cosa, $valormodi, $pago) {
        IncludeLib("funciones_facturacion");
        if ($cosa == 1) {
            IncludeLib("tarifario_cargos");

            $IYM = array();

            foreach ($_SESSION['CAJA']['ARRAY_PAGO'] as $index => $codigo) {
                if ($_SESSION['CAJA']['ARRAY_PAGO'][$index]['cargo'] == 'IMD') {
                    unset($_SESSION['CAJA']['ARRAY_PAGO'][$index]);
                }
                if ($codigo['cargo'] != 'INC_CITA' AND $codigo['cargo'] != 'IMD') {
                    $datos = $this->DatosOs($codigo[numero_orden_id]);
                    for ($i = 0; $i < sizeof($datos); $i++) {
                        $dat[$j]['cargo'] = $datos[$i]['cargo'];
                        $dat[$j]['tarifario_id'] = $datos[$i]['tarifario_id'];
                        $dat[$j][descripcion] = $datos[$i]['descripcion'];
                        $dat[$j][numero_orden_id] = $datos[$i]['numero_orden_id'];
                        $dat[$j][os_maestro_cargos_id] = $datos[$i]['os_maestro_cargos_id'];
                        $Arr_Descripcion[$j] = array('des_cargo' => $datos[$i]['descripcion'], 'servicio' => $datos[$i]['servicio'], 'des_servicio' => $codigo[des_servicio], 'numero_orden_id' => $datos[$i]['numero_orden_id'], 'cargo' => $datos[$i]['cargo']);
                        $cargo_liq[] = array('plan_id' => $datos[$i]['plan_id'], 'cargo_cita' => $datos[$i]['cargo_cups']);
                        //$_SESSION['CAJA']['AUX']['datos'][]=$dat[$j];
                        $j++;
                    }
                } else {
                    unset($_SESSION['CAJA']['ARRAY_PAGO'][$index]);
                }
            }
            $emp = BuscarEmpleadorOrden($codigo[numero_orden_id]);

            $cargo_fact = LiquidarCargosCuentaVirtual($_SESSION['CAJA']['ARRAY_PAGO'], $_SESSION['CAJA']['INASISTENCIAS'], $_SESSION['ARREGLO_IYM'], $_SESSION['VECTOR_DESC'], $_SESSION['CAJA']['AUX']['plan_id'], $datos[0][tipo_afiliado_id], $datos[0][rango], $datos[0][semanas_cotizacion], $datos[0][servicio], $_SESSION['CAJA']['AUX']['tipo_id_paciente'], $_SESSION['CAJA']['AUX']['paciente_id'], $emp[tipo_id_empleador], $emp[empleador_id]);

            $valor_cuota_moderadora = $_SESSION['CAJA']['AUX']['liq'][valor_cuota_moderadora];
            $valor_cuota_paciente = $_SESSION['CAJA']['AUX']['liq'][valor_cuota_paciente];
            $valor_descuento_empresa = $_SESSION['CAJA']['AUX']['liq'][valor_descuento_empresa];
            $valor_descuento_paciente = $_SESSION['CAJA']['AUX']['liq'][valor_descuento_paciente];
            $valor_no_cubierto = $_SESSION['CAJA']['AUX']['liq'][valor_no_cubierto];
            $valor_cubierto = $_SESSION['CAJA']['AUX']['liq'][valor_cubierto];
            $valor_total_paciente = $_SESSION['CAJA']['AUX']['liq'][valor_total_paciente];
            $valor_total_empresa = $_SESSION['CAJA']['AUX']['liq'][valor_total_empresa];
            $valor_gravamen_paciente = $_SESSION['CAJA']['AUX']['liq'][valor_gravamen_paciente];
            $valor_gravamen_empresa = $_SESSION['CAJA']['AUX']['liq'][valor_gravamen_empresa];
            $tipo_liquidacion_cargo = $_SESSION['CAJA']['AUX']['liq'][tipo_liquidacion_cargo];

            unset($_SESSION['CAJA']['AUX']['liq']);
            $_SESSION['CAJA']['AUX']['liq'] = $cargo_fact;
            $cargo_tmp_fact = $_SESSION['CAJA']['ARRAY_PAGO'];

            unset($_SESSION['CAJA']['ARRAY_PAGO']);
            unset($_SESSION['CAJA']['ARRAY_PAGO_TMP']);
            //$pago : 1-modificaci� cuota moderadora, 2-modificaci� copago
            if ($pago == 1) {
                $valor_cuota_moderadora = $valormodi;
                $valor_total_paciente = $valor_cuota_paciente + $valor_cuota_moderadora + $valor_no_cubierto + $valor_gravamen_paciente;
                $valor_total_empresa = $valor_cubierto + $valor_gravamen_empresa - ($valor_cuota_paciente + $valor_cuota_moderadora);
            } elseif ($pago == 2) {
                $valor_cuota_paciente = $valormodi;
                $valor_total_paciente = $valor_cuota_paciente + $valor_cuota_moderadora + $valor_no_cubierto + $valor_gravamen_paciente;
                $valor_total_empresa = $valor_cubierto + $valor_gravamen_empresa - ($valor_cuota_paciente + $valor_cuota_moderadora);
            } elseif ($pago == 3) {
                $valor_total_paciente-=$valormodi;
                $valor_descuento_paciente = $valormodi;
                $valor_total_empresa = $valor_cubierto + $valor_gravamen_empresa - ($valor_cuota_paciente + $valor_cuota_moderadora);
            } elseif ($pago == 4) {
                $valor_total_empresa-=$valormodi;
                $valor_descuento_empresa = $valormodi;
                $valor_total_paciente = $valor_cuota_paciente + $valor_cuota_moderadora + $valor_no_cubierto + $valor_gravamen_paciente - $valor_descuento_empresa;
            }
            $_SESSION['CAJA']['AUX']['liq'][valor_cuota_moderadora] = $valor_cuota_moderadora;
            $_SESSION['CAJA']['AUX']['liq'][valor_cuota_paciente] = $valor_cuota_paciente;
            $_SESSION['CAJA']['AUX']['liq'][valor_descuento_empresa] = $valor_descuento_empresa;
            $_SESSION['CAJA']['AUX']['liq'][valor_descuento_paciente] = $valor_descuento_paciente;
            $_SESSION['CAJA']['AUX']['liq'][valor_no_cubierto] = $valor_no_cubierto;
            $_SESSION['CAJA']['AUX']['liq'][valor_cubierto] = $valor_cubierto;
            $_SESSION['CAJA']['AUX']['liq'][valor_total_paciente] = $valor_total_paciente;
            $_SESSION['CAJA']['AUX']['liq'][valor_total_empresa] = $valor_total_empresa;
            $_SESSION['CAJA']['AUX']['liq'][valor_gravamen_paciente] = $valor_gravamen_paciente;
            $_SESSION['CAJA']['AUX']['liq'][valor_gravamen_empresa] = $valor_gravamen_empresa;
            $_SESSION['CAJA']['AUX']['liq'][tipo_liquidacion_cargo] = $tipo_liquidacion_cargo;

            $k = 0;
            foreach ($cargo_fact[cargos] as $w => $v) {
                if ($cargo_tmp_fact[$w]['valor_no_cubierto'] <> $v[valor_no_cubierto]) {
                    $v[valor_no_cubierto] = $cargo_tmp_fact[$w]['valor_no_cubierto'];
                    $_SESSION['CAJA']['AUX']['liq']['cargos'][$w]['valor_no_cubierto'] = $cargo_tmp_fact[$w]['valor_no_cubierto'];
                }
                if ($cargo_tmp_fact[$w]['valor_cargo'] <> $v[valor_cargo]) {
                    $v[valor_cargo] = $cargo_tmp_fact[$w]['valor_cargo'];
                    $_SESSION['CAJA']['AUX']['liq']['cargos'][$w]['valor_cargo'] = $cargo_tmp_fact[$w]['valor_cargo'];
                    $_SESSION['CAJA']['AUX']['liq']['cargos'][$w]['valor_cubierto'] = $cargo_tmp_fact[$w]['valor_cargo'] - $cargo_tmp_fact[$w]['valor_no_cubierto'];
                    $_SESSION['CAJA']['AUX']['liq']['cargos'][$w]['precio_plan'] = $cargo_tmp_fact[$w]['valor_cargo'];
                }
                $_SESSION['CAJA']['ARRAY_PAGO'][] = array('tarifario_id' => $v['tarifario_id'], 'descripcion' => $v[descripcion], 'os_maestro_cargos_id' => $v['os_maestro_cargos_id'], 'numero_orden_id' => $_SESSION['CAJA']['AUX']['datos'][$k]['numero_orden_id'], 'cargo' => $v['cargo'], 'des_servicio' => $Arr_Descripcion[$k][des_servicio], 'cantidad' => $v['cantidad'], 'valor_cargo' => $v[valor_cargo], 'valor_no_cubierto' => $v[valor_no_cubierto], 'autorizacion_int' => $v['autorizacion_int'], 'autorizacion_ext' => $v['autorizacion_ext']);
                $k++;
            }
            unset($_SESSION['CAJA']['OTRAVEZ']);
        }
        $this->FormaOrdenesServicio($_SESSION['CAJA']['AUX']['tipo_id_paciente'], $_SESSION['CAJA']['AUX']['paciente_id'], $_SESSION['CAJA']['AUX']['plan_id']);
        return true;
    }

    //funcion combo
    function TraerTipoDesc($sw) {
        //1 es cuota moderadora , 2 copago
        if ($sw == 1) {
            $info = "sw_moderadora=1";
        } elseif ($sw == 2) {
            $info = "sw_copago=1";
        } elseif ($sw == 3) {
            $info = "sw_cliente=1";
        } elseif ($sw == 4) {
            $info = "sw_empresa=1";
        }
        list($dbconn) = GetDBconn();
        $query = "SELECT tipo_desc_id,sw_moderadora,sw_copago,descripcion
                            FROM tipos_descuento WHERE $info";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }

        return $var;
    }

    function TraerOtro($ingreso) {
        list($dbconn) = GetDBconn();
        $query = "
                    SELECT ingreso
                    FROM ingresos_soat
                    WHERE ingreso = ".$ingreso;
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }

        return $var;
    }
    
    function DatosFactura($cuenta, $PlanId, $TipoId, $PacienteId, $arreglo, $sw_facpaciente=false) {
        list($dbconn) = GetDBconn();

        if (empty($_SESSION['FAC']['EMP_EMP'])) {
            $empresa = $_SESSION['LABORATORIO']['EMPRESA_ID'];
        } else {
            $empresa = $_SESSION['FAC']['EMP_EMP'];
        }

        if (!empty($_SESSION['FAC']['PRE_EMP'])) {
            $prefijo = $_SESSION['FAC']['PRE_EMP'];
        } elseif ($sw_facpaciente OR !empty($_SESSION['CAJA']['FACTURA']['PACIENTE'])) {
            $prefijo = $_SESSION['CAJA']['FACTURA']['PACIENTE']['prefijo'];
        } elseif (empty($_SESSION['FAC']['PRE_EMP']) AND !empty($_SESSION['CAJA']['FACTURA']['EMPRESA']['prefijo'])) {
            $prefijo = $_SESSION['CAJA']['FACTURA']['EMPRESA']['prefijo'];
        } elseif (!empty($_SESSION['FAC']['PRE'])) {
            $prefijo = $_SESSION['FAC']['PRE'];
        }

        if (!empty($_SESSION['FAC']['NUM_EMP'])) {
            $ffiscal = $_SESSION['FAC']['NUM_EMP'];
        } elseif ($sw_facpaciente OR !empty($_SESSION['CAJA']['FACTURA']['PACIENTE'])) {
            $ffiscal = $_SESSION['CAJA']['FACTURA']['PACIENTE']['factura'];
        } elseif (empty($_SESSION['FAC']['NUM_EMP']) AND !empty($_SESSION['CAJA']['FACTURA']['EMPRESA']['factura'])) {
            $ffiscal = $_SESSION['CAJA']['FACTURA']['EMPRESA']['factura'];
        } elseif (!empty($_SESSION['FAC']['NUM'])) {
            $ffiscal = $_SESSION['FAC']['NUM'];
        }




//                 $query = "select (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos) as abonos,
//                                     a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
//                                     c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,d.fecha_cierre,
//                                     e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
//                                     e.residencia_telefono, e.residencia_direccion, f.prefijo, f.factura_fiscal,  d.departamento_actual as dpto, h.descripcion,
//                                     i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid, i.id, j.departamento, k.municipio, d.fecha_registro,
//                                     f.sw_tipo, a.valor_cuota_paciente, a.valor_nocubierto,
//                                     a.valor_cubierto, a.valor_descuento_empresa, a.valor_descuento_paciente,
//                                     a.total_cuenta, a.abono_efectivo, a.abono_cheque, a.abono_tarjetas,
//                                     a.abono_chequespf, a.abono_letras,
//                                     a.valor_total_paciente, a.valor_total_empresa,
//                                     x.texto1, x.texto2, x.mensaje, z.fecha_registro as fechafac,
//                                     c.direccion AS direccion_tercero, c.telefono AS telefono_tercero
//                                     from cuentas as a, planes as b, terceros as c, pacientes as e, fac_facturas_cuentas as f,  departamentos as  h,
//                                     empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d,
//                                     fac_facturas as z, documentos as x
//                                     where a.numerodecuenta=$cuenta
//                                     and f.factura_fiscal=".$_SESSION['FAC']['NUM_EMP']."
//                                     and f.prefijo='".$_SESSION['FAC']['PRE_EMP']."'
//                                     and f.numerodecuenta=$cuenta
//                                     --and a.plan_id=".$PlanId."
//                                     and a.plan_id=b.plan_id and b.tercero_id=c.tercero_id
//                                     and b.tipo_tercero_id=c.tipo_id_tercero
//                                     and z.prefijo=f.prefijo
//                                     and z.factura_fiscal=f.factura_fiscal
//                                     and x.documento_id=z.documento_id
//                                     and d.ingreso=a.ingreso
//                                     --and d.tipo_id_paciente='".$TipoId."'
//                                     --and d.paciente_id='".$PacienteId."'
//                                     and d.tipo_id_paciente=e.tipo_id_paciente
//                                     and d.paciente_id=e.paciente_id and a.numerodecuenta=f.numerodecuenta
//                                     and a.empresa_id=i.empresa_id and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
//                                     and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id
//                                     and d.departamento_actual=h.departamento";

        $query = "
                        SELECT
                        a.abonos,
                        a.numerodecuenta,
                        a.ingreso,
                        a.plan_id,
                        a.empresa_id,
                        b.plan_descripcion,
                        c.nombre_tercero,
                        c.tipo_id_tercero,
                        c.tercero_id,
                        d.tipo_id_paciente,
                        d.paciente_id,
                        d.fecha_cierre,
                        e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
                        e.residencia_telefono,
                        e.residencia_direccion,
                        a.prefijo,
                        a.factura_fiscal,
                        d.departamento_actual as dpto,
                        h.descripcion,
                        i.razon_social,
                        i.direccion,
                        i.telefonos,
                        i.tipo_id_tercero as tipoid,
                        i.id,
                        j.departamento,
                        k.municipio,
                        d.fecha_registro,
                        a.sw_tipo,
                        a.valor_cuota_paciente,
                        a.valor_nocubierto,
                        a.valor_cubierto,
                        a.valor_descuento_empresa,
                        a.valor_descuento_paciente,
                        a.total_cuenta,
                        a.abono_efectivo,
                        a.abono_cheque,
                        a.abono_tarjetas,
                        a.abono_chequespf,
                        a.abono_letras,
                        a.valor_total_paciente,
                        a.valor_total_empresa,
                        x.texto1,
                        x.texto2,
                        x.mensaje,
                        a.fechafac,
                        c.direccion AS direccion_tercero,
                        c.telefono AS telefono_tercero

                        FROM
                        (
                            SELECT
                                a.empresa_id,
                                a.prefijo,
                                a.factura_fiscal,
                                a.fecha_registro AS fechafac,
                                a.documento_id,
                                b.sw_tipo,
                                (c.abono_efectivo + c.abono_cheque + c.abono_tarjetas + c.abono_chequespf + c.abono_bonos) as abonos,
                                c.numerodecuenta,
                                c.ingreso,
                                c.plan_id,
                                c.valor_cuota_paciente,
                                c.valor_nocubierto,
                                c.valor_cubierto,
                                c.valor_descuento_empresa,
                                c.valor_descuento_paciente,
                                c.total_cuenta,
                                c.abono_efectivo,
                                c.abono_cheque,
                                c.abono_tarjetas,
                                c.abono_chequespf,
                                c.abono_letras,
                                c.valor_total_paciente,
                                c.valor_total_empresa
                            FROM
                            fac_facturas as a,
                            fac_facturas_cuentas as b,
                            cuentas as c

                            WHERE
                            a.empresa_id = '" . $empresa . "'
                            AND a.prefijo = '" . $prefijo . "'
                            AND a.factura_fiscal = " . $ffiscal . "
                            AND b.empresa_id = '" . $empresa . "'
                            AND b.prefijo = '" . $prefijo . "'
                            AND b.factura_fiscal = " . $ffiscal . "
                            AND b.numerodecuenta = $cuenta
                            AND c.numerodecuenta = $cuenta
                        ) as a,
                        planes as b,
                        terceros as c,
                        ingresos as d,
                        pacientes as e,
                        departamentos as h,
                        empresas as i,
                        tipo_dptos as j,
                        tipo_mpios as k,
                        documentos as x

                        WHERE
                        b.plan_id = a.plan_id
                        AND c.tipo_id_tercero = b.tipo_tercero_id
                        AND c.tercero_id = b.tercero_id
                        AND d.ingreso = a.ingreso
                        AND e.paciente_id = d.paciente_id
                        AND e.tipo_id_paciente = d.tipo_id_paciente
                        AND h.departamento = d.departamento_actual
                        AND i.empresa_id = a.empresa_id
                        AND j.tipo_pais_id = i.tipo_pais_id
                        AND j.tipo_dpto_id = i.tipo_dpto_id
                        AND k.tipo_pais_id = i.tipo_pais_id
                        AND k.tipo_dpto_id = i.tipo_dpto_id
                        AND k.tipo_mpio_id = i.tipo_mpio_id
                        AND x.documento_id = a.documento_id
                        
              ";
        print_r("DatosFactura_1: ".$query);
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $vars = $result->GetRowAssoc($ToUpper = false);

        $result->Close();
        //unset($_SESSION['FAC']);
        return $vars;
    }

    function TraerTipoCambio($sw) {
        list($dbconn) = GetDBconn();
        //1 es cuota moderadora , 2 copago
        if ($sw == 1) {
            $query = "SELECT motivo_cambio_cuota_moderadora_id,descripcion
                            FROM motivos_cambio_cuota_moderadora;";
        } elseif ($sw == 2) {
            $query = "SELECT motivo_cambio_copago_id,descripcion
                            FROM motivos_cambio_copago;";
        }
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        $i = 0;
        while (!$result->EOF) {
            $var[$i] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarCriterios() {
        UNSET($_SESSION['PAGARE']['valorc']);
        UNSET($_SESSION['PAGARE']['valorcr']);
        UNSET($_SESSION['PAGARE']['valord']);
        if ($_REQUEST['volver'] == 'VOLVER') {
            UNSET($_SESSION['PAGARE']);
        }

        if ($_REQUEST['Documento'] <> NULL) {
            $tipodoc = $_REQUEST['TipoDocumentop'];
            $doc = $_REQUEST['Documento'];
            $condicion1 = " AND C.tipo_id_paciente='" . $tipodoc . "' AND C.paciente_id=" . $doc . "";
        }
        else
            $condicion1 = "";
        if ($_REQUEST['Pagare'] <> NULL) {
            $pagare = $_REQUEST['Pagare'];
            $condicion2 = " AND A.numero=" . $pagare . "";
        }
        else
            $condicion2 = "";
        if ($_REQUEST['Cuenta'] <> NULL) {
            $cuenta = $_REQUEST['Cuenta'];
            $condicion3 = " AND A.numerodecuenta=" . $cuenta . "";
        }
        else
            $condicion3 = "";
        if ($_REQUEST['TipoDocumentoRes'] <> NULL) {
            $docres = $_REQUEST['DocumentoRes'];
            $tipodocres = $_REQUEST['TipoDocumentoRes'];
            $tablas1 = ", pagares_responsables E, terceros F";
            $condicion4 = " AND E.tipo_id_tercero=F.tipo_id_tercero AND E.tercero_id=F.tercero_id
                                        AND A.prefijo=E.prefijo AND A.numero=E.numero
                                     AND E.tipo_id_tercero='" . $tipodocres . "' AND E.tercero_id=" . $docres . "";
        } else {
            $condicion4 = "";
            $tablas1 = "";
        }

        if ($_REQUEST['Nombres'] <> NULL) {
            $nombres = $_REQUEST['Nombres'];
            $select1 = ", D.tipo_id_paciente, D.paciente_id, D.primer_nombre||D.segundo_nombre||D.primer_apellido||D.segundo_apellido AS nombre_paciente";
            //$tablas=", pacientes D";
            $condicion5 = " AND (D.primer_nombre LIKE(UPPER('$nombres%')) OR D.segundo_nombre LIKE(UPPER('$nombres%')))";
        } else {
            $condicion5 = "";
            $tablas = "";
        }

        if ($_REQUEST['NombresRes'] <> NULL) {
            $nombre = $_REQUEST['NombresRes'];
            $tablas2 = ", pagares_responsables E, terceros F";
            $condicion6 = " AND A.prefijo=E.prefijo AND A.numero=E.numero
                                        AND E.tipo_id_tercero=F.tipo_id_tercero AND E.tercero_id=F.tercero_id
                                        AND F.nombre_tercero LIKE(UPPER('%$nombre%'))";
        } else {
            $condicion6 = "";
            $tablas2 = "";
        }

        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        //AND A.prefijo=".."
        $busca = "SELECT DISTINCT A.numero, A.numerodecuenta, A.vencimiento,
                                A.tipo_forma_pago_id, A.valor, A.fecha_registro, A.prefijo,
                                A.empresa_id, A.documento_id, B.plan_id
                                $select1
                        FROM pagares A,
                                cuentas B,
                                ingresos C,
                                pacientes D
                                $tablas1
                                $tablas2
                        WHERE A.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                AND A.sw_estado ='1'
                                AND A.numerodecuenta=B.numerodecuenta
                                AND B.ingreso=C.ingreso
                                AND C.tipo_id_paciente=D.tipo_id_paciente
                                AND C.paciente_id=D.paciente_id
                                --AND B.estado='0'
                                AND B.estado IN('0','1','2','3')
                                $condicion1
                                $condicion2
                                $condicion3
                                $condicion4
                                $condicion5
                                $condicion6";
        $resulta = $dbconn->execute($busca);
        while (!$resulta->EOF) {
            $var[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        $this->FormaMetodoBuscarPagare($var);
        return true;
    }

    function FormaPago($formapago) {
        list($dbconn) = GetDBconn();
        $busca = "SELECT descripcion
                            FROM tipos_formas_pago
                            WHERE tipo_forma_pago_id=" . $formapago . "";

        $resulta = $dbconn->execute($busca);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        if (!$resulta->EOF) {
            $var = $resulta->GetRowAssoc($ToUpper = false);
        }
        return $var;
    }

    function DatosPaciente($cuenta) {
        list($dbconn) = GetDBconn();
        $busca = "SELECT DISTINCT D.tipo_id_paciente,D.paciente_id,
                                         D.primer_nombre,D.segundo_nombre,D.primer_apellido,D.segundo_apellido
                            FROM pagares A,
                                    cuentas B,
                                    ingresos C,
                                    pacientes D
                            WHERE A.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                    AND A.numerodecuenta=B.numerodecuenta
                                    AND B.ingreso=C.ingreso
                                    AND C.tipo_id_paciente=D.tipo_id_paciente
                                    AND C.paciente_id=D.paciente_id
                                    AND A.numerodecuenta=" . $cuenta . "";

        $resulta = $dbconn->execute($busca);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        if (!$resulta->EOF) {
            $var = $resulta->GetRowAssoc($ToUpper = false);
        }
        return $var;
    }

    function GetTiposParentescos() {
        list($dbconn) = GetDBconn();
        $busca = "SELECT tipo_parentesco_id, descripcion
                            FROM tipos_parentescos";

        $resulta = $dbconn->execute($busca);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        while (!$resulta->EOF) {
            $var[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function ComboConceptos() {
        list($dbconn) = GetDBconn();
        $busca = "SELECT DISTINCT a.grupo_concepto,a.descripcion
              FROM grupos_conceptos a,caja_conceptos b where b.grupo_concepto =a.grupo_concepto
              ORDER BY a.grupo_concepto";

        $resulta = $dbconn->execute($busca);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        while (!$resulta->EOF) {
            $var[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function ComboEntidadConfirma() {
        list($dbconn) = GetDBconn();
        $busca = "SELECT entidad_confirma,descripcion
                FROM confirmacion_entidades
                ORDER BY entidad_confirma";

        $resulta = $dbconn->execute($busca);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        while (!$resulta->EOF) {
            $var[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function ConsultarConceptos() {
        list($dbconn) = GetDBconn();
        $busca = "SELECT c.subgrupo_tarifario_descripcion,
                                    c.grupo_tarifario_id,
                                    c.subgrupo_tarifario_id
            FROM grupos_tarifarios b,
                                subgrupos_tarifarios c
            WHERE b.grupo_tarifario_id ='00'
                                AND b.grupo_tarifario_id=c.grupo_tarifario_id
                                AND c.subgrupo_tarifario_id<>'00'
            ORDER BY c.subgrupo_tarifario_descripcion;";
        $resulta = $dbconn->execute($busca);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        while (!$resulta->EOF) {
            $var[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarConceptos($factura, $grupo) {
        if (!empty($grupo) AND $grupo != -1){
            $cond = " AND a.grupo_concepto='$grupo'";
            $sqlconcepto = "";
             if(isset($_SESSION['CAJA']['concepto_caja'])){
                 $sqlconcepto = " AND b.concepto_id = {$_SESSION['CAJA']['concepto_caja']} ";
             }
        }  else{
            $cond = "";
            $sqlconcepto = "";
        }
        list($dbconn) = GetDBconn();
        
        
        
        
        if ($factura == 'credito') {
            $busca = "SELECT b.grupo_concepto,b.descripcion,b.precio,
                                        b.porcentaje_gravamen,b.sw_precio_manual,b.sw_cantidad,
                                        b.concepto_id
                            FROM grupos_conceptos a,conceptos_caja_conceptos b
                            WHERE a.grupo_concepto=b.grupo_concepto
                            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                            AND a.grupo_concepto=b.grupo_concepto
                            AND b.sw_credito='1'
                            $sqlconcepto
                            $cond
                            ORDER BY a.grupo_concepto;";
        } else
        if ($factura == 'contado') {
            $busca = "SELECT b.grupo_concepto,b.concepto_id,b.descripcion,b.precio,
                                        b.porcentaje_gravamen,b.sw_precio_manual,b.sw_cantidad
                            FROM grupos_conceptos a,conceptos_caja_conceptos b
                            WHERE a.grupo_concepto=b.grupo_concepto
                            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                            AND a.grupo_concepto=b.grupo_concepto
                            AND b.sw_contado='1'
                            $sqlconcepto
                            $cond
                            ORDER BY a.grupo_concepto;";
        }
        
        
        $resulta = $dbconn->execute($busca);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        while (!$resulta->EOF) {
            $var[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }

        return $var;
    }

    function BuscarConceptos1($factura, $concepto, $grupo) {
        list($dbconn) = GetDBconn();
        if ($factura == 'credito') {
            $busca = "SELECT  b.grupo_concepto,
                        b.descripcion,b.precio,
                        b.porcentaje_gravamen,
                        b.sw_precio_manual,b.sw_cantidad,
                        b.sw_modificar_gravamen
                FROM grupos_conceptos a,conceptos_caja_conceptos b
                WHERE a.grupo_concepto=b.grupo_concepto
                AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                AND a.grupo_concepto=b.grupo_concepto
                AND a.grupo_concepto='$grupo'
                AND b.concepto_id='$concepto'
                AND b.sw_credito='1'
                ORDER BY a.grupo_concepto;";
        } else
        if ($factura == 'contado') {
            $busca = "SELECT  b.grupo_concepto,b.descripcion,b.precio,
                    b.porcentaje_gravamen,b.sw_precio_manual,
                    b.sw_cantidad,
                    b.sw_modificar_gravamen
            FROM grupos_conceptos a,conceptos_caja_conceptos b
            WHERE a.grupo_concepto=b.grupo_concepto
            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
            AND a.grupo_concepto=b.grupo_concepto
            AND a.grupo_concepto='$grupo'
            AND b.concepto_id='$concepto'
            AND b.sw_contado='1'
            ORDER BY a.grupo_concepto;";
        }
        $resulta = $dbconn->execute($busca);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        while (!$resulta->EOF) {
            $var[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }

        return $var;
    }

    function ConsultaGrupoConceptos($grupo) {
        list($dbconn) = GetDBconn();
        if ($grupo == 'contado') {
            $busca = "SELECT DISTINCT a.grupo_concepto,a.descripcion
                            FROM grupos_conceptos a,conceptos_caja_conceptos b
                            WHERE a.grupo_concepto=b.grupo_concepto
                            AND b.sw_contado='1'
                            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                            AND a.grupo_concepto=b.grupo_concepto
                            ORDER BY a.grupo_concepto;";
        } else
        if ($grupo == 'credito') {
            $busca = "SELECT DISTINCT a.grupo_concepto,a.descripcion
                            FROM grupos_conceptos a,conceptos_caja_conceptos b
                            WHERE a.grupo_concepto=b.grupo_concepto
                            AND b.sw_credito='1'
                            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                            AND a.grupo_concepto=b.grupo_concepto
                            ORDER BY a.grupo_concepto;";
        }
        
       //echo "<pre>".$busca."</pre>";
        $resulta = $dbconn->execute($busca);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        while (!$resulta->EOF) {
            $var[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function InsertarConceptos() {
        $emp = $_SESSION['CAJA']['EMPRESA'];
        $cutilidad = $_SESSION['CAJA']['CENTROUTILIDAD'];
       // echo " ====================". print_r($_SESSION['CAJA']); 
        //contado credito
        $tipo_factura = $_SESSION['CAJA']['FACTURA'];

        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $total_efectivo = $_REQUEST['efectivo'];
        $total_cheques = $_REQUEST['cheque'];
        $total_tarjetas = $_REQUEST['tarjetad'] + $_REQUEST['tarjetac'];
        $bono = $_SESSION['CAJA']['BONO'];
        //$total_abono=$total_efectivo+$total_cheques+$total_tarjetas+$bono;
        
        if ($_REQUEST['Devuelta'] > 0 AND $total_efectivo > $_REQUEST['Devuelta'])
            $total_efectivo-=$_REQUEST['Devuelta'];
        else if ($_REQUEST['Devuelta'] > 0 AND $total_cheques > $_REQUEST['Devuelta'])
            $total_cheques-=$_REQUEST['Devuelta'];
        else if ($_REQUEST['Devuelta'] > 0 AND $total_tarjetas > $_REQUEST['Devuelta'])
            $total_tarjetas-=$_REQUEST['Devuelta'];
        
        $total_abono = $_REQUEST['aPagar'];
        $tipoTerceroFacPaciente = $_SESSION['CAJA']['TIPO_ID_TERCERO'];
        $idTerceroFacPaciente = $_SESSION['CAJA']['TERCEROID'];
        //-------------------------------------------------------------
        //contado
        if ($tipo_factura == 'contado') {
            //----------------NUMERACION-------------------------
            //sacamos la numeracion de la factura de contado
            $var = $this->AsignarNumero($_SESSION['CAJA']['PRECONTADO'], &$dbconn);
            $factura = $var[numero];
            $prefijo = $var[prefijo];
            //----------------FIN NUMERACION-----------------------
            //sw_clase_factura 0=>contado 1=>credito
            $sw_clase = 0;
            $documento = $_SESSION['CAJA']['PRECONTADO'];
        } elseif ($tipo_factura == 'credito') {
            //----------------NUMERACION-------------------------
            //cambiamos numeraciones factura credito
            $va = $this->AsignarNumero($_SESSION['CAJA']['PRECREDITO'], &$dbconn);
            $factura = $va[numero];
            $prefijo = $va[prefijo];
            //----------------FIN NUMERACION-----------------------
            //sw_clase_factura 0=>contado 1=>credito
            $sw_clase = 1;
            $documento = $_SESSION['CAJA']['PRECREDITO'];
        } else {
            echo "NO ESTA ENVIANDO EL TIPO DE FACTURA: contado o credito";
            exit;
        }
		
		       
        //sw_clase_factura 0=>contado 1=>credito
        //tipo_factura 5 porque es conceptos
        $query = "INSERT INTO fac_facturas(
                        empresa_id, prefijo, factura_fiscal, estado, usuario_id, fecha_registro, plan_id, tipo_id_tercero, tercero_id, sw_clase_factura,
                        documento_id, tipo_factura, centro_utilidad)
                 VALUES('$emp','$prefijo',$factura,0," . UserGetUID() . ",'now()', NULL,'$tipoTerceroFacPaciente','$idTerceroFacPaciente','$sw_clase'
                    ," . $documento . ",5,'$cutilidad')";
        
       // print_r('<pre>fac_facturas: '.$query);
        
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar fac_facturas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        //contado se inserta en fac_facturas_Contado
        if ($tipo_factura == 'contado') {
            $sqls = "INSERT into fac_facturas_contado(
                                    empresa_id,
                                    factura_fiscal,
                                    centro_utilidad,
                                    prefijo,
                                    total_abono,
                                    total_efectivo,
                                    total_cheques,
                                    total_tarjetas,
                                    tipo_id_tercero,
                                    tercero_id,
                                    estado,
                                    fecha_registro,
                                    usuario_id,
                                    caja_id,
                                    total_bonos)
                                VALUES('$emp','$factura','$cutilidad',
                                '$prefijo',$total_abono,$total_efectivo,$total_cheques,$total_tarjetas,
                                '$tipoTerceroFacPaciente','$idTerceroFacPaciente',0,'now()'," . UserGetUID() . ",
                                '" . $_SESSION['CAJA']['CAJAID'] . "',$bono);";
            
            //print_r('<pre>fac_facturas_contado: '.$sqls);

            $dbconn->Execute($sqls);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al insertar en fac_facturas_contado";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }

        //------------va a insertar en las tablas de conceptos
        //-------busco los datos
        $sql = "SELECT * FROM tmp_detalle_conceptos
                                    WHERE tipo_id_tercero='$tipoTerceroFacPaciente'
                                    AND tercero_id='$idTerceroFacPaciente'
                                    AND empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "';";
        $result = $dbconn->execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        while (!$result->EOF) {
            $dat[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();

        $total_factura = $gravamen = 0;
        for ($i = 0; $i < sizeof($dat); $i++) {
            $query = "SELECT nextval('fac_facturas_conceptos_fac_factura_concepto_id_seq')";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al traer la secuencia cuentas_numerodecuenta_seq ";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            $id = $result->fields[0];
            if (empty($dat[$i]['descripcion'])) {
                $dat[$i]['descripcion'] = 'NULL';
            } else {
                $dat[$i]['descripcion'] = "'" . $dat[$i]['descripcion'] . "'";
            }

            $sql = "INSERT INTO fac_facturas_conceptos
                                                            (fac_factura_concepto_id,
                                                            empresa_id,
                                                            prefijo,
                                                            factura_fiscal,
                                                            sw_tipo,
                                                            cantidad,
                                                            precio,
                                                            valor_total,
                                                            porcentaje_gravamen,
                                                            valor_gravamen,
                                                            concepto,
                                                            caja_id)
                                    VALUES(         $id,
                                                            '" . $dat[$i]['empresa_id'] . "',
                                                            '$prefijo',
                                                            '$factura',
                                                            '0',
                                                            " . $dat[$i]['cantidad'] . ",
                                                            " . $dat[$i]['precio'] . ",
                                                            " . $dat[$i]['valor_total'] . ",
                                                            " . $dat[$i]['porcentaje_gravamen'] . ",
                                                            " . $dat[$i]['valor_gravamen'] . ",
                                                            " . $dat[$i]['descripcion'] . ",
                                                            " . $_SESSION['CAJA']['CAJAID'] . ");";
           // print_r('<pre>fac_facturas_conceptos: '.$sql);
            $dbconn->execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error INSERT INTO fac_facturas_conceptos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                $dbconn->RollbackTrans();
                return false;
            }

            $sql = "INSERT INTO fac_facturas_conceptos_dc(fac_factura_concepto_id,
                                                                    empresa_id,
                                                                    concepto_id,
                                                                    grupo_concepto)
                                VALUES(     $id,
                                                '" . $dat[$i]['empresa_id'] . "',
                                                '" . $dat[$i]['concepto_id'] . "',
                                                '" . $dat[$i]['grupo_concepto'] . "');";
            
            //print_r('<pre>fac_facturas_conceptos_dc: '.$sql);
            $dbconn->execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error INSERT INTO fac_facturas_conceptos_dc";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                $dbconn->RollbackTrans();
                return false;
            }
            //AQUI SUMO LOS VALORES PARA TOTAL_FACTURA Y GRAVAMEN Q VAN EN FAC_FACTURAS, NO HICE TRIGGER
            $total_factura += $dat[$i]['valor_total'];
            $gravamen += $dat[$i]['valor_gravamen'];
        }
        //actualizao los valores totales en fac_facturas, solo total_factura y gravamen los otros no se utilizan
        $sql = "UPDATE fac_facturas SET total_factura=$total_factura, gravamen=$gravamen
                            WHERE empresa_id='$emp'
                            and prefijo='$prefijo' and factura_fiscal=$factura;";
        
        
        $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error UPDATE fac_facturas ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $sql = "DELETE FROM tmp_detalle_conceptos
                            WHERE tipo_id_tercero='$tipoTerceroFacPaciente'
                            AND tercero_id='$idTerceroFacPaciente'
                            AND empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "';";
        $resul = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error DELETE FROM tmp_detalle_conceptos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
      
        $_SESSION['CAJA']['FACTURACONCEPTO'] = $factura;
        $_SESSION['CAJA']['PREFIJOCONCEPTO'] = $prefijo;

        $arreglo = array('empresa' => $_SESSION['CAJA']['EMPRESA'], 'prefijo' => $_SESSION['CAJA']['PREFIJOCONCEPTO']
                , 'factura' => $_SESSION['CAJA']['FACTURACONCEPTO'], 'tipo_factura' => $tipo_factura);
        
        
         $sql_1 = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
         $sql_2 = AutoCarga::factory("ContratacionProductosClienteSQL", "classes", "app", "ContratacionProductosCliente");
        
         
         $Tercero = $sql_2->ConsultarTercero_Contrato($_SESSION['CAJA']['EMPRESA'], $idTerceroFacPaciente, $tipoTerceroFacPaciente);
         $Parametros_Retencion = $sql_1->Parametros_Retencion($_SESSION['CAJA']['EMPRESA']);
          
          $impuestos = $this->TraerPorcentajeImpuestos($_SESSION['CAJA']['EMPRESA'], $idTerceroFacPaciente, $tipoTerceroFacPaciente);
          
          $sqlimpuestos =  $this->ActualizarImpuestosFactura($impuestos, $_SESSION['CAJA']['EMPRESA'], $prefijo, $factura);
          
          
           $resul = $dbconn->Execute($sqlimpuestos);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error Actualizar impuestos factura";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
           
             $dbconn->CommitTrans();
       /* IncludeLib("calcular_impuestos");
       $resultadoFI = UpdateImpuestosFacFacturas($_SESSION['CAJA']['EMPRESA'], $prefijo, $factura);*/

        /*IncludeLib("WSInsertarFacturaConceptos");
        $resultadoFI = FWSInsertarFacturaConceptos($_SESSION['CAJA']['EMPRESA'], $prefijo, $factura);*/
        
        
        
        
        
        if($resultadoFI['crearInformacionContableResult']['estado']==FALSE){
            $mensajeFI  = "INTEGRACI?N FI "."<BR>";
            $mensajeFI .=  $resultadoFI['crearInformacionContableResult']['descripcion']." Factura N?mero: ".$resultadoFI['crearInformacionContableResult']['numerodoc']."<BR>";
        }
        
        $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarTercero');
        $mensaje = 'FACTURA CONCEPTOS GENERADA'."<BR>".$mensajeFI;
        $botonC = 'facturaconceptos';
        if (!$this->LlamaFormaMensaje($mensaje, $action, $botonC, $arreglo)) {
            return false;
        }
        return true;
    }
    
    function ActualizarImpuestosFactura($impuestos, $empresa, $prefijo, $factura){
        
        
        $sql = " UPDATE fac_facturas set 
            porcentaje_rtf = {$impuestos['porcentaje_rtf']}, 
            porcentaje_ica = {$impuestos['porcentaje_ica']},  
            porcentaje_reteiva = {$impuestos['porcentaje_reteiva']},  
            porcentaje_cree = {$impuestos['porcentaje_cree']}
             WHERE factura_fiscal = '{$factura}' AND empresa_id= '{$empresa}' AND prefijo= '{$prefijo}' ";
            
             //echo $sql;

            
            return $sql;
    }
    
    function TraerPorcentajeImpuestos($empresa_id, $idTerceroFacPaciente, $tipoTerceroFacPaciente){
        $sql_1 = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
         $sql_2 = AutoCarga::factory("ContratacionProductosClienteSQL", "classes", "app", "ContratacionProductosCliente");
        
         
         $Tercero = $sql_2->ConsultarTercero_Contrato($empresa_id, $idTerceroFacPaciente, $tipoTerceroFacPaciente);
         $Parametros_Retencion = $sql_1->Parametros_Retencion($empresa_id);
         
        $porcentaje_rtf = '0';
        $porcentaje_ica = '0';
        $porcentaje_reteiva = '0';
        $porcentaje_cree = '0';
        
        

        if ($Parametros_Retencion['sw_rtf'] == '1' || $Parametros_Retencion['sw_rtf'] == '3')
            $porcentaje_rtf = $Tercero['porcentaje_rtf'];
        if ($Parametros_Retencion['sw_ica'] == '1' || $Parametros_Retencion['sw_ica'] == '3')
            $porcentaje_ica = $Tercero['porcentaje_ica'];
        if ($Parametros_Retencion['sw_reteiva'] == '1' || $Parametros_Retencion['sw_reteiva'] == '3')
            $porcentaje_reteiva = $Tercero['porcentaje_reteiva'];
        
        if(!empty($Tercero['porcentaje_cree'])){
            $porcentaje_cree = $Tercero['porcentaje_cree'];
        }
         
        
        return array("porcentaje_rtf" => $porcentaje_rtf, "porcentaje_ica" => $porcentaje_ica, 
                            "porcentaje_reteiva" => $porcentaje_reteiva, "porcentaje_cree" => $porcentaje_cree,
                            "base_rtf" =>$Parametros_Retencion['base_rtf'], "base_ica" => $Parametros_Retencion['base_ica'], 'base_reteiva' => $Parametros_Retencion['base_reteiva'] );
         
    }
    


    function LlamaFormaMensaje($mensaje, $action, $botonC, $arreglo) {
        if (empty($mensaje)
                AND empty($action)
                AND empty($botonC)
                AND empty($arreglo)) {
            $mensaje = $_REQUEST[mensaje];
            $action = $_REQUEST[action];
            $botonC = $_REQUEST[botonC];
            $arreglo = $_REQUEST[arreglo];
        }
        if (!$this->FormaMensaje($mensaje, 'CONFIRMACION', $action, 'Volver', $botonC, $arreglo)) {
            return false;
        }
        return true;
    }

    function DatosFacturaConcepto($empresa, $prefijo, $factura, $tipo_factura) {
        //DATOS GENERALES
        list($dbconn) = GetDBconn();

        if ($tipo_factura == 'contado') {
            $query = "SELECT i.razon_social, i.direccion, i.telefonos,
                        i.tipo_id_tercero as tipoid,
                        i.id, j.departamento, k.municipio,
                        f.*, e.texto1, e.texto2,
                        e.mensaje, d.nombre, g.nombre_tercero,
                        g.tercero_id, g.tipo_id_tercero as tipotercero,
                        dep.descripcion as centro_atencion,
                        g.direccion as direccion_tercero,
                        g.telefono as telefono_tercero,
                        ffc.total_abono,ffc.total_efectivo,
                        ffc.total_cheques, ffc.total_tarjetas,
                        ffcpto.porcentaje_gravamen,
                        ffcpto.valor_gravamen,
                        f.tercero_id,
                        f.tipo_id_tercero
                       FROM empresas as i, tipo_dptos as j, tipo_mpios as k,
                            documentos as e,fac_facturas as f, system_usuarios as d, terceros as g,
                            fac_facturas_contado ffc, cajas_rapidas as cr, departamentos as dep,
                            fac_facturas_conceptos ffcpto
                        WHERE f.empresa_id='$empresa'
                            and f.prefijo='$prefijo'
                            and f.factura_fiscal=$factura
                            and f.empresa_id=ffcpto.empresa_id
                            and f.prefijo=ffcpto.prefijo
                            and f.factura_fiscal=ffcpto.factura_fiscal
                            and f.prefijo=ffc.prefijo
                            and f.factura_fiscal=ffc.factura_fiscal
                            and ffc.caja_id=cr.caja_id
                            and cr.departamento=dep.departamento
                            and f.usuario_id=d.usuario_id
                            and i.empresa_id=f.empresa_id
                            and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
                            and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id
                            and i.tipo_mpio_id=k.tipo_mpio_id
                            and f.documento_id=e.documento_id
                            and f.tipo_id_tercero=g.tipo_id_tercero
                            and f.tercero_id=g.tercero_id";
        } elseif ($tipo_factura == 'credito') {
            $query = "SELECT i.razon_social, i.direccion, i.telefonos,
                                                        i.tipo_id_tercero as tipoid,
                                                        i.id, j.departamento, k.municipio,
                                                        f.*, e.texto1, e.texto2,
                                                        e.mensaje, d.nombre, g.nombre_tercero,
                                                        g.tercero_id, g.tipo_id_tercero as tipotercero,
                                                        dep.descripcion as centro_atencion,
                                                        g.direccion as direccion_tercero,
                                                        g.telefono as telefono_tercero,
                                                        ffc.porcentaje_gravamen,
                                                        ffc.valor_gravamen,
                                                        f.tercero_id,
                                                        f.tipo_id_tercero
                                        FROM empresas as i, tipo_dptos as j, tipo_mpios as k,
                                        documentos as e,fac_facturas as f, system_usuarios as d, terceros as g,
                                        fac_facturas_conceptos ffc, cajas_rapidas as cr, departamentos as dep
                                        WHERE f.empresa_id='$empresa'
                                        and f.prefijo='$prefijo'
                                        and f.factura_fiscal=$factura
                                        and f.prefijo=ffc.prefijo
                                        and f.factura_fiscal=ffc.factura_fiscal
                                        and f.sw_clase_factura='1'
                                        and ffc.caja_id=cr.caja_id
                                        and cr.departamento=dep.departamento
                                        and f.usuario_id=d.usuario_id
                                        and i.empresa_id=f.empresa_id
                                        and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
                                        and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id
                                        and i.tipo_mpio_id=k.tipo_mpio_id
                                        and f.documento_id=e.documento_id
                                        and f.tipo_id_tercero=g.tipo_id_tercero
                                        and f.tercero_id=g.tercero_id";
        }
        
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var[] = $resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();

        //DATOS Y DETALLE FACTURA (solo conceptos no inventarios)
        /*                  $query = "SELECT a.*,
          case when a.concepto isnull then c.descripcion else a.concepto  end as descripcion
          FROM fac_facturas_conceptos as a,
          fac_facturas_conceptos_dc as b, conceptos_caja_conceptos as c
          WHERE a.empresa_id='$empresa'
          and a.prefijo='$prefijo'
          and a.factura_fiscal=$factura
          and a.fac_factura_concepto_id=b.fac_factura_concepto_id
          and b.concepto_id=c.concepto_id
          and b.grupo_concepto=c.grupo_concepto
          and b.empresa_id=c.empresa_id"; */
        $query = "SELECT a.*,
                                        a.concepto,c.descripcion
                                        FROM fac_facturas_conceptos as a,
                                        fac_facturas_conceptos_dc as b, conceptos_caja_conceptos as c
                                        WHERE a.empresa_id='$empresa'
                                        and a.prefijo='$prefijo'
                                        and a.factura_fiscal=$factura
                                        and a.fac_factura_concepto_id=b.fac_factura_concepto_id
                                        and b.concepto_id=c.concepto_id
                                        and b.grupo_concepto=c.grupo_concepto
                                        and b.empresa_id=c.empresa_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $var;
    }

    // impresion pos de caja inventarios
//  function ImpresionReportesInventarios()
//  {
//          list($dbconn) = GetDBconn();
//          if (!IncludeFile("classes/reports/reports.class.php")) {
//                  $this->error = "No se pudo inicializar la Clase de Reportes";
//                  $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
//                  return false;
//          }
//
//          //DATOS GENERALES
//       $query = "SELECT i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid,
//                              i.id, j.departamento, k.municipio, f.*, e.texto1, e.texto2,
//                              e.mensaje, d.nombre, g.nombre_tercero, g.tercero_id, g.tipo_id_tercero as tipotercero
//                              FROM empresas as i, tipo_dptos as j, tipo_mpios as k,
//                              documentos as e,fac_facturas as f, system_usuarios as d, terceros as g
//                              WHERE f.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
//                              and f.prefijo='".$_SESSION['CAJA']['PREFIJOINVENTARIO']."'
//                              and f.factura_fiscal=".$_SESSION['CAJA']['FACTURAINVENTARIO']."
//                              and f.usuario_id=d.usuario_id
//                              and i.empresa_id=f.empresa_id
//                              and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
//                              and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id
//                              and i.tipo_mpio_id=k.tipo_mpio_id
//                              and f.documento_id=e.documento_id
//                              and f.tipo_id_tercero=g.tipo_id_tercero
//                              and f.tercero_id=g.tercero_id";
//          $resulta = $dbconn->Execute($query);
//          if ($dbconn->ErrorNo() != 0) {
//                  $this->error = "Error al Cargar el Modulo";
//                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                  return false;
//          }
//          $var[]=$resulta->GetRowAssoc($ToUpper = false);
//          $resulta->Close();
//
//          //DATOS Y DETALLE FACTURA (solo inventarios no conceptos)
//          $query = "SELECT a.*,
//                              case when a.concepto isnull then c.descripcion else a.concepto  end as descripcion
//                              FROM fac_facturas_inventarios as a,
//                              fac_facturas_conceptos_di as b, inventarios_productos as c
//                              WHERE a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
//                              and a.prefijo='".$_SESSION['CAJA']['PREFIJOINVENTARIO']."'
//                              and a.factura_fiscal=".$_SESSION['CAJA']['FACTURAINVENTARIO']."
//                              and a.fac_factura_concepto_id=b.fac_factura_concepto_id
//                              and b.concepto_id=c.concepto_id
//                              and b.grupo_concepto=c.grupo_concepto
//                              and b.empresa_id=c.empresa_id";
//          $result = $dbconn->Execute($query);
//          if ($dbconn->ErrorNo() != 0) {
//                          $this->error = "Error al Cargar el Modulo";
//                          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                          return false;
//          }
//          if(!$result->EOF)
//          {
//                  while(!$result->EOF)
//                  {
//                                  $var[]=$result->GetRowAssoc($ToUpper = false);
//                                  $result->MoveNext();
//                  }
//                  $result->Close();
//                  $classReport = new reports;
//                  $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
//                  $reporte=$classReport->PrintReport('pos','app','CajaGeneral','facturaInventario',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
//                  if(!$reporte){
//                          $this->error = $classReport->GetError();
//                          $this->mensajeDeError = $classReport->MensajeDeError();
//                          unset($classReport);
//                          return false;
//                  }
//
//                  $resultado=$classReport->GetExecResultado();
//          }
//          $var='';
//          if(!empty($resultado[codigo])){
//                  "El PrintReport retorno : " . $resultado[codigo] . "<br>";
//          }
//          //aqui va donde vuelve
//          $this->FormaVentanaImpresionInventarioProducto();
//          return true;
//  }
    // impresion pos de caja conceptos
    function ImpresionReportesConceptos() {
        list($dbconn) = GetDBconn();
        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }

        //DATOS GENERALES
        $query = "SELECT i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid,
                                i.id, j.departamento, k.municipio, f.*, e.texto1, e.texto2,
                                e.mensaje, d.nombre, g.nombre_tercero, g.tercero_id, g.tipo_id_tercero as tipotercero
                                FROM empresas as i, tipo_dptos as j, tipo_mpios as k,
                                documentos as e,fac_facturas as f, system_usuarios as d, terceros as g
                                WHERE f.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                and f.prefijo='" . $_SESSION['CAJA']['PREFIJOCONCEPTO'] . "'
                                and f.factura_fiscal=" . $_SESSION['CAJA']['FACTURACONCEPTO'] . "
                                and f.usuario_id=d.usuario_id
                                and i.empresa_id=f.empresa_id
                                and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
                                and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id
                                and i.tipo_mpio_id=k.tipo_mpio_id
                                and f.documento_id=e.documento_id
                                and f.tipo_id_tercero=g.tipo_id_tercero
                                and f.tercero_id=g.tercero_id";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var[] = $resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();

        //DATOS Y DETALLE FACTURA (solo conceptos no inventarios)
        $query = "SELECT a.*,
                                case when a.concepto isnull then c.descripcion else a.concepto  end as descripcion
                                FROM fac_facturas_conceptos as a,
                                fac_facturas_conceptos_dc as b, conceptos_caja_conceptos as c
                                WHERE a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                and a.prefijo='" . $_SESSION['CAJA']['PREFIJOCONCEPTO'] . "'
                                and a.factura_fiscal=" . $_SESSION['CAJA']['FACTURACONCEPTO'] . "
                                and a.fac_factura_concepto_id=b.fac_factura_concepto_id
                                and b.concepto_id=c.concepto_id
                                and b.grupo_concepto=c.grupo_concepto
                                and b.empresa_id=c.empresa_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $result->Close();
            $classReport = new reports;
            $impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte = 'pos');
            $reporte = $classReport->PrintReport('pos', 'app', 'CajaGeneral', 'facturaConcepto', $var, $impresora, $orientacion = '', $unidades = '', $formato = '', $html = 1);
            if (!$reporte) {
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
            }

            $resultado = $classReport->GetExecResultado();
        }
        $var = '';
        if (!empty($resultado[codigo])) {
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }
        //aqui va donde vuelve
        $this->FormaVentanaImpresionConceptos();
        return true;
    }

// INSERTAR FACTURA DE PRODUCTOS DE INVENTARIOS
    function InsertarInventarios() {
        $emp = $_SESSION['CAJA']['EMPRESA'];
        $cutilidad = $_SESSION['CAJA']['CENTROUTILIDAD'];

        //contado credito
        $tipo_factura = $_SESSION['CAJA']['FACTURA'];

        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $total_efectivo = $_REQUEST['efectivo'];
        $total_cheques = $_REQUEST['cheque'];
        $total_tarjetas = $_REQUEST['tarjetad'] + $_REQUEST['tarjetac'];
        $bono = $_SESSION['CAJA']['BONO'];
        //$total_abono=$total_efectivo+$total_cheques+$total_tarjetas+$bono;
        if ($_REQUEST['Devuelta'] > 0 AND $total_efectivo > $_REQUEST['Devuelta'])
            $total_efectivo-=$_REQUEST['Devuelta'];
        else
        if ($_REQUEST['Devuelta'] > 0 AND $total_cheques > $_REQUEST['Devuelta'])
            $total_cheques-=$_REQUEST['Devuelta'];
        else
        if ($_REQUEST['Devuelta'] > 0 AND $total_tarjetas > $_REQUEST['Devuelta'])
            $total_tarjetas-=$_REQUEST['Devuelta'];
        $total_abono = $_REQUEST['aPagar'];
        $tipoTerceroFacPaciente = $_SESSION['CAJA']['TIPO_ID_TERCERO'];
        $idTerceroFacPaciente = $_SESSION['CAJA']['TERCEROID'];
        //-------------------------------------------------------------
        //contado
        if ($tipo_factura == 'contado') {
            //----------------NUMERACION-------------------------
            //sacamos la numeracion de la factura de contado
            $var = $this->AsignarNumero($_SESSION['CAJA']['PRECONTADO'], &$dbconn);
            $factura = $var[numero];
            $prefijo = $var[prefijo];
            //----------------FIN NUMERACION-----------------------
            //sw_clase_factura 0=>contado 1=>credito
            $sw_clase = 0;
            $documento = $_SESSION['CAJA']['PRECONTADO'];
        } elseif ($tipo_factura == 'credito') {
            //----------------NUMERACION-------------------------
            //cambiamos numeraciones factura credito
            $va = $this->AsignarNumero($_SESSION['CAJA']['PRECREDITO'], &$dbconn);
            $factura = $va[numero];
            $prefijo = $va[prefijo];
            //----------------FIN NUMERACION-----------------------
            //sw_clase_factura 0=>contado 1=>credito
            $sw_clase = 1;
            $documento = $_SESSION['CAJA']['PRECREDITO'];
        } else {
            echo "NO ESTA ENVIANDO EL TIPO DE FACTURA: contado o credito";
            exit;
        }

        //sw_clase_factura 0=>contado 1=>credito
        //tipo_factura 5 porque es conceptos
        $query = "INSERT INTO fac_facturas(
                                                    empresa_id,
                                                    prefijo,
                                                    factura_fiscal,
                                                    estado,
                                                    usuario_id,
                                                    fecha_registro,
                                                    plan_id,
                                                    tipo_id_tercero,
                                                    tercero_id,
                                                    sw_clase_factura,
                                                    documento_id,
                                                    tipo_factura,
													centro_utilidad)
                        VALUES('$emp','$prefijo',$factura,0," . UserGetUID() . ",'now()',
                                        NULL,'$tipoTerceroFacPaciente','$idTerceroFacPaciente','$sw_clase'," . $documento . ",6,'$cutilidad')";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar fac_facturas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        //contado se inserta en fac_facturas_Contado
        if ($tipo_factura == 'contado') {
            $sqls = "INSERT into fac_facturas_contado(
                                                    empresa_id,
                                                    factura_fiscal,
                                                    centro_utilidad,
                                                    prefijo,
                                                    total_abono,
                                                    total_efectivo,
                                                    total_cheques,
                                                    total_tarjetas,
                                                    tipo_id_tercero,
                                                    tercero_id,
                                                    estado,
                                                    fecha_registro,
                                                    usuario_id,
                                                    caja_id,
                                                    total_bonos
                                                    )
                                VALUES('$emp','$factura','$cutilidad',
                                '$prefijo',$total_abono,$total_efectivo,$total_cheques,$total_tarjetas,
                                '$tipoTerceroFacPaciente','$idTerceroFacPaciente',0,'now()'," . UserGetUID() . ",
                                '" . $_SESSION['CAJA']['CAJAID'] . "',$bono);";
            $dbconn->Execute($sqls);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al insertar en fac_facturas_contado";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }

//*********************************************************************************************
//SELECCIONAR EL DOCUMENTO DE INVENTARIOS CON EL QUE SE GENERA LA ACTUALIZACION DEL INVENTARIOS
//*********************************************************************************************
        $this->CallMetodoExterno('app', 'InvBodegas', 'user', 'CrearDocumentosBodegaCajaGeneral');
//*********************************************************************************************
//FIN SELECCIONAR EL DOCUMENTO DE INVENTARIOS CON EL QUE SE GENERA LA ACTUALIZACION DEL INVENTARIOS
//*********************************************************************************************
        //------------va a insertar en las tablas de inventarios
        //-------busco los datos
        foreach ($_SESSION['CAJA_GENERAL']['RETORNO']['VECTOR'] as $Id => $datosT) {
            //UNSET($dat);
            $sql = "SELECT * FROM tmp_detalle_inventarios
                                    WHERE rc_inventario_id=" . $datosT[consecutivo_tmp] . ";";
            $result = $dbconn->execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                return false;
            }
            while (!$result->EOF) {
                $dat[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $result->Close();
        }//FIN foreach
        /*     echo     $sql="SELECT * FROM tmp_detalle_inventarios
          WHERE tipo_id_tercero='$tipoTerceroFacPaciente'
          AND tercero_id='$idTerceroFacPaciente'
          AND empresa_id='".$_SESSION['CAJA']['EMPRESA']."';";
          $result = $dbconn->execute($sql);
          if($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $this->fileError = __FILE__;
          $this->lineError = __LINE__;
          return false;
          }
          while(!$result->EOF)
          {
          $dat[]=$result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
          }
          $result->Close(); */

        $total_factura = $gravamen = 0;
        for ($i = 0; $i < sizeof($dat); $i++) {
            $query = "SELECT nextval('fac_facturas_conceptos_fac_factura_concepto_id_seq')";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al traer la secuencia cuentas_numerodecuenta_seq ";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            $id = $result->fields[0];
            if (empty($dat[$i]['descripcion'])) {
                $dat[$i]['descripcion'] = 'NULL';
            } else {
                $dat[$i]['descripcion'] = "'" . $dat[$i]['descripcion'] . "'";
            }
            $sql = "INSERT INTO fac_facturas_inventarios
                                                            (fac_factura_inventario_id,
                                                            empresa_id,
                                                            prefijo,
                                                            factura_fiscal,
                                                            bodegas_doc_id,
                                                            numeracion,
                                                            cantidad,
                                                            precio,
                                                            valor_total,
                                                            porcentaje_gravamen,
                                                            valor_gravamen,
                                                            concepto,
                                                            consecutivo)
                                    VALUES(         $id,
                                                            '" . $dat[$i]['empresa_id'] . "',
                                                            '$prefijo',
                                                            '$factura',
                                                            " . $datosT[tipo_doc] . ",
                                                            " . $datosT[numero] . ",
                                                            " . $dat[$i]['cantidad'] . ",
                                                            " . $dat[$i]['precio'] . ",
                                                            " . $dat[$i]['valor_total'] . ",
                                                            " . $dat[$i]['porcentaje_gravamen'] . ",
                                                            " . $dat[$i]['valor_gravamen'] . ",
                                                            " . $dat[$i]['descripcion'] . ",
                                                            " . $datosT[consecutivo_bodega] . ");";
            $dbconn->execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error INSERT INTO fac_facturas_inventarios";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                $dbconn->RollbackTrans();
                return false;
            }
            $sql = "INSERT INTO fac_facturas_conceptos_di(
                                                                    fac_factura_concepto_id,
                                                                    empresa_id,
                                                                    centro_utilidad,
                                                                    bodega,
                                                                    codigo_producto)
                                VALUES(     $id,
                                                '" . $dat[$i]['empresa_id'] . "',
                                                '" . $dat[$i]['centro_utilidad'] . "',
                                                '" . $dat[$i]['bodega'] . "',
                                                '" . $dat[$i]['codigo_producto'] . "');";
            $dbconn->execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error INSERT INTO fac_facturas_conceptos_di";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                $dbconn->RollbackTrans();
                return false;
            }
            //AQUI SUMO LOS VALORES PARA TOTAL_FACTURA Y GRAVAMEN Q VAN EN FAC_FACTURAS, NO HICE TRIGGER
            $total_factura += $dat[$i]['valor_total'];
            $gravamen += $dat[$i]['valor_gravamen'];
        }//FIN foreach
        //actualizando los valores totales en fac_facturas, solo total_factura y gravamen los otros no se utilizan
        $sql = "UPDATE fac_facturas SET total_factura=$total_factura, gravamen=$gravamen
                            WHERE empresa_id='$emp'
                            and prefijo='$prefijo' and factura_fiscal=$factura;";
        $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error UPDATE fac_facturas ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $sql = "DELETE FROM tmp_detalle_inventarios
                            WHERE tipo_id_tercero='$tipoTerceroFacPaciente'
                            AND tercero_id='$idTerceroFacPaciente'
                            AND empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "';";
        $resul = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error DELETE FROM tmp_detalle_conceptos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $dbconn->CommitTrans();
        $_SESSION['CAJA']['FACTURAINVENTARIO'] = $factura;
        $_SESSION['CAJA']['PREFIJOINVENTARIO'] = $prefijo;

        if (!$this->FormaVentanaImpresionInventarioProducto()) {
            return false;
        }
        return true;
    }

    // impresion pos de caja inventarios
    function ImpresionReportesInventarios() {
        list($dbconn) = GetDBconn();
        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }

        //DATOS GENERALES
        $query = "SELECT i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid,
                                i.id, j.departamento, k.municipio, f.*, e.texto1, e.texto2,
                                e.mensaje, d.nombre, g.nombre_tercero, g.tercero_id, g.tipo_id_tercero as tipotercero
                                FROM empresas as i, tipo_dptos as j, tipo_mpios as k,
                                documentos as e,fac_facturas as f, system_usuarios as d, terceros as g
                                WHERE f.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                and f.prefijo='" . $_SESSION['CAJA']['PREFIJOINVENTARIO'] . "'
                                and f.factura_fiscal=" . $_SESSION['CAJA']['FACTURAINVENTARIO'] . "
                                and f.usuario_id=d.usuario_id
                                and i.empresa_id=f.empresa_id
                                and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
                                and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id
                                and i.tipo_mpio_id=k.tipo_mpio_id
                                and f.documento_id=e.documento_id
                                and f.tipo_id_tercero=g.tipo_id_tercero
                                and f.tercero_id=g.tercero_id";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var[] = $resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
        //DATOS Y DETALLE FACTURA (solo inventarios no conceptos)
        $query = "SELECT a.*, c.descripcion as descripcion
                                FROM fac_facturas_inventarios as a,
                                fac_facturas_conceptos_di as b, inventarios_productos as c
                                WHERE a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                and a.prefijo='" . $_SESSION['CAJA']['PREFIJOINVENTARIO'] . "'
                                and a.factura_fiscal=" . $_SESSION['CAJA']['FACTURAINVENTARIO'] . "
                                and a.fac_factura_inventario_id=b.fac_factura_concepto_id
                                and b.codigo_producto=c.codigo_producto;";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $result->Close();
            $classReport = new reports;
            $impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte = 'pos');
            //$reporte=$classReport->PrintReport('pos','app','CajaGeneral','facturaInventario',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
            $reporte = $classReport->PrintReport('pos', 'app', 'CajaGeneral', 'facturaConcepto', $var, $impresora, $orientacion = '', $unidades = '', $formato = '', $html = 1);
            if (!$reporte) {
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
            }

            $resultado = $classReport->GetExecResultado();
        }
        $var = '';
        if (!empty($resultado[codigo])) {
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }
        //aqui va donde vuelve
        $this->FormaVentanaImpresionInventarioProducto();
        return true;
    }

    function InsertarDetalle() {
        $centro = $_SESSION['CAJA']['CENTROUTILIDAD'];
        $emp = $_SESSION['CAJA']['EMPRESA'];
        $Cuenta = $_SESSION['CAJA']['CUENTA'];
        $Cajaid = $_REQUEST['Cajaid'];
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $tipoid = $_SESSION['CAJA']['TIPO_ID_TERCERO'];
        $tercero = $_SESSION['CAJA']['TERCEROID'];

        if (!$_REQUEST['cantidad'] || !$_REQUEST['precio'] || !is_numeric($_REQUEST['gravamen']) || $_REQUEST['gravamen'] < 0) {
            if (!$_REQUEST['cantidad']) {
                $this->frmError["cantidad"] = 1;
            }
            if (!$_REQUEST['precio']) {
                $this->frmError["precio"] = 1;
            }
            $this->frmError["MensajeError"] = 'Faltan datos obligatorios.';
            if ($_REQUEST['gravamen'] < 0) {
                $this->frmError["gravamen"] = 1;
                $this->frmError["MensajeError"] = 'El gravamen debe ser positivo.';
            }
            if (!is_numeric($_REQUEST['gravamen'])) {
                $this->frmError["gravamen"] = 1;
                $this->frmError["MensajeError"] = 'El gravamen debe ser numerico.';
            }
            /*                  if(!$this->CapturaDetalle($_REQUEST['conceptos'],$_REQUEST['arx'])){
              return false;
              } */
            $this->FormaCuentaConceptos();
            return true;
        }
        //-------------liquidacion del precio
        $valGravamen = 0;
        $precio = str_replace(".", "", $_REQUEST['precio']); //quita los puntos..........
        if (!empty($_REQUEST['gravamen'])) {
            $valGravamen = ($precio * $_REQUEST['gravamen'] / 100);
            $precio = ($precio + ($precio * $_REQUEST['gravamen'] / 100));
        } else {
            $_REQUEST['gravamen'] = 0;
        }
        //-------------fin liquidacion del precio

        $descrip = $_REQUEST['observacion'];
        $valor_total = $_REQUEST['cantidad'] * $precio;
        $arr = explode('*', $_REQUEST['conceptos']);

        $sql = "INSERT INTO tmp_detalle_conceptos
                                (empresa_id,
                                centro_utilidad,
                                grupo_concepto,
                                concepto_id,
                                tipo_id_tercero,
                                tercero_id,
                                sw_tipo,
                                cantidad,
                                precio,
                                valor_total,
                                porcentaje_gravamen,
                                valor_gravamen,
                                descripcion
                                )VALUES(
                                '$emp',
                                '$centro',
                                '" . $arr[0] . "',
                                '" . $arr[1] . "',
                                '$tipoid',
                                '$tercero',
                                '0',
                                " . $_REQUEST['cantidad'] . ",
                                " . $precio . ",
                                $valor_total,
                                " . $_REQUEST['gravamen'] . ",
                                $valGravamen,
                                '$descrip'
                                );";
        $resulta = $dbconn->execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $dbconn->RollbackTrans();
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $dbconn->CommitTrans();
        if (!$this->FormaCuentaConceptos()) {
            return false;
        }
        return true;
    }

    function ConsultaInsertadosDetalle() {
        list($dbconn) = GetDBconn();
        /* if(!$valores){$valores=$_REQUEST['arx'];}
          $tipoid=$valores[tipo_id_tercero];
          $tercero=$valores[tercero_id]; */
        $Cuenta = $_SESSION['CAJA']['CUENTA'];
        $tipoid = $_SESSION['CAJA']['TIPO_ID_TERCERO'];
        $tercero = $_SESSION['CAJA']['TERCEROID'];
        /*      $sql="select concepto_id,grupo_concepto,detalle,valor,descripcion from tmp_detalle_conceptos where tipo_id_tercero='$tipoid'
          AND   tercero_id='$tercero' AND cuenta='$Cuenta'"; */
        /*$sql = "SELECT a.concepto_id,a.cantidad,a.precio,a.descripcion,
                                    a.valor_gravamen,a.porcentaje_gravamen,a.valor_total,
                                    a.concepto_id,a.grupo_concepto, c.descripcion as desconcepto,
                                    b.descripcion as desgrupo,a.rc_concepto_id
                        FROM tmp_detalle_conceptos a, grupos_conceptos b,
                                    conceptos_caja_conceptos c
                        WHERE a.tipo_id_tercero='$tipoid'
                        AND a.tercero_id='$tercero'
                        AND b.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                        AND b.empresa_id=c.empresa_id
                        AND a.grupo_concepto=b.grupo_concepto
                        AND b.grupo_concepto=c.grupo_concepto
                        AND a.concepto_id=c.concepto_id
                        ;";*/
        
        $sql = "SELECT a.concepto_id,a.cantidad,a.precio,a.descripcion, a.valor_gravamen,a.porcentaje_gravamen,a.valor_total, a.concepto_id,a.grupo_concepto, 
                    c.descripcion as desconcepto, b.descripcion as desgrupo,a.rc_concepto_id, a.tipo_id_tercero, a.tercero_id  FROM tmp_detalle_conceptos a
                    inner join grupos_conceptos b on a.grupo_concepto = b.grupo_concepto
                    inner join conceptos_caja_conceptos c on b.grupo_concepto = c.grupo_concepto AND a.concepto_id = c.concepto_id
                    WHERE a.tipo_id_tercero='{$tipoid}' AND a.tercero_id='{$tercero}' AND b.empresa_id='{$_SESSION['CAJA']['EMPRESA']}' ";
        $resulta = $dbconn->execute($sql);
        
        //echo $sql;
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $i = 0;
        while (!$resulta->EOF) {
            $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function ConsultaInsertadosDetalleInventarios() {
        list($dbconn) = GetDBconn();
        $Cuenta = $_SESSION['CAJA']['CUENTA'];
        $tipoid = $_SESSION['CAJA']['TIPO_ID_TERCERO'];
        $tercero = $_SESSION['CAJA']['TERCEROID'];
        $sql = "SELECT a.codigo_producto,a.cantidad,a.precio,a.descripcion,
                                a.valor_gravamen,a.porcentaje_gravamen,a.valor_total,
                                c.descripcion as desgrupo,a.rc_inventario_id, a.bodega
                    FROM tmp_detalle_inventarios a, inventarios b,
                            inventarios_productos c
                    WHERE a.tipo_id_tercero='$tipoid'
                    AND a.tercero_id='$tercero'
                    AND a.empresa_id=b.empresa_id
                    AND b.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                    AND a.codigo_producto=b.codigo_producto
                    AND b.codigo_producto=c.codigo_producto;";
        $resulta = $dbconn->execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $i = 0;
        while (!$resulta->EOF) {
            $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function InsertarProductoTmpInventario() {
        list($dbconn) = GetDBconn();
        $Cuenta = $_SESSION['CAJA']['CUENTA'];
        $tipoid = $_SESSION['CAJA']['TIPO_ID_TERCERO'];
        $tercero = $_SESSION['CAJA']['TERCEROID'];
        $ProductosBodega = $_SESSION['CAJA']['PRODUCTOS'];
        for ($i = 0; $i < sizeof($ProductosBodega); $i++) {
            if (!empty($_REQUEST['producto' . $ProductosBodega[$i]['codigo_producto']])) {
                $valor_gravamen = (($ProductosBodega[$i]['porc_iva'] * $ProductosBodega[$i]['precio_venta']) / 100) * $_REQUEST['producto' . $ProductosBodega[$i]['codigo_producto']];
                $valortotal = $_REQUEST['producto' . $ProductosBodega[$i]['codigo_producto']] * $ProductosBodega[$i]['precio_venta'] + $valor_gravamen;
                $sql = "INSERT INTO tmp_detalle_inventarios
                                (
                                empresa_id,
                                centro_utilidad,
                                codigo_producto,
                                bodega,
                                tipo_id_tercero,
                                tercero_id,
                                cantidad,
                                precio,
                                valor_total,
                                porcentaje_gravamen,
                                valor_gravamen
                                )
                                VALUES
                                (
                                '" . $_SESSION['CAJA']['EMPRESA'] . "',
                                '" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "',
                                '" . $ProductosBodega[$i]['codigo_producto'] . "',
                                " . $_SESSION['CAJA']['BODEGA'] . ",
                                '" . $_SESSION['CAJA']['TIPO_ID_TERCERO'] . "',
                                '" . $_SESSION['CAJA']['TERCEROID'] . "',
                                " . $_REQUEST['producto' . $ProductosBodega[$i]['codigo_producto']] . ",
                                " . $ProductosBodega[$i]['precio_venta'] . ",
                                " . $valortotal . ",
                                " . $ProductosBodega[$i]['porc_iva'] . ",
                                $valor_gravamen
                                );";
                $resulta = $dbconn->execute($sql);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    return false;
                }
            }
        }

        $this->FormaCuentaInventarios();
        return true;
    }

    function ProductosInventariosBodega($codigoBus, $DescripcionBus, $bodega) {
        if ($_REQUEST['Volver']) {
            $this->FormaCuentaInventarios();
            return true;
        }
        $codigoBus = $_REQUEST['codigoBus'];
        $DescripcionBus = $_REQUEST['DescripcionBus'];
        if (!empty($codigoBus)) {
            $sql = " AND a.codigo_producto ILIKE '$codigoBus%'";
        } else {
            $sql = "";
        }

        if (!empty($DescripcionBus)) {
            $sql1 = " AND b.descripcion ILIKE '%" . strtoupper($DescripcionBus) . "%'";
        } else {
            $sql1 = "";
        }

        $bodega = $_REQUEST['bodega'];
        $this->paginaActual = 1;
        $this->offset = 0;
        if ($_REQUEST['offset']) {
            $this->paginaActual = intval($_REQUEST['offset']);
            if ($this->paginaActual > 1) {
                $this->offset = ($this->paginaActual - 1) * ($this->limit);
            }
        }
        list($dbconn) = GetDBconn();
        $query = "SELECT a.codigo_producto,b.descripcion,a.existencia,
									d.precio_venta, b.porc_iva
    FROM existencias_bodegas a,inventarios_productos b,
        inv_grupos_inventarios c, inventarios d
    WHERE a.codigo_producto=b.codigo_producto
        AND a.empresa_id=d.empresa_id
        AND a.codigo_producto=d.codigo_producto
        AND a.bodega=$bodega
        AND b.grupo_id=c.grupo_id
        AND (c.sw_medicamento='1' OR c.sw_insumos='1')
        $sql
        $sql1
        ORDER BY b.descripcion";
        if (empty($_REQUEST['conteo'])) {
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $this->conteo = $result->RecordCount();
        } else {
            $this->conteo = $_REQUEST['conteo'];
        }
        $query.=" LIMIT " . $this->limit . " OFFSET " . $this->offset . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            while (!$result->EOF) {
                $vars[] = $result->GetRowAssoc($toUpper = false);
                $result->MoveNext();
            }
        }
        $this->BuscadorProductoInv($NoLiquidacion, $TipoDocumento, $Documento, $nombrePaciente, $cuenta, $ingreso, $codigoBus, $DescripcionBus, $bodega, $vars);
        return true;
    }

    function EliminarConcepto() {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $tipoid = $_SESSION['CAJA']['TIPO_ID_TERCERO'];
        $tercero = $_SESSION['CAJA']['TERCEROID'];
        $emp = $_SESSION['CAJA']['EMPRESA'];

        $grupo = $_REQUEST['grupoid'];
        $concepto = $_REQUEST['conceptoid'];
        $key = $_REQUEST['rc_concepto'];
        $sql = "DELETE FROM tmp_detalle_conceptos
              WHERE rc_concepto_id=$key;";
        $resulta = $dbconn->execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $dbconn->RollbackTrans();
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        if (!$this->FormaCuentaConceptos()) {
            return false;
        }
        $dbconn->CommitTrans();
        return true;
    }

    function EliminarTmpInventario() {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $tipoid = $_SESSION['CAJA']['TIPO_ID_TERCERO'];
        $tercero = $_SESSION['CAJA']['TERCEROID'];
        $emp = $_SESSION['CAJA']['EMPRESA'];

        $grupo = $_REQUEST['grupoid'];
        $concepto = $_REQUEST['conceptoid'];
        $key = $_REQUEST['rc_inventario_id'];
        $sql = "DELETE FROM tmp_detalle_inventarios
              WHERE rc_inventario_id=$key;";
        $resulta = $dbconn->execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $dbconn->RollbackTrans();
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        if (!$this->FormaCuentaInventarios()) {
            return false;
        }
        $dbconn->CommitTrans();
        return true;
    }

    function TraerBusqTercero($id, $nombre) {
        $tipo = $_REQUEST['TipoDocum'];
        if (!empty($id) AND !empty($nombre) AND $tipo != -1) {
            //$nombre1=STRTOUPPER($nombre);
            $bus1 = "WHERE tipo_id_tercero='$tipo'
                        AND tercero_id LIKE('%$id%')
                        AND (nombre_tercero LIKE(UPPER('%$nombre%'))
                             OR nombre_tercero LIKE(LOWER('%$nombre%')))";
        } else
        if (!empty($id) AND !empty($nombre) AND $tipo == -1) {
            $nombre1 = STRTOUPPER($nombre);
            $bus2 = "WHERE tercero_id LIKE('%$id%')
                        AND (nombre_tercero LIKE(UPPER('%$nombre%'))
                            OR nombre_tercero LIKE(LOWER('%$nombre%')));";
        } else
        if (!empty($id) AND $tipo != -1) {
            $bus3 = "WHERE tipo_id_tercero='$tipo'
                        AND tercero_id LIKE('%$id%');";
        } else
        if (!empty($nombre)) {
            $nombre1 = STRTOUPPER($nombre);
            $bus4 = "WHERE (nombre_tercero LIKE(UPPER('%$nombre%'))
                          OR nombre_tercero LIKE(LOWER('%$nombre%')));";
        } else
        if (!empty($id) AND $tipo == -1) {
            $bus5 = "WHERE tercero_id LIKE('%$id%');";
        } else
        if (empty($id) AND empty($nombre) AND $tipo != -1) {
            //$nombre1=STRTOUPPER($nombre);
            $bus6 = "WHERE tipo_id_tercero='$tipo';";
        } else
        if (empty($id) AND empty($nombre) AND $tipo == -1) {
            //$nombre1=STRTOUPPER($nombre);
            $bus7 = "";
        }
        list($dbconn) = GetDBconn();
        $sql = "  SELECT tipo_id_tercero,tercero_id,nombre_tercero,
                                        direccion,telefono,email
                                FROM terceros
                                $bus1 $bus2 $bus3 $bus4 $bus5 $bus6 $bus7";
        $resulta = $dbconn->execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $i = 0;
        while (!$resulta->EOF) {
            $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        
        if(count($var)==0){
            $var = $this->WSTerceroFI($id, $nombre, $tipo);
        }
        
        return $var;
    }

    function WSTerceroFI($id, $nombre, $tipodea) {
        require_once ('nusoap/lib/nusoap.php');
        $url = "http://10.0.0.10:8080/SinergiasFinanciero3-ejb/terceroGeneralGet/terceroGeneralGet?wsdl";
        $params = array('numeroidentificacion' => $id);
        $soapclient = new nusoap_client($url, true);			
        $function = 'buscarTerceroGeneralDocumento'; //$function = 'todos_pacientes';
        $result = $soapclient->call($function,$params);

        if(count($result['buscarTerceroGeneralDocumentoResult']) > 0){
            $con['identificacion'] = $result['buscarTerceroGeneralDocumentoResult']['identificacion'];
            $con['tipodocumento'] = $result['buscarTerceroGeneralDocumentoResult']['tipodocumento'];
            $idtercero = $result['buscarTerceroGeneralDocumentoResult']['idtercero'];
            //EMPIEZA INTEGRACI?N DUSOFT FINANCIERO Y DUSOFT ASISTENCIAL PARA CREAR TERCERO EN CASO DE LA NO EXISTENCIA
            $function = 'buscarTerceroId'; //$function = 'todos_pacientes';
            $params = array('idtercero' => $idtercero);
            $direccion = $soapclient->call($function,$params);
            
            $con['direccion']=$direccion['buscarTerceroIdResult']['direccion'];
            $con['tipo_pais_id']=$direccion['buscarTerceroIdResult']['prefijopais']; 
            $con['tipo_dpto_id']=$direccion['buscarTerceroIdResult']['coddepartamento']; 
            $con['tipo_mpio_id']=$direccion['buscarTerceroIdResult']['codmunicipio']; 
            $con['telefono']=$direccion['buscarTerceroIdResult']['numerotelefonico'];
            $con['razonsocial']=$direccion['buscarTerceroIdResult']['razonsocial'];
            $con['email']=$direccion['buscarTerceroIdResult']['email'];
            
            $function = 'buscarNaturalezaTercero'; //$function = 'todos_pacientes';
            $params = array('idtercero' => $idtercero);
            $naturaleza = $soapclient->call($function,$params);
            $natu = explode(",", $naturaleza['buscarNaturalezaTerceroResult']);
            if($natu[0]==0){
                    $con['naturaleza']=1;
            }else{
                    $con['naturaleza']=0;
            }
            if(isset($con['email'])){
                $email = $con['email'];
            }else{
                $email = null;
            }
            
            list($dbconn) = GetDBconn();
            $cons2 = "INSERT INTO terceros
                            (tipo_id_tercero,
                            tercero_id,
                            nombre_tercero,
                            tipo_pais_id,
                            tipo_dpto_id,
                            tipo_mpio_id,
                            direccion,
                            telefono,
                            email,
                            sw_persona_juridica,
                            usuario_id)
                            VALUES
                            ('".$con['tipodocumento']."',
                            '".$id."',
                            '".$con['razonsocial']."',
                            '".$con['tipo_pais_id']."',
                            '".$con['tipo_dpto_id']."',
                            '".$con['tipo_mpio_id']."',
                            '".$con['direccion']."',
                            '".$con['telefono']."',
                            '".$email."',
                            '".$con['naturaleza']."',
                            ".UserGetUID().");";
            $re = $dbconn->Execute($cons2);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            
        }
        
        $tipo = $tipodea;
        if (!empty($id) AND !empty($nombre) AND $tipo != -1) {
            //$nombre1=STRTOUPPER($nombre);
            $bus1 = "WHERE tipo_id_tercero='$tipo'
                        AND tercero_id LIKE('%$id%')
                        AND (nombre_tercero LIKE(UPPER('%$nombre%'))
                             OR nombre_tercero LIKE(LOWER('%$nombre%')))";
        } else
        if (!empty($id) AND !empty($nombre) AND $tipo == -1) {
            $nombre1 = STRTOUPPER($nombre);
            $bus2 = "WHERE tercero_id LIKE('%$id%')
                        AND (nombre_tercero LIKE(UPPER('%$nombre%'))
                            OR nombre_tercero LIKE(LOWER('%$nombre%')));";
        } else
        if (!empty($id) AND $tipo != -1) {
            $bus3 = "WHERE tipo_id_tercero='$tipo'
                        AND tercero_id LIKE('%$id%');";
        } else
        if (!empty($nombre)) {
            $nombre1 = STRTOUPPER($nombre);
            $bus4 = "WHERE (nombre_tercero LIKE(UPPER('%$nombre%'))
                          OR nombre_tercero LIKE(LOWER('%$nombre%')));";
        } else
        if (!empty($id) AND $tipo == -1) {
            $bus5 = "WHERE tercero_id LIKE('%$id%');";
        } else
        if (empty($id) AND empty($nombre) AND $tipo != -1) {
            //$nombre1=STRTOUPPER($nombre);
            $bus6 = "WHERE tipo_id_tercero='$tipo';";
        } else
        if (empty($id) AND empty($nombre) AND $tipo == -1) {
            //$nombre1=STRTOUPPER($nombre);
            $bus7 = "";
        }
        list($dbconn) = GetDBconn();
        $sql = "  SELECT tipo_id_tercero,tercero_id,nombre_tercero,
                                        direccion,telefono,email
                                FROM terceros
                                $bus1 $bus2 $bus3 $bus4 $bus5 $bus6 $bus7";
        $resulta = $dbconn->execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $i = 0;
        while (!$resulta->EOF) {
            $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        
        return $var;        
    }
    
    function RetornarFormaOrdenesServicio() {
        $this->FormaOrdenesServicio();
        return true;
    }

    function CajaConsultaExterna($TipoId, $PacienteId, $paso, $PlanId) {
        if (!$TipoId) {
            $TipoId = $_REQUEST['TipoId'];
            $PacienteId = $_REQUEST['PacienteId'];
            $paso = $_REQUEST['paso'];
            $PlanId = $_REQUEST['PlanId'];
            $_SESSION['CAJA']['PASO'] = $paso;
        }

//       if(!$_SESSION['CUENTA'])
//       {   $_SESSION['CUENTA']=rand();   }

        if (!$this->FormaCuentaExterna($TipoId, $PacienteId, $PlanId)) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    function CajaConsulta() {
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
        }

        $Busqueda = $_REQUEST['TipoBusqueda'];
        if (!$this->FormaMetodoBuscar($Busqueda, $mensaje, $D, $arr, $Departamento, $f)) {
            return false;
        }
        return true;
    }

    /* funcion de la caja general en la cual reposa los datos de las ordenes de
      servicios q hay que pagar....
     */

    function CajaOrdenes() {
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
        }

        $Busqueda = $_REQUEST['TipoBusqueda'];
        if (!$this->FormaMetodoBuscarOrden($Busqueda, $arr, $f)) {
            return false;
        }
        return true;
    }

    function BuscarDatos() {
        if (empty($_REQUEST['PlanId']))
            $_REQUEST['PlanId'] = $_SESSION['planid'];
        else
            $_SESSION['planid'] = $_REQUEST['PlanId'];
        $caja_id = $_REQUEST['cajaid'];
        $PlanId = $_REQUEST['PlanId'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Nivel = $_REQUEST['Nivel'];
        $FechaC = $_REQUEST['FechaC'];
        $Cuenta = $_REQUEST['Cuenta'];
        $Pieza = $_REQUEST['Pieza'];
        $Cama = $_REQUEST['Cama'];
        $NombrePaciente = $_REQUEST['NombrePaciente'];
        list($dbconn) = GetDBconn();
        if ($_REQUEST['swd'] == 'DV') {
            $sql = "SELECT a.recibo_caja,a.prefijo,a.caja_id,a.fecha_registro,
                                a.total_devolucion,b.razon_social,
                c.descripcion,d.plan_descripcion,e.usuario,
                btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id

                FROM rc_devoluciones a,empresas b,centros_utilidad c,
                planes d,system_usuarios e,pacientes f
                WHERE a.recibo_caja='" . $_REQUEST['Recibo'] . "'
                                AND a.prefijo='" . $_REQUEST['prefijo'] . "'
                AND a.empresa_id=b.empresa_id
                AND c.empresa_id='" . $_REQUEST['empresa'] . "'
                AND c.centro_utilidad='" . $_REQUEST['cu'] . "'
                AND d.plan_id='" . $PlanId . "'
                AND a.usuario_id=e.usuario_id
                AND tipo_id_paciente='" . $_REQUEST['TipoId'] . "'
                AND paciente_id='" . $_REQUEST['PacienteId'] . "'
                                AND a.caja_id='$caja_id'";
        }

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01' AND empty($_REQUEST['swd'])) {
            $sql = "SELECT a.fecha_ingcaja,a.recibo_caja,a.prefijo,a.caja_id,a.fecha_registro,a.total_abono,a.total_efectivo,
                a.total_tarjetas,a.total_cheques,a.total_bonos,b.razon_social,
                c.descripcion,d.plan_descripcion,e.usuario,
                btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id

                FROM recibos_caja a,empresas b,centros_utilidad c,
                planes d,system_usuarios e,pacientes f
                WHERE a.recibo_caja='" . $_REQUEST['Recibo'] . "'
                                AND a.prefijo='" . $_REQUEST['prefijo'] . "'
                AND a.empresa_id=b.empresa_id
                AND c.empresa_id='" . $_REQUEST['empresa'] . "'
                AND c.centro_utilidad='" . $_REQUEST['cu'] . "'
                AND d.plan_id='" . $PlanId . "'
                AND a.usuario_id=e.usuario_id
                                AND a.usuario_id=" . UserGetUID() . "
                AND tipo_id_paciente='" . $_REQUEST['TipoId'] . "'
                AND paciente_id='" . $_REQUEST['PacienteId'] . "'
                                AND a.caja_id='$caja_id'
                                AND a.estado IN ('0')";
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03' || $_SESSION['CAJA']['TIPOCUENTA'] == '02' AND empty($_REQUEST['swd'])) {

            $sql = "SELECT a.fecha_ingcaja,a.recibo_caja,a.prefijo,a.caja_id,a.fecha_registro,a.total_abono,a.total_efectivo,
                a.total_tarjetas,a.total_cheques,a.total_bonos,b.razon_social,
                c.descripcion,e.usuario,
                f.nombre_tercero,f.tipo_id_tercero,f.tercero_id

                FROM recibos_caja a,empresas b,centros_utilidad c,
                system_usuarios e,terceros f
                WHERE a.recibo_caja='" . $_REQUEST['Recibo'] . "'
                                AND a.empresa_id=b.empresa_id
                AND c.empresa_id='" . $_REQUEST['empresa'] . "'
                AND c.centro_utilidad='" . $_REQUEST['cu'] . "'
                AND a.usuario_id=e.usuario_id
                                AND a.usuario_id=" . UserGetUID() . "
                AND a.tipo_id_tercero='" . $_SESSION['CAJA']['TIPO_ID_TERCERO'] . "'
                AND a.tipo_id_tercero=f.tipo_id_tercero
                AND a.tercero_id=f.tercero_id
                AND a.tercero_id='" . $_SESSION['CAJA']['TERCEROID'] . "'
                                AND a.estado IN ('0')";
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '06' AND empty($_REQUEST['swd'])) {
            $sql = "SELECT a.fecha_ingcaja,a.recibo_caja,a.prefijo,a.caja_id,a.fecha_registro,a.total_abono,a.total_efectivo,
                                a.total_tarjetas,a.total_cheques,a.total_bonos,b.razon_social,
                                c.descripcion,d.plan_descripcion,e.usuario,
                                btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                                f.tipo_id_paciente||' '||f.paciente_id as id,
                                g.numero,
                                g.prefijo_pagare

                                FROM recibos_caja a,empresas b,centros_utilidad c,
                                planes d,system_usuarios e,pacientes f, rc_detalle_pagare g,
                                terceros h
                                WHERE a.recibo_caja='" . $_REQUEST['Recibo'] . "'
                                AND a.prefijo='" . $_REQUEST['prefijo'] . "'
                                AND a.empresa_id=b.empresa_id
                                AND c.empresa_id='" . $_REQUEST['empresa'] . "'
                                AND c.centro_utilidad='" . $_REQUEST['cu'] . "'
                                AND d.plan_id='" . $_REQUEST['PlanId'] . "'
                                AND a.usuario_id=e.usuario_id
                                AND a.usuario_id=" . UserGetUID() . "
                                AND f.tipo_id_paciente='" . $_REQUEST['TipoId'] . "'
                                AND f.paciente_id='" . $_REQUEST['PacienteId'] . "'
                                AND a.caja_id='$caja_id'
                                AND a.empresa_id=g.empresa_id
                                AND a.centro_utilidad=g.centro_utilidad
                                AND a.prefijo=g.prefijo
                                AND a.recibo_caja=g.recibo_caja
                                AND a.tipo_id_tercero=h.tipo_id_tercero
                                AND a.tercero_id=h.tercero_id
                                AND a.estado IN ('0')";
        }
        $resulta = $dbconn->execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $var = $resulta->GetRowAssoc($ToUpper = false);
        IncludeLib("reportes/recibo_caja"); //car
        //unset($_SESSION['CAJA']['PARAM']);
        if ($_REQUEST['swd'] == 'DV')
            GenerarReciboDevolucion($var);
        else
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '06')
            GenerarReciboAbonoPagare($var);
        else
            GenerarReciboCaja($var);
        //GenerarReciboDevolucion($var);

        $_SESSION['CAJA']['PARAM'] = 'ShowReport'; //esta variable es para que muestre el reporte
        //$this->salida="<a href=\"http://147.120.0.236/SIIS/cache/halt\">cosa</a>";
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                return false;
            }
            return true;
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            if (!$this->CajaConsultaExterna($TipoId, $PacienteId, $_SESSION['CAJA']['PASO'], $PlanId)) {
                return false;
            }
            return true;
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
            if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                return false;
            }
            return true;
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            if (!$this->CajaConceptos($Cajaid)) {
                return false;
            }
            return true;
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
            if (!$this->RetornarFormaOrdenesServicio()) {
                return false;
            }
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
            if (!$this->CajaPagares($spy, $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId)) {
                return false;
            }
        }
        return true;
    }

//esta funcion me permite a mi imprimir en pos un recibo de caja hospitalaria
    function Imprimir_POS_Recibo_Hosp() {
        $caja_id = $_REQUEST['cajaid'];
        $PlanId = $_REQUEST['PlanId'];
        $recibo = $_REQUEST['Recibo'];
        $prefijo = $_REQUEST['prefijo'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Nivel = $_REQUEST['Nivel'];
        $FechaC = $_REQUEST['FechaC'];
        $Cuenta = $_REQUEST['Cuenta'];
        $Pieza = $_REQUEST['Pieza'];
        $Cama = $_REQUEST['Cama'];
        $NombrePaciente = $_REQUEST['NombrePaciente'];
        list($dbconn) = GetDBconn();
        if ($_REQUEST['swd'] == 'DV') {
            $sql = "SELECT a.recibo_caja,a.prefijo,a.caja_id,a.fecha_registro,
                                    a.total_devolucion,b.razon_social,
                                    c.descripcion,d.plan_descripcion,e.usuario,
                                    btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                                    f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                                    f.tipo_id_paciente||' '||f.paciente_id as id
                                    FROM rc_devoluciones a,empresas b,centros_utilidad c,
                                    planes d,system_usuarios e,pacientes f
                                    WHERE a.recibo_caja='" . $_REQUEST['Recibo'] . "'
                                    AND a.prefijo='" . $_REQUEST['prefijo'] . "'
                                    AND a.empresa_id=b.empresa_id
                                    AND c.empresa_id='" . $_REQUEST['empresa'] . "'
                                    AND c.centro_utilidad='" . $_REQUEST['cu'] . "'
                                    AND d.plan_id='" . $PlanId . "'
                                    AND a.usuario_id=e.usuario_id
                                    AND a.usuario_id=" . UserGetUID() . "
                                    AND tipo_id_paciente='" . $_REQUEST['TipoId'] . "'
                                    AND paciente_id='" . $_REQUEST['PacienteId'] . "'
                                    AND a.caja_id='$caja_id'";
        }

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01' AND empty($_REQUEST['swd'])) {
            $sql = "SELECT a.fecha_ingcaja,a.recibo_caja,a.prefijo,a.caja_id,a.fecha_registro,a.total_abono,a.total_efectivo,
                            a.total_tarjetas,a.total_cheques,a.total_bonos,b.razon_social,b.direccion,
                            c.descripcion,d.plan_descripcion,e.usuario,e.usuario_id,
                            btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                            f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                            f.tipo_id_paciente||' '||f.paciente_id as id

                            FROM recibos_caja a,empresas b,centros_utilidad c,
                            planes d,system_usuarios e,pacientes f
                            WHERE a.recibo_caja='" . $_REQUEST['Recibo'] . "'
                            AND a.prefijo='" . $_REQUEST['prefijo'] . "'
                            AND a.empresa_id=b.empresa_id
                            AND c.empresa_id='" . $_REQUEST['empresa'] . "'
                            AND c.centro_utilidad='" . $_REQUEST['cu'] . "'
                            AND d.plan_id='" . $_REQUEST['PlanId'] . "'
                            AND a.usuario_id=e.usuario_id
                            AND a.usuario_id=" . UserGetUID() . "
                            AND tipo_id_paciente='" . $_REQUEST['TipoId'] . "'
                            AND paciente_id='" . $_REQUEST['PacienteId'] . "'
                            AND a.caja_id='$caja_id'
                            AND a.estado IN ('0')";
        }

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '06' AND empty($_REQUEST['swd'])) {
            $sql = "SELECT a.fecha_ingcaja,a.recibo_caja,a.prefijo,a.caja_id,a.fecha_registro,a.total_abono,a.total_efectivo,
                                a.total_tarjetas,a.total_cheques,a.total_bonos,b.razon_social,
                                c.descripcion,d.plan_descripcion,e.usuario,e.usuario_id,
                                btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                                f.tipo_id_paciente||' '||f.paciente_id as id,
                                b.empresa_id
                                FROM recibos_caja a,empresas b,centros_utilidad c,
                                planes d,system_usuarios e,pacientes f, rc_detalle_pagare g,
                                terceros h
                                WHERE a.recibo_caja='" . $_REQUEST['Recibo'] . "'
                                AND a.prefijo='" . $_REQUEST['prefijo'] . "'
                                AND a.empresa_id=b.empresa_id
                                AND c.empresa_id='" . $_REQUEST['empresa'] . "'
                                AND c.centro_utilidad='" . $_REQUEST['cu'] . "'
                                AND d.plan_id='" . $_REQUEST['PlanId'] . "'
                                AND a.usuario_id=e.usuario_id
                                AND a.usuario_id=" . UserGetUID() . "
                                AND tipo_id_paciente='" . $_REQUEST['TipoId'] . "'
                                AND paciente_id='" . $_REQUEST['PacienteId'] . "'
                                AND a.caja_id='$caja_id'
                                AND a.empresa_id=g.empresa_id
                                AND a.centro_utilidad=g.centro_utilidad
                                AND a.prefijo=g.prefijo
                                AND a.recibo_caja=g.recibo_caja
                                AND a.tipo_id_tercero=h.tipo_id_tercero
                                AND a.tercero_id=h.tercero_id
                                AND a.estado IN ('0')";
        }

        $resulta = $dbconn->execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }
        $var = $resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
        $classReport = new reports;
        $impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte = 'pos');
        if ($_REQUEST['swd'] == 'DV')
            $reporte = $classReport->PrintReport('pos', 'app', 'CajaGeneral', 'Devolucion', $var, $impresora, $orientacion = '', $unidades = '', $formato = '', $html = 1);
        else
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '06')
            $reporte = $classReport->PrintReport('pos', 'app', 'CajaGeneral', 'ReciboPagare', $var, $impresora, $orientacion = '', $unidades = '', $formato = '', $html = 1);
        else
        if ($_SESSION['CAJA']['TIPOCUENTA'] >= '01')
            $reporte = $classReport->PrintReport('pos', 'app', 'CajaGeneral', 'Recibo', $var, $impresora, $orientacion = '', $unidades = '', $formato = '', $html = 1);
        if (!$reporte) {
            $this->error = $classReport->GetError();
            $this->mensajeDeError = $classReport->MensajeDeError();
            unset($classReport);
            return false;
        }

        $resultado = $classReport->GetExecResultado();
        unset($classReport);
        $var = '';

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                return false;
            }
            return true;
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
            if (!$this->CajaPagares($spy, $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId)) {
                return false;
            }
            return true;
        }
    }

    /**
     * Realiza la busqueda segn el plan,documento .. de los pacientes que
     * tienen ordenes de servicios pendientes
     * @access private
     * @return boolean
     */
    function BuscarOrden() {
        $Buscar1 = $_REQUEST['Busc'];
        $Buscar = $_REQUEST['Buscar'];
        $Busqueda = $_REQUEST['TipoBusqueda'];
        $TipoBuscar = $_REQUEST['TipoBuscar'];
        $arreglo = $_REQUEST['arreglo'];
        $TipoCuenta = $_REQUEST['TipoCuenta'];
        $NUM = $_REQUEST['Of'];
        if ($Buscar) {
            unset($_SESSION['OS']['SPY']);
        }
        if (!$Busqueda) {
            $new = $TipoBuscar;
        }
        if (!$NUM) {
            $NUM = '0';
        }
        foreach ($_REQUEST as $v => $v1) {
            if ($v != 'modulo' and $v != 'metodo' and $v != 'SIIS_SID') {
                $vec[$v] = $v1;
            }
        }
        $_REQUEST['Of'] = $NUM;
        if ($Buscar1) {
            $this->FormaMetodoBuscarOrden($Busqueda, $arr, $f);
            return true;
        }

        list($dbconn) = GetDBconn();
        if ($TipoBuscar) {
            if ($TipoBuscar == 1) {
                $TipoId = $_REQUEST['TipoDocumento'];
                $PacienteId = $_REQUEST['Documento'];
                if (!$PacienteId) {
                    $this->frmError["MensajeError"] = 'La busqueda no arrojo resultados.';
                    $this->FormaMetodoBuscarOrden($Busqueda = '', $arr, $f = true);
                    return true;
                }
                /*  if(empty($_SESSION['SPY'])){
                  $conteo=$this->Buscar1($TipoId,$PacienteId,$NUM);
                  $_SESSION['SPY']=$conteo;
                  } */
                $Cuentas = $this->Buscar1($TipoId, $PacienteId, $NUM);
                if ($Cuentas) {
                    $this->FormaMetodoBuscarOrden($Busqueda = '', $Cuentas, $f = true);
                    return true;
                } else {
                    $this->frmError["MensajeError"] = 'La busqueda no arrojo resultados.';
                    $this->FormaMetodoBuscarOrden($Busqueda = '', $Cuentas, $f = true);
                    return true;
                }
            }//tipobuscar=1


            if ($TipoBuscar == 3) {
                $cuenta = $_REQUEST['Responsable'];
                if ($cuenta == -1) {
                    if ($cuenta == -1) {
                        $this->frmError["Responsable"] = 1;
                    }
                    $this->frmError["MensajeError"] = "Debe Elegir el plan.";
                    if (!$this->FormaMetodoBuscarOrden($TipoBuscar, $arr, $f = false)) {
                        return false;
                    }
                    return true;
                }
                /* if(empty($_SESSION['SPY'])){
                  $conteo=$this->RecordSearch1($Departamento,$TipoId,$PacienteId,$caso=1,$Caja,$NUM);
                  $_SESSION['SPY']=$conteo;
                  } */
                $Cuentas = $this->Buscar3($cuenta);
                if ($Cuentas) {
                    $this->FormaMetodoBuscarOrden($Busqueda = '', $Cuentas, $f = true);
                    return true;
                } else {
                    $this->frmError["MensajeError"] = 'La busqueda no arrojo resultados.';
                    $this->FormaMetodoBuscarOrden($Busqueda = '', $Cuentas, $f = true);
                    return true;
                }
            }//tipobuscar=1

            if ($TipoBuscar == 4) {
                $IngresoId = $_REQUEST['NumIngreso'];
                if (!$IngresoId) {
                    if (!$IngresoId) {
                        $this->frmError["IngresoId"] = 1;
                    }
                    $this->frmError["MensajeError"] = "Debe digitar el Nmero de Ingreso.";
                    if (!$this->FormaMetodoBuscarOrden($TipoBuscar, $arr, $f = false)) {
                        return false;
                    }
                    return true;
                }
                /* if(empty($_SESSION['SPY'])){
                  $conteo=$this->RecordSearch1($Departamento,$TipoId,$PacienteId,$caso=1,$Caja,$NUM);
                  $_SESSION['SPY']=$conteo;
                  } */
                $Cuentas = $this->Buscar4($IngresoId);
                if ($Cuentas) {
                    $this->FormaMetodoBuscarOrden($Busqueda = '', $Cuentas, $f = true);
                    return true;
                } else {
                    $this->frmError["MensajeError"] = 'La busqueda no arrojo resultados.';
                    $this->FormaMetodoBuscarOrden($Busqueda = '', $Cuentas, $f = true);
                    return true;
                }
            }//tipobuscar=1
        }//tipobuscar
    }

    function BuscarNombreTercero($plan) {
        list($dbconn) = GetDBconn();
        $query = "SELECT a.plan_descripcion,b.nombre_tercero,b.tercero_id,b.tipo_id_tercero
             FROM planes a,terceros b
             WHERE
             a.plan_id='$plan'
             AND a.tipo_tercero_id=b.tipo_id_tercero
             AND a.tercero_id=b.tercero_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        while (!$result->EOF) {
            $vars[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $vars;
    }

    /**
     * funcion buscar1 es la que se filtra por el tipo del paciente y la identificacion del
     * paciente.
     * @access private
     * @return array
     */
    /* funcion que trae las ordenes de servicios */
// $spia es una variable q si esta activa  va a realizar un record count del query
//si no va vacia y se realiza el query comun y corriente.
    function Buscar1($TipoId, $PacienteId, $spia='') {
        unset($_SESSION['CAJA']['ARRAY_PAGO']); //eliminamos la variable que tiene el arreglo de pago.
        list($dbconn) = GetDBconn();
        if ($_SESSION['CAJA']['CU'] == 1) {
            $filtro_cu = '';
        } else {
            //  $filtro_cu_dpto=', departamentos d,os_internas e';
            //  $filtro_cu=' AND e.departamento=d.departamento AND e.numero_orden_id=b.numero_orden_id';
        }

        //$filtro_cuenta=", os_cuenta_activa('$TipoId','$PacienteId',c.plan_id) as sw_cuenta";
        $query = "SELECT
          c.plan_id,c.plan_descripcion,h.servicio,h.descripcion as serv_des,
          sw_cargo_multidpto as switche,
          CASE c.sw_tipo_plan
          WHEN '0' THEN d.nombre_tercero
          WHEN '1' THEN 'SOAT'
          WHEN '2' THEN 'PARTICULAR'
          WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
          ELSE e.descripcion END,

          a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,
          i.fecha_vencimiento, f.cargo as cargoi,g.descripcion as des1,i.cantidad,
          a.autorizacion_int,a.autorizacion_ext,a.observacion,b.tipo_id_paciente,
          b.paciente_id,btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
          b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
          k.tipo_afiliado_nombre,h.sw_cargo_multidpto$filtro_cuenta

          FROM os_ordenes_servicios as a, pacientes as b, planes c,
          terceros d, tipos_planes as e, os_internas as f, cups g,
          servicios h,os_maestro i, tipos_afiliado k

          WHERE
          a.orden_servicio_id=i.orden_servicio_id
          AND i.numero_orden_id=f.numero_orden_id
          AND a.tipo_id_paciente=b.tipo_id_paciente
          AND a.paciente_id=b.paciente_id
          AND a.tipo_id_paciente='$TipoId'
          AND a.paciente_id='$PacienteId'
          AND a.servicio=h.servicio
          AND g.cargo=f.cargo
          AND c.plan_id=a.plan_id
          AND e.sw_tipo_plan=c.sw_tipo_plan
          AND c.tercero_id=d.tercero_id
          AND c.tipo_tercero_id=d.tipo_id_tercero
           AND i.sw_estado=1
          AND a.tipo_afiliado_id=k.tipo_afiliado_id
          AND DATE(i.fecha_activacion) <= NOW()
          ORDER BY c.plan_id,i.fecha_vencimiento,a.orden_servicio_id";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las 0rdenes de servicios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        if ($spia == true) {
            return $result->RecordCount();
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $var;
    }

    /**
     * funcion buscar1 es la que se filtra por el tipo del paciente y la identificacion del
     * paciente.
     * @access private
     * @return array
     */
    /* funcion que trae las ordenes de servicios */
    /* $spia es una variable q si esta activa  va a realizar un record count del query
      si no va vacia y se realiza el query comun y corriente. */
    /* buscar 4 es una funcion que busca o filtra por el numero de orden de la persona.
     */

    function Buscar4($nOrden) {
        unset($_SESSION['CAJA']['ARRAY_PAGO']); //eliminamos la variable que tiene el arreglo de pago.
        list($dbconn) = GetDBconn();
        if ($_SESSION['CAJA']['CU'] == 1) {
            $filtro_cu = '';
        } else {
            //  $filtro_cu_dpto=', departamentos d,os_internas e';
            //  $filtro_cu=' AND e.departamento=d.departamento AND e.numero_orden_id=b.numero_orden_id';
        }

        $query = "SELECT
          c.plan_id,c.plan_descripcion,h.servicio,h.descripcion as serv_des,
          sw_cargo_multidpto as switche,
          CASE c.sw_tipo_plan
          WHEN '0' THEN d.nombre_tercero
          WHEN '1' THEN 'SOAT'
          WHEN '2' THEN 'PARTICULAR'
          WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero
          ELSE e.descripcion END,

          a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,
          i.fecha_vencimiento, f.cargo as cargoi,g.descripcion as des1,i.cantidad,
          a.autorizacion_int,a.autorizacion_ext,a.observacion,b.tipo_id_paciente,
          b.paciente_id,btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
          b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
          k.tipo_afiliado_nombre,h.sw_cargo_multidpto

          FROM os_ordenes_servicios as a, pacientes as b, planes c,
          terceros d, tipos_planes as e, os_internas as f, cups g,
          servicios h,os_maestro i, tipos_afiliado k

          WHERE
          a.orden_servicio_id=i.orden_servicio_id
          AND i.numero_orden_id=f.numero_orden_id
           AND i.numero_orden_id='$nOrden'
          AND a.tipo_id_paciente=b.tipo_id_paciente
          AND a.paciente_id=b.paciente_id
          AND a.servicio=h.servicio
          AND g.cargo=f.cargo
          AND c.plan_id=a.plan_id
          AND e.sw_tipo_plan=c.sw_tipo_plan
          AND c.tercero_id=d.tercero_id
          AND c.tipo_tercero_id=d.tipo_id_tercero
           AND i.sw_estado=1
          AND a.tipo_afiliado_id=k.tipo_afiliado_id
          AND DATE(i.fecha_activacion) <= NOW()
          ORDER BY c.plan_id,i.fecha_vencimiento,a.orden_servicio_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las 0rdenes de servicios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        if ($spia == true) {
            return $result->RecordCount();
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $var;
    }

//ESTA BUSQUEDA ES UNA CONSULTA COMPLETA(PARA ORDENES DE SERVICIO)
// PERO EN EL CASO DE CAJA NO SE PUEDE REALIZAR..PREGUNTAR
//     /**
//   * Realiza la busqueda general de los pacientes que tienen ordenes de servicios pendientes
//   * @access private
//   * @return array
//   */
//   function BusquedaCompleta()
//   {
//         unset($_SESSION['CAJA']['ARRAY_PAGO']); //eliminamos la variable que tiene el arreglo de pago.
//         $NUM=$_REQUEST['Of'];
//         if(!$NUM)
//         {   $NUM='0';   }
//         $limit=$this->limit;
//         list($dbconn) = GetDBconn();
//         if(!empty($_SESSION['OS']['SPY']))
//         {   $x=" LIMIT ".$this->limit." OFFSET $NUM";   }
//         else
//         {   $x='';   }
//
//           if($_SESSION['CAJA']['CU']==1){
//           $filtro_cu='';
//           }else{
//                 $filtro_cu_dpto=', departamentos d,os_internas e';
//                 $filtro_cu=' AND e.departamento=d.departamento AND e.numero_orden_id=b.numero_orden_id';
//           }
//
//    $query="SELECT a.numero_orden_id,a.cargo,a.descripcion,a.cantidad,a.fecha_act,
//                   a.tipo_id_paciente,a.nombre,a.valor_cargo,a.tarifario_id,a.paciente_id,a.plan_id
//
//                   FROM tmp_os_cargos_pago_caja a,os_maestro b,os_ordenes_servicios c$filtro_cu_dpto
//
//                   WHERE
//                   a.numero_orden_id=b.numero_orden_id
//                   AND c.paciente_id=a.paciente_id
//                   AND b.sw_estado=1
//                   AND a.cargo=b.cargo
//                   AND a.tarifario_id=b.tarifario_id
//                   AND b.orden_servicio_id=c.orden_servicio_id
//                   AND c.tipo_id_paciente=a.tipo_id_paciente
//                   AND b.orden_servicio_id=c.orden_servicio_id$filtro_cu  $x";
//
//         $result = $dbconn->Execute($query);
//         if ($dbconn->ErrorNo() != 0) {
//           $this->error = "Error al Cargar el Modulo";
//           $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//           return false;
//         }
//         if(!empty($_SESSION['OS']['SPY']))
//         {
//             while(!$result->EOF)
//             {
//                 $vars[]=$result->GetRowAssoc($ToUpper = false);
//                 $result->MoveNext();
//             }
//         }
//         else
//         {
//             $vars=$result->RecordCount();
//             $_SESSION['OS']['SPY']=$vars;
//         }
//         $result->Close();
//    return $vars;
//   }



    function IraFormaOrden() {
        //asinamos una cuenta virtual por si no tiene.............ojo con esto
        if (!$_SESSION['CAJA']['CUENTA']) {
            $_SESSION['CAJA']['CUENTA'] = $this->AsignarCuentaVirtual();
        }
        //$this->FormaOrdenesServicio();
        return true;
    }

    /* funcion para retornar a las ordenes de servicio */

    function RetornarOrdenServicio() {

        $this->FormaOrdenesServicio($_SESSION['CAJA']['AUX']['tipo_id_paciente'], $_SESSION['CAJA']['AUX']['paciente_id'], $_SESSION['CAJA']['AUX']['plan_id']);
        return true;
    }

    /**
     * Esta funcion asigna un numero de cuenta virtual al pago de caja en conceptos
     * y en consulta externa.
     * @access private
     * @return boolean
     */
    function AsignarCuentaVirtual() {
        list($dbconn) = GetDBconn();
        $sql = "select nextval('asignacuentavirtual_seq')";
        $result = $dbconn->Execute($sql);
        $dato = $result->fields[0];
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        return $dato;
    }

    /**
     *
     */
    function CajaConceptos($Cajaid) {
        if (!$_SESSION['CAJA']['CUENTA']) {
            $_SESSION['CAJA']['CUENTA'] = $this->AsignarCuentaVirtual();
        }


        $dat = $_REQUEST['arreglo'];
        $factura = $_REQUEST['factura'];

        if (empty($_SESSION['CAJA']['TIPO_ID_TERCERO'])) {
            $_SESSION['CAJA']['TERCEROID'] = $dat[tercero_id];
            $_SESSION['CAJA']['TIPO_ID_TERCERO'] = $dat[tipo_id_tercero];
            $_SESSION['CAJA']['DIRECCION'] = $dat[direccion];
            $_SESSION['CAJA']['TELEFONO'] = $dat[telefono];
            $_SESSION['CAJA']['MAIL'] = $dat[email];
            $_SESSION['CAJA']['NOMBRE_TERCERO'] = urldecode($_REQUEST['nombre']);
        }
        /*  else
          {"ENTROja";
          $this->Cutilidad=$_SESSION['CENTROUTILIDAD'];
          $this->Empresa=$_SESSION['EMPRESA'];
          if(empty($_SESSION['TIPONUMERACION']))
          {
          $_SESSION['TIPONUMERACION']=$_REQUEST['Tiponumeracion'];
          }
          if(empty($_SESSION['TIPOCUENTA']))
          {
          $_SESSION['TIPOCUENTA']=$_REQUEST['TipoCuenta'];
          }
          } */
        $Busqueda = $_REQUEST['TipoBusqueda'];

        if (empty($_SESSION['CAJA']['CAJAID'])) {
            $_SESSION['CAJA']['CAJAID'] = $_REQUEST['Cajaid'];
        }

        if ($_REQUEST['TipoCuenta'] == '08' or $_SESSION['CAJA']['TIPOCUENTA'] == '08') {
            if (!$this->FormaCuentaInventarios($dat, $factura)) {
                return false;
            }
        } else {
            
            if (!$this->FormaCuentaConceptos($dat, $factura)) {
                return false;
            }
        }
        return true;
    }

    /**
     * La funcion BuscarPermisosUser recibe todas las variables de manejo
     * (de informacion de cabecera acerca del 'paciente' y el
     * 'responsable'(metodo extraido del modulo facturacion(creado por Darling dorado ))  y las envia a
     * a 'FormaCuenta' que es la Interfaz principal.
     * Nota: las variables pueden llegar por REQUEST o por Parametros.
     * @access private
     * @return boolean                 //FALTA ARREGLAR
     */
    function BuscarPermisosUser() {
        unset($_SESSION['CAJA']);
        list($dbconn) = GetDBconn();
        GLOBAL $ADODB_FETCH_MODE;
        $usuario = UserGetUID();
        $query = "(SELECT a.caja_id, b.sw_todos_cu, b.empresa_id, b.centro_utilidad,b.ip_address,
                                            b.descripcion as descripcion3, b.tipo_numeracion, d.razon_social as descripcion1,
                                            e.descripcion as descripcion2, b.cuenta_tipo_id, a.caja_id,b.tipo_numeracion_devoluciones,
                                            NULL AS prefijo_fac_contado, NULL AS prefijo_fac_credito, NULL as concepto_caja
                            FROM cajas_usuarios as a, cajas as b, documentos as c, empresas as d, centros_utilidad as e
                            WHERE a.usuario_id=$usuario
                            AND a.caja_id=b.caja_id
                            AND b.empresa_id=d.empresa_id
                            AND d.empresa_id=e.empresa_id
                            AND b.centro_utilidad=e.centro_utilidad
                            AND b.tipo_numeracion=c.documento_id
                            ORDER BY d.empresa_id, b.centro_utilidad, a.caja_id)
                        UNION
                            (SELECT a.caja_id, NULL as sw_todos_cu , b.empresa_id,
                                            f.centro_utilidad, b.ip_address, b.descripcion as descripcion3, NULL as tipo_numeracion, d.razon_social as descripcion1,
                                            e.descripcion AS descripcion2, b.cuenta_tipo_id, a.caja_id, NULL as tipo_numeracion_devoluciones, b.prefijo_fac_contado,
                                            b.prefijo_fac_credito, b.concepto as concepto_caja
                            FROM userpermisos_cajas_rapidas as a, cajas_rapidas as b,
                                        empresas as d, centros_utilidad as e, departamentos f
                            WHERE a.usuario_id=$usuario
                            AND (b.cuenta_tipo_id='03' OR b.cuenta_tipo_id='08')
                            AND b.departamento=f.departamento
                            AND a.caja_id=b.caja_id
                            AND b.empresa_id=d.empresa_id
                            AND f.empresa_id=e.empresa_id
                            AND f.centro_utilidad=e.centro_utilidad
                            ORDER BY d.empresa_id, a.caja_id);";
        
        /*    $query = "SELECT a.caja_id, b.sw_todos_cu, b.empresa_id, b.centro_utilidad,b.ip_address,
          b.descripcion as descripcion3, b.tipo_numeracion, d.razon_social as descripcion1,
          e.descripcion as descripcion2, b.cuenta_tipo_id, a.caja_id,b.tipo_numeracion_devoluciones,
          b.prefijo_fac_contado, b.prefijo_fac_credito
          FROM cajas_usuarios as a, cajas as b, documentos as c, empresas as d, centros_utilidad as e
          WHERE a.usuario_id=$usuario
          AND a.caja_id=b.caja_id
          AND b.empresa_id=d.empresa_id
          AND d.empresa_id=e.empresa_id
          AND b.centro_utilidad=e.centro_utilidad
          AND b.tipo_numeracion=c.documento_id
          ORDER BY d.empresa_id, b.centro_utilidad, a.caja_id;"; */
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resulta = $dbconn->Execute($query);

        while ($data = $resulta->FetchRow()) {
            $caja[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']] = $data;
        }
        $url[0] = 'app';
        $url[1] = 'CajaGeneral';
        $url[2] = 'user';
        $url[3] = 'MenudeCaja';
        $url[4] = 'Caja';
        $url['concepto_id'] = "calie";
        $arreglo[0] = 'EMPRESA';
        $arreglo[1] = 'CENTRO UTILIDAD';
        $arreglo[2] = 'CAJA';
        $this->salida.= gui_theme_menu_acceso('CAJA GENERAL', $arreglo, $caja, $url, ModuloGetURL('system', 'Menu'));
        return true;
    }

    /**
     *
     */
    function Principal() {
        if ($_REQUEST['Caja']['sw_todos_cu']) {
            $Cutilidad = $_REQUEST['Caja']['sw_todos_cu'];
        }
        //if($arreglo[$i][sw_todos_cu]==0)
        //{  $Cutilidad=$arreglo[$i][centro_utilidad]; }
        $Tipo = $_REQUEST['Caja']['caja_id'];
        $desp = $_REQUEST['Caja']['descripcion'];
        $numero = $_REQUEST['Caja']['numero'];
        $prefijo = $_REQUEST['Caja']['prefijo'];
        $Tiponumeracion = $_REQUEST['Caja']['tipo_numeracion'];
        $TipoCuenta = $_REQUEST['Caja']['cuenta_tipo_id'];
        $Empresa = $_REQUEST['Caja']['empresa_id'];
        $Cutilidad = $_REQUEST['Caja']['centro_utilidad'];
        $TipoCuenta = $_REQUEST['Caja']['cuenta_tipo_id'];
        $arreglo1 = array('numero' => $numero, 'prefijo' => $prefijo, 'TipoCuenta' => $TipoCuenta, 'Tiponumeracion' => $Tiponumeracion);
        if ($i % 2) {
            $estilo = "modulo_list_claro";
        } else {
            $estilo = "modulo_list_oscuro";
        }
        if ($_REQUEST['Caja']['cuenta_tipo_id'] == '01' || $_REQUEST['Caja']['cuenta_tipo_id'] == '02') {
            $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'main', array('Caja' => $Tipo, 'Empresa' => $Empresa, 'CentroUtilidad' => $Cutilidad, 'arreglo' => $arreglo1, 'TipoCuenta' => $TipoCuenta, 'facturacion' => $Tipo, 'CU' => $_REQUEST['Caja']['sw_todos_cu'], 'SWCUENTAS' => 'Cuentas'));
            return true;
        }
        if ($_REQUEST['Caja']['cuenta_tipo_id'] == '04') {
            $this->CajaConsulta();
            return true;
            //$accion=ModuloGetURL('app','CajaGeneral','user','CajaConsulta',array('Cajaid'=>$Tipo,'Empresa'=>$Empresa,'CentroUtilidad'=>$Cutilidad,'Tiponumeracion'=>$Tiponumeracion,'TipoCuenta'=>$TipoCuenta));
        }
        if ($_REQUEST['Caja']['cuenta_tipo_id'] == '03') {
            $this->BuscarTercero();
            //$this->BusquedaTercer();
            return true;
            //$accion=ModuloGetURL('app','CajaGeneral','user','BuscarTercero',array('Cajaid'=>$Tipo,'Empresa'=>$Empresa,'CentroUtilidad'=>$Cutilidad,'Tiponumeracion'=>$Tiponumeracion,'TipoCuenta'=>$TipoCuenta));
        }
    }

    function CreaTerceros() {
        $_SESSION['INFORM']['RETORNO']['contenedor'] = 'app';
        $_SESSION['INFORM']['RETORNO']['modulo'] = 'CajaGeneral';
        $_SESSION['INFORM']['RETORNO']['tipo'] = 'user';
        $_SESSION['INFORM']['RETORNO']['metodo'] = 'RetornoTerceros';
        $_SESSION['tercer']['empresa'] = $_SESSION['CAJA']['EMPRESA'];
        $_SESSION['tercer']['razonso'] = $_SESSION['CAJA']['nomempresa'];
        $_SESSION['tercer']['tipo_id_tercero'] = $_REQUEST['tipoid'];
        $_SESSION['tercer']['tercero_id'] = $_REQUEST['tercero'];
        $this->ReturnMetodoExterno('app', 'Terceros', 'user', 'BusquedaTercer'); //IngresaTercer
        return true;
    }

    function RetornoTerceros() {
        unset($_SESSION['CAJA']['DATOS']);
        $_SESSION['CAJA']['DATOS'] = $_SESSION['INFORM']['DATOS'];
        if ($_SESSION['INFORM']['RETORNO']['sw'] == 1) {
            if ($_SESSION['INFORM']['DATOS']['existe'] == 1) {
                unset($_SESSION['INFORM']);
                unset($_SESSION['tercer']);
                if ($this->PantallaProfesional() == false) {
                    return false;
                }
            } else {
                unset($_SESSION['INFORM']);
                unset($_SESSION['tercer']);
                if (empty($_SESSION['PROVEEDORES'])) {
                    if ($this->BuscarTercero() == false) {
                        return false;
                    }
                } else {
                    $this->ReturnMetodoExterno($_SESSION['PROVEEDORES']['RETORNO']['contenedor'], $_SESSION['PROVEEDORES']['RETORNO']['modulo'], $_SESSION['PROVEEDORES']['RETORNO']['tipo'], $_SESSION['PROVEEDORES']['RETORNO']['metodo']);
                }
            }
        } else {
            $_SESSION['CAJA']['TIPO_ID_TERCERO'] = $_SESSION['tercer']['tipo_id_tercero'];
            $_SESSION['CAJA']['TERCEROID'] = $_SESSION['tercer']['tercero_id'];
            $_SESSION['CAJA']['NOMBRE_TERCERO'] = $_SESSION['tercer']['nombre_tercero'];
            //query para traer los datos q faltan
            list($dbconn) = GetDBconn();
            $query = "SELECT direccion, telefono, email FROM terceros
                                WHERE tipo_id_tercero='" . $_SESSION['CAJA']['TIPO_ID_TERCERO'] . "' and tercero_id='" . $_SESSION['CAJA']['TERCEROID'] . "'";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $_SESSION['CAJA']['DIRECCION'] = $resulta->fields[0];
            $_SESSION['CAJA']['TELEFONO'] = $resulta->fields[1];
            $_SESSION['CAJA']['MAIL'] = $resulta->fields[2];
            $resulta->Close();
            unset($_SESSION['INFORM']);
            unset($_SESSION['tercer']);
            //FormaCuentaConceptos
            //BuscarTercero
            if (SessionIsSetVar("BodegaInv") AND SessionIsSetVar("FacturaInv")) {
                if ($this->FormaCuentaInventarios($_SESSION['CAJA'], SessionIsSetVar("BodegaInv"), SessionIsSetVar("FacturaInv")) == false)
                    return false;
            }
            elseif ($this->FormaCuentaConceptos() == false) {
                return false;
            }
        }
        return true;
    }

    function MenudeCaja() {
        
        //se busca el concepto que necesita la caja por defecto
        if(!empty($_REQUEST['Caja']['concepto_caja']) && !trim($_REQUEST['Caja']['concepto_caja']) == ""){
            $_SESSION['CAJA']['concepto_caja'] = $_REQUEST['Caja']['concepto_caja'];
        }
        
        
        if (!empty($_REQUEST['ip']))
            $_REQUEST['Caja']['ip_address'] = $_REQUEST['ip'];
        if (GetIPAddress() != $_REQUEST['Caja']['ip_address'] AND !empty($_REQUEST['Caja']['ip_address'])) {
            $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'main');
            $this->FormaMensaje('NO PUEDE ACCESAR A LA CAJA [&nbsp;' . $_REQUEST['Caja']['descripcion3'] . '&nbsp;] DESDE LA IP [&nbsp;' . GetIPAddress() . '&nbsp;]', 'ERROR DE CONEXION A CAJA', $accion, 'MENU');
            return true;
        }
        if (empty($_SESSION['CAJA']['EMPRESA'])) {
            $_SESSION['CAJA']['EMPRESA'] = $_REQUEST['Caja']['empresa_id'];
            $_SESSION['CAJA']['CENTROUTILIDAD'] = $_REQUEST['Caja']['centro_utilidad'];
            $_SESSION['CAJA']['TIPONUMERACION'] = $_REQUEST['Caja']['tipo_numeracion'];
            $_SESSION['CAJA']['CAJAID'] = $_REQUEST['Caja']['caja_id'];
            $_SESSION['CAJA']['TIPOCUENTA'] = $_REQUEST['Caja']['cuenta_tipo_id'];
            $_SESSION['CAJA']['CU'] = $_REQUEST['Caja']['sw_todos_cu'];
            $_SESSION['CAJA']['TIPONUMERACION_DEVOLUCIONES'] = $_REQUEST['Caja']['tipo_numeracion_devoluciones'];
            //solo se llenan cuando son cajas de conceptos
            $_SESSION['CAJA']['PRECONTADO'] = $_REQUEST['Caja']['prefijo_fac_contado'];
            $_SESSION['CAJA']['PRECREDITO'] = $_REQUEST['Caja']['prefijo_fac_credito'];
        } else {
            $_REQUEST['Caja']['empresa_id'] = $_SESSION['CAJA']['EMPRESA'];
            $_REQUEST['Caja']['centro_utilidad'] = $_SESSION['CAJA']['CENTROUTILIDAD'];
            $_REQUEST['Caja']['tipo_numeracion'] = $_SESSION['CAJA']['TIPONUMERACION'];
            $_REQUEST['Caja']['caja_id'] = $_SESSION['CAJA']['CAJAID'];
            $_REQUEST['Caja']['cuenta_tipo_id'] = $_SESSION['CAJA']['TIPOCUENTA'];
            $_REQUEST['Caja']['sw_todos_cu'] = $_SESSION['CAJA']['CU'];
            $_REQUEST['Caja']['tipo_numeracion_devoluciones'] = $_SESSION['CAJA']['TIPONUMERACION_DEVOLUCIONES'];
        }

        $Tipo = $_REQUEST['Caja']['caja_id'];
        $numero = $_REQUEST['Caja']['numero'];
        $prefijo = $_REQUEST['Caja']['prefijo'];
        $Tiponumeracion = $_REQUEST['Caja']['tipo_numeracion'];
        $TipoCuenta = $_REQUEST['Caja']['cuenta_tipo_id'];
        $arreglo = array('numero' => $numero, 'prefijo' => $prefijo, 'TipoCuenta' => $TipoCuenta, 'Tiponumeracion' => $Tiponumeracion);
        $acc = ModuloGetURL('app', 'CajaGeneral', 'user', 'main');
        
        if ($_REQUEST['Caja']['cuenta_tipo_id'] == '01' or $_REQUEST['Caja']['cuenta_tipo_id'] == '02') {
            //CONSULTAR SI LA CAJA TIENE CIERRES SIN CONFIRMAR DEL DIA ACTUAL
            /*              $DatonSinConfirmar=$this->EstadoCierre($_REQUEST['Caja']['caja_id']);
              if(!empty($DatonSinConfirmar))
              {
              $this->FormaMensaje('LA CAJA TIENE CIERRES SIN CONFIRMAR!!','INFORMACI�',$acc,'volver');
              return true;
              } */
            //FIN CONSULTAR SI LA CAJA TIENE CIERRES SIN CONFIRMAR
            //*******************************************************
            //CONSULTA SI EXISTEN RECIBOS SIN CUADRAR POR UN USUARIO
            //DISTINTO AL ACTUAL
            $existenciarecibosincuadre = $this->ReciboSinCuadre('', '', $_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['cuenta_tipo_id'], '');
            if ($existenciarecibosincuadre) {
                $this->frmError["MensajeError"] = "EXISTEN RECIBOS DE CAJA DE OTROS USUARIOS SIN CUADRAR.";
                $this->uno = 1;
                $this->FrmRecibosCajaSinCuadreHoy($existenciarecibosincuadre, 2, $_REQUEST['Caja']['caja_id'], '', '', '', $TipoCuenta, '');
                return true;
            }
            //FIN CONSULTA SI EXISTEN RECIBOS SIN CUADRAR POR UN USUARIO
            //DISTINTO AL ACTUAL
            $this->Menu($_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['empresa_id'], $_REQUEST['Caja']['centro_utilidad'], $arreglo
                    , $_REQUEST['Caja']['cuenta_tipo_id'], $_REQUEST['Caja']['sw_todos_cu']);
            return true;
        } elseif ($_REQUEST['Caja']['cuenta_tipo_id'] == '03') {
            //CONSULTA SI EXISTEN RECIBOS SIN CUADRAR POR UN USUARIO
            //DISTINTO AL ACTUAL
            
            $existenciarecibosincuadre = $this->ReciboSinCuadre('', '', $_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['cuenta_tipo_id'], '');
            if ($existenciarecibosincuadre) {
                $this->frmError["MensajeError"] = "EXISTEN RECIBOS DE CAJA DE OTROS USUARIOS SIN CUADRAR.";
                $this->uno = 1;
                $this->FrmRecibosCajaSinCuadreHoy($existenciarecibosincuadre, 1, $_REQUEST['Caja']['caja_id'], '', '', '', $TipoCuenta, '');
                return true;
            }
            //FIN CONSULTA SI EXISTEN RECIBOS SIN CUADRAR POR UN USUARIO
            //DISTINTO AL ACTUAL
            //$UsuarioConceptos=$this->TraerUsuariosConceptos();
            $UsuarioCpto = new app_Facturacion_Conceptos();
            $UsuarioConceptos = $UsuarioCpto->TraerUsuariosConceptos($_REQUEST['Caja']['caja_id']);
            
            if (!empty($UsuarioConceptos))
                if ($UsuarioConceptos[sw_credito] == 1 AND $UsuarioConceptos[sw_contado] == 1) {
                    $this->MenuCajaConceptos($_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['empresa_id'], $_REQUEST['Caja']['centro_utilidad'], $_REQUEST['Caja']['Tiponumeracion']
                            , $_REQUEST['Caja']['cuenta_tipo_id'], $UsuarioConceptos);
                    /*                  $this->frmError["MensajeError"]="EXISTEN RECIBOS DE CAJA DE OTROS USUARIOS SIN CUADRAR.";
                      $this->uno=1;
                      $this->FrmRecibosCajaSinCuadreHoy($existenciarecibosincuadre,2,$_REQUEST['Caja']['caja_id'],'','','',$TipoCuenta,''); */
                    return true;
                } else
                if ($UsuarioConceptos[sw_credito] == 1) {
                    /*                      $this->Menu($_REQUEST['Caja']['caja_id'],$_REQUEST['Caja']['empresa_id'],$_REQUEST['Caja']['centro_utilidad'],$_REQUEST['Caja']['Tiponumeracion']
                      ,$_REQUEST['Caja']['cuenta_tipo_id'],$UsuarioConceptos,'credito'); */
                    $this->MenuCajaConceptos($_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['empresa_id'], $_REQUEST['Caja']['centro_utilidad'], $_REQUEST['Caja']['Tiponumeracion']
                            , $_REQUEST['Caja']['cuenta_tipo_id'], $UsuarioConceptos, 'credito');
                    return true;
                }
            if ($UsuarioConceptos[sw_contado] == 1) {
                /*                      $this->Menu($_REQUEST['Caja']['caja_id'],$_REQUEST['Caja']['empresa_id'],$_REQUEST['Caja']['centro_utilidad'],$_REQUEST['Caja']['Tiponumeracion']
                  ,$_REQUEST['Caja']['cuenta_tipo_id'],$UsuarioConceptos,'contado'); */
                
               // echo print_r($UsuarioConceptos) . "";
                $this->MenuCajaConceptos($_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['empresa_id'], $_REQUEST['Caja']['centro_utilidad'], $_REQUEST['Caja']['Tiponumeracion']
                        , $_REQUEST['Caja']['cuenta_tipo_id'], $UsuarioConceptos, 'contado');
                return true;
            } else {
                $this->BuscarPermisosUser();
                return true;
            }
        } elseif ($_REQUEST['Caja']['cuenta_tipo_id'] == '04') {
            //CONSULTA SI EXISTEN RECIBOS SIN CUADRAR POR UN USUARIO
            //DISTINTO AL ACTUAL
            $existenciarecibosincuadre = $this->ReciboSinCuadre('', '', $_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['cuenta_tipo_id'], '');
            if ($existenciarecibosincuadre) {
                $this->frmError["MensajeError"] = "EXISTEN RECIBOS DE CAJA DE OTROS USUARIOS SIN CUADRAR.";
                $this->uno = 1;
                $this->FrmRecibosCajaSinCuadreHoy($existenciarecibosincuadre, 1, $_REQUEST['Caja']['caja_id'], '', '', '', $TipoCuenta, '');
                return true;
            }
            $this->BuscarPermisosUser();
            return true;
        } elseif ($_REQUEST['Caja']['cuenta_tipo_id'] == '05') {
            //CONSULTAR SI LA CAJA TIENE CIERRES SIN CONFIRMAR DEL DIA ACTUAL
            /*              $DatonSinConfirmar=$this->EstadoCierre($_REQUEST['Caja']['caja_id']);
              if(!empty($DatonSinConfirmar))
              {
              $this->FormaMensaje('LA CAJA TIENE CIERRES SIN CONFIRMAR!!','INFORMACI�',$acc,'volver');
              return true;
              } */
            //FIN CONSULTAR SI LA CAJA TIENE CIERRES SIN CONFIRMAR
            //*******************************************************
            //CONSULTA SI EXISTEN RECIBOS SIN CUADRAR POR UN USUARIO
            //DISTINTO AL ACTUAL
            $existenciarecibosincuadre = $this->ReciboSinCuadre('', '', $_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['cuenta_tipo_id'], '');
            if ($existenciarecibosincuadre) {
                $this->frmError["MensajeError"] = "EXISTEN RECIBOS DE CAJA DE OTROS USUARIOS SIN CUADRAR.";
                $this->uno = 1;
                $this->FrmRecibosCajaSinCuadreHoy($existenciarecibosincuadre, 2, $_REQUEST['Caja']['caja_id'], '', '', '', $TipoCuenta, '');
                return true;
            }
            //FIN CONSULTA SI EXISTEN RECIBOS SIN CUADRAR POR UN USUARIO
            //DISTINTO AL ACTUAL
            unset($_SESSION['OS']['SPY']);
            $this->Menu($_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['empresa_id'], $_REQUEST['Caja']['centro_utilidad'], $_REQUEST['Caja']['Tiponumeracion']
                    , $_REQUEST['Caja']['cuenta_tipo_id'], $_REQUEST['Caja']['sw_todos_cu'], '', 1);
            return true;
        } elseif ($_REQUEST['Caja']['cuenta_tipo_id'] == '06') {
            //*********************************************************************
            //**OJO FALTA CONSULTA SI EXISTEN RECIBOS SIN CUADRAR POR UN USUARIO***
            //**DISTINTO AL ACTUAL*************************************************
            //*********************************************************************
            unset($_SESSION['OS']['SPY']);
            $this->Menu($_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['empresa_id'], $_REQUEST['Caja']['centro_utilidad'], $_REQUEST['Caja']['Tiponumeracion']
                    , $_REQUEST['Caja']['cuenta_tipo_id'], $_REQUEST['Caja']['sw_todos_cu']);
            return true;
        }
        //CAJA INVENTARIOS
        elseif ($_REQUEST['Caja']['cuenta_tipo_id'] == '08') {
            //CONSULTA SI EXISTEN RECIBOS SIN CUADRAR POR UN USUARIO
            //DISTINTO AL ACTUAL
            $existenciarecibosincuadre = $this->ReciboSinCuadre('', '', $_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['cuenta_tipo_id'], '');
            if ($existenciarecibosincuadre) {
                $this->frmError["MensajeError"] = "EXISTEN RECIBOS DE CAJA DE OTROS USUARIOS SIN CUADRAR.";
                $this->uno = 1;
                $this->FrmRecibosCajaSinCuadreHoy($existenciarecibosincuadre, 1, $_REQUEST['Caja']['caja_id'], '', '', '', $TipoCuenta, ''); 
                return true;
            }
            //FIN CONSULTA SI EXISTEN RECIBOS SIN CUADRAR POR UN USUARIO
            //DISTINTO AL ACTUAL
            $UsuarioInventarios = $this->TraerUsuariosCajaInventarios();
            if (!empty($UsuarioInventarios[sw_credito]) OR !empty($UsuarioInventarios[sw_contado])) {
                if (!empty($UsuarioInventarios))
                    if ($UsuarioInventarios[sw_credito] == 1 AND $UsuarioInventarios[sw_contado] == 1) { //$this->MenuBodega(
                        $this->MenuBodega($_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['empresa_id'], $_REQUEST['Caja']['centro_utilidad'], $_REQUEST['Caja']['Tiponumeracion']
                                , $_REQUEST['Caja']['cuenta_tipo_id'], $UsuarioInventarios);
                        /*                  $this->frmError["MensajeError"]="EXISTEN RECIBOS DE CAJA DE OTROS USUARIOS SIN CUADRAR.";
                          $this->uno=1;
                          $this->FrmRecibosCajaSinCuadreHoy($existenciarecibosincuadre,2,$_REQUEST['Caja']['caja_id'],'','','',$TipoCuenta,''); */
                        return true;
                    } else
                    if ($UsuarioInventarios[sw_credito] == 1) {
                        /*                      $this->Menu($_REQUEST['Caja']['caja_id'],$_REQUEST['Caja']['empresa_id'],$_REQUEST['Caja']['centro_utilidad'],$_REQUEST['Caja']['Tiponumeracion']
                          ,$_REQUEST['Caja']['cuenta_tipo_id'],$UsuarioConceptos,'credito'); */
                        $this->MenuBodega($_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['empresa_id'], $_REQUEST['Caja']['centro_utilidad'], $_REQUEST['Caja']['Tiponumeracion']
                                , $_REQUEST['Caja']['cuenta_tipo_id'], $UsuarioInventarios, 'credito');
                        return true;
                    }
                if ($UsuarioInventarios[sw_contado] == 1) {
                    /*                      $this->Menu($_REQUEST['Caja']['caja_id'],$_REQUEST['Caja']['empresa_id'],$_REQUEST['Caja']['centro_utilidad'],$_REQUEST['Caja']['Tiponumeracion']
                      ,$_REQUEST['Caja']['cuenta_tipo_id'],$UsuarioConceptos,'contado'); */
                    $this->MenuBodega($_REQUEST['Caja']['caja_id'], $_REQUEST['Caja']['empresa_id'], $_REQUEST['Caja']['centro_utilidad'], $_REQUEST['Caja']['Tiponumeracion']
                            , $_REQUEST['Caja']['cuenta_tipo_id'], $UsuarioInventarios, 'contado');
                    return true;
                } else {
                    $this->BuscarPermisosUser();
                    return true;
                }
            } else {
                $mensaje = 'Usuario sin definir proceso facturas inv credito,contado (cajas_usuarios_inventarios).';
                $titulo = 'Caja Inventario';
                $accion = ModuloGetURL('app', 'CajaGeneral', 'user', 'BuscarPermisosUser');
                $boton = 'Aceptar';
                $this->FormaMensaje($mensaje, $titulo, $accion, $boton);
                return true;
            }
            //CAJA INVENTARIOS
        }
    }

    /**
     * La funcion LlamarFormaBuscar manda las variables requeridas para realizar
     * la busqueda de una cuenta.
     *
     * @access private
     * @return boolean
     */
    function LlamarFormaBuscar() {
        if (!$this->FormaMetodoBuscar($Busqueda, $mensaje, $D, $arr, $Departamento, $f)) {
            return false;
        }
        return true;
    }

    /**
     * La funcion tipo_id_paciente se encarga de obtener de la base de datos
     * los diferentes tipos de identificacion de los paciente.
     * @access public
     * @return array
     */
    function tipo_id_paciente() {
        list($dbconn) = GetDBconn();
        $query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        } else {
            if ($result->EOF) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla maestra 'tipos_id_pacientes' esta vacia ";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                return false;
            }
            while (!$result->EOF) {
                $vars[$result->fields[0]] = $result->fields[1];
                $result->MoveNext();
            }
        }
        $result->Close();
        $this->retornoMetodo = $vars;
        return $vars;
    }

    /**
     * La funcion  Abonos se encarga de obtener de la base de datos
     * los totales de los cheques, las tarjetas,y los efectivos segun la cuenta de una persona.
     * @access public
     * @return array
     */
    function Abonos($Cuenta) {
        list($dbconn) = GetDBconn();
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            $query = "SELECT abono_efectivo,abono_cheque,abono_tarjetas,abono_chequespf
                FROM cuentas WHERE numerodecuenta=$Cuenta";
        }

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
            $query = "SELECT total,abono_efectivo,abono_cheque,abono_tarjetas,
                abono_chequespf FROM cab_cuenta_pv WHERE cuenta_pv=$Cuenta";
        }
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al buscar los abonos de la cuenta";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $var = $result->GetRowAssoc($ToUpper = false);
        return $var;
    }

    //METODO PARA CONSULTAR LOS USUARIOS DE LA CAJA INVENTARIOS
    function TraerUsuariosCajaInventarios($Caja_id) {
        list($dbconn) = GetDBconn();
        $query = "SELECT    a.usuario_id,
                                            a.sw_credito,
                                            a.sw_contado
                            FROM cajas_usuarios_inventarios as a,
                                    userpermisos_cajas_rapidas as b
                            WHERE a.usuario_id=" . UserGetUID() . "
                                AND a.caja_id=b.caja_id
                                AND a.usuario_id=b.usuario_id;";
       
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $var = $result->GetRowAssoc($ToUpper = false);
        $result->Close();
        return $var;
    }

    //FIN METODO PARA CONSULTAR LOS USUARIOS DE LA CAJA INVENTARIOS

    /**
     * La funcion TotalesPvta se encarga de obtener de la base de datos
     * los totales de la cuenta de una persona para punto de venta por medio de la tabla cab_cuenta_pv.
     * @access public
     * @return array
     */
    function TotalesPvta($Cuenta) {
        list($dbconn) = GetDBconn();
        $query = "SELECT total,valor_gravamen,descuento,abono_letras
                FROM cab_cuenta_pv WHERE cuenta_pv=$Cuenta";

        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $var = $result->GetRowAssoc($ToUpper = false);
        return $var;
    }

    /**
     * La funcion  ConsultaDevolucionesCaja se encarga de obtener de la base de datos
     *  el total de las devoluciones anteriores, segun la cuenta de una persona.
     * @access public
     * @return array
     */
    function ConsultaDevolucionesCaja($Cuenta, $Recibo, $Prefijo, $PagareNumero) {
        if ($Recibo) {
            $var = "AND e.recibo_caja='$Recibo'";
        } else {
            $var = '';
        }
        list($dbconn) = GetDBconn();

        $query = "SELECT e.empresa_id,e.recibo_caja,e.centro_utilidad,
                    e.prefijo,e.total_devolucion,e.estado,e.fecha_registro,
                                        e.usuario_id,e.caja_id
                    FROM cajas as a, rc_devoluciones e, cuentas i
                                        WHERE e.numerodecuenta=$Cuenta AND i.numerodecuenta=e.numerodecuenta
                    $var
                    AND e.caja_id=a.caja_id
                    ORDER BY e.recibo_caja  DESC LIMIT 12 OFFSET 0";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $i = 0;
        while (!$result->EOF) {
            $vars[$i] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
            $i++;
        }
        $result->Close();
        return $vars;
    }

    /**
     * La funcion  ConsultaPagosCaja se encarga de obtener de la base de datos
     * los totales de los cheques, las tarjetas, los efectivos y el total de los
     * recibos de caja anteriores, segun la cuenta de una persona.
     * @access public
     * @return array
     */
    function ConsultaPagosCaja($Cuenta, $Recibo, $Prefijo, $PagareNumero) {
        $spi = 0;

        if ($Recibo) {
            $var = "AND e.recibo_caja = '$Recibo'";
        } else {
            $var = '';
        }

        list($dbconn) = GetDBconn();

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            $query = "
                        SELECT
                        e.empresa_id,
                        e.recibo_caja,
                        e.centro_utilidad,
                        e.prefijo,
                        e.fecha_ingcaja,
                        e.total_abono,
                        e.total_bonos,
                        e.total_efectivo,
                        e.total_cheques,
                        e.total_tarjetas,
                        e.tipo_id_tercero,
                        e.tercero_id,
                        e.estado,
                        e.fecha_registro,
                        e.usuario_id,
                        e.caja_id

                        FROM
                        rc_detalle_hosp as h,
                        recibos_caja as e,
                        cajas as a


                        WHERE
                        h.numerodecuenta = $Cuenta
                        AND e.empresa_id = h.empresa_id
                        AND e.centro_utilidad = h.centro_utilidad
                        AND e.recibo_caja = h.recibo_caja
                        AND e.prefijo = h.prefijo
                        AND e.estado = '0'
                        AND a.caja_id = e.caja_id
                        AND a.cuenta_tipo_id='01'
                        $var

                        ORDER BY recibo_caja
                        DESC LIMIT 12 OFFSET 0
            ";

            $spi = 1;
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {

            $query = "
                    SELECT
                    e.empresa_id,
                    e.recibo_caja,
                    e.centro_utilidad,
                    e.prefijo,
                    e.fecha_ingcaja,
                    e.total_abono,
                    e.total_bonos,
                    e.total_efectivo,
                    e.total_cheques,
                    e.total_tarjetas,
                    e.tipo_id_tercero,
                    e.tercero_id,
                    e.estado,
                    e.fecha_registro,
                    e.usuario_id

                    FROM
                    recibos_caja as e,
                    cajas as a,
                    rc_detalle_pto_vta as h

                    WHERE
                    h.cuenta_pv = $Cuenta
                    AND e.empresa_id = h.empresa_id
                    AND e.centro_utilidad = h.centro_utilidad
                    AND e.recibo_caja = h.recibo_caja
                    AND e.prefijo = h.prefijo
                    AND e.estado = '0'
                    AND a.caja_id = e.caja_id
                    AND a.cuenta_tipo_id = '02'
                    $var

                    ORDER BY recibo_caja
                    DESC LIMIT 12 OFFSET 0
            ";

            $spi = 1;
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            $tipo = $_SESSION['CAJA']['TIPO_ID_TERCERO'];
            $tercero = $_SESSION['CAJA']['TERCEROID'];
            $query = "SELECT
                        e.empresa_id,
                        e.recibo_caja,
                        e.centro_utilidad,
                        e.prefijo,
                        e.fecha_ingcaja,
                        e.total_abono,
                        e.total_bonos,
                        e.total_efectivo,
                        e.total_cheques,
                        e.total_tarjetas,
                        e.tipo_id_tercero,
                        e.tercero_id,
                        e.estado,
                        e.fecha_registro,
                        e.usuario_id

                        FROM recibos_caja as e,
                        cajas as a

                        WHERE
                        e.tipo_id_tercero = '$tipo'
                        AND e.tercero_id = '$tercero'
                        AND e.caja_id = a.caja_id
                        AND e.estado = '0'
                        AND a.cuenta_tipo_id='03'
                        $var
            ";
            $spi = 1;
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            $tipo = $_SESSION['CAJA']['TIPO_ID_PACIENTE'];
            $paciente = $_SESSION['CAJA']['PACIENTEID'];

            if ($Recibo) {
                $var = "AND c.recibo_caja='$Recibo'";
            } else {
                $var = '';
            }

            $query = "
                        SELECT
                        c.empresa_id,
                        c.recibo_caja,
                        c.centro_utilidad,
                        c.prefijo,
                        c.fecha_ingcaja,
                        c.total_abono,
                        c.total_efectivo,
                        c.total_cheques,
                        c.total_tarjetas,
                        c.total_bonos,
                        c.tipo_id_tercero,
                        c.tercero_id,
                        c.estado,
                        c.fecha_registro,
                        c.usuario_id

                        FROM
                        servicios_ambulatorios_autorizados as a,
                        rc_detalle_cargos_ambulatorios as b,
                        recibos_caja as c,
                        cajas as v

                        WHERE
                        paciente_id='$paciente'
                        and tipo_id_paciente='$tipo'
                        and a.rc_detalle_cargo_amb_id=b.rc_detalle_cargo_amb_id
                        and b.empresa_id=c.empresa_id
                        and b.centro_utilidad=c.centro_utilidad
                        and b.recibo_caja=c.recibo_caja
                        and c.estado IN ('0')
                        and b.prefijo=c.prefijo
                        and v.cuenta_tipo_id='04' $var
            ";

            $spi = 1;
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
            $tipo = $_SESSION['CAJA']['TIPO_ID_PACIENTE'];
            $paciente = $_SESSION['CAJA']['PACIENTEID'];

            if ($Recibo) {
                $var = "AND c.recibo_caja='$Recibo'";
            } else {
                $var = '';
            }

            $query = "SELECT
            c.empresa_id,
            c.recibo_caja,
            c.centro_utilidad,
                            c.prefijo,
                            c.fecha_ingcaja,
                            c.total_abono,
                            c.total_efectivo,
                            c.total_cheques,
                            c.total_tarjetas,
                            c.total_bonos,
                            c.tipo_id_tercero,
                            c.tercero_id,
                            c.estado,
                            c.fecha_registro,
                            c.usuario_id

                            FROM
                            servicios_ambulatorios_autorizados as a,
                            rc_detalle_cargos_ambulatorios as b,
                            recibos_caja as c,
                            cajas as v

                            WHERE
                            paciente_id='$paciente'
                            and tipo_id_paciente='$tipo'
                            and a.rc_detalle_cargo_amb_id=b.rc_detalle_cargo_amb_id
                            and b.empresa_id=c.empresa_id
                            and b.centro_utilidad=c.centro_utilidad
                            and b.recibo_caja=c.recibo_caja
                            and c.estado IN ('0')
                            and b.prefijo=c.prefijo
                            and v.cuenta_tipo_id='04'
                            $var";
            //cambiar al cuenta de tipo 05 ojo con esto preguntar...
            $spi = 1;
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {

            $query = "
                            SELECT
                            e.empresa_id,
                            e.recibo_caja,
                            e.centro_utilidad,
                            e.prefijo,
                            e.fecha_ingcaja,
                            e.total_abono,
                            e.total_bonos,
                            e.total_efectivo,
                            e.total_cheques,
                            e.total_tarjetas,
                            e.tipo_id_tercero,
                            e.tercero_id,
                            e.estado,
                            e.fecha_registro,
                            e.usuario_id,
                            e.caja_id

                            FROM
                            recibos_caja e,
                            cajas as a,
                            pagares h,
                            rc_detalle_pagare i

                            WHERE h.numerodecuenta=$Cuenta
                            AND e.recibo_caja=i.recibo_caja
                            AND e.prefijo=i.prefijo
                            AND i.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                            AND i.prefijo_pagare='$Prefijo'
                            AND i.numero=$PagareNumero
                            AND e.caja_id=a.caja_id
                            AND h.numero=i.numero
                            AND h.prefijo=i.prefijo_pagare
                            AND e.estado = '0'
                            AND a.cuenta_tipo_id='06'

                            ORDER BY recibo_caja
                            DESC LIMIT 12 OFFSET 0
            ";
            $spi = 1;
        }

        if ($spi == 1) {
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                return false;
            }
            $i = 0;
            while (!$result->EOF) {
                $vars[$i] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
                $i++;
            }
            $result->Close();
        }
        return $vars;
    }

    function ConsultaCabeceraPagare($empresa, $prefijo, $pagarenumero) {
        $empresa = $_SESSION['CAJA']['EMPRESA'];
        list($dbconn) = GetDBconn();
        $query = "    SELECT B.tipo_id_tercero,B.tercero_id,B.nombre_tercero
                                            FROM pagares_responsables A, terceros B, empresas C, pagares D
                                            WHERE D.empresa_id='$empresa'
                                            AND D.prefijo='$prefijo'
                                            AND D.numero=$pagarenumero
                                            AND A.prefijo=D.prefijo
                                            AND B.tercero_id=A.tercero_id
                                            AND B.tipo_id_tercero=A.tipo_id_tercero
                                            AND C.empresa_id=A.empresa_id
                                            AND D.empresa_id=A.empresa_id
                                            AND D.prefijo=A.prefijo
                                            AND D.numero=A.numero";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $i = 0;
        while (!$result->EOF) {
            $var[$i] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
            $i++;
        }
        $result->Close();
        return $var;
    }

    function ConsultaDetalleTercero($Cuenta) {
        list($dbconn) = GetDBconn();
        $query = "select a.plan_pv_id,b.tipo_id_tercero,b.tercero_id,b.nombre_tercero
                ,c.razon_social,d.descripcion from cab_cuenta_pv a, terceros b, empresas c,centros_utilidad
                d where cuenta_pv=$Cuenta and b.tercero_id=a.tercero_id
                AND b.tipo_id_tercero=a.tipo_tercero_id
                AND c.empresa_id=a.empresa_id
                AND d.centro_utilidad=a.centro_utilidad_id and d.empresa_id=a.empresa_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $i = 0;
        while (!$result->EOF) {
            $var[$i] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
            $i++;
        }
        $result->Close();
        return $var;
    }

    function ConsultaCabeceraPvta($Cuenta) {
        list($dbconn) = GetDBconn();
        $query = "select a.plan_pv_id,b.tipo_id_tercero,b.tercero_id,b.nombre_tercero
                ,c.razon_social,d.descripcion from cab_cuenta_pv a, terceros b, empresas c,centros_utilidad
                d where cuenta_pv=$Cuenta and b.tercero_id=a.tercero_id
                AND b.tipo_id_tercero=a.tipo_tercero_id
                AND c.empresa_id=a.empresa_id
                AND d.centro_utilidad=a.centro_utilidad_id and d.empresa_id=a.empresa_id";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $i = 0;
        while (!$result->EOF) {
            $var[$i] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
            $i++;
        }
        $result->Close();
        return $var;
    }

    /**
     * La funcion  TotalAbonos se encarga de obtener de la base de datos
     * los totales de los cheques, las tarjetas, los efectivos y el total de los
     * pagos que se efectuan en el momento, antes de guardar el nuevo recibo de caja,
     * segun la cuenta de una persona.
     * @access public
     * @return array
     */
    function TotalAbonos($Cuenta) {
        if (!$Cuenta) {
            $Cuenta = $_SESSION['CAJA']['CUENTA'];
        }
        // if($_SESSION['TIPOCUENTA']=='04' || $_SESSION['TIPOCUENTA']=='03'){ $Cuenta=0; }
        list($dbconn) = GetDBconn();
        $query = " select sum(total) from tmp_cheques_mov where numerodecuenta=$Cuenta";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $var[cheque] = $result->fields[0];

        $query = " select sum(total) from tmp_tarjetas_mov_credito where numerodecuenta=$Cuenta";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $var[tarjeta_credito] = $result->fields[0];

        $query = " select sum(total) from tmp_tarjetas_mov_debito where numerodecuenta=$Cuenta";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $var[tarjeta_debito] = $result->fields[0];

//       $query = " select sum(valor_bono) from tmp_caja_bonos where numerodecuenta=$Cuenta";
//        $result = $dbconn->Execute($query);
//
//       if ($dbconn->ErrorNo() != 0) {
//         $this->error = "Error al Cargar el Modulo";
//         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//         return false;
//       }
//       $var[caja_bono]=$result->fields[0];

        return $var;
    }

    //FUNCION PARA EL CALCULO DEL SALDO TOTAL DE UNA CUENTA DADA
    function TotalSaldoCuenta($Cuenta, $Devol) {
        list($dbconn) = GetDBconn();
        $query = "SELECT SUM(B.total_abono) AS totalabono
                    FROM rc_detalle_hosp A, recibos_caja B, cuentas C
                                    WHERE B.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                                AND A.numerodecuenta=$Cuenta
                                                AND A.numerodecuenta=C.numerodecuenta
                                                AND A.empresa_id=B.empresa_id
                                                AND A.centro_utilidad=B.centro_utilidad
                                                AND A.prefijo=B.prefijo
                                                AND B.estado IN ('0')
                                                AND A.recibo_caja=B.recibo_caja";
        $result = $dbconn->Execute($query);
        $totalabono = $result->GetRowAssoc($ToUpper = false);

        $query = "SELECT total_cuenta
                                FROM cuentas
                                WHERE empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                                AND numerodecuenta=$Cuenta";
        $result = $dbconn->Execute($query);
        $total_cuenta = $result->GetRowAssoc($ToUpper = false);
        if (!empty($Devol))
            $var = ($total_cuenta[total_cuenta] + $Devol) - $totalabono[totalabono];
        else
            $var = $total_cuenta[total_cuenta] - $totalabono[totalabono];
        return $var;
    }

    /**
     * La funcion  MovChequesTmp se encarga de obtener de la base de datos
     * los movimientos de pagos de cheques que hace la persona en el momento antes de
     * guardar el nuevo recibo de caja(REPRESENTADO EN UNA TABLA COMO CONSULTA).
     * segun la cuenta de una persona.
     * @access public
     * @return array
     */
    function MovChequesTmp($Cuenta) {    //if($_SESSION['TIPOCUENTA']=='04' || $_SESSION['TIPOCUENTA']=='03'){ $Cuenta=0; }
        list($dbconn) = GetDBconn();
        $query = "SELECT e.cheque_mov_id,e.banco,f.descripcion,e.cta_cte,e.cheque,e.girador,e.fecha_cheque,e.total,e.fecha
                FROM tmp_cheques_mov e,bancos f WHERE numerodecuenta=$Cuenta AND f.banco=e.banco";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $i = 0;
        while (!$result->EOF) {
            $var[$i] = $result->GetRowAssoc($ToUpper = false);
            $i++;
            $result->MoveNext();
        }
        return $var;
    }

    /**
     * La funcion  MovCheques se encarga de obtener de la base de datos
     * los movimientos de pagos de cheques que ha hecho
     * la persona con diferentes numeros de recibos de caja segun su numero de cuenta.
     * (REPRESENTADO EN UNA TABLA COMO CONSULTA(de manera GENERAL)).
     * segun la cuenta de una persona.
     * @access public
     * @return array
     */
    function MovCheques($Cuenta, $Recibo, $PagareNumero, $Prefijo) {
        list($dbconn) = GetDBconn();
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                        recibo_caja,prefijo,numerodecuenta from rc_detalle_hosp
                        WHERE numerodecuenta=$Cuenta and recibo_caja='$Recibo'";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                        recibo_caja,prefijo,cuenta_pv from rc_detalle_pto_vta
                        WHERE cuenta_pv=$Cuenta and recibo_caja='$Recibo'";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                        recibo_caja,prefijo from rc_detalle_conceptos
                        WHERE  recibo_caja='$Recibo'";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                        recibo_caja,prefijo from rc_detalle_cargos_ambulatorios
                        WHERE  recibo_caja='$Recibo'";
        } else
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                        recibo_caja,prefijo,numero from rc_detalle_pagare
                        WHERE prefijo='$Prefijo' AND numero=$PagareNumero and recibo_caja='$Recibo'";
        }
        $resulta = $dbconn->Execute($busqueda);
        $conteo = $resulta->RecordCount();
        $i = 0;
        while (!$resulta->EOF) {
            $caja[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $i++;
            $resulta->MoveNext();
        }
        $i = 0;
        for ($j = 0; $j < $conteo; $j++) {
            $prefijo = $caja[$j][prefijo];
            $recibo = $caja[$j][recibo_caja];
            $empresa = $caja[$j][empresa_id];
            $cutilidad = $caja[$j][centro_utilidad];
            $query = "SELECT e.cheque_mov_id,
                          e.banco,
                          e.recibo_caja,
                          f.descripcion,
                          e.cta_cte,
                          e.cheque,
                          e.girador,
                          e.fecha_cheque,
                          e.total,
                          e.fecha
                  FROM cheques_mov e,  bancos f
                  WHERE prefijo= '$prefijo' AND recibo_caja='$recibo'
                  AND empresa_id='$empresa' AND centro_utilidad='$cutilidad'
                  AND e.banco=f.banco";
            $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                return false;
            }
            while (!$result->EOF) {
                $var[$i] = $result->GetRowAssoc($ToUpper = false);
                $i++;
                $result->MoveNext();
            }
        }
        return $var;
    }

    /**
     * La funcion  MovTarjetasCreditoTmp se encarga de obtener de la base de datos
     * los movimientos de pagos de tarjetas de credito, que hace la persona en el momento antes de
     * guardar el nuevo recibo de caja(REPRESENTADO EN UNA TABLA COMO CONSULTA).
     * segun la cuenta de una persona.
     * @access public
     * @return array
     */
    function MovTarjetasCreditoTmp($Cuenta) {
        list($dbconn) = GetDBconn();
        //if($_SESSION['TIPOCUENTA']=='04' || $_SESSION['TIPOCUENTA']=='03'){ $Cuenta=0; }
        $query = "SELECT a.tarjeta_numero,a.tarjeta,a.fecha,a.autorizacion,a.socio,a.fecha_expira,a.autorizado_por,a.total,b.descripcion
                FROM tmp_tarjetas_mov_credito a, tarjetas b  WHERE numerodecuenta=$Cuenta AND b.tarjeta=a.tarjeta";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $i = 0;
        while (!$result->EOF) {
            $var[$i] = $result->GetRowAssoc($ToUpper = false);
            $i++;
            $result->MoveNext();
        }
        return $var;
    }

    /**
     * La funcion  MovTarjetasDebitoTmp se encarga de obtener de la base de datos
     * los movimientos de pagos de tarjetas de debito, que hace la persona en el momento antes de
     * guardar el nuevo recibo de caja(REPRESENTADO EN UNA TABLA COMO CONSULTA).
     * segun la cuenta de una persona.
     * @access public
     * @return array
     */
    function MovTarjetasDebitoTmp($Cuenta) {
        list($dbconn) = GetDBconn();
        //if($_SESSION['TIPOCUENTA']=='04' || $_SESSION['TIPOCUENTA']=='03'){ $Cuenta=0; }
        $query = "SELECT a.tarjeta,a.autorizacion,a.total,b.descripcion,a.tarjeta_numero
                FROM tmp_tarjetas_mov_debito a, tarjetas b WHERE numerodecuenta=$Cuenta AND b.tarjeta=a.tarjeta";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $i = 0;
        while (!$result->EOF) {
            $var[$i] = $result->GetRowAssoc($ToUpper = false);
            $i++;
            $result->MoveNext();
        }
        return $var;
    }

    /**
     * La funcion  MovTarjetasCredito se encarga de obtener de la base de datos
     * los movimientos de pagos de tarjetas credito que ha hecho
     * la persona con diferentes numeros de recibos de caja segun su numero de cuenta.
     * (REPRESENTADO EN UNA TABLA COMO CONSULTA(de manera GENERAL)).
     *
     * @access public
     * @return array
     */
    function MovTarjetasCredito($Cuenta, $Recibo, $PagareNumero, $Prefijo) {
        list($dbconn) = GetDBconn();

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                 recibo_caja,prefijo,prefijo,numerodecuenta from rc_detalle_hosp
                 WHERE numerodecuenta=$Cuenta and recibo_caja='$Recibo'";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                        recibo_caja,prefijo,cuenta_pv from rc_detalle_pto_vta
                        WHERE cuenta_pv=$Cuenta and recibo_caja='$Recibo'";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                        recibo_caja,prefijo from rc_detalle_conceptos
                        WHERE  recibo_caja='$Recibo'";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                        recibo_caja,prefijo from rc_detalle_cargos_ambulatorios
                        WHERE  recibo_caja='$Recibo'";
        } else
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                    recibo_caja,prefijo,prefijo,numero from rc_detalle_pagare
                    WHERE numero=$PagareNumero and recibo_caja='$Recibo'
                                    AND prefijo='$Prefijo'";
        }

        $resulta = $dbconn->Execute($busqueda);
        $conteo = $resulta->RecordCount();
        $i = 0;
        while (!$resulta->EOF) {
            $caja[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $i++;
            $resulta->MoveNext();
        }
        $i = 0;
        for ($j = 0; $j < $conteo; $j++) {
            $prefijo = $caja[$j][prefijo];
            $recibo = $caja[$j][recibo_caja];
            $empresa = $caja[$j][empresa_id];
            $cutilidad = $caja[$j][centro_utilidad];
            $query = "SELECT e.tarjeta_mov_id,
                          e.tarjeta,
                          f.descripcion,
                          e.empresa_id,
                          e.centro_utilidad,
                          e.recibo_caja,
                          e.prefijo,
                          e.fecha,
                          e.autorizacion,
                          e.socio,
                          e.fecha_expira,
                          e.autorizado_por,
                          e.total,
                          e.usuario_id,
                          e.fecha_registro,
                          e.tarjeta_numero
                  FROM tarjetas_mov_credito e,tarjetas f
                  WHERE prefijo= '$prefijo' AND recibo_caja='$recibo'
                  AND empresa_id='$empresa' AND centro_utilidad='$cutilidad'
                  AND e.tarjeta=f.tarjeta";

            $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                return false;
            }
            while (!$result->EOF) {
                $var[$i] = $result->GetRowAssoc($ToUpper = false);
                $i++;
                $result->MoveNext();
            }
        }
        return $var;
    }

    /**
     * La funcion  MovTarjetasDebito se encarga de obtener de la base de datos
     * los movimientos de pagos de tarjetas debito que ha hecho
     * la persona con diferentes numeros de recibos de caja segun su numero de cuenta.
     * (REPRESENTADO EN UNA TABLA COMO CONSULTA(de manera GENERAL)).
     * @access public
     * @return array
     */
    function MovTarjetasDebito($Cuenta, $Recibo, $PagareNumero, $Prefijo) {
        list($dbconn) = GetDBconn();


        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                 recibo_caja,prefijo,prefijo,numerodecuenta from rc_detalle_hosp
                 WHERE numerodecuenta=$Cuenta and recibo_caja='$Recibo'";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {



            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                        recibo_caja,prefijo,cuenta_pv from rc_detalle_pto_vta
                        WHERE cuenta_pv=$Cuenta and recibo_caja='$Recibo'";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                        recibo_caja,prefijo from rc_detalle_conceptos
                        WHERE  recibo_caja='$Recibo'";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                        recibo_caja,prefijo from rc_detalle_cargos_ambulatorios
                        WHERE  recibo_caja='$Recibo'";
        } else
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
            $busqueda = " SELECT DISTINCT empresa_id,centro_utilidad,
                                            recibo_caja,prefijo,prefijo,numero from rc_detalle_pagare
                                            WHERE numero=$PagareNumero and recibo_caja='$Recibo'
                                            AND prefijo='$Prefijo'";
        }


        $resulta = $dbconn->Execute($busqueda);
        $conteo = $resulta->RecordCount();
        $i = 0;
        while (!$resulta->EOF) {
            $caja[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $i++;
            $resulta->MoveNext();
        }
        $i = 0;
        for ($j = 0; $j < $conteo; $j++) {
            $prefijo = $caja[$j][prefijo];
            $recibo = $caja[$j][recibo_caja];
            $empresa = $caja[$j][empresa_id];
            $cutilidad = $caja[$j][centro_utilidad];

            $query = "SELECT e.tarjeta_mov_db_id,
                          e.tarjeta,
                          f.descripcion,
                          e.empresa_id,
                          e.centro_utilidad,
                          e.recibo_caja,
                          e.prefijo,
                          e.autorizacion,
                          e.total,
                          e.tarjeta_numero
                  FROM tarjetas_mov_debito e,tarjetas f
                  WHERE prefijo= '$prefijo' AND recibo_caja='$recibo'
                  AND empresa_id='$empresa' AND centro_utilidad='$cutilidad'
                  AND e.tarjeta=f.tarjeta";

            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                return false;
            }
            while (!$result->EOF) {
                $var[$i] = $result->GetRowAssoc($ToUpper = false);
                $i++;
                $result->MoveNext();
            }
        }
        return $var;
    }

    /**
     * La funcion  MovTarjetasDebito se encarga de obtener de la base de datos
     * los movimientos de pagos de tarjetas debito que ha hecho
     * la persona con diferentes numeros de recibos de caja segun su numero de cuenta.
     * (REPRESENTADO EN UNA TABLA COMO CONSULTA(de manera GENERAL)).
     * @access public
     * @return array
     */
    function MovBonos($Cuenta, $Recibo) {
        list($dbconn) = GetDBconn();


        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                 recibo_caja,prefijo,prefijo,numerodecuenta from rc_detalle_hosp
                 WHERE numerodecuenta=$Cuenta and recibo_caja='$Recibo'";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {



            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                        recibo_caja,prefijo,cuenta_pv from rc_detalle_pto_vta
                        WHERE cuenta_pv=$Cuenta and recibo_caja='$Recibo'";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                        recibo_caja,prefijo from rc_detalle_conceptos
                        WHERE  recibo_caja='$Recibo'";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            $busqueda = "SELECT DISTINCT empresa_id,centro_utilidad,
                        recibo_caja,prefijo from rc_detalle_cargos_ambulatorios
                        WHERE  recibo_caja='$Recibo'";
        }


        $resulta = $dbconn->Execute($busqueda);
        $conteo = $resulta->RecordCount();
        $i = 0;
        while (!$resulta->EOF) {
            $caja[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $i++;
            $resulta->MoveNext();
        }
        $i = 0;
        for ($j = 0; $j < $conteo; $j++) {
            $prefijo = $caja[$j][prefijo];
            $recibo = $caja[$j][recibo_caja];
            $empresa = $caja[$j][empresa_id];
            $cutilidad = $caja[$j][centro_utilidad];

            $query = "SELECT e.valor_bono,
                          f.descripcion,
                           e.empresa_id,
                          e.centro_utilidad,
                          e.recibo_caja,
                          e.prefijo
                  FROM caja_bonos e,tipos_bonos f
                  WHERE prefijo= '$prefijo' AND recibo_caja='$recibo'
                  AND e.empresa_id='$empresa' AND e.centro_utilidad='$cutilidad'
                  AND e.tipo_bono=f.tipo_bono";

            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al buscar los movimientos de los Bonos 1479";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            while (!$result->EOF) {
                $var[$i] = $result->GetRowAssoc($ToUpper = false);
                $i++;
                $result->MoveNext();
            }
        }
        return $var;
    }

    /**
     * La funcion  ComboBancos se encarga de obtener de la base de datos
     * los tipos de bancos disponibles, y retorna un arreglo el cual se podr�    * visualizar en html mediante un objeto combo por ejemplo: Coomeva,Colpatria
     *
     * @access public
     * @return array
     */
    function ComboBancos() {
        list($dbconn) = GetDBconn();
        $query = "SELECT banco,descripcion  FROM bancos";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i = 0;
        while (!$result->EOF) {
            $var[$i] = $result->GetRowAssoc($ToUpper = false);
            $i++;
            $result->MoveNext();
        }
        return $var;
    }

    /**
     * La funcion  ComboTarjetas se encarga de obtener de la base de datos
     * los tipos de tarjetas disponibles, y retorna un arreglo el cual se podr�    * visualizar en html mediante un objeto combo por ejemplo: Visa,Diners
     *
     * @access public
     * @return array
     */
    function ComboTarjetas() {
        list($dbconn) = GetDBconn();
        $query = "SELECT tarjeta,descripcion,comision,cuotas_maxima,sw_tipo FROM tarjetas
								WHERE sw_estado = '1'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i = 0;
        while (!$result->EOF) {
            $var[$i] = $result->GetRowAssoc($ToUpper = false);
            $i++;
            $result->MoveNext();
        }
        return $var;
    }

    /* funcion de jaime para traer las citas incumplidas o inasistidas
      tener en cuenta esta funcion para uso posterior
     */

    function CitasIncumplidasPaciente($tipoid, $paciente, $plan) {
        list($dbconn) = GetDBconn();
        $sql = "SELECT actividad_incumplimientos FROM planes where plan_id=$plan;";
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al consultar en planes";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $query = "SELECT c.fecha_turno || ' ' || b.hora as fecha,
              f.nombre_tercero, a.agenda_cita_asignada_id, g.descripcion, a.cargo_cita, a.plan_id, h.numero_orden_id

              FROM agenda_citas_asignadas as a,agenda_citas as b, agenda_turnos as c,
              profesionales as e,terceros as f, tipos_servicios_ambulatorios as g, os_cruce_citas as h, os_maestro as i

              WHERE
              h.numero_orden_id=i.numero_orden_id
              AND a.agenda_cita_asignada_id=h.agenda_cita_asignada_id
              AND a.agenda_cita_id=b.agenda_cita_id
              AND g.tipo_servicio_amb_id=c.tipo_consulta_id
              AND b.agenda_turno_id=c.agenda_turno_id
              AND date(c.fecha_turno) < date(now())
              AND date(c.fecha_turno) > (date(now())-" . $result->fields[0] . ")
              AND a.paciente_id='$paciente' and tipo_id_paciente='$tipoid'
              AND c.profesional_id=e.tercero_id
              AND c.tipo_id_profesional=e.tipo_id_tercero
             -- and i.sw_estado!=3
                            and i.sw_estado='1'
              AND e.tercero_id=f.tercero_id
              AND e.tipo_id_tercero=f.tipo_id_tercero
                            AND a.sw_atencion!='1'
                            AND b.sw_estado !='3';";


        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {

            if ($resulta->RecordCount() < 1) {
                return 0; //esto es para que el vector no muestre nada..
            } else {
                $i = 0;
                while (!$resulta->EOF) {
                    $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
                    $i++;
                    $resulta->MoveNext();
                }
            }
        }
        return $var;
    }

    /*
     * Esta funcion trae los datos de la tabla
     * os_maestro,os_ordenes_servicios segun el numero de la orden.
     * @return array
     */

    function DatosOs($orden) {
        list($dbconn) = GetDBconn();
        $query = "SELECT *, d.descripcion,c.os_maestro_cargos_id FROM os_ordenes_servicios a,os_maestro b,os_maestro_cargos c,
       tarifarios_detalle as d
      WHERE a.orden_servicio_id=b.orden_servicio_id AND b.numero_orden_id=$orden
      AND b.numero_orden_id=c.numero_orden_id and c.cargo=d.cargo
      and c.tarifario_id=d.tarifario_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las 0rdenes de servicios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }

        $result->Close();
        return $var;
    }

    /* funcion que realiza la liquidacion de las ordenes de servicios(CAJA GENERAL))
      y genera el arreglo que ira a empatar con el arreglo de pagos de caja rapida.
     */

    function LiquidarCargoCuentaGeneral() {
        $id = $_REQUEST['id'];
        $tipo = $_REQUEST['id_tipo'];
        $nom = $_REQUEST['nom'];
        $plan = $_REQUEST['plan_id'];
        $op = $_REQUEST['op'];

        unset($_SESSION['CAJA']['OTRAVEZ']); //variable q coloca el valor por defecto q tiene q pagar a
        unset($_SESSION['CAJA']['ARRAY_PAGO']); //arreglo q contiene los  cargos..
        unset($_SESSION['CAJA']['liq']);
        unset($_SESSION['CAJA']['datos']);
        unset($_SESSION['CAJA']['vector']);
        unset($_SESSION['CAJA']['AUX']['vector']);
        unset($_SESSION['CAJA']['AUX']['liq']);
        unset($_SESSION['CAJA']['AUX']['datos']);
        unset($_SESSION['ARREGLO_CITAS_INCUMPLIDAS']); //vector q  contiene el listado de
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
        //las citas incumplidas....
        $j = 0;
        IncludeLib("tarifario_cargos");
        IncludeLib("funciones_facturacion");

        $total_cargo = $total_paciente = $total_empresa = 0;
        $cargo_liq = array(); //arreglo que contiene los cargos y demas datos para liquidarlos.
        $Arr_Descripcion[] = array(); //arreglo para guardar la descripcion y los servicios.
        //$i=0;
        foreach ($op as $index => $codigo) {
            $valores = explode(",", $codigo);
            $datos = $this->DatosOs($valores[0]);
            for ($i = 0; $i < sizeof($datos); $i++) {
                $dat[$j]['cargo'] = $datos[$i]['cargo'];
                $dat[$j]['tarifario_id'] = $datos[$i]['tarifario_id'];
                $dat[$j][descripcion] = $datos[$i]['descripcion'];
                $dat[$j][numero_orden_id] = $datos[$i]['numero_orden_id'];
                $dat[$j][os_maestro_cargos_id] = $datos[$i]['os_maestro_cargos_id'];
                $Arr_Descripcion[$j] = array('des_cargo' => $valores[6], 'servicio' => $valores[7], 'des_servicio' => $valores[8], 'numero_orden_id' => $datos[$i]['numero_orden_id'], 'cargo' => $valores[1]);
                $cargo_liq[] = array('tarifario_id' => $datos[$i]['tarifario_id'], 'cargo' => $datos[$i]['cargo'], 'cantidad' => $datos[$i]['cantidad'], 'autorizacion_int' => $datos[$i]['autorizacion_int'], 'autorizacion_ext' => $datos[$i]['autorizacion_ext']);
                $j++;
            }
            //$i++;
        }

        $emp = BuscarEmpleadorOrden($valores[0]);
        $cargo_fact = LiquidarCargosCuentaVirtual($cargo_liq, '', '', $datos[0][plan_id], $datos[0][tipo_afiliado_id], $datos[0][rango], $datos[0][semanas_cotizacion], $datos[0][servicio], $tipo, $id, $emp[tipo_id_empleador], $emp[empleador_id]);

        $afiliado = $datos[0][tipo_afiliado_id];
        $rango = $datos[0][rango];
        $sem = $datos[0][semanas_cotizacion];
        $auto = $datos[0][autorizacion_int];
        $serv = $datos[0][servicio];
        $_SESSION['CAJA']['AUX']['afiliado'] = $afiliado;
        $_SESSION['CAJA']['AUX']['rango'] = $rango;
        $_SESSION['CAJA']['AUX']['sem'] = $sem;
        $_SESSION['CAJA']['AUX']['auto'] = $auto;
        $_SESSION['CAJA']['AUX']['serv'] = $serv;
        //exit;
        $k = 0;
        foreach ($cargo_fact as $w => $Cargo) {
            foreach ($Cargo as $key => $v) {
                $cargo_arr[] = array('tarifario_id' => $v['tarifario_id'], 'descripcion' => $v[descripcion], 'os_maestro_cargos_id' => $dat[$k]['os_maestro_cargos_id'], 'numero_orden_id' => $dat[$k]['numero_orden_id'], 'cargo' => $v['cargo'], 'des_servicio' => $Arr_Descripcion[$k][des_servicio], 'cantidad' => $v['cantidad'], 'valor_cargo' => $v[valor_cargo]);
                $k++;
            }
        }

        $_SESSION['CAJA']['ARRAY_PAGO'] = $cargo_arr;
        $_SESSION['CAJA']['AUX']['liq'] = $cargo_fact;
        $_SESSION['CAJA']['AUX']['datos'] = $dat;
        $_SESSION['CAJA']['AUX']['vector'] = $vector;
        $_SESSION['CAJA']['AUX']['tipo_id_paciente'] = $tipo;
        $_SESSION['CAJA']['AUX']['paciente_id'] = $id;
        $_SESSION['CAJA']['AUX']['plan_id'] = $plan;
        $_SESSION['CAJA']['AUX']['op'] = $op;
        $_SESSION['CAJA']['AUX']['nom'] = $nom;
        /* variable que determina en q caja se esta trabajando
          1 si es en caja general
          2 si es en caja rapida */
        $_SESSION['CAJA']['AUX']['RUTA_CAJA'] = 1;

        $this->FormaOrdenesServicio($_SESSION['CAJA']['AUX']['tipo_id_paciente'], $_SESSION['CAJA']['AUX']['paciente_id'], $_SESSION['CAJA']['AUX']['plan_id']);
        return true;
    }

    function UserUltimoCuadre($Caja, $TipoCuenta, $dp) {
        list($dbconn) = GetDBconn();
        if ($TipoCuenta == '01' OR $TipoCuenta == '02') {
            $query = "SELECT MAX(a.fecha_registro) as fecha
        FROM recibos_caja_cierre a, cajas b, recibos_caja c, cajas_usuarios d
        WHERE b.caja_id=$Caja
        AND b.caja_id=c.caja_id
        AND d.caja_id=c.caja_id
        AND d.usuario_id=c.usuario_id
        AND b.cuenta_tipo_id='$TipoCuenta'
        AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
        AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
        AND a.cierre_caja_id is not null
        AND a.cierre_caja_id NOT IN( SELECT b.cierre_caja_id FROM cierre_de_caja a,cierre_de_caja_detalle b WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
        AND a.cierre_caja_id=c.cierre_caja_id
        AND c.estado IN ('0');";
        } else
        if ($TipoCuenta == '03' OR $TipoCuenta == '08') {
            $query = "SELECT MAX(c.fecha_registro) as fecha
                                FROM fac_facturas_contado a, cajas_rapidas b,
                                        recibos_caja_cierre c
                                WHERE b.caja_id=$Caja
                                AND b.caja_id=a.caja_id
                                AND a.cierre_caja_id=c.cierre_caja_id
                                AND b.cuenta_tipo_id='$TipoCuenta'
                                AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                AND a.cierre_caja_id is not null
                                        AND a.cierre_caja_id NOT IN(
                                                        SELECT b.cierre_caja_id
                                                        FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                        WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                                        AND a.caja_id=b.caja_id;";
        } else
        if ($TipoCuenta == '04') {
            $query = "SELECT MAX(a.fecha_registro) as fecha
                                FROM fac_facturas_contado a, cajas b
                                WHERE b.caja_id=$Caja
                                AND b.cuenta_tipo_id='$TipoCuenta'
                                AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                AND (a.cierre_caja_id is not null
                                        AND a.cierre_caja_id NOT IN(
                                                        SELECT b.cierre_caja_id
                                                        FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                        WHERE a.cierre_de_caja_id=b.cierre_de_caja_id))
                                        AND a.caja_id=b.caja_id;";
        } else
        if ($TipoCuenta == '05') {
            /*          echo    $query="    SELECT MAX(c.fecha_registro) as fecha
              FROM fac_facturas_contado a, cajas_rapidas b ,
              recibos_caja_cierre c
              WHERE b.departamento='$dp'
              AND a.cierre_caja_id is not null
              AND a.cierre_caja_id=c.cierre_caja_id
              AND a.cierre_caja_id NOT IN( SELECT b.cierre_caja_id
              FROM cierre_de_caja a,cierre_de_caja_detalle b
              WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
              AND a.caja_id=b.caja_id;"; */
            $query = "        SELECT a.usuario_id, d.nombre
                FROM fac_facturas_contado a, cajas_rapidas b,
                        recibos_caja_cierre c, system_usuarios d
                WHERE c.fecha_registro=(SELECT MAX(c.fecha_registro)
                                                                FROM fac_facturas_contado a,
                                                                    cajas_rapidas b,
                                                                    recibos_caja_cierre c
                                                                WHERE b.departamento='$dp'
                                                                    AND a.cierre_caja_id is not null
                                                                    AND a.cierre_caja_id=c.cierre_caja_id
                                                                    AND a.caja_id=b.caja_id
                                                                    AND b.caja_id=$Caja)
                AND  b.departamento='$dp'
                AND a.usuario_id=d.usuario_id
                AND a.cierre_caja_id is not null
                AND a.cierre_caja_id=c.cierre_caja_id
                AND a.cierre_caja_id NOT IN(SELECT b.cierre_caja_id
                                                                        FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                                        WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                AND b.caja_id=$Caja
                AND a.caja_id=b.caja_id
                GROUP BY a.usuario_id, d.nombre;";
        } elseif ($TipoCuenta == '06') {
            $query = "SELECT  MAX(c.fecha_registro) as fecha
                    FROM recibos_caja a, cajas b,
                            recibos_caja_cierre c, system_usuarios d
                    WHERE c.fecha_registro=(SELECT MAX(c.fecha_registro)
                                                                    FROM recibos_caja a,
                                                                        cajas b,
                                                                        recibos_caja_cierre c
                                                                    WHERE a.cierre_caja_id is not null
                                                                        AND a.cierre_caja_id=c.cierre_caja_id
                                                                        AND a.caja_id=b.caja_id
                                                                        AND b.caja_id=$Caja
                                                                        AND a.estado IN ('0'))
                    AND a.usuario_id=d.usuario_id
                    AND a.cierre_caja_id is not null
                    AND a.cierre_caja_id=c.cierre_caja_id
                    AND a.cierre_caja_id NOT IN(SELECT b.cierre_caja_id
                                                                            FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                                            WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                    AND b.caja_id=$Caja
                    AND a.caja_id=b.caja_id
                    AND a.estado IN ('0')
                    GROUP BY a.usuario_id, d.nombre;";
        }

        //colocarle el filtro de la fecha de hoy
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la consulta de los cierres";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var = $resulta->GetRowAssoc($ToUpper = false);
        return $var;
    }

    function UltimoCuadre($fecha, $TipoCuenta) {
        $fe = $fecha[fecha];
        list($dbconn) = GetDBconn();
        if ($TipoCuenta == '01' OR $TipoCuenta == '02') {
            $query = "SELECT DISTINCT a.usuario_id, e.nombre
                                FROM recibos_caja_cierre a, cajas b, recibos_caja c, cajas_usuarios d, system_usuarios e
                                WHERE a.fecha_registro='$fe'
                                AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                AND a.cierre_caja_id is not null
                                AND a.cierre_caja_id NOT IN( SELECT b.cierre_caja_id FROM cierre_de_caja a,cierre_de_caja_detalle b
                                        WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                                AND c.caja_id=b.caja_id
                                AND c.caja_id=d.caja_id
                                AND c.usuario_id=d.usuario_id
                                AND a.cierre_caja_id=c.cierre_caja_id
                                AND a.usuario_id=e.usuario_id
                                AND c.estado IN ('0');";
        } else
        if ($TipoCuenta == '03' OR $TipoCuenta == '08') {
            $query = "SELECT a.usuario_id, c.nombre
                                FROM fac_facturas_contado a, cajas_rapidas b,
                                    system_usuarios c, recibos_caja_cierre d
                                WHERE a.cierre_caja_id=d.cierre_caja_id
                                AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                AND d.fecha_registro='$fe'
                                AND b.cuenta_tipo_id='$TipoCuenta'
                                AND (a.cierre_caja_id is not null
                                        AND a.cierre_caja_id NOT IN(
                                                        SELECT b.cierre_caja_id
                                                        FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                        WHERE a.cierre_de_caja_id=b.cierre_de_caja_id))
                                        AND a.caja_id=b.caja_id
                                AND a.usuario_id=c.usuario_id;";
        } else
        if ($TipoCuenta == '04') {
            $query = "SELECT a.usuario_id, c.nombre
                                FROM fac_facturas_contado a, cajas b,
                                system_usuarios c
                                WHERE a.fecha_registro='$fe'
                                AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                AND (a.cierre_caja_id is not null
                                        AND a.cierre_caja_id NOT IN(
                                                        SELECT b.cierre_caja_id
                                                        FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                        WHERE a.cierre_de_caja_id=b.cierre_de_caja_id))
                                        AND a.caja_id=b.caja_id
                                AND a.usuario_id=c.usuario_id;";
        } else
        if ($TipoCuenta == '05') {
            $query = "SELECT a.usuario_id, c.nombre
                                FROM fac_facturas_contado a, cajas_rapidas b,
                                system_usuarios c
                                WHERE
                                a.fecha_registro='$fe'
                                AND (a.cierre_caja_id is not null
                                        AND a.cierre_caja_id NOT IN(
                                                        SELECT b.cierre_caja_id
                                                        FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                        WHERE a.cierre_de_caja_id=b.cierre_de_caja_id))
                                        AND a.caja_id=b.caja_id;
                                AND a.usuario_id=c.usuario_id;";
        } elseif ($TipoCuenta == '06') {
            $query = "SELECT a.usuario_id, c.nombre
                                FROM recibos_caja a, cajas b,
                                system_usuarios c, recibos_caja_cierre d
                                WHERE d.fecha_registro='$fe'
                                AND (a.cierre_caja_id is not null
                                        AND a.cierre_caja_id NOT IN(
                                                        SELECT b.cierre_caja_id
                                                        FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                        WHERE a.cierre_de_caja_id=b.cierre_de_caja_id))
                                AND a.caja_id=b.caja_id
                                AND a.usuario_id=c.usuario_id
                                AND a.cierre_caja_id=d.cierre_caja_id
                                AND a.estado IN ('0');";
        }
        //colocarle el filtro de la fecha de hoy
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la consulta de los cierres";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var = $resulta->GetRowAssoc($ToUpper = false);
        return $var;
    }

    /* funcion que liquida las citas inasistidas */

    function liquidarCargo() {

        if (empty($_REQUEST['op'])) {
            $this->frmError["MensajeError"] = "ESCOGA UNA O MAS CITAS POR FAVOR.";
            $this->FormaOrdenesServicio($_SESSION['CAJA']['AUX']['tipo_id_paciente'], $_SESSION['CAJA']['AUX']['paciente_id'], $_SESSION['CAJA']['AUX']['plan_id']);
            return true;
        }

        IncludeLib("tarifario_cargos");
        IncludeLib("funciones_facturacion");
        $op = $_REQUEST['op'];
        $j = 0;
        $total_cargo = $total_paciente = $total_empresa = 0;
        $cargo_liq = array(); //arreglo que contiene los cargos y demas datos para liquidarlos.
        //$Arr_Descripcion[]=array();//arreglo para guardar la descripcion y los servicios.
        $cargo_arr = array();
        foreach ($op as $index => $codigo) {
            $valores = explode(",", $codigo);
            $this->QuitarItemsArreglo($valores[1], $valores[0]);
            $datos = $this->DatosOs($valores[1]);
            for ($i = 0; $i < sizeof($datos); $i++) {
                $dat[$j]['cargo'] = $datos[$i]['cargo'];
                $dat[$j]['tarifario_id'] = $datos[$i]['tarifario_id'];
                $dat[$j][descripcion] = $datos[$i]['descripcion'];
                $dat[$j][numero_orden_id] = $datos[$i]['numero_orden_id'];
                $dat[$j][os_maestro_cargos_id] = $datos[$i]['os_maestro_cargos_id'];
                //$Arr_Descripcion[$j]=array('des_cargo'=>$valores[6],'servicio'=>$valores[7],'des_servicio'=>$valores[8],'numero_orden_id'=>$valores[0],'cargo'=>$valores[1]);
                //$cargo_liq[]=array('tarifario_id'=>$datos[$i]['tarifario_id'],'cargo'=>$datos[$i]['cargo'],'cantidad'=>$datos[$i]['cantidad'],'autorizacion_int'=>$datos[$i]['autorizacion_int'],'autorizacion_ext'=>$datos[$i]['autorizacion_ext']);
                $cargo_liq[] = array('plan_id' => $datos[$i]['plan_id'], 'cargo_cita' => $datos[$i]['cargo_cups']);
                $_SESSION['CAJA']['AUX']['datos'][] = $dat[$j];
                $j++;
            }
        }
        foreach ($cargo_liq as $a => $v) {
            $_SESSION['CAJA']['INASISTENCIAS'][] = $v;
        }


        $emp = BuscarEmpleadorOrden($valores[1]);
        $cargo_fact = LiquidarCargosCuentaVirtual($_SESSION['CAJA']['ARRAY_PAGO'], $_SESSION['CAJA']['INASISTENCIAS'], array(), array(), $datos[0][plan_id], $datos[0][tipo_afiliado_id], $datos[0][rango], $datos[0][semanas_cotizacion], $datos[0][servicio], $_SESSION['CAJA']['AUX']['tipo_id_paciente'], $_SESSION['CAJA']['AUX']['paciente_id'], $emp[tipo_id_empleador], $emp[empleador_id]);

        unset($_SESSION['CAJA']['AUX']['liq']);
        $_SESSION['CAJA']['AUX']['liq'] = $cargo_fact;

        unset($_SESSION['CAJA']['ARRAY_PAGO']);
        $k = 0;
        foreach ($cargo_fact[cargos] as $w => $v) {
            $_SESSION['CAJA']['ARRAY_PAGO'][] = array('tarifario_id' => $v['tarifario_id'], 'descripcion' => $v[descripcion], 'os_maestro_cargos_id' => $v['os_maestro_cargos_id'], 'numero_orden_id' => $_SESSION['CAJA']['AUX']['datos'][$k]['numero_orden_id'], 'cargo' => $v['cargo'], 'des_servicio' => $Arr_Descripcion[$k][des_servicio], 'cantidad' => $v['cantidad'], 'valor_cargo' => $v[valor_cargo], 'valor_no_cubierto' => $v[valor_no_cubierto], 'autorizacion_int' => $v['autorizacion_int'], 'autorizacion_ext' => $v['autorizacion_ext']);
            $k++;
        }
        unset($_SESSION['CAJA']['OTRAVEZ']);
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
            if (!$this->FormaOrdenesServicio($_SESSION['CAJA']['AUX']['tipo_id_paciente'], $_SESSION['CAJA']['AUX']['paciente_id'], $_SESSION['CAJA']['AUX']['plan_id'])) {
                return false;
            }
            return true;
        }
    }

    /* cada vez que cumplimos citas inasistidas debemos quitar la linea del arreglo
      $_SESSION['CAJA']['ARRAY_PAGO'], para que no aparezcan en pantalla...
     */

    function QuitarItemsArreglo($orden, $agenda_id) {
        //$i=0;
        foreach ($_SESSION['ARREGLO_CITAS_INCUMPLIDAS'] as $dato => $value) {

            if ($value[numero_orden_id] == $orden) {
                unset($_SESSION['ARREGLO_CITAS_INCUMPLIDAS'][$dato]);
                $_SESSION['ARR_UPDATE_AGENDA'][] = $value[agenda_cita_asignada_id];
            }
            //$i++;
        }
        if (sizeof($_SESSION['ARREGLO_CITAS_INCUMPLIDAS']) < 1) {
            $_SESSION['SW_ARR_CITA'] = 1;
        }
        return true;
    }

    /* realizamos la busqueda del usuario que tiene permisos para autorizar a
      una persona un determinado descuento.....
     */

    function AutorizadorDescuento() {
        list($dbconn) = GetDBconn();

        $query = "SELECT COUNT(*) FROM rc_autorizacion_descuentos a,system_usuarios b
              WHERE  a.usuario_id=b.usuario_id
              AND b.activo=1 AND a.usuario_id=" . UserGetUID() . "$a�de;";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer el autorizador de la tabla rc_autorizacion_descuentos ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $result->fields[0];
    }

    /* realizamos la insercion del descuento
     */

    function InsertarDescuento() {
        $cutilidad = $_SESSION['CAJA']['CENTROUTILIDAD'];
        $empresa = $_SESSION['CAJA']['EMPRESA'];
        $Departamento = $_REQUEST['Departamento'];
        $ValorNo = $_REQUEST['ValorNo'];
        $ValorPac = $_REQUEST['ValorPac'];
        $Precio = $_REQUEST['Precio'];
        $Cargo = $_REQUEST['Cargo'];
        $ValEmpresa = $_REQUEST['ValorEmp'];
        $TarifarioId = $_REQUEST['TarifarioId'];
        $GrupoTarifario = $_REQUEST['GrupoTarifario'];
        $SubGrupoTarifario = $_REQUEST['SubGrupoTarifario'];
        $Gravamen = $_REQUEST['Gravamen'];
        $Cantidad = $_REQUEST['Cantidad'];
        $Cuenta = $_REQUEST['Cuenta'];
        $spy = $_REQUEST['spy'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Nivel = $_REQUEST['Nivel'];
        $PlanId = $_REQUEST['PlanId'];
        $Pieza = $_REQUEST['Pieza'];
        $Cama = $_REQUEST['Cama'];
        $Valor = $_REQUEST['descuento'];

        $Ingreso = $_REQUEST['Ingreso'];
        if ($_REQUEST['conteo_user'] < 1) {
            $passwd = $_REQUEST['pass'];
            $usuario = $_REQUEST['usuario'];
            $res_usuario = UserValidarUsuario($usuario, $passwd);

            if (!$res_usuario) {
                $this->frmError["MensajeError"] = "AUTORIZADOR NO VALIDO O NO TIENE PERMISOS";
                $this->CapturaHospitalizacion('6', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor);
                return true;
            }
        }



        if ($_REQUEST['descuento'] == '') {
            $this->frmError["descuento"] = 1;
            $this->frmError["MensajeError"] = "FALTAN DATOS OBLIGATORIOS";
            $this->CapturaHospitalizacion('6', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor);
            return true;
        }

        if (!is_numeric($_REQUEST['descuento'])) {
            $this->frmError["descuento"] = 1;
            $this->frmError["MensajeError"] = "DIGITE NUMEROS POR FAVOR";
            $this->CapturaHospitalizacion('6', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor);
            return true;
        }

        //Este array_descuento es para mostrar los descuentos del paciente..
        $_SESSION['CAJA']['ARRAY_DESCUENTO'] = array('tarifario_id' => 'SYS', 'cargo' => 'DESCUENTO', 'valor_cargo' => $_REQUEST['descuento']);


        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                return false;
            }
            return true;
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            if (!$this->CajaConsultaExterna($TipoId, $PacienteId, $_SESSION['CAJA']['PASO'], $PlanId)) {
                return false;
            }
            return true;
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
            if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                return false;
            }
            return true;
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            if (!$this->CajaConceptos($Cajaid)) {
                return false;
            }
            return true;
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
            if (!$this->FormaOrdenesServicio($_SESSION['CAJA']['AUX']['tipo_id_paciente'], $_SESSION['CAJA']['AUX']['paciente_id'], $_SESSION['CAJA']['AUX']['plan_id'])) {
                return false;
            }
            return true;
        }
    }

    /**
     * La funcion  InsertarHospitalizacion se encarga de obtener los datos para la creacion
     * de un recibo de caja,(para HOSPITALIZACION), siguiendo estos pasos:
     *
     * Primero inserta en la tabla recibos_caja el nuevo registro de caja
     *
     * Segundo insertamos en la tabla  rc_detalle_hosp nuestras llaves primarias
     * de la tabla recibos_caja, con la nueva insercion(con el nuevo registro)
     *
     * tercero  actualizamos la tabla tmp_cheques_mov el numero de recibo de caja,estado,prefijo
     * de las inserciones anteriores, esto con el fin de migrar los datos correctamente a cheques_mov
     *
     * cuarto insertamos en la tabla cheques_mov  los datos que estan en la tabla tmp_cheques_mov.
     *
     * quinto  actualizamos la tabla tmp_tarjetas_mov_credito el numero de recibo de caja,
     * estado,prefijo de las inserciones anteriores, esto con el fin de migrar los datos
     * correctamente a tarjetas_mov_credito
     *
     * sexto insertamos en la tabla tarjetas_mov_credito  los datos de la tabla
     * tmp_tarjetas_mov_credito.
     *
     * septimo  actualizamos la tabla tmp_tarjetas_mov_debito el numero de recibo de caja,
     * estado,prefijo de las inserciones anteriores, esto con el fin de migrar los datos
     * correctamente a tarjetas_mov_debito
     *
     * octavo insertamos en la tabla tarjetas_mov_debito   los datos de la tabla
     * tmp_tarjetas_mov_debito.
     *
     * noveno borramos las tablas temporales tmp_tarjetas_mov_credito,
     * tmp_tarjetas_mov_debito,tmp_cheques_mov.
     *
     * @access public
     * @return boolean
     */
    function InsertarHospitalizacion() {
        $Cajaid = $_SESSION['CAJA']['CAJAID'];
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
        $TipoCuenta = $_SESSION['CAJA']['TIPOCUENTA'];
        $usuario = UserGetUID();
        $cutilidad = $_SESSION['CAJA']['CENTROUTILIDAD'];
        $empresa = $_SESSION['CAJA']['EMPRESA'];
        $Tiponumeracion = $_SESSION['CAJA']['TIPONUMERACION'];

        IncludeLib('funciones_admision');
        //$recibo=$this->recibo;///numero caja
        $cheque = $_REQUEST['cheque'];
        $efectivo = $_REQUEST['efectivo'];
        $tarjetad = $_REQUEST['tarjetad'];
        $tarjetac = $_REQUEST['tarjetac'];
        $FechaHoy = date("Y/m/d H:i:s");
        /* OJO AQUI */
        $estado = '0';
        //$prefijo='xyz';
        //$tipoRecibo='01';
        $Ttarjeta = $tarjetac + $tarjetad;
        $FechaC = date("Y/m/d H:i:s"); //el ingde la caja es de hoy....

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            //$datos=$this->CallMetodoExterno('app','Triage','user','BuscarPlanes',$argumentos=array('PlanId'=>$PlanId,'Ingreso'=>$Ingreso));
            $datos = BuscarPlanes($PlanId, $Ingreso);
//***********************************************************************
// CAMBIADO por Alex G. 2007-01-04
// EL TERCERO DE UN RECIBO DE CAJA HOSPITALARIO ES EL TERCERO NO EL PLAN
//           $TipoIdTercero=$datos[tipo_id_tercero];
//           $TerceroId=$datos[tercero_id];
            $TipoIdTercero = $TipoId;
            $TerceroId = $PacienteId;
//***********************************************************************
            $TotalAbono = $Ttarjeta + $efectivo + $cheque + $_SESSION['CAJA']['BONO'];
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
            $datos = $this->ConsultaCabeceraPvta($Cuenta);
            for ($i = 0; $i < sizeof($datos); $i++) {
                $TipoIdTercero = $datos[$i][tipo_id_tercero];
                $TerceroId = $datos[$i][tercero_id];
                $TotalAbono = $Ttarjeta + $efectivo + $cheque + $_SESSION['CAJA']['BONO'];
            }
        }

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            $TipoIdTercero = $_SESSION['CAJA']['TIPO_ID_TERCERO'];
            $TerceroId = $_SESSION['CAJA']['TERCEROID'];
            $TotalAbono = $_REQUEST['Apagar'];
        }
        list($dbconn) = GetDBconn();
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            $TipoIdTercero = $_SESSION['CAJA']['TIPO_ID_TERCERO'];
            $TerceroId = $_SESSION['CAJA']['TERCEROID'];
            $TotalAbono = $_REQUEST['Apagar'];
        }

        $numerodoc = AsignarNumeroDocumento($Tiponumeracion);
        $recibo = $numerodoc['numero'];
        $prefijo = $numerodoc['prefijo'];



        //nuevo arreglo 12/9/2004, agregando el caso del tercero
        $datos_ter = $this->RetornarDatosoCrearTercero($TerceroId, $TipoIdTercero, &$dbconn);
        $TipoIdTercero = $datos_ter[TipoTercero];
        $TerceroId = $datos_ter[Tercero];
        /*
          parte nueva de la insercion de caja, aca en recibos_caja insertamos documento_id,cuenta_tipo
          echa 2005-06-23
         */

        $query = "SELECT   cuenta_tipo_id,
                                         tipo_numeracion,tipo_numeracion_devoluciones
                           FROM      cajas
                           WHERE    caja_id='$Cajaid'
                           AND        empresa_id='$empresa'
                           AND        centro_utilidad='$cutilidad'";
        $result = $dbconn->Execute($query); //  GuardarNumeroDocumento(true);
        $dbconn->ErrorNo();
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer datos de cajas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            GuardarNumeroDocumento(false);
            return false;
        }
        $documento_id = $result->fields[1]; //trae documento_id de cajas(osea el campo tipo_numeracion)
        $cuenta_tipo = $result->fields[0];


        /* insercion de recibos_caja */
        $sqls = "INSERT into recibos_caja
                           (
                            empresa_id,
                            recibo_caja,
                            centro_utilidad,
                            prefijo,
                            fecha_ingcaja,
                            total_abono,
                            total_efectivo,
                            total_cheques,
                            total_tarjetas,
                            tipo_id_tercero,
                            tercero_id,
                            estado,
                            fecha_registro,
                            usuario_id,
                            caja_id,
                            total_bonos,
                            documento_id,
                            cuenta_tipo_id
                          )
               VALUES(
                            '$empresa',
                            '$recibo',
                            '$cutilidad',
                            '$prefijo',
                            '$FechaC',
                            $TotalAbono,
                            $efectivo,
                            $cheque,
                            $Ttarjeta,
                            '$TipoIdTercero',
                            '$TerceroId',
                            $estado,
                            '$FechaHoy',
                            $usuario,
                            '$Cajaid',
                            " . $_SESSION['CAJA']['BONO'] . ",
                            '$documento_id',
                            '$cuenta_tipo'
                            );";
        // $dbconn->BeginTrans();  //Inicia la transaccion
        $result = $dbconn->Execute($sqls); //  GuardarNumeroDocumento(true);
        $dbconn->ErrorNo();
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            GuardarNumeroDocumento(false);
            return false;
        }

        //ESTA ES UNA COMPARACION CON EL CAJA_ID DE LA TABLA TIPO_CAJA, DONDE ESTA HOSPITALIZACION
        //PUNTO VENTA,CONCEPTOS,CONSULTA EXTERNA, OJO QUE ESTO PUEDE CAMBIAR....OJO
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {//hospitalizacion.
            $consulta = "INSERT INTO rc_detalle_hosp
                                            (
                                              empresa_id,
                                              centro_utilidad,
                                              recibo_caja,
                                              prefijo,
                                              numerodecuenta
                                           )
                          VALUES     ( 
                                            '$empresa',
                                            '$cutilidad',
                                            '$recibo',
                                            '$prefijo',
                                            '$Cuenta'
                                            );";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '02') { //pto venta.
            $consulta = "INSERT INTO rc_detalle_pto_vta
                                            (
                                              empresa_id,
                                              centro_utilidad,
                                              recibo_caja,
                                              prefijo,
                                              cuenta_pv
                                            )
                           VALUES     (
                                              '$empresa',
                                              '$cutilidad',
                                              '$recibo',
                                              '$prefijo',
                                              '$Cuenta'
                                             );";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '03') { //conceptos.
            if (!$Cuenta) {
                $Cuenta = $_SESSION['CAJA']['CUENTA'];
            }
            $bucon = "UPDATE tmp_detalle_conceptos SET
                recibo_caja='$recibo', prefijo='$prefijo'
                where cuenta='$Cuenta';";
            $res = $dbconn->Execute($bucon);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                ///  GuardarNumeroDocumento(false);
                return false;
            }
            $consulta = "INSERT INTO rc_detalle_conceptos
                                          (
                                            empresa_id,
                                            centro_utilidad,
                                            recibo_caja,
                                            prefijo,
                                            concepto_id,
                                            detalle,
                                            grupo_concepto,
                                            valor
                                          )
                                          SELECT empresa_id,
                                                      centro_utilidad,
                                                      recibo_caja,
                                                      prefijo,
                                                      concepto_id,
                                                      detalle,
                                                      grupo_concepto,
                                                      valor 
                                           FROM   tmp_detalle_conceptos 
                                           WHERE cuenta='$Cuenta';";
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {//Consulta externa.
            if (!$Cuenta) {
                $Cuenta = $_SESSION['CAJA']['CUENTA'];
            }
            $bucon = "UPDATE tmp_detalle_cargos_ambulatorios SET
                recibo_caja='$recibo', prefijo='$prefijo'
                where cuenta='$Cuenta';";
            $res = $dbconn->Execute($bucon);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al actualizar tmp_detalle_cargos_ambulatorios ";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                ///  GuardarNumeroDocumento(false);
                return false;
            }
            $consulta = "INSERT INTO rc_detalle_cargos_ambulatorios
                                            (
                                              rc_detalle_cargo_amb_id,
                                              empresa_id,
                                              centro_utilidad,
                                              recibo_caja,
                                              prefijo,
                                              descuento,
                                              gravamen_valor_cubierto,
                                              valor_cargo,
                                              valor_cuota_paciente,
                                              valor_nocubierto,
                                              valor_cubierto,
                                               gravamen_valor_nocubierto
                                            )
                                              SELECT rc_detalle_cargo_amb_id,
                                                          empresa_id,
                                                          centro_utilidad,
                                                          recibo_caja,
                                                          prefijo,
                                                          descuento,
                                                          gravamen_valor_cubierto,
                                                          valor_cargo,
                                                          valor_cuota_paciente,
                                                          valor_nocubierto,
                                                          valor_cubierto,
                                                          gravamen_valor_nocubierto
                                              FROM   tmp_detalle_cargos_ambulatorios 
                                              WHERE cuenta='$Cuenta';";
        }
        $resulte = $dbconn->Execute($consulta);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al insertar rc_detalle_cargos_ambulatorios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            GuardarNumeroDocumento(false);
            return false;
        }

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            if (!$Cuenta) {
                $Cuenta = $_SESSION['CAJA']['CUENTA'];
            }
            $sql = "INSERT INTO servicios_ambulatorios_autorizados
                                        (
                                           paciente_id,
                                          tipo_id_paciente,
                                          plan_id,
                                          rango,
                                          tipo_id_afiliado,
                                          estado,
                                          tipo_servicio_amb_id,
                                          fecha,
                                          fecha_vencimiento,
                                          rc_detalle_cargo_amb_id,
                                          cargo,
                                          tarifario_id
                                        )
                                        SELECT  '" . $_SESSION['CAJA']['PACIENTEID'] . "',
                                                     '" . $_SESSION['CAJA']['TIPO_ID_PACIENTE'] . "',
                                                      plan_id,
                                                      rango,
                                                      tipo_id_afiliado,
                                                      estado,
                                                      tipo_servicio,
                                                      fecha,
                                                      fecha,
                                                      rc_detalle_cargo_amb_id,
                                                      cargo,
                                                      tarifario_id
                                        FROM     tmp_detalle_cargos_ambulatorios
                                        WHERE   cuenta='$Cuenta'";
            $resul = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Insertar en la tabla servicios_ambulatorios_autorizados";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumento(false);
                return false;
            }

            $query = " SELECT  a.agenda_cita_asignada_id,
                                           a.cantidad,
                                           b.servicio_amb_id from
                                           tmp_detalle_cargos_ambulatorios a,
                                           servicios_ambulatorios_autorizados b
                               WHERE a.rc_detalle_cargo_amb_id=b.rc_detalle_cargo_amb_id
                      and a.cuenta='$Cuenta' ";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al buscar en la tabla temporal ";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumento(false);
                return false;
            }

            while (!$resulta->EOF) {
                $cita = $resulta->fields[0];
                $cantidad = $resulta->fields[1];
                $servicios = $resulta->fields[2];
                if (!empty($cita)) {
                    //tener en cuenta esa parte ok....
                    $cita+=1;
                    for ($i = 0; $i < $cantidad or $cantidad === '0'; $i++) {
                        $query = "INSERT INTO agenda_citas_pagos
                                                                (
                                                                  agenda_cita_asignada_id,
                                                                  servicio_amb_id
                                                                )
                                                          VALUES
                                                          (
                                                          " . ($cita-=1) . ",
                                                          $servicios
                                                          )
                                            ";

                        if ($cantidad === '0') {
                            $cantidad = 'x';
                        }

                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al insertar en la tabla agenda_citas_pagos ";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            GuardarNumeroDocumento(false);
                            return false;
                        }
                    }
                }

                $resulta->MoveNext();
            }

            if (sizeof($_SESSION['CAJA']['ORDENPAGO']) == 1) {
                $orden = $_SESSION['CAJA']['ORDENPAGO'][0];
                $query = "DELETE FROM  caja_ordenes_pago where orden_pago=$orden";

                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al borrar en la tabla caja_ordenes_pago ";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    GuardarNumeroDocumento(false);
                    return false;
                }
            } else {


                for ($j = 1; $j <= sizeof($_SESSION['CAJA']['ORDENPAGO']); $j++) {
                    $orden = $_SESSION['CAJA']['ORDENPAGO'][$j];
                    $query = "DELETE FROM  caja_ordenes_pago where orden_pago=$orden";

                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al borrar en la tabla caja_ordenes_pago ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        GuardarNumeroDocumento(false);
                        return false;
                    }
                }
            }
            unset($_SESSION['CAJA']['ORDENPAGO']);
        }

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            if (!$Cuenta) {
                $Cuenta = $_SESSION['CAJA']['CUENTA'];
            }
            $sql = "delete from tmp_detalle_conceptos where cuenta='$Cuenta'";
            $resul = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumento(false);
                return false;
            }
        }


        if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            if (!$Cuenta) {
                $Cuenta = $_SESSION['CAJA']['CUENTA'];
            }
            $sql = "delete from tmp_detalle_cargos_ambulatorios where cuenta='$Cuenta'";
            $resul = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumento(false);
                return false;
            }
        }

        $fr = "SELECT COUNT(cheque_mov_id) FROM tmp_cheques_mov where numerodecuenta=$Cuenta;";
        $res = $dbconn->Execute($fr);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            //  GuardarNumeroDocumento(false);
            return false;
        }

        if ($res->fields[0] > 0) {
            $bucon = "  UPDATE  tmp_cheques_mov 
                           SET         recibo_caja='$recibo', 
                                          prefijo='$prefijo', 
                                          estado='$estado'
                           WHERE    numerodecuenta=$Cuenta;";

            $res = $dbconn->Execute($bucon);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                ///  GuardarNumeroDocumento(false);
                return false;
            }

            $cons1 = "INSERT INTO cheques_mov
                                            (
                                              cheque_mov_id,
                                              empresa_id,
                                              centro_utilidad,
                                              recibo_caja,
                                              prefijo,
                                              banco,
                                              cta_cte,
                                              cheque,
                                              girador,
                                              fecha_cheque,
                                              total,
                                              fecha,
                                              estado,
                                              usuario_id,
                                              fecha_registro,
                                              sw_postfechado
                                             )
                                              SELECT  cheque_mov_id,
                                                          empresa_id,
                                                          centro_utilidad,
                                                          recibo_caja,
                                                          prefijo,
                                                          banco,
                                                          cta_cte,
                                                          cheque,
                                                          girador,
                                                          fecha_cheque,
                                                          total,
                                                          fecha,
                                                          estado,
                                                          usuario_id,
                                                          fecha_registro,
                                                     CASE WHEN fecha_cheque > DATE(now()) THEN '1' ELSE '0' END AS sw_postfechado 
                                              FROM      tmp_cheques_mov 
                                              WHERE    numerodecuenta=$Cuenta;";
            $re = $dbconn->Execute($cons1);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                //  GuardarNumeroDocumento(false);
                return false;
            }

            $cons2 = "INSERT INTO  confirmacion_che
                                             (
                                              consecutivo,
                                              cheque_mov_id,
                                              entidad_confirma,
                                              funcionario_confirma,
                                              numero_confirmacion,
                                              fecha,
                                              usuario_id
                                             )
                                              SELECT
                                                        consecutivo,
                                                        cheque_mov_id,
                                                        entidad_confirma,
                                                        funcionario_confirma,
                                                        numero_confirmacion,
                                                        fecha,
                                                        usuario_id
                                              FROM tmp_confirmacion_che 
                                              WHERE numerodecuenta=$Cuenta";
            $re = $dbconn->Execute($cons2);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumento(false);
                return false;
            }
        }

        $fr = "SELECT COUNT(tarjeta_mov_id) FROM tmp_tarjetas_mov_credito where numerodecuenta=$Cuenta;";
        $res = $dbconn->Execute($fr);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            GuardarNumeroDocumento(false);
            return false;
        }

        if ($res->fields[0] > 0) {
            $buscon = "UPDATE tmp_tarjetas_mov_credito SET  recibo_caja='$recibo', prefijo='$prefijo'
                      where numerodecuenta=$Cuenta;";
            $re = $dbconn->Execute($buscon);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumento(false);
                return false;
            }

            $cons2 = "INSERT INTO tarjetas_mov_credito(
                                tarjeta_mov_id,
                                tarjeta,
                                empresa_id,
                                centro_utilidad,
                                recibo_caja,
                                prefijo,
                                fecha,
                                autorizacion,
                                socio,
                                fecha_expira,
                                autorizado_por,
                                total,
                                usuario_id,
                                fecha_registro,
                                tarjeta_numero)
                                SELECT
                                tarjeta_mov_id,
                                tarjeta,
                                empresa_id,
                                centro_utilidad,
                                recibo_caja,
                                prefijo,
                                fecha,
                                autorizacion,
                                socio,
                                fecha_expira,
                                autorizado_por,
                                total,
                                usuario_id,
                                fecha_registro,
                                tarjeta_numero FROM tmp_tarjetas_mov_credito where numerodecuenta=$Cuenta;";
            $re = $dbconn->Execute($cons2);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumento(false);
                return false;
            }
            $cons2 = "INSERT INTO  confirmacion_tar(
                                    consecutivo,
                                    tarjeta_mov_id,
                                    entidad_confirma,
                                    funcionario_confirma,
                                    numero_confirmacion,
                                    fecha,
                                    usuario_id
                                    )
                                    SELECT
                                    consecutivo,
                                    tarjeta_mov_id,
                                    entidad_confirma,
                                    funcionario_confirma,
                                    numero_confirmacion,
                                    fecha,
                                    usuario_id
                                    FROM tmp_confirmacion_tar where numerodecuenta=$Cuenta";
            $re = $dbconn->Execute($cons2);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumento(false);
                return false;
            }
        }

        $fr = "SELECT COUNT(tarjeta_mov_db_id) FROM tmp_tarjetas_mov_debito where numerodecuenta=$Cuenta;";
        $res = $dbconn->Execute($fr);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            GuardarNumeroDocumento(false);
            return false;
        }

        if ($res->fields[0] > 0) {
            $bu = "UPDATE tmp_tarjetas_mov_debito SET  recibo_caja='$recibo', prefijo='$prefijo'
                  where numerodecuenta=$Cuenta;";
            $re = $dbconn->Execute($bu);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumento(false);
                return false;
            }

            $cons3 = "INSERT INTO tarjetas_mov_debito(
                                empresa_id,
                                centro_utilidad,
                                recibo_caja,
                                prefijo,
                                autorizacion,
                                tarjeta,
                                total,
                                tarjeta_numero
                                )
                                SELECT
                                empresa_id,
                                centro_utilidad,
                                recibo_caja,
                                prefijo,
                                autorizacion,
                                tarjeta,
                                total,
                                tarjeta_numero
                                from tmp_tarjetas_mov_debito where numerodecuenta=$Cuenta;";
            $re = $dbconn->Execute($cons3);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumento(false);
                return false;
            }
        }


        GuardarNumeroDocumento(true);

        if (!empty($Cuenta)) {
            $qx = " DELETE FROM tmp_confirmacion_tar where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_confirmacion_che where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_tarjetas_mov_debito  where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_tarjetas_mov_credito  where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_cheques_mov where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_caja_bonos where numerodecuenta=$Cuenta;";
            $dbconn->Execute($qx);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al borrar las tablas temporales en el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        }

        /*  $qx=" DELETE FROM  tmp_confirmacion_tar where numerodecuenta=$Cuenta;
          DELETE FROM tmp_confirmacion_che where numerodecuenta=$Cuenta;
          DELETE FROM  tmp_tarjetas_mov_debito  where numerodecuenta=$Cuenta;
          DELETE FROM tmp_tarjetas_mov_credito  where numerodecuenta=$Cuenta;
          DELETE FROM tmp_cheques_mov where numerodecuenta=$Cuenta;
          ";
          $re = $dbconn->Execute($qx);
          if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          GuardarNumeroDocumento(false);
          return false;
          } */

        unset($_SESSION['CAJA']['TOTAL_EFECTIVO']); //se destruye la variable de session..
        unset($_SESSION['CAJA']['SUBTOTAL']);
        unset($_SESSION['CAJA']['SAL']);
        unset($_SESSION['CAJA']['BONO']);
        unset($_SESSION['CAJA']['OTRAVEZ']); //variable q coloca el valor por defecto q tiene q pagar a
        //en la celda de efectivo.
        //  unset($_SESSION['CENTROUTILIDAD']);
        //  unset($_SESSION['EMPRESA']);
        //  unset($_SESSION['TIPONUMERACION']);
        //  unset($_SESSION['TIPOCUENTA']);
        //LLAMADO A FUNCION QUE EJECUTA EL WS DE CREACION DE TERCEROS PARA FI
        //$result_ws = $this->CrearTerceroWS($TipoId, $PacienteId);

		IncludeLib('WSIntegracionParticularesFI');
        CrearTerceroWS($TipoId, $PacienteId, $PlanId);
        
        if ($result_ws['return']['descripcion'] != "") {
            //unset($_SESSION['msj_ws']);
            $_REQUEST['msj_ws'] = $result_ws['return']['descripcion'];
            $mensaje_ws = $_REQUEST['msj_ws'];
        }

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            $this->FormaCuentaConceptos();
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '02' || $_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            $this->FormaCuenta($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy, $Tiponumeracion, $mensaje_ws);
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            $this->FormaCuentaExterna();
        }

        return true;
    }

    /* FUNCION QUE GUARDA EL TERCERO CON EL WS DE FI */

    function CrearTerceroWS($tipo_id, $paciente_id) {
        list($dbconn) = GetDBconn();

        $sql_data = " ";
        $sql_data .= "  SELECT T.*, P.* 
                        FROM terceros T INNER JOIN pacientes P 
                        ON (T.tercero_id = P.paciente_id AND T.tipo_id_tercero = P.tipo_id_paciente) 
                        WHERE P.paciente_id = '" . $paciente_id . "' 
                        AND   P.tipo_id_paciente = '" . $tipo_id . "' ";

        if (!$rst = $dbconn->Execute($sql_data))
            return false;

        //$cod_pais = array();
        while (!$rst->EOF) {
            $data_ws = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();



        if ($data_ws['tipo_estado_civil_id'] == '') {
            $data_ws['tipo_estado_civil_id'] = 'S';
        }
        if ($data_ws['nacionalidad'] == '') {
            $data_ws['nacionalidad'] = '1';
        }
        if (!is_numeric($data_ws['telefono'])) {
            $data_ws['telefono'] = '9999999';
        }
        if ($data_ws['sw_persona_juridica'] == '1') {
            $razon_social = "";
        } else {
            $razon_social = "RS";
        }
        if ($data_ws['direccion'] == '') {
            $data_ws['direccion'] = 'nn';
        }
        //CONSUME WS QUE INGRESA EL TERCERO EN FI
        require_once ('nusoap/lib/nusoap.php');

        $url = "http://10.0.0.223:8080/terceroDusoft-ejb/TerceroGeneralSet?wsdl";
        //$url = "http://10.0.0.10:8080/terceroDusoft-ejb/GetTerceroGeneral?wsdl";
        $soapclient = new nusoap_client($url, true);
        $function = 'crearTerceroGeneral';
        /* $params = array('coddepartamento'=>$datos[dpto],'codestadocivil'=>$datos[estadocivil],'codgenero'=>$datos[Sexo],
          'codigoempresa'=>'02','codmunicipio'=>$datos[mpio],'codnacionalidad'=>$cod_pais[codigo],
          'codpais'=>$cod_pais[codigo],'codtipocontacto'=>null,'codtipodireccion'=>'SR',
          'codtipodocumento'=>$datos[tipo_id_paciente],'codtipoemail'=>null,'codtipolinea'=>'FIJO',
          'codtiponaturaleza'=>'2','codtiporedsocial'=>null,'codtiporganizacion'=>'1','codtipotelefono'=>'1',
          'contrasena'=>'miclave','digitoverificacion'=>'2','direccion'=>$datos[Direccion],'email'=>$datos[email],
          'emailcontacto'=>null,'fechacreacion'=>date('d-m-Y H:i:s'),'fechaexpedicion'=>null,
          'fechaexpiracion'=>null,'fechanacimiento'=>$datos[fechanacimiento],'identificacion'=>$datos[paciente_id],
          'indicativodepto'=>null,'indicativonacional'=>null,'nombrecomercial'=>'Nombre Comercial','nombrecontacto'=>null,
          'numerotelefonico'=>$datos[Telefono],'observacioncontacto'=>$datos[Observaciones],'observaciontercero'=>null,
          'primerapellido'=>$datos[primerapellido],'primernombre'=>$datos[primernombre],'razonsocial'=>null,
          'redsocial'=>null,'segundoapellido'=>$datos[segundoapellido],'segundonombre'=>$datos[segundonombre],
          'telefonocontacto'=>null
          ); */
        $params = array('arg0' => array('coddepartamento' => $data_ws['tipo_dpto_id'],
                'codestadocivil' => $data_ws['tipo_estado_civil_id'],
                'codgenero' => $data_ws['sexo_id'],
                //'codigoempresa'=>$_SESSION['CAJA']['EMPRESA'],
                'codigoempresa' => 'DUMIAN',
                'codmunicipio' => $data_ws['tipo_mpio_id'],
                'codnacionalidad' => $data_ws['nacionalidad'],
                //'codpais'=>$data_ws['tipo_pais_id'],
                'codpais' => '169',
                'codtipocontacto' => '1', //DEFAULT
                'codtipodireccion' => '1', // DEFAULT
                'codtipodocumento' => $data_ws['tipo_id_paciente'],
                'codtipoemail' => '1', //DEFAULT
                'codtipolinea' => '1', //DEFAULT
                'codtiponaturaleza' => $data_ws['sw_persona_juridica'],
                'codtiporedsocial' => '', //DEFAULT
                'codtiporganizacion' => '1', //DEFAULT
                'codtipotelefono' => '1', //DEFAULT
                'contrasena' => 'miclave', //DEFAULT
                'digitoverificacion' => '2', //DEFAULT
                'direccion' => $data_ws['direccion'],
                'email' => $data_ws['email'],
                'emailcontacto' => '', //DEFAULT
                'fechacreacion' => date('d-m-Y H:i:s'),
                'fechaexpedicion' => '', //DEFAULT
                'fechaexpiracion' => '', //DEFAULT
                'fechanacimiento' => $data_ws['fecha_nacimiento'],
                'identificacion' => $data_ws['paciente_id'],
                'indicativodepto' => '', //DEFAULT
                'indicativonacional' => '', //DEFAULT
                'nombrecomercial' => $data_ws['nombre_tercero'],
                'nombrecontacto' => '', //DEFAULT
                'numerotelefonico' => $data_ws['telefono'],
                'observacioncontacto' => $data_ws['observaciones'],
                'observaciontercero' => '', //DEFAULT
                'primerapellido' => $data_ws['primer_apellido'],
                'primernombre' => $data_ws['primer_nombre'],
                'razonsocial' => $razon_social, //DEFAULT
                'redsocial' => '', //DEFAULT
                'segundoapellido' => $data_ws['segundo_apellido'],
                'segundonombre' => $data_ws['segundo_nombre'],
                'telefonocontacto' => '' //DEFAULT
                ));

        $result = $soapclient->call($function, $params);

       
        return $result;
    }

    function BorrarTemporales() {
        list($dbconn) = GetDBconn();
        $Cuenta = $_REQUEST['Cuenta'];
        if (!$Cuenta) {
            $Cuenta = $_SESSION['CAJA']['CUENTA'];
        }
        if (!empty($Cuenta)) {
            $qx = " DELETE FROM tmp_confirmacion_tar where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_confirmacion_che where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_tarjetas_mov_debito  where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_tarjetas_mov_credito  where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_cheques_mov where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_caja_bonos where numerodecuenta=$Cuenta;";
            $dbconn->Execute($qx);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al borrar las tablas temporales en el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        }

        unset($_SESSION['CAJA']['TOTAL_EFECTIVO']);  //pendfiente
        unset($_SESSION['CAJA']['BONO']);
        unset($_SESSION['CAJA']['SUBTOTAL']);
        unset($_SESSION['CAJA']['SAL']);
        unset($_SESSION['CAJA']['OTRAVEZ']);
        //variable q coloca el valor por defecto q tiene q pagar a
        //la celda de efectivo.....
        //cambio dar
        if (!empty($_SESSION['CAJA']['RETORNO'])) {
            $Contenedor = $_SESSION['CAJA']['RETORNO']['contenedor'];
            $Modulo = $_SESSION['CAJA']['RETORNO']['modulo'];
            $Tipo = $_SESSION['CAJA']['RETORNO']['tipo'];
            $Metodo = $_SESSION['CAJA']['RETORNO']['metodo'];
            $argu = $_SESSION['CAJA']['RETORNO']['argumentos'];

            $this->ReturnMetodoExterno($Contenedor, $Modulo, $Tipo, $Metodo, $argu);
            return true;
        }
        //fin cambio dar

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
            $sql = "DELETE FROM tmp_detalle_conceptos
                                    WHERE tipo_id_tercero='" . $_SESSION['CAJA']['TIPO_ID_TERCERO'] . "'
                                    AND tercero_id='" . $_SESSION['CAJA']['TERCEROID'] . "'
                                    AND empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "';";
            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error DELETE FROM tmp_detalle_conceptos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            if (!$Cuenta) {
                $Cuenta = $_SESSION['CAJA']['CUENTA'];
            }
            $sql = "delete from tmp_detalle_cargos_ambulatorios where cuenta='$Cuenta'";
            $resul = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        }
//$accion=ModuloGetURL('app','CajaGeneral','user','BuscarTercero',array('Caja'=>$_SESSION['CAJAID'],'Empresa'=>$_SESSION['EMPRESA'],'CentroUtilidad'=>$_SESSION['Cutilidad'],'Tiponumeracion'=>$Tiponumeracion,'TipoCuenta'=>$_SESSION['TIPOCUENTA']));
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03' OR $_SESSION['CAJA']['TIPOCUENTA'] == '08') {
            unset($_SESSION['CAJA']['TOTAL_EFECTIVO']);
            unset($_SESSION['CAJA']['SUBTOTAL']);
            unset($_SESSION['CAJA']['SAL']);
            unset($_SESSION['CAJA']['BONO']);
            $this->BuscarTercero();
            //$this->BusquedaTercer();
            return true;
        }
        if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
            unset($_SESSION['CAJA']['TOTAL_EFECTIVO']);
            unset($_SESSION['CAJA']['SUBTOTAL']);
            unset($_SESSION['CAJA']['SAL']);
            unset($_SESSION['CAJA']['BONO']);
            unset($_SESSION['CAJA']['ARRAY_PAGO']);

            //si es 2 se direcciona a caja rapida...
            if ($_SESSION['CAJA']['AUX']['RUTA_CAJA'] == 2) {

                $id = $_SESSION['CAJA']['AUX']['paciente_id'];
                $tipo = $_SESSION['CAJA']['AUX']['tipo_id_paciente'];
                $nom = $_SESSION['CAJA']['AUX']['nom'];
                $op = $_SESSION['CAJA']['AUX']['op'];
                $plan = $_SESSION['CAJA']['AUX']['plan_id'];
                unset($_SESSION['CAJA']['liq']);
                unset($_SESSION['CAJA']['datos']);
                unset($_SESSION['CAJA']['vector']);
                unset($_SESSION['CAJA']['AUX']['paciente_id']);
                unset($_SESSION['CAJA']['AUX']['tipo_id_paciente']);
                unset($_SESSION['CAJA']['AUX']['nom']);
                unset($_SESSION['CAJA']['AUX']['plan_id']);
                unset($_SESSION['CAJA']['EMPRESA']);
                unset($_SESSION['CAJA']['AUX']['CUENTA']);
                unset($_SESSION['CAJA']['AUX']['INGRESO']);
                unset($_SESSION['CAJAX']['TIPONUMERACION']['FACTURA']);
                unset($_SESSION['LABORATORIO']['TIPOFACTURA_CRE']);
                unset($_SESSION['LABORATORIO']['TIPOFACTURA']);
                unset($_SESSION['CAJAX']['TIPONUMERACION']['FACTURA_CRE']);
                unset($_SESSION['CAJA']['AUX']['serv']);
                unset($_SESSION['CAJA']['AUX']['auto']);
                //$this->FormaMetodoBuscarOrden();
                if (!empty($_SESSION['CONSULTAEXT']['RETORNO'])) {
                    $cont = $_SESSION['CONSULTAEXT']['RETORNO']['contenedor'];
                    $mod = $_SESSION['CONSULTAEXT']['RETORNO']['modulo'];
                    $tipo = $_SESSION['CONSULTAEXT']['RETORNO']['tipo'];
                    $metodo = $_SESSION['CONSULTAEXT']['RETORNO']['metodo'];
                    $this->ReturnMetodoExterno($cont, $mod, $tipo, $metodo, array());
                } else {
                    $this->ReturnMetodoExterno('app', 'Os_Atencion', 'user', 'BuscarCuentaActiva', array('id_tipo' => $tipo, 'nom' => $nom, 'id' => $id, 'op' => $op, 'plan_id' => $plan));
                }
                //BuscarCuentaActiva($id,$tipo,$nom,$op,$plan)
                return true;
            }
            //es por q es caja general.......
            elseif ($_SESSION['CAJA']['AUX']['RUTA_CAJA'] == 1) {

                $this->CajaOrdenes();
                return true;
            }
        }

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
            unset($_SESSION['CAJA']['TOTAL_EFECTIVO']);
            unset($_SESSION['CAJA']['ORDENPAGO']);
            unset($_SESSION['CAJA']['SUBTOTAL']);
            unset($_SESSION['CAJA']['SAL']);
            unset($_SESSION['CAJA']['BONO']);
            $this->FormaMetodoBuscar();
            return true;
        } elseif ($_SESSION['CAJA']['TIPOCUENTA'] == '02' || $_SESSION['CAJA']['TIPOCUENTA'] == '01') {
            unset($_SESSION['CAJA']['CUENTA']);
            $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'main', array('Caja' => $_SESSION['CAJA']['CAJAID'], 'Empresa' => $_SESSION['CAJA']['EMPRESA'], 'CentroUtilidad' => $_SESSION['CAJA']['CENTROUTILIDAD'], 'Tiponumeracion' => $Tiponumeracion, 'TipoCuenta' => $_SESSION['CAJA']['TIPOCUENTA'], 'CU' => $_SESSION['CAJA']['CU']));
            return true;
        }
    }

    function TraerOrdenConsultaExterna($Paciente_id, $Tipo_pac) {
        list($dbconn) = GetDBconn();
        $query = "select v.servicio,a.orden_pago,a.plan_id,a.rango,a. tarifario_id,a.cargo,a.tipo_id_afiliado,
            a.tipo_servicio, a.paciente_id, a.tipo_id_paciente,a.fecha,a.observaciones,
            a.agenda_cita_asignada_id, f.agenda_cita_id,
            b.primer_apellido||' '||b.segundo_apellido||' '||b.primer_nombre||' '||b.segundo_nombre as nombrep,
            c.descripcion,d.descripcion as tarifariodes,h.nombre ,n.descripcion as consultorio
            from caja_ordenes_pago a
            left join agenda_citas_asignadas as e on (a.agenda_cita_asignada_id=e.agenda_cita_asignada_id)
            left join agenda_citas as f on(e.agenda_cita_id=f.agenda_cita_id)
            left join  agenda_turnos as g on(f.agenda_turno_id=g.agenda_turno_id)
            left join profesionales as h on(g.tipo_id_profesional= h.tipo_id_tercero
              and g.profesional_id= h.tercero_id)
            left join consultorios as m on(m.consultorio=g.consultorio_id)
            left join tipos_consultorios as n on(n.tipo_consultorio=m.tipo_consultorio),
            pacientes b,tipos_servicios_ambulatorios c,tipos_cargos_ambulatorios v,
            tarifarios_detalle d
            where a.tipo_id_paciente='" . $Tipo_pac . "' and a.paciente_id='" . $Paciente_id . "'
            and a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id
            and a.tipo_servicio=c.tipo_servicio_amb_id
            and a.tarifario_id=d.tarifario_id and a.cargo=d.cargo
            and c.tipo_cargo_amb_id=v.tipo_cargo_amb_id
            and date(a.fecha) >= date(now()) order by a.fecha, f.agenda_cita_id";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i = 0;
        while (!$result->EOF) {
            $var[$i] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
            $i++;
        }
        $result->Close();
        return $var;
    }

    function TraerDetalleTmpCexterna() {
        // "esta es papa".$Caja;
//       if($CentroU)
//       { $CU="and a.centro_utilidad='$CentroU'"; }
        list($dbconn) = GetDBconn();
        $query = "select a.fecha,a.tarifario_id,a.cargo,b.descripcion,a.valor_cargo from
              tmp_detalle_cargos_ambulatorios a,tarifarios_detalle b
              where  a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "' and a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
              and a.cargo=b.cargo and  a.tarifario_id=b.tarifario_id and cuenta='" . $_SESSION['CAJA']['CUENTA'] . "'";

        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$resulta->EOF) {
            $var[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function InsertarTempDetalleExt() {
        $cantidad = $_REQUEST['cantidad'];
        $dat = $_REQUEST['checo'];
        $datos = $_REQUEST['datos'];

        if (!$dat) {
            $this->frmError["MensajeError"] = "Debe Seleccionar minimo una cita para poder efectuar el pago.";
            $_REQUEST['Documento'] = $_REQUEST['Documento'];
            $_REQUEST['tipodoc'] = $_REQUEST['tipodoc'];
            $this->FormaMetodoBuscar();
            return true;
        }
        if (!$_SESSION['CAJA']['CUENTA']) {
            $_SESSION['CAJA']['CUENTA'] = $this->AsignarCuentaVirtual();
        }
        $cuenta = $_SESSION['CAJA']['CUENTA'];
        $empresa = $_SESSION['CAJA']['EMPRESA'];
        $cu = $_SESSION['CAJA']['CENTROUTILIDAD'];
        $_SESSION['CAJA']['PACIENTEID'] = $_REQUEST['Documento'];
        $_SESSION['CAJA']['TIPO_ID_PACIENTE'] = $_REQUEST['tipodoc'];
        $_SESSION['CAJA']['PLAN'] = $_REQUEST['Plan'];
        $_SESSION['CAJA']['NIVEL'] = $_REQUEST['Nivel'];
        IncludeLib("tarifario");
        list($dbconn) = GetDBconn();
        $query = "SELECT
                                tipo_id_tercero,tercero_id,descripcion
                                FROM terceros_particular";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $_SESSION['CAJA']['TIPO_ID_TERCERO'] = $result->fields[0];
        $_SESSION['CAJA']['TERCEROID'] = $result->fields[1];
        $_SESSION['CAJA']['NOMBRE_TERCERO'] = $result->fields[2];



        for ($i = 0; $i < sizeof($dat); $i++) {
            //$cant=$cantidad[$i];
            $c = explode("/", $dat[$i]);
            $cant = $c[1];
            $a = explode(",", $dat[$i]);

            unset($a[sizeof($a) - 1]);
            unset($asignada);
            /*  $Var=$this->CallMetodoExterno('app','Facturacion','user','CoutaPaciente',array('PlanId'=>$datos[$a[0]][plan_id],'Nivel'=>$datos[$a[0]][rango]));
              $PorPaciente=$Var[porcentaje];
              $Maximo=$Var[valor_maximo];
              $Minimo=$Var[valor_minimo]; */
            $cargo = $datos[$a[0]][cargo];
            $plan = $datos[$a[0]][plan_id];
            $tarifario = $datos[$a[0]][tarifario_id];
            $tiposervicio = $datos[$a[0]][tipo_servicio];
            $fecha = $datos[$a[0]][fecha];
            $plan = $datos[$a[0]][plan_id];
            //$nivel=$datos[$a[0]][nivel];
            $rango = $datos[$a[0]][rango];
            $tipo_afiliado = $datos[$a[0]][tipo_id_afiliado];
            $servicio = $datos[$a[0]][servicio];

            //REVISAR ESTO YA Q NO SE SABE DE DONDE SALDRA EN
            //NIVEL PARA PODER SACAR ESTOS DATOS...
            //ADEMAS
            //$rango=$dat[$i][rango]; //este fue el cambio nuevo cuando se quito el nivel.
            //  $tipo_afiliado=$dat[$i][tipo_id_afiliado]; //este fue el cambio nuevo cuando se quito el nivel.


            foreach ($a as $t => $v) {
                if (!empty($v) or $v === '0') {
                    //"->".$datos[$v][orden_pago]."<br>";
                    $_SESSION['CAJA']['ORDENPAGO'][$v] = $datos[$v][orden_pago];
                    $asignada[$v] = $datos[$v][agenda_cita_asignada_id];
                }
            }
            asort($asignada);
            $cita = end($asignada);

            $Liq = LiquidarCargoCuenta('', $tarifario, $cargo, 1, 0, 0, true, true, 0, $servicio, $plan, $tipo_afiliado, $rango, '', true, 0, 0);
            //$Liq=LiquidarCargo($plan,$tarifario,$cargo,$PorPaciente,$Maximo,$Minimo,1,'');
            // $rango=$Liq[rango];  //ESTOS DATOS SON NUEVOS....
            //  $tipo_afiliado=$Liq[tipo_id_afiliado];//ESTOS DATOS SON NUEVOS....


            $Precio = $Liq[precio_plan];
            $ValorCargo = $Liq[valor_cargo];
            $GravamenEmp = $Liq[gravamen_empresa];
            $GravamenPac = $Liq[gravamen_paciente];
            $ValorPac = $Liq[copago];
            $ValorNo = $Liq[valor_no_cubierto];
            $ValorCub = $Liq[valor_cubierto];
            $ValEmpresa = $Liq[valor_empresa];
            if (!empty($cita)) {
                $estado = 1;
            } else {
                $estado = 0;
                $cita = 'NULL';
            }
            $query = "INSERT INTO  tmp_detalle_cargos_ambulatorios
                  (  cargo,
                    tarifario_id,
                    descuento,
                    gravamen_valor_cubierto,
                    valor_cargo,
                    valor_cuota_paciente,
                    valor_nocubierto,
                    valor_cubierto,
                    gravamen_valor_nocubierto,
                    empresa_id,
                    centro_utilidad,
                    recibo_caja,
                    prefijo,
                    cuenta,
                    agenda_cita_asignada_id,
                    estado,
                    plan_id,
                    rango,
                    tipo_id_afiliado,
                    fecha,
                    tipo_servicio,
                    cantidad)
                    VALUES('$cargo','$tarifario',0,$GravamenEmp,$ValorCargo,$ValorPac,$ValorNo,
                    $ValorCub,$GravamenPac,'$empresa','$cu','0',0,$cuenta,$cita,$estado,'$plan',
                    '$rango','$tipo_afiliado','$fecha','$tiposervicio',$cant)";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo1";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        }
        $this->FormaCuentaExterna();
        return true;
    }

    /**
     * La funcion  BuscarPunto revisa si se esta inserando con punto o si punto el
     * valor numerico ejemplo (25.780) 0 (25780) para realizar operaciones de suma,resta,mult,division.
     * @access public
     * @return boolean
     */
    function FormatoValor($cadena) {
        if (strpos($cadena, ".") === false && strpos($cadena, ",") === false) {
            return FormatoValor($cadena);
        }
        return $cadena;
    }

    function limpiar($valor) {
        $tmp = explode('.', $valor);
        $val = $tmp[0] . $tmp[1];
        return $val;
    }

    //INSERTAR PAGOS DE LOS PAGARES
    function InsertarPagos() {
        list($dbconn) = GetDBconn();
        //$dbconn->debug = true;
        $usuario = UserGetUID();
        $FechaHoy = date("Y-m-d H:i:s");

        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Cuenta = $_REQUEST['Cuenta'];
        $Valor = $_REQUEST['Valor'];
        $Total = $_REQUEST['valorpagar'];
        $Cajaid = $_REQUEST['Cajaid'];
        $NombrePaciente = $_REQUEST['NombrePaciente'];
        $PagareNumero = $_REQUEST['PagareNumero'];
        $Empresa = $_REQUEST['Empresa'];
        $Prefijo = $_REQUEST['Prefijo'];
        $Valor = $_REQUEST['Valor'];
        $DocumentoId = $_REQUEST['DocumentoId'];
        if ($_REQUEST['spy'] == 1) {
            $val = explode('.', $_REQUEST['efectivo']);
            $Total = $val[0] . $val[1];

            $contador = strpos($Total, ".");
            if ($contador != FALSE AND $contador <> "") {
                $this->frmError["efectivo"] = 1;
                $this->frmError["MensajeError"] = "NO COLOQUE EL CARACTER . A LO QUE VA A COLOCAR EN EFECTIVO";
                if (!$this->FormaCuenta($Cuenta, $TipoId, $PacienteId, "", "", "", "", "", "", "", "", $PagareNumero, $Cajaid, $NombrePaciente, $Empresa, $Prefijo, $Valor, $DocumentoId)) {
                    return false;
                }
                return true;
            }

            if (!is_numeric($Total)) {
                $this->frmError["valorpagar"] = 1;
                $this->frmError["MensajeError"] = "DIGITE UN VALOR NUMERICO !";
                if (!$this->FormaCuenta($Cuenta, $TipoId, $PacienteId, "", "", "", "", "", "", "", "", $PagareNumero, $Cajaid, $NombrePaciente, $Empresa, $Prefijo, $Valor, $DocumentoId)) {
                    return false;
                }
            }

            if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
                $sql = "SELECT documento_id_recibo ";
                $sql .= "FROM   documentos_pagares ";
                $sql .= "WHERE  documento_id_pagare = " . $DocumentoId . " ";

                $cxn = AutoCarga::factory('ConexionBD');
                //$cxn->debug = true;
                if (!$rst = $cxn->ConexionBaseDatos($sql))
                    return false;

                $datos = array();
                if (!$rst->EOF) {
                    $datos = $rst->GetRowAssoc($ToUpper = false);
                    $rst->MoveNext();
                }
                $rst->Close();

                $DocumentoId = $datos['documento_id_recibo'];
            }

            $datos = $this->AsignarNumero($DocumentoId, &$dbconn);
            $recibo = $datos['numero'];
            $prf_pagare = $datos['prefijo'];

            $efectivo = $this->Limpiar($_REQUEST['efectivo']);
            $cheque = $this->Limpiar($_REQUEST['cheque']);

            $Ttarjeta = $this->Limpiar($_REQUEST['tarjetac']) + $this->Limpiar($_REQUEST['tarjetad']);
            $TotalAbono = $efectivo + $cheque + $Ttarjeta;
            if ($TotalAbono > $Valor) {
                $this->frmError["MensajeError"] = "EL TOTAL DE LOS ABONOS ES MAYOR QUE EL VALOR TOTAl";
                if (!$this->FormaCuenta($Cuenta, $TipoId, $PacienteId, "", "", "", "", "", "", "", "", $PagareNumero, $Cajaid, $NombrePaciente, $Empresa, $Prefijo, $Valor, $DocumentoId)) {
                    return false;
                }
            }
            if ($TotalAbono == 0) {
                $this->frmError["MensajeError"] = "EL VALOR TOTAL DE LOS ABONOS ES CERO.";
                $this->FormaCuenta($Cuenta, $TipoId, $PacienteId, "", "", "", "", "", "", "", "", $PagareNumero, $Cajaid, $NombrePaciente, $Empresa, $Prefijo, $Valor, $DocumentoId);
                return true;
            }
            $cutilidad = $_SESSION['PAGARE']['centro_utilidad'];
            $FechaC = date("Y-m-d H:i:s");
            if (empty($Empresa) || empty($Prefijo) || empty($PagareNumero)) {
                $Empresa = $_SESSION['CAJA']['EMPRESA'];
                $Prefijo = $_SESSION['PAGARE']['PREFIJO'];
                $PagareNumero = $_SESSION['PAGARE']['NUMERO'];
            } else {
                $_SESSION['CAJA']['EMPRESA'] = $Empresa;
                $_SESSION['PAGARE']['PREFIJO'] = $Prefijo;
                $_SESSION['PAGARE']['NUMERO'] = $PagareNumero;
            }

            $datos = $this->ConsultaCabeceraPagare($Empresa, $Prefijo, $PagareNumero);

            if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
                $prefijo_pagare = $Prefijo;
                $Prefijo = $prf_pagare;
            }

            $i = sizeof($datos);
            if (!empty($datos)) {
                $TipoIdTercero = $datos[$i - 1][tipo_id_tercero];
                $TerceroId = $datos[$i - 1][tercero_id];
            } else {
                $TipoIdTercero = $TipoId;
                $TerceroId = $PacienteId;
            }
            $cuenta_tipo = $_SESSION['CAJA']['TIPOCUENTA'];
            if ($cheque == '') {
                $cheque = 0;
            }
            if (empty($efectivo)) {
                $efectivo = 0;
            }
            if (empty($Ttarjeta)) {
                $Ttarjeta = 0;
            }
            $sqls = "INSERT into recibos_caja
                  (
                    empresa_id,
                    centro_utilidad,
                    prefijo,
                    recibo_caja,
                    fecha_ingcaja,
                    total_abono,
                    total_efectivo,
                    total_cheques,
                    total_tarjetas,
                    tipo_id_tercero,
                    tercero_id,
                    fecha_registro,
                    usuario_id,
                    caja_id,
                    documento_id,
                    cuenta_tipo_id
                  )
                  VALUES
                  (
                    '$Empresa',
                    '$cutilidad',
                    '$Prefijo',
                    $recibo,
                    '$FechaC',
                    $TotalAbono,
                    $efectivo,
                    $cheque,
                    $Ttarjeta,
                    '$TipoIdTercero',
                    '$TerceroId',
                    '$FechaHoy',
                    $usuario,
                    '$Cajaid',
                    '$DocumentoId',
                    '$cuenta_tipo'
                  );";
            // $dbconn->BeginTrans();  //Inicia la transaccion
            $result = $dbconn->Execute($sqls); //  GuardarNumeroDocumento(true);
            $dbconn->ErrorNo();
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al insertar en recibos_caja";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumento(false);
                return false;
            }

            if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {//CAJA PAGARE.
                $consulta = "INSERT INTO rc_detalle_pagare
                        (
                          empresa_id,
                          centro_utilidad,
                          recibo_caja,
                          prefijo,
                          numero,
                          prefijo_pagare
                        )
                        VALUES
                        (
                          '" . $Empresa . "',
                          '" . $cutilidad . "',
                           " . $recibo . ",
                          '" . $Prefijo . "',
                           " . $PagareNumero . ",
                          '" . $prefijo_pagare . "'
                        );";
                $result = $dbconn->Execute($consulta); //  GuardarNumeroDocumento(true);
                $dbconn->ErrorNo();
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    GuardarNumeroDocumento(false);
                    return false;
                }
                //SI EL TOTAL DE PAGARE YA SE HA CANCELADO
                //ACTUALIZAR EL ESTADO DE LOS PAGARES A CANCELADO
                $consulta = " SELECT  SUM(a.total_abono)
                        FROM    recibos_caja a,
                                rc_detalle_pagare b
                        WHERE   b.empresa_id = '" . $Empresa . "'
                        AND     b.prefijo = '" . $Prefijo . "'
                        AND     b.prefijo_pagare = '" . $prefijo_pagare . "'
                        AND     b.numero = " . $PagareNumero . "
                        AND     b.recibo_caja=a.recibo_caja
                        AND     b.prefijo=a.prefijo
                        AND     b.empresa_id=a.empresa_id
                        AND     b.centro_utilidad=a.centro_utilidad
                        AND     a.estado IN ('0') ";
                $resul = $dbconn->Execute($consulta); //  GuardarNumeroDocumento(true);
                $dbconn->ErrorNo();
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Seleccionar de recibos_caja/rc_detalle_pagare";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                if ($resul->fields[0] == $Valor) {
                    $consulta = "UPDATE   pagares SET sw_estado='3'
                          WHERE   empresa_id = '" . $Empresa . "'
                          AND     prefijo = '" . $prefijo_pagare . "'
                          AND     numero = " . $PagareNumero . " ";
                    $result = $dbconn->Execute($consulta); //  GuardarNumeroDocumento(true);
                    $dbconn->ErrorNo();
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al actualizar el estado de pagares";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                }
                //FIN- SI EL TOTAL DE PAGARE YA SE HA CANCELADO
                //ACTUALIZAR EL ESTADO DE LOS PAGARES A CANCELADO
            }
            $this->frmError["MensajeError"] = "DATOS GUARDADOS";
            GuardarNumeroDocumento(true);
            unset($_SESSION['PAGARE']['valorc']);
            unset($_SESSION['PAGARE']['valorcr']);
            unset($_SESSION['PAGARE']['valord']);
            unset($_SESSION['PAGARE']['VALORPAGAR']);

            $this->FormaCuenta($Cuenta, $TipoId, $PacienteId, "", "", "", "", "", "", "", "", $PagareNumero, $Cajaid, $NombrePaciente, $Empresa, $Prefijo, $Valor, $DocumentoId);
            return true;
        }//CHEQUES
    }

    /**
     * La funcion  InsertarTemporales R
     * Guarda los datos de los pagos momentaneamente en unas tablas
     * temporales(tmp_tarjetas_mov_credito,tmp_tarjetas_mov_debito,tmp_cheques_mov)
     *  cuando se inserta en la tabla recibo_caja, se borra el contenido de estas tablas temporales.
     * @access public
     * @return boolean
     */
    function InsertarTemporales() {
        IncludeLib("tarifario");
        $cutilidad = $_SESSION['CAJA']['CENTROUTILIDAD'];
        $empresa = $_SESSION['CAJA']['EMPRESA'];
        $Departamento = $_REQUEST['Departamento'];
        $ValorNo = $_REQUEST['ValorNo'];
        $ValorPac = $_REQUEST['ValorPac'];
        $Precio = $_REQUEST['Precio'];
        $Cargo = $_REQUEST['Cargo'];
        $ValEmpresa = $_REQUEST['ValorEmp'];
        $TarifarioId = $_REQUEST['TarifarioId'];
        $GrupoTarifario = $_REQUEST['GrupoTarifario'];
        $SubGrupoTarifario = $_REQUEST['SubGrupoTarifario'];
        $Gravamen = $_REQUEST['Gravamen'];
        $Cantidad = $_REQUEST['Cantidad'];
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
        //$kia=$_REQUEST['kia'];
        $FechaHoy = date("Y/m/d H:i:s");
        $usuario = UserGetUID();
        $Total = $_REQUEST['valorpagar'];
        //  $cutilidad=$this->Cutilidad;
        //  $empresa=$this->Empresa;
        list($dbconn) = GetDBconn();
        if (!$Cuenta) {
            $Cuenta = $_SESSION['CAJA']['CUENTA'];
        }

        if ($spy == 1) {

            if ($_SESSION['CAJA']['TIPOCUENTA'] == '03' or $_SESSION['CAJA']['TIPOCUENTA'] == '04' or $_SESSION['CAJA']['TIPOCUENTA'] == '05' or $_SESSION['CAJA']['TIPOCUENTA'] == '08') {


                //$res=FormatoValor($Total + $_SESSION['CAJA']['SUBTOTAL']);
                $res = $Total + $_SESSION['CAJA']['SUBTOTAL'];
                //if($res > $_SESSION['CAJA']['SAL'] or ($_SESSION['CAJA']['SAL']-$_SESSION['CAJA']['SUBTOTAL'])==0)
                //if($res > FormatoValor($_SESSION['CAJA']['SAL']) or (FormatoValor($_SESSION['CAJA']['SAL'])-FormatoValor($_SESSION['CAJA']['SUBTOTAL']))==0)
                /* if($res > $_SESSION['CAJA']['SAL'] or ($_SESSION['CAJA']['SAL'])-($_SESSION['CAJA']['SUBTOTAL'])==0)
                  {
                  $this->frmError["efectivo"]=1;
                  $this->frmError["MensajeError"]="El pago execede el valor del saldo o el saldo es 0";
                  if(!$this->CapturaHospitalizacion('1',$Cuenta,$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC,$FechaHoy,$Total)){
                  return false;
                  }
                  return true;
                  } */

                //Para controlar el efectivo que no se coloque un caracter '.'
                $contador = strpos($Total, ".");
                if ($contador != FALSE AND $contador <> "") {
                    $this->frmError["efectivo"] = 1;
                    $this->frmError["MensajeError"] = "NO COLOQUE EL CARACTER . A LO QUE VA A COLOCAR EN EFECTIVO";
                    if (!$this->CapturaHospitalizacion('1', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Total)) {
                        return false;
                    }
                    return true;
                }

                if (!is_numeric($Total)) {
                    $this->frmError["efectivo"] = 1;
                    $this->frmError["MensajeError"] = "DIGITE UN VALOR NUMERICO !";
                    if (!$this->CapturaHospitalizacion('1', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Total)) {
                        return false;
                    }
                    return true;
                }
            }


            $Efectivo = $_REQUEST['efectivo'];
            $Total = str_replace(".", "", $Total);
            //$_SESSION['CAJA']['TOTAL_EFECTIVO']=$_SESSION['CAJA']['TOTAL_EFECTIVO']+$Total;
            $_SESSION['CAJA']['TOTAL_EFECTIVO'] = $Total;
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
                if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                    return false;
                }
                return true;
            }
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
                if (!$this->CajaConsultaExterna($TipoId, $PacienteId, $_SESSION['CAJA']['PASO'], $PlanId)) {
                    return false;
                }
                return true;
            }
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
                if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                    return false;
                }
                return true;
            }
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                if (!$this->CajaConceptos($Cajaid)) {
                    return false;
                }
                return true;
            }
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                if (!$this->RetornarFormaOrdenesServicio()) {
                    return false;
                }
                return true;
            }
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '08') {
                if (!$this->CajaConceptos($Cajaid)) {
                    return false;
                }
                return true;
            }
        }

        if ($spy == 2) {
            if (empty($_SESSION['CAJA']['NUM']) and empty($_SESSION['CAJA']['KIA']) and empty($_SESSION['CAJA']['CONSECUTIVO'])) {
                $_SESSION['CAJA']['NUM'] = $_REQUEST['num'];
                $_SESSION['CAJA']['KIA'] = $_REQUEST['kia'];
                $_SESSION['CAJA']['CONSECUTIVO'] = $_REQUEST['consecutivo'];
            }
            $banco = $_REQUEST['banco'];
            $CtaCte = $_REQUEST['ctac'];
            $cheque = $_REQUEST['nocheque'];
            $girador = $_REQUEST['girador'];
            $fechaC = $_REQUEST['fechacheque'];
            $valorC = $_REQUEST['totalc'];
            $valorC = str_replace(".", "", $valorC);
            $fechapapel = $_REQUEST['fech'];

            if (!$cheque || !$CtaCte || !$fechaC) {
                if (!$cheque) {
                    $this->frmError["nocheque"] = 1;
                }
                if (!$CtaCte) {
                    $this->frmError["ctac"] = 1;
                }
                if (!$fechaC) {
                    $this->frmError["fechacheque"] = 1;
                }
                $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
                if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                    if (!$this->CapturaHospitalizacion('2', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                        return false;
                    }
                } else {
                    if (!$this->CapturaPagares('2', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '')) {
                        return false;
                    }
                }
                return true;
            }


            //Para controlar el pago con cheque que no se coloque un caracter '.'
            $contador = strpos($_REQUEST['totalc'], ".");
            if ($contador != FALSE AND $contador <> "") {
                $this->frmError["MensajeError"] = "NO COLOQUE EL CARACTER . A LO QUE VA A COLOCAR EN EFECTIVO";
                if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                    if (!$this->CapturaHospitalizacion('2', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                        return false;
                    }
                } else {
                    if (!$this->CapturaPagares('2', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '')) {
                        return false;
                    }
                }
                return true;
            }


            //compara q no se coloquen letras.
            if (!is_numeric($_REQUEST['totalc'])) {
                $this->frmError["MensajeError"] = "DIGITE UN VALOR NUMERICO !";
                $this->frmError["valor"] = 1;
                if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                    if (!$this->CapturaHospitalizacion('2', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                        return false;
                    }
                } else {
                    if (!$this->CapturaPagares('2', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '')) {
                        return false;
                    }
                }
                return true;
            }



            /*             * **********************revisar fechacheque******************************** */
            $x = str_replace("/", "", $fechaC);
            $x = str_replace("-", "", $x);

            if (!is_numeric($x)) {
                $this->frmError["MensajeError"] = "POR FAVOR LOS UNICOS CARACTERES VALIDOS PARA LA FECHA SON (/) O (-)";
                if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                    if (!$this->CapturaHospitalizacion('2', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                        return false;
                    }
                } else {
                    if (!$this->CapturaPagares('2', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '')) {
                        return false;
                    }
                }
                return true;
            }


            $ar = str_replace("/", "-", $fechaC);
            $arr = explode("-", $ar);
            if (strlen($arr[2]) == 4) {
                $fechaC = $arr[2] . "-" . $arr[1] . "-" . $arr[0]; //se cambia a(y-m-d) * q esta(d-m-Y)
            } else {
                $fechaC = $ar; //formato Y-m-d
            }
            /*             * **********************revisar fechacheque******************************** */


            /*             * **********************revisar fech******************************** */
            $x = str_replace("/", "", $fechapapel);
            $x = str_replace("-", "", $x);

            if (!is_numeric($x)) {
                $this->frmError["MensajeError"] = "POR FAVOR LOS UNICOS CARACTERES VALIDOS PARA LA FECHA SON (/) O (-)";
                if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                    if (!$this->CapturaHospitalizacion('2', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                        return false;
                    }
                } else {
                    if (!$this->CapturaPagares('2', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '')) {
                        return false;
                    }
                }
                return true;
            }


            $ar = str_replace("/", "-", $fechapapel);
            $arr = explode("-", $ar);
            if (strlen($arr[2]) == 4) {
                $fechapapel = $arr[2] . "-" . $arr[1] . "-" . $arr[0]; //se cambia a(y-m-d) * q esta(d-m-Y)
            } else {
                $fechapapel = $ar; //formato Y-m-d
            }
            /*             * **********************revisar fech******************************** */




//          if($_SESSION['CAJA']['TIPOCUENTA']=='03' or $_SESSION['CAJA']['TIPOCUENTA']=='04' or $_SESSION['CAJA']['TIPOCUENTA']=='05')
//           {
//             $res=$valorC + $_SESSION['CAJA']['SUBTOTAL'];
//             if($res > $_SESSION['CAJA']['SAL'] or ($_SESSION['CAJA']['SAL']-$_SESSION['CAJA']['SUBTOTAL'])==0 )
//             {
//                 $this->frmError["MensajeError"].="No puede exceder el valor del saldo o el saldo es 0";
//                          if($_SESSION['CAJA']['TIPOCUENTA']<>'06')
//                          {
//                 if(!$this->CapturaHospitalizacion('2',$Cuenta,$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC,$FechaHoy,$Valor)){
//                   return false;
//                 }
//                          }
//                          else
//                          {
//                              if(!$this->CapturaPagares('2',$TipoId,$PacienteId,$Cuenta,$Valor,$Cajaid,$NombrePaciente,$PagareNumero,$Empresa,$Prefijo,$Valor,$FechaHoy='')){
//                                  return false;
//                              }
//                          }
//                 return true;
//             }
//           }
//MODI 27102005
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '04' or $_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                $res = $valorC + $_SESSION['CAJA']['SUBTOTAL'];
                if ($res > $_SESSION['CAJA']['SAL'] or ($_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL']) == 0) {
                    $this->frmError["MensajeError"].="No puede exceder el valor del saldo o el saldo es 0";
                    if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                        if (!$this->CapturaHospitalizacion('2', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Valor)) {
                            return false;
                        }
                    } else {
                        if (!$this->CapturaPagares('2', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '')) {
                            return false;
                        }
                    }
                    return true;
                }
            }
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                if (!$this->CapturaPagares('2', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = ''))
                    return false;
            }
//FIN MODI 27102005

            $num = $_SESSION['CAJA']['NUM'];
            $kia = $_SESSION['CAJA']['KIA'];
            /*      $query="SELECT NEXTVAL('tmp_cheques_mov_seq') ";
              $result = $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al traer la secuencia de tmp_cheques_mov_seq";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
              }
              $kia=$result->fields[0]; */
            //
            $consecutivo = $_SESSION['CAJA']['CONSECUTIVO'];
            if (($num == '') or $kia == '') {
                $kia = 99999999999999; //asegurarse que no va a realizar la busqueda
                $num = 99999999999999; //asegurarse que no va a realizar la busqueda
            }
            $query = "SELECT COUNT(cheque_mov_id) from tmp_confirmacion_che WHERE
                cheque_mov_id='$kia' and numero_confirmacion='$num' ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al traer la secuencia de tmp_confirmacion_che";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $conteo = $result->fields[0];

            if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                $FechaHoy = date("Y/m/d H:i:s");
                if ($conteo > 0) {
                    $query = "INSERT INTO tmp_cheques_mov(
                                                                                                cheque_mov_id,
                                                                                                empresa_id,
                                                                                                centro_utilidad,
                                                                                                numerodecuenta,
                                                                                                banco,
                                                                                                cta_cte,
                                                                                                cheque,
                                                                                                girador,
                                                                                                fecha_cheque,
                                                                                                total,
                                                                                                fecha,
                                                                                                usuario_id,
                                                                                                fecha_registro,
                                                                                                consecutivo)
                                                                        VALUES($kia,'$empresa','$cutilidad',$Cuenta,'$banco','$CtaCte','$cheque','$girador','$fechaC',
                                                                                        $valorC,'$fechapapel',$usuario,'$FechaHoy',$consecutivo)";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    } else {
                        $_SESSION['CAJA']['SUBTOTAL'] = $_SESSION['CAJA']['SUBTOTAL'] + $valorC;
                        unset($_SESSION['CAJA']['CONSECUTIVO']);
                        unset($_SESSION['CAJA']['KIA']);
                        unset($_SESSION['CAJA']['NUM']);
                        $banco = $_REQUEST['banco'];
                        $_REQUEST['ctac'] = '';
                        $_REQUEST['nocheque'] = '';
                        $_REQUEST['girador'] = '';
                        $_REQUEST['fechacheque'] = '';
                        $_REQUEST['totalc'] = '';
                        $_REQUEST['fech'] = '';
                        if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
                            if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                                return false;
                            }
                            return true;
                        }
                        if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
                            if (!$this->CajaConsultaExterna($TipoId, $PacienteId, $_SESSION['CAJA']['PASO'], $PlanId)) {
                                return false;
                            }
                            return true;
                        }
                        if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
                            if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                                return false;
                            }
                            return true;
                        }
                        if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                            if (!$this->CajaConceptos($Cajaid)) {
                                return false;
                            }
                            return true;
                        }
                        if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                            if (!$this->RetornarFormaOrdenesServicio()) {
                                return false;
                            }
                            return true;
                        }
                        if ($_SESSION['CAJA']['TIPOCUENTA'] == '08') {
                            if (!$this->CajaConceptos($Cajaid)) {
                                return false;
                            }
                            return true;
                        }
                    }
                }
            } else {
                $Empresa = $_REQUEST['Empresa'];
                $cutilidad = $_SESSION['PAGARE']['centro_utilidad'];
                if ($conteo > 0) {
                    $query = "INSERT INTO tmp_cheques_mov(
                                                                                            cheque_mov_id,
                                                                                            empresa_id,
                                                                                            centro_utilidad,
                                                                                            numerodecuenta,
                                                                                            banco,
                                                                                            cta_cte,
                                                                                            cheque,
                                                                                            girador,
                                                                                            fecha_cheque,
                                                                                            total,
                                                                                            fecha,
                                                                                            usuario_id,
                                                                                            fecha_registro,
                                                                                            consecutivo)
                                                                    VALUES($kia,'$Empresa','$cutilidad',$Cuenta,'$banco','$CtaCte','$cheque','$girador','$fechaC',
                                                                                    $valorC,'$fechapapel',$usuario,'$FechaHoy',$consecutivo)";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    } else {
                        $_SESSION['PAGARE']['valorc'] = $valorC;
                        unset($_SESSION['CAJA']['CONSECUTIVO']);
                        unset($_SESSION['CAJA']['KIA']);
                        unset($_SESSION['CAJA']['NUM']);
                        $banco = $_REQUEST['banco'];
                        $_REQUEST['ctac'] = '';
                        $_REQUEST['nocheque'] = '';
                        $_REQUEST['girador'] = '';
                        $_REQUEST['fechacheque'] = '';
                        $_REQUEST['totalc'] = '';
                        $_REQUEST['fech'] = '';
                        if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
                            $this->frmError["MensajeError"] = "Datos guardados(tmp_cheques_mov).";
                            if (!$this->CajaPagares('2', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '')) {
                                return false;
                            }
                            return true;
                        }
                    }
                }
            }
            $this->frmError["MensajeError"] = "Debe Autorizar el Cheque.";
            if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                $this->CapturaHospitalizacion('2', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor);
            } else {
                UNSET($_SESSION['PAGARE']['valorc']);
                if (!$this->CapturaPagares('2', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '')) {
                    return false;
                }
            }
            return true;
        }

        if ($spy == 3) {
            if (empty($_SESSION['CAJA']['NUMS']) and empty($_SESSION['CAJA']['KIAS']) and empty($_SESSION['CAJA']['CONSECUTIVOS'])) {
                $_SESSION['CAJA']['NUMS'] = $_REQUEST['num'];
                $_SESSION['CAJA']['KIAS'] = $_REQUEST['kia'];
                $_SESSION['CAJA']['CONSECUTIVOS'] = $_REQUEST['consecutivo'];
            }
            $tarjeta = $_REQUEST['tarjeta'];
            $fechapapel = $_REQUEST['fecha'];
            $autorizacion = $_REQUEST['noautorizacion'];
            $socio = $_REQUEST['socio'];
            $fechaexp = $_REQUEST['fechaexp'];
            if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                $valor = $_REQUEST['total'];
            }
            else
                $valor = $_REQUEST['totalcr'];

            $valor = str_replace(".", "", $valor);
            $autorizador = $_REQUEST['autoriza'];
            $notarjeta = $_REQUEST['numtarjeta'];

            if (!$tarjeta || !$fechaexp || !$fechapapel || !$notarjeta || !$socio || !$valor) {
                if (!$tarjeta) {
                    $this->frmError["tarjeta"] = 1;
                }
                if (!$fechaexp) {
                    $this->frmError["fechaexp"] = 1;
                }
                if (!$fechapapel) {
                    $this->frmError["fecha"] = 1;
                }
                if (!$notarjeta) {
                    $this->frmError["numtarjeta"] = 1;
                }
                if (!$socio) {
                    $this->frmError["socio"] = 1;
                }
                if (!$valor) {
                    $this->frmError["valor"] = 1;
                }

                $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
                if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                    if (!$this->CapturaHospitalizacion('3', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                        return false;
                    }
                    return true;
                } else {
                    if (!$this->CapturaPagares('3', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '')) {
                        return false;
                    }
                }
            }



            //Para controlar el pago credito que no se coloque un caracter '.'
            if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                $contador = strpos($_REQUEST['total'], ".");
            }
            else
                $contador = strpos($_REQUEST['totalcr'], ".");

            if ($contador != FALSE AND $contador <> "") {
                $this->frmError["MensajeError"] = "NO COLOQUE EL CARACTER . A LO QUE VA A COLOCAR EN EFECTIVO";
                if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                    if (!$this->CapturaHospitalizacion('3', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                        return false;
                    }
                    return true;
                } else {
                    if (!$this->CapturaPagares('3', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '')) {
                        return false;
                    }
                }
            }


            //Para controlar el pago credito que no se coloque un caracter '.'

            if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                if (!is_numeric($_REQUEST['total'])) {
                    $this->frmError["valor"] = 1;
                    $this->frmError["MensajeError"] = "DIGITE UN VALOR NUMERICO";
                    if (!$this->CapturaHospitalizacion('3', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                        return false;
                    }
                    return true;
                }
            } else
            if (!is_numeric($_REQUEST['totalcr'])) {
                $this->frmError["totalcr"] = 1;
                $this->frmError["MensajeError"] = "DIGITE UN VALOR NUMERICO";
                if (!$this->CapturaPagares('3', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '')) {
                    return false;
                }
                return true;
            }






            /*             * **********************revisar fecha******************************** */
            $x = str_replace("/", "", $fechapapel);
            $x = str_replace("-", "", $x);

            if (!is_numeric($x)) {
                $this->frmError["MensajeError"] = "POR FAVOR LOS UNICOS CARACTERES VALIDOS DE LA FECHA SON (/) O (-)";
                if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                    if (!$this->CapturaHospitalizacion('3', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                        return false;
                    }
                } else {
                    if (!$this->CapturaPagares('3', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '')) {
                        return false;
                    }
                }
                return true;
            }


            $ar = str_replace("/", "-", $fechapapel);
            $arr = explode("-", $ar);
            if (strlen($arr[2]) == 4) {
                $fechapapel = $arr[2] . "-" . $arr[1] . "-" . $arr[0]; //se cambia a(y-m-d) * q esta(d-m-Y)
            } else {
                $fechapapel = $ar; //formato Y-m-d
            }
            /*             * **********************revisar fecha******************************** */


            /*             * **********************revisar $fechaexp******************************** */
            $x = str_replace("/", "", $fechaexp);
            $x = str_replace("-", "", $x);

            if (!is_numeric($x)) {
                $this->frmError["MensajeError"] = "POR FAVOR LOS UNICOS CARACTERES VALIDOS DE LA FECHA SON (/) O (-)";
                if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                    if (!$this->CapturaHospitalizacion('3', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                        return false;
                    }
                } else {
                    if (!$this->CapturaPagares('3', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '')) {
                        return false;
                    }
                }
                return true;
            }


            $ar = str_replace("/", "-", $fechaexp);
            $arr = explode("-", $ar);
            if (strlen($arr[2]) == 4) {
                $fechaexp = $arr[2] . "-" . $arr[1] . "-" . $arr[0]; //se cambia a(y-m-d) * q esta(d-m-Y)
            } else {
                $fechaexp = $ar; //formato Y-m-d
            }
            /*             * **********************revisar $fechaexp******************************** */


            if ($_SESSION['CAJA']['TIPOCUENTA'] == '03' or $_SESSION['CAJA']['TIPOCUENTA'] == '04' or $_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                $res = $valor + $_SESSION['CAJA']['SUBTOTAL'];
                if ($res > $_SESSION['CAJA']['SAL'] or ($_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL']) == 0) {
                    $this->frmError["MensajeError"].="No puede exceder el valor del saldo o el saldo es 0";
                    if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                        if (!$this->CapturaHospitalizacion('3', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Valor)) {
                            return false;
                        }
                    } else {
                        if (!$this->CapturaPagares('3', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '')) {
                            return false;
                        }
                    }
                    return true;
                }
            }

            $num = $_SESSION['CAJA']['NUMS'];
            $kia = $_SESSION['CAJA']['KIAS'];
            $consecutivo = $_SESSION['CAJA']['CONSECUTIVOS'];
            if (($num == '') or $kia == '') {
                $kia = 99999999999999; //asegurarse que no va a realizar la busqueda
                $num = 99999999999999; //asegurarse que no va a realizar la busqueda
            }

            $query = "SELECT COUNT(tarjeta_mov_id) from tmp_confirmacion_tar WHERE
                  tarjeta_mov_id='$kia' and numero_confirmacion='$num' ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $conteo = $result->fields[0];
            if ($conteo > 0) {
                $query = "INSERT into tmp_tarjetas_mov_credito(
                                              tarjeta_mov_id,
                                              tarjeta,
                                              tarjeta_numero,
                                              empresa_id,
                                              centro_utilidad,
                                              fecha,
                                              autorizacion,
                                              socio,
                                              fecha_expira,
                                              autorizado_por,
                                              total,
                                              usuario_id,
                                              fecha_registro,
                                              numerodecuenta,
                                              consecutivo)
                                VALUES($kia,'$tarjeta','$notarjeta','$empresa','$cutilidad','$fechapapel',
                                '$autorizacion','$socio','$fechaexp','$autorizador',$valor,$usuario,'$FechaHoy',$Cuenta,$consecutivo)";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                } else {
                    $_SESSION['CAJA']['SUBTOTAL'] = $_SESSION['CAJA']['SUBTOTAL'] + $valor;
                    $_SESSION['PAGARE']['valorcr'] = $valor;
                    unset($_SESSION['CAJA']['CONSECUTIVOS']);
                    unset($_SESSION['CAJA']['KIAS']);
                    unset($_SESSION['CAJA']['NUMS']);
                    $_REQUEST['tarjeta'] = '';
                    $_REQUEST['fecha'] = '';
                    $_REQUEST['noautorizacion'] = '';
                    $_REQUEST['socio'] = '';
                    $_REQUEST['fechaexp'] = '';
                    $_REQUEST['total'] = '';
                    $_REQUEST['autoriza'] = '';
                    $_REQUEST['numtarjeta'] = '';
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
                        if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                            return false;
                        }
                        return true;
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
                        if (!$this->CajaConsultaExterna($TipoId, $PacienteId, $_SESSION['CAJA']['PASO'], $PlanId)) {
                            return false;
                        }
                        return true;
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
                        if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                            return false;
                        }
                        return true;
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                        if (!$this->CajaConceptos($Cajaid)) {
                            return false;
                        }
                        return true;
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                        if (!$this->RetornarFormaOrdenesServicio()) {
                            return false;
                        }
                        return true;
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
                        if (!$this->CajaPagares($spy, $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId)) {
                            return false;
                        }
                        return true;
                    }
                    if ($_SESSION['CAJA']['TIPOCUENTA'] == '08') {
                        if (!$this->CajaConceptos($Cajaid)) {
                            return false;
                        }
                        return true;
                    }
                }
            }
            $this->frmError["MensajeError"] = "Debe Autorizar la tarjeta.";
            if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06')
                $this->CapturaHospitalizacion('3', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor);
            else {
                UNSET($_SESSION['PAGARE']['valorcr']);
                $this->CapturaPagares('3', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '');
            }

            return true;
        }

        if ($spy == 4) {
            $valor = $_REQUEST['totald'];

            //Para controlar el pago debito que no se coloque un caracter '.'
            $contador = strpos($valor, ".");
            if ($contador != FALSE AND $contador <> "") {
                $this->frmError["MensajeError"] = "NO COLOQUE EL CARACTER . A LO QUE VA A COLOCAR EN EFECTIVO";
                if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                    if (!$this->CapturaHospitalizacion('4', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                        return false;
                    }
                } else {
                    $this->CapturaPagares('4', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '');
                }
                return true;
            }


            if (!is_numeric($valor)) {
                $this->frmError["MensajeError"] = "DIGITE UN VALOR NUMERICO !";
                $this->frmError["valor"] = 1;
                if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                    if (!$this->CapturaHospitalizacion('4', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                        return false;
                    }
                } else {
                    $this->CapturaPagares('4', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '');
                }
                return true;
            }




            $valor = str_replace(".", "", $valor); //para quitarle los puntos...
            $autorizacion = $_REQUEST['noautorizad'];
            $Tarjeta = $_REQUEST['tarjeta'];
            $numtarjeta = $_REQUEST['numtarjeta'];

            if (!$autorizacion || !$numtarjeta || !$valor) {
                if (!$autorizacion) {
                    $this->frmError["noautorizad"] = 1;
                }
                if (!$numtarjeta) {
                    $this->frmError["numtarjeta"] = 1;
                }
                if (!$valor) {
                    $this->frmError["totald"] = 1;
                }
                $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
                if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                    if (!$this->CapturaHospitalizacion('4', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                        return false;
                    }
                } else {
                    $this->CapturaPagares('4', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '');
                }
                return true;
            }


            if ($_SESSION['CAJA']['TIPOCUENTA'] == '03' or $_SESSION['CAJA']['TIPOCUENTA'] == '04' or $_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                $res = $valor + $_SESSION['CAJA']['SUBTOTAL'];
                if ($res > $_SESSION['CAJA']['SAL'] or ($_SESSION['CAJA']['SAL'] - $_SESSION['CAJA']['SUBTOTAL']) == 0) {
                    $this->frmError["MensajeError"].="No puede exceder el valor del saldo o el saldo es 0";
                    if ($_SESSION['CAJA']['TIPOCUENTA'] <> '06') {
                        if (!$this->CapturaHospitalizacion('4', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                            return false;
                        }
                    } else {
                        $this->CapturaPagares('4', $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $FechaHoy = '');
                    }
                    return true;
                }
            }

            $query = "INSERT into tmp_tarjetas_mov_debito(
                                      empresa_id,
                                      centro_utilidad,
                                      autorizacion,
                                      total,
                                      numerodecuenta,
                                      tarjeta,
                                      tarjeta_numero
                                      )
                        VALUES('$empresa','$cutilidad','$autorizacion',$valor,$Cuenta,'$Tarjeta','$numtarjeta')";
            $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            } else {
                $_SESSION['CAJA']['SUBTOTAL'] = $_SESSION['CAJA']['SUBTOTAL'] + $valor;
                $_SESSION['PAGARE']['valord'] = $valor;
                $_REQUEST['totald'] = '';
                $_REQUEST['noautorizad'] = '';
                $_REQUEST['tarjeta'] = '';
                $_REQUEST['numtarjeta'] = '';
                if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
                    if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                        return false;
                    }
                    return true;
                }
                if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
                    if (!$this->CajaConsultaExterna($TipoId, $PacienteId, $_SESSION['CAJA']['PASO'], $PlanId)) {
                        return false;
                    }
                    return true;
                }
                if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
                    if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                        return false;
                    }
                    return true;
                }
                if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                    if (!$this->CajaConceptos($Cajaid)) {
                        return false;
                    }
                    return true;
                }
                if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                    if (!$this->RetornarFormaOrdenesServicio()) {
                        return false;
                    }
                    return true;
                }

                if ($_SESSION['CAJA']['TIPOCUENTA'] == '06') {
                    if (!$this->CajaPagares($spy, $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId)) {
                        return false;
                    }
                    return true;
                }
            }
        }

        if ($spy == 5) {
            $valor = $_REQUEST['pagabono'];
            //Para controlar el bono que no se coloque un caracter '.'
            $contador = strpos($valor, ".");
            if ($contador != FALSE AND $contador <> "") {
                $this->frmError["MensajeError"] = "NO COLOQUE EL CARACTER . A LO QUE VA A COLOCAR EN EFECTIVO";
                if (!$this->CapturaHospitalizacion('5', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                    return false;
                }
                return true;
            }


            if (!is_numeric($valor)) {
                $this->frmError["MensajeError"] = "DIGITE UN VALOR NUMERICO !";
                if (!$this->CapturaHospitalizacion('5', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                    return false;
                }
                return true;
            }





            $valor = str_replace(".", "", $valor); //quita los puntos..........

            if (!$valor) {
                if (!$valor) {
                    $this->frmError["pagabono"] = 1;
                }
                if (!$this->CapturaHospitalizacion('5', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                    return false;
                }
                return true;
            }

            /*  if($_SESSION['CAJA']['TIPOCUENTA']=='03' or $_SESSION['CAJA']['TIPOCUENTA']=='04'  or $_SESSION['CAJA']['TIPOCUENTA']=='05')
              {
              $res=$valor + $_SESSION['CAJA']['SUBTOTAL'];
              if($res > $_SESSION['CAJA']['SAL'] or ($_SESSION['CAJA']['SAL']-$_SESSION['CAJA']['SUBTOTAL'])==0 )
              {
              $this->frmError["MensajeError"].="No puede exceder el valor del saldo o el saldo es 0";
              if(!$this->CapturaHospitalizacion('5',$Cuenta,$PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$FechaC,$FechaHoy='',$Valor)){
              return false;
              }
              return true;
              }
              } */


            if ($_SESSION['CAJA']['TIPOCUENTA'] == '03' or $_SESSION['CAJA']['TIPOCUENTA'] == '04' or $_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                $res = $valor + ($_SESSION['CAJA']['SUBTOTAL'] - $_SESSION['CAJA']['BONO']);
                if ($res > $_SESSION['CAJA']['SAL']) {
                    $this->frmError["MensajeError"].="No puede exceder el valor del saldo o el saldo es 0";
                    if (!$this->CapturaHospitalizacion('5', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy = '', $Valor)) {
                        return false;
                    }
                    return true;
                }
            }




            $_SESSION['CAJA']['SUBTOTAL'] = $_SESSION['CAJA']['SUBTOTAL'] + $valor;
            // $_SESSION['CAJA']['BONO']=$_SESSION['CAJA']['BONO']+$valor;
            $_SESSION['CAJA']['BONO'] = $valor;
            $_REQUEST['totald'] = '';
            $_REQUEST['noautorizad'] = '';
            $_REQUEST['tarjeta'] = '';
            $_REQUEST['numtarjeta'] = '';
            $this->frmError["MensajeError"].="Datos guardados";
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '01') {
                if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                    return false;
                }
                return true;
            }
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '04') {
                if (!$this->CajaConsultaExterna($TipoId, $PacienteId, $_SESSION['CAJA']['PASO'], $PlanId)) {
                    return false;
                }
                return true;
            }
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '02') {
                if (!$this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy)) {
                    return false;
                }
                return true;
            }
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '03') {
                if (!$this->CajaConceptos($Cajaid)) {
                    return false;
                }
                return true;
            }
            if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
                if (!$this->RetornarFormaOrdenesServicio()) {
                    return false;
                }
                return true;
            }
        }
    }

    /**
     *
     */
    function InsertarConfirmaT($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy) {
        list($dbconn) = GetDBconn();
        $usuario = UserGetUID();
        if ($_SESSION['CAJA']['TIPOCUENTA'] <> 6) {
            $PlanId = $_REQUEST['PlanId'];
            $TipoId = $_REQUEST['TipoId'];
            $PacienteId = $_REQUEST['PacienteId'];
            $Ingreso = $_REQUEST['Ingreso'];
            $Nivel = $_REQUEST['Nivel'];
            $FechaC = $_REQUEST['FechaC'];
            $Cuenta = $_REQUEST['Cuenta'];
            if (!$Cuenta) {
                $Cuenta = $_SESSION['CAJA']['CUENTA'];
            }
            $Efectivo = $_REQUEST['efectivo'];
            $Total = $_REQUEST['valorpagar'];
            $spy = $_REQUEST['spy'];
            $a = $_REQUEST['entconfirma'];
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
            $Efectivo = $_REQUEST['efectivo'];
            $Total = $_REQUEST['valorpagar'];
            $a = $_REQUEST['entconfirma'];
            $b = $_REQUEST['funconfirma'];
            $c = $_REQUEST['numconfirma'];
            $d = $_REQUEST['fechaconfirma'];
        }



        $b = $_REQUEST["funconfirma"];
        $c = $_REQUEST['numconfirma'];
        $d = $_REQUEST['fechaconfirma'];
        if (!$a || !$c || !$d) {
            if (!$a) {
                $this->frmError["entconfirma"] = 1;
            }
            if (!$c) {
                $this->frmError["numconfirma"] = 1;
            }
            if (!$d) {
                $this->frmError["fechaconfirma"] = 1;
            }
            if ($_SESSION['CAJA']['TIPOCUENTA'] <> 6) {
                $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
                if (!$this->autorizacion($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy, '3')) {
                    return false;
                }
            } else {
                $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
                if (!$this->autorizacionPagares($spy, $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId, '3')) {
                    return false;
                }
            }
            return true;
        }

        $x = str_replace("/", "", $d);
        $x = str_replace("-", "", $x);

        if (!is_numeric($x)) {
            $this->frmError["MensajeError"] = "POR FAVOR LOS UNICOS CARACTERES VALIDOS DE FECHA SON (/) O (-)";
            if ($_SESSION['CAJA']['TIPOCUENTA'] <> 6) {
                if (!$this->autorizacion($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy, '3')) {
                    return false;
                }
            } else {
                $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
                if (!$this->autorizacionPagares($spy, $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId, $FechaHoy)) {
                    return false;
                }
            }
            return true;
        }


        $ar = str_replace("/", "-", $d);
        $arr = explode("-", $ar);
        if (strlen($arr[2]) == 4) {
            $d = $arr[2] . "-" . $arr[1] . "-" . $arr[0]; //se cambia a(y-m-d) * q esta(d-m-Y)
        } else {
            $d = $ar; //formato Y-m-d
        }


//exit;

        $query = "select nextval('public.tarjetas_mov_credito_tarjeta_mov_id_seq');";
        $r = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar la identificacion de la tarjeta";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $id = $r->fields[0];

        $query = "select nextval('public.confirmacion_tar_consecutivo_seq');";
        $r = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar la identificacion de la tarjeta";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $consecutivo = $r->fields[0];

        $query = "INSERT INTO tmp_confirmacion_tar
              (tarjeta_mov_id,
               entidad_confirma,
               funcionario_confirma,
               numero_confirmacion,
               fecha,
               usuario_id,
               consecutivo,
               numerodecuenta
               )VALUES(
               $id,'$a','" . $b . "','$c','$d',$usuario,$consecutivo,$Cuenta)";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Insertar la confirmacion de la tarjeta";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            $FechaHoy = '';
            $FechaHoy['0'] = 'si';
            $FechaHoy['1'] = urlencode($b);
            $FechaHoy['2'] = $c;
            $FechaHoy['3'] = $id;
            $FechaHoy['4'] = $consecutivo;
            if ($_SESSION['CAJA']['TIPOCUENTA'] <> 6) {
                $this->CapturaHospitalizacion('3', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Valor);
            } else {
                $this->CapturaPagares($spy, $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId, $FechaHoy);
            }
            return true;
        }
    }

    /**
     *
     */
    function InsertarConfirmaC($spy, $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId) {
        list($dbconn) = GetDBconn();
        $usuario = UserGetUID();
        if ($_SESSION['CAJA']['TIPOCUENTA'] <> 6) {
            $PlanId = $_REQUEST['PlanId'];
            $TipoId = $_REQUEST['TipoId'];
            $PacienteId = $_REQUEST['PacienteId'];
            $Ingreso = $_REQUEST['Ingreso'];
            $Nivel = $_REQUEST['Nivel'];
            $FechaC = $_REQUEST['FechaC'];
            $Cuenta = $_REQUEST['Cuenta'];
            if (!$Cuenta) {
                $Cuenta = $_SESSION['CAJA']['CUENTA'];
            }
            $Efectivo = $_REQUEST['efectivo'];
            $Total = $_REQUEST['valorpagar'];
            $spy = $_REQUEST['spy'];
            $a = $_REQUEST['entconfirma'];
            $b = $_REQUEST['funconfirma'];
            $c = $_REQUEST['numconfirma'];
            $d = $_REQUEST['fechaconfirma'];
        } else {
            $TipoId = $_REQUEST['TipoId'];
            $PacienteId = $_REQUEST['PacienteId'];
            $Cuenta = $_REQUEST['Cuenta'];
            //$spy=$_REQUEST['spy'];
            $spy = 2;
            $Cajaid = $_REQUEST['Cajaid'];
            $NombrePaciente = $_REQUEST['NombrePaciente'];
            $PagareNumero = $_REQUEST['PagareNumero'];
            $Empresa = $_REQUEST['Empresa'];
            $Prefijo = $_REQUEST['Prefijo'];
            $Valor = $_REQUEST['Valor'];
            $DocumentoId = $_REQUEST['DocumentoId'];
            $Efectivo = $_REQUEST['efectivo'];
            $Total = $_REQUEST['valorpagar'];
            $a = $_REQUEST['entconfirma'];
            $b = $_REQUEST['funconfirma'];
            $c = $_REQUEST['numconfirma'];
            $d = $_REQUEST['fechaconfirma'];
        }

        if (!$a || !$c || !$d) {
            if (!$a) {
                $this->frmError["entconfirma"] = 1;
            }
            if (!$c) {
                $this->frmError["numconfirma"] = 1;
            }
            if (!$d) {
                $this->frmError["fechaconfirma"] = 1;
            }
            if ($_SESSION['CAJA']['TIPOCUENTA'] <> 6) {
                $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
                if (!$this->autorizacion($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy, '2')) {
                    return false;
                }
            } else {
                $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
                if (!$this->autorizacionPagares($spy, $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId)) {
                    return false;
                }
            }
            return true;
        }

        $x = str_replace("/", "", $d);
        $x = str_replace("-", "", $x);

        if (!is_numeric($x)) {
            $this->frmError["MensajeError"] = "POR FAVOR LOS UNICOS CARACTERES VALIDOS DE FECHA SON (/) O (-)";
            if ($_SESSION['CAJA']['TIPOCUENTA'] <> 6) {
                if (!$this->autorizacion($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy, '2')) {
                    return false;
                }
            } else {
                $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
                if (!$this->autorizacionPagares($spy, $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId)) {
                    return false;
                }
            }
            return true;
        }


        $ar = str_replace("/", "-", $d);
        $arr = explode("-", $ar);
        if (strlen($arr[2]) == 4) {
            $d = $arr[2] . "-" . $arr[1] . "-" . $arr[0]; //se cambia a(y-m-d) * q esta(d-m-Y)
        } else {
            $d = $ar; //formato Y-m-d
        }

        $query = "select nextval('public.cheques_mov_cheque_mov_id_seq');";
        $r = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar la identificacion de la tarjeta";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $id = $r->fields[0];

        $query = "select nextval('public.confirmacion_che_consecutivo_seq');";
        $r = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar la identificacion de la tarjeta";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $consecutivo = $r->fields[0];

        $query = "INSERT INTO tmp_confirmacion_che
              (cheque_mov_id,
               entidad_confirma,
               funcionario_confirma,
               numero_confirmacion,
               fecha,
               usuario_id,
               consecutivo,
               numerodecuenta
               )VALUES($id,'$a','$b','$c','$d',$usuario,$consecutivo,$Cuenta)";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Insertar la confirmacion de la tarjeta";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            $FechaHoy = '';
            $FechaHoy['0'] = 'si';
            $FechaHoy['1'] = $b;
            $FechaHoy['2'] = $c;
            $FechaHoy['3'] = $id;
            $FechaHoy['4'] = $consecutivo;
            if ($_SESSION['CAJA']['TIPOCUENTA'] <> 6) {
                $this->CapturaHospitalizacion('2', $Cuenta, $PlanId, $TipoId, $PacienteId, $Ingreso, $Nivel, $FechaC, $FechaHoy, $Valor);
                return true;
            } else {
                $this->frmError["MensajeError"] = "DATOS GUARDADOS";
                $this->CapturaPagares($spy, $TipoId, $PacienteId, $Cuenta, $Valor, $Cajaid, $NombrePaciente, $PagareNumero, $Empresa, $Prefijo, $Valor, $DocumentoId, $FechaHoy);
                return true;
            }
        }
    }

    function DatosEncabezadoEmpresa($Caja, $tipocuenta, $tmp) {
        if (empty($_SESSION['caja']['Caja']))
            $_SESSION['caja']['Caja'] = $_SESSION['CAJA']['CAJAID'];
        if (empty($Caja))
            $Caja = $_SESSION['caja']['Caja'];
        $CentroU = $_SESSION['CAJA']['CENTROUTILIDAD'];
        //if($CentroU)
        //{ $CU="and a.centro_utilidad='$CentroU'"; }

        list($dbconn) = GetDBconn();
        //GLOBAL $ADODB_FETCH_MODE;
        if (($Caja AND $tipocuenta != '03' AND $tipocuenta != '05' AND $tipocuenta != '08') OR !empty($tmp)) {
            $query = "SELECT a.descripcion as descuenta, c.descripcion,
                                                c.centro_utilidad, b.razon_social
                                    FROM cajas as a, empresas as b,centros_utilidad as c
                                    WHERE  c.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                        AND c.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                        AND a.caja_id='" . $Caja . "'
                                        AND b.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "';";
        } else
        if ($Caja) {
            $query = "SELECT a.descripcion as descuenta, c.descripcion,
                                                c.centro_utilidad, b.razon_social
                                    FROM cajas_rapidas as a, empresas as b,centros_utilidad as c
                                    WHERE  c.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                        AND c.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                        AND a.caja_id='" . $Caja . "'
                                        AND b.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "';";
        }
        //$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var = $resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
        return $var;
    }

    /**
     * Busca los datos de los cargos que estan pendientes de un usuario
     * @access public
     * @return array
     */
    function DatosTmpCuentasPendientes() {
        $Usuario = UserGetUID();
        list($dbconn) = GetDBconn();
        $query = "(select distinct a.numerodecuenta, b.ingreso, b.plan_id, b.fecha_registro,
                    b.rango, c.paciente_id, c.tipo_id_paciente, d.primer_nombre, d.segundo_nombre,
                    d.primer_apellido, d.segundo_apellido from tmp_cuentas_detalle as a, cuentas as b, ingresos as c, pacientes as d
                    where a.usuario_id=$Usuario
                                        and a.numerodecuenta=b.numerodecuenta and b.ingreso=c.ingreso
                                        and c.paciente_id=d.paciente_id and c.tipo_id_paciente=d.tipo_id_paciente)";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $var;
    }

    /**
     * Busca el departamento y su descripcion en la tabla departamentos.
     * @access public
     * @return array
     */
    function Departamentos() {
        $EmpresaId = $_SESSION['CUENTAS']['EMPRESA'];
        $CentroU = $_SESSION['CUENTAS']['CENTROUTILIDAD'];
        if ($CentroU) {
            $CU = "and centro_utilidad='$CentroU'";
        }

        list($dbconn) = GetDBconn();
        $query = "SELECT a.departamento,a.descripcion
                  FROM departamentos as a, servicios as b WHERE a.empresa_id='$EmpresaId'
                  and a.servicio=b.servicio and b.sw_asistencial=1";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            if ($result->EOF) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla maestra 'departamentos' esta vacia ";
                return false;
            }
            while (!$result->EOF) {
                $vars[$result->fields[0]] = $result->fields[1];
                $result->MoveNext();
            }
        }
        $result->Close();
        return $vars;
    }

    /*
     * funcion que trae el nombre del usuasrio para el reporte de cierre de caja general(hosp))
     *
     */

    function TraerPacienteCajaGeneral($recibo, $prefijo, $tipocuenta) {
        list($dbconn) = GetDBconn();
        if ($tipocuenta == '03' OR $tipocuenta == '08') {
            $query = "SELECT  b.nombre_tercero as nombre,
                                    b.tipo_id_tercero||' '||b.tercero_id as id

                                    FROM fac_facturas_contado a, terceros b
                                    WHERE a.factura_fiscal=" . $recibo . "
                                    AND a.prefijo='" . $prefijo . "'
                                    AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                    AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                    AND a.tipo_id_tercero=b.tipo_id_tercero
                                    AND a.tercero_id=b.tercero_id;";
        } else {
            $query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id

                                FROM pacientes f,ingresos s,cuentas x,rc_detalle_hosp a
                WHERE a.recibo_caja=" . $recibo . "
                                AND a.prefijo='" . $prefijo . "'
                                AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                AND a.numerodecuenta=x.numerodecuenta
                                AND x.ingreso=s.ingreso
                                AND s.paciente_id=f.paciente_id
                                AND s.tipo_id_paciente=f.tipo_id_paciente";
        }
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer datos del usuario";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $resulta->fields[1] . "&nbsp;-&nbsp;" . $resulta->fields[0];
    }

    /*
     * funcion que trae el nombre del paciente cuando se han realizado cajas facturadoras)
     *
     */

// function TraerPacientePagare($recibo,$prefijo)
// {
//      list($dbconn) = GetDBconn();
//      $query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
//                 f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
//                 f.tipo_id_paciente||' '||f.paciente_id as id
//
//                              FROM pacientes f,cuentas x,pagares A, ingresos s
//                 WHERE a.numero=".$recibo."
//                              AND a.prefijo='".$prefijo."'
//                              AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
//                              AND a.numerodecuenta=x.numerodecuenta
//                              AND x.ingreso=s.ingreso
//                              AND s.paciente_id=f.paciente_id
//                              AND s.tipo_id_paciente=f.tipo_id_paciente";
//
//          $resulta=$dbconn->Execute($query);
//          if ($dbconn->ErrorNo() != 0) {
//                  $this->error = "Error al traer datos del usuario";
//                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                  return false;
//          }
//
//          return $resulta->fields[1]."&nbsp;-&nbsp;".$resulta->fields[0];
// }



    /*
     * funcion que trae el nombre del paciente cuando se han realizado cajas facturadoras)
     *
     */

    function TraerPaciente($recibo, $prefijo) {
        list($dbconn) = GetDBconn();
        $query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id
                                FROM pacientes f,ingresos s,cuentas x,fac_facturas_cuentas a
                WHERE a.factura_fiscal=" . $recibo . "
                                AND a.prefijo='" . $prefijo . "'
                                AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                AND a.numerodecuenta=x.numerodecuenta
                                AND x.ingreso=s.ingreso
                                AND s.paciente_id=f.paciente_id
                                AND s.tipo_id_paciente=f.tipo_id_paciente";

        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer datos del usuario";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        return $resulta->fields[1] . "&nbsp;-&nbsp;" . $resulta->fields[0];
    }

    function TraerPacientePagare($recibo, $prefijo) {
        list($dbconn) = GetDBconn();
        $query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id
                                FROM pacientes f,ingresos s,cuentas x,
                                        rc_detalle_pagare a, pagares b
                WHERE a.recibo_caja=" . $recibo . "
                                AND a.prefijo='" . $prefijo . "'
                                AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                AND b.numerodecuenta=x.numerodecuenta
                                AND x.ingreso=s.ingreso
                                AND s.paciente_id=f.paciente_id
                                AND s.tipo_id_paciente=f.tipo_id_paciente
                                AND a.empresa_id=b.empresa_id
                                AND a.prefijo=b.prefijo
                                AND a.numero=b.numero";

        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer datos del usuario";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $resulta->fields[1] . "&nbsp;-&nbsp;" . $resulta->fields[0];
    }

    /*
     * funcion q trae los decuestos de las facturas
     */

    function TraerDescuento($no_cuenta) {
        list($dbconn) = GetDBconn();
        $query = "SELECT precio
                                            FROM cuentas_detalle
                                            WHERE
                                            empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                            AND centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                            AND departamento='" . $_SESSION['CAJA']['CIERRE']['DEPTO'] . "'
                                            AND numerodecuenta=$no_cuenta
                                            AND cargo='DESCUENTO'
                                            AND tarifario_id='SYS'";
        $resulta = $dbconn->Execute($query);
        if (!$resulta->fields[0]) {
            return 0;
        } else {
            return $resulta->fields[0];
        }
    }

    //TRAE LOS TOTALES DE LOS RECIBOS QUE NO SE LES HA REALIZADO
    //EL CUADRE DE CAJA  y por lo tanto el cierre tampoco
    //DE LOS USUARIOS DISTINTOS AL ACTUAL DEL SISTEMA
    function ReciboSinCuadre($emp, $cu, $Caja, $tipocuenta, $dp) {
        list($dbconn) = GetDBconn();
        //$fecha=date('Y-m-d');
        if ($tipocuenta == '03' OR $tipocuenta == '08') {
            //caso de cierre de caja conceptos.
            /*      echo    $query="SELECT a.empresa_id,a.centro_utilidad,a.factura_fiscal as recibo_caja,
              a.fecha_registro as fecha_ingcaja,b.caja_id,
              b.descripcion as descripcion, a.total_efectivo,a.total_cheques,
              a.total_bonos,a.total_tarjetas,a.usuario_id,a.prefijo,
              (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma,
              d.nombre
              FROM fac_facturas v,
              fac_facturas_contado a,cajas b,cajas_usuarios c,
              system_usuarios d
              WHERE a.caja_id=b.caja_id
              AND a.caja_id=$Caja
              AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
              AND a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."' and a.cierre_caja_id ISNULL
              AND a.usuario_id<>".UserGetUID()."
              AND c.caja_id=a.caja_id
              AND a.usuario_id=c.usuario_id
              AND a.usuario_id=d.usuario_id
              AND a.factura_fiscal=v.factura_fiscal
              AND a.prefijo=v.prefijo
              AND v.estado='0'
              ORDER BY a.factura_fiscal,a.fecha_registro;"; */
            $query = "SELECT DISTINCT a.empresa_id,a.centro_utilidad,b.caja_id,
                                        b.descripcion as descripcion, a.usuario_id,
                                        d.nombre
                            FROM fac_facturas_contado a,cajas_rapidas b,
                                userpermisos_cajas_rapidas c,
                                system_usuarios d
                            WHERE a.caja_id=b.caja_id
                            AND a.caja_id=$Caja
                            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                            AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "' and a.cierre_caja_id ISNULL
                            AND a.usuario_id<>" . UserGetUID() . "
                            AND c.caja_id=a.caja_id
                            AND a.usuario_id=c.usuario_id
                            AND a.usuario_id=d.usuario_id;";
							
        } else
        if (!empty($dp)) {
            //caso recibos de otro usuario
            //REEMPLAZADO POR STEVEN
            /*
              $query="SELECT a.empresa_id,a.centro_utilidad,a.factura_fiscal as recibo_caja,a.fecha_registro as fecha_ingcaja,b.caja_id,
              b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
              a.total_tarjetas,a.usuario_id,a.prefijo
              FROM fac_facturas v,
              fac_facturas_contado a,cajas_rapidas b
              WHERE b.caja_id=$Caja
              AND a.cierre_caja_id ISNULL
              AND a.caja_id=b.caja_id
              AND a.empresa_id='".$emp."'
              AND a.usuario_id<>".UserGetUID()."
              AND v.empresa_id=a.empresa_id
              AND v.prefijo=a.prefijo
              AND v.factura_fiscal=a.factura_fiscal
              AND v.tipo_factura in('0','2')
              AND v.estado='0';";
             */
            $query = "SELECT a.empresa_id,
                                   a.centro_utilidad,
                                   a.factura_fiscal as recibo_caja,
                                   a.fecha_registro as fecha_ingcaja,
                                   b.caja_id,
                                   b.descripcion as caja, 
                                   a.total_efectivo,
                                   a.total_cheques,a.total_bonos,
                                   a.total_tarjetas,a.usuario_id,a.prefijo

                            FROM    fac_facturas_contado a INNER JOIN cajas_rapidas b ON (a.caja_id=b.caja_id)
                            INNER JOIN fac_facturas v ON (v.empresa_id=a.empresa_id
                                                                    AND   v.prefijo=a.prefijo
                                                                    AND   v.factura_fiscal=a.factura_fiscal)

                            WHERE b.caja_id=$Caja
                            AND   a.cierre_caja_id ISNULL
                            AND   a.empresa_id='" . $emp . "'
                            AND   a.usuario_id<>" . UserGetUID() . "
                            AND   v.tipo_factura in('0','2')
                            AND   v.estado='0';";





            ////SOLO FACTURAS ACTIVAS
            /*  $query="SELECT a.empresa_id,a.centro_utilidad,a.factura_fiscal as recibo_caja,a.fecha_registro as fecha_ingcaja,b.caja_id,
              b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
              a.total_tarjetas,a.usuario_id,a.prefijo,e.numerodecuenta,e.sw_tipo,
              (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma
              FROM fac_facturas_cuentas e,fac_facturas v,
              fac_facturas_contado a,cajas_rapidas b,userpermisos_cajas_rapidas c
              WHERE a.caja_id=b.caja_id and a.empresa_id='".$emp."'
              AND b.caja_id=$Caja
              AND a.centro_utilidad='".$cu."' and a.cierre_caja_id ISNULL
              AND a.usuario_id<>".UserGetUID()."
              AND c.caja_id=a.caja_id and a.usuario_id=c.usuario_id
              AND e.factura_fiscal=a.factura_fiscal
              AND e.prefijo=a.prefijo
              AND e.factura_fiscal=v.factura_fiscal
              AND e.prefijo=v.prefijo
              AND v.estado='0'
              AND e.sw_tipo=0
              ORDER BY a.factura_fiscal,a.fecha_registro"; */
        } elseif ($_SESSION['CAJA']['CIERRE']['DEPTO']) {
            //caso de cierre de cajas rapidas.
            $dpto = $_SESSION['CAJA']['CIERRE']['DEPTO'];
            //                          AND DATE(a.fecha_registro)='".$fecha."'
            //PROXIMO A MODIFICAR POR STEVEN

            /*
              SELECT  a.empresa_id,
              a.centro_utilidad,
              a.factura_fiscal as recibo_caja,
              a.fecha_registro as fecha_ingcaja,
              b.caja_id,
              b.descripcion as caja,
              a.total_efectivo,
              a.total_cheques,
              a.total_bonos,
              a.total_tarjetas,
              a.usuario_id,
              a.prefijo,
              e.numerodecuenta,
              e.sw_tipo,
              (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma
              FROM    fac_facturas contado a INNER JOIN cajas_rapidas b ON (a.caja_id = b.caja_id)
              INNER JOIN userpermisos_cajas_rapidas c ON (c.caja_id = a.caja_id
              AND c.usuario_id = a.usuario_id)
              INNER JOIN fac_facturas_cuentas e ON (e.factura_fiscal = a.factura_fiscal
              AND e.prefijo = a.prefijo)
              INNER JOIN fac_facturas v ON (v.factura_fiscal=e.factura_fiscal
              AND v.prefijo=e.prefijo)
              AND     a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
              AND     a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
              AND     a.cierre_caja_id ISNULL
              AND     b.departamento='$dpto'
              AND     a.usuario_id<>".UserGetUID()."
              AND     v.estado='0'
              AND     e.sw_tipo=0
              ORDER BY a.factura_fiscal,a.fecha_registro
             */
            $query = "SELECT  a.empresa_id,
                                    a.centro_utilidad,
                                    a.factura_fiscal as recibo_caja,
                                    a.fecha_registro as fecha_ingcaja,
                                    b.caja_id,
                                    b.descripcion as caja, 
                                    a.total_efectivo,
                                    a.total_cheques,
                                    a.total_bonos,
                                    a.total_tarjetas,
                                    a.usuario_id,
                                    a.prefijo,
                                    e.numerodecuenta,
                                    e.sw_tipo,
                                    (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma
                            FROM fac_facturas v 
                                    INNER JOIN fac_facturas_cuentas e ON (e.empresa_id = v.empresa_id AND e.prefijo = v.prefijo AND e.factura_fiscal = v.factura_fiscal)
                                INNER JOIN fac_facturas_contado a ON (a.empresa_id = v.empresa_id AND a.prefijo = v.prefijo AND a.factura_fiscal = v.factura_fiscal)
                                INNER JOIN userpermisos_cajas_rapidas c ON (c.caja_id = a.caja_id AND c.usuario_id = a.usuario_id)
                                INNER JOIN cajas_rapidas b ON (b.caja_id=a.caja_id)
                            WHERE   a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                            AND     a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "' 
                            AND     a.cierre_caja_id ISNULL
                            AND     b.departamento='$dpto'  
                            AND     a.usuario_id<>" . UserGetUID() . "
                            AND     v.estado='0'
                            AND     e.sw_tipo=0
                            ORDER BY a.factura_fiscal,a.fecha_registro    ";
            /*
              $query="SELECT a.empresa_id,a.centro_utilidad,a.factura_fiscal as recibo_caja,a.fecha_registro as fecha_ingcaja,b.caja_id,
              b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
              a.total_tarjetas,a.usuario_id,a.prefijo,e.numerodecuenta,e.sw_tipo,
              (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma
              FROM fac_facturas_cuentas e,fac_facturas v,
              fac_facturas_contado a,cajas_rapidas b,userpermisos_cajas_rapidas c
              WHERE a.caja_id=b.caja_id and a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
              and a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."' and a.cierre_caja_id ISNULL
              and b.departamento='$dpto'  AND a.usuario_id<>".UserGetUID()."
              and c.caja_id=a.caja_id and a.usuario_id=c.usuario_id
              and e.factura_fiscal=a.factura_fiscal
              and e.prefijo=a.prefijo
              and e.factura_fiscal=v.factura_fiscal
              and e.prefijo=v.prefijo
              and v.estado='0'
              and e.sw_tipo=0
              ORDER BY a.factura_fiscal,a.fecha_registro";
             * 
             */
        } else {
            //                                  AND DATE(a.fecha_registro)='".$fecha."'
            //QUERY MODIFICADO POR JONIER

            $query = "SELECT  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,d.descripcion as des
                        FROM recibos_caja a
                                INNER JOIN cajas b ON (a.caja_id=b.caja_id)
                                INNER JOIN cajas_usuarios c ON (a.caja_id=c.caja_id AND c.usuario_id=a.usuario_id)
                                INNER JOIN system_usuarios d ON (c.usuario_id=d.usuario_id)
                        WHERE   a.caja_id=$Caja
                                AND a.cierre_caja_id ISNULL
                                AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                AND a.usuario_id<>" . UserGetUID() . "
                                AND b.cuenta_tipo_id='01'
                                AND a.estado IN ('0')
                                ORDER BY b.descripcion	";
            /*
              $query="SELECT  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
              d.descripcion as des
              FROM recibos_caja a,cajas b,system_usuarios d ,
              cajas_usuarios c
              WHERE   a.caja_id=$Caja
              AND a.caja_id=b.caja_id
              AND a.cierre_caja_id ISNULL
              AND a.caja_id=c.caja_id
              AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
              AND a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
              AND a.usuario_id<>".UserGetUID()."
              AND c.usuario_id=d.usuario_id
              AND c.usuario_id=a.usuario_id
              AND b.cuenta_tipo_id='01'
              AND a.estado IN ('0')
              ORDER BY b.descripcion;";
             * 
             */
        }
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer los recibos de caja para cierre";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while (!$resulta->EOF) {
            $var[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

//FIN TRAE LOS TOTALES DE LOS RECIBOS QUE SE LES HA REALIZADO
//EL CUADRE DE CAJA PERO NO EL CIERRE
//VERIFICACI� DEL ESTADO DEL CIERRE (CONFIRMADO O NO CONFIRMADO)
    function EstadoCierre($Caja) {
        list($dbconn) = GetDBconn();
        $fecha = date('Y-m-d');
        //caso de cierre de cajas rapidas.
        //$dpto=$_SESSION['CAJA']['CIERRE']['DEPTO'];
        $query = "SELECT d.cierre_caja_id,c.fecha_registro,
                                            c.total_efectivo,c.total_cheques,c.total_tarjetas,
                                            c.total_devolucion,c.entrega_efectivo,c.observaciones,
                                            c.sw_confirmado
                                    FROM recibos_caja_cierre a,cajas b, cierre_de_caja c,
                                            cierre_de_caja_detalle d
                                    WHERE b.caja_id=$Caja
                                    AND c.caja_id=b.caja_id
                                    AND a.cierre_caja_id=d.cierre_caja_id
                                    AND c.cierre_de_caja_id=d.cierre_de_caja_id
                                    AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                    AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                    AND c.sw_confirmado=0
                                    AND DATE(c.fecha_registro)='" . $fecha . "';";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer los recibos de caja para cierre";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$resulta->EOF) {
            $var[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

//FIN VERIFICACI� DEL ESTADO DEL CIERRE (CONFIRMADO O NO CONFIRMADO)

    function TraerReciboCaja($Caja, $tipocuenta, $usuario, $sw_cuadrada, $cierre) {
        list($dbconn) = GetDBconn();
        if (($tipocuenta == '03' AND $sw_cuadrada) OR ($tipocuenta == '08' AND $sw_cuadrada)) {
            if ($tipocuenta == '03')
                $tipofactura = "and v.tipo_factura in('5')";
            else
                $tipofactura = "and v.tipo_factura in('6')";
            //AND v.estado='0'
            $query = "SELECT a.empresa_id,a.centro_utilidad,a.factura_fiscal as recibo_caja,
                                    a.fecha_registro as fecha_ingcaja,b.caja_id, b.descripcion as caja,
                                    a.total_efectivo,a.total_cheques,a.total_bonos, a.total_tarjetas,a.usuario_id,
                                    a.prefijo,
                                    (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma,
                                    CASE WHEN v.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
                                    FROM fac_facturas v, fac_facturas_contado a,cajas_rapidas b,
                                    userpermisos_cajas_rapidas c
                                    WHERE a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                    AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                    AND a.cierre_caja_id IS NOT NULL
                                    AND a.usuario_id=$usuario
                                    AND a.caja_id=b.caja_id
                                    AND b.caja_id=c.caja_id
                                    AND a.usuario_id=c.usuario_id
                                    AND a.prefijo=v.prefijo
                                    AND a.factura_fiscal=v.factura_fiscal
                                    $tipofactura
                                    AND a.cierre_caja_id NOT IN(
                                            SELECT b.cierre_caja_id
                                            FROM cierre_de_caja a,cierre_de_caja_detalle b
                                            WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                                    ORDER BY a.factura_fiscal,a.fecha_registro;";
        }
        else
        if ($tipocuenta == '03' OR $tipocuenta == '08') {
            if ($tipocuenta == '03')
                $tipofactura = "and v.tipo_factura in('5')";
            else
                $tipofactura = "and v.tipo_factura in('6')";
            //and v.estado='0'
            //
            $query = "SELECT a.empresa_id,a.centro_utilidad,a.factura_fiscal as recibo_caja,
                                    a.fecha_registro as fecha_ingcaja,b.caja_id, b.descripcion as caja,
                                    a.total_efectivo,a.total_cheques,a.total_bonos, a.total_tarjetas,a.usuario_id,
                                    a.prefijo,
                  (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma,
                                    CASE WHEN v.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
                                    FROM fac_facturas v, fac_facturas_contado a,cajas_rapidas b,
                                    userpermisos_cajas_rapidas c
                                    WHERE a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                    and a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                    and a.cierre_caja_id ISNULL
                                    and a.usuario_id=$usuario
                                    and a.caja_id=b.caja_id
                                    and b.caja_id=c.caja_id
                                    and a.usuario_id=c.usuario_id
                                    and a.prefijo=v.prefijo
                                    and a.factura_fiscal=v.factura_fiscal
                                    $tipofactura
                                    order by a.factura_fiscal,a.fecha_registro;";
        }
        else
        if ($_SESSION['CAJA']['CIERRE']['DEPTO']) {
            //caso de cierre de cajas rapidas.
            $dpto = $_SESSION['CAJA']['CIERRE']['DEPTO'];

            /* $query="select a.empresa_id,a.centro_utilidad,a.recibo_caja,a.fecha_ingcaja,b.caja_id,
              b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
              a.total_tarjetas,a.usuario_id,a.prefijo,
              (a.total_efectivo + a.total_cheques + a.total_tarjetas) as suma
              from recibos_caja a,cajas_rapidas b,userpermisos_cajas_rapidas c where a.caja_id=b.caja_id and a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
              and a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."' and a.cierre_caja_id ISNULL
              and a.sw_facturado =1 and b.departamento='$dpto'  AND a.usuario_id=".UserGetUID()."
              and c.caja_id=a.caja_id and a.usuario_id=c.usuario_id order by a.recibo_caja"; */
            /*    $query="select a.empresa_id,a.centro_utilidad,a.factura_fiscal as recibo_caja,a.fecha_registro as fecha_ingcaja,b.caja_id,
              b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
              a.total_tarjetas,a.usuario_id,a.prefijo,e.numerodecuenta,e.sw_tipo,
              (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma
              from fac_facturas_cuentas e,fac_facturas v,
              fac_facturas_contado a,cajas_rapidas b,userpermisos_cajas_rapidas c
              where a.caja_id=b.caja_id
              and a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
              and a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
              and a.cierre_caja_id ISNULL
              and b.departamento='$dpto'
              AND a.usuario_id=".UserGetUID()."
              and c.caja_id=a.caja_id
              and a.usuario_id=c.usuario_id
              and e.factura_fiscal=a.factura_fiscal
              and e.prefijo=a.prefijo
              and e.factura_fiscal=v.factura_fiscal
              and e.prefijo=v.prefijo
              and v.estado='0'
              and e.sw_tipo=0
              order by a.factura_fiscal,a.fecha_registro"; */
            if ($sw_cuadrada) {
                //                      and v.estado='0'
                //cambio dar para agilizar el query
                $query = "SELECT a.empresa_id,a.centro_utilidad,a.factura_fiscal as recibo_caja,
                                                a.fecha_registro as fecha_ingcaja,b.caja_id, b.descripcion as caja,
                                                a.total_efectivo,a.total_cheques,a.total_bonos, a.total_tarjetas,a.usuario_id,
                                                a.prefijo,e.numerodecuenta,e.sw_tipo,
                                                (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma,
                                        CASE WHEN v.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
                                        FROM  fac_facturas_cuentas e,fac_facturas v, fac_facturas_contado a,cajas_rapidas b,
                                                userpermisos_cajas_rapidas c
                                        WHERE a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                        and a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                        and a.cierre_caja_id=$cierre
                                        and a.cierre_caja_id IS NOT NULL
                                        and a.usuario_id=$usuario
                                        and a.caja_id=$Caja
                                        and a.caja_id=b.caja_id
                                        and b.departamento='$dpto'
                                        and b.caja_id=c.caja_id
                                        and a.usuario_id=c.usuario_id
                                        and a.prefijo=v.prefijo
                                        and a.factura_fiscal=v.factura_fiscal
                                        and e.factura_fiscal=v.factura_fiscal
                                        and e.prefijo=v.prefijo
                                        AND a.cierre_caja_id NOT IN(
                                                SELECT b.cierre_caja_id
                                                FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                                        and v.tipo_factura in('0','2')
                                        order by a.factura_fiscal,a.fecha_registro;";
            } else {
                //                      and v.estado='0'
                $query = "SELECT a.empresa_id,a.centro_utilidad,a.factura_fiscal as recibo_caja,a.fecha_registro as fecha_ingcaja,b.caja_id,
                                                b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
                                                a.total_tarjetas,a.usuario_id,a.prefijo,e.numerodecuenta,e.sw_tipo,
                                                (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma,
                                                CASE WHEN v.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
                                        FROM fac_facturas_cuentas e,fac_facturas v,
                                                fac_facturas_contado a,cajas_rapidas b,userpermisos_cajas_rapidas c
                                        WHERE a.caja_id=b.caja_id
                                        and a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                        and a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                        and a.cierre_caja_id ISNULL
                                        and b.departamento='$dpto'
                                        AND a.usuario_id=$usuario
                                        AND a.caja_id=$Caja
                                        and c.caja_id=a.caja_id
                                        and a.usuario_id=c.usuario_id
                                        and e.factura_fiscal=a.factura_fiscal
                                        and e.prefijo=a.prefijo
                                        and e.factura_fiscal=v.factura_fiscal
                                        and e.prefijo=v.prefijo
                                        and v.tipo_factura IN('0','2')
                                        and e.sw_tipo IN('0','2')
                                        order by a.factura_fiscal,a.fecha_registro;";
                //cambio dar para agilizar el query
                /*              $query = "SELECT a.empresa_id,a.centro_utilidad,a.factura_fiscal as recibo_caja,
                  a.fecha_registro as fecha_ingcaja,b.caja_id, b.descripcion as caja,
                  a.total_efectivo,a.total_cheques,a.total_bonos, a.total_tarjetas,a.usuario_id,
                  a.prefijo,
                  (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma
                  FROM fac_facturas v, fac_facturas_contado a,cajas_rapidas b,
                  userpermisos_cajas_rapidas c
                  WHERE a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
                  AND a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
                  AND a.cierre_caja_id ISNULL
                  AND a.usuario_id=$usuario
                  AND a.caja_id=$Caja
                  AND a.caja_id=b.caja_id
                  AND b.departamento='$dpto'
                  AND b.caja_id=c.caja_id
                  AND a.usuario_id=c.usuario_id
                  AND a.prefijo=v.prefijo
                  AND a.factura_fiscal=v.factura_fiscal
                  AND v.estado='0'
                  AND v.tipo_factura in('0','2')
                  ORDER BY a.factura_fiscal,a.fecha_registro;"; */
            }
        } else
        if ($sw_cuadrada) {
            $fecha = date('Y-m-d');
//                      AND DATE(c.fecha_registro)='".$fecha."'
            $query = " SELECT  DISTINCT a.empresa_id,a.centro_utilidad,a.recibo_caja,a.fecha_ingcaja,
                            b.caja_id, b.descripcion as caja, a.total_efectivo,a.total_cheques,
                            a.total_bonos, a.total_tarjetas,a.usuario_id,a.prefijo,
                            (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma,
                            CASE WHEN a.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
                    FROM recibos_caja a, cajas b, recibos_caja_cierre c
                    WHERE a.caja_id=b.caja_id AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                        AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                        AND a.cierre_caja_id IS NOT NULL
                        AND a.cierre_caja_id=c.cierre_caja_id
                        AND a.cierre_caja_id=$cierre
                        AND c.cierre_caja_id NOT IN(
                                SELECT b.cierre_caja_id
                                FROM cierre_de_caja a,cierre_de_caja_detalle b
                                WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                        AND a.caja_id=$Caja  AND a.usuario_id=" . $usuario . "
                        AND a.estado IN ('0','1')
                        ORDER BY a.recibo_caja,a.fecha_ingcaja;";
        } else {
            /*     $query=" SELECT DISTINCT a.empresa_id,a.centro_utilidad,a.recibo_caja,a.fecha_ingcaja,b.caja_id,
              b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
              a.total_tarjetas,a.usuario_id,a.prefijo,
              (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma
              FROM cajas b,recibos_caja a, rc_devoluciones c
              WHERE a.caja_id=b.caja_id
              AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
              AND a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
              AND a.prefijo NOT IN(c.prefijo)
              AND a.recibo_caja NOT IN(c.recibo_caja)
              AND a.cierre_caja_id ISNULL
              AND a.caja_id=$Caja
              AND a.usuario_id=".UserGetUID()."
              ORDER BY a.recibo_caja,a.fecha_ingcaja"; */
            $query = " SELECT  DISTINCT a.empresa_id,a.centro_utilidad,a.recibo_caja,a.fecha_ingcaja,
                                        b.caja_id, b.descripcion as caja, a.total_efectivo,a.total_cheques,
                                        a.total_bonos, a.total_tarjetas,a.usuario_id,a.prefijo,
                                        (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma,
                                        CASE WHEN a.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
                                            FROM recibos_caja a, cajas b
                                    WHERE a.caja_id=b.caja_id AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                        AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                        AND a.cierre_caja_id ISNULL
                                        AND a.caja_id=$Caja  AND a.usuario_id=" . $usuario . "
                                        AND a.estado IN ('0','1')
                                        ORDER BY a.recibo_caja,a.fecha_ingcaja";
        }
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer los recibos de caja para cierre";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while (!$resulta->EOF) {
            $var[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function TraerReciboPagares($Caja, $usuario_id, $sw_cuadrada) {
        list($dbconn) = GetDBconn();
        if ($sw_cuadrada) {
            $query = "SELECT a.empresa_id,a.centro_utilidad,a.recibo_caja,a.fecha_ingcaja,b.caja_id,
                                            b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
                                            a.total_tarjetas,a.usuario_id,a.prefijo,
                                            (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma
                            FROM recibos_caja a, cajas b, rc_detalle_pagare c
                            WHERE a.caja_id=b.caja_id
                                     AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                     AND a.cierre_caja_id IS NOT NULL
                                     AND a.caja_id=$Caja
                                     AND a.usuario_id=$usuario_id
                                     AND a.empresa_id=c.empresa_id
                                     AND a.prefijo=c.prefijo
                                     AND a.recibo_caja=c.recibo_caja
                                     AND a.cierre_caja_id NOT IN(
                                                SELECT b.cierre_caja_id
                                                FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                                     AND a.estado IN ('0')
                                     ORDER BY a.recibo_caja,a.fecha_ingcaja;";
        } else {
            $query = "SELECT a.empresa_id,a.centro_utilidad,a.recibo_caja,a.fecha_ingcaja,b.caja_id,
                                            b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
                                            a.total_tarjetas,a.usuario_id,a.prefijo,
                                            (a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma
                            FROM recibos_caja a, cajas b, rc_detalle_pagare c
                            WHERE a.caja_id=b.caja_id
                                     AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                     AND a.cierre_caja_id ISNULL
                                     AND a.caja_id=$Caja
                                     AND a.usuario_id=$usuario_id
                                     AND a.empresa_id=c.empresa_id
                                     AND a.prefijo=c.prefijo
                                     AND a.recibo_caja=c.recibo_caja
                                     AND a.estado IN ('0')
                                     ORDER BY a.recibo_caja,a.fecha_ingcaja;";
        }
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer los recibos de caja para cierre";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while (!$resulta->EOF) {
            $var[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function TraerDevoluciones($Caja, $usuario_id, $sw_cuadrada, $cierre) {
        list($dbconn) = GetDBconn();
        if ($sw_cuadrada) {
            //                          AND DATE(d.fecha_registro)='".$fecha."'
            $fecha = date('Y-m-d');
            $query = "SELECT a.empresa_id,a.centro_utilidad,a.numerodecuenta,a.fecha_registro,b.caja_id,
                                                        a.usuario_id,a.prefijo,a.recibo_caja,a.total_devolucion,b.descripcion
                                FROM rc_devoluciones a,cajas b, cuentas c,
                                            rc_devoluciones_cierre d
                                WHERE a.caja_id=b.caja_id
                                            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                            AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                            AND a.caja_id=$Caja AND a.usuario_id=" . $usuario_id . "
                                            AND a.numerodecuenta=c.numerodecuenta
                                            AND a.cierre_caja_id IS NOT NULL
                                            AND a.cierre_caja_id=d.cierre_caja_id
                                            AND a.cierre_caja_id=$cierre
                                            AND d.cierre_caja_id NOT IN(
                                                    SELECT b.cierre_caja_id
                                                    FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                    WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                                            ORDER BY a.recibo_caja,a.fecha_registro";
        } else {
            $query = "SELECT a.empresa_id,a.centro_utilidad,a.numerodecuenta,a.fecha_registro,b.caja_id,
                                                        a.usuario_id,a.prefijo,a.recibo_caja,a.total_devolucion,b.descripcion
                                FROM rc_devoluciones a,cajas b, cuentas c
                                WHERE a.caja_id=b.caja_id
                                            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                            AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                            AND a.caja_id=$Caja AND a.usuario_id=" . $usuario_id . "
                                            AND a.numerodecuenta=c.numerodecuenta
                                            AND a.cierre_caja_id ISNULL
                                            ORDER BY a.recibo_caja,a.fecha_registro";
        }
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las devoluciones de caja para cierre";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while (!$resulta->EOF) {
            $var[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function TraerPacienteDevolucion($Caja, $recibo, $prefijo, $usuario_id) {
        list($dbconn) = GetDBconn();
        $query = "SELECT trim(d.primer_nombre||' '||d.segundo_nombre||' ' ||
                            d.primer_apellido||' '||d.segundo_apellido,'') as nombre,
                            d.tipo_id_paciente||' '||d.paciente_id as id
                        FROM rc_devoluciones a,cajas b, cuentas c, pacientes d, ingresos e
                        WHERE a.caja_id=b.caja_id AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                    AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                    AND a.caja_id=$Caja AND a.usuario_id=" . $usuario_id . "
                                    AND a.numerodecuenta=c.numerodecuenta
                                    AND c.ingreso=e.ingreso
                                    AND e.tipo_id_paciente=d.tipo_id_paciente
                                    AND e.paciente_id=d.paciente_id
                                    AND a.recibo_caja=$recibo
                                    AND a.prefijo='" . $prefijo . "'";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las devoluciones de caja para cierre";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var[] = $resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
        return $var;
    }

    /* funcion que saca el departamento y centro utilidad segn el usuario de sistema */

//function TraerDpto($caja,$dpto)
    function TraerDpto($tipocuenta) {
        list($dbconn) = GetDBconn();

        /*      if($tipocuenta!='03' AND $tipocuenta!='05')
          {
          $query="select b.departamento,b.centro_utilidad,
          b.descripcion
          from system_usuarios_departamentos a,departamentos b
          where a.departamento=b.departamento
          and a.usuario_id=".UserGetUID()." order by b.descripcion ";
          }
          else
          if($tipocuenta=='05')
          { */
        $query = "select b.departamento,b.centro_utilidad,
              b.descripcion as dessdpto, d.caja_id,d.descripcion
              from system_usuarios_departamentos a,departamentos b,
                                    userpermisos_cajas_rapidas c, cajas_rapidas d
              where a.departamento=b.departamento
              and a.usuario_id=" . UserGetUID() . "
                            AND a.usuario_id=c.usuario_id
                            AND c.caja_id=d.caja_id
                            AND b.departamento=d.departamento
                            AND b.empresa_id = '" . $_REQUEST['Empresa'] . "'
                            AND b.centro_utilidad = '" . $_REQUEST['CentroUtilidad'] . "'
                            order by b.descripcion ";
        /*      }
          else
          {
          $query="select b.departamento,b.centro_utilidad,
          b.descripcion
          from system_usuarios_departamentos a,departamentos b,
          userpermisos_cajas_rapidas c, cajas_rapidas d
          where a.departamento=b.departamento
          and a.usuario_id=".UserGetUID()."
          AND a.usuario_id=c.usuario_id
          AND d.cuenta_tipo_id='03'
          AND c.caja_id=d.caja_id
          AND b.departamento=d.departamento
          order by b.descripcion ";
          } */
        $resulta = $dbconn->execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Buscar el departamento";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i = 0;
        while (!$resulta->EOF) {
            $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function TraerBodega($tipocuenta) {
        list($dbconn) = GetDBconn();
        $query = "SELECT b.bodega,b.centro_utilidad,
                            b.descripcion as desbodega, d.caja_id,
                            d.descripcion as descaja
                        FROM userpermisos_caja_inventarios a,bodegas b,
                                    userpermisos_cajas_rapidas c, cajas_rapidas d
                        WHERE a.bodega=b.bodega
                            AND c.usuario_id=" . UserGetUID() . "
                            AND a.caja_id=c.caja_id
                            AND c.caja_id=d.caja_id
                            ORDER BY b.descripcion; ";
        $resulta = $dbconn->execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Buscar el departamento";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i = 0;
        while (!$resulta->EOF) {
            $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function IrListadoCierre() {
        if ($_REQUEST['TipoCuenta'] == '01' OR $_REQUEST['TipoCuenta'] == '02') {
            $criterio = 2;
            //$this->ListadoCerrarCaja($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU']);
            //'Caja'=>$Caja,'Empresa'=>$empresa,'CentroUtilidad'=>$centro,'arreglo'=>$tipo,'TipoCuenta'=>$tipocuenta,'facturacion'=>$fact,'CU'=>$cu,'SWCUENTAS'=>'Cuentas'
            $this->Busqueda($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $criterio);
            return true;
        }
        if ($_REQUEST['TipoCuenta'] == '03') {
            $criterio = 1;
            //$this->ListadoCerrarCaja($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta']);
            //$this->MenuDpto($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta'],$_REQUEST['CU'],$criterio);
            $this->Busqueda($_REQUEST['Cajaid'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $criterio);
            return true;
        }
        if ($_REQUEST['TipoCuenta'] == '04') {
            $criterio = 1;
            //$this->ListadoCerrarCaja($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta']);
            $this->Busqueda($_REQUEST['Cajaid'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], '', $criterio);
            return true;
        }
        if ($_REQUEST['TipoCuenta'] == '05') {
            $criterio = 1;
            //$this->ListadoCerrarCaja($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta'],$_REQUEST['CU']);
            //debe filtrar el departamento ya que los cierres de las cajas rapidas se efectuan por departamentos.
            $this->MenuDpto($_REQUEST['Cajaid'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $criterio);
            return true;
        }
        if ($_REQUEST['TipoCuenta'] == '06') {
            $criterio = 2; //LOS PAGARES GENERAN PAGOS
            //QUE SE GUARDAN EN RECIBOS DE CAJA
            $this->Busqueda($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $criterio);
            return true;
        }
        if ($_REQUEST['TipoCuenta'] == '08') {
            $criterio = 1;
            //$this->ListadoCerrarCaja($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta']);
            $this->Busqueda($_REQUEST['Cajaid'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $criterio);
            return true;
        }
    }

    function BusquedaCierre($Caja, $Empresa, $CentroUtilidad, $arreglo, $TipoCuenta, $CU) {
        $_REQUEST['criterio'] = '2';
        if ($_REQUEST['criterio'] == '2') { //caja hospitalarias..2

            list($dbconn) = GetDBconn();
            $fecha = date('Y-m-d');
            $query = "SELECT  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
                        d.descripcion as des
                        FROM recibos_caja a,cajas b,system_usuarios d ,
                        cajas_usuarios c, recibos_caja_cierre e
                        WHERE   a.caja_id=$Caja
                        AND a.caja_id=b.caja_id
                        AND a.cierre_caja_id is not null
                        AND a.cierre_caja_id=e.cierre_caja_id
                        AND a.caja_id=c.caja_id
                        AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                        AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                        AND c.usuario_id=d.usuario_id
                        AND c.usuario_id=a.usuario_id
                        AND b.cuenta_tipo_id='01'
                        AND e.cierre_caja_id NOT IN(
                                SELECT b.cierre_caja_id
                                FROM cierre_de_caja a,cierre_de_caja_detalle b
                                WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                        AND DATE(e.fecha_registro)='" . $fecha . "'
                        AND a.estado IN ('0')
                        ORDER BY b.descripcion ";
            //colocarle el filtro de la fecha de hoy
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al traer la consulta de los cierres";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $i = 0;

            if ($resulta->EOF) {
                $this->BusquedaCajasHoy('show', '', $Caja, '', '', '', $TipoCuenta, $CU);
                return true;
            }

            while (!$resulta->EOF) {
                $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
                $i++;
            }
            $this->BusquedaCajasHoy($var, 2, $Caja, $Empresa, $CentroUtilidad, $arreglo, $TipoCuenta, $CU);
            return true;
        }
    }

//CUADRE
    /*
     * funcion que realiza las busqueda de losn cierres que se hacen en la fecha actual.
     */
    function Busqueda($Caja, $Empresa, $CentroUtilidad, $arreglo, $TipoCuenta, $CU, $criterio) {

        if (empty($Caja))
        //$Caja=$_SESSION['caja']['Caja'];
            $Caja = $_REQUEST['Cajaid'];
        else
            $_SESSION['caja']['Caja'] = $Caja;
        if (empty($TipoCuenta))
            $TipoCuenta = $_REQUEST['TipoCuenta'];
        if (!empty($criterio))
            $_REQUEST['criterio'] = $criterio;

        //$_REQUEST['criterio']='1';
        //AND DATE(a.fecha_registro)='".$fecha."'

        $fecha = date('Y-m-d');
        list($dbconn) = GetDBconn();
        //CAJA PAGARES--BUSQUEDA DE USUARIOS DE CAJA PAGARES SIN CUADRAR
        if ($_REQUEST['criterio'] == '2' AND $TipoCuenta == '06') {
			//-21-
            //USUARIOS CON PAGARES SIN CUADRAR
            $query = "SELECT DISTINCT c.usuario_id,e.caja_id,
                                                c.nombre,b.descripcion,
                                                c.descripcion as des
                                            FROM pagares a, cajas b, system_usuarios c,
                                                cajas_usuarios e,
                                                rc_detalle_pagare f, recibos_caja g
                                            WHERE a.empresa_id=f.empresa_id
                                            AND a.prefijo=f.prefijo_pagare
                                            AND a.numero=f.numero
                                            AND f.empresa_id=g.empresa_id
                                            AND f.centro_utilidad=g.centro_utilidad
                                            AND f.recibo_caja=g.recibo_caja
                                            AND f.prefijo=g.prefijo
                                            AND g.caja_id=b.caja_id
                                            AND e.caja_id=b.caja_id
                                            AND e.usuario_id=c.usuario_id
                                            AND b.caja_id=$Caja
                                            AND g.cierre_caja_id IS NULL
                                            AND g.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                            AND g.estado='0'
                                            AND g.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                            AND g.usuario_id=c.usuario_id
                                            AND c.usuario_id=e.usuario_id
                                            ORDER BY b.descripcion;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al traer la consulta de los cierres";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
			//-21-
            //FIN USUARIOS CON PAGARES SIN CUADRAR
            //USUARIOS CON PAGARES CUADRARADOS PERO SIN CERRAR
            if ($resulta->EOF) {
                $query = "SELECT DISTINCT c.usuario_id,e.caja_id,
                                                    c.nombre,b.descripcion,
                                                    c.descripcion as des
                                                FROM pagares a, cajas b, system_usuarios c,
                                                    cajas_usuarios e,
                                                    rc_detalle_pagare f, recibos_caja g
                                                WHERE a.empresa_id=f.empresa_id
                                                AND a.prefijo=f.prefijo_pagare
                                                AND a.numero=f.numero
                                                AND f.empresa_id=g.empresa_id
                                                AND f.centro_utilidad=g.centro_utilidad
                                                AND f.recibo_caja=g.recibo_caja
                                                AND f.prefijo=g.prefijo
                                                AND g.caja_id=b.caja_id
                                                AND e.caja_id=b.caja_id
                                                AND e.usuario_id=c.usuario_id
                                                AND b.caja_id=$Caja
                                                AND g.cierre_caja_id IS NOT NULL
                                                AND g.cierre_caja_id NOT IN
                                                    (
                                                        SELECT b.cierre_caja_id
                                                        FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                        WHERE a.cierre_de_caja_id=b.cierre_de_caja_id
                                                    )
                                                AND g.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                                AND g.estado='0'
                                                AND g.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                                AND g.usuario_id=c.usuario_id
                                                AND c.usuario_id=e.usuario_id
                                                ORDER BY b.descripcion;";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al traer la consulta de los cierres";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                if ($resulta->EOF) {
                    $this->BusquedaCajasHoy('show', '', $Caja, '', '', '', $TipoCuenta, $CU);
                    return true;
                }

                $i = 0;
                while (!$resulta->EOF) {
                    $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                    $i++;
                }
                $this->BusquedaCajasHoy($var, 2, $Caja, $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $arreglo, $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $dpto, $_REQUEST['Caja_empresa']);
                return true;
            }
            //FIN USUARIOS CON PAGARES CUADRARADOS PERO SIN CERRAR

            $i = 0;
            while (!$resulta->EOF) {
                $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
                $i++;
            }
            $this->BusquedaCajasHoy($var, 2, $Caja, $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $arreglo, $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $dpto, $_REQUEST['Caja_empresa']);
            return true;
        } else
        //FIN CAJA PAGARES
        if ($_REQUEST['criterio'] == '2') { //caja hospitalarias..2
            //list($dbconn) = GetDBconn();
            $query = "SELECT  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
                        d.descripcion as des
                        FROM recibos_caja a,cajas b,system_usuarios d ,
                        cajas_usuarios c
                        WHERE   a.caja_id=$Caja
                        AND a.caja_id=b.caja_id
                        AND a.cierre_caja_id isnull
                        AND a.caja_id=c.caja_id
                        AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                        AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                        AND c.usuario_id=d.usuario_id
                        AND c.usuario_id=a.usuario_id
                        AND b.cuenta_tipo_id='01'
                        AND a.estado IN ('0')
                        ORDER BY b.descripcion;";
            //colocarle el filtro de la fecha de hoy
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al traer la consulta de los cierres";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $i = 0;
            //VERIFICAR SI HAY CUADRE SIN CERRAR DE LA CAJA
//                                          AND DATE(e.fecha_registro)='".$fecha."'
            if ($resulta->EOF) {
                $query = "SELECT  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
                                                    d.descripcion as des
                                                    FROM recibos_caja a,cajas b,system_usuarios d ,
                                                    cajas_usuarios c, recibos_caja_cierre e
                                            WHERE   a.caja_id=$Caja
                                            AND a.caja_id=b.caja_id
                                            AND a.cierre_caja_id is NOT null
                                            AND a.cierre_caja_id=e.cierre_caja_id
                                            AND a.caja_id=c.caja_id
                                            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                            AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                            AND c.usuario_id=d.usuario_id
                                            AND c.usuario_id=a.usuario_id
                                            AND b.cuenta_tipo_id='01'
                                            AND e.cierre_caja_id NOT IN(
                                                    SELECT b.cierre_caja_id
                                                    FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                    WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                                            AND a.estado IN ('0')
                                            ORDER BY b.descripcion;";
                //colocarle el filtro de la fecha de hoy
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al traer la consulta de los cierres";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                if ($resulta->EOF) {
                    $this->BusquedaCajasHoy('show', '', $Caja, '', '', '', $TipoCuenta, $CU);
                    return true;
                }
                while (!$resulta->EOF) {
                    $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                    $i++;
                }
                $this->BusquedaCajasHoy($var, 2, $Caja, $Empresa, $CentroUtilidad, $arreglo, $TipoCuenta, $CU);
                return true;
            }//fin del if
            //FIN VERIFICAR SI HAY CUADRE SIN CERRAR DE LA CAJA
            while (!$resulta->EOF) {
                $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
                $i++;
            }
            $this->BusquedaCajasHoy($var, 2, $Caja, $Empresa, $CentroUtilidad, $arreglo, $TipoCuenta, $CU);
            return true;
        }
        //parte de las cajas facturadoras.
        elseif (($_REQUEST['criterio'] == '1' AND $TipoCuenta == '03') OR ($_REQUEST['criterio'] == '1' AND $TipoCuenta == '08')) {
        //CAJAS DE CONCEPTOS OR CAJA INVENTARIOS
            $dpto = $_REQUEST['departamento'];
            //if($_REQUEST['departamento']=='/a/')
            if ($_REQUEST['departamento'] == "") {
                $search_dpto = '';
            } else {
                $search_dpto = "AND b.departamento='$dpto'";
            }
            //$Caja=$_REQUEST['Cajaid'];
            //list($dbconn) = GetDBconn();
            /* select DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,b.departamento, d.descripcion as des
              FROM fac_facturas_contado a,cajas_rapidas b,system_usuarios d , userpermisos_cajas_rapidas c
              where a.caja_id=b.caja_id and a.cierre_caja_id is not null and a.caja_id=c.caja_id and a.empresa_id='01' and a.centro_utilidad='01' and c.usuario_id=d.usuario_id and c.usuario_id=a.usuario_id order by b.descripcion */
            //AND DATE(a.fecha_registro)='".$fecha."'
            $query = "SELECT  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
                                                            d.descripcion as des
                                    FROM fac_facturas_contado a,cajas_rapidas b,system_usuarios d ,
                                    userpermisos_cajas_rapidas c
                                    WHERE a.caja_id=b.caja_id
                                    AND a.caja_id=$Caja
                                    AND a.cierre_caja_id IS NULL
                                    AND a.caja_id=c.caja_id
                                    AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                    AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                    AND c.usuario_id=d.usuario_id
                                    AND c.usuario_id=a.usuario_id $search_dpto
                                    ORDER BY b.descripcion;";
            //colocarle el filtro de la fecha de hoy
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al traer la consulta de los cierres";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $i = 0;

            //VERIFICAR SI HAY CUADRE SIN CERRAR DE LA CAJA DE CONCEPTOS
            if ($resulta->EOF) {
                $query = "SELECT  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
                                                            d.descripcion as des
                                    FROM fac_facturas_contado a,cajas_rapidas b,system_usuarios d ,
                                        userpermisos_cajas_rapidas c, recibos_caja_cierre e
                                    WHERE a.caja_id=b.caja_id
                                    AND a.caja_id=$Caja
                                    AND a.cierre_caja_id IS NOT NULL
                                    AND a.cierre_caja_id=e.cierre_caja_id
                                    AND a.caja_id=c.caja_id
                                    AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                    AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                    AND c.usuario_id=d.usuario_id
                                    AND c.usuario_id=a.usuario_id $search_dpto
                                    AND a.cierre_caja_id NOT IN(
                                            SELECT b.cierre_caja_id
                                            FROM cierre_de_caja a,cierre_de_caja_detalle b
                                            WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                                    ORDER BY b.descripcion;";
                $resulta = $dbconn->Execute($query);
                if ($resulta->EOF) {
                    $this->BusquedaCajasHoy('show', '', $Caja, '', '', '', $TipoCuenta, $CU);
                    return true;
                }

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al traer la consulta de los cierres";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $i = 0;
                while (!$resulta->EOF) {
                    $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                    $i++;
                }
                //                                              //$Caja,$Empresa,$CentroUtilidad,$arreglo,$TipoCuenta,$CU,$criterio
                $this->BusquedaCajasHoy($var, 1, $Caja, $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $arreglo, $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $dpto);
                //$this->BusquedaCajasHoy($var,1);
                return true;
                /* $this->BusquedaCajasHoy('show');
                  return true; */
            }

            while (!$resulta->EOF) {
                $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
                $i++;
            }

            //$URL=ModuloGetURL('app','CajaGeneral','user','Busqueda',array("Cajaid"=>$Caja,"Empresa"=>$empresa,"dpto"=>$dpto,"CentroUtilidad"=>$centro,"Tiponumeracion"=>$tipo,"TipoCuenta"=>$tipocuenta,"CU"=>$cu));
            $this->BusquedaCajasHoy($var, 1, $Caja, $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $arreglo, $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $dpto);
            //$this->BusquedaCajasHoy($var,1);
            return true;
        } elseif ($_REQUEST['criterio'] == '1') {//cajas facturadoras..
            $dpto = $_REQUEST['departamento'];
            if ($_REQUEST['departamento'] == '/a/') {
            //if($_REQUEST['departamento']=="")
                $search_dpto = '';
            } else {
                $search_dpto = "AND b.departamento='$dpto'";
            }
            if (!empty($_REQUEST['Cajaid']))
                $Caja = $_REQUEST['Cajaid'];
            else
            if (!empty($_REQUEST['Caja']))
                $Caja = $_REQUEST['Caja'];
            //list($dbconn) = GetDBconn();
            /* select DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,b.departamento, d.descripcion as des
              FROM fac_facturas_contado a,cajas_rapidas b,system_usuarios d , userpermisos_cajas_rapidas c
              where a.caja_id=b.caja_id and a.cierre_caja_id is not null and a.caja_id=c.caja_id and a.empresa_id='01' and a.centro_utilidad='01' and c.usuario_id=d.usuario_id and c.usuario_id=a.usuario_id order by b.descripcion */
            //AND DATE(a.fecha_registro)='".$fecha."'
            $query = "SELECT  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,b.departamento,
                                                    d.descripcion as des
                            FROM fac_facturas_contado a,cajas_rapidas b,system_usuarios d ,
                            userpermisos_cajas_rapidas c, fac_facturas e
                            WHERE a.caja_id=b.caja_id
                            AND a.caja_id=$Caja
                            AND a.cierre_caja_id IS NULL
                            AND a.caja_id=c.caja_id
                            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                            AND a.prefijo=e.prefijo
                            AND a.factura_fiscal=e.factura_fiscal
                            AND e.estado='0'
                            AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                            AND c.usuario_id=d.usuario_id
                            AND c.usuario_id=a.usuario_id $search_dpto

                            ORDER BY b.descripcion;";
            //colocarle el filtro de la fecha de hoy
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al traer la consulta de los cierres";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $i = 0;

            //VERIFICAR SI HAY CUADRE SIN CERRAR DE LA CAJA
            if ($resulta->EOF) {
                $query = "SELECT  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,b.departamento,
                                                        d.descripcion as des
                                FROM fac_facturas_contado a,cajas_rapidas b,system_usuarios d ,
                                    userpermisos_cajas_rapidas c, recibos_caja_cierre e, fac_facturas f
                                WHERE a.caja_id=b.caja_id
                                AND a.caja_id=$Caja
                                AND a.cierre_caja_id IS NOT NULL
                                AND a.cierre_caja_id=e.cierre_caja_id
                                AND a.caja_id=c.caja_id
                                AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                AND c.usuario_id=d.usuario_id
                                AND c.usuario_id=a.usuario_id $search_dpto
                                AND a.cierre_caja_id NOT IN(
                                        SELECT b.cierre_caja_id
                                        FROM cierre_de_caja a,cierre_de_caja_detalle b
                                        WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                                AND a.prefijo=f.prefijo
                                AND a.factura_fiscal=f.factura_fiscal
                                AND f.estado='0'

                                ORDER BY b.descripcion;";
                $resulta = $dbconn->Execute($query);
                if ($resulta->EOF) {
                    $this->BusquedaCajasHoy('show', '', $Caja, '', '', '', $TipoCuenta, $CU, $dpto, $_REQUEST['Caja_empresa']);
                    return true;
                }

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al traer la consulta de los cierres";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $i = 0;
                while (!$resulta->EOF) {
                    $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                    $i++;
                }

                //$URL=ModuloGetURL('app','CajaGeneral','user','Busqueda',array("Cajaid"=>$Caja,"Empresa"=>$empresa,"dpto"=>$dpto,"CentroUtilidad"=>$centro,"Tiponumeracion"=>$tipo,"TipoCuenta"=>$tipocuenta,"CU"=>$cu));
                //$this->BusquedaCajasHoy($var,2,$Caja,$Empresa,$CentroUtilidad,$arreglo,$TipoCuenta,$CU);
                $this->BusquedaCajasHoy($var, 1, $Caja, $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $arreglo, $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $dpto, $_REQUEST['Caja_empresa']);
                //$this->BusquedaCajasHoy($var,1);
                return true;
                /* $this->BusquedaCajasHoy('show');
                  return true; */
            }

            while (!$resulta->EOF) {
                $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
                $i++;
            }

            //$URL=ModuloGetURL('app','CajaGeneral','user','Busqueda',array("Cajaid"=>$Caja,"Empresa"=>$empresa,"dpto"=>$dpto,"CentroUtilidad"=>$centro,"Tiponumeracion"=>$tipo,"TipoCuenta"=>$tipocuenta,"CU"=>$cu));
            $this->BusquedaCajasHoy($var, 1, $Caja, $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $arreglo, $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $dpto, $_REQUEST['Caja_empresa']);
            //$this->BusquedaCajasHoy($var,1);
            return true;
        }

        return true;
    }

//BUSQUEDA DE USUARIOS QUE TIENEN CUADRADA LA CAJA
    /*
     * funcion que realiza las busqueda de losn cierres que se hacen en la fecha actual.
     */
    function BusquedaUsuariosCuadrados($Caja, $sw, $dpto, $TipoCuenta) {
        list($dbconn) = GetDBconn();
        $fecha = date('Y-m-d');
        if (($sw == 1 AND $TipoCuenta == '03') OR ($sw == 1 AND $TipoCuenta == '08')) {//CAJAS FACTURADORAS
            /*          echo    $query="SELECT DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
              d.descripcion as des, a.cierre_caja_id
              FROM fac_facturas_contado a,cajas b,
              system_usuarios d, cajas_usuarios c,
              recibos_caja_cierre e
              WHERE a.caja_id=b.caja_id
              AND a.caja_id=$Caja
              AND a.cierre_caja_id IS NOT NULL
              AND a.cierre_caja_id=e.cierre_caja_id
              AND a.caja_id=c.caja_id
              AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
              AND a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
              AND c.usuario_id=d.usuario_id
              AND c.usuario_id=a.usuario_id
              AND a.cierre_caja_id
              NOT IN( SELECT b.cierre_caja_id
              FROM cierre_de_caja a,cierre_de_caja_detalle b
              WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
              ORDER BY b.descripcion;";exit; */
            $query = "SELECT DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
                                            d.descripcion as des, a.cierre_caja_id
                                        FROM fac_facturas_contado a,cajas_rapidas b,
                                            system_usuarios d, userpermisos_cajas_rapidas c
                                        WHERE a.caja_id=b.caja_id
                                        AND a.caja_id=$Caja
                                        AND a.cierre_caja_id IS NOT NULL
                                        AND a.caja_id=c.caja_id
                                        AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                        AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                        AND c.usuario_id=d.usuario_id
                                        AND c.usuario_id=a.usuario_id
                                        AND a.cierre_caja_id
                                                NOT IN( SELECT b.cierre_caja_id
                                                                FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                                WHERE a.cierre_de_caja_id=b.cierre_de_caja_id);";
        }
		//-21-
        //BUSQUEDA USUARIOS CUADRADOS PAGARES
        elseif ($sw == 2 AND $TipoCuenta == '06') {
            $query = "SELECT DISTINCT c.usuario_id,e.caja_id,
                                                c.nombre,b.descripcion,
                                                c.descripcion as des, g.cierre_caja_id
                                            FROM pagares a, cajas b, system_usuarios c,
                                                cajas_usuarios e,
                                                rc_detalle_pagare f, recibos_caja g
                                            WHERE a.empresa_id=f.empresa_id
                                            AND a.prefijo=f.prefijo_pagare
                                            AND a.numero=f.numero
                                            AND f.empresa_id=g.empresa_id
                                            AND f.centro_utilidad=g.centro_utilidad
                                            AND f.recibo_caja=g.recibo_caja
                                            AND f.prefijo=g.prefijo
                                            AND g.caja_id=b.caja_id
                                            AND e.caja_id=b.caja_id
                                            AND e.usuario_id=c.usuario_id
                                            AND b.caja_id=$Caja
                                            AND g.cierre_caja_id IS NOT NULL
                                            AND g.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                            AND g.estado='0'
                                            AND g.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                            AND g.usuario_id=c.usuario_id
                                            AND c.usuario_id=e.usuario_id
                                            AND g.cierre_caja_id
                                                    NOT IN  ( SELECT b.cierre_caja_id
                                                                        FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                                        WHERE a.cierre_de_caja_id=b.cierre_de_caja_id
                                                                    );";
        } else
        //FIN BUSQUEDA USUARIOS CUADRADOS PAGARES
        if ($sw == 1) {//CAJAS FACTURADORAS//AND DATE(a.fecha_registro)='$fecha'
            //AND b.cuenta_tipo_id='01'
            /*          $query="SELECT a.usuario_id,c.caja_id,d.nombre,b.descripcion,
              d.descripcion as des, a.cierre_caja_id
              FROM cajas_rapidas b,system_usuarios d, cajas_usuarios c,
              recibos_caja_cierre a
              WHERE b.caja_id=$Caja
              AND c.caja_id=b.caja_id
              AND a.cierre_caja_id is NOT null
              AND  a.empresa_id='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
              AND a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
              AND c.usuario_id=d.usuario_id
              AND c.usuario_id=a.usuario_id
              AND a.sw_facturado='1'
              AND a.cierre_caja_id
              NOT IN(SELECT b.cierre_caja_id
              FROM cierre_de_caja a,cierre_de_caja_detalle b
              WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)

              ORDER BY b.descripcion;";exit; */
            //  AND a.fecha_registro='$fecha'
            $query = "SELECT DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
                                            b.departamento, d.descripcion as des, a.cierre_caja_id
                                        FROM fac_facturas_contado a,cajas_rapidas b,
                                            system_usuarios d,userpermisos_cajas_rapidas c,
                                            recibos_caja_cierre e
                                        WHERE a.caja_id=b.caja_id
                                        AND b.caja_id=$Caja
                                        AND a.cierre_caja_id IS NOT NULL
                                        AND a.cierre_caja_id=e.cierre_caja_id
                                        AND a.caja_id=c.caja_id
                                        AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                        AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                        AND c.usuario_id=d.usuario_id
                                        AND c.usuario_id=a.usuario_id
                                        AND b.departamento='$dpto'
                                        AND a.cierre_caja_id
                                                NOT IN( SELECT b.cierre_caja_id
                                                                FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                                WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                                        ORDER BY a.cierre_caja_id;";
        } else
        if ($sw == 2) {//CAJAS HOSPITALARIAS
//                                  AND DATE(a.fecha_registro)='$fecha'
//                                  AND b.cuenta_tipo_id='01'
            /*          $query="SELECT a.usuario_id,c.caja_id,d.nombre,b.descripcion,
              d.descripcion as des, a.cierre_caja_id
              FROM cajas b,system_usuarios d, cajas_usuarios c,
              recibos_caja_cierre a
              WHERE c.caja_id=$Caja
              AND c.caja_id=b.caja_id
              AND a.cierre_caja_id is NOT null
              AND  a.empresa_id='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
              AND a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
              AND c.usuario_id=d.usuario_id
              AND c.usuario_id=a.usuario_id
              AND a.sw_facturado='0'
              AND a.cierre_caja_id
              NOT IN(SELECT b.cierre_caja_id
              FROM cierre_de_caja a,cierre_de_caja_detalle b
              WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
              ORDER BY b.descripcion;"; */
            $query = "SELECT  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
                                                    d.descripcion as des, a.cierre_caja_id
                                                    FROM recibos_caja a,cajas b,system_usuarios d ,
                                                    cajas_usuarios c, recibos_caja_cierre e
                                            WHERE   a.caja_id=$Caja
                                            AND a.caja_id=b.caja_id
                                            AND a.cierre_caja_id is NOT null
                                            AND a.cierre_caja_id=e.cierre_caja_id
                                            AND a.caja_id=c.caja_id
                                            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                            AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                            AND c.usuario_id=d.usuario_id
                                            AND c.usuario_id=a.usuario_id
                                            AND b.cuenta_tipo_id='01'
                                            AND e.cierre_caja_id NOT IN(
                                                    SELECT b.cierre_caja_id
                                                    FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                    WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                                            AND a.estado IN ('0')
                                            ORDER BY b.descripcion;";
        }
        //colocarle el filtro de la fecha de hoy
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la consulta de los cierres";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i = 0;

//              if($resulta->EOF)
//              {
//                  $this->BusquedaCajasHoy('show');
//                  return true;
//              }

        while (!$resulta->EOF) {
            $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

//FIN BUSQUEDA DE USUARIOS QUE TIENEN CUADRADA LA CAJA
//**********************************//
//**********************************//
//**BUSQUEDA USUARIOS DESCUADRADOS**//
//**********************************//
//**********************************//
    function BusquedaUsuariosDesCuadrados($Caja, $sw, $dpto, $TipoCuenta) {
        list($dbconn) = GetDBconn();
        $fecha = date('Y-m-d');
        if (($sw == 1 AND $TipoCuenta == '03') OR ($sw == 1 AND $TipoCuenta == '08')) {
            $query = "SELECT DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
                                            d.descripcion as des
                                        FROM fac_facturas_contado a,cajas_rapidas b,
                                            system_usuarios d,userpermisos_cajas_rapidas c
                                        WHERE a.caja_id=$Caja
                                            AND a.caja_id=b.caja_id
                                            AND a.cierre_caja_id IS NULL
                                            AND a.caja_id=c.caja_id
                                            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                            AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                            AND c.usuario_id=d.usuario_id
                                            AND c.usuario_id=a.usuario_id
                                            ORDER BY b.descripcion;";
        }
		//-21-
        //BUSQUEDA USUARIOS DESCUADRADOS PAGARES
        elseif ($sw == 2 AND $TipoCuenta == '06') {
            $query = "SELECT DISTINCT c.usuario_id,e.caja_id,
                                                c.nombre,b.descripcion,
                                                c.descripcion as des, g.cierre_caja_id
                                            FROM pagares a, cajas b, system_usuarios c,
                                                cajas_usuarios e,
                                                rc_detalle_pagare f, recibos_caja g
                                            WHERE a.empresa_id=f.empresa_id
                                            AND a.prefijo=f.prefijo_pagare
                                            AND a.numero=f.numero
                                            AND f.empresa_id=g.empresa_id
                                            AND f.centro_utilidad=g.centro_utilidad
                                            AND f.recibo_caja=g.recibo_caja
                                            AND f.prefijo=g.prefijo
                                            AND g.caja_id=b.caja_id
                                            AND e.caja_id=b.caja_id
                                            AND e.usuario_id=c.usuario_id
                                            AND b.caja_id=$Caja
                                            AND g.cierre_caja_id IS NULL
                                            AND g.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                            AND g.estado='0'
                                            AND g.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                            AND g.usuario_id=c.usuario_id
                                            AND c.usuario_id=e.usuario_id;";
        } else
        //FIN BUSQUEDA USUARIOS DESCUADRADOS PAGARES
        if ($sw == 1) {
            $query = "SELECT DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
                                            b.departamento, d.descripcion as des
                                        FROM fac_facturas_contado a,cajas_rapidas b,
                                            system_usuarios d,userpermisos_cajas_rapidas c,
                                            fac_facturas e
                                        WHERE a.caja_id=$Caja
                                            AND a.caja_id=b.caja_id
                                            AND a.cierre_caja_id IS NULL
                                            AND a.caja_id=c.caja_id
                                            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                            AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                            AND c.usuario_id=d.usuario_id
                                            AND c.usuario_id=a.usuario_id
                                            AND b.departamento='$dpto'
                                            AND a.prefijo=e.prefijo
                                            AND a.factura_fiscal=e.factura_fiscal
                                            AND e.estado='0'
                                            ORDER BY b.descripcion;";
        } else
        if ($sw == 2) {
//              AND DATE(a.fecha_registro)='".$fecha."'
//              AND b.cuenta_tipo_id='01'
            $query = "SELECT  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
                d.descripcion as des
                FROM recibos_caja a,cajas b,system_usuarios d ,
                cajas_usuarios c
                WHERE   a.caja_id=$Caja
                AND a.caja_id=b.caja_id
                AND a.cierre_caja_id isnull
                AND a.caja_id=c.caja_id
                AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                AND c.usuario_id=d.usuario_id
                AND c.usuario_id=a.usuario_id
                AND a.estado IN ('0')
                ORDER BY b.descripcion;";
            //colocarle el filtro de la fecha de hoy
        }
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la consulta de los cierres";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i = 0;

//              if($resulta->EOF)
//              {
//                  $this->BusquedaCajasHoy('show');
//                  return true;
//              }

        while (!$resulta->EOF) {
            $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

//FIN BUSQUEDA USUARIOS DESCUADRADOS
    //acordar de que hay q' colocarle el filtro de la fecha de hoy
    //traer totales es solo para cajas facturadoras ok.
    function TraerTotales($uid, $caja, $cierre) {
        if (!empty($cierre)) {
            $cond = "AND a.cierre_caja_id is not null";
            $cond2 = "AND a.cierre_caja_id=$cierre";
        } else {
            $cond = "AND a.cierre_caja_id is null";
            $cond2 = "";
        }
        /*      $query="SELECT a.total_abono,a.total_cheques,a.total_efectivo,
          a.total_tarjetas,a.total_bonos
          FROM fac_facturas_contado a,fac_facturas b
          WHERE a.usuario_id=$uid
          AND a.caja_id=$caja
          $cond
          $cond2
          AND a.prefijo=b.prefijo
          AND a.factura_fiscal=b.factura_fiscal
          AND b.estado='0';"; */
        list($dbconn) = GetDBconn();
        $query = "SELECT a.total_cheques,a.total_efectivo,
                                a.total_tarjetas,a.total_bonos,
                                CASE WHEN b.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
                    FROM fac_facturas_contado a,fac_facturas b
                    WHERE a.usuario_id=$uid
                    AND a.caja_id=$caja
                    $cond
                    $cond2
                    AND a.prefijo=b.prefijo
                    AND a.factura_fiscal=b.factura_fiscal;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la consulta de los cierres";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i = 0;

        while (!$resulta->EOF) {
            $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    //acordar de que hay q' colocarle el filtro de la fecha de hoy
    //traer totales es solo para cajas hospitalarias ok.
    function TraerTotalesRecibos($uid, $caja, $TipoCuenta) {
        list($dbconn) = GetDBconn();
        $fecha = date('Y-m-d');
        if (!empty($TipoCuenta) AND $TipoCuenta == '06') {
            //ESTADO 0 --> paciente
            $query = "SELECT a.total_abono,a.total_efectivo,a.total_cheques,a.total_tarjetas,a.total_bonos
                                FROM recibos_caja a,rc_detalle_pagare b
                                WHERE a.usuario_id=$uid
                                AND a.caja_id=$caja
                                AND a.cierre_caja_id isnull
                                AND a.prefijo=b.prefijo
                                AND a.recibo_caja=b.recibo_caja
                                AND a.estado='0';";
        } else {
            //ESTADO 0 --> paciente
            $query = "SELECT CASE WHEN a.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono,
                                            a.total_efectivo,a.total_cheques,a.total_tarjetas,a.total_bonos
                                FROM recibos_caja a,rc_detalle_hosp b
                                WHERE a.usuario_id=$uid
                                AND a.caja_id=$caja
                                AND a.cierre_caja_id isnull
                                AND a.prefijo=b.prefijo
                                AND a.recibo_caja=b.recibo_caja
                                AND a.estado IN ('0','1');";
        }
        //ojo con el estado del recibo q actualmente es 0.
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la consulta de los cierres";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i = 0;

        while (!$resulta->EOF) {
            $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    //acordar de que hay q' colocarle el filtro de la fecha de hoy
    //traer totales de los recibos cuadrados por el usuario es solo para cajas hospitalarias ok.
    function TraerTotalesRecibosCuadrados($uid, $caja, $cierre) {
        /*                                  AND c.cierre_caja_id NOT IN(
          SELECT b.cierre_caja_id
          FROM cierre_de_caja a,cierre_de_caja_detalle b
          WHERE a.cierre_de_caja_id=b.cierre_de_caja_id) */
//                                  AND DATE(a.fecha_registro) ='".$fecha."'
        $fecha = date('Y-m-d');
        list($dbconn) = GetDBconn();
        $query = "SELECT a.total_efectivo,a.total_cheques,
                                                 a.total_tarjetas, a.cierre_caja_id, a.total_bonos
                                    FROM recibos_caja_cierre a, cajas_usuarios b,
                                                cajas c
                                    WHERE a.usuario_id=$uid
                                    AND a.usuario_id=b.usuario_id
                                    AND b.caja_id=$caja
                                    AND b.caja_id=c.caja_id
                                    AND a.sw_facturado='0'
                                    AND a.cierre_caja_id=$cierre
                                    AND a.cierre_caja_id NOT IN(
                                            SELECT b.cierre_caja_id
                                            FROM cierre_de_caja a,cierre_de_caja_detalle b
                                            WHERE a.cierre_de_caja_id=b.cierre_de_caja_id);";
        //ojo con el estado del recibo q actualmente es 0.
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la consulta de los cierres";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i = 0;

        while (!$resulta->EOF) {
            $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function TraerCierresCajaCuadrada($uid, $Caja, $sw, $dp, $TipoCuenta) {
        $fecha = date('Y-m-d');
//a.usuario_id=$uid AND
//AND DATE(a.fecha_registro) ='$fecha'
        list($dbconn) = GetDBconn();
        if ($sw == 1 AND $TipoCuenta == '03') {//CAJAS FACTURADORAS
            $query = "SELECT DISTINCT a.cierre_caja_id
                                        FROM fac_facturas_contado a,cajas_rapidas b,
                                            system_usuarios d,userpermisos_cajas_rapidas c,
                                            recibos_caja_cierre e,
                                            fac_facturas_conceptos f
                                        WHERE a.caja_id=b.caja_id
                                        AND a.cierre_caja_id IS NOT NULL
                                        AND a.cierre_caja_id=e.cierre_caja_id
                                        AND a.caja_id=c.caja_id
                                        AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                        AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                        AND c.usuario_id=d.usuario_id
                                        AND c.usuario_id=a.usuario_id
                                        AND a.empresa_id = f.empresa_id
                                        AND a.prefijo = f.prefijo
                                        AND a.factura_fiscal = f.factura_fiscal
                                        AND a.cierre_caja_id
                                                NOT IN( SELECT b.cierre_caja_id
                                                                FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                                WHERE a.cierre_de_caja_id=b.cierre_de_caja_id);";
            //ojo con el estado del recibo q actualmente es 0.
        } else
        if ($sw == 1) {//CAJAS FACTURADORAS
            if (!empty($dp))
                $cond = " AND b.departamento='$dp'";
            else
                $cond = "";
            /*      $query="SELECT a.cierre_caja_id
              FROM recibos_caja_cierre a, cajas_rapidas b
              WHERE b.caja_id=$Caja
              AND a.cierre_caja_id IS NOT NULL
              AND a.usuario_id=$uid
              $cond
              AND a.cierre_caja_id NOT IN(
              SELECT b.cierre_caja_id
              FROM cierre_de_caja a,cierre_de_caja_detalle b
              WHERE a.cierre_de_caja_id=b.cierre_de_caja_id);"; */
            //  AND b.departamento='$dpto'
            $query = "SELECT DISTINCT a.cierre_caja_id
                                        FROM fac_facturas_contado a,cajas_rapidas b,
                                            system_usuarios d,userpermisos_cajas_rapidas c,
                                            recibos_caja_cierre e
                                        WHERE a.caja_id=b.caja_id
                                        AND a.cierre_caja_id IS NOT NULL
                                        AND a.cierre_caja_id=e.cierre_caja_id
                                        AND a.caja_id=c.caja_id
                                        AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                        AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                        AND c.usuario_id=d.usuario_id
                                        AND c.usuario_id=a.usuario_id
                                        $cond
                                        AND a.cierre_caja_id
                                                NOT IN( SELECT b.cierre_caja_id
                                                                FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                                WHERE a.cierre_de_caja_id=b.cierre_de_caja_id);";
            //ojo con el estado del recibo q actualmente es 0.
        }
//                                  AND DATE(a.fecha_registro) ='$fecha'
        elseif ($sw == 2 AND $TipoCuenta == '06') {//CAJA PAGARES
            $query = "SELECT DISTINCT a.cierre_caja_id
                                                    FROM recibos_caja a,cajas b,system_usuarios d ,
                                                    cajas_usuarios c, recibos_caja_cierre e
                                            WHERE   a.caja_id=$Caja
                                            AND a.caja_id=b.caja_id
                                            AND a.cierre_caja_id is NOT null
                                            AND a.cierre_caja_id=e.cierre_caja_id
                                            AND a.caja_id=c.caja_id
                                            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                            AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                            AND c.usuario_id=d.usuario_id
                                            AND c.usuario_id=a.usuario_id
                                            AND b.cuenta_tipo_id='$TipoCuenta'
                                            AND e.cierre_caja_id NOT IN(
                                                    SELECT b.cierre_caja_id
                                                    FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                    WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                                            AND a.estado IN ('0');";
        } elseif ($sw == 2) {//CAJAS HOSPITALARIAS
            /*      $query="SELECT a.cierre_caja_id
              FROM recibos_caja_cierre a, cajas b
              WHERE b.caja_id=$caja
              AND a.cierre_caja_id IS NOT NULL
              AND a.usuario_id=$uid

              AND a.cierre_caja_id NOT IN(
              SELECT b.cierre_caja_id
              FROM cierre_de_caja a,cierre_de_caja_detalle b
              WHERE a.cierre_de_caja_id=b.cierre_de_caja_id);";exit; */
            /*          $query="SELECT a.cierre_caja_id
              FROM cajas b,system_usuarios d, cajas_usuarios c,
              recibos_caja_cierre a
              WHERE c.caja_id=$Caja
              AND c.caja_id=b.caja_id
              AND a.cierre_caja_id is NOT null
              AND  a.empresa_id='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
              AND a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
              AND c.usuario_id=d.usuario_id
              AND c.usuario_id=a.usuario_id
              AND b.cuenta_tipo_id='01'
              AND a.cierre_caja_id
              NOT IN(SELECT b.cierre_caja_id
              FROM cierre_de_caja a,cierre_de_caja_detalle b
              WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
              ORDER BY b.descripcion;"; */
            $query = "SELECT DISTINCT a.cierre_caja_id
                                                    FROM recibos_caja a,cajas b,system_usuarios d ,
                                                    cajas_usuarios c, recibos_caja_cierre e
                                            WHERE   a.caja_id=$Caja
                                            AND a.caja_id=b.caja_id
                                            AND a.cierre_caja_id is NOT null
                                            AND a.cierre_caja_id=e.cierre_caja_id
                                            AND a.caja_id=c.caja_id
                                            AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                            AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                            AND c.usuario_id=d.usuario_id
                                            AND c.usuario_id=a.usuario_id
                                            AND b.cuenta_tipo_id='01'
                                            AND e.cierre_caja_id NOT IN(
                                                    SELECT b.cierre_caja_id
                                                    FROM cierre_de_caja a,cierre_de_caja_detalle b
                                                    WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
                                            AND a.estado IN ('0');";
        }
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la consulta de los cierres";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i = 0;

        while (!$resulta->EOF) {
            $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }

        return $var;
    }

    //acordar de que hay q' colocarle el filtro de la fecha de hoy
    //traer totales es solo para las devoluciones de cajas hospitalarias ok.
    function TraerTotalesDevolucionesCuadradas($uid, $caja, $cierre) {
        /* SELECT a.total_abono,a.total_efectivo,a.total_tarjetas,a.total_bonos
          FROM recibos_caja a left join rc_devoluciones c on(c.empresa_id=a.empresa_id AND c.centro_utilidad=a.centro_utilidad AND c.prefijo=a.prefijo AND c.recibo_caja=a.recibo_caja AND c.cierre_caja_id ISNULL)
          WHERE a.usuario_id=2 AND a.caja_id=3 AND a.cierre_caja_id isnull AND a.estado='0' */
        $fecha = date('Y-m-d');
//                                  AND DATE(a.fecha_registro) ='".$fecha."'
        list($dbconn) = GetDBconn();
        $query = "SELECT a.total_devolucion
                                    FROM rc_devoluciones_cierre a, cajas_usuarios b,
                                                cajas c
                                    WHERE b.caja_id=$caja
                                    AND a.cierre_caja_id=$cierre
                                    AND b.caja_id =c.caja_id
                                    AND a.usuario_id =$uid
                                    AND a.usuario_id =b.usuario_id
                                    AND a.cierre_caja_id NOT IN(
                                            SELECT b.cierre_caja_id
                                            FROM cierre_de_caja a,cierre_de_caja_detalle b
                                            WHERE a.cierre_de_caja_id=b.cierre_de_caja_id);";
        //ojo con el estado del recibo q actualmente es 0.
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la consulta de los cierres";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i = 0;

        while (!$resulta->EOF) {
            $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

//CUADRE NUEVO
    //acordar de que hay q' colocarle el filtro de la fecha de hoy
    //traer totales es solo para cajas hospitalarias ok.
    function TraerTotalesDevoluciones($uid, $caja, $OtroUser) {
        /* SELECT a.total_abono,a.total_efectivo,a.total_tarjetas,a.total_bonos
          FROM recibos_caja a left join rc_devoluciones c on(c.empresa_id=a.empresa_id AND c.centro_utilidad=a.centro_utilidad AND c.prefijo=a.prefijo AND c.recibo_caja=a.recibo_caja AND c.cierre_caja_id ISNULL)
          WHERE a.usuario_id=2 AND a.caja_id=3 AND a.cierre_caja_id isnull AND a.estado='0' */
        list($dbconn) = GetDBconn();
        $fecha = date('Y-m-d');
        if (empty($OtroUser)) {
            $query = "SELECT SUM(total_devolucion) AS total_devolucion
                                        FROM rc_devoluciones
                                        WHERE usuario_id=$uid
                                        AND caja_id=$caja
                                        AND cierre_caja_id isnull
                                        AND estado='0'";
        } else {
//                                      AND DATE(fecha_registro)='".$fecha."'
            $query = "SELECT SUM(total_devolucion) AS total_devolucion
                                        FROM rc_devoluciones
                                        WHERE usuario_id=$uid
                                        AND caja_id=$caja
                                        AND cierre_caja_id isnull
                                        AND estado='0'";
        }
        //ojo con el estado del recibo q actualmente es 0.
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la consulta de los cierres";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i = 0;

        while (!$resulta->EOF) {
            $var[$i] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

//CUADRE NUEVO
    function VolverMenu() {
        if (empty($_REQUEST['Empresa']) || empty($_REQUEST['CentroUtilidad'])) {
            $_REQUEST['Empresa'] = $_SESSION['CAJA']['EMPRESA'];
            $_REQUEST['CentroUtilidad'] = $_SESSION['CAJA']['CENTROUTILIDAD'];
        }
        //esta variable de session contiene el departamento para el cierre de caja de con
        unset($_SESSION['CAJA']['CIERRE']);
        if ($_REQUEST['TipoCuenta'] == '01' OR $_REQUEST['TipoCuenta'] == '02') {
            $this->Menu($_REQUEST['Cajaid'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU']);
            return true;
        }
        if ($_REQUEST['TipoCuenta'] == '03' OR $_REQUEST['TipoCuenta'] == '04') {
            $this->Menu($_REQUEST['Cajaid'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta']);
            return true;
        }
        if ($_REQUEST['TipoCuenta'] == '05') {
            $this->Menu($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], '', $_REQUEST['Caja_empresa']);
            return true;
        }
        if ($_REQUEST['TipoCuenta'] == '06') {
            $this->Menu($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU']);
            return true;
        }
        if ($_REQUEST['TipoCuenta'] == '08') {
            $this->BuscarPermisosUser($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU']);
            return true;
        }
    }

    /* funcion que genera el pdf para despues ser mostrado como reporte */

    function GenerarListadoCuadreCaja() {
        IncludeLib("reportes/cierre_caja"); //car
        GenerarCierreCaja($_SESSION['CAJA']['VECTOR_CIERRE'], $_SESSION['CAJA']['VECTOR_CIERRE_DEV']);
        return true;
    }

//FUNCION PARA CUADRE
    function GenerarReporteCierreDeCaja() {
        IncludeLib("reportes/cierre_caja"); //car
        //GenerarReporteDeCaja($_SESSION['CAJA']['VECT_CUADRE_DE_CAJA'],$_SESSION['CAJA']['VECTOR_CUADRE_DEV']);
        GenerarReporteDeCuadreCaja($_SESSION['CAJA']['VECT_CUADRE_DE_CAJA'], $_SESSION['CAJA']['VECTOR_CUADRE_DEV'], $_SESSION['CAJA']['CUADRE']['observa']);
        return true;
    }

    /* funcion que genera el pdf para despues ser mostrado como reporte */

    //FUNCION PARA CIERRES
    function GenerarListadoCierreDeCaja() {
        IncludeLib("reportes/cierre_caja"); //car
        if (!empty($_SESSION['CAJA']['VECTOR_CUADRE_DEV1']))
            GenerarCierreDeCaja1($_SESSION['CAJA']['VECT_CIERRE_DE_CAJA'], 1, $_SESSION['CAJA']['VECTOR_CUADRE_DEV1']);
        else
            GenerarCierreDeCaja1($_SESSION['CAJA']['VECT_CIERRE_DE_CAJA'], $_SESSION['CAJA']['VECTOR_CIERRE_DEV']);
        return true;
    }

    /*
     * funcion q guarda los descuentos ya sean por cuota moderadora o por cuota paciente.
     */

    function GuardarDesc() {
        $nom = $_REQUEST['nom'];
        $id = $_REQUEST['id'];
        $tipo = $_REQUEST['id_tipo'];
        $sw_pago = $_REQUEST['sw_pago'];
        $valor = $_REQUEST['valor_pago'];
        $vector = $_REQUEST['vector'];
        $plan = $_REQUEST['plan_id'];
        $op = $_REQUEST['op'];

        if ($_REQUEST['valormodificado'] !== NULL) {
            if ($sw_pago == 1)
                $_SESSION['CAJA']['VALORCUOTAMODERADORA'] = $_REQUEST['valormodificado'];
            elseif ($sw_pago == 2)
                $_SESSION['CAJA']['VALORCOPAGO'] = $_REQUEST['valormodificado'];
            $_SESSION['CAJA']['TIPOCAMBIO'] = $_REQUEST['tipode'];
        }
        //SW_PAGO==1-->CUOTA MODERADORARA
        //SW_PAGO==2-->COPAGO
        //VARIABLES DE SESION PARA LOS VALORES A MODIFICAR EN COPAGO O CUOTA MODERADORA
        /*      if($sw_pago==1)
          $_SESSION['CAJA']['VALORCUOTAPACIENTE']=$_REQUEST['descuento'];
          elseif($sw_pago==2)
          $_SESSION['CAJA']['VALORCOPAGO']=$_REQUEST['descuento']; */
        $_SESSION['CAJA']['OBSERVACION'] = $_REQUEST['obs'];
        //FIN VARIABLES DE SESION PARA LOS VALORES A MODIFICAR EN COPAGO O CUOTA MODERADORA

        if ((empty($_REQUEST['descuento']) OR $_REQUEST['descuento'] < 1) AND $sw_pago != 1 AND $sw_pago != 2) {
            $this->frmError["MensajeError"] = "EL DESCUENTO NO PUEDE SER UN VALOR 0 O NULO";
            $this->RealizarDescuento($nom, $id, $tipo, $sw_pago, $valor, $vector, $op, $plan);
            return true;
        } elseif ((empty($_REQUEST['descuento']) OR $_REQUEST['descuento'] < 0) AND $sw_pago == 1 AND $sw_pago == 2) {
            $this->frmError["MensajeError"] = "EL DESCUENTO NO PUEDE SER UN VALOR MENOR QUE 0 O NULO";
            $this->RealizarDescuento($nom, $id, $tipo, $sw_pago, $valor, $vector, $op, $plan);
            return true;
        }

        if ($sw_pago != 1 AND $sw_pago != 2)
            if ($_REQUEST['descuento'] > $valor) {
                $this->frmError["MensajeError"] = "EL VALOR DEL DESCUENTO NO PUEDE EXCEDER EL VALOR A PAGAR";
                $this->RealizarDescuento($nom, $id, $tipo, $sw_pago, $valor, $vector, $op, $plan);
                return true;
            }

        //Para controlar el pago con cheque que no se coloque un caracter '.'
        $contador = strpos($_REQUEST['descuento'], ".");
        if ($contador != FALSE AND $contador <> "") {
            $this->frmError["MensajeError"] = "NO COLOQUE EL CARACTER . A LO QUE VA A COLOCAR EN LA CASILLA";
            $this->RealizarDescuento($nom, $id, $tipo, $sw_pago, $valor, $vector, $op, $plan);
            return true;
        }


        //unset($_SESSION['VECTOR_DESC']);
        //si llega $sw_pago en 2 es por que es cuota moderadora o el descuento se hizo sobre el
        if ($sw_pago == 2) {
            $_SESSION['VECTOR_DESC']['cuota_paciente'] = array('valormodi' => $_REQUEST['valormodificado'],
                'observacion' => $_REQUEST['obs'], 'tipo_descuento' => $_REQUEST['tipode']);
        }
        //si llega $sw_pago en 1 es por que es copago el descuento se hizo sobre el
        elseif ($sw_pago == 1) {
            $_SESSION['VECTOR_DESC']['cuota_moderadora'] = array('valormodi' => $_REQUEST['valormodificado'],
                'observacion' => $_REQUEST['obs'], 'tipo_descuento' => $_REQUEST['tipode']);
        }
        //si llega $sw_pago en 3 es por que es  el descuento del total_paciente hizo sobre el
        elseif ($sw_pago == 3) {
            $_SESSION['VECTOR_DESC']['total_paciente'] = array('valor' => $_REQUEST['descuento'],
                'observacion' => $_REQUEST['obs'], 'tipo_descuento' => $_REQUEST['tipode']);
        }
        //si llega $sw_pago en 4 es por que es total_empresa descuento se hizo sobre el
        elseif ($sw_pago == 4) {
            $_SESSION['VECTOR_DESC']['total_empresa'] = array('valor' => $_REQUEST['descuento'],
                'observacion' => $_REQUEST['obs'], 'tipo_descuento' => $_REQUEST['tipode']);
        }

        if ($_REQUEST['valormodificado'] >= 0 AND $_REQUEST['valormodificado'] != NULL) {
            $this->RetornarOrdenesServicio(1, $_REQUEST['valormodificado'], $sw_pago);
        } elseif (!empty($_REQUEST['descuento'])) {
            $this->RetornarOrdenesServicio(1, $_REQUEST['descuento'], $sw_pago);
        }
        return true;
    }

    function BuscarInformacionCita($cita) {
        list($dbconn) = GetDBconn();
        $sql = "SELECT $cita as id_cita, fecha_turno || ' ' || hora as fecha, nombre_tercero,
                    f.nombre, d.tercero_id, d.tipo_id_tercero
          FROM agenda_citas_asignadas as a, agenda_citas as b, agenda_turnos as c,
          terceros as d, os_cruce_citas as e, system_usuarios as f
          WHERE a.agenda_cita_asignada_id=e.agenda_cita_asignada_id
          AND    numero_orden_id=$cita
          AND    a.agenda_cita_id=b.agenda_cita_id
          AND b.agenda_turno_id=c.agenda_turno_id
          AND c.profesional_id=d.tercero_id
          AND c.tipo_id_profesional=d.tipo_id_tercero
          AND a.usuario_id=f.usuario_id;";
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB a: " . $dbconn->ErrorMsg();
            return false;
        }
        if ($result->RecordCount() < 1) {
            return 0;
        }
        $datos = $result->GetRowAssoc(false);
        return $datos;
    }

    //ESTE ES EL METODO PARA HACER EL CIERRE DE CAJA
    function InsertarCuadreCaja() {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $busca = "select nextval('cierre_caja_seq')";
        $resulta = $dbconn->Execute($busca);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer el serial de cierre de caja";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $serial = $resulta->fields[0];
        /*      if($_SESSION['CAJA']['CIERRE']['DEPTO'])
          {$sw_facturado=1;}else{$sw_facturado=0;} */
        //ESTA SECUENCIA ES PARA PREGUNTARLA CUANDO GENERE EL REPORTE SACAR LA OBSERVACION,FECHA REPORTE

        if (!$_REQUEST['usuario'] || !$_REQUEST['pass']) {
            if (!$_REQUEST['usuario']) {
                $this->frmError["usuario"] = 1;
            }
            if (!$_REQUEST['pass']) {
                $this->frmError["pass"] = 1;
            }
            $this->frmError["MensajeError"] = "Faltan datos de autenticacion.";

            if ($_REQUEST['TipoCuenta'] == '01' OR $_REQUEST['TipoCuenta'] == '02') {
                //$this->BusquedaCajasHoy($var,2,$Caja,$Empresa,$CentroUtilidad,$arreglo,$TipoCuenta,$CU);
                $this->CerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $arreglo, $_REQUEST['TipoCuenta'], $_REQUEST['$CU']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '03' OR $_REQUEST['TipoCuenta'] == '04' OR $_REQUEST['TipoCuenta'] == '08') {
                /*                  $this->ListadoCerrarCaja($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta'],$_REQUEST['observa']); */
                $this->CerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $arreglo, $_REQUEST['TipoCuenta'], $_REQUEST['$CU']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '05') {
//                  $this->ListadoCerrarCaja($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta'],$_REQUEST['observa']);
                $this->CerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $arreglo, $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $_REQUEST['dpto']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '06') {
//                  $this->ListadoCerrarCaja($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU'],$_REQUEST['observa']);
                $this->CerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $arreglo, $_REQUEST['TipoCuenta'], $_REQUEST['$CU']);
                return true;
            }
        }
        /*      elseif($_REQUEST['usuario']!=UserGetUID)
          {
          $this->frmError["MensajeError"]="NO SE PUEDEN CERRAR DATOS DE OTRO USUARIO.";

          if($_REQUEST['TipoCuenta']=='01' OR $_REQUEST['TipoCuenta']=='02')
          {
          $this->CerrarCaja($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$arreglo,$_REQUEST['TipoCuenta'],$_REQUEST['$CU']);
          //$this->ListadoCerrarCaja($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU'],$_REQUEST['observa']);
          return true;
          }
          if($_REQUEST['TipoCuenta']=='03' OR $_REQUEST['TipoCuenta']=='04')
          {
          $this->CerrarCaja($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta'],$_REQUEST['observa']);
          return true;
          }
          if($_REQUEST['TipoCuenta']=='05')
          {
          $this->CerrarCaja($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta'],$_REQUEST['observa']);
          return true;
          }
          if($_REQUEST['TipoCuenta']=='06')
          {
          $this->CerrarCaja($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU'],$_REQUEST['observa']);
          return true;
          }
          } */ else {
            //cambio realiado para la sos....
            $usuario_id = UserValidarUsuario($_REQUEST['usuario'], $_REQUEST['pass']);

            if ($usuario_id != UserGetUID()) {
                if (!$_REQUEST['usuario']) {
                    $this->frmError["usuario"] = 1;
                }
                if (!$_REQUEST['pass']) {
                    $this->frmError["pass"] = 1;
                }
                $this->frmError["MensajeError"] = "Identificacion incorrecta,Si ha olvidado su contrase�  contacte al administrador del sistema .";

                if ($_REQUEST['TipoCuenta'] == '01' OR $_REQUEST['TipoCuenta'] == '02') {
                    $this->CerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $arreglo, $_REQUEST['TipoCuenta'], $_REQUEST['$CU']);
                    //$this->ListadoCerrarCaja($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU'],$_REQUEST['observa']);
                    return true;
                }
                if ($_REQUEST['TipoCuenta'] == '03' OR $_REQUEST['TipoCuenta'] == '04' OR $_REQUEST['TipoCuenta'] == '08') {
                    $this->CerrarCaja($_REQUEST['Cajaid'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['observa']);
                    return true;
                }
                if ($_REQUEST['TipoCuenta'] == '05') {
                    $this->CerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['observa']);
                    return true;
                }
                if ($_REQUEST['TipoCuenta'] == '06') {
                    $this->CerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $_REQUEST['observa']);
                    return true;
                }
            }

            $entrega = $_SESSION['CIERRE']['ta'] - $_SESSION['CIERRE']['totaldev'];
            $usuario = UserGetUID();

            $_SESSION['CAJA']['CIERRE_TOTAL']['SEQ'] = $serial;
            if (empty($_SESSION['CIERRE']['caja']))
                $_SESSION['CIERRE']['caja'] = $_SESSION['CAJA']['CAJAID'];
//***************************************************
//CASO DE CIERRE DE CAJA PARA LAS CAJAS FACTURADORAS
//**************************************************
            if ($_REQUEST['TipoCuenta'] == '05') {
                $query = "SELECT DISTINCT a.cierre_caja_id
                        FROM fac_facturas_contado a, cajas_rapidas b,
                                    userpermisos_cajas_rapidas c
                        WHERE a.caja_id=" . $_REQUEST['Caja'] . "
                                    AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                    AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                    AND a.caja_id=b.caja_id
                                    AND c.caja_id=b.caja_id
                                    AND c.usuario_id=a.usuario_id
                                    AND a.cierre_caja_id IS NOT NULL
                                    AND a.cierre_caja_id NOT IN (
                                            SELECT e.cierre_caja_id
                                            FROM cierre_de_caja d, cierre_de_caja_detalle e
                                            WHERE d.cierre_de_caja_id=e.cierre_de_caja_id);";
                $result1 = $dbconn->Execute($query);
                while (!$result1->EOF) {
                    $var[] = $result1->GetRowAssoc($ToUpper = false);
                    $result1->MoveNext();
                }
                $_SESSION['CAJA']['VECT_CIERRE_DE_CAJA'] = $var;
                $query = "SELECT SUM(a.total_efectivo) AS total_efectivo,SUM(a.total_cheques) AS total_cheques,SUM(a.total_tarjetas) AS total_tarjetas,
                                    SUM(a.total_bonos) AS total_bonos
                        FROM fac_facturas_contado a, cajas_rapidas b,
                                    userpermisos_cajas_rapidas c, fac_facturas d
                        WHERE a.caja_id=" . $_REQUEST['Caja'] . "
                                    AND a.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                                    AND a.centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                                    AND a.caja_id=b.caja_id
                                    AND c.caja_id=b.caja_id
                                    AND a.prefijo=d.prefijo
                                    AND a.factura_fiscal=d.factura_fiscal
                                    AND c.usuario_id=a.usuario_id
                                    AND a.cierre_caja_id IS NOT NULL
                                    AND d.estado<>2
                                    AND a.cierre_caja_id NOT IN (
                                            SELECT e.cierre_caja_id
                                            FROM cierre_de_caja d, cierre_de_caja_detalle e
                                            WHERE d.cierre_de_caja_id=e.cierre_de_caja_id);";
                $result = $dbconn->Execute($query);
                $entrega = $result->fields[0] + $result->fields[1] + $result->fields[2] + $result->fields[3];
                $query = "INSERT INTO cierre_de_caja
                            (caja_id,
                                cierre_de_caja_id,
                                centro_utilidad,
                                empresa_id,
                                usuario_id,
                                usuario_recibio,
                                total_efectivo,
                                total_cheques,
                                total_tarjetas,
                                total_bonos,
                                total_devolucion,
                                entrega_efectivo,
                                fecha_registro,
                                observaciones
                            )
                            VALUES
                            (
                            " . $_SESSION['CIERRE']['caja'] . " ,
                            $serial,
                            '" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "',
                            '" . $_SESSION['CAJA']['EMPRESA'] . "',
                            " . UserGetUID() . " ,
                            " . $usuario . " ,
                            " . round($result->fields[0]) . ",
                            " . round($result->fields[1]) . ",
                            " . round($result->fields[2]) . ",
                            " . round($result->fields[3]) . ",
                            0,
                            " . round($entrega) . ",
                                now(),
                            '" . substr($_REQUEST['observa'], 0, 254) . "'
                            )";
                $dbconn->Execute($query);

                if (sizeof($var) > 0) {
                    for ($i = 0; $i < sizeof($var); $i++) {
                        $query = "INSERT INTO cierre_de_caja_detalle
                                                    (
                                                        cierre_de_caja_id,
                                                        cierre_caja_id
                                                    )
                                                    VALUES
                                                    (
                                                        $serial,
                                                        " . $var[$i][cierre_caja_id] . "
                                                    )";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al insertar en la tabla cierre_de_caja_detalle (En cajas rapidas)";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                    }
                }
                $dbconn->CommitTrans();
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'Menu', array('Caja' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $_REQUEST['Tiponumeracion'], 'TipoCuenta' => $_REQUEST['TipoCuenta']));
                $this->FormaMensaje('CIERRE DE CAJA EFECTUADO SATISFACTORIAMENTE', 'CONFIRMACION', $action, 'Volver', 'cierre_de_caja');
                //$this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],'',
                //$_REQUEST['TipoCuenta'],'');
                return true;
            }
//***************************************************
//CASO DE CIERRE DE CAJA PARA LAS CAJAS FACTURADORAS
//**************************************************
//
            $query = "INSERT INTO cierre_de_caja
                        (
                            caja_id,
                            cierre_de_caja_id,
                            centro_utilidad,
                            empresa_id,
                            usuario_id,
                            usuario_recibio,
                            total_efectivo,
                            total_cheques,
                            total_tarjetas,
                            total_bonos,
                            total_devolucion,
                            entrega_efectivo,
                            fecha_registro,
                            observaciones
                        )
                        VALUES
                        (
                        " . $_SESSION['CIERRE']['caja'] . " ,
                        $serial,
                        '" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "',
                        '" . $_SESSION['CAJA']['EMPRESA'] . "',
                        " . UserGetUID() . " ,
                        " . $usuario . " ,
                        " . round($_SESSION['CIERRE']['ef']) . ",
                        " . round($_SESSION['CIERRE']['che']) . ",
                        " . round($_SESSION['CIERRE']['tar']) . ",
                        " . round($_SESSION['CIERRE']['tbon']) . ",
                        " . round($_SESSION['CIERRE']['totaldev']) . ",
                        " . round($entrega) . ",
                            now(),
                        '" . substr($_REQUEST['observa'], 0, 254) . "'
                        )";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al insertar en la tabla cierre_de_caja";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            //DETALLE DE CUADRES DEL CIERRE
            if (sizeof($_SESSION['CIERRE']['cierres']) > 0) {
                for ($i = 0; $i < sizeof($_SESSION['CIERRE']['cierres']); $i++) {
                    $query = "INSERT INTO cierre_de_caja_detalle
                                                (
                                                    cierre_de_caja_id,
                                                    cierre_caja_id
                                                )
                                                VALUES
                                                (
                                                    $serial,
                                                    " . $_SESSION['CIERRE']['cierres'][$i][cierre_caja_id] . "
                                                )";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al insertar en la tabla cierre_de_caja_detalle";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                }
            }
            $dbconn->CommitTrans();
            /*          $this->uno=1;
              $this->frmError["MensajeError"]="Cierre de caja realizado."; */
            unset($_SESSION['CAJA']['CIERRE']);
            if ($_REQUEST['TipoCuenta'] == '01' OR $_REQUEST['TipoCuenta'] == '02') {
                //$this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU']);
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'Menu', array('Caja' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $_REQUEST['arreglo'], 'TipoCuenta' => $_REQUEST['TipoCuenta'], 'CU' => $_REQUEST['CU']));
            }
            if ($_REQUEST['TipoCuenta'] == '03' OR $_REQUEST['TipoCuenta'] == '04' OR $_REQUEST['TipoCuenta'] == '08') {
                //$this->Menu($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta']);
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array('Caja' => $_REQUEST['Cajaid'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $_REQUEST['Tiponumeracion'], 'TipoCuenta' => $_REQUEST['TipoCuenta']));
            }
            if ($_REQUEST['TipoCuenta'] == '05') {
                //$this->Menu($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta']);
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'Menu', array('Caja' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $_REQUEST['Tiponumeracion'], 'TipoCuenta' => $_REQUEST['TipoCuenta']));
            }
            if ($_REQUEST['TipoCuenta'] == '06') {
                //$this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU']);
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'Menu', array('Caja' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $_REQUEST['arreglo'], 'TipoCuenta' => $_REQUEST['TipoCuenta'], 'CU' => $_REQUEST['CU']));
            }
            $this->FormaMensaje('CIERRE DE CAJA EFECTUADO SATISFACTORIAMENTE', 'CONFIRMACION', $action, 'Volver', 'cierre_de_caja');
            //$this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],'',
            //$_REQUEST['TipoCuenta'],'');
            return true;
        }
    }

    function InsertarCierreCaja() {
        if ($_REQUEST['cerrar'] == 'CERRAR') {
            $this->InsertarCuadreCierre();
            /*          if($_REQUEST['TipoCuenta']=='01' OR $_REQUEST['TipoCuenta']=='02')
              {                                                       //($Caja,$empresa,$centro,$tipo,$tipocuenta,$cu='',$obs)
              $this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU'],$_REQUEST['observa'],$_REQUEST['user']);
              return true;
              }
              if($_REQUEST['TipoCuenta']=='03' OR $_REQUEST['TipoCuenta']=='04')
              {
              $this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta'],$_REQUEST['observa'],$_REQUEST['user']);
              return true;
              }
              if($_REQUEST['TipoCuenta']=='05')
              {
              $this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta'],$_REQUEST['observa'],$_REQUEST['user']);
              return true;
              }
              if($_REQUEST['TipoCuenta']=='06')
              {
              $this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU'],$_REQUEST['observa'],$_REQUEST['user']);
              return true;
              } */
            return true;
        }

        list($dbconn) = GetDBconn();
        unset($_SESSION['REF_DPTO']); //esta variable tomara el departamento para revisarlo en en reporte.
        if (!$_REQUEST['usuario'] || !$_REQUEST['pass']) {
            if (!$_REQUEST['usuario']) {
                $this->frmError["usuario"] = 1;
            }
            if (!$_REQUEST['pass']) {
                $this->frmError["pass"] = 1;
            }
            $this->frmError["MensajeError"] = "Faltan datos de autenticacion.";

            if ($_REQUEST['TipoCuenta'] == '01' OR $_REQUEST['TipoCuenta'] == '02') {                                                       //($Caja,$empresa,$centro,$tipo,$tipocuenta,$cu='',$obs)
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $_REQUEST['observa'], $_REQUEST['user']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '03' OR $_REQUEST['TipoCuenta'] == '04') {
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['observa'], $_REQUEST['user']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '05') {
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['observa'], $_REQUEST['user']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '06') {
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $_REQUEST['observa'], $_REQUEST['user']);
                return true;
            }
        } elseif ($_REQUEST['user'] != UserGetUID()) {
            $this->frmError["MensajeError"] = "NO SE PUEDEN CUADRAR DOCUMENTOS DE OTRO USUARIO.";

            if ($_REQUEST['TipoCuenta'] == '01' OR $_REQUEST['TipoCuenta'] == '02') {
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], '', $_REQUEST['CU'], $_REQUEST['observa'], '', $_REQUEST['Caja_empresa']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '03' OR $_REQUEST['TipoCuenta'] == '04' OR $_REQUEST['TipoCuenta'] == '08') {
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['observa']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '05') {
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], '', $_REQUEST['observa'], '', $_REQUEST['Caja_empresa']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '06') {
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $_REQUEST['observa']);
                return true;
            }
        } else {
            //cambio realiado para la sos....
            $usuario_id = UserValidarUsuario($_REQUEST['usuario'], $_REQUEST['pass']);

            if ($usuario_id != UserGetUID()) {
                if (!$_REQUEST['usuario']) {
                    $this->frmError["usuario"] = 1;
                }
                if (!$_REQUEST['pass']) {
                    $this->frmError["pass"] = 1;
                }
                $this->frmError["MensajeError"] = "Identificacion incorrecta,Si ha olvidado su contrase�  contacte al administrador del sistema .";

                if ($_REQUEST['TipoCuenta'] == '01' OR $_REQUEST['TipoCuenta'] == '02') {
                    $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $_REQUEST['observa']);
                    return true;
                }
                if ($_REQUEST['TipoCuenta'] == '03' OR $_REQUEST['TipoCuenta'] == '04' OR $_REQUEST['TipoCuenta'] == '08') {
                    $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['observa']);
                    return true;
                }
                if ($_REQUEST['TipoCuenta'] == '05') {
                    $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], '', $_REQUEST['observa'], '', $_REQUEST['Caja_empresa']);
                    return true;
                }
                if ($_REQUEST['TipoCuenta'] == '06') {
                    $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $_REQUEST['observa']);
                    return true;
                }
            }


            $busca = "select nextval('public.recibos_caja_cierre_cierre_caja_id_seq')";
            $resulta = $dbconn->Execute($busca);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al traer el serial de cierre de caja";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $serial = $resulta->fields[0];
            $_SESSION['CAJA']['SERIALCIERRE'] = $serial;
            if ($_SESSION['CAJA']['CIERRE']['DEPTO'] || $_REQUEST['TipoCuenta'] == '03' || $_REQUEST['TipoCuenta'] == '08') {
                $sw_facturado = 1;
            } else {
                $sw_facturado = 0;
            }
            //ESTA SECUENCIA ES PARA PREGUNTARLA CUANDO GENERE EL REPORTE SACAR LA OBSERVACION,FECHA REPORTE
            $_SESSION['CAJA']['CIERRES']['SEQ'] = $serial;
            $_SESSION['CAJA']['CUADRE']['observa'] = $_REQUEST['observa'];
            $tefectivo = $_REQUEST['tef'];
            $query = "INSERT INTO recibos_caja_cierre
                                        (cierre_caja_id,
                                        centro_utilidad,
                                        empresa_id,
                                        cajero_id,
                                        total_efectivo,
                                        total_cheques,
                                        total_tarjetas,
                                        total_bonos,
                                        fecha_registro,
                                        usuario_id,
                                        observaciones,
                                        sw_facturado)
                                        VALUES
                                        (
                                        $serial,
                                        '" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "',
                                        '" . $_SESSION['CAJA']['EMPRESA'] . "',
                                        " . $_REQUEST['user'] . " ,
                                        " . $tefectivo . ",
                                        " . $_REQUEST['tche'] . ",
                                        " . $_REQUEST['ttar'] . ",
                                        " . $_REQUEST['tbon'] . ",
                                            now(),
                                        " . UserGetUID() . " ,
                                        '" . substr($_REQUEST['observa'], 0, 254) . "',
                                        '$sw_facturado'
                                        )";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al insertar en la tabla recibos_caja_cierre";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            //INSERTAR CIERRE DE DEVOLUCIONES
            if (!empty($_REQUEST['tefd'])) {
                /*                      $busca="select nextval('rc_devoluciones_cierre_seq')";
                  $resulta=$dbconn->Execute($busca);
                  if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al traer el serial de cierre de caja";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
                  }
                  $cierre_caja_id=$resulta->fields[0]; */
                $total_recibo_caja = $tefectivo + $_REQUEST['tche'] + $_REQUEST['ttar'] + $_REQUEST['tbon'];
                $total_entregado = $total_recibo_caja - $_REQUEST['tefd'];
                $query = "INSERT INTO rc_devoluciones_cierre
                                        (cierre_caja_id,
                                        centro_utilidad,
                                        empresa_id,
                                        cajero_id,
                                        total_recibo_caja,
                                        total_devolucion,
                                        total_entregado,
                                        fecha_registro,
                                        usuario_id,
                                        observaciones,
                                        sw_facturado)
                                        VALUES
                                        (
                                        $serial,
                                        '" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "',
                                        '" . $_SESSION['CAJA']['EMPRESA'] . "',
                                        " . $_REQUEST['user'] . " ,
                                        " . $total_recibo_caja . ",
                                        " . $_REQUEST['tefd'] . ",
                                        " . $total_entregado . ",
                                            now(),
                                        " . UserGetUID() . " ,
                                        '" . substr($_REQUEST['observa'], 0, 254) . "',
                                        '$sw_facturado'
                                        )";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al insertar en la tabla rc_devoluciones_cierre";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $dev = $_SESSION['CAJA']['CIERRE']['DEVOLUCIONES'];
                for ($i = 0; $i < sizeof($dev); $i++) {
                    $query = "UPDATE rc_devoluciones SET cierre_caja_id=$serial WHERE  cierre_caja_id ISNULL
                                                AND caja_id=" . $dev[$i] . " AND usuario_id=" . UserGetUID() . "";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al actualizar la tabla rc_devoluciones";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                }
            }
            //FIN INSERTAR CIERRE DE DEVOLUCIONES
            //aqui controlamos que sea caja rapida..
            if ($_REQUEST['TipoCuenta'] == '03' OR $_REQUEST['TipoCuenta'] == '08') {//CAJA CONCEPTOS
                $dec = $_SESSION['CAJA']['CIERRE']['DATOS'];
                $usuario_id = $_REQUEST['user'];

                for ($i = 0; $i < sizeof($dec); $i++) {
                    $query = "UPDATE fac_facturas_contado SET cierre_caja_id=$serial WHERE  cierre_caja_id ISNULL
                                                    AND caja_id=" . $dec[$i] . " AND usuario_id=$usuario_id";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al actualizar la tabla fac_facturas_contado";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                }
                //pasamos el departamento a esta variable,para mostrarlo en el reporte.
                $_SESSION['REF_DPTO'] = $_SESSION['CAJA']['CIERRE']['DEPTO'];
            } else
            if ($_SESSION['CAJA']['CIERRE']['DEPTO']) {//CAJAS AMBULATORIAS - CONSULTA EXTERNA
                $dec = $_SESSION['CAJA']['CIERRE']['DATOS'];
                $usuario_id = $_REQUEST['user'];
                for ($i = 0; $i < sizeof($dec); $i++) {
                    $query = "UPDATE fac_facturas_contado SET cierre_caja_id=$serial WHERE  cierre_caja_id ISNULL
                                                    AND caja_id=" . $dec[$i] . " AND usuario_id=$usuario_id";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al actualizar la tabla fac_facturas_contado";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                }
                //pasamos el departamento a esta variable,para mostrarlo en el reporte.
                $_SESSION['REF_DPTO'] = $_SESSION['CAJA']['CIERRE']['DEPTO'];
            } else {//si no es por q es un cierre de caja normal....
                //CAJAS HOSPITALARIAS
                $query = "UPDATE recibos_caja SET cierre_caja_id=$serial WHERE  cierre_caja_id ISNULL
                            AND caja_id=" . $_SESSION['CAJA']['CAJAID'] . " AND usuario_id=" . UserGetUID() . ";";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al actualizar la tabla recibos_caja";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }

            unset($_SESSION['CAJA']['CIERRE']);
            if ($_REQUEST['TipoCuenta'] == '01' OR $_REQUEST['TipoCuenta'] == '02') {
                //$this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU']);
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'Menu', array('Caja' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $_REQUEST['arreglo'], 'TipoCuenta' => $_REQUEST['TipoCuenta'], 'CU' => $_REQUEST['CU']));
                $reporte = 'cierre';
            }
            if ($_REQUEST['TipoCuenta'] == '03' OR $_REQUEST['TipoCuenta'] == '04' OR $_REQUEST['TipoCuenta'] == '08') {
                $_SESSION['CAJA']['CONCEPTOS'] = $_REQUEST['TipoCuenta'];
                //$this->Menu($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta']);
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array('Caja' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $_REQUEST['Tiponumeracion'], 'TipoCuenta' => $_REQUEST['TipoCuenta']));
                $reporte = 'cierre';
            }
            if ($_REQUEST['TipoCuenta'] == '05') {
                //$this->Menu($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta']);
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'Menu', array('Caja' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $_REQUEST['Tiponumeracion'], 'TipoCuenta' => $_REQUEST['TipoCuenta']));
                $reporte = 'reporte_cierre_de_caja';
            }
            if ($_REQUEST['TipoCuenta'] == '06') {
                //$this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU']);
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'Menu', array('Caja' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $_REQUEST['arreglo'], 'TipoCuenta' => $_REQUEST['TipoCuenta'], 'CU' => $_REQUEST['CU']));
                $reporte = 'cierre';
            }
            $this->FormaMensaje('CUADRE DE CAJA EFECTUADO SATISFACTORIAMENTE', 'CONFIRMACION', $action, 'Volver', $reporte);
            return true;
        }
    }

    function InsertarCuadreCierre() {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        unset($_SESSION['REF_DPTO']); //esta variable tomara el departamento para revisarlo en en reporte.
        if (!$_REQUEST['usuario'] || !$_REQUEST['pass']) {
            if (!$_REQUEST['usuario']) {
                $this->frmError["usuario"] = 1;
            }
            if (!$_REQUEST['pass']) {
                $this->frmError["pass"] = 1;
            }
            $this->frmError["MensajeError"] = "Faltan datos de autenticacion.";

            if ($_REQUEST['TipoCuenta'] == '01' OR $_REQUEST['TipoCuenta'] == '02') {                                                       //($Caja,$empresa,$centro,$tipo,$tipocuenta,$cu='',$obs)
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $_REQUEST['observa'], $_REQUEST['user']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '03' OR $_REQUEST['TipoCuenta'] == '04') {
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['observa'], $_REQUEST['user']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '05') {
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['observa'], $_REQUEST['user']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '06') {
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $_REQUEST['observa'], $_REQUEST['user']);
                return true;
            }
        } elseif ($_REQUEST['user'] != UserGetUID()) {
            $this->frmError["MensajeError"] = "NO SE PUEDEN CUADRAR DOCUMENTOS DE OTRO USUARIO.";

            if ($_REQUEST['TipoCuenta'] == '01' OR $_REQUEST['TipoCuenta'] == '02') {
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $_REQUEST['observa']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '03' OR $_REQUEST['TipoCuenta'] == '04') {
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['observa']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '05') {
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['observa']);
                return true;
            }
            if ($_REQUEST['TipoCuenta'] == '06') {
                $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $_REQUEST['observa']);
                return true;
            }
        } else {
            //cambio realiado para la sos....
            $usuario_id = UserValidarUsuario($_REQUEST['usuario'], $_REQUEST['pass']);

            if ($usuario_id != UserGetUID()) {
                if (!$_REQUEST['usuario']) {
                    $this->frmError["usuario"] = 1;
                }
                if (!$_REQUEST['pass']) {
                    $this->frmError["pass"] = 1;
                }
                $this->frmError["MensajeError"] = "Identificacion incorrecta,Si ha olvidado su contrase�  contacte al administrador del sistema .";

                if ($_REQUEST['TipoCuenta'] == '01' OR $_REQUEST['TipoCuenta'] == '02') {
                    $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $_REQUEST['observa']);
                    return true;
                }
                if ($_REQUEST['TipoCuenta'] == '03' OR $_REQUEST['TipoCuenta'] == '04') {
                    $this->ListadoCerrarCaja($_REQUEST['Cajaid'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['observa']);
                    return true;
                }
                if ($_REQUEST['TipoCuenta'] == '05') {
                    $this->ListadoCerrarCaja($_REQUEST['Cajaid'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['Tiponumeracion'], $_REQUEST['TipoCuenta'], $_REQUEST['observa']);
                    return true;
                }
                if ($_REQUEST['TipoCuenta'] == '06') {
                    $this->ListadoCerrarCaja($_REQUEST['Caja'], $_REQUEST['Empresa'], $_REQUEST['CentroUtilidad'], $_REQUEST['arreglo'], $_REQUEST['TipoCuenta'], $_REQUEST['CU'], $_REQUEST['observa']);
                    return true;
                }
            }

            $busca = "select nextval('public.recibos_caja_cierre_cierre_caja_id_seq')";
            $resulta = $dbconn->Execute($busca);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al traer el serial de cierre de caja";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            $serial = $resulta->fields[0];
            if ($_SESSION['CAJA']['CIERRE']['DEPTO']) {
                $sw_facturado = 1;
            } else {
                $sw_facturado = 0;
            }
            //ESTA SECUENCIA ES PARA PREGUNTARLA CUANDO GENERE EL REPORTE SACAR LA OBSERVACION,FECHA REPORTE
            $_SESSION['CAJA']['CIERRES']['SEQ'] = $serial;
            $tefectivo = $_REQUEST['tef'];
            $query = "INSERT INTO recibos_caja_cierre
                                        (cierre_caja_id,
                                        centro_utilidad,
                                        empresa_id,
                                        cajero_id,
                                        total_efectivo,
                                        total_cheques,
                                        total_tarjetas,
                                        total_bonos,
                                        fecha_registro,
                                        usuario_id,
                                        observaciones,
                                        sw_facturado)
                                        VALUES
                                        (
                                        $serial,
                                        '" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "',
                                        '" . $_SESSION['CAJA']['EMPRESA'] . "',
                                        " . $_REQUEST['user'] . " ,
                                        " . $tefectivo . ",
                                        " . $_REQUEST['tche'] . ",
                                        " . $_REQUEST['ttar'] . ",
                                        " . $_REQUEST['tbon'] . ",
                                            now(),
                                        " . UserGetUID() . " ,
                                        '" . substr($_REQUEST['observa'], 0, 254) . "',
                                        '$sw_facturado'
                                        )";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al insertar en la tabla recibos_caja_cierre";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            //INSERTAR CIERRE DE DEVOLUCIONES
            if (!empty($_REQUEST['tefd'])) {
                /*                      $busca="select nextval('rc_devoluciones_cierre_seq')";
                  $resulta=$dbconn->Execute($busca);
                  if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al traer el serial de cierre de caja";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
                  }
                  $cierre_caja_id=$resulta->fields[0]; */
                $total_recibo_caja = $tefectivo + $_REQUEST['tche'] + $_REQUEST['ttar'] + $_REQUEST['tbon'];
                $total_entregado = $total_recibo_caja - $_REQUEST['tefd'];
                $query = "INSERT INTO rc_devoluciones_cierre
                                        (cierre_caja_id,
                                        centro_utilidad,
                                        empresa_id,
                                        cajero_id,
                                        total_recibo_caja,
                                        total_devolucion,
                                        total_entregado,
                                        fecha_registro,
                                        usuario_id,
                                        observaciones,
                                        sw_facturado)
                                        VALUES
                                        (
                                        $serial,
                                        '" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "',
                                        '" . $_SESSION['CAJA']['EMPRESA'] . "',
                                        " . $_REQUEST['user'] . " ,
                                        " . $total_recibo_caja . ",
                                        " . $_REQUEST['tefd'] . ",
                                        " . $total_entregado . ",
                                            now(),
                                        " . UserGetUID() . " ,
                                        '" . substr($_REQUEST['observa'], 0, 254) . "',
                                        '$sw_facturado'
                                        )";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al insertar en la tabla rc_devoluciones_cierre";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
                $dev = $_SESSION['CAJA']['CIERRE']['DEVOLUCIONES'];
                for ($i = 0; $i < sizeof($dev); $i++) {
                    $query = "UPDATE rc_devoluciones SET cierre_caja_id=$serial WHERE  cierre_caja_id ISNULL
                                                AND caja_id=" . $dev[$i] . " AND usuario_id=" . UserGetUID() . "";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al actualizar la tabla rc_devoluciones";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                }
            }
            //FIN INSERTAR CIERRE DE DEVOLUCIONES
            //CAJA CONCEPTOS
            if ($_REQUEST['TipoCuenta'] == '03') {
                $dec = $_SESSION['CAJA']['CIERRE']['DATOS'];
                $usuario_id = $_REQUEST['user'];
                for ($i = 0; $i < sizeof($dec); $i++) {
                    $query = "UPDATE fac_facturas_contado SET cierre_caja_id=$serial WHERE  cierre_caja_id ISNULL
                                                    AND caja_id=" . $dec[$i] . " AND usuario_id=$usuario_id";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al actualizar la tabla fac_facturas_contado";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                }
            } else
            //aqui controlamos que sea caja rapida..
            if ($_SESSION['CAJA']['CIERRE']['DEPTO']) {//CAJAS AMBULATORIAS - CONSULTA EXTERNA
                $dec = $_SESSION['CAJA']['CIERRE']['DATOS'];
                $usuario_id = $_REQUEST['user'];
                for ($i = 0; $i < sizeof($dec); $i++) {
                    $query = "UPDATE fac_facturas_contado SET cierre_caja_id=$serial WHERE  cierre_caja_id ISNULL
                                                    AND caja_id=" . $dec[$i] . " AND usuario_id=$usuario_id";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al actualizar la tabla fac_facturas_contado";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                }
                //pasamos el departamento a esta variable,para mostrarlo en el reporte.
                $_SESSION['REF_DPTO'] = $_SESSION['CAJA']['CIERRE']['DEPTO'];
            } else {//si no es por q es un cierre de caja normal....
                //CAJAS HOSPITALARIAS
                $query = "UPDATE recibos_caja SET cierre_caja_id=$serial WHERE  cierre_caja_id ISNULL
                                                        AND caja_id=" . $_SESSION['CAJA']['CAJAID'] . "
                                                        AND usuario_id=" . UserGetUID() . ";";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al actualizar la tabla recibos_caja";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
            }

            $dbconn->CommitTrans();

            unset($_SESSION['CAJA']['CIERRE']);
//VALORES PARA LOS REPORTES
            /*              if($_REQUEST['TipoCuenta']=='01' OR $_REQUEST['TipoCuenta']=='02')
              {
              //$this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU']);
              $action=ModuloGetURL('app','CajaGeneral','user','Menu',array('Caja'=>$_REQUEST['Caja'],'Empresa'=>$_REQUEST['Empresa'],'CentroUtilidad'=>$_REQUEST['CentroUtilidad'],'Tiponumeracion'=>$_REQUEST['arreglo'],'TipoCuenta'=>$_REQUEST['TipoCuenta'],'CU'=>$_REQUEST['CU']));
              $reporte='cierre';
              }
              if($_REQUEST['TipoCuenta']=='03' OR $_REQUEST['TipoCuenta']=='04')
              {
              //$this->Menu($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta']);
              $action=ModuloGetURL('app','CajaGeneral','user','Menu',array('Caja'=>$_REQUEST['Cajaid'],'Empresa'=>$_REQUEST['Empresa'],'CentroUtilidad'=>$_REQUEST['CentroUtilidad'],'Tiponumeracion'=>$_REQUEST['Tiponumeracion'],'TipoCuenta'=>$_REQUEST['TipoCuenta']));
              $reporte='cierre';
              }
              if($_REQUEST['TipoCuenta']=='05')
              {
              //$this->Menu($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta']);
              $action=ModuloGetURL('app','CajaGeneral','user','Menu',array('Caja'=>$_REQUEST['Cajaid'],'Empresa'=>$_REQUEST['Empresa'],'CentroUtilidad'=>$_REQUEST['CentroUtilidad'],'Tiponumeracion'=>$_REQUEST['Tiponumeracion'],'TipoCuenta'=>$_REQUEST['TipoCuenta']));
              $reporte='reporte_cierre_de_caja';
              }
              if($_REQUEST['TipoCuenta']=='06')
              {
              //$this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU']);
              $action=ModuloGetURL('app','CajaGeneral','user','Menu',array('Caja'=>$_REQUEST['Caja'],'Empresa'=>$_REQUEST['Empresa'],'CentroUtilidad'=>$_REQUEST['CentroUtilidad'],'Tiponumeracion'=>$_REQUEST['arreglo'],'TipoCuenta'=>$_REQUEST['TipoCuenta'],'CU'=>$_REQUEST['CU']));
              $reporte='cierre';
              }
              $this->FormaMensaje('CUADRE DE CAJA EFECTUADO SATISFACTORIAMENTE','CONFIRMACION',$action,'Volver',$reporte);
              return true; */
//FIN VALORES PARA LOS REPORTES
//****************************************************
//****************************************************
//****************************************************
//CONSULTA E INSERCI� DE DATOS PARA EL CIERRE DE CAJA
//INICIO CONSULTA
            $dbconn->BeginTrans();
            $Caja = $_SESSION['CAJA']['CAJAID'];
            $sw = $_SESSION['CAJA']['SW'];
            $dpto = $_SESSION['CAJA']['DEPTO'];
            $vectcuadre = $this->BusquedaUsuariosCuadrados($Caja, $sw, $dpto, $_REQUEST['TipoCuenta']);
            $_SESSION['CAJA']['VECT_CIERRE_DE_CAJA'] = $vectcuadre;
            for ($i = 0; $i < sizeof($vectcuadre);) {
                $_SESSION['CIERRE']['cierres'] = $this->TraerCierresCajaCuadrada($vectcuadre[$i][usuario_id], $vectcuadre[$i][caja_id], $sw, $vectcuadre[$i][departamento], $_REQUEST['TipoCuenta']);
                $k = $i;
                while ($vectcuadre[$i][descripcion] == $vectcuadre[$k][descripcion]) {
                    if ($sw == 1) {   //*****************FACTURAS**************************
                        $arr = $this->TraerTotales($vectcuadre[$k][usuario_id], $vectcuadre[$k][caja_id], $vectcuadre[$k][cierre_caja_id]);
                    }   //*****************FACTURAS**************************
                    elseif ($sw == 2) {   //*****************RECIBOS DE CAJA CUADRADOS*******************
                        $arr = $this->TraerTotalesRecibosCuadrados($vectcuadre[$k][usuario_id], $vectcuadre[$k][caja_id], $vectcuadre[$k][cierre_caja_id]);
                        //$arr=$this->TraerTotalesRecibos($vect[$k][usuario_id],$vect[$k][caja_id]);
                        //*****************DEVOLUCIONES*******************
                        $arrdev = $this->TraerTotalesDevolucionesCuadradas($vectcuadre[$k][usuario_id], $vectcuadre[$k][caja_id], $vectcuadre[$k][cierre_caja_id]);
                    }   //*****************DEVOLUCIONES*******************
                    for ($n = 0; $n < sizeof($arr); $n++) {
                        $efectivo = $efectivo + $arr[$n][total_efectivo];
                        $cheque = $cheque + $arr[$n][total_cheques];
                        $tarjeta = $tarjeta + $arr[$n][total_tarjetas];
                        //$abono=$abono+$arr[$n][total_abono];
                        $bonos = $bonos + $arr[$n][total_bonos];
                    }
                    $abono = $abono + ($efectivo + $cheque + $tarjeta + $bonos);
                    $cont = $cont + sizeof($arr);
                    $te = $te + $efectivo;
                    $che = $che + $cheque;
                    $tar = $tar + $tarjeta;
                    $tbon = $tbon + $bonos;
                    $ta = $ta + $abono;
                    $totaldev = $totaldev + $arrdev[0][total_devolucion];
                    //$_SESSION['CIERRE']['cierres'][$k]=$vectcuadre[$k][cierre_caja_id];
                    $totaldevabono = $totaldevabono + ($abono - $arrdev[0][total_devolucion]);
                    unset($total);
                    unset($cheque);
                    unset($tarjeta);
                    unset($efectivo);
                    unset($abono);
                    unset($bonos);
                    $k++;
                }
                /*                  $_SESSION['CIERRE']['ef']=$te;
                  $_SESSION['CIERRE']['che']=$che;
                  $_SESSION['CIERRE']['tar']=$tar;
                  $_SESSION['CIERRE']['tbon']=$tbon;
                  $_SESSION['CIERRE']['ta']=$ta;
                  $_SESSION['CIERRE']['totaldev']=$totaldev;
                  $_SESSION['CIERRE']['caja']=$Caja;
                  unset($cont);
                  unset($te);
                  unset($ta);
                  unset($tar);
                  unset($che);
                  unset($tbon);
                  unset($totaldev); */
                $i = $k;
//********************************
//********************************
            }

//FIN CONSULTA
            $entrega = $ta - $totaldev;
            $usuario = UserGetUID();
            $_SESSION['CAJA']['CIERRE_TOTAL']['SEQ'] = $serial;
            $query = "INSERT INTO cierre_de_caja
                        (
                            caja_id,
                            cierre_de_caja_id,
                            centro_utilidad,
                            empresa_id,
                            usuario_id,
                            usuario_recibio,
                            total_efectivo,
                            total_cheques,
                            total_tarjetas,
                            total_bonos,
                            total_devolucion,
                            entrega_efectivo,
                            fecha_registro,
                            observaciones
                        )
                        VALUES
                        (" . $_SESSION['CIERRE']['caja'] . " ,
                        $serial,
                        '" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "',
                        '" . $_SESSION['CAJA']['EMPRESA'] . "',
                        " . UserGetUID() . " ,
                        " . $usuario . " ,
                        " . round($te) . ",
                        " . round($che) . ",
                        " . round($tar) . ",
                        " . round($tbon) . ",
                        " . round($totaldev) . ",
                        " . round($entrega) . ",
                            now(),
                        '" . substr($_REQUEST['observa'], 0, 254) . "'
                        );";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al insertar en la tabla cierre_de_caja";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            //DETALLE DE CUADRES DEL CIERRE
            if (sizeof($_SESSION['CIERRE']['cierres']) > 0) {
                for ($i = 0; $i < sizeof($_SESSION['CIERRE']['cierres']); $i++) {
                    $query = "INSERT INTO cierre_de_caja_detalle
                                                    (
                                                        cierre_de_caja_id,
                                                        cierre_caja_id
                                                    )
                                                    VALUES
                                                    (
                                                        $serial,
                                                        " . $_SESSION['CIERRE']['cierres'][$i][cierre_caja_id] . "
                                                    )";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al insertar en la tabla cierre_de_caja_detalle";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                }
            }
            $dbconn->CommitTrans();
            /*          $this->uno=1;
              $this->frmError["MensajeError"]="Cierre de caja realizado."; */
            unset($_SESSION['CAJA']['CIERRE']);
            if ($_REQUEST['TipoCuenta'] == '01' OR $_REQUEST['TipoCuenta'] == '02') {
                //$this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU']);
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'Menu', array('Caja' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $_REQUEST['arreglo'], 'TipoCuenta' => $_REQUEST['TipoCuenta'], 'CU' => $_REQUEST['CU']));
            }
            if ($_REQUEST['TipoCuenta'] == '03' OR $_REQUEST['TipoCuenta'] == '04') {
                //$this->Menu($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta']);
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'MenuCajaConceptos', array('Caja' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $_REQUEST['Tiponumeracion'], 'TipoCuenta' => $_REQUEST['TipoCuenta']));
            }
            if ($_REQUEST['TipoCuenta'] == '05') {
                //$this->Menu($_REQUEST['Cajaid'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['Tiponumeracion'],$_REQUEST['TipoCuenta']);
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'Menu', array('Caja' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $_REQUEST['Tiponumeracion'], 'TipoCuenta' => $_REQUEST['TipoCuenta']));
            }
            if ($_REQUEST['TipoCuenta'] == '06') {
                //$this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],$_REQUEST['arreglo'],$_REQUEST['TipoCuenta'],$_REQUEST['CU']);
                $action = ModuloGetURL('app', 'CajaGeneral', 'user', 'Menu', array('Caja' => $_REQUEST['Caja'], 'Empresa' => $_REQUEST['Empresa'], 'CentroUtilidad' => $_REQUEST['CentroUtilidad'], 'Tiponumeracion' => $_REQUEST['arreglo'], 'TipoCuenta' => $_REQUEST['TipoCuenta'], 'CU' => $_REQUEST['CU']));
            }
            $this->FormaMensaje('CIERRE DE CAJA EFECTUADO SATISFACTORIAMENTE', 'CONFIRMACION', $action, 'Volver', 'cierre_de_caja');
            //$this->Menu($_REQUEST['Caja'],$_REQUEST['Empresa'],$_REQUEST['CentroUtilidad'],'',
            //$_REQUEST['TipoCuenta'],'');
            return true;
//CONSULTA E INSERCI� DE DATOS PARA EL CIERRE DE CAJA
        //
        }
    }

    //esta funcion de asignar numero de documento es de entregar una numeracion
    //segun el tipo de doc(caja,etc..) en facturas de contado y credito.
    function AsignarNumero($prefijo, &$dbconn) {
        ///list($dbconn) = GetDBconn();
        if ((!empty($prefijo))) {
            //$sql="BEGIN WORK;  LOCK TABLE fac_tipos_facturas IN ROW EXCLUSIVE MODE";
            $sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE";
            $result = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                die(MsgOut("Error al iniciar la transaccion", "Error DB : " . $dbconn->ErrorMsg()));
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
            //actualizacion contado
            $sql = "UPDATE documentos set numeracion=numeracion + 1
                                WHERE  documento_id='$prefijo'";
            $result = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                die(MsgOut("Error al actualizar numeracion", "Error DB : " . $dbconn->ErrorMsg()));
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
            if ($dbconn->Affected_Rows() == 0) {
                die(MsgOut("Error al actualizar numeracion", "El prefijo '$prefijo' no existe."));
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }

            //sacamos el numero de la factura de contado.
            $sql = "SELECT numeracion,prefijo FROM documentos
                                WHERE documento_id='$prefijo'";
            $results = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                die(MsgOut("Error al traer numeracion", "Error DB : " . $dbconn->ErrorMsg()));
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }

            if ($results->EOF) {
                die(MsgOut("Error al actualizar numeracion", "El tipo de numeracion '$prefijo' no existe."));
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
//MODIFICACI� PARA GUARGAR EL NUMERO DOC
            $this->GuardarNumero(true, &$dbconn);
//FIN MODIFICACI� PARA GUARGAR EL NUMERO DOC
            list($numerodoc['numero'], $numerodoc['prefijo']) = $results->fetchRow();

            return $numerodoc;
        }

        die(MsgOut("Error al actualizar numeracion..", "El documento &nbsp;['$prefijo']&nbsp; esta vacio."));
        return false;
    }

    /**
     *
     */
    function GuardarNumero($commit=true, &$dbconn) {
        //list($dbconn) = GetDBconn();
        if ($commit) {
            //$sql="CommitTrans();";
            $dbconn->CommitTrans();
        } else {
            $dbconn->RollbackTrans();
            //$sql="RollbackTrans();";
        }

        //$result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            die(MsgOut("Error al terminar la transaccion", "Error DB : " . $dbconn->ErrorMsg() . 'linea' . $this->lineError));
            return false;
        }
        return true;
    }

    /**
     * Busca el tercero_id y el plan_descripcion de la table planes.
     * @access public
     * @return array
     * @param string id del plan
     * @param int ingreso
     */
    function BuscarPlanes($PlanId) {
        list($dbconn) = GetDBconn();
        $query = "SELECT sw_tipo_plan FROM planes WHERE plan_id='$PlanId'";
        $results = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $sw = $results->fields[0];
        //soat
        if ($sw == 1) {
            $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, a.sw_tipo_plan, b.nombre_tercero, a.protocolos
                                                                FROM planes as a, terceros as b
                                                                WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
        }
        //cliente
        if ($sw == 0) {
            $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, a.sw_tipo_plan, b.nombre_tercero, a.protocolos
                                                FROM planes as a, terceros as b
                                                WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
        }
        //particular
        if ($sw == 2) {
            //$var['tipo_id_tercero']=$_SESSION['CAJA']['AUX']['tipo_id_paciente'];
            //$var['tercero_id']=$_SESSION['CAJA']['AUX']['paciente_id'];
            $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, a.sw_tipo_plan, b.nombre_tercero, a.protocolos
                                                FROM planes as a, terceros as b
                                                WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
        }
        //capitado
        if ($sw == 3) {
            $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, a.sw_tipo_plan, b.nombre_tercero, a.protocolos
                                                                FROM planes as a, terceros as b
                                                                WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
        }
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var = $result->GetRowAssoc($ToUpper = false);
        $result->Close();
        return $var;
    }

    /**
     *
     */
    function BuscarNombreCop($plan) {

        list($dbconn) = GetDBconn();
        $query = "SELECT nombre_copago,nombre_cuota_moderadora,tipo_liquidacion_cargo
                  FROM planes
                            WHERE plan_id=$plan
                            ";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer los planes";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var = $result->GetRowAssoc($ToUpper = false);
        return $var;
    }

    function TipoPlan(&$dbconn, $plan) {
        $query = "SELECT sw_tipo_plan, sw_facturacion_agrupada, sw_desc_nomina FROM planes
                                WHERE estado='1' and plan_id=$plan
                                and fecha_final >= now() and fecha_inicio <= now();";
        $results = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumero(false, &$dbconn);
            return false;
        }
        $var = $results->GetRowAssoc($ToUpper = false);
        return $var;
    }

    function ActualizarOsMaestro(&$dbconn, $cuenta) {
        $TipoId = $_SESSION['CAJA']['AUX']['tipo_id_paciente'];
        $PacienteId = $_SESSION['CAJA']['AUX']['paciente_id'];

        $filtroCuenta = $filtroCuenta1 = '';
        if (!empty($cuenta)) {
            $filtroCuenta = "AND numerodecuenta=$cuenta";
            $filtroCuenta1 = ", numerodecuenta=$cuenta";
        }

        $arr = $_SESSION['CAJA']['ARRAY_PAGO'];
        $lim = sizeof($arr);
        for ($j = 0; $j < $lim; $j++) {
            if ($arr[$j][tarifario_id] == 'SYS' AND $arr[$j][cargo] == 'IMD') {
                UNSET($arr[$j]);
            }
        }
        for ($i = 0; $i < sizeof($arr); $i++) {
            //nuevo duvan
            $query = "SELECT a.numero_orden_id,b.evento_soat FROM os_maestro a,os_ordenes_servicios b
                                        WHERE a.numero_orden_id=" . $arr[$i][numero_orden_id] . "
                                        AND a.orden_servicio_id=b.orden_servicio_id
                                        AND b.tipo_id_paciente='$TipoId'
                                        AND b.paciente_id='$PacienteId'";
            $res = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "fallo al buscar en os_maestro el estado";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }


            //revisamos que el numero de orden pertenezca al tipo_id_paciente y paciente_id
            if (empty($res->fields[0])) {
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }

            //Evento soat
            $evento_soat = $res->fields[1];
            //fin

            $query = "SELECT sw_estado FROM os_maestro
                                        WHERE numero_orden_id=" . $arr[$i][numero_orden_id] . "";
            $dato_x = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "fallo al buscar en os_maestro el estado";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
            //este if determina si sw_estado=0 es por q
            //es cargos realizados en la atenci� no cargados a una cuenta
            //y cambiamos el estado a 6 si el sw_estado=1 o 5 es por q es un
            //pago normal y cambiamos el estado a 2
            if ($dato_x->fields[0] == 1 OR $dato_x->fields[0] == 5) {
                $query = "UPDATE os_maestro SET sw_estado='2'$filtroCuenta1
                                            WHERE numero_orden_id='" . $arr[$i][numero_orden_id] . "' AND (sw_estado ='1' OR sw_estado='5')";
            } elseif ($dato_x->fields[0] == 0) {
                $query = "UPDATE os_maestro SET sw_estado='6'$filtroCuenta1
                                                WHERE numero_orden_id='" . $arr[$i][numero_orden_id] . "' AND sw_estado='0'";
                //se agrego sw_estado='0' debido a que puede llegar una orden de servicio
                //en este estado(cargos realizados en la atenci� no cargados a una cuenta quedando pendiente por cobrar)
            }
            $dbconn->Execute($query);
            //es importante determinar este error ya que en algun momento desde agenda
            // se podr�mandar un estado 8
            if ($dbconn->Affected_Rows() == 0) {
                $this->error = "1fallo actualizacion en os_maestro debido a un estado <> 1 y <> 5 <> 0";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al actualizar en os_maestro";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
        }
        //Evento soat
        if ($evento_soat) {
            $query = "INSERT INTO ingresos_soat (ingreso,evento) VALUES ('" . $_SESSION['CAJA']['AUX']['INGRESO'] . "',$evento_soat)";
            $dbconn->Execute($query);
            if ($dbconn->Affected_Rows() == 0) {
                $this->error = "fallo ingreso en ingresos_soat";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
        }
        //Fin
        return true;
    }

    function LlamarVentanaFinal($boton=false) {
        echo " ";

        //cuando boton esa true es q hizo factura
        unset($_SESSION['CAJA']['PROFESIONAL']);
        unset($_SESSION['CAJA']['TOTAL_EFECTIVO']); //se destruye la variable de session..
        unset($_SESSION['CAJA']['SUBTOTAL']);
        unset($_SESSION['CAJA']['SAL']);
        unset($_SESSION['CAJA']['BONO']);
        unset($_SESSION['ARR_UPDATE_AGENDA']);
        unset($_SESSION['ARREGLO_CITAS_INCUMPLIDAS']);
        $_SESSION['SW_ARR_CITA'] = 0;
        $arreglo1 = array();
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
        list($dbconn) = GetDBconn();



        $query1 = "
                                        SELECT  numerodecuenta
                                        FROM        cuentas
                                        WHERE       ingreso = '" . $_SESSION['CAJA']['AUX']['INGRESO'] . "'
                            ";

        $result1 = $dbconn->Execute($query1);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "ERROR AL TRAER SW DE AUTOMATICOS DE DEPTOS CARGO.";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $cuenta = $result1->fields[0];

        if (!empty($_SESSION['CAJA']['FACTURA']['EMPRESA'])) {
            $a = 1;
        } else {
            $a = 0;
        }

        if (empty($boton)) {
            $boton = '';
            $msg = 'DATOS GUARDADOS SATISFACTORIAMENTE - NUMERO DE CUENTA   ' . $cuenta;
        } else {
            $boton = 'factura';
            $msg = 'FACTURA GENERADA SATISFACTORIAMENTE - NUMERO DE CUENTA   ' . $cuenta;
        }
//validacion de automatico
//MauroB
        //SESSION para el retorno desde os_atencion
        unset($_SESSION['OS_ATENCION']['sw']);
        $_SESSION['OS_ATENCION']['sw'] = 1;
        $sw_automatico = '0';
        list($dbconn) = GetDBconn();

        foreach ($_SESSION['OS_ATENCION']['op'] AS $op => $valores) {
            $datos = explode(",", $valores);
            $query = "
                                        SELECT  sw_cumplido_automatico
                                        FROM        departamentos_cargos
                                        WHERE       departamento = '" . $_SESSION['LABORATORIO']['DPTO'] . "'
                                                        AND cargo =  '" . $datos[1] . "'
                    ";

            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "ERROR AL TRAER SW DE AUTOMATICOS DE DEPTOS CARGO.";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            $sw_auto = $result->fields[0];
            if ($sw_auto[sw_cumplido_automatico] == '1') {
                $sw_automatico = '1';
            }
        }
        //$result->Close();

        if (($sw_automatico == '1') AND ($_SESSION['LABORATORIO']['SW_HONORARIO']) == 0) {
            $this->CallMetodoExterno('app', "Os_Atencion", "user", "CambiarEstadoACumplimiento", array('nom' => $_SESSION['OS_ATENCION']['nom'],
                'id_tipo' => $_SESSION['OS_ATENCION']['tipo'],
                'id' => $_SESSION['OS_ATENCION']['id'],
                'vect' => sizeof($_SESSION['OS_ATENCION']['op']),
                'op' => $_SESSION['OS_ATENCION']['op'],
                'accion' => 0,
                'retorno' => 0));
        }
//fin MauroB
        $arreglo = array('cuenta' => $_SESSION['CAJA']['AUX']['CUENTA'], 'switche_emp' => $a);
        $this->FormaMensaje($msg, 'CONFIRMACION', $accion, 'Volver', $boton, $arreglo, $arreglo1);
        return true;
    }

    /**
     * Metodo para la creacion de una cuenta con su respectivo ingreso
     *
     * @param object $dbconn Objeto de la conexion a la base de datos
     */
    function CrearCuentaIngreso(&$dbconn) {
        $query = "SELECT sw_tipo_plan, sw_afiliacion
                FROM planes
                WHERE estado='1' 
                and   plan_id=" . $_SESSION['CAJA']['AUX']['plan_id'] . "
                and   fecha_final >= now() 
                and   fecha_inicio <= now();";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la secuencia ingresos_ingreso_seq ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumero(false, &$dbconn);
            return false;
        }

        list($TipoPlan, $swAfiliados, $Protocolos, $swAutoSinBD) = $result->FetchRow();

        $query = "SELECT nextval('ingresos_ingreso_seq');";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la secuencia ingresos_ingreso_seq ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumero(false, &$dbconn);
            return false;
        }
        $IngresoId = $result->fields[0];
        $query = "INSERT INTO ingresos 
                  (
                    ingreso,
                    tipo_id_paciente,
                    paciente_id,
                    fecha_ingreso,
                    causa_externa_id,
                    via_ingreso_id,
                    comentario,
                    departamento,
                    estado,
                    fecha_registro,
                    usuario_id,
                    departamento_actual)
                VALUES($IngresoId,'" . $_SESSION['CAJA']['AUX']['tipo_id_paciente'] . "','" . $_SESSION['CAJA']['AUX']['paciente_id'] . "','now()','15','1','','" . $_SESSION['LABORATORIO']['DPTO'] . "','0','now()','" . UserGetUID() . "','" . $_SESSION['LABORATORIO']['DPTO'] . "');";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error ingresos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->lineError = __LINE__;
            $this->GuardarNumero(false, &$dbconn);
            return false;
        }

        $auto_ce = SessionGetVar("AutorizacionCE");
        if (is_numeric($auto_ce)) {
            $sql = "UPDATE autorizaciones ";
            $sql .= "SET    ingreso = " . $IngresoId . " ";
            $sql .= "WHERE  autorizacion = " . $auto_ce . "; ";

            $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error Actualizacion Autorizaciones";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "<br>" . $sql;
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
        }
        //bd afiliados
        if ($swAfiliados == 1) {
            if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php")) {
                $this->error = "Error";
                $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/notas_enfermeria/revision_sistemas.class.php";
                return false;
            }
            if (!class_exists('BDAfiliados')) {
                $this->error = "Error";
                $this->mensajeDeError = "NO EXISTE BD AFILIADOS";
                return false;
            }

            $class = New BDAfiliados($_SESSION['CAJA']['AUX']['tipo_id_paciente'], $_SESSION['CAJA']['AUX']['paciente_id'], $_SESSION['CAJA']['AUX']['plan_id']);
            $class->GetDatosAfiliado();
            if ($class->GetDatosAfiliado() == false) {
                $this->frmError["MensajeError"] = $class->mensajeDeError;
            }

            if (!empty($class->salida)) {
                unset($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
                $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'] = $class->salida;
            }

            if (!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador'])) {
                $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador'] = $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_empleador'];
                $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador'] = $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_id_empleador'];
                $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['empleador'] = $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador'];
                $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['telefono_empleador'] = $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_telefono_empresa'];
                $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['direccion_empleador'] = $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_direccion_empresa'];

                list($dbconn) = GetDBconn();
                $query = "SELECT * FROM empleadores
                                                    WHERE tipo_id_empleador='" . $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador'] . "'
                                                    AND empleador_id='" . $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador'] . "'";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error ingresos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->lineError = __LINE__;
                    $this->GuardarNumero(false, &$dbconn);
                }
                //no existe el empleador en la tabla
                if ($result->EOF) {
                    $query = "INSERT INTO empleadores(
                                                                                empleador_id,
                                                                                tipo_id_empleador,
                                                                                nombre,
                                                                                direccion,
                                                                                telefono,
                                                                                usuario_id)
                                                            VALUES('" . $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador'] . "','" . $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador'] . "','" . $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['empleador'] . "','" . $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['direccion_empleador'] . "','" . $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['telefono_empleador'] . "'," . UserGetUID() . ");";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error ingresos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->lineError = __LINE__;
                        $this->GuardarNumero(false, &$dbconn);
                        return false;
                    }
                }
                $result->Close();

                $query = "INSERT INTO ingresos_empleadores(
                                                                        empleador_id,
                                                                        tipo_id_empleador,
                                                                        ingreso)
                                                    VALUES('" . $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador'] . "','" . $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador'] . "',$IngresoId);";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error ingresos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->lineError = __LINE__;
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }
            }
        }
        //fin afiliados

        $query = "SELECT nextval('cuentas_numerodecuenta_seq');";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la secuencia cuentas_numerodecuenta_seq ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumero(false, &$dbconn);
            return false;
        }
        $Cuenta = $result->fields[0];

        if (empty($_SESSION['CAJA']['AUX']['sem'])) {
            $sem = 0;
        } else {
            $sem = $_SESSION['CAJA']['AUX']['sem'];
        }
        $query = "INSERT INTO cuentas (numerodecuenta,
                                                                                empresa_id,
                                                                                centro_utilidad,
                                                                                ingreso,
                                                                                plan_id,
                                                                                estado,
                                                                                usuario_id,
                                                                                fecha_registro,
                                                                                tipo_afiliado_id,
                                                                                rango,
                                                                                autorizacion_int,
                                                                                autorizacion_ext,
                                                                                semanas_cotizadas,
                                                                                sw_estado_paciente,
                                                                                fecha_cierre,
                                                                                usuario_cierre)
                                    VALUES($Cuenta,'" . $_SESSION['LABORATORIO']['EMPRESA_ID'] . "','" . $_SESSION['LABORATORIO']['CENTROUTILIDAD'] . "',$IngresoId,'" . $_SESSION['CAJA']['AUX']['plan_id'] . "','1','" . UserGetUID() . "','now()','" . $_SESSION['CAJA']['AUX']['afiliado'] . "','" . $_SESSION['CAJA']['AUX']['rango'] . "'," . $_SESSION['CAJA']['AUX']['auto'] . ",NULL,$sem,0,'now()','" . UserGetUID() . "');";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error cuentas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            //$dbconn->RollbackTrans();
            $this->lineError = __LINE__;
            $this->GuardarNumero(false, &$dbconn);
            return false;
        }

        //Esto lo hacemos para determinar si se hizo una cuenta, para tratar de
        // controlar el ghost de que se inserta en caja y no se esta insertando en ingresos, ni en cuenta <DUVAN>
        $query = "SELECT COUNT(*) FROM cuentas WHERE numerodecuenta='$Cuenta';";
        $rest = $dbconn->Execute($query);
        if ($rest->fields[0] < 1) {
            $this->error = "(cuenta) SE PERDIO LA SECCION, INTENTE EL PROCESO OTRA VEZ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->lineError = __LINE__;
            $this->GuardarNumero(false, &$dbconn);
            return false;
        }

        //verificar si a la orden se le adicionaron
        //insumos y/o medicamentos
        $query = "SELECT  count(*)
                          FROM    (
                                    SELECT a.orden_servicio_id
                                    FROM os_maestro a
                                    WHERE a.numero_orden_id=" . $_SESSION['CAJA']['ARRAY_PAGO'][0][numero_orden_id] . "
                                  ) a,
                                  os_maestro b,
                                  tmp_cuenta_imd c
                          WHERE   a.orden_servicio_id=b.orden_servicio_id 
                          AND     b.numero_orden_id=c.numero_orden_id; ";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        //ACTUALZAR numerodecuenta en tmp_cuenta_imd
        if ($result->fields[0] > 0) {
            $this->ActualizarTmp_cuenta_imd($_SESSION['CAJA']['ARRAY_PAGO'][0][numero_orden_id], $Cuenta, &$dbconn);
            IncludeLib("despacho_medicamentos");

            if (LiquidarIYMOrdenServicio($Cuenta, &$dbconn) == false) {
                echo $_SESSION['INV_MENSAJE_ERROR'];
                return false;
            }
        }
        //LORENA
        //verificar si a la orden se le adicionaron
        //cargos temporales
        $query = "SELECT  count(*)
                  FROM    (
                            SELECT  a.orden_servicio_id
                            FROM    os_maestro a
                            WHERE   a.numero_orden_id=" . $_SESSION['CAJA']['ARRAY_PAGO'][0][numero_orden_id] . "
                          ) a,
                          os_maestro b,
                          tmp_cuentas_cargos c
                  WHERE   a.orden_servicio_id=b.orden_servicio_id 
                  AND     b.numero_orden_id=c.numero_orden_id;";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }


        //ACTUALZAR numerodecuenta en tmp_cuentas_cargos
        if ($result->fields[0] > 0) {
            $this->ActualizarTmpCargosCuentas($_SESSION['CAJA']['ARRAY_PAGO'][0][numero_orden_id], $Cuenta, &$dbconn);
        }
        //fin lorena
        //llamar metodo de invbodegas para descargar el inventario y
        //cargar los insumos a la cuenta creada
        $_SESSION['CAJA']['AUX']['CUENTA'] = $Cuenta;
        $_SESSION['CAJA']['AUX']['INGRESO'] = $IngresoId;
        return true;
    }

    function ActualizarTmp_cuenta_imd($orden, $Cuenta, &$dbconn) {
        //list($dbconn) = GetDBconn();
        $query = "SELECT c.numero_orden_id
              FROM (SELECT a.orden_servicio_id
                    FROM os_maestro a
                    WHERE a.numero_orden_id=" . $orden . "
                    ) a,os_maestro b,tmp_cuenta_imd c
              WHERE a.orden_servicio_id=b.orden_servicio_id AND b.numero_orden_id=c.numero_orden_id;";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            for ($i = 0; $i < sizeof($var); $i++) {
                $query = "UPDATE tmp_cuenta_imd
                SET numerodecuenta=$Cuenta
                WHERE numero_orden_id=" . $var[$i]['numero_orden_id'] . ";";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al actualizar numerodecuenta tmp_cuenta_imd";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    function ActualizarTmpCargosCuentas($orden, $Cuenta, &$dbconn) {
        //list($dbconn) = GetDBconn();
        $query = "SELECT c.numero_orden_id
              FROM (SELECT a.orden_servicio_id
                    FROM os_maestro a
                    WHERE a.numero_orden_id=" . $orden . "
                    ) a,os_maestro b,tmp_cuentas_cargos c
              WHERE a.orden_servicio_id=b.orden_servicio_id AND b.numero_orden_id=c.numero_orden_id;";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            for ($i = 0; $i < sizeof($var); $i++) {
                $query = "UPDATE tmp_cuentas_cargos
                SET numerodecuenta=$Cuenta
                WHERE numero_orden_id=" . $var[$i]['numero_orden_id'] . ";";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al actualizar numerodecuenta tmp_cuenta_imd";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    //INSERTAR INSUMOS Y MEDICAMENTOS
    /**
     *
     */
    function GuardarTodosCargosIyM($Cuenta) {
        //$Cuenta=$_SESSION['CAJA']['AUX']['CUENTA'];
        $Ingreso = $_SESSION['CAJA']['AUX']['INGRESO'];
        $PlanId = $_SESSION['CAJA']['AUX']['plan_id'];
        //Nivel=$_REQUEST['Nivel'];
        //$TipoId=$_SESSION['OS_ATENCION']['tipo'];
        //$PacienteId=$_SESSION['OS_ATENCION']['id'];
        $Fecha = date('Y-m-d');

        list($dbconn) = GetDBconn();
        $query = "SELECT count(a.numerodecuenta)
                                        FROM tmp_cuenta_imd as a
                                        WHERE a.numerodecuenta=$Cuenta";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        /* if($result->fields[0]==0)
          {
          $this->frmError["MensajeError"]="NO HA AGREGADO NINGUN INSUMO.";
          if(!$this->LiquidacionOrden()){
          return false;
          }
          return true;
          } */
        $argu = array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso);
        $_SESSION['OS_ATENCION']['RETORNO']['contenedor'] = 'app';
        $_SESSION['OS_ATENCION']['RETORNO']['modulo'] = 'Os_Atencion';
        $_SESSION['OS_ATENCION']['RETORNO']['tipo'] = 'user';
        $_SESSION['OS_ATENCION']['RETORNO']['metodo'] = 'RetornoInsumos';
        $_SESSION['OS_ATENCION']['RETORNO']['argumentos'] = $argu;
        $_SESSION['OS_ATENCION']['CUENTA'] = $Cuenta;
        $_SESSION['OS_ATENCION']['DBCONN'] = $dbconn;
        $this->ReturnMetodoExterno('app', 'InvBodegas', 'user', 'LiquidarIYMOrdenServicio', array('db' => $dbconn));
        return true;
    }

    function CrearCuentaDetalle(&$dbconn, $Cuenta) {
        $i = 0;
        $vectliq = $_SESSION['CAJA']['AUX']['liq'];

        //EXTRAER LOS CARGOS DE INVENTARIOS
        $lim = sizeof($vectliq[cargos]);
        for ($k = 0; $k < $lim; $k++) {
            if ($vectliq[cargos][$k][tarifario_id] == 'SYS' AND $vectliq[cargos][$k][cargo] == 'IMD') {
                UNSET($vectliq[cargos][$k]);
            }
        }
        $dat = $_SESSION['CAJA']['AUX']['datos'];
//MODIFICACION DE COPAGO Y COUTA MODERADORA
        //insertamos cuentas_descuentos_copagos para dejar guardado en la tabla los descuentos
        //if($_SESSION['CAJA']['AUX']['liq']['descuentos']['cuota_paciente'])
        if ($_SESSION['CAJA']['VALORCOPAGO'] !== NULL) {
//                  $query = "insert into cuentas_descuentos_copagos
//                                        (   numerodecuenta,tipo_desc_id,valor,sw_pago,observacion,usuario_id)
//                                        VALUES
//                                        (".$_SESSION['CAJA']['AUX']['CUENTA'].",".$_SESSION['CAJA']['AUX']['liq']['descuentos']['cuota_paciente']['tipo_descuento'].",
//                                         ".$_SESSION['CAJA']['AUX']['liq']['descuentos']['cuota_paciente']['valor'].",'2','".$_SESSION['CAJA']['AUX']['liq']['descuentos']['observacion']."',".UserGetUID().")";
            $query = "INSERT INTO cuentas_modificacion_copago
                                        (
                                        numerodecuenta,
                                        valor,
                                        motivo_cambio_copago_id,
                                        observacion,
                                        fecha_registro,
                                        usuario_id
                                        )
                                        VALUES
                                        (" . $_SESSION['CAJA']['AUX']['CUENTA'] . "," . $_SESSION['CAJA']['VALORCOPAGO'] . ",'" . $_SESSION['CAJA']['TIPOCAMBIO'] . "','" . $_SESSION['CAJA']['OBSERVACION'] . "',now()," . UserGetUID() . ");";

            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
        }
        //insertamos cuentas_descuentos_copagos para dejar guardado en la tabla los descuentos
        //  if($_SESSION['CAJA']['AUX']['liq']['descuentos']['cuota_moderadora'])
        if ($_SESSION['CAJA']['VALORCUOTAMODERADORA'] !== NULL) {
//                  $query = "INSERT INTO cuentas_descuentos_copagos
//                                        (numerodecuenta,tipo_desc_id,valor,sw_pago,observacion,usuario_id)
//                                        VALUES
//                                        (".$_SESSION['CAJA']['AUX']['CUENTA'].",".$_SESSION['CAJA']['AUX']['liq']['descuentos']['cuota_moderadora']['tipo_descuento'].",
//                                            ".$_SESSION['CAJA']['AUX']['liq']['descuentos']['cuota_moderadora']['valor'].",'1','".$_SESSION['CAJA']['AUX']['liq']['descuentos']['cuota_moderadora']['observacion']."',".UserGetUID().")";
            $query = "INSERT INTO cuentas_modificacion_cuota_moderadora
                                        (
                                        numerodecuenta,
                                        valor,
                                        motivo_cambio_cuota_moderadora_id,
                                        observacion,
                                        fecha_registro,
                                        usuario_id
                                        )
                                        VALUES
                                        (" . $_SESSION['CAJA']['AUX']['CUENTA'] . "," . $_SESSION['CAJA']['VALORCUOTAMODERADORA'] . ",'" . $_SESSION['CAJA']['TIPOCAMBIO'] . "','" . $_SESSION['CAJA']['OBSERVACION'] . "',now()," . UserGetUID() . ");";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en cuentas_descuentos_copagos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
        }
        //FIN MODIFICACION DE COPAGO Y COUTA MODERADORA
        //2	PARTICULARES/
        //IncludeLib("tarifario_cargos");
        //FIN 2	PARTICULARES/
        $arreglo = $_SESSION['CAJA']['ARRAY_PAGO'];
        foreach ($vectliq[cargos] as $w => $liq) {
            $TarifarioId = $liq[tarifario_id];
            $Cargo = $liq[cargo];
            $cargo_cups = $this->BuscarCargoCups($liq[tarifario_id], $liq[cargo], &$dbconn);
            $Cantidad = $liq[cantidad];
            if (empty($Cantidad)) {
                $Cantidad = 1;
            }
            //**************************************
            //LIQUIDACION EN EL CASO DE PARTICULARES
            //**************************************
            /*                    $tipo_plan = $this->TipoPlan(&$dbconn,$_SESSION['CAJA']['AUX']['plan_id']);
              if($tipo_plan[sw_tipo_plan] == '2')
              {
              $LiquidarCargo = LiquidarCargo($_SESSION['CAJA']['AUX']['plan_id'],$TarifarioId,$Cargo,$Cantidad,$precio=0,$datosExcepcionesAdicionales,$tipoUninadTiempo);
              $Precio = $LiquidarCargo[precio_plan];
              $ValorCargo = $LiquidarCargo[valor_cargo];
              $ValorNo = $LiquidarCargo[valor_no_cubierto];
              }
              else
              {
              $Precio=$liq[precio_plan];
              $ValorCargo=$liq[valor_cargo];
              $ValorNo=$liq[valor_no_cubierto];
              } */
            //******************************************
            //FIN LIQUIDACION EN EL CASO DE PARTICULARES
            //******************************************
            $Precio = $liq[precio_plan];
            $ValorNo = $liq[valor_no_cubierto];
            $ValorCubierto = $liq[valor_cubierto];
            $Fecha = $liq[fecha_cargo];
            $ValorCargo = $liq[valor_cargo];
            $Facturado = $liq[facturado];
            $DescuentoEmp = $liq[valor_descuento_empresa];
            $DescuentoPac = $liq[valor_descuento_paciente];
            $Moderadora = $liq[cuota_moderadora];
            $codigo = $liq[codigo_agrupamiento_id];
            if (empty($codigo)) {
                $codigo = 'NULL';
            } else {
                $codigo = "'$codigo'";
            }
            $AutoExt = 'NULL';
            $AutoInt = 'NULL';
            $servicio = $liq[servicio_cargo];
            $query = "SELECT nextval('cuentas_detalle_transaccion_seq');";
            $result = $dbconn->Execute($query);
            $Transaccion = $result->fields[0];
            $query = "INSERT INTO cuentas_detalle (
                                            transaccion,
                                            empresa_id,
                                            centro_utilidad,
                                            numerodecuenta,
                                            departamento,
                                            tarifario_id,
                                            cargo,
                                            cantidad,
                                            precio,
                                            valor_cargo,
                                            valor_nocubierto,
                                            valor_cubierto,
                                            usuario_id,
                                            facturado,
                                            fecha_cargo,
                                            fecha_registro,
                                            valor_descuento_empresa,
                                            valor_descuento_paciente,
                                            autorizacion_int,
                                            autorizacion_ext,
                                            servicio_cargo,
                                            porcentaje_gravamen,
                                            sw_cuota_paciente,
                                            sw_cuota_moderadora,
                                            codigo_agrupamiento_id,
                                            cargo_cups,
                                            sw_cargue)
                                        VALUES ($Transaccion,'" . $_SESSION['LABORATORIO']['EMPRESA_ID'] . "','" . $_SESSION['LABORATORIO']['CENTROUTILIDAD'] . "',$Cuenta,'" . $_SESSION['LABORATORIO']['DPTO'] . "','" . $TarifarioId . "','" . $Cargo . "',$Cantidad,$Precio,$ValorCargo,$ValorNo,$ValorCubierto," . UserGetUID() . ",1,'now()','now()',$DescuentoEmp,$DescuentoPac,$AutoInt,$AutoExt," . $_SESSION['CAJA']['AUX']['serv'] . "," . $liq[porcentaje_gravamen] . ",'" . $liq[sw_cuota_paciente] . "','" . $liq[sw_cuota_moderadora] . "',$codigo,$cargo_cups,'4');";


            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {

                $this->error = "Error al Guardar en cuentas_detalles";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }

            //SI ES UNA CITA GUARDA EL PROFESIONAL
            if (!empty($_SESSION['CAJA']['PROFESIONAL']['tercero_id'])
                    AND !empty($_SESSION['CAJA']['PROFESIONAL']['tipo_id_tercero'])) {
                $query = "INSERT INTO cuentas_detalle_profesionales(
                                                                                                                            transaccion,
                                                                                                                            tipo_tercero_id,
                                                                                                                            tercero_id)
                                                VALUES($Transaccion,'" . $_SESSION['CAJA']['PROFESIONAL']['tipo_id_tercero'] . "','" . $_SESSION['CAJA']['PROFESIONAL']['tercero_id'] . "');";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error cuentas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->lineError = __LINE__;
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }
            }

            //FIN GUARDAR PROFESIONAL
            //MauroB
            //NO ESOY SEGURO DE ESTE CAMBIO
            //CASO: NO SE ESTAN CARGANDO LOS HONORARIOS A LOS
            //          MEDICOS QUE REALIZAN UN EXAMEN
//                  $arreglo=$_SESSION['CAJA']['ARRAY_PAGO'];
            if ($TarifarioId != 'SYS') {
                for ($j = 0; $j < sizeof($arreglo); $j++) {

                    if (!empty($arreglo[$j][os_maestro_cargos_id])) {
                        $query = "
                                                UPDATE os_maestro_cargos SET transaccion=$Transaccion
                                                WHERE numero_orden_id=" . $arreglo[$j][numero_orden_id] . " AND cargo='" . $Cargo . "'
                                                AND tarifario_id='" . $TarifarioId . "';
                                            ";
                        $dbconn->Execute($query);
                        //es importante determinar este error
                        if ($dbconn->Affected_Rows() == 0) {
                            $this->error = "fallo actualizacion [transaccion] os_maestro_cargos en CrearCuentaDetalle de CajaGeneral";
                            echo "fallo actualizacion [transaccion] os_maestro_cargos en CrearCuentaDetalle de CajaGeneral";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            //$dbconn->RollbackTrans();
                            $this->lineError = __LINE__;
                            $this->GuardarNumero(false, &$dbconn);
                            return false;
                        }
                        unset($arreglo[$j]);
                        $m = 0;
                        $lim = sizeof($arreglo);
                        for ($l = 0; $l < $lim;) {
                            if (isset($arreglo[$l])) {
                                $arreglo[$m] = $arreglo[$l];
                                $m++;
                                $l++;
                            } else {
                                $lim+=1;
                                $l++;
                            }
                        }
                        $j = sizeof($arreglo);
                    }
                }
            }
            //Fin MAuroB
            //$arr=$_SESSION['CAJA']['ARRAY_PAGO'];  //aqui esta el arreglo para cambiar los estados
            $arr = $_SESSION['CAJA']['ARRAY_PAGO_TMP'];  //aqui esta el arreglo para cambiar los estados
            // de los numero_de_orden_id que esta alli....estado 1 -> a 2 y si son de incumplimiento estado 2 a 2
            $lim = sizeof($arr);

            //EXTRAER LOS CARGOS DE INVENTARIOS
            for ($l = 0; $l < $lim; $l++) {
                if ($arr[$l][tarifario_id] == 'SYS' AND $arr[$l][cargo] == 'IMD') {
                    UNSET($arr[$l]);
                }
            }
            for ($j = 0; $j < sizeof($arr); $j++) {
                $query = "UPDATE os_maestro  SET numerodecuenta=" . $_SESSION['CAJA']['AUX']['CUENTA'] . "
                                        WHERE numero_orden_id='" . $arr[$j][numero_orden_id] . "'
                                        AND (sw_estado ='1' OR sw_estado='5' OR sw_estado='0');";
                //se agrego sw_estado='0' debido a que puede llegar una orden de servicio
                //en este estado(cargos realizados en la atenci� no cargados a una cuenta quedando pendiente por cobrar)
                $dbconn->Execute($query);
                //es importante determinar este error ya que en algun momento desde agenda
                // se podr�mandar un estado 8
                if ($dbconn->Affected_Rows() == 0) {
                    $this->error = "fallo actualizacion Nocuenta en os_maestro debido a un estado <> 1 y <> 5 <> 0";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->lineError = __LINE__;
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al actualizar en os_maestro";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->lineError = __LINE__;
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }

                $query = "SELECT DISTINCT autorizacion_int,autorizacion_ext FROM os_ordenes_servicios a,os_maestro b
                                                WHERE a.orden_servicio_id=b.orden_servicio_id
                                                AND b.numero_orden_id='" . $arr[$j][numero_orden_id] . "';";
                $res = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al traer os_ordenes_servicios";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->lineError = __LINE__;
                    $this->GuardarNumero(false);
                    return false;
                }
                if (!is_array($arr_auto)) {
                    $arr_auto[0] = $res->fields[0]; //auto interna
                    $arr_auto[1] = $res->fields[1]; //auto externa
                    if (empty($arr_auto[1])) {
                        $arr_auto[1] = 'NULL';
                    }
                }
            }//fin for
            $i++;
        }//fin foreach principal
        //LORENA borra los cargos temporales adicionedos
        $query = "DELETE FROM tmp_cuentas_cargos
              WHERE numerodecuenta=" . $_SESSION['CAJA']['AUX']['CUENTA'] . ";";

        $res = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer os_ordenes_servicios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->lineError = __LINE__;
            $this->GuardarNumero(false);
            return false;
        }
        //fin borra
//ACTUALIZACION FIN ACTUALIZACION DE CUENTAS 4/12/2005
        //foreach($vectliq as $w=>$liq)
        //{
        //VALORES PARA ACTUALIZAR EL TOTAL PACIENTE, TOTAL EMPRESA
        $TotalPaciente = $vectliq[valor_total_paciente];
        $TotalEmpresa = $vectliq[valor_total_empresa];
        $Copago = $vectliq[valor_cuota_paciente];
        $CuotaModeradora = $vectliq[valor_cuota_moderadora];
        $valor_descuento_paciente = $vectliq[valor_descuento_paciente];
        $TotalCuenta = $vectliq[valor_total_empresa] + $vectliq[valor_total_paciente] + $vectliq[valor_descuento_paciente];
        //FIN VALORES PARA ACTUALIZAR EL TOTAL PACIENTE, TOTAL EMPRESA
        /*                  if(empty($codigo))
          { $codigo='NULL'; }
          else
          {  $codigo="'$codigo'"; }
          $AutoExt='NULL'; $AutoInt='NULL';
          $servicio=$liq[servicio_cargo];
          $query="SELECT nextval('cuentas_detalle_transaccion_seq')";
          $result=$dbconn->Execute($query);
          $Transaccion=$result->fields[0]; */
        //ACTUALIZACION EN CUENTAS DE LOS TOTALES DEL PACIENTE Y LA EMPRES
        if ($TotalPaciente >= 0 AND $TotalEmpresa >= 0 AND $Copago >= 0 AND $CuotaModeradora >= 0 AND $valor_descuento_paciente >= 0) {
            $sql1 = "valor_total_paciente =$TotalPaciente, valor_total_empresa =$TotalEmpresa,
                                    valor_cuota_paciente =$Copago, valor_cuota_moderadora=$CuotaModeradora, total_cuenta=$TotalCuenta
                                    , valor_descuento_paciente=$valor_descuento_paciente";
        } elseif ($TotalPaciente >= 0 AND $TotalEmpresa >= 0) {
            $sql1 = "valor_total_paciente =$TotalPaciente,valor_total_empresa =$TotalEmpresa, total_cuenta=$TotalCuenta";
        } elseif ($TotalPaciente >= 0 AND $Copago >= 0) {
            $sql1 = "valor_total_paciente =$TotalPaciente,valor_cuota_paciente =$Copago, total_cuenta=$TotalCuenta";
        } elseif ($TotalPaciente >= 0 AND $CuotaModeradora >= 0) {
            $sql1 = "valor_total_paciente =$TotalPaciente, valor_cuota_moderadora=$CuotaModeradora, total_cuenta=$TotalCuenta";
        } elseif ($TotalPaciente >= 0 AND $valor_descuento_paciente >= 0) {
            $sql1 = "valor_total_paciente =$TotalPaciente, valor_descuento_paciente=$valor_descuento_paciente, total_cuenta=$TotalCuenta";
        } elseif ($TotalEmpresa >= 0 AND $Copago >= 0) {
            $sql1 = "valor_total_empresa =$TotalEmpresa,valor_cuota_paciente =$Copago, total_cuenta=$TotalCuenta";
        } elseif ($TotalEmpresa >= 0 AND $CuotaModeradora >= 0) {
            $sql1 = "valor_total_empresa =$TotalEmpresa,valor_cuota_moderadora=$CuotaModeradora, total_cuenta=$TotalCuenta";
        } elseif ($TotalEmpresa >= 0 AND $valor_descuento_paciente >= 0) {
            $sql1 = "valor_total_empresa =$TotalEmpresa, valor_descuento_paciente=$valor_descuento_paciente, total_cuenta=$TotalCuenta";
        } elseif ($Copago >= 0 AND $CuotaModeradora >= 0) {
            $sql1 = "valor_cuota_paciente =$Copago,valor_cuota_moderadora=$CuotaModeradora, total_cuenta=$TotalCuenta";
        } elseif ($Copago >= 0 AND $valor_descuento_paciente >= 0) {
            $sql1 = "valor_cuota_paciente =$Copago,valor_descuento_paciente=$valor_descuento_paciente, total_cuenta=$TotalCuenta";
        } elseif ($TotalPaciente >= 0 AND $TotalEmpresa >= 0 AND $Copago >= 0) {
            $sql1 = "valor_total_paciente =$TotalPaciente,valor_total_empresa =$TotalEmpresa,valor_cuota_paciente =$Copago, total_cuenta=$TotalCuenta";
        } elseif ($TotalPaciente >= 0 AND $TotalEmpresa >= 0 AND $CuotaModeradora >= 0) {
            $sql1 = "valor_total_paciente =$TotalPaciente,valor_total_empresa =$TotalEmpresa, valor_cuota_moderadora=$CuotaModeradora, total_cuenta=$TotalCuenta";
        } elseif ($Copago >= 0 AND $TotalEmpresa >= 0 AND $CuotaModeradora >= 0) {
            $sql1 = "valor_cuota_paciente =$Copago,valor_total_empresa =$TotalEmpresa, valor_cuota_moderadora=$CuotaModeradora, total_cuenta=$TotalCuenta";
        } elseif ($Copago >= 0 AND $TotalPaciente >= 0 AND $CuotaModeradora >= 0 AND $valor_descuento_paciente >= 0) {
            $sql1 = "valor_cuota_paciente =$Copago,valor_total_paciente =$TotalPaciente, valor_cuota_moderadora=$CuotaModeradora, valor_descuento_paciente=$valor_descuento_paciente
                        , total_cuenta=$TotalCuenta";
        } elseif ($Copago >= 0 AND $TotalPaciente >= 0 AND $CuotaModeradora >= 0) {
            $sql1 = "valor_cuota_paciente =$Copago,valor_total_paciente =$TotalPaciente, valor_cuota_moderadora=$CuotaModeradora
                        , total_cuenta=$TotalCuenta";
        } elseif ($TotalPaciente >= 0) {
            $sql1 = "valor_total_paciente =$TotalPaciente, total_cuenta=$TotalCuenta";
        } elseif ($TotalEmpresa >= 0) {
            $sql1 = "valor_total_empresa =$TotalEmpresa, total_cuenta=$TotalCuenta";
        } elseif ($Copago >= 0) {
            $sql1 = "valor_cuota_paciente =$Copago, total_cuenta=$TotalCuenta";
        } elseif ($CuotaModeradora >= 0) {
            $sql1 = "valor_cuota_moderadora=$valor_descuento_paciente, total_cuenta=$TotalCuenta";
        } elseif ($valor_descuento_paciente >= 0) {
            $sql1 = "valor_descuento_paciente=$valor_descuento_paciente, total_cuenta=$TotalCuenta";
        } else {
            $sql1 = "";
        }

        if (!empty($sql1)) {
            $query = "UPDATE cuentas
                                            SET $sql1
                                            WHERE numerodecuenta=$Cuenta;";
            //Este query no debe de funcionar pues los valores los calcula el trigger
            //esto no dede de estar funcionando y no se debe hacer
            /* $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en cuentas_detalles query estra�";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $this->lineError = __LINE__;
              $this->GuardarNumero(false,&$dbconn);
              return false;
              } */
        }
        //}
//FIN ACTUALIZACION DE CUENTAS 4/12/2005
        //si esta variable esta llena implica q debo de
        //guardar el cargo de descuento en cuentas detalle...
        if ($_SESSION['CAJA']['ARRAY_DESCUENTO']) {
            $TarifarioId = $_SESSION['CAJA']['ARRAY_DESCUENTO']['tarifario_id'];
            $cargo = $_SESSION['CAJA']['ARRAY_DESCUENTO']['cargo'];
            //$Cantidad=1;
            $codigo = 'NULL';
            $cargo_cups = $this->BuscarCargoCups($TarifarioId, $cargo, &$dbconn);
            $Precio = $_SESSION['CAJA']['ARRAY_DESCUENTO']['valor_cargo'];
            //$GravamenEmp=0;
            //$GravamenPac=0;
            //$ValorPac=0;
            //$ValorNo=0;
            //$ValorCubierto=0;
            //$Fecha=0;
            $ValorCargo = $_SESSION['CAJA']['ARRAY_DESCUENTO']['valor_cargo'];
            //$Facturado=1;
            //$DescuentoEmp=0;
            //$DescuentoPac=0;
            //$Moderadora=0;
            //$PorEmp=0;
            //$PorPac=0;
            $AutoExt = 'NULL';
            $AutoInt = 'NULL';
            $query = "INSERT INTO cuentas_detalle (
                                        empresa_id,centro_utilidad,
                                        numerodecuenta,departamento,
                                        tarifario_id,cargo,
                                        cantidad,precio,
                                        valor_cargo,valor_nocubierto,
                                        valor_cubierto,usuario_id,
                                        facturado,fecha_cargo,
                                        fecha_registro,valor_descuento_empresa,
                                        valor_descuento_paciente,autorizacion_int,
                                        autorizacion_ext,servicio_cargo,
                                        porcentaje_gravamen,sw_cuota_paciente,
                                        sw_cuota_moderadora,codigo_agrupamiento_id,
                                        cargo_cups,sw_cargue)
                                VALUES ('" . $_SESSION['LABORATORIO']['EMPRESA_ID'] . "'
                                    ,'" . $_SESSION['LABORATORIO']['CENTROUTILIDAD'] . "'
                                    ,$Cuenta
                                    ,'" . $_SESSION['LABORATORIO']['DPTO'] . "'
                                    ,'" . $TarifarioId . "'
                                    ,'" . $cargo . "'
                                    ,1
                                    ,$Precio
                                    ,$ValorCargo
                                    ,$ValorNo
                                    ,0
                                    ," . UserGetUID() . "
                                    ,0,'now()'
                                    ,'now()',0
                                    ,$Precio
                                    ,$AutoInt
                                    ,$AutoExt
                                    ," . $_SESSION['CAJA']['AUX']['serv'] . "
                                    ,0
                                    ,'',''
                                    ,$codigo
                                    ,'$cargo_cups','4');";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al guardar descuentos en cuentas detalle";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
        }
        return true;
    }

    function ControlTotalFactura(&$dbconn, $TotalAbono) {
        unset($_SESSION['CAJA']['VENTANA']);
        $TotalEmpresa = $_SESSION['CAJA']['AUX']['liq']['valor_total_empresa'];
        //datos del plan sw_tipo_plan 0=>cliente 1=>soat 3=>capitacion 2=>particular
        $DatPlan = $this->TipoPlan(&$dbconn, $_SESSION['CAJA']['AUX']['plan_id']);
        if ($DatPlan[sw_desc_nomina] == '1') {
            if ($TotalAbono < $_SESSION['CAJA']['AUX']['liq']['valor_no_cubierto']) {
                $TotalEmpresa = $_SESSION['CAJA']['AUX']['liq']['valor_no_cubierto'] - $TotalAbono;
            }
        }
        if ($this->CrearCuentaIngreso(&$dbconn) === true) {
            $this->CrearCuentaDetalle(&$dbconn, $_SESSION['CAJA']['AUX']['CUENTA']);
        } else {
            unset($_SESSION['INV_MENSAJE_ERROR']);
            $this->GuardarNumero(false, &$dbconn);
            return false;
        }


        //ajuste si el valor a pagar es mayor q el de la empresa
        //$TotalAbono > 0 si se hace para el paciente
        //$DatPlan[sw_tipo_plan]!=2 q no sea un particular
        //buscar el valor de los cargos
        $query = "SELECT COALESCE(SUM(valor_cargo),0) FROM cuentas_detalle WHERE numerodecuenta=" . $_SESSION['CAJA']['AUX']['CUENTA'] . ";";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error sumar valor cargos ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->lineError = __LINE__;
            $this->GuardarNumero(false, &$dbconn);
            return false;
        }
        
        
        $_SESSION['CAJA']['AUX']['sw_tipo_plan']=$DatPlan[sw_tipo_plan];
		$centro_utilidad = $_SESSION['LABORATORIO']['CENTROUTILIDAD'];
        if ($TotalAbono > 0 AND $DatPlan[sw_tipo_plan] != 2 AND $result->fields[0] < $TotalAbono) { //>=0 caso COC cuando modifican el valor a pagar
            $Cargo = ModuloGetVar('app', 'Facturacion_Fiscal', 'CargoAjuste');
            $Saldo = ($TotalAbono - ($_SESSION['CAJA']['AUX']['liq']['valor_cubierto']));

            $query = "SELECT nextval('cuentas_detalle_transaccion_seq');";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error INSERT INTO cuentas_detalle ";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
            
            $Transaccion = $result->fields[0];
            $query = "INSERT INTO cuentas_detalle (
                                                transaccion,
                                                empresa_id,
                                                centro_utilidad,
                                                numerodecuenta,
                                                departamento,
                                                tarifario_id,
                                                cargo,
                                                cantidad,
                                                precio,
                                                valor_cargo,
                                                usuario_id,
                                                facturado,
                                                fecha_cargo,
                                                fecha_registro,
                                                servicio_cargo,
                                                sw_cargue)
                                        VALUES ($Transaccion,'" . $_SESSION['LABORATORIO']['EMPRESA_ID'] . "'
                                            ,'" . $_SESSION['LABORATORIO']['CENTROUTILIDAD'] . "'
                                            ," . $_SESSION['CAJA']['AUX']['CUENTA'] . "
                                           ,'" . $_SESSION['LABORATORIO']['DPTO'] . "'
                                           ,'SYS'
                                           ,'$Cargo'
                                           ,1
                                           ,$Saldo
                                           ,$Saldo
                                           ," . UserGetUID() . "
                                           ,1
                                           ,'now()'
                                           ,'now()'
                                           ," . $_SESSION['CAJA']['AUX']['serv'] . "
                                           ,'3');";
            

            $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error INSERT INTO cuentas_detalle ";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
            if ($DatPlan[sw_tipo_plan] == 1){
                
                //AQUI VOY
                
                $exisinsoat = $this->TraerOtro($_SESSION['CAJA']['AUX']['INGRESO']);
                
                if (count($exisinsoat > 0)){

                    
                        $query = "
                                    SELECT tipo_id_paciente, paciente_id
                                    FROM ingresos
                                    WHERE ingreso = ".$_SESSION['CAJA']['AUX']['INGRESO']." ";
            
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->fileError = __FILE__;
                            $this->lineError = __LINE__;
                            return false;
                        }

                        while (!$result->EOF) {
                            $ing[] = $result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                        }
                        
                        $TIPOPADEA = $ing[0]['tipo_id_paciente'];
                        $PACIENDEA = $ing[0]['paciente_id'];
                        
                        $query = "
                                    SELECT evento
                                    FROM ingresos A INNER JOIN ingresos_soat B ON (A.ingreso = B.ingreso)
                                    WHERE A.tipo_id_paciente = '".$TIPOPADEA."'
                                        AND A.paciente_id = '".$PACIENDEA."'";
            
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->fileError = __FILE__;
                            $this->lineError = __LINE__;
                            return false;
                        }

                        while (!$result->EOF) {
                            $evento21[] = $result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                        }
                    
                        $eventoDEA = $evento21[0]['evento'];
						
                        $query = "INSERT INTO soat_cargos_atencion
                                (
                                    transaccion,
                                    ingreso,
                                    empresa_id,
                                    centro_utilidad,
                                    numerodecuenta,
                                    plan_id,
                                    departamento,
                                    tarifario_id,
                                    cargo,
                                    cantidad,
                                    precio,
                                    valor_cargo,
                                    evento
                                )
                                VALUES
                                (
                                    $Transaccion,
                                    ".$_SESSION['CAJA']['AUX']['INGRESO'].",
                                    '" . $_SESSION['LABORATORIO']['EMPRESA_ID'] . "',
                                    '" . $_SESSION['LABORATORIO']['CENTROUTILIDAD'] . "',
                                    ".$_SESSION['CAJA']['AUX']['CUENTA'].",
                                    ".$_SESSION['CAJA']['AUX']['plan_id'].",
                                    '" . $_SESSION['LABORATORIO']['DPTO'] . "',
                                    'SYS',
                                    '$Cargo',
                                    1,
                                    $Saldo,
                                    $Saldo,
                                    $eventoDEA
                                )";

                }else{
                    $query = "INSERT INTO soat_atencion_ambulatoria
                                (
                                        transaccion, 
                                        empresa_id,
                                        centro_utilidad,
                                        numerodecuenta,
                                        ingreso,
                                        plan_id,
                                        departamento,
                                        tarifario_id,
                                        cargo,
                                        cantidad,
                                        precio,
                                        valor_cargo
                                )
                                VALUES
                                (
                                        $Transaccion,
                                        '" . $_SESSION['LABORATORIO']['EMPRESA_ID'] . "',
                                        '" . $_SESSION['LABORATORIO']['CENTROUTILIDAD'] . "',
                                        ".$_SESSION['CAJA']['AUX']['CUENTA'].",
                                        ".$_SESSION['CAJA']['AUX']['INGRESO'].",
                                        ".$_SESSION['CAJA']['AUX']['plan_id'].",
                                        '" . $_SESSION['LABORATORIO']['DPTO'] . "',
                                        'SYS',
                                        '$Cargo',
                                        1,
                                        $Saldo,
                                        $Saldo
                                )";
                }

                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error INSERT INTO soat_atencion_ambulatoria ";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->lineError = __LINE__;
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }
            }
        }
        //fin ajuste cuenta

        if ($_SESSION[CumplirCita][cargo_cups] == '890203' OR $_SESSION[CumplirCita][cargo] == '890203'
                OR $_SESSION[CumplirCita][cargo_cups] == '890303' OR $_SESSION[CumplirCita][cargo] == '890303'
                OR $_SESSION[CumplirCita][cargo_cups] == '990212' OR $_SESSION[CumplirCita][cargo] == '990212'
                OR $_SESSION[CumplirCita][cargo_cups] == '890204' OR $_SESSION[CumplirCita][cargo] == '890204'
                OR $_SESSION[CumplirCita][cargo_cups] == '890304' OR $_SESSION[CumplirCita][cargo] == '890304') {
            $estado = "3";
        } else {
            $estado = "3";
        }
		
        $query = "UPDATE cuentas SET estado = $estado WHERE numerodecuenta=" . $_SESSION['CAJA']['AUX']['CUENTA'] . ";";
        $result = $dbconn->Execute($query);
        //es importante determinar este error
        if ($dbconn->Affected_Rows() == 0) {
            $this->error = "fallo actualizacion [estado] en cuentas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->lineError = __LINE__;
            $this->GuardarNumero(false, &$dbconn);
            return false;
        }

        if ($TotalAbono == 0 AND $TotalEmpresa == 0) {           //solo se actualiza os_maestro y va ha la ventana de volver
            $query = "UPDATE cuentas SET estado='0' WHERE numerodecuenta=" . $_SESSION['CAJA']['AUX']['CUENTA'] . ";";
            $result = $dbconn->Execute($query);
            //es importante determinar este error
            if ($dbconn->Affected_Rows() == 0) {
                $this->error = "fallo actualizacion [estado] en cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
            //$this->ActualizarOsMaestro(&$dbconn,$_SESSION['CAJA']['AUX']['CUENTA']);
            $_SESSION['CAJA']['VENTANA'] = false;
            //$this->LlamarVentanaFinal(false);
            //return true;
        } elseif ($TotalAbono > 0 OR ($TotalEmpresa > 0 AND ($DatPlan[sw_tipo_plan] == 0 OR $DatPlan[sw_tipo_plan] == 1))) {
            $vars = $this->BuscarPlanes($_SESSION['CAJA']['AUX']['plan_id']);
            $_SESSION['CAJA']['VENTANA'] = true;
        }
        $Cuenta = $_SESSION['CAJA']['AUX']['CUENTA'];
        //hace factura paciente

		//encontrar departamento_actual
		$query=" SELECT  i.departamento_actual
                              FROM     cuentas as a
							  INNER JOIN ingresos as i on a.ingreso = i.ingreso
                              WHERE   a.numerodecuenta=$Cuenta";
                $results = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) 
                {
                  $FormaMensaje->error = "Error al Cargar el Modulo";
                  $FormaMensaje->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
                }
                $departamento_actual = $results->fields[0];
                $results->Close();
				
        if ($TotalAbono > 0) {//caso COC CUANDO MODIFICAN EL VALOR A PAGAR(>=0)
            //-------------------TRAER EL TERCERO DE LA FACTURA DEL PACIENTE Y PARTICULAR
            IncludeLib('funciones_facturacion');
            $retorno = ResponsableFacturaPaciente($_SESSION['CAJA']['AUX']['tipo_id_paciente'], $_SESSION['CAJA']['AUX']['paciente_id'], $_SESSION['LABORATORIO']['EMPRESA_ID'], &$dbconn);
            $tipoTerceroFacPaciente = $retorno[tipo_id_tercero];
            $idTerceroFacPaciente = $retorno[tercero_id];

            //cuando es particular es 2  sw_tipo 1->cliente 0->paciente 2->particular
            if ($DatPlan[sw_tipo_plan] == 2) {
                $sw = 2;
            } else {
                $sw = 0;
            }
            //se hace la del paciente sw_clase_factura=0 contado

            if ($_SESSION['CAJA']['AUX']['liq']['valor_no_cubierto'] > 0) {

                //sacamos la numeracion de la factura de contado
                $var = $this->AsignarNumero($_SESSION['CAJAX']['TIPONUMERACION']['FACTURA'], &$dbconn);
                $Factura = $var[numero];
                $Prefijo = $var[prefijo];

                //guardamos en estas variables de session el numero y prefijo de la fac de contado.
                $_SESSION['FAC']['NUM'] = $Factura;
                $_SESSION['FAC']['PRE'] = $Prefijo;


                $query = "INSERT INTO fac_facturas(
                                                                    empresa_id,
                                                                    prefijo,
                                                                    factura_fiscal,
                                                                    estado,
                                                                    usuario_id,
                                                                    fecha_registro,
                                                                    plan_id,
                                                                    tipo_id_tercero,
                                                                    tercero_id,
                                                                    sw_clase_factura,
                                                                    documento_id,
                                                                    tipo_factura,
																	centro_utilidad,
																	departamento_actual)
                        VALUES('" . $_SESSION['LABORATORIO']['EMPRESA_ID'] . "','$Prefijo',$Factura,0," . UserGetUID() . ",'now()',
                        '" . $_SESSION['CAJA']['AUX']['plan_id'] . "','" . $tipoTerceroFacPaciente . "','" . $idTerceroFacPaciente . "',0," . $_SESSION['CAJAX']['TIPONUMERACION']['FACTURA'] . ",'$sw','$centro_utilidad','$departamento_actual');";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar fac_facturas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->lineError = __LINE__;
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }

                $_SESSION['CAJA']['FACTURA']['PACIENTE'] = array('prefijo' => $Prefijo, 'factura' => $Factura, 'tipoid' => $_SESSION['CAJA']['AUX']['tipo_id_paciente'], 'paciente' => $_SESSION['CAJA']['AUX']['paciente_id'], 'cuenta' => $Cuenta);

                $query = "INSERT INTO fac_facturas_cuentas(
                                                                        empresa_id,
                                                                        prefijo,
                                                                        factura_fiscal,
                                                                        numerodecuenta,
                                                                        sw_tipo)
                                                                VALUES('" . $_SESSION['CAJA']['EMPRESA'] . "','$Prefijo',$Factura,$Cuenta,$sw);";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar fac_facturas_cuentas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->lineError = __LINE__;
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }
            }
            $query = "UPDATE cuentas SET estado=3 WHERE numerodecuenta=$Cuenta;";
            $result = $dbconn->Execute($query);
            //es importante determinar este error
            if ($dbconn->Affected_Rows() == 0) {
                $this->error = "fallo actualizacion [estado] en cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar fac_facturas_cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }

            $query = "UPDATE ingresos SET estado='0' WHERE ingreso=" . $_SESSION['CAJA']['AUX']['INGRESO'] . ";";
            $result = $dbconn->Execute($query);
            //es importante determinar este error
            if ($dbconn->Affected_Rows() == 0) {
                $this->error = "fallo actualizacion [estado a 0] en ingresos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
        }//fin factura paciente
        //hace factura empresa
        if ($TotalEmpresa > 0 AND $DatPlan[sw_facturacion_agrupada] == 0 AND ($DatPlan[sw_tipo_plan] == 0 OR $DatPlan[sw_tipo_plan] == 1)) {
            //cambiamos numeraciones factura credito
            $va = $this->AsignarNumero($_SESSION['CAJAX']['TIPONUMERACION']['FACTURA_CRE'], &$dbconn);
            $Factura = $va[numero];
            $prefijo_cre = $va[prefijo];
            $_SESSION['FAC']['EMP_EMP'] = $_SESSION['LABORATORIO']['EMPRESA_ID'];
            $_SESSION['FAC']['NUM_EMP'] = $Factura;
            $_SESSION['FAC']['PRE_EMP'] = $prefijo_cre;

            //factura empresa
            $query = "INSERT INTO fac_facturas(
                                                                        empresa_id,
                                                                        prefijo,
                                                                        factura_fiscal,
                                                                        estado,
                                                                        usuario_id,
                                                                        fecha_registro,
                                                                        plan_id,
                                                                        tipo_id_tercero,
                                                                        tercero_id,
                                                                        sw_clase_factura,
                                                                        documento_id,
                                                                        tipo_factura,
																		centro_utilidad,
																		departamento_actual)
                                            VALUES('" . $_SESSION['LABORATORIO']['EMPRESA_ID'] . "','$prefijo_cre',$Factura,0," . UserGetUID() . ",'now()',
                                            '" . $_SESSION['CAJA']['AUX']['plan_id'] . "','" . $vars[tipo_id_tercero] . "','" . $vars[tercero_id] . "',1," . $_SESSION['CAJAX']['TIPONUMERACION']['FACTURA_CRE'] . ",'1','$centro_utilidad','$departamento_actual');";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar fac_facturas emp";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
            $_SESSION['CAJA']['FACTURA']['EMPRESA'] = array('prefijo' => $prefijo_cre, 'factura' => $Factura, 'tipoid' => $_SESSION['CAJA']['AUX']['tipo_id_paciente'], 'paciente' => $_SESSION['CAJA']['AUX']['paciente_id'], 'cuenta' => $Cuenta);
            //sw_tipo 1->cliente 0->paciente 2->particular
            $query = "INSERT INTO fac_facturas_cuentas(
                                                                    empresa_id,
                                                                    prefijo,
                                                                    factura_fiscal,
                                                                    numerodecuenta,
                                                                    sw_tipo)
                                                            VALUES('" . $_SESSION['CAJA']['EMPRESA'] . "','$prefijo_cre',$Factura,$Cuenta,'1');";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar fac_facturas_cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }

            $query = "UPDATE cuentas SET estado=0 WHERE numerodecuenta=$Cuenta;";
            $result = $dbconn->Execute($query);
            //es importante determinar este error
            if ($dbconn->Affected_Rows() == 0) {
                $this->error = "fallo actualizacion [estado a 0] en cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al actualizar cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }

            $query = "UPDATE ingresos SET estado='0' WHERE ingreso=" . $_SESSION['CAJA']['AUX']['INGRESO'] . ";";
            $result = $dbconn->Execute($query);
            //es importante determinar este error
            if ($dbconn->Affected_Rows() == 0) {
                $this->error = "fallo actualizacion [estado a 0] en ingresos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
        }//fin factura empresa
//CASO PLANES PARTICULAR

        if ($TotalAbono > 0 AND $TotalEmpresa <= 0 AND $DatPlan[sw_tipo_plan] == 2) {
            $query = "UPDATE cuentas SET estado='0' WHERE numerodecuenta=" . $Cuenta . ";";
            $result = $dbconn->Execute($query);
            //es importante determinar este error
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "fallo actualizacion [estado] en cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
        }
//FIN CASO PLANES PARTICULAR

        if ($_SESSION[CumplirCita][cargo_cups] == '890203' OR $_SESSION[CumplirCita][cargo] == '890203' 
            OR $_SESSION[CumplirCita][cargo_cups] == '890303' OR $_SESSION[CumplirCita][cargo] == '890303' 
            OR $_SESSION[CumplirCita][cargo_cups] == '990212' OR $_SESSION[CumplirCita][cargo] == '990212' 
            OR $_SESSION[CumplirCita][cargo_cups] == '890204' OR $_SESSION[CumplirCita][cargo] == '890204' 
            OR $_SESSION[CumplirCita][cargo_cups] == '890304' OR $_SESSION[CumplirCita][cargo] == '890304') {
            
            $query = "UPDATE cuentas SET estado='3' WHERE numerodecuenta=" . $Cuenta . ";";
            $result = $dbconn->Execute($query);
            //es importante determinar este error
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "fallo actualizacion [estado] en cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
            
            $query = "SELECT nextval('cuentas_numerodecuenta_seq');";
            $resultdea = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al traer la secuencia cuentas_numerodecuenta_seq ";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
            $Cuentadea = $resultdea->fields[0];
            
            $query = "SELECT * FROM cuentas WHERE numerodecuenta=" . $Cuenta . ";";
            $result = $dbconn->Execute($query);
            //es importante determinar este error
            if ($dbconn->Affected_Rows() == 0) {
                $this->error = "fallo actualizacion [estado] en cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }else{
                if (!$result->EOF) {
                    while (!$result->EOF) {
                        $vardea = $result->GetRowAssoc($ToUpper = false);
                        $result->MoveNext();
                    }
                }
                
            }
            
            $query = "INSERT INTO cuentas (numerodecuenta,
                            empresa_id,
                            centro_utilidad,
                            ingreso,
                            plan_id,
                            estado,
                            usuario_id,
                            fecha_registro,
                            tipo_afiliado_id,
                            rango,
                            autorizacion_int,
                            autorizacion_ext,
                            semanas_cotizadas,
                            sw_estado_paciente,
                            fecha_cierre,
                            usuario_cierre)
                       VALUES($Cuentadea
                            ,'" . $vardea['empresa_id'] . "'
                            ,'" . $vardea['centro_utilidad'] . "'
                            ,"  . $vardea['ingreso']."
                            ,'" . $vardea['plan_id'] . "'
                            ,'2'
                            ,'" . UserGetUID() . "'
                            ,'now()'
                            ,'" . $vardea['tipo_afiliado_id'] . "'
                            ,'" . $vardea['rango'] . "'
                            ,"  . $vardea['autorizacion_int'] . "
                            ,NULL
                            ,"  . $vardea['semanas_cotizadas'] . "
                            ,0
                            ,'now()'
                            ,'" . UserGetUID() . "');";
            
            
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                //$dbconn->RollbackTrans();
                $this->lineError = __LINE__;
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
        }

        $this->ActualizarOsMaestro(&$dbconn, $_SESSION['CAJA']['AUX']['CUENTA']);

        return true;
    }

    /**
     * La funcion  InsertarHospitalizacion2 se encarga de obtener los datos para la creacion
     * de una factura de caja,(para CONSULTA EXTERNA), siguiendo estos pasos:
     *
     * @access public
     * @return boolean
     */
    function InsertarHospitalizacion2() {
        $Cajaid = $_SESSION['CAJA']['CAJAID'];
        $spy = $_REQUEST['spy'];
        $Cuenta = $_SESSION['CAJA']['CUENTA'];
        $TipoId = $_SESSION['CAJA']['AUX']['tipo_id_paciente'];
        $PacienteId = $_SESSION['CAJA']['AUX']['paciente_id'];
        $Nivel = $_REQUEST['Nivel'];
        $PlanId = $_SESSION['CAJA']['AUX']['plan_id'];
        $Ingreso = $_REQUEST['Ingreso'];
        $banco = $_REQUEST['banco'];
        $girador = $_REQUEST['girador'];
        $TipoCuenta = $_SESSION['CAJA']['TIPOCUENTA'];
        $usuario = UserGetUID();
        $cutilidad = $_SESSION['CAJA']['CENTROUTILIDAD'];
        $empresa = $_SESSION['CAJA']['EMPRESA'];
        $Tiponumeracion = $_SESSION['CAJA']['TIPONUMERACION'];
        // $Tiponumeracion=$_SESSION['CAJA']['TIPONUMERACION'];
        $TipoFactura = $_SESSION['LABORATORIO']['TIPOFACTURACION'];
        //$recibo=$this->recibo;///numero caja
        $cheque = $_REQUEST['cheque'];
        //$efectivo=$_REQUEST['efectivo'];
        $tarjetad = $_REQUEST['tarjetad'];
        $tarjetac = $_REQUEST['tarjetac'];
        $FechaHoy = date("Y-m-d H:i:s");
        $estado = '0';
        $Ttarjeta = $tarjetac + $tarjetad;
        $FechaC = date("Y-m-d H:i:s"); //el ingde la caja es de hoy....

        if ($_SESSION['CAJA']['TIPOCUENTA'] == '05') {
            $TotalAbono = $_REQUEST['Apagar'];
        }
        $efectivo = $TotalAbono - $Ttarjeta - $cheque - $_SESSION['CAJA']['BONO']; //sacando las restas del total abonado sacamos cuanto fue el efectivo.
        unset($_SESSION['CAJA']['FACTURA']);
        //$this->Facturacion();//tener en cuenta esta funcion..
        //unsetiamos $_SESSION['FAC'] ya que contiene el numero y prefijo del paciente q va a ser
        //insertado en fac_facturas_contado
        unset($_SESSION['FAC']);

        /*         * *********************PARTE DE INSERCION DE LA FACTURACION************************* */

        list($dbconn) = GetDBconn();
//        $dbconn->debug = true;//STEVEN
        $dbconn->BeginTrans();
        $this->ControlTotalFactura(&$dbconn, $TotalAbono);


        //encontrar departamento_actual
		$query=" SELECT  i.departamento_actual
                              FROM     cuentas as a
							  INNER JOIN ingresos as i on a.ingreso = i.ingreso
                              WHERE   a.numerodecuenta=$Cuenta";
                $results = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) 
                {
                  $FormaMensaje->error = "Error al Cargar el Modulo";
                  $FormaMensaje->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
                }
                $departamento_actual = $results->fields[0];
                $results->Close();
        
        if ($TotalAbono > 0) {
            $tercero = $this->BuscarPlanes($_SESSION['CAJA']['AUX']['plan_id']);
            $Tercero = $tercero[tercero_id];
            $TipoTercero = $tercero[tipo_id_tercero];
            if ($tercero[sw_tipo_plan] == 2) {
                $sw = 2;
            } else {
                $sw = 0;
            }
            //esta parte cambio con la nueva numeracion,ya no traemos con el recibo sino q con la factura
            //  $numerodoc=AsignarNumeroDocumento($_SESSION['CAJA']['TIPONUMERACION']['RECIBO']);
            $recibo = $_SESSION['FAC']['NUM'] + 1;
            $prefijo = $_SESSION['FAC']['PRE'];
            //unsetiamos $_SESSION['FAC'] ya que contiene el numero y prefijo del paciente q va a ser
            //insertado en fac_facturas_contado
            //unset($_SESSION['FAC']);
            $Cuenta = $_SESSION['CAJA']['CUENTA']; //este numero de cuenta es virtual....
            //y es solo para borrar los temporales el verdadero nuemro de cuenta
            //es $_SESSION['CAJA']['AUX']['CUENTA']....
            //revisar esto con cuidado..
            //esta variable de session se llena para despues realizar la consulta respectiva
            //mediante este numero de recibo y sacar los datos q van a ser impresos..
            $_SESSION['CAJA']['AUX']['NUMERO_RECIBO_DE_CAJA'] = $recibo;

            $datos_ter = $this->RetornarDatosoCrearTercero($Tercero, $TipoTercero, $dbconn);
            $TipoTercero = $datos_ter[TipoTercero];
            $Tercero = $datos_ter[Tercero];

            $query1 = "SELECT   prefijo_fac_contado,
                                         documento_recibo_caja
                           FROM      cajas_rapidas
                           WHERE    caja_id='$Cajaid'
                           AND        empresa_id='$empresa'
                           ;";
            $result = $dbconn->Execute($query1); //  GuardarNumeroDocumento(true);
            $dbconn->ErrorNo();
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al traer datos de cajas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                GuardarNumeroDocumento(false);
                return false;
            }
            $documento_id = $result->fields[1]; //trae documento_id de cajas(osea el campo tipo_numeracion)
            $cuenta_tipo = $result->fields[0];



            if (empty($_SESSION['CAJA']['BONO'])) {
                $_SESSION['CAJA']['BONO'] = 0;
            }
            if ($_SESSION['CAJA']['AUX']['liq']['valor_cuota_moderadora'] > 0 OR $_SESSION['CAJA']['AUX']['liq']['valor_cuota_paciente'] > 0) {
                $numerodoc = AsignarNumeroDocumento($documento_id);
                $recibo = $numerodoc['numero'];
                $prefijo = $numerodoc['prefijo'];

                $_SESSION['FAC']['NUM'] = $recibo;
                $_SESSION['FAC']['PRE'] = $prefijo;

                if ($TotalAbono > 0)
                    $TotalAbono = $_SESSION['CAJA']['AUX']['liq']['valor_cuota_moderadora'] + $_SESSION['CAJA']['AUX']['liq']['valor_cuota_paciente'];
                if ($efectivo > 0)
                    $efectivo = $_SESSION['CAJA']['AUX']['liq']['valor_cuota_moderadora'] + $_SESSION['CAJA']['AUX']['liq']['valor_cuota_paciente'];
                if ($cheque > 0)
                    $cheque = $_SESSION['CAJA']['AUX']['liq']['valor_cuota_moderadora'] + $_SESSION['CAJA']['AUX']['liq']['valor_cuota_paciente'];
                if ($Ttarjeta > 0)
                    $Ttarjeta = $_SESSION['CAJA']['AUX']['liq']['valor_cuota_moderadora'] + $_SESSION['CAJA']['AUX']['liq']['valor_cuota_paciente'];

                $sqls = "INSERT into fac_facturas_contado(
                                                                          empresa_id,
                                                                          factura_fiscal,
                                                                          centro_utilidad,
                                                                          prefijo,
                                                                          total_abono,
                                                                          total_efectivo,
                                                                          total_cheques,
                                                                          total_tarjetas,
                                                                          tipo_id_tercero,
                                                                          tercero_id,
                                                                          estado,
                                                                          fecha_registro,
                                                                          usuario_id,
                                                                          caja_id,
                                                                          total_bonos,
                                                                          numerodecuenta,
                                                                          sw_cuota_moderadora
                                                                      )
                          VALUES                               (
                                                                       '$empresa',
                                                                       '$recibo',
                                                                       '$cutilidad',
                                                                       '$prefijo',
                                                                       $TotalAbono,
                                                                       $efectivo,
                                                                       $cheque,
                                                                       $Ttarjeta,
                                                                       '$TipoTercero',
                                                                       '$Tercero',
                                                                       $estado,
                                                                       '$FechaHoy',
                                                                       $usuario,
                                                                       '$Cajaid'," . $_SESSION['CAJA']['BONO'] . ",
                                                                       " . $_SESSION['CAJA']['AUX']['CUENTA'] . ",
                                                                       '1'
                                                                      );";

                $result = $dbconn->Execute($sqls); //  GuardarNumeroDocumento(true);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al insertar en fac_facturas_contado";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }

                $query = "INSERT INTO fac_facturas(
                                                                    empresa_id,
                                                                    prefijo,
                                                                    factura_fiscal,
                                                                    estado,
                                                                    usuario_id,
                                                                    fecha_registro,
                                                                    plan_id,
                                                                    tipo_id_tercero,
                                                                    tercero_id,
                                                                    sw_clase_factura,
                                                                    documento_id,
                                                                    tipo_factura,
																	centro_utilidad,
																	departamento_actual)
                                                VALUES      ('$empresa',
                                                                    '$prefijo',
                                                                    $recibo,
                                                                    0,
                                                                    " . UserGetUID() . ",
                                                                    'now()',
                                                                     '" . $_SESSION['CAJA']['AUX']['plan_id'] . "',
                                                                     '" . $_SESSION['CAJA']['AUX']['tipo_id_paciente'] . "',
                                                                     '" . $_SESSION['CAJA']['AUX']['paciente_id'] . "',
                                                                     0,
                                                                     " . $documento_id . ",
                                                                     '" . $sw . "',
																	 '$cutilidad',
																	 '$departamento_actual');";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar fac_facturas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->lineError = __LINE__;
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }

                $query1 = "INSERT INTO fac_facturas_cuentas
                                                     ( empresa_id,
                                                       prefijo,
                                                       factura_fiscal,
                                                       numerodecuenta,
                                                       sw_tipo)
                                  VALUES        ( '$empresa',
                                                         '$prefijo',
                                                         $recibo,
                                                         " . $_SESSION['CAJA']['AUX']['CUENTA'] . ",
                                                         '" . $sw . "');";
                $result = $dbconn->Execute($query1);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar fac_facturas_cuentas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->lineError = __LINE__;
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }




                GuardarNumeroDocumento(true);
            }
            if ($_SESSION['CAJA']['AUX']['liq']['valor_no_cubierto'] > 0) {
                if ($TotalAbono > 0)
                    $TotalAbono = $_SESSION['CAJA']['AUX']['liq']['valor_no_cubierto'];
                if ($efectivo > 0)
                    $efectivo = $_SESSION['CAJA']['AUX']['liq']['valor_no_cubierto'];
                if ($cheque > 0)
                    $cheque = $_SESSION['CAJA']['AUX']['liq']['valor_no_cubierto'];
                if ($Ttarjeta > 0)
                    $Ttarjeta = $_SESSION['CAJA']['AUX']['liq']['valor_no_cubierto'];
                $numerodoc1 = AsignarNumeroDocumento($documento_id);
                $recibo = $numerodoc1['numero'];
                $prefijo = $numerodoc1['prefijo'];
                $_SESSION['FAC']['NUM'] = $recibo;
                $_SESSION['FAC']['PRE'] = $prefijo;

                // insercion de fac_facturas_contado 
                $sqls = "INSERT into fac_facturas_contado(
                                                                          empresa_id,
                                                                          factura_fiscal,
                                                                          centro_utilidad,
                                                                          prefijo,
                                                                          total_abono,
                                                                          total_efectivo,
                                                                          total_cheques,
                                                                          total_tarjetas,
                                                                          tipo_id_tercero,
                                                                          tercero_id,
                                                                          estado,
                                                                          fecha_registro,
                                                                          usuario_id,
                                                                          caja_id,
                                                                          total_bonos,
                                                                          numerodecuenta,
																		  sw_cuota_moderadora
                                                                      )
                          VALUES                               (
                                                                       '$empresa',
                                                                       '$recibo',
                                                                       '$cutilidad',
                                                                       '$prefijo',
                                                                       $TotalAbono,
                                                                       $efectivo,
                                                                       $cheque,
                                                                       $Ttarjeta,
                                                                       '$TipoTercero',
                                                                       '$Tercero',
                                                                       $estado,
                                                                       '$FechaHoy',
                                                                       $usuario,
                                                                       '$Cajaid'," . $_SESSION['CAJA']['BONO'] . ",
                                                                       " . $_SESSION['CAJA']['AUX']['CUENTA'] . ",
																	   '2'
                                                                      );";
                $result = $dbconn->Execute($sqls); //  GuardarNumeroDocumento(true);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al insertar en fac_facturas_contado";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }

                $query = "INSERT INTO fac_facturas(
                                                                    empresa_id,
                                                                    prefijo,
                                                                    factura_fiscal,
                                                                    estado,
                                                                    usuario_id,
                                                                    fecha_registro,
                                                                    plan_id,
                                                                    tipo_id_tercero,
                                                                    tercero_id,
                                                                    sw_clase_factura,
                                                                    documento_id,
                                                                    tipo_factura,
																	centro_utilidad,
																	departamento_actual)
                                                VALUES      ('$empresa',
                                                                    '$prefijo',
                                                                    $recibo,
                                                                    0,
                                                                    " . UserGetUID() . ",
                                                                    'now()',
                                                                     '" . $_SESSION['CAJA']['AUX']['plan_id'] . "',
                                                                     '" . $_SESSION['CAJA']['AUX']['tipo_id_paciente'] . "',
                                                                     '" . $_SESSION['CAJA']['AUX']['paciente_id'] . "',
                                                                     0,
                                                                     " . $documento_id . ",
                                                                     '" . $sw . "',
																	 '$cutilidad',
																	 '$departamento_actual');";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar fac_facturas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->lineError = __LINE__;
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }

                $query1 = "INSERT INTO fac_facturas_cuentas
                                                     ( empresa_id,
                                                       prefijo,
                                                       factura_fiscal,
                                                       numerodecuenta,
                                                       sw_tipo)
                                  VALUES        ( '$empresa',
                                                         '$prefijo',
                                                         $recibo,
                                                         " . $_SESSION['CAJA']['AUX']['CUENTA'] . ",
                                                         '" . $sw . "');";
                $result = $dbconn->Execute($query1);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar fac_facturas_cuentas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->lineError = __LINE__;
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }




                GuardarNumeroDocumento(true);
            }


            $fr = "SELECT COUNT(cheque_mov_id) FROM tmp_cheques_mov where numerodecuenta=$Cuenta;";
            $res = $dbconn->Execute($fr);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }

            if ($res->fields[0] > 0) {
                $bucon = "  UPDATE tmp_cheques_mov 
                            SET       recibo_caja='$recibo', 
                                         prefijo='$prefijo', 
                                         estado='$estado'
                            WHERE  numerodecuenta=$Cuenta;";
                $res = $dbconn->Execute($bucon);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }
                $cons1 = "INSERT INTO chequesf_mov(
                                                                    cheque_mov_id,
                                                                    empresa_id,
                                                                    centro_utilidad,
                                                                    factura_fiscal,
                                                                    prefijo,
                                                                    banco,
                                                                    cta_cte,
                                                                    cheque,
                                                                    girador,
                                                                    fecha_cheque,
                                                                    total,
                                                                    fecha,
                                                                    estado,
                                                                    usuario_id,
                                                                    fecha_registro,
                                                                    sw_postfechado
                                                                 )
                                                                  SELECT cheque_mov_id,
                                                                              empresa_id,
                                                                              centro_utilidad,
                                                                              recibo_caja,
                                                                              prefijo,
                                                                              banco,
                                                                              cta_cte,
                                                                              cheque,
                                                                              girador,
                                                                              fecha_cheque,
                                                                              total,
                                                                              fecha,
                                                                              estado,
                                                                              usuario_id,
                                                                             fecha_registro,
                                                               CASE WHEN fecha_cheque > DATE(now()) THEN '1' ELSE '0' END AS sw_postfechado from tmp_cheques_mov 
                                                               WHERE    numerodecuenta=$Cuenta;";
                $re = $dbconn->Execute($cons1);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }

                $cons2 = "INSERT INTO  confirmacion_chef(  consecutivo,
                                                                           cheque_mov_id,
                                                                           entidad_confirma,
                                                                           funcionario_confirma,
                                                                           numero_confirmacion,
                                                                           fecha,
                                                                           usuario_id
                                                                         )
                                                                          SELECT  consecutivo,
                                                                                       cheque_mov_id,
                                                                                       entidad_confirma,
                                                                                       funcionario_confirma,
                                                                                       numero_confirmacion,
                                                                                       fecha,
                                                                                       usuario_id
                                                                          FROM    tmp_confirmacion_che 
                                                                          WHERE  numerodecuenta=$Cuenta";
                $re = $dbconn->Execute($cons2);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }
            }

            $fr = "SELECT COUNT(tarjeta_mov_id) FROM tmp_tarjetas_mov_credito where numerodecuenta=$Cuenta;";
            $res = $dbconn->Execute($fr);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }

            if ($res->fields[0] > 0) {
                $buscon = " UPDATE  tmp_tarjetas_mov_credito 
                            SET        recibo_caja='$recibo', 
                                         prefijo='$prefijo'
                            WHERE   numerodecuenta=$Cuenta;";
                $re = $dbconn->Execute($buscon);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }

                $cons2 = "INSERT INTO tarjetasf_mov_credito
                                            (
                                              tarjeta_mov_id,
                                              tarjeta,
                                              empresa_id,
                                              centro_utilidad,
                                              factura_fiscal,
                                              prefijo,
                                              fecha,
                                              autorizacion,
                                              socio,
                                              fecha_expira,
                                              autorizado_por,
                                              total,
                                              usuario_id,
                                              fecha_registro,
                                              tarjeta_numero
                                            )
                                            SELECT  tarjeta_mov_id,
                                                        tarjeta,
                                                        empresa_id,
                                                        centro_utilidad,
                                                        recibo_caja,
                                                        prefijo,
                                                        fecha,
                                                        autorizacion,
                                                        socio,
                                                        fecha_expira,
                                                        autorizado_por,
                                                        total,
                                                        usuario_id,
                                                        fecha_registro,
                                                        tarjeta_numero 
                                            FROM    tmp_tarjetas_mov_credito 
                                            WHERE  numerodecuenta=$Cuenta;";
                $re = $dbconn->Execute($cons2);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }
                $cons2 = "INSERT INTO  confirmacion_tarf
                                            (
                                              consecutivo,
                                              tarjeta_mov_id,
                                              entidad_confirma,
                                              funcionario_confirma,
                                              numero_confirmacion,
                                              fecha,
                                              usuario_id
                                            )
                                            SELECT  consecutivo,
                                                         tarjeta_mov_id,
                                                         entidad_confirma,
                                                         funcionario_confirma,
                                                         numero_confirmacion,
                                                         fecha,
                                                         usuario_id
                                            FROM    tmp_confirmacion_tar 
                                            WHERE  numerodecuenta=$Cuenta";
                $re = $dbconn->Execute($cons2);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }
            }

            $fr = "SELECT COUNT(tarjeta_mov_db_id) FROM tmp_tarjetas_mov_debito where numerodecuenta=$Cuenta;";
            $res = $dbconn->Execute($fr);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumero(false);
                return false;
            }

            if ($res->fields[0] > 0) {
                $bu = "  UPDATE  tmp_tarjetas_mov_debito 
                       SET        recibo_caja='$recibo', prefijo='$prefijo'
                       WHERE   numerodecuenta=$Cuenta;";
                $re = $dbconn->Execute($bu);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }

                $cons3 = "INSERT INTO tarjetasf_mov_debito
                                           (
                                              empresa_id,
                                              centro_utilidad,
                                              factura_fiscal,
                                              prefijo,
                                              autorizacion,
                                              tarjeta,
                                              total,
                                              tarjeta_numero
                                           )
                                            SELECT  empresa_id,
                                                         centro_utilidad,
                                                         recibo_caja,
                                                         prefijo,
                                                         autorizacion,
                                                         tarjeta,
                                                         total,
                                                         tarjeta_numero
                                            FROM     tmp_tarjetas_mov_debito 
                                            WHERE   numerodecuenta=$Cuenta;";
                $re = $dbconn->Execute($cons3);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }
            }
        }//FIN IF DE CAJA
        //si esto existe deber�realizar la actualizacion
        if ($_SESSION['ARR_UPDATE_AGENDA']) {
            for ($i = 0; $i < sizeof($_SESSION['ARR_UPDATE_AGENDA']); $i++) {
                $bu = "UPDATE agenda_citas_asignadas  SET sw_atencion=3
                      WHERE agenda_cita_asignada_id=" . $_SESSION['ARR_UPDATE_AGENDA'][$i] . "
                      AND   sw_atencion <> 3";
                $re = $dbconn->Execute($bu);


                //es importante determinar este error ya que en algun momento desde agenda
                // se puede generar un error.
                if ($dbconn->Affected_Rows() == 0) {
                    $this->error = "fallo al cambiar el estado de agenda_citas_asignadas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }



                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al actualizar agenda_citas_asignadas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumero(false, &$dbconn);
                    return false;
                }
            }
        }

        if (!empty($Cuenta)) {
            $qx = " DELETE FROM tmp_confirmacion_tar where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_confirmacion_che where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_tarjetas_mov_debito  where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_tarjetas_mov_credito  where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_cheques_mov where numerodecuenta=$Cuenta;
                                DELETE FROM tmp_caja_bonos where numerodecuenta=$Cuenta;";
            $dbconn->Execute($qx);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al borrar las tablas temporales en el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        }


        //LLAMADO A FUNCION QUE EJECUTA EL WS DE CREACION DE TERCEROS PARA FI
        //$result_ws = $this->CrearTerceroWS($TipoId, $PacienteId);

			IncludeLib('WSIntegracionParticularesFI');
			CrearTerceroWS($TipoId, $PacienteId, $PlanId);
		
			if(!empty($_SESSION['CAJA']['FACTURA']['PACIENTE']['factura'])){
           IncludeLib("calcular_impuestos");
           $resultadoFI = UpdateImpuestosFacFacturas($empresa, $_SESSION['CAJA']['FACTURA']['PACIENTE']['prefijo'], $_SESSION['CAJA']['FACTURA']['PACIENTE']['factura']);
           
           IncludeLib("WSInsertarFacturaAgrupada");
           $resultadoFI = InsertarFacturaAgrupadaFI($empresa, $_SESSION['CAJA']['FACTURA']['PACIENTE']['prefijo'], $_SESSION['CAJA']['FACTURA']['PACIENTE']['factura']);
       }

        $this->GuardarNumero(true, &$dbconn);
        $this->LlamarVentanaFinal($_SESSION['CAJA']['VENTANA']);

        return true;
    }

    function BuscarCargoCups($tarifario, $cargo, $dbconn) {
        $query = "SELECT cargo_base FROM tarifarios_equivalencias WHERE cargo='$cargo'
        AND tarifario_id='$tarifario';";
        $res = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer el cargo_cups";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumero(false);
            return false;
        }
        if (empty($res->fields[0])) { //si esta vacio el cargo_base puede ser
        //debido a que es 'INC_CITA' incumplimiento de citas , por lo
        //tanto no va a traer ningun resultado de la consulta
            $rest = 'NULL';
            return $rest;
        } else {
            $rest = "'" . $res->fields[0] . "'";
            return $rest;
        }
    }

    function RetornarDatosoCrearTercero($Tercero, $TipoTercero, &$dbconn) {
        $query = "SELECT COUNT(*) FROM terceros WHERE tercero_id='$Tercero'
        AND tipo_id_tercero='$TipoTercero';";
        $res = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al revisar si existen datos en terceros";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            //$dbconn->RollbackTrans();
            $this->GuardarNumero(false, &$dbconn);
            return false;
        }
        if ($res->fields[0] > 0) {
            return array('Tercero' => $Tercero, 'TipoTercero' => $TipoTercero);
        } else {
            $i = 0;
            $query = "SELECT btrim(primer_apellido||' '||segundo_apellido||' ' ||
              primer_nombre||' '||segundo_nombre,'') as nombre,residencia_direccion,
              tipo_pais_id,tipo_dpto_id ,tipo_mpio_id
              FROM pacientes WHERE paciente_id='$Tercero'
              AND tipo_id_paciente='$TipoTercero'";
            $res = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al extraer datos en pacientes";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                //$dbconn->RollbackTrans();
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
            $va[] = $res->GetRowAssoc($ToUpper = false);

            if (empty($va[0]['residencia_direccion'])) {
                $dir = '-';
            }
            $query = "INSERT INTO terceros
              (tipo_id_tercero,
              tercero_id,
              nombre_tercero,
              tipo_pais_id,
              tipo_mpio_id,
              tipo_dpto_id,
              direccion,
              usuario_id)
              VALUES
              (
                '$TipoTercero',
                '$Tercero',
                '" . $va[0][nombre] . "',
                '" . $va[0][tipo_pais_id] . "',
                '" . $va[0][tipo_mpio_id] . "',
                '" . $va[0][tipo_dpto_id] . "',
                '$dir',
                " . UserGetUID() . ")";
            $res = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al insertar en terceros";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                //$dbconn->RollbackTrans();
                $this->GuardarNumero(false, &$dbconn);
                return false;
            }
            return array('Tercero' => $Tercero, 'TipoTercero' => $TipoTercero);
        }
        return true;
    }

    function BuscarInfoPlan($planid) {
        unset($_SESSION['CAJA']['AUX']['protocolo']);
        list($dbconn) = GetDBconn();
        $query = "SELECT plan_id,sw_tipo_plan, sw_afiliacion,protocolos FROM planes
                              WHERE estado='1' and plan_id=$planid
                              and fecha_final >= now() and fecha_inicio <= now()";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la informacion de plan";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $plan = $result->GetRowAssoc($ToUpper = false);
        $_SESSION['CAJA']['AUX']['protocolo'] = $plan[protocolos];
        $result->Close();


        if (($plan[sw_tipo_plan] == 0 AND $plan[sw_afiliacion] == 1) OR ($plan[sw_afiliacion] == 1)) {
            if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php")) {
                $this->error = "Error";
                $this->mensajeDeError = "No se pudo incluir : classes/notas_enfermeria/revision_sistemas.class.php";
                return false;
            }
            if (!class_exists('BDAfiliados')) {
                $this->error = "Error";
                $this->mensajeDeError = "no existe BDAfiliados";
                return false;
            }

            $class = New BDAfiliados($_SESSION['CAJA']['AUX']['tipo_id_paciente'], $_SESSION['CAJA']['AUX']['paciente_id'], $planid);
            if ($class->GetDatosAfiliado() == false) {
                $this->error = $class->error;
                $this->mensajeDeError = $class->mensajeDeError;
                return false;
            }

            if (!empty($class->salida)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     * Revisamos si a esta caja ya la han hecho una devolucion..
     * esta parte se va a a cambiar debido a las nuevas tablas de contabilidad.
     */

    function GetDevolucion() {
        list($dbconn) = GetDBconn();
        $sql = "    SELECT SUM(total_devolucion) FROM rc_devoluciones
                            WHERE caja_id='" . $_SESSION['CAJA']['CAJAID'] . "'
                            AND empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'
                            AND centro_utilidad='" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "'
                            AND numerodecuenta='" . $_SESSION['CAJA']['CUENTA'] . "'";
        $resulta = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al consultar en rc_devoluciones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        return $resulta->fields[0];
    }

    //funcion q inserta las devoluciones ..
    /*
     *  esta parte se va a a cambiar debido a las nuevas tablas de contabilidad.
     *  funcion que guarda las devoluciones q hace la caja de tipo
     *  hospitalaria.
     *
     */
    function InsertarDevolucion($TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $Cuenta, $Tiponumeracion, $FechaHoy, $Devolucion, $Saldo, $bool) {
        //'sal'=>$resultado,'Cuenta'=>$Cuenta,'Cama'=>$Cama,'PlanId'=>$PlanId,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'FechaC'=>$FechaC,
        //'FechaHoy'=>$FechaHoy,'Nivel'=>$Nivel,'Tiponumeracion'=>$Tiponumeracion,'TipoCuenta'=>$TipoCuenta,'Cajaid'=>$Cajaid
        $total = 0;
        if (!empty($_REQUEST['devol']) || !empty($_REQUEST['TipoId']) || !empty($_REQUEST['PacienteId']) || !empty($_REQUEST['PlanId'])) {
            //DATOS PARA EL MANEJO DE LA DEVULUCION
            $Tiponumeracion = $_SESSION['CAJA']['TIPONUMERACION_DEVOLUCIONES'];
            $Devolucion = $_REQUEST['devol'];
            $Saldo = abs($_REQUEST['sal']);
            $_SESSION['tmp']['TipoId'] = $_REQUEST['TipoId'];
            $_SESSION['tmp']['PacienteId'] = $_REQUEST['PacienteId'];
            $_SESSION['tmp']['Nivel'] = $_REQUEST['Nivel'];
            $_SESSION['tmp']['PlanId'] = $_REQUEST['PlanId'];
            $_SESSION['tmp']['Pieza'] = $_REQUEST['Pieza'];
            $_SESSION['tmp']['Cama'] = $_REQUEST['Cama'];
            $_SESSION['tmp']['FechaC'] = $_REQUEST['FechaC'];
            $_SESSION['tmp']['Ingreso'] = $_REQUEST['Ingreso'];
            $_SESSION['tmp']['Cuenta'] = $_REQUEST['Cuenta'];
            $_SESSION['tmp']['Ingreso'] = $_REQUEST['Ingreso'];
            $_SESSION['tmp']['FechaHoy'] = $_REQUEST['FechaHoy'];
            $_SESSION['tmp']['Cajaid'] = $_REQUEST['Cajaid'];
            $_SESSION['tmp']['devol'] = $_REQUEST['devol'];
            $_SESSION['tmp']['sal'] = abs($_REQUEST['sal']);
            //
            $_SESSION['tmp']['TipoCuenta'] = $_REQUEST['TipoCuenta'];
        } else {
            $_REQUEST['TipoId'] = $_SESSION['tmp']['TipoId'];
            $_REQUEST['PacienteId'] = $_SESSION['tmp']['PacienteId'];
            $_REQUEST['Nivel'] = $_SESSION['tmp']['Nivel'];
            $_REQUEST['PlanId'] = $_SESSION['tmp']['PlanId'];
            $_REQUEST['Pieza'] = $_SESSION['tmp']['Pieza'];
            $_REQUEST['Cama'] = $_SESSION['tmp']['Cama'];
            $_REQUEST['FechaC'] = $_SESSION['tmp']['FechaC'];
            $_REQUEST['Ingreso'] = $_SESSION['tmp']['Ingreso'];
            $_REQUEST['Cuenta'] = $_SESSION['tmp']['Cuenta'];
            $_REQUEST['Ingreso'] = $_SESSION['tmp']['Ingreso'];
            $_REQUEST['FechaHoy'] = $_SESSION['tmp']['FechaHoy'];
            $_SESSION['CAJA']['TIPONUMERACION_DEVOLUCIONES'] = $Tiponumeracion;
            $_REQUEST['Cajaid'] = $_SESSION['tmp']['Cajaid'];
            $_REQUEST['devol'] = $_SESSION['tmp']['devol'];
            $_REQUEST['sal'] = $_SESSION['tmp']['sal'];
            $_REQUEST['TipoCuenta'] = $_SESSION['tmp']['TipoCuenta'];
        }//$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Pieza,$Cama,$FechaC,$Ingreso,$FechaHoy,$Tiponumeracion

        if (!is_numeric($_REQUEST['devol']) OR empty($_REQUEST['devol'])
                OR $_REQUEST['devol'] <= 0) {
            $this->frmError["MensajeError"] = "DEBE COLOCAR UN VALOR NUMERICO MENOR O IGUAL A &nbsp;" . abs($_REQUEST['sal']) . "";
            $this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy);
            return true;
        }

        $Abono = $this->Abonos($_REQUEST['Cuenta']); //VALORES DE cuentas
        //DEVOLUCIONES SOBRE EL TOTAL DEL ABONO
        $total = $Abono[abono_efectivo] + $Abono[abono_tarjetas] + $Abono[abono_cheque];

        if ($_REQUEST['sal'] < 0 AND $_REQUEST['devol'] > abs($_REQUEST['sal'])) {
            $this->frmError["MensajeError"] = "FALLO: EL VALOR A DEVOLVER&nbsp;(" . $_REQUEST['devol'] . ")&nbsp;ES MAYOR QUE EL SALDO&nbsp;(" . $_REQUEST['sal'] . ")";
            $this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy);
            return true;
        }

        //$Abono=$this->AbonosCuenta($_REQUEST['Cuenta']);//VALORES DE recibos_caja
        //$TotalAbonoEfectivo
        //if ($_REQUEST['devol']>$Abono[totalefectivo])//VALORES DE recibos_caja
        //if ($_REQUEST['devol']>$Abono[abono_efectivo])//VALORES DE cuentas
        if ($_REQUEST['devol'] > $total) {//VALORES DE cuentas
            $this->frmError["MensajeError"] = "FALLO: EL VALOR A DEVOLVER&nbsp;(" . $_REQUEST['devol'] . ")&nbsp;ES MAYOR QUE&nbsp;(" . $total . ")";
            $this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy);
            return true;
        } else
        if ($_REQUEST['devol'] <= $total AND $_REQUEST['sal'] > 0 AND $bool <> '0') {
            /*          $this->frmError["MensajeError"]="DEBE AUTORIZAR EL VALOR A DEVOLVER&nbsp;->&nbsp;".$_REQUEST['devol']."";
              $this->FormaValidarUsuarioDevoluciones($TipoId,$PacienteId,$Nivel,$PlanId,$Pieza,$Cama,$FechaC,$Ingreso,$Cuenta,$Tiponumeracion,$FechaHoy,$Devolucion,$Saldo,$Cajaid); */
            $this->frmError["MensajeError"] = "FALLO: EL VALOR A DEVOLVER&nbsp;(" . $_REQUEST['devol'] . ")&nbsp;NO HAY SALDO A FAVOR&nbsp;(" . $_REQUEST['sal'] . ")";
            $this->CajaHospitalaria($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Pieza, $Cama, $FechaC, $Ingreso, $FechaHoy);
            return true;
            return true;
        }

        $numerodoc = AsignarNumeroDocumento($Tiponumeracion);
        $n_devolucion = $numerodoc['numero'];
        $prefijo = $numerodoc['prefijo'];

        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();  //Inicia la transaccion
        $sql = "INSERT INTO rc_devoluciones
                    (devolucion_id,
                    prefijo,
                    empresa_id,
                    centro_utilidad,
                    numerodecuenta,
                    total_devolucion,
                    estado,
                    fecha_registro,
                    usuario_id,
                    caja_id,
                    recibo_caja,
                    documento_id
                    )
                    VALUES
                    (nextval('rc_devoluciones_devolucion_id_seq'),
                        '$prefijo',
                        '" . $_SESSION['CAJA']['EMPRESA'] . "',
                        '" . $_SESSION['CAJA']['CENTROUTILIDAD'] . "',
                        '" . $_SESSION['CAJA']['CUENTA'] . "',
                        " . $_REQUEST['devol'] . ",
                        '0',
                        now(),
                        " . UserGetUID() . ",
                        " . $_SESSION['CAJA']['CAJAID'] . ",
                        $n_devolucion,
                        $Tiponumeracion
                    )";

        $resulta = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al insertar en rc_devoluciones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            GuardarNumeroDocumento(false);
            return false;
        }
        GuardarNumeroDocumento(true);
        $this->frmError["MensajeError"] = "DEVOLUCION REALIZADA SATISFACTORIAMENTE";
        $dbconn->CommitTrans();
        //$this->CajaHospitalaria($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Pieza,$Cama,$FechaC,$Ingreso,$FechaHoy);
        $this->CajaHospitalaria($_REQUEST['Cuenta'], $_REQUEST['TipoId'], $_REQUEST['PacienteId'], $_REQUEST['Nivel'], $_REQUEST['PlanId'], $_REQUEST['Pieza'], $_REQUEST['Cama'], $_REQUEST['FechaC'], $_REQUEST['Ingreso'], $_REQUEST['FechaHoy']);
        return true; //$_REQUEST['PlanId']
    }

    //FUNCION QUE CONSULTA EL TOTAL ABONO EFECTIVO DE LA CUENTA DADA
    function AbonosCuenta($Cuenta) {
        list($dbconn) = GetDBconn();
        $sql = "SELECT    SUM (B.total_efectivo) AS totalefectivo,
                                            SUM(B.total_cheques) AS totalcheques,
                                            SUM (B.total_tarjetas) AS totaltarjetas
                                FROM rc_detalle_hosp A, recibos_caja B, cuentas C
                                WHERE A.numerodecuenta=$Cuenta
                                            AND A.empresa_id=B.empresa_id
                                            AND A.centro_utilidad=B.centro_utilidad
                                            AND A.recibo_caja=B.recibo_caja
                                            AND A.prefijo=B.prefijo
                                            AND A.numerodecuenta=C.numerodecuenta
                                            AND B.estado IN ('0')
                                            AND B.empresa_id='" . $_SESSION['CAJA']['EMPRESA'] . "'";
        $resulta = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al seleccionar en rc_detalle_hosp, recibos_caja";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        list($total[totalefectivo], $total[totalcheques], $total[totaltarjetas]) = $resulta->fetchRow();
        return $total;
    }

    //-------------------------Rollo fiscal---------------------------------------------------------
    /*
     *  Esta funcion genera una copia de cada factura del dia, que se ha pagado en la caja
     *  en el momento que se realiza un cierre,obviamente se imprimiran tantas copias de facturas segn
     *  como aparezcan en el reporte de cierre de la caja.
     */
    function GenerarRolloFiscal() {
        $action = $_REQUEST['go_to']; //aqui va la direccion a donde debe volver..
        $_REQUEST['sw']; //aqui va si el rollo es de 1 de contado o 2 de credito..

        if ($_REQUEST['sw'] == 1) {//CONTADO
            $sw_pos = 0;
        }//CONTADO
        else {
            $sw_pos = 1;
        }//CREDITO
        $a = $_SESSION['CAJA']['VECTOR_CIERRE'];
        $x = 0;
        if (is_array($a)) {
            for ($i = 0; $i < sizeof($a); $i++) {
                $cuenta = $a[$i][numerodecuenta];
                $var = '';
                $var[0] = $this->EncabezadoFactura($cuenta);

                list($dbconn) = GetDBconn();
                if (!IncludeFile("classes/reports/reports.class.php")) {
                    $this->error = "No se pudo inicializar la Clase de Reportes";
                    $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
                    return false;
                }

                $query = "select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
                                                                        a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
                                                                        b.descripcion as desccargo, a.departamento, b.grupo_tipo_cargo, c.sw_tipo,
                                                                        e.texto1, e.texto2, e.mensaje, f.*
                                                                        from cuentas_detalle as a, tarifarios_detalle as b,
                                                                        fac_facturas_cuentas as c, documentos as e, fac_facturas as f
                                                                        where a.numerodecuenta=$cuenta and a.cargo=b.cargo
                                                                        and a.tarifario_id=b.tarifario_id
                                                                        and a.cargo!='DESCUENTO'
                                                                        and c.numerodecuenta=a.numerodecuenta
                                                                        and c.sw_tipo='$sw_pos'
                                                                        and c.prefijo=e.prefijo
                                                                        and c.prefijo=f.prefijo
                                                                        and c.factura_fiscal=f.factura_fiscal
                                                                        order by b.grupo_tipo_cargo desc;";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                while (!$result->EOF) {
                    $var[] = $result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }
                $result->MoveFirst();
                if (!$result->EOF) {
                    $classReport = new reports;
                    $impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte = 'pos');
                    $reporte = $classReport->PrintReport('pos', 'app', 'CajaGeneral', 'factura', $var, $impresora, $orientacion = '', $unidades = '', $formato = '', $html = 1);
                    if (!$reporte) {
                        $this->error = $classReport->GetError();
                        $this->mensajeDeError = $classReport->MensajeDeError();
                        unset($classReport);
                        return false;
                    }
                    $resultado = $classReport->GetExecResultado();
                    unset($classReport);
                    $x++;
                }

                if (!empty($resultado[codigo])) {
                    "El PrintReport retorno : " . $resultado[codigo] . "<br>";
                }
            }//fin for

            if ($x == 0) {
                $this->FormaMensaje('NO HAY ROLLO FISCAL GENERADO PARA ESTE TIPO DE FACTURAS', 'CONFIRMACION', $action, 'Volver', 'cierre');
                return true;
            }

            $this->FormaMensaje('ROLLO FISCAL GENERADO SATISFACTORIAMENTE', 'CONFIRMACION', $action, 'Volver', 'cierre');
            return true;
        } else {
            $this->FormaMensaje('EL ROLLO FISCAL NO SE PUDO IMPRIMIR,NOTIFICAR AL ADMIN', 'CONFIRMACION', $action, 'Volver', 'cierre');
            return true;
        }
    }

//-----------------------------------REPORTES--------------------------------------
    /**
     *
     */
    function EncabezadoFactura($cuenta) {
        list($dbconn) = GetDBconn();
        $query = "select (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos) as abonos,
                  a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
                  c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
                  e.primer_nombre||' '||e.segundo_nombre||' '||e.primer_apellido||' '||e.segundo_apellido as nombre,
                  e.residencia_telefono, e.residencia_direccion, d.departamento_actual as dpto, h.descripcion,
                  i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid,
                  i.id, j.departamento, k.municipio, d.fecha_registro, a.rango, Z.tipo_afiliado_nombre,
                  b.nombre_cuota_moderadora, b.nombre_copago, x.nombre as usuario, x.usuario_id,
                  a.valor_cuota_moderadora, a.valor_cuota_paciente, a.valor_nocubierto,
                  a.valor_total_paciente, a.valor_total_empresa, a.valor_descuento_paciente,
                  a.valor_descuento_empresa, a.valor_cubierto, a.total_cuenta
                  from cuentas as a, planes as b, terceros as c, pacientes as e, departamentos as  h,
                  empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d,
                  system_usuarios as x, tipos_afiliado as Z
                  where a.numerodecuenta=$cuenta and a.plan_id=b.plan_id and b.tercero_id=c.tercero_id
                  and b.tipo_tercero_id=c.tipo_id_tercero
                  and x.usuario_id=" . UserGetUID() . "
                  and a.tipo_afiliado_id=Z.tipo_afiliado_id
                  and d.ingreso=a.ingreso and d.tipo_id_paciente=e.tipo_id_paciente
                  and d.paciente_id=e.paciente_id
                  and a.empresa_id=i.empresa_id and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
                  and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id
                  and d.departamento_actual=h.departamento";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $var = $resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
        return $var;
    }

    /**
     *
     */
    function Reportes() {
        //$cuenta=$_SESSION['CAJA']['FACTURA']['PACIENTE']['cuenta'];
        $cuenta = $_SESSION['CAJA']['AUX']['CUENTA'];
        unset($_SESSION['CAJA']['FACTURA']['encabezado']);
        $var[0] = $this->EncabezadoFactura($cuenta);
        $var1[0] = $this->EncabezadoFactura($cuenta);
        $_SESSION['CAJA']['FACTURA']['encabezado'] = $var[0];

        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }
        //siempre se hace la del paciente
        $query = "select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
                                    a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
                                    b.descripcion as desccargo, a.departamento, b.grupo_tipo_cargo, c.sw_tipo,
                                    e.texto1, e.texto2, e.mensaje, f.*, d.sw_cuota_moderadora
                                    from cuentas_detalle as a, tarifarios_detalle as b,
                                    fac_facturas_cuentas as c, fac_facturas_contado d, documentos as e, fac_facturas as f
                                    where a.numerodecuenta=$cuenta and a.cargo=b.cargo
                                    and a.tarifario_id=b.tarifario_id
                                    and a.cargo!='DESCUENTO'
                                    and c.numerodecuenta=a.numerodecuenta
                                    and (c.sw_tipo=0 OR c.sw_tipo=2)
                                    and c.prefijo=e.prefijo
                                    and c.prefijo=f.prefijo
                                    and c.factura_fiscal=f.factura_fiscal
									and f.prefijo = d.prefijo
									and f.factura_fiscal = d.factura_fiscal
									and d.sw_cuota_moderadora = '1'
                                    order by b.grupo_tipo_cargo desc ";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $result->Close();
            $classReport = new reports;
            $impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte = 'pos');
            $reporte = $classReport->PrintReport('pos', 'app', 'CajaGeneral', 'factura', $var, $impresora, $orientacion = '', $unidades = '', $formato = '', $html = 1);
            if (!$reporte) {
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
            }

            $resultado = $classReport->GetExecResultado();
        }
        //unset($classReport);
        $query = "select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
                                    a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
                                    b.descripcion as desccargo, a.departamento, b.grupo_tipo_cargo, c.sw_tipo,
                                    e.texto1, e.texto2, e.mensaje, f.*,d.sw_cuota_moderadora
                                    from cuentas_detalle as a, tarifarios_detalle as b,
                                    fac_facturas_cuentas as c, fac_facturas_contado d, documentos as e, fac_facturas as f
                                    where a.numerodecuenta=$cuenta and a.cargo=b.cargo
                                    and a.tarifario_id=b.tarifario_id
                                    and a.cargo!='DESCUENTO'
                                    and c.numerodecuenta=a.numerodecuenta
                                    and (c.sw_tipo=0 OR c.sw_tipo=2)
                                    and c.prefijo=e.prefijo
                                    and c.prefijo=f.prefijo
                                    and c.factura_fiscal=f.factura_fiscal
									and f.prefijo = d.prefijo
									and f.factura_fiscal = d.factura_fiscal
									and d.sw_cuota_moderadora = '2'
                                    order by b.grupo_tipo_cargo desc ";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var1[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $result->Close();
            $classReport = new reports;
            $impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte = 'pos');
            $reporte = $classReport->PrintReport('pos', 'app', 'CajaGeneral', 'factura', $var1, $impresora, $orientacion = '', $unidades = '', $formato = '', $html = 1);
            if (!$reporte) {
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
            }

            $resultado = $classReport->GetExecResultado();
        }
        unset($classReport);
        $var = '';
        /* if(!empty($_SESSION['CAJA']['FACTURA']['EMPRESA']))
          {
          $cuenta=$_SESSION['CAJA']['FACTURA']['EMPRESA']['cuenta'];
          //$var[0]=$this->EncabezadoFactura($cuenta);
          $var[0]=$_SESSION['CAJA']['FACTURA']['encabezado'];
          $query = "select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
          a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
          b.descripcion as desccargo, a.departamento, b.grupo_tipo_cargo, c.sw_tipo,
          e.texto1, e.texto2, e.mensaje, f.*
          from cuentas_detalle as a, tarifarios_detalle as b,
          fac_facturas_cuentas as c, documentos as e, fac_facturas as f
          where a.numerodecuenta=$cuenta and a.cargo=b.cargo
          and a.tarifario_id=b.tarifario_id
          and a.cargo!='DESCUENTO'
          and c.numerodecuenta=a.numerodecuenta
          and c.sw_tipo=1
          and a.empresa_id=e.empresa_id
          and c.prefijo=e.prefijo
          and c.prefijo=f.prefijo
          and c.factura_fiscal=f.factura_fiscal
          order by b.grupo_tipo_cargo desc ";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
          }
          while(!$result->EOF)
          {
          $var[]=$result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
          }
          $result->Close();
          $classReport = new reports;
          $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
          $reporte=$classReport->PrintReport('pos','app','CajaGeneral','factura',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
          if(!$reporte){
          $this->error = $classReport->GetError();
          $this->mensajeDeError = $classReport->MensajeDeError();
          unset($classReport);
          return false;
          }
          $resultado=$classReport->GetExecResultado();
          unset($classReport);
          } */
        $var = '';
        if (!empty($resultado[codigo])) {
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }
        //aqui va donde vuelve
        $arreglo1 = array();
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
        //$this->FormaMensaje('IMPRESION HECHA SATISFACTORIAMENTE','CONFIRMACION',$accion,'Volver','factura');
        //esta parte es nueva en {sos} {duvan}
        if (!empty($_SESSION['CAJA']['FACTURA']['EMPRESA'])) {
            $a = 1;
        } else {
            $a = 0;
        }
        $arreglo = array('cuenta' => $_SESSION['CAJA']['AUX']['CUENTA'], 'switche_emp' => $a);
        $this->FormaMensaje('IMPRESION HECHA SATISFACTORIAMENTE', 'CONFIRMACION', $accion, 'Volver', 'factura', $arreglo, $arreglo1);

        return true;
    }

    function LlamaFormaMenuFacturacionConceptos() {
        $fact = new app_Facturacion_ConceptosHTML();
        $this->salida .= $fact->FormaMenuFacturacionConceptos($_REQUEST['empresa']);
        return true;
    }

    function FormaBusquedaFacturasConceptos() {
        $fact = new app_Facturacion_ConceptosHTML();
        $this->salida .= $fact->FormaBusquedaFacturasConceptosHTML($_REQUEST['empresa']);
        return true;
    }

    function LlamaFormaBuscar() {
        $fact = new app_Facturacion_ConceptosHTML();
        $this->salida .= $fact->BuscarFacturas($_REQUEST['empresa']);
        return true;
    }

    function LlamaFormaDetalleFacturaConcepto() {
        $fact = new app_Facturacion_ConceptosHTML();
        $tercero = split(" ",$_REQUEST['identificacion']);
        $impuestos = $this->TraerPorcentajeImpuestos($_REQUEST['empresa'],$tercero[1], $tercero[0]);
        
        $this->salida .= $fact->FormaDetalleFacturaConcepto($_REQUEST['fecha_registro'], $_REQUEST['factura_fiscal'], $_REQUEST['prefijo'], $_REQUEST['empresa'], $_REQUEST['nombre_tercero'], $_REQUEST['tipo_factura'], $_REQUEST['identificacion'], $_REQUEST['sw_clase_factura'], $impuestos);
        return true;
    }

    function LlamaFormaResponsable() {
        $fact = new app_Facturacion_ConceptosHTML();
        $this->salida .= $fact->FormaResponsable($_REQUEST['empresa']);
        return true;
    }

    function LlamarFormaBuscarEnvios() {
        $fact = new app_Facturacion_ConceptosHTML();
        $this->salida .= $fact->FormaBuscarEnvios('', $_REQUEST['empresa'], $_REQUEST['Plan']);
        return true;
    }

    function LlamaBuscarEnvios() {
        $fact = new app_Facturacion_ConceptosHTML();
        $this->salida .= $fact->BuscarEnvios($_REQUEST['FechaI'], $_REQUEST['FechaF'], $_REQUEST['prefijo'], $_REQUEST['numero'], $_REQUEST['empresa'], $_REQUEST['plan']);
        return true;
    }

    function LlamarFormaEnvio() {
        $fact = new app_Facturacion_ConceptosHTML();
        $this->salida .= $fact->FormaEnvio($_REQUEST[empresa], $_REQUEST[plan], $_REQUEST[datos]);
        return true;
    }

    function LlamaHacerEnvio() {
        $fact = new app_Facturacion_ConceptosHTML();
        $this->salida .= $fact->HacerEnvio($_REQUEST[empresa], $_REQUEST[Plan], $_REQUEST[Fecha_Envio], $_REQUEST[datos], $_REQUEST[enviod]);
        return true;
    }

    /*     * ******************************************************************
     *
     * ******************************************************************** */

    function BuscarTerceros() {
        $this->rqs = $_REQUEST;
        $this->pst['tercero_id'] = $this->rqs['tercero_id'];
        $this->pst['nombre_tercero'] = $this->rqs['nombre_tercero'];
        $this->pst['tipo_id_tercero'] = $this->rqs['tipo_id_tercero'];

        $this->Emp = $this->rqs['empresa'];

        $this->Terceros = array();
        $this->action1 = ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaBuscarTerceros', array("empresa" => $this->Emp));
        if ($this->pst['tercero_id'] || $this->pst['nombre_tercero'] || $this->pst['tipo_id_tercero']) {
            $fct = new app_Facturacion_Conceptos();
            $this->pst['empresa'] = $this->rqs['empresa'];
            $this->Terceros = $fct->ObtenerFacturasTerceros($this->pst, $this->Emp, SessionGetVar("DocumentosFacturacion"), $this->rqs['offset']);

            $this->conteo = $fct->conteo;
            $this->paginaActual = $fct->paginaActual;
            $this->action2 = ModuloGetURL('app', 'CajaGeneral', 'user', 'FormaBuscarTerceros', $this->pst);
            if (empty($this->Terceros))
                $this->frmError['MensajeError'] = "LA BUSQUEDA NO ARROJO NINGUN RESULTADO";
        }
    }

    /*
     *
     */

    function ObtenerTipoIdTercero() {
        $fct = new app_Facturacion_Conceptos();
        $tid = $fct->ObternerTiposIdTerceros();
        return $tid;
    }

    function GetDatosFactura($cuenta) {
        unset($_SESSION['CAJA']['FACTURA']['encabezado']);
        $var[0] = $this->EncabezadoFactura($cuenta);
        $_SESSION['CAJA']['FACTURA']['encabezado'] = $var[0];
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        //c.sw_tipo=0 PACIENTE
        //c.sw_tipo=2 PARTICULAR
        //siempre se hace la del paciente
        $query = " SELECT c.prefijo,
			  c.factura_fiscal, 
			  a.valor_nocubierto, 
			  a.precio, 
			  a.cargo, 
			  a.tarifario_id, 
			  a.cantidad, 
			  a.fecha_cargo, 
			  a.transaccion, 
			  b.descripcion as desccargo,
			  f.empresa_id, 
			  c.total_efectivo,
			  f.valor_cuota_paciente,
			  f.valor_nocubierto,
			  f.valor_cubierto,
			  f.valor_cuota_moderadora,
			  c.sw_cuota_moderadora
                   FROM   cuentas_detalle as a, 
                          tarifarios_detalle as b, 
                          fac_facturas_contado as c, 
                          cuentas as f 
                   WHERE  a.numerodecuenta=" . $cuenta . " 
                   AND    a.numerodecuenta=f.numerodecuenta
                   AND    a.cargo=b.cargo 
                   AND    a.tarifario_id=b.tarifario_id 
                   AND    a.cargo!='DESCUENTO' 
                   AND    c.numerodecuenta=a.numerodecuenta 
                   ";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();

        return $var;
    }

    //--GVILLOTA [RQ 5364]------------------------------------------------------

    function ObtenerSaldoAseguradoraPaciente($tipo_id,$paciente_id)
    {

        list($dbconnect) = GetDBconn();
        $sql = "SELECT saldo
                            FROM soat_eventos AS A
                            WHERE A.tipo_id_paciente='" . $tipo_id . "'
                              AND A.paciente_id='" . $paciente_id . "'";

        //echo "sql:$sql<br>";

        $result = $dbconnect->Execute($sql);

        if ($dbconnect->ErrorNo() != 0) {
            $this->error = "Error al buscar el Saldo en la tabla soat_eventos";
            $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
            return false;
        } else {
            //$i = 0;
            while (!$result->EOF) {
                //$vector[$i] = $result->GetRowAssoc($ToUpper = false);
                $saldo = $result->fields[0];
                $result->MoveNext();
                //$i++;
            }
        }

        //echo "saldo:$saldo<br>";

        $result->Close();
        return $saldo;
    }


    function ObtenerDatosPaciente($tipo_id,$paciente_id){

        list($dbconnect) = GetDBconn();

        $sql = "select pl.sw_tipo_plan
                from pacientes p
                inner join ingresos i on (i.paciente_id = p.paciente_id and i.tipo_id_paciente = p.tipo_id_paciente)
                inner join cuentas c on (c.ingreso = i.ingreso)
                inner join planes pl on (pl.plan_id = c.plan_id)
                where p.tipo_id_paciente='$tipo_id' and p.paciente_id='$paciente_id'
                order by c.fecha_registro desc
                limit 1";

        //echo "sql:$sql<br>";
        //if(!$rst = $this->ConexionBaseDatos($sql))

        $result = $dbconnect->Execute($sql);

        if ($dbconnect->ErrorNo() != 0)
        {
            $this->error = "Error al buscar los datos del plan con el ingreso '$ingreso'";
            return false;
        }
        else{

            if(!$result->EOF)
            {
                $tipoPlan = $result->fields[0];
            }

            //echo "tipoPlan:$tipoPlan<br>";

            if($tipoPlan == "1")
            {
                $vdatos[0] = "sisoat";
                $result->Close();
            }
            else{
                $vdatos[0] = "nosoat";
            }

            return $vdatos;

        }

    }


}

//fin clase user
?>