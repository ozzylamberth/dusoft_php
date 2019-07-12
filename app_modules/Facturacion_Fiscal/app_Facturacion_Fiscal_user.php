<?php

/* * ****************************************************************************
 * $Id: app_Facturacion_Fiscal_user.php,v 1.9 2011/02/23 21:54:04 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de la facturacion.
 * ****************************************************************************** */
/* * *****************************************************************************
 * Clase app_Facturacion_Fical_user
 *
 * Contiene los metodos para realizar el triage y admision de los pacientes de
 * urgencias
 * ****************************************************************************** */
IncludeClass('app_Facturacion_Permisos', '', 'app', 'Facturacion_Fiscal');
IncludeClass('Facturar', '', 'app', 'Facturar');

class app_Facturacion_Fiscal_user extends classModulo {

    var $limit;
    var $conteo;
    var $paginaActual;

    /**
     * Es el contructor de la clase
     * @return boolean
     */
    function app_Facturacion_Fiscal_user() {
        $this->limit = GetLimitBrowser();
        return true;
    }

    /**
     * Cambia el formato de la fecha de dd/mm/YY a YY/mm/dd
     * @access private
     * @return string
     * @param date fecha
     * @var    cad   Cadena con el nuevo formato de la fecha
     */
    function ConvFecha($fecha) {
        if ($fecha) {
            $fech = strtok($fecha, "/");
            for ($i = 0; $i < 3; $i++) {
                $date[$i] = $fech;
                $fech = strtok("/");
            }
            $cad = $date[2] . "-" . $date[1] . "-" . $date[0];
            return $cad;
        }
    }

    /*     * ********************************************************************************
     *
     * ********************************************************************************* */

    function main() {
        SessionDelVar("EmpresaFacturacion");
        SessionDelVar("CentroUtilidadFacturacion");
        $fct = new app_Facturacion_Permisos();
        $empresas = $fct->ObtenerPermisosFacturacion();

        $url[0] = 'app';                                        //contenedor
        $url[1] = 'Facturacion_Fiscal';         //m�ulo
        $url[2] = 'user';                                       //clase
        $url[3] = 'FormaMostrarDocumentos'; //m�odo
        $url[4] = 'permiso';                                //indice del request
        $titulo[0] = 'EMPRESAS';
        $titulo[1] = 'CENTRO UTILIDAD';

        $action = ModuloGetURL('system', 'Menu');

        $this->salida .= gui_theme_menu_acceso('FACTURACION', $titulo, $empresas, $url, $action);
        return true;
    }

    /*     * ********************************************************************************
     * Funcion donde se crean las variables usadas en la funcion FormaMostrarDocumentos,
     * se averiguan los tipos de documentos
     * ********************************************************************************* */

    function MostrarDocumentos() {
        unset($_SESSION['FACTURACION']);
        SessionDelVar("DocumentosFacturacion");
        $fct = new app_Facturacion_Permisos();

        if (!SessionIsSetVar("EmpresaFacturacion"))
            SessionSetVar("EmpresaFacturacion", $_REQUEST['permiso'][2]);
        SessionSetVar("CentroUtilidadFacturacion", $_REQUEST['permiso'][0]);

        $this->Rta = $fct->ObtenerTiposDocumentos(SessionGetVar("EmpresaFacturacion"), $tipodc);
        $this->frmError = $fct->frmError;
    }

    /**
     *
     */
    function Menu() {
        if (!SessionIsSetVar("DocumentosFacturacion"))
            SessionSetVar("DocumentosFacturacion", $_REQUEST['documento']['documento_id']);

        unset($_SESSION['FACTURACION']['arreglo']);
        unset($_SESSION['FACTURACION']['SWCUENTAS']);
        unset($_SESSION['FACTURACION']['CERRADAS']);

        //if(empty($_SESSION['FACTURACION']['EMPRESA']))
        // {
        $fct = new app_Facturacion_Permisos();
        $fact = $fct->DatosFactura(SessionGetVar("EmpresaFacturacion"), SessionGetVar("DocumentosFacturacion"));

        $_SESSION['FACTURACION']['EMPRESA'] = SessionGetVar("EmpresaFacturacion");
        $_SESSION['FACTURACION']['PREFIJOCONTADO'] = $fact['prefijo_fac_contado'];
        $_SESSION['FACTURACION']['PREFIJOCREDITO'] = $fact['prefijo_fac_credito'];
        $_SESSION['FACTURACION']['PUNTOFACTURACION'] = $fact['punto_facturacion_id'];
        // }
        if (!$this->FormaMenus()) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    function LlamarFormaMetodoBuscar() {
        unset($_SESSION['FACTURACION']['arreglo']);
        unset($_SESSION['FACTURACION']['aseguradora']);
        unset($_SESSION['FACTURACION']['CERRADAS']);
        $_SESSION['FACTURACION']['aseguradora'] = '';

        if (empty($_SESSION['FACTURACION']['SWCUENTAS'])) {
            $_SESSION['FACTURACION']['SWCUENTAS'] = $_REQUEST['SWCUENTAS'];
        }

        if (!empty($_REQUEST['res'])) {
            list($dbconn) = GetDBconn();
            $query = "SELECT sw_tipo_plan
                            FROM planes
                            WHERE estado='1' and plan_id='" . $_REQUEST['res'] . "'
                            and fecha_final >= now() and fecha_inicio <= now()";
            $results = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $results->Close();
            if ($results->fields[0] == 1) {
                $_SESSION['FACTURACION']['aseguradora'] = 'Si';
            }
        }

        if (!$this->FormaMetodoBuscar('', '', '', '', $_REQUEST['res'])) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    function LlamarFormaResponsableAgrupada() {
        unset($_SESSION['FACTURACION']['arreglo']);
        unset($_SESSION['FACTURACION']['aseguradora']);

        if (!$this->FormaResponsableAgrupada()) {
            return false;
        }
        return true;
    }

    /**
     * Busca los datos para el encabezado de la forma empresa
     * @access public
     * @return array
     * @param int caja_id
     */
    function DatosEncabezadoEmpresa() {
        list($dbconn) = GetDBconn();
        $query = "select *
                from empresas as b
                where  b.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var = $resulta->GetRowAssoc($ToUpper = false);
        return $var;
    }

    /**
     *
     */
    function Departamentos() {
        $EmpresaId = $_SESSION['FACTURACION']['EMPRESA'];
        $CentroU = $_SESSION['FACTURACION']['CENTROUTILIDAD'];
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

    function MostrarDetalle() {


        unset($_SESSION['SPYA']);
        $var = $this->DetalleFactura($_REQUEST['prefijo'], $_REQUEST['numero'], $_REQUEST['prnAnuladas']);
        //tiene varias
        //Y LA BUSQUEDA NO ES POR CUENTA.
        //if(sizeof($var) > 1 AND empty($_REQUEST[BusquedaPorCuenta]))
        if (sizeof($var) > 0 AND empty($_REQUEST[BusquedaPorCuenta])
                AND $var[0][sw_facturacion_agrupada] == 1 AND $var[0][sw_clase_factura] == 1) {
            $this->FormaFacturasAgrupadas($var);
            return true;
        } else {
            //solo tiene una cuenta
            //SI LA BUSQUEDA ES POR CUENTA MUESTRA
            //LA FORMA DE FACTURACION
            $_REQUEST['TipoId'] = $var[0][tipo_id_paciente];
            $_REQUEST['PacienteId'] = $var[0][paciente_id];
            $_REQUEST['Nivel'] = $var[0][rango];
            $_REQUEST['PlanId'] = $var[0][plan_id];
            $_REQUEST['Fecha'] = $var[0][fecha];
            $_REQUEST['Ingreso'] = $var[0][ingreso];
            $_REQUEST['Cuenta'] = $var[0][numerodecuenta];
            $_REQUEST['Estado'] = $var[0][estado];
            $this->Facturacion();
            return true;
        }
    }

    /**
     *
     */
    function Facturacion() {
        if (empty($_SESSION['FACTURACION']['VAR'])) {
            $_SESSION['FACTURACION']['VAR']['factura'] = $_REQUEST['numero'];
            $_SESSION['FACTURACION']['VAR']['prefijo'] = $_REQUEST['prefijo'];
            $_SESSION['FACTURACION']['VAR']['empresa'] = $_REQUEST['empresa'];
            $_SESSION['FACTURACION']['VAR']['centro'] = $_REQUEST['cu'];
        }
        if (empty($_SESSION['FACTURACION']['arreglo'])) {
            $_SESSION['FACTURACION']['arreglo'] = $_REQUEST['arreglo'];
        }

        $this->facturas = array("prefijo" => $_REQUEST['prefijo'], "factura" => $_REQUEST['numero'], "prnAnuladas" => $_REQUEST['prnAnuladas']);

        if (!$this->FormaFacturas($_REQUEST['Cuenta'], $_REQUEST['TipoId'], $_REQUEST['PacienteId'], $_REQUEST['PlanId'], $_REQUEST['Nivel'], $_REQUEST['Fecha'], $_REQUEST['Ingreso'], $_REQUEST['Transaccion'], $Dev, $vars, $_REQUEST['Estado'], '', $_REQUEST['tipo_factura'], $_REQUEST['verhojas'], $_REQUEST[prefijo], $_REQUEST[numero])) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    function BuscarNombresApellidosPaciente($Ingreso) {
        list($dbconn) = GetDBconn();
        $query = "SELECT a.primer_nombre||' '||a.segundo_nombre||' '||a.primer_apellido||' '||a.segundo_apellido as nombre,
                                a.tipo_id_paciente, a.paciente_id, c.semanas_cotizadas, c.tipo_afiliado_id, c.rango
                                FROM pacientes as a, ingresos as b, cuentas as c
                                WHERE b.ingreso=$Ingreso AND a.tipo_id_paciente=b.tipo_id_paciente AND
                                a.paciente_id=b.paciente_id AND c.ingreso=b.ingreso";
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

    /*     * *********************************************************************
     * Busca el detalle de una cuenta en la tabla cuentas_detalle.
     * @access public
     * @return array
     * @param int numero de Cuenta
     * ********************************************************************** */

    function BuscarDetalleCuenta($Cuenta) {
        list($dbconn) = GetDBconn();
        $query = "SELECT a.transaccion,
                         a.cargo,
                         a.cantidad,
                         a.precio,
                         a.valor_nocubierto,
                         a.fecha_registro,
                         a.tarifario_id,
                         a.valor_cubierto,
                         a.valor_cargo,
                         a.porcentaje_descuento_paciente,
                         a.porcentaje_descuento_empresa,
                         a.valor_descuento_empresa,
                         a.valor_descuento_paciente,
                         case facturado when 1 then a.valor_cargo else 0 end as fac,
                         a.autorizacion_int as interna,
                         a.autorizacion_ext as externa,
                         a.codigo_agrupamiento_id,
                         a.consecutivo, b.cuenta_liquidacion_qx_id
                  FROM   cuentas_detalle as a
                         LEFT JOIN cuentas_codigos_agrupamiento as b
                         ON (a.codigo_agrupamiento_id=b.codigo_agrupamiento_id)
                  WHERE  a.numerodecuenta='$Cuenta'
                  AND    a.facturado=1
                  ORDER BY b.cuenta_liquidacion_qx_id,a.consecutivo DESC,a.codigo_agrupamiento_id ";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
     * Llama la formacuenta para mostrar el detalle de los medicamentos.
     * @access public
     * @return boolean
     */
    /* function LlamaForma()
      {
      $Transaccion=$_REQUEST['Transaccion'];
      $TipoId=$_REQUEST['TipoId'];
      $PacienteId=$_REQUEST['PacienteId'];
      $Nivel=$_REQUEST['Nivel'];
      $PlanId=$_REQUEST['PlanId'];
      $Fecha=$_REQUEST['Fecha'];
      $Ingreso=$_REQUEST['Ingreso'];
      $Cuenta=$_REQUEST['Cuenta'];
      $Estado=$_REQUEST['Estado'];

      list($dbconn) = GetDBconn();
      $query =" select b.empresa_id, b.codigo_producto, e.descripcion, b.precio_venta,
      b.despachada, b.gravamen, b.total_venta
      from bodegas_documentos_d b, bodegas_documentos as c, bodegas_documentos_conceptos as d,
      inventarios as e
      where c.transaccion=$Transaccion and b.documento=c.documento and
      b.empresa_id=c.empresa_id and b.centro_utilidad=c.centro_utilidad and
      b.bodega=c.bodega and b.prefijo=c.prefijo and c.concepto_inv=d.concepto_inv and
      d.tipo_mov='E' and b.empresa_id=e.empresa_id and b.codigo_producto=e.codigo_producto";
      $resulta=$dbconn->Execute($query);
      while(!$resulta->EOF)
      {
      $vars[]=$resulta->GetRowAssoc($ToUpper = false);
      $resulta->MoveNext();
      }
      $resulta->Close();

      if(!$this->FormaFacturas($Cuenta,$TipoId,$PacienteId,$PlanId,$Nivel,$Fecha,$Ingreso,$Transaccion,$Dev,$vars,$Estado))
      {
      return false;
      }
      return true;
      } */

    /**
     * Busca el nombre y el precio del cargo en la tabla tarifarios_detalle.
     * @access public
     * @return array
     * @param text numero del tarifario
     * @param text id del Cargo
     */
    function BuscarNombreCargo($TarifarioId, $Cargo) {
        list($dbconn) = GetDBconn();
        $query = "SELECT descripcion,precio FROM tarifarios_detalle WHERE tarifario_id='$TarifarioId' AND cargo='$Cargo'";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var[0] = $result->fields[0];
        $var[1] = $result->fields[1];
        $result->Close();
        return $var;
    }

    /**
     *
     */
    function NombreCodigoAgrupamiento($codigo) {
        list($dbconn) = GetDBconn();
        $query = "  SELECT * FROM cuentas_codigos_agrupamiento
                                        WHERE codigo_agrupamiento_id='$codigo'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al eliminar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var = $result->GetRowAssoc($ToUpper = false);
        $result->Close();
        return $var;
    }

    /**
     * Llama la formacuenta para mostrar el detalle de los medicamentos.
     * @access public
     * @return boolean
     */
    function LlamaFormaDevolucionMedicamentos() {
        $Transaccion = $_REQUEST['Transaccion'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Nivel = $_REQUEST['Nivel'];
        $PlanId = $_REQUEST['PlanId'];
        $Fecha = $_REQUEST['Fecha'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Cuenta = $_REQUEST['Cuenta'];
        $Estado = $_REQUEST['Estado'];

        list($dbconn) = GetDBconn();
        $query = " select b.empresa_id, b.codigo_producto, e.descripcion, b.precio_venta,
                b.despachada, b.gravamen, b.total_venta
                 from bodegas_documentos_d b, bodegas_documentos as c, bodegas_documentos_conceptos as d,
                inventarios as e
                where c.transaccion=$Transaccion and b.documento=c.documento and
                b.empresa_id=c.empresa_id and b.centro_utilidad=c.centro_utilidad and
                b.bodega=c.bodega and b.prefijo=c.prefijo and c.concepto_inv=d.concepto_inv and
                d.tipo_mov='I' and b.empresa_id=e.empresa_id and b.codigo_producto=e.codigo_producto";
        $resulta = $dbconn->Execute($query);
        while (!$resulta->EOF) {
            $Dev[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        $resulta->Close();
        if (!$this->FormaFacturas($Cuenta, $TipoId, $PacienteId, $PlanId, $Nivel, $Fecha, $Ingreso, $Transaccion, $Dev, $vars, $Estado)) {
            return false;
        }
        return true;
    }

    /**
     * Busca el detalle de los apoyos diagnosticos de una cuenta.
     * @access public
     * @return boolean
     */
    /* function DetalleApoyos()
      {
      $Transaccion=$_REQUEST['Transaccion'];
      $TipoId=$_REQUEST['TipoId'];
      $PacienteId=$_REQUEST['PacienteId'];
      $Nivel=$_REQUEST['Nivel'];
      $PlanId=$_REQUEST['PlanId'];
      $Fecha=$_REQUEST['Fecha'];
      $Ingreso=$_REQUEST['Ingreso'];
      $Cuenta=$_REQUEST['Cuenta'];
      $Estado=$_REQUEST['Estado'];

      list($dbconn) = GetDBconn();
      $query = "select d.*, e.nombre from(select a.consecutivo, a.cargo, b.descripcion, a.precio,
      a.cantidad, a.valor_cargo, a.valor_cuota_paciente, a.valor_nocubierto,
      a.valor_cubierto, d.resultado, d.tipo_id_tercero, d.tercero_id,
      d.observacion, a.gravamen_valor_cubierto, a.fecha_registro,
      a.tarifario_id, a.gravamen_valor_nocubierto
      from ayudas_diagnosticas a left join resultados_dx d on (a.consecutivo=d.consecutivo),
      tarifarios_detalle b where a.transaccion=$Transaccion and a.cargo=b.cargo) as d
      left join profesionales e on (d.tipo_id_tercero=e.tipo_id_tercero and
      d.tercero_id=e.tercero_id)";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      $i=0;
      while(!$result->EOF){
      $vars[$i][0]=$result->fields[0];//consecutivo
      $vars[$i][1]=$result->fields[1];//cargo
      $vars[$i][2]=$result->fields[2];//descripcion
      $vars[$i][3]=$result->fields[3];//precio
      $vars[$i][4]=$result->fields[4];//cantidad
      $vars[$i][5]=$result->fields[5];//valor_cargo
      $vars[$i][6]=$result->fields[6];//valor_cuota
      $vars[$i][7]=$result->fields[7];//valor_no
      $vars[$i][8]=$result->fields[8];//valor_cubierto
      $vars[$i][9]=$result->fields[9];//resultado
      $vars[$i][10]=$result->fields[10];//tipo_id_tercero
      $vars[$i][11]=$result->fields[11];//tercero_id
      $vars[$i][12]=$result->fields[12];//observacion
      $vars[$i][13]=$result->fields[13];//gravamen_cubierto
      $vars[$i][14]=$result->fields[14];//fecha
      $vars[$i][15]=$result->fields[15];//tarifario_id
      $vars[$i][16]=$result->fields[16];//gravamen_nocubierto
      $vars[$i][17]=$result->fields[17];//nombre
      $result->MoveNext();
      $i++;
      }
      $result->Close();
      $this->FormaCuentaApoyos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$vars,$Transaccion,$Estado);
      return true;
      } */

    /**
     * Llama la forma FormaResultadosDiagnostico que muestra los datos del detalle de
     * diagnosticos de la cuenta
     * @access public
     * @return boolean
     */
    function ResultadosDiagnostico() {
        $Transaccion = $_REQUEST['Transaccion'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Nivel = $_REQUEST['Nivel'];
        $PlanId = $_REQUEST['PlanId'];
        $Fecha = $_REQUEST['Fecha'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Cuenta = $_REQUEST['Cuenta'];
        $Datos = $_REQUEST['Datos'];
        $Estado = $_REQUEST['Estado'];

        if (!$this->FormaResultadosDiagnostico($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Fecha, $Ingreso, $Transaccion, $Datos, $Estado)) {
            return false;
        }
        return true;
    }

    /**
     * Llamar la FormaCuentaCirugias
     * @access public
     * @return boolean
     */
    function LlamaFormaCuentaCirugias() {
        $Transaccion = $_REQUEST['Transaccion'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Nivel = $_REQUEST['Nivel'];
        $PlanId = $_REQUEST['PlanId'];
        $Fecha = $_REQUEST['Fecha'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Cuenta = $_REQUEST['Cuenta'];
        $Estado = $_REQUEST['Estado'];

        if (!$this->FormaCuentaCirugias($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Ingreso, $vars, $Transaccion, $Fecha, $Estado)) {
            return false;
        }
        return true;
    }

    /**
     * Busca los diferentes tipos de responsable (planes)
     * @access public
     * @return array
     */
    function responsables($tipo, $tercero) {
        $EmpresaId = $_SESSION['FACTURACION']['EMPRESA'];
        $CentroU = $_SESSION['FACTURACION']['CENTROUTILIDAD'];

        if (!empty($_SESSION['FACTURACION']['CU'])) {
            $CU = "and a.centro_utilidad='$CentroU'";
        }

        if (empty($CentroU)) {
            $CentroU = SessionGetVar("CentroUtilidadFacturacion");
        }
        $var = '';
        if (!empty($tipo) or !empty($tercero)) {
            $var = " and a.tipo_tercero_id='$tipo' and a.tercero_id='$tercero'";
        }

        list($dbconn) = GetDBconn();

        if (!ModuloGetVar('', '', 'planes_x_centro_utilidad_' . $EmpresaId . '')) {
            $query = " SELECT DISTINCT 	a.plan_id, ";
            $query .= " 					a.plan_descripcion, ";
            $query .= "						a.tercero_id, ";
            $query .= "						a.tipo_tercero_id ";
            $query .= " FROM 	planes as a ";
            $query .= "	WHERE 	a.fecha_final >= now() ";
            $query .= " AND 	a.estado=1 ";
            $query .= " AND 	a.fecha_inicio <= now() ";
            $query .= $var;
            $query .= " AND     a.empresa_id = '" . $EmpresaId . "' ";
            $query .= " ORDER BY a.plan_descripcion; ";
        } else {
            $query = " SELECT DISTINCT 	a.plan_id, ";
            $query .= " 					a.plan_descripcion, ";
            $query .= "						a.tercero_id, ";
            $query .= "						a.tipo_tercero_id ";
            $query .= " FROM 	planes as a, ";
            $query .= " 		planes_centro_utilidad b ";
            $query .= "	WHERE 	a.fecha_final >= now() ";
            $query .= " AND 	a.estado=1 ";
            $query .= " AND 	a.fecha_inicio <= now() ";
            $query .= $var;
            $query .= " AND     a.empresa_id = '" . $EmpresaId . "' ";
            $query .= " AND     a.empresa_id = b.empresa_id ";
            $query .= " AND     b.centro_utilidad = '" . $CentroU . "' ";
            $query .= " AND     a.plan_id = b.plan_id ";
            $query .= " ORDER BY a.plan_descripcion; ";
        }
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            if ($result->EOF) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'planes' esta vacia ";
                return false;
            }
            while (!$result->EOF) {
                $planes[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $planes;
    }

    /**
     * Busca los diferentes tipos de responsable (planes)
     * @access public
     * @return array
     */
    function responsablesAgrupados() {
        $EmpresaId = $_SESSION['FACTURACION']['EMPRESA'];
        $CentroU = $_SESSION['FACTURACION']['CENTROUTILIDAD'];
        if (!empty($_SESSION['FACTURACION']['CU'])) {
            $CU = "and a.centro_utilidad='$CentroU'";
        }

        list($dbconn) = GetDBconn();
        $query = "SELECT DISTINCT a.plan_id, a.plan_descripcion, a.tercero_id, a.tipo_tercero_id
              FROM planes as a
              WHERE a.fecha_final >= now() and a.estado=1 and a.fecha_inicio <= now()
              and a.sw_facturacion_agrupada=1";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            if ($result->EOF) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'planes' esta vacia ";
                return false;
            }
            while (!$result->EOF) {
                $planes[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $planes;
    }

    function ConceptoFacturaAgrupada() {
        list($dbconn) = GetDBconn();
        $query = "SELECT * FROM facturacion_agrupada_conceptos";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var = '';
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $var;
    }

    function ValidarSalidaUrgencia($ingreso) {
        list($dbconn) = GetDBconn();
        $query = "SELECT count(ingreso) FROM pacientes_urgencias WHERE ingreso=$ingreso and sw_estado in('4','5','6')";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $result->Close();
        return $result->fields[0];
    }

    /**
     *
     */
    function CerrarCuenta() {
        $PlanId = $_REQUEST['PlanId'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Nivel = $_REQUEST['Nivel'];
        $Fecha = $_REQUEST['Fecha'];
        $Cuenta = $_REQUEST['Cuenta'];

        IncludeLib('funciones_facturacion');

        list($dbconn) = GetDBconn();
        $query = "SELECT sw_apertura_admision
                FROM ingresos
                WHERE ingreso=$Ingreso";
        $resul = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if ($resul->fields[0] == '1') {
            $msg = 'Esta seguro que desea CUADRAR la Cuenta No. ' . $Cuenta;
            $arreglo = array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado);
            $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'ConfirmarAccion', array('c' => 'app', 'm' => 'Facturacion_Fiscal', 'me2' => 'Facturacion', 'me' => 'CuadrarFactura', 'mensaje' => $msg, 'titulo' => 'CUADRAR CUENTA No. ' . $Cuenta, 'arreglo' => $arreglo, 'boton1' => 'ACEPTAR', 'boton2' => 'CANCELAR'));
            return true;
        }
        $query = "SELECT count(numerodecuenta) FROM cuentas WHERE ingreso=$Ingreso";
        $resul = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        //solo tiene una cuenta
        if ($resul->fields[0] == 1) {
            //revisa q no tenga cirugias por liquidar
            $query = "SELECT count(numerodecuenta) FROM cuentas_liquidaciones_qx
                                        WHERE numerodecuenta=$Cuenta and (estado='0' OR estado='1')";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $result->Close();
            if ($result->fields[0] > 0) {           //tiene pendiente
                $mensaje = 'La Cuenta No ' . $Cuenta . ' no se puede Cuadrar: Tiene una Cirug� sin Liquidar.';
                $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
                $this->FormaMensaje($mensaje, 'CUADRAR CUENTA', $accion, 'ACEPTAR');
                return true;
            }

            $query = "SELECT * FROM hc_evoluciones WHERE ingreso=$Ingreso and estado!=0";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }


            if (!$result->EOF) {      //mensaje evolucion ABIERTA
                $mensaje = 'La Cuenta No ' . $Cuenta . ' no se puede Cuadrar: Tiene Evoluci� Abierta.';
                $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
                $this->FormaMensaje($mensaje, 'CUADRAR CUENTA', $accion, 'ACEPTAR');
                return true;
            }

            //mira si tiene pendientes por cargar
            $y = BuscarPendientesCargar($Ingreso);
            if (!empty($y)) {      //mensaje tiene pendientes
                $mensaje = 'La Cuenta No ' . $Cuenta . ' no se puede Cuadrar: TIENE CARGOS PENDIENTES.';
                $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
                $this->FormaMensaje($mensaje, 'CUADRAR CUENTA', $accion, 'ACEPTAR');
                return true;
            }
        }
        //ARRANQUE CALI
        //validacion de ordenes de servicio sin cumplir
        $query = "SELECT count(*)

            FROM os_maestro a, hc_os_solicitudes b
            LEFT JOIN hc_os_solicitudes_manuales e ON(b.hc_os_solicitud_id=e.hc_os_solicitud_id),
            hc_evoluciones c, ingresos i, cuentas d,
            system_modulos_variables f
            WHERE a.hc_os_solicitud_id=b.hc_os_solicitud_id
            AND b.evolucion_id=c.evolucion_id
            AND c.ingreso=i.ingreso
            AND i.ingreso=$Ingreso
            AND i.ingreso=d.ingreso
            AND d.numerodecuenta=$Cuenta
            AND (a.sw_estado='1' OR a.sw_estado='2')
            AND e.hc_os_solicitud_id IS NULL
            AND b.sw_ambulatorio='0'
            AND f.modulo='Facturacion_Fiscal'
            AND f.modulo_tipo='app'
            AND f.variable='ValidacionOSCuadreCuenta'
            AND f.valor='1'";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if ($result->fields[0] > 0) {
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
            $mensaje = 'La Cuenta No. ' . $Cuenta . ' tiene Ordenes de Servicio sin cumplir. ';
            $this->FormaMensaje($mensaje, 'ERROR AL CUADRAR LA CUENTA', $accion, 'ACEPTAR');
            return true;
        }
        //fin validacion

        $query = "SELECT estado
            FROM ingresos
            WHERE ingreso=$Ingreso";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if ($result->fields[0] == '0') {
            $msg = 'Esta seguro que desea CUADRAR la Cuenta No. ' . $Cuenta;
            $arreglo = array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado);
            $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'ConfirmarAccion', array('c' => 'app', 'm' => 'Facturacion_Fiscal', 'me2' => 'Facturacion', 'me' => 'CuadrarFactura', 'mensaje' => $msg, 'titulo' => 'CUADRAR CUENTA No. ' . $Cuenta, 'arreglo' => $arreglo, 'boton1' => 'ACEPTAR', 'boton2' => 'CANCELAR'));
            return true;
        }

        $query = "SELECT *
            FROM hc_ordenes_medicas a
            WHERE a.ingreso=$Ingreso AND a.sw_estado IN ('0','1') AND a.hc_tipo_orden_medica_id IN ('99','06','07')"; //a.sw_estado='0' ORDEN CONFIRMADA / '1' = ORDEN PENDIENTE
        global $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if ($result->EOF) {
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
            $mensaje = 'La Cuenta No ' . $Cuenta . ' no se puede Cuadrar: El Paciente no tiene orden de salida.';
            $this->FormaMensaje($mensaje, 'ERROR AL CUADRAR LA CUENTA', $accion, 'ACEPTAR');
            return true;
        }

        $datosOrdenMedica = $result->FetchRow();
        $result->Close();

        $query = "SELECT count(*)
            FROM hc_vistosok_salida_detalle a
            WHERE a.ingreso=$Ingreso AND a.evolucion_id=" . $datosOrdenMedica['evolucion_id'] . " AND a.visto_id='01'";

        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if ($result->fields[0] < 1) {
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
            $mensaje = 'La Cuenta No ' . $Cuenta . ' no se puede Cuadrar: El Paciente no tiene Visto Bueno de la EE.';
            $this->FormaMensaje($mensaje, 'ERROR AL CUADRAR LA CUENTA', $accion, 'ACEPTAR');
            return true;
        }
        //FIN ARRANQUE CALI
        //verifica que el paciente tiene movimientos de camas
        $query = " select numerodecuenta from movimientos_habitacion
                                    where numerodecuenta=$Cuenta";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        //no tiene movimeintos
        if ($result->EOF) {
            //--no tiene moviemientos y le dieron salida en urgencias
            $msg = 'Esta seguro que desea CUADRAR la Cuenta No. ' . $Cuenta;
            $arreglo = array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado);
            $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'ConfirmarAccion', array('c' => 'app', 'm' => 'Facturacion_Fiscal', 'me2' => 'Facturacion', 'me' => 'CuadrarFactura', 'mensaje' => $msg, 'titulo' => 'CUADRAR CUENTA No. ' . $Cuenta, 'arreglo' => $arreglo, 'boton1' => 'ACEPTAR', 'boton2' => 'CANCELAR'));
            return true;
        } else {
            //verifica que el paciente tenga fecha de egreso de las camas
//SE COMENTO PARA FACILITAR EL NUEVO PROCESO DE SALIDA PACIENTE - 30112006
            //$query = " select numerodecuenta from movimientos_habitacion
            //                        where numerodecuenta=$Cuenta and fecha_egreso is NULL";
            //$result = $dbconn->Execute($query);
            // if ($dbconn->ErrorNo() != 0) {
            //    $this->error = "Error al Cargar el Modulo";
            //    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            //    return false;
            //}//tiene fecha de egreso(no encontro nada en el query)
            //if($result->EOF)
            //{   //mira si el paciente esta en pendientes por liquidar
            //ARRANQUE CALI
            $query = "SELECT count(*)
                            FROM hc_ordenes_medicas a,hc_vistosok_salida_detalle b
                            WHERE a.ingreso=$Ingreso AND a.sw_estado='1' AND a.hc_tipo_orden_medica_id IN ('99','06','07') AND
                            a.ingreso=b.ingreso AND b.visto_id='01' AND a.evolucion_id=b.evolucion_id"; //a.sw_estado='0'
            //FIN ARRANQUE CALI
            /* $query = "select b.evolucion_id, c.egreso_dpto_id
              from egresos_departamento as a, hc_evoluciones as b, egresos_departamento_cuentas_x_liquidar as c
              where b.ingreso=$Ingreso and b.evolucion_id=a.evolucion_id
              and a.egreso_dpto_id=c.egreso_dpto_id"; */
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            } else {
                if (!$result->EOF) {
                    if (is_array($_SESSION['CUENTAS']['CAMA']['LIQ'])) {
                        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
                        $mensaje = 'La Cuenta No ' . $Cuenta . ' no se puede Cuadrar: Deben cargarse los cargos de habitaciones pendientes.';
                        $this->FormaMensaje($mensaje, 'ERROR AL CUADRAR LA CUENTA', $accion, 'ACEPTAR');
                        return true;
                    }
                    $msg = 'Esta seguro que desea CUADRAR la Cuenta No. ' . $Cuenta;
                    $arreglo = array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado);
                    $this->ReturnMetodoExterno('app', 'Facturacion', 'user', 'ConfirmarAccion', array('c' => 'app', 'm' => 'Facturacion_Fiscal', 'me2' => 'Facturacion', 'me' => 'CuadrarFactura', 'mensaje' => $msg, 'titulo' => 'CUADRAR CUENTA No. ' . $Cuenta, 'arreglo' => $arreglo, 'boton1' => 'ACEPTAR', 'boton2' => 'CANCELAR'));
                    return true;
                }//no se ha dado la orden de egreso
                else {
                    $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
                    $mensaje = 'La Cuenta No ' . $Cuenta . ' no se puede Cuadrar: El Paciente no se encuentra en cuentas por liquidar, informe a sistemas de este mensaje GRACIAS.';
                    $this->FormaMensaje($mensaje, 'ERROR AL CUADRAR LA CUENTA', $accion, 'ACEPTAR');
                    return true;
                }
            }
            //}
            //else
            //{
            //            $accion=ModuloGetURL('app','Facturacion_Fiscal','user','Facturacion',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
            //            $mensaje='La Cuenta No '.$Cuenta.' no se puedea Cuadrar: El Paciente no tiene fecha de egreso en la Estaci�, debe dar salida de la estaci�.';
            //            $this->FormaMensaje($mensaje,'ERROR AL CUADRAR LA CUENTA',$accion,'ACEPTAR');
            //            return true;
            //}
        }
    }

    /**
     *
     */
    function CerrarEvolucion() {
        $PlanId = $_REQUEST['PlanId'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Nivel = $_REQUEST['Nivel'];
        $Fecha = $_REQUEST['Fecha'];
        $Cuenta = $_REQUEST['Cuenta'];
        $arr = '';
        $i = 0;
        $result = '';
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $sql = "select evolucion_id, case when now()-fecha > interval '" . GetVarConfigAplication('MinutosCerradoEvoluciones') . " minutes' then '1' else '0' end as estado, usuario_id
            from hc_evoluciones as a
            where a.ingreso=$Ingreso and a.estado!=0;";
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                if ($result->fields[1] == 1) {
                    $sql = "update hc_evoluciones set estado=0,
                      fecha_cierre='now()'
                      where evolucion_id=" . $result->fields[0] . ";";
                    $result2 = $dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0) {
                        $dbconn->RollbackTrans();
                        return false;
                    }
                } else {
                    $sql = "select count(*) from system_session
                        where usuario_id=" . $result->fields[2] . "
                        and " . time() . "-ultimo_acceso_session<" . GetVarConfigAplication('InactivarSesion') . "*60;";
                    $result1 = $dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0) {
                        $dbconn->RollbackTrans();
                        return false;
                    }
                    if ($result1->fields[0] == 0) {
                        $sql = "update hc_evoluciones set estado=0,
                            fecha_cierre='" . date("Y-m-d H:i:s") . "'
                            where evolucion_id=" . $result->fields[0] . ";";
                        $result2 = $dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() != 0) {
                            $dbconn->RollbackTrans();
                            return false;
                        }
                    } else {
                        $arr[$i] = $result->fields[0];
                        $i++;
                    }
                }
                $result->MoveNext();
            }
        }

        $dbconn->CommitTrans();
        if (is_array($arr)) {
            $mensaje = 'La Evoluci� no pudo ser Cerrada:';
            for ($i = 0; $i < sizeof($arr); $i++) {
                $mensaje.=$arr[$i] . '<br>';
            }
        } else {
            $mensaje = 'La Evolucion de la La Cuenta No ' . $Cuenta . ' fue cerrada.';
        }

        // $accion=ModuloGetURL('app','Facturacion_Fiscal','user','Facturacion',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'CerrarCuenta', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
        $this->FormaMensaje($mensaje, 'CUADRAR CUENTA', $accion, 'ACEPTAR');
        return true;
    }

    function ConsultarDatosCargoAjuste($Empresa, $Cargo) {
        list($dbconn) = GetDBconn();
        $sql = "SELECT a.valor_min,a.valor_max
            FROM  fact_cargos_ajuste_cuentas as a
            WHERE a.empresa_id='" . $Empresa . "'
            AND a.tarifario_id='SYS'
            AND a.cargo='" . $Cargo . "';";
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        return $var;
    }

    /**
     * METODO PARA AJUSTAR UNA CUENTA CON LA SELLECCION DE VARIOS CARGOS DE AJUSTE
     */
//   function AjustarCuenta()
//   {
//         $PlanId=$_REQUEST['PlanId'];
//         $TipoId=$_REQUEST['TipoId'];
//         $PacienteId=$_REQUEST['PacienteId'];
//         $Ingreso=$_REQUEST['Ingreso'];
//         $Nivel=$_REQUEST['Nivel'];
//         $Fecha=$_REQUEST['Fecha'];
//         $Cuenta=$_REQUEST['Cuenta'];
//         $Cargo=$_REQUEST['cargo'];
//         $Saldo=($_REQUEST['Saldo']);
//         $ValorCargo=($_REQUEST['valor']);
//         $FechaRegistro=date("Y-m-d H:i:s");
//         $SystemId=UserGetUID();
//         $EmpresaId=$_SESSION['FACTURACION']['EMPRESA'];
//         //$CUtilidad=$_SESSION['FACTURACION']['CENTROUTILIDAD'];
//         $CUtilidad=$_SESSION['FACTURACION']['arreglo'][centro_utilidad];
//
//         if($Saldo > 0)
//         {   echo 'CargoDescuento';  }
//         elseif($Saldo < 0)
//         {  echo 'CargoAprovechamiento';  }
//         if($_REQUEST['valor']>$_REQUEST['Saldo'])
//         {
//           $this->frmError["MensajeError"]="EL VALOR DEL AJUSTE NO DEBE SER MAYOR AL SALDO($&nbsp;".$_REQUEST['Saldo'].").";
//           $this->FormaCargosAjusteCuenta();
//           return true;
//         }
//         if($_REQUEST['valor']>$_REQUEST['Saldo'])
//         {
//           $this->frmError["MensajeError"]="EL VALOR DEL AJUSTE NO DEBE SER MAYOR AL SALDO($&nbsp;".$_REQUEST['Saldo'].").";
//           $this->FormaCargosAjusteCuenta();
//           return true;
//         }
//         $DatosCargo=$this->ConsultarDatosCargoAjuste($EmpresaId,$_REQUEST['cargo']);
//         if($DatosCargo[0][valor_min]>$_REQUEST['valor'] || $DatosCargo[0][valor_max]<$_REQUEST['valor'])
//         {
//           $this->frmError["MensajeError"]="EL VALOR MINIMO Y VALOR MAXIMO DEL CARGO AJUSTE SON<BR>VALOR MINIMO : ".$DatosCargo[0][valor_min]." -- VALOR MAXIMO : ".$DatosCargo[0][valor_max]."";
//           $this->FormaCargosAjusteCuenta();
//           return true;
//         }
//         list($dbconn) = GetDBconn();
//         $query = "SELECT numerodecuenta FROM cuentas_detalle
//                   WHERE tarifario_id='SYS' AND cargo='$Cargo' AND numerodecuenta=$Cuenta";
//         $result = $dbconn->Execute($query);
//         if(!$result->EOF)
//         {
//               $accion=ModuloGetURL('app','Facturacion_Fiscal','user','Facturacion',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
//               $mensaje='La Cuenta No '.$Cuenta.' ya tiene un Cargo de Ajuste.';
//               $this->FormaMensaje($mensaje,'AJUSTAR LA CUENTA',$accion,'ACEPTAR');
//               return true;
//         }
//
//         $query ="SELECT b.servicio, c.departamento_actual
//                 FROM departamentos as b, cuentas as a, ingresos as c
//                 WHERE a.numerodecuenta=$Cuenta
//                 and a.ingreso=c.ingreso
//                 and c.departamento_actual=b.departamento";
//         $results = $dbconn->Execute($query);
//         if ($dbconn->ErrorNo() != 0) {
//           $this->error = "Error al Cargar el Modulo";
//           $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//           return false;
//         }
//         $Servicio=$results->fields[0];
//         $Dpto=$results->fields[1];
//
//                 $Saldo=($_REQUEST['Saldo'])*(-1);
//         $query=" SELECT nextval('cuentas_detalle_transaccion_seq')";
//         $result=$dbconn->Execute($query);
//         $Transaccion=$result->fields[0];
//
//       $query = "INSERT INTO cuentas_detalle (
//                       transaccion,
//                       empresa_id,
//                       centro_utilidad,
//                       numerodecuenta,
//                       departamento,
//                       tarifario_id,
//                       cargo,
//                       cantidad,
//                       precio,
//                       valor_cargo,
//                       usuario_id,
//                       facturado,
//                       fecha_cargo,
//                       fecha_registro,
//                       servicio_cargo,
//                       sw_cargue)
//                   VALUES ($Transaccion,'$EmpresaId','$CUtilidad',$Cuenta,'$Dpto','SYS','$Cargo',1,$ValorCargo,$ValorCargo,$SystemId,1,'$Fecha','$FechaRegistro',$Servicio,'3');";
//         $dbconn->Execute($query);
//         if ($dbconn->ErrorNo() != 0) {
//             $this->error = "Error INSERT INTO cuentas_detalle ";
//             $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//             $dbconn->RollbackTrans();
//             return false;
//         }
//        //INSERTAR AUDITORIA CARGOS AJUSTE fact_cargos_ajuste_cuentas_auditoria
//        $query = "INSERT INTO fact_cargos_ajuste_cuentas_auditoria (
//                       transaccion,
//                       numerodecuenta,
//                       usuario_id,
//                       observaciones,
//                       fecha_registro)
//                   VALUES ($Transaccion,$Cuenta,$SystemId,'".$_REQUEST['observacion']."','$FechaRegistro');";
//         $dbconn->Execute($query);
//         if ($dbconn->ErrorNo() != 0) {
//             $this->error = "Error INSERT INTO act_cargos_ajuste_cuentas_auditoria ";
//             $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//             $dbconn->RollbackTrans();
//             return false;
//         }
//         //FIN INSERTAR AUDITORIA CARGOS AJUSTE fact_cargos_ajuste_cuentas_auditoria
//         $accion=ModuloGetURL('app','Facturacion_Fiscal','user','Facturacion',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
//         $mensaje='La Cuenta No '.$Cuenta.' fue Ajustada.';
//         $this->FormaMensaje($mensaje,'AJUSTAR LA CUENTA',$accion,'ACEPTAR');
//         return true;
//   }
//METODO ANTERIOR PARA ALMACENAAR EL CARGO DE AJUSTE
    function AjustarCuenta() {
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

        if ($Saldo > 0) {
            $Cargo = ModuloGetVar('app', 'Facturacion_Fiscal', 'CargoDescuento');
        } elseif ($Saldo < 0) {
            $Cargo = ModuloGetVar('app', 'Facturacion_Fiscal', 'CargoAprovechamiento');
        }

        list($dbconn) = GetDBconn();
        $query = "SELECT numerodecuenta FROM cuentas_detalle
                  WHERE tarifario_id='SYS' AND cargo='$Cargo' AND numerodecuenta=$Cuenta";
        $result = $dbconn->Execute($query);
        if (!$result->EOF) {
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
            $mensaje = 'La Cuenta No ' . $Cuenta . ' ya tiene un Cargo de Ajuste.';
            $this->FormaMensaje($mensaje, 'AJUSTAR LA CUENTA', $accion, 'ACEPTAR');
            return true;
        }

        $query = "SELECT b.servicio, c.departamento_actual
                FROM departamentos as b, cuentas as a, ingresos as c
                WHERE a.numerodecuenta=$Cuenta
                and a.ingreso=c.ingreso
                and c.departamento_actual=b.departamento";
        $results = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $Servicio = $results->fields[0];
        $Dpto = $results->fields[1];

        $Saldo = ($_REQUEST['Saldo']) * (-1);
        $query = " SELECT nextval('cuentas_detalle_transaccion_seq')";
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
                      usuario_id,
                      facturado,
                      fecha_cargo,
                      fecha_registro,
                      servicio_cargo,
                                            sw_cargue)
                  VALUES ($Transaccion,'$EmpresaId','$CUtilidad',$Cuenta,'$Dpto','SYS','$Cargo',1,$Saldo,$Saldo,$SystemId,1,'$Fecha','$FechaRegistro',$Servicio,'3')";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error INSERT INTO cuentas_detalle ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => $Estado));
        $mensaje = 'La Cuenta No ' . $Cuenta . ' fue Ajustada.';
        $this->FormaMensaje($mensaje, 'AJUSTAR LA CUENTA', $accion, 'ACEPTAR');
        return true;
    }

    /**
     *
     */
    function CuadrarFactura() {
        $PlanId = $_REQUEST['PlanId'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Nivel = $_REQUEST['Nivel'];
        $Fecha = $_REQUEST['Fecha'];
        $Cuenta = $_REQUEST['Cuenta'];

        list($dbconn) = GetDBconn();
        $query = "UPDATE ingresos SET estado='0',fecha_cierre='now()'
                  WHERE ingreso=$Ingreso AND sw_apertura_admision='1';";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al ingresos(sw_apertura_admision) ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $query = "UPDATE cuentas SET estado='3', fecha_cierre='now()',
                                  usuario_cierre=" . UserGetUID() . "
                  WHERE numerodecuenta=$Cuenta";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            $mensaje = 'La Cuenta No. ' . $Cuenta . ' ha sido CUADRADA.';
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'Facturacion', array('Cuenta' => $Cuenta, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId, 'Nivel' => $Nivel, 'PlanId' => $PlanId, 'Fecha' => $Fecha, 'Ingreso' => $Ingreso, 'Estado' => 'C'));
            if (!$this->FormaMensaje($mensaje, 'CUADRAR CUENTA No. ' . $Cuenta, $accion, 'ACEPTAR')) {
                return false;
            }
            return true;
        }
    }

    /**
     *
     */
    function FacturaAgrupada($PlanId) {
        list($dbconn) = GetDBconn();
        $query = "SELECT sw_facturacion_agrupada FROM planes
                WHERE plan_id='$PlanId'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $result->fields[0];
    }

    /**
     *
     */
    function VerificarFactura($cuenta, $sw) {
        //cuando es particular es 2  sw_tipo 1->cliente 0->paciente 2->particular
        list($dbconn) = GetDBconn();
        $query = "SELECT a.prefijo, a.factura_fiscal, a.empresa_id
                                FROM fac_facturas_cuentas as a, fac_facturas as b
                                WHERE a.numerodecuenta=$cuenta and a.sw_tipo=0
                                and a.empresa_id=b.empresa_id and a.prefijo=b.prefijo and a.factura_fiscal=b.factura_fiscal
                                AND b.estado not in('2')";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $result->Close();
        if (!$result->EOF) {
            //hay q crear las var sessiones de factura
            if ($sw == 1) {
                $_SESSION['FACTURACION']['VAR']['factura'] = $result->fields[1];
                $_SESSION['FACTURACION']['VAR']['prefijo'] = $result->fields[0];
                $_SESSION['FACTURACION']['VAR']['empresa'] = $result->fields[2];
            }
            return 1;
        } else {
            return 0;
        }
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
            $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
                                                                FROM planes as a, terceros as b
                                                                WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
        }
        //cliente
        if ($sw == 0) {
            $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
                                                            FROM planes as a, terceros as b
                                                            WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
        }
        //particular
        if ($sw == 2) {
            $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
                                                                    FROM planes as a, terceros as b
                                                                    WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
        }
        //capitado
        if ($sw == 3) {
            $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
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
    function FacturarCuenta() {
        IncludeLib('funciones_facturacion');
        $PlanId = $_REQUEST['PlanId'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Nivel = $_REQUEST['Nivel'];
        $Fecha = $_REQUEST['Fecha'];
        $Cuenta = $_REQUEST['Cuenta'];

        $SystemId = UserGetUID();
        $FechaRegistro = date("Y-m-d H:i:s");
        $EmpresaId = $_SESSION['FACTURACION']['EMPRESA'];
        $PrefijoCon = $_SESSION['FACTURACION']['PREFIJOCONTADO'];
        $PrefijoCre = $_SESSION['FACTURACION']['PREFIJOCREDITO'];
        $punto = $_SESSION['FACTURACION']['PUNTOFACTURACION'];

        $ValorNoCubierto = $_SESSION['FACTURACION']['arreglo']['valor_nocubierto'];
        $ValorPac = $_SESSION['FACTURACION']['arreglo']['valor_cuota_paciente'];
        $ValorCubierto = $_SESSION['FACTURACION']['arreglo']['valor_cubierto'];
        $GravamenEmp = $_SESSION['FACTURACION']['arreglo']['gravamen_valor_cubierto'];
        $GravamenPac = $_SESSION['FACTURACION']['arreglo']['gravamen_valor_nocubierto'];
        $Gravamen = $GravamenEmp + $GravamenPac;
        $Descuento = $_SESSION['FACTURACION']['arreglo']['valor_descuento_paciente'] + $_SESSION['FACTURACION']['arreglo']['valor_descuento_empresa'];
        $TotalCuenta = $_SESSION['FACTURACION']['arreglo']['total_cuenta'];
        //$datos=$this->CallMetodoExterno('app','Triage','user','BuscarPlanes',array('PlanId'=>$PlanId,'Ingreso'=>$Ingreso));
        $datos = $this->BuscarPlanes($PlanId, $Ingreso);
        $Tercero = $datos[tercero_id];
        $TipoTercero = $datos[tipo_id_tercero];
        list($dbconn) = GetDBconn();
        //----------SI TIENE HABITACIONES SE CARGAN AUTOMATICAMENTE
        if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php")) {
            die(MsgOut("Error al incluir archivo", "El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
        }

        $liqHab = new LiquidacionHabitaciones;
        $hab = $liqHab->LiquidarCargosInternacion($Cuenta, false);
        //---------FIN CARGUE DE CAMAS


        if (is_array($hab)) {
            //va  a includes a insertar en cuentas detalle CARGUE DE CAMAS
            //---como la cuenta esta en un estado no valido la abrimos y luego la cerramos
            $query = "UPDATE cuentas SET estado='1' WHERE numerodecuenta=$Cuenta";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar fac_facturas_cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                //$dbconn->RollbackTrans();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                $this->GuardarNumero(false);
                return false;
            }
            $cargue = CargarHabitacionCuenta('', $hab, true, &$dbconn, $EmpresaId, $Cuenta, 0);
            //ocurrio un error al insertar
            if (empty($cargue)) {
                $this->frmError["MensajeError"] = "OCURRIO UN ERROR AL INSERTAR LA HABITACIONES.";
                $this->Facturacion();
                return true;
            }
            //-------vuelve a poner la cuenta como estaba
            //ojo se comento esto pues estaba sospechoso
            /* $query = "UPDATE cuentas SET estado='1' WHERE numerodecuenta='3'";
              $result=$dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar fac_facturas_cuentas";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              //$dbconn->RollbackTrans();
              $this->fileError = __FILE__;
              $this->lineError = __LINE__;
              $this->GuardarNumero(false);
              return false;
              } */
            //fin
        }

        //------busco cuanto a abonado el paciente para la validacion de la
        $query = "SELECT (a.abono_efectivo + a.abono_cheque +
                                a.abono_tarjetas + a.abono_chequespf + a.abono_letras) as abono
                                FROM cuentas as a WHERE a.numerodecuenta=$Cuenta";
        $results = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $abono = $results->fields[0];
        $results->Close(); //$abono=0;
        /*
          //----------------NUMERACION-------------------------
          //cambiamos numeraciones.
          $va=$this->AsignarNumero($_SESSION['FACTURACION']['PREFIJOCONTADO'],&$dbconn);
          $Facturapac=$va[numero];
          $prefijocon=$va[prefijo];
          //----------------FIN NUMERACION-----------------------
         */
        //-----------CONVENCIONES-------------
        //sw_tipo 1 es cliente y 0 es paciente
        //-------------------TRAER EL TERCERO DE LA FACTURA DEL PACIENTE Y PARTICULAR
        IncludeLib('funciones_facturacion');
        $retorno = ResponsableFacturaPaciente($TipoId, $PacienteId, $EmpresaId, &$dbconn);
        $tipoTerceroFacPaciente = $retorno[tipo_id_tercero];
        $idTerceroFacPaciente = $retorno[tercero_id];
        //-----------------------------------------------------------------------

        $query = "SELECT sw_tipo_plan, sw_facturacion_agrupada
                                                FROM planes
                                                WHERE estado='1' and plan_id='$PlanId'
                                                and fecha_final >= now() and fecha_inicio <= now()";
        $results = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $results->Close();
        $sw = $results->fields[0];
        $tiposfacturacion = '';
        //si sw es 1 es soat 3 capitacion 2 particular  o es facturacion agrupada
        //if($sw==1 OR $sw==3 OR $sw==2 OR $results->fields[1]==1)
        if ($sw == 3 OR $sw == 2 OR $results->fields[1] == 1) {

            //cuando es particular es 2  sw_tipo 1->cliente 0->paciente 2->particular
            if ($sw == 2) {
                $swtipo = 2;
            } else {
                $swtipo = 0;
            }

            //OJO ESTA VALIDACION SE REALIZO PARA EL CASO EN QUE SE
            //ANULA UNA FACTURA Y QUEDAN OTRAS REGISTRADAS CON ESTADO 0
            //Y NO DEBEN VOLVER A GENERARSE
            $facturaExiste = 0;
            $query = "SELECT *
                                      FROM fac_facturas_cuentas a,fac_facturas b
                                      WHERE a.numerodecuenta=$Cuenta AND a.prefijo=b.prefijo
                                      AND a.factura_fiscal=b.factura_fiscal AND a.empresa_id='$EmpresaId'
                                      AND sw_tipo='$swtipo' AND b.estado='0'";
            $results = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            } else {
                if ($results->RecordCount() > 0) {
                    $facturaExiste = 1;
                }
            }
            //FIN VALIDACION
            if ($facturaExiste != 1) {
                //----------------NUMERACION-------------------------
                //cambiamos numeraciones.
                $va = $this->AsignarNumero($_SESSION['FACTURACION']['PREFIJOCONTADO'], &$dbconn);
                $Facturapac = $va[numero];
                $prefijocon = $va[prefijo];
                //----------------FIN NUMERACION-----------------------
                //$tipofac=$this->BuscarTipoFactura($_SESSION['FACTURACION']['PREFIJOCONTADO'],$EmpresaId);
                //factura paciente
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
                                                                        tipo_factura)
                                                                VALUES('$EmpresaId','$prefijocon',$Facturapac,0,$SystemId,'$FechaRegistro',
                                                                '$PlanId','$tipoTerceroFacPaciente','$idTerceroFacPaciente',0," . $_SESSION['FACTURACION']['PREFIJOCONTADO'] . ",'$swtipo')";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar fac_facturas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    //$dbconn->RollbackTrans();
                    $this->GuardarNumero(false);
                    return false;
                }

                $query = "INSERT INTO fac_facturas_cuentas(
                                                                            empresa_id,
                                                                            prefijo,
                                                                            factura_fiscal,
                                                                            numerodecuenta,
                                                                            sw_tipo)
                                                                    VALUES('$EmpresaId','$prefijocon',$Facturapac,$Cuenta,'$swtipo')";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar fac_facturas_cuentas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    //$dbconn->RollbackTrans();
                    $this->GuardarNumero(false);
                    return false;
                }
            }
            //despues de guardar en facturas se actualiza el estado de la cuenta
            //si es particular se cierra la cuenta
            if ($sw == 2) {
                $estado = 0;
            } else {
                $estado = 3;
            }
            $query = "UPDATE cuentas SET estado=$estado,
                                                    fecha_cierre='now()',
                                                    usuario_id=" . UserGetUID() . "
                                                WHERE numerodecuenta=$Cuenta";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar fac_facturas_cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                //$dbconn->RollbackTrans();
                $this->GuardarNumero(false);
                return false;
            }
            $tiposfacturacion = 1;
        } else {    //el paciente no tiene que pagar nada       y no abono nada
            if ($_SESSION['FACTURACION']['arreglo']['valor_total_paciente'] > 0 AND $abono > 0) {
                //OJO ESTA VALIDACION SE REALIZO PARA EL CASO EN QUE SE
                //ANULA UNA FACTURA Y QUEDAN OTRAS REGISTRADAS CON ESTADO 0
                //Y NO DEBEN VOLVER A GENERARSE
                $facturaExiste = 0;
                $query = "SELECT *
                                          FROM fac_facturas_cuentas a,fac_facturas b
                                          WHERE a.numerodecuenta=$Cuenta AND a.prefijo=b.prefijo
                                          AND a.factura_fiscal=b.factura_fiscal AND a.empresa_id='$EmpresaId'
                                          AND sw_tipo='0' AND b.estado='0'";

                $results = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                } else {
                    if ($results->RecordCount() > 0) {
                        $facturaExiste = 1;
                    }
                }
                //FIN VALIDACION
                if ($facturaExiste != 1) {
                    //----------------NUMERACION-------------------------
                    //cambiamos numeraciones.
                    $va = $this->AsignarNumero($_SESSION['FACTURACION']['PREFIJOCONTADO'], &$dbconn);
                    $Facturapac = $va[numero];
                    $prefijocon = $va[prefijo];
                    //----------------FIN NUMERACION-----------------------
                    //aqui el sw_tipo es cero 0
                    //$tipofac=$this->BuscarTipoFactura($_SESSION['FACTURACION']['PREFIJOCONTADO'],$EmpresaId);
                    //factura paciente
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
                                                                            tipo_factura)
                                                                    VALUES('$EmpresaId','$prefijocon',$Facturapac,0,$SystemId,'$FechaRegistro',
                                                                    '$PlanId','$tipoTerceroFacPaciente','$idTerceroFacPaciente',0," . $_SESSION['FACTURACION']['PREFIJOCONTADO'] . ",'0')";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar fac_facturas";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        //$dbconn->RollbackTrans();
                        $this->GuardarNumero(false);
                        return false;
                    }
                    //sw_tipo 1->cliente 0->paciente 2->particular
                    $query = "INSERT INTO fac_facturas_cuentas(
                                                                                empresa_id,
                                                                                prefijo,
                                                                                factura_fiscal,
                                                                                numerodecuenta,
                                                                                sw_tipo)
                                                                        VALUES('$EmpresaId','$prefijocon',$Facturapac,$Cuenta,'0')";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar fac_facturas_cuentas";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        //$dbconn->RollbackTrans();
                        $this->GuardarNumero(false);
                        return false;
                    }
                    $facPac = true;
                }
            }
            if ($_SESSION['FACTURACION']['arreglo']['valor_total_empresa'] > 0) {

                //OJO ESTA VALIDACION SE REALIZO PARA EL CASO EN QUE SE
                //ANULA UNA FACTURA Y QUEDAN OTRAS REGISTRADAS CON ESTADO 0
                //Y NO DEBEN VOLVER A GENERARSE
                $facturaExiste = 0;
                $query = "SELECT *
                                          FROM fac_facturas_cuentas a,fac_facturas b
                                          WHERE a.numerodecuenta=$Cuenta AND a.prefijo=b.prefijo
                                          AND a.factura_fiscal=b.factura_fiscal AND a.empresa_id='$EmpresaId'
                                          AND sw_tipo='1' AND b.estado='0'";

                $results = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                } else {
                    if ($results->RecordCount() > 0) {
                        $facturaExiste = 1;
                    }
                }
                //FIN VALIDACION
                if ($facturaExiste != 1) {
                    //$tipofac=$this->BuscarTipoFactura($_SESSION['FACTURACION']['PREFIJOCREDITO'],$EmpresaId);
                    //----------------NUMERACION-------------------------
                    //cambiamos numeraciones.
                    $var = $this->AsignarNumero($_SESSION['FACTURACION']['PREFIJOCREDITO'], &$dbconn);
                    $Factura = $var[numero];
                    $Prefijo = $var[prefijo];
                    //----------------FIN NUMERACION-----------------------
                    //factura cliente
                    //sw_clase_factura=0 contado 1 credito
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
                                                                            tipo_factura)
                                                                    VALUES('$EmpresaId','$Prefijo',$Factura,0,$SystemId,'$FechaRegistro',
                                                                    '$PlanId','$TipoTercero','$Tercero',1," . $_SESSION['FACTURACION']['PREFIJOCREDITO'] . ",'1')";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar fac_facturas";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                    //sw_tipo 1->cliente 0->paciente 2->particular
                    $query = "INSERT INTO fac_facturas_cuentas(
                                                                            empresa_id,
                                                                            prefijo,
                                                                            factura_fiscal,
                                                                            numerodecuenta,
                                                                            sw_tipo)
                                                                    VALUES('$EmpresaId','$Prefijo',$Factura,$Cuenta,'1')";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar fac_facturas_cuentas";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                    $facClie = true;
                }
            }
            //despues de guardar en facturas se actualiza el estado de la cuenta
            $query = "UPDATE cuentas SET estado=0,
                                                fecha_cierre='now()',
                                                usuario_id=" . UserGetUID() . "
                                            WHERE numerodecuenta=$Cuenta";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar fac_facturas_cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                //$dbconn->RollbackTrans();
                $this->GuardarNumero(false);
                return false;
            }
            $tiposfacturacion = 2;
        }
        /* $query = "SELECT count(*) FROM cuentas
          WHERE ingreso=$Ingreso AND estado not in(0,5)";
          $result=$dbconn->Execute($query);
          if($result->fields[0] == 1)
          {
          $query = "UPDATE ingresos SET estado='0',fecha_cierre='now()'
          WHERE ingreso=$Ingreso";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Guardar";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          //$dbconn->RollbackTrans();
          $this->GuardarNumero(false);
          return false;
          }
          } */


        $dbconn->CommitTrans();
        //solo factura paciente
        if ($tiposfacturacion == 1) {
            $mensaje = 'La Cuenta No. ' . $Cuenta . ' ha sido FACTURADA, el Nmero de Factura Paciente Asignada fue: ' . $prefijocon . ' ' . $Facturapac . '';
        } elseif ($tiposfacturacion == 2) {   //no es agrupada pero hay que validar si se le hizo al paciente y al cliente o a cual
            if (!empty($facClie) AND !empty($facPac)) {
                $mensaje = 'La Cuenta No. ' . $Cuenta . ' ha sido FACTURADA, el Nmero de Factura Cliente Asignada fue: ' . $Prefijo . ' ' . $Factura . ', el Nmero de Factura Paciente Asignada fue: ' . $prefijocon . ' ' . $Facturapac . '';
            } elseif (!empty($facClie)) {
                $mensaje = 'La Cuenta No. ' . $Cuenta . ' ha sido FACTURADA, el Nmero de Factura Cliente Asignada fue: ' . $Prefijo . ' ' . $Factura . ', NO SE GENERO FACTURA PARA EL PACIENTE';
            } elseif (!empty($facPac)) {
                $mensaje = 'La Cuenta No. ' . $Cuenta . ' ha sido FACTURADA, el Nmero de Factura Paciente Asignada fue: ' . $prefijocon . ' ' . $Facturapac . ', NO SE GENERO FACTURA PARA EL CLIENTE';
            } elseif (empty($facClie) AND empty($facPac)) {
                $mensaje = 'La Cuenta No. ' . $Cuenta . ' ha sido CERRADA, no se genera Factura Cliente ni Factura Paciente, debido a que el valor a pagar es cero.';
            }
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

        $this->FormaFacturarImpresion($mensaje, $Cuenta, $prefijocon, $Facturapac, $Prefijo, $Factura, $PlanId);
        /* if(!$this-> FormaMensaje($mensaje,' CUENTA No. '.$Cuenta,$accion,'ACEPTAR')){
          return false;
          } */
        return true;
    }

    /**
     *
     */
    function FacturarCuentasAgrupadas() {
        if (!empty($_REQUEST['Seleccionar']) AND $_REQUEST['Todo'] != 'TodoCuentas') {
            unset($_SESSION['FACTURACION']['SELECCION'][$_REQUEST['paso']]);
            foreach ($_REQUEST as $k => $v) {
                if (substr_count($k, 'seleccion')) {
                    $var = explode(',', $v);
                    $_SESSION['FACTURACION']['SELECCION'][$_REQUEST['paso']][$var[0]] = $v;
                }
            }
            $_SESSION['FACTURACION']['SELECCION']['AGRUPADA'] = '';
            $this->FormaBuscarAgrupadas($_SESSION['FACTURACION']['VECTOR'], $_REQUEST['Plan']);
            return true;
        }

        $EmpresaId = $_SESSION['FACTURACION']['EMPRESA'];
        $CentroU = $_SESSION['FACTURACION']['CENTROUTILIDAD'];

        if ($_REQUEST['Todo'] == 'TodoCuentas') {
            $_SESSION['FACTURACION']['SELECCION'][0] = '';
            if ($_REQUEST['Departamento'] != -1 AND !empty($_REQUEST['Departamento'])) {
                $dpto = "and b.departamento='" . $_REQUEST['Departamento'] . "'";
            } else {
                $dpto = '';
            }
            $filtroFecha = '';
            $filtroDocumento = '';

            if (!empty($_REQUEST['FechaI']) AND !empty($_REQUEST['FechaF'])) {
                $FechaF = $_REQUEST['FechaF'];
                $FechaI = $_REQUEST['FechaI'];
                $filtroFecha = "and date(a.fecha_registro) <= date('$FechaF') and date(a.fecha_registro) >= date('$FechaI')";
            } elseif (!empty($_REQUEST['FechaF'])) {
                $FechaF = $_REQUEST['FechaF'];
                $filtroFecha = " and date(a.fecha_registro) <= date('$FechaF')";
            } elseif (!empty($_REQUEST['FechaI'])) {
                $FechaI = $_REQUEST['FechaI'];
                $filtroFecha = " and date(a.fecha_registro) >= date('$FechaI')";
            }
            if (!empty($_REQUEST['TipoDocumento']) AND !empty($_REQUEST['Documento'])) {
                $filtroDocumento = " and b.tipo_id_paciente = '" . $_REQUEST['TipoDocumento'] . "' and b.paciente_id = " . $_REQUEST['Documento'] . " ";
            }

            list($dbconn) = GetDBconn();
            $filtro = "";
            if (is_array($_REQUEST['Plan'])) {
                if (empty($_REQUEST['rango'])) {
                    $planes = "";
                    foreach ($_REQUEST['Plan'] as $key => $dtl)
                        $planes .= (($planes == "") ? "" : ",") . $key;

                    $filtro .= "AND    a.plan_id IN (" . $planes . ") ";
                } else {
                    foreach ($_REQUEST['Plan'] as $k1 => $dtl) {
                        if ($_REQUEST['rango'][$k1] != '-1')
                            $filtro .= (($filtro == "") ? "" : "OR") . " (a.plan_id = " . $k1 . " AND a.rango = '" . $_REQUEST['rango'][$k1] . "' ) ";
                        else
                            $filtro .= (($filtro == "") ? "" : "OR") . " (a.plan_id = " . $k1 . ") ";
                    }
                    $filtro = "AND   (" . $filtro . ") ";
                }
            }
            else {
                $filtro = "AND    a.plan_id = " . $_REQUEST['Plan'] . " ";
            }
            if ($_SESSION['FACTURACION']['aseguradora'] == 'Si') {
                $var = explode(',', $Aseguradora);
                $query = "select * 
                    from    (
                              select  a.numerodecuenta, 
                                      a.plan_id, 
                                      a.total_cuenta, 
                                      a.valor_nocubierto,
                                      a.ingreso, 
                                      a.valor_cuota_paciente, 
                                      a.valor_cubierto, 
                                      a.gravamen_valor_cubierto,
                                      a.gravamen_valor_nocubierto, 
                                      a.valor_descuento_paciente, 
                                      a.valor_descuento_empresa,
                                      (a.valor_total_paciente - (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo,
                              from    cuentas as a, 
                                      ingresos as b, 
                                      pacientes as c, 
                                      ingresos_soat as d, 
                                      terceros as f,
                                      soat_polizas as g, 
                                      soat_eventos as h
                              where   a.empresa_id='" . $EmpresaId . "'
                              " . $CU . "  
                              and     a.estado=3 
                              and     a.valor_total_empresa > 0
                              and     a.ingreso=b.ingreso  
                              " . $dpto . "
                              " . $filtro . "
                              and     b.ingreso=d.ingreso 
                              AND     d.evento=h.evento 
                              AND     g.poliza=h.poliza
                              AND     g.tipo_id_tercero='" . $_SESSION['FACTURACION']['TIPOTERCERO'] . "' 
                              AND     g.tercero_id='" . $_SESSION['FACTURACION']['IDTERCERO'] . "'
                              AND     g.tipo_id_tercero=f.tipo_id_tercero 
                              AND     g.tercero_id=f.tercero_id
                            ) as a 
                    where a.saldo=0 ";
            } else {
                $query = "select  SUM(valor_total_empresa), 
                            count(*)
                    from    cuentas as a, 
                            ingresos as b
                    where   a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                    " . $CU . "  
                    and     a.estado='3'  
                    and     a.valor_total_empresa > 0
                    and     a.ingreso=b.ingreso  
                    " . $dpto . "
                    " . $filtro . "
                    " . $filtroFecha . " 
                    " . $filtroDocumento . " ";
            }
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            unset($_SESSION['FACTURACION']['SELECCION'][$_REQUEST['paso']]);

            while (!$result->EOF) {
                if ($filtroFecha != '' OR $filtroDocumento != '') {
                    $_SESSION['FACTURACION']['SELECCION']['AGRUPADA']['TOTALES'] = $result->fields[0] . "," . $result->fields[1];
                } else {
                    $Descuento = $result->fields[9] + $result->fields[10];
                    $arreglo = $result->fields[0] . "," . $result->fields[1] . "," . $result->fields[2] . "," . $result->fields[3] . "," . $result->fields[4] . "," . $result->fields[5] . "," . $result->fields[6] . "," . $result->fields[7] . "," . $result->fields[8] . "," . $Descuento;
                    $_SESSION['FACTURACION']['SELECCION'][0][$result->fields[0]] = $arreglo;
                }
                $result->MoveNext();
            }
            $result->Close();
        }
        $f = 0;
        if (!empty($_SESSION['FACTURACION']['SELECCION'])) {
            $f = 1;
        } else {
            foreach ($_REQUEST as $k => $v) {
                if (substr_count($k, 'seleccion')) {
                    $var = explode(',', $v);
                    $_SESSION['FACTURACION']['SELECCION'][$_REQUEST['paso']][$var[0]] = $v;
                    $f++;
                }
            }
            $_SESSION['FACTURACION']['SELECCION']['AGRUPADA'] = '';
        }
        if ($f == 0) {
            $this->frmError["MensajeError"] = "ERROR DATOS VACIOS: Debe elegir alguna Cuenta.";
            $this->FormaBuscarAgrupadas();
            return true;
        }
        //if(!empty($_SESSION['FACTURACION']['SELECCION']))
        if ($_SESSION['FACTURACION']['SELECCION'][0] !== '') {
            foreach ($_SESSION['FACTURACION']['SELECCION'] as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    $var = explode(',', $v1);
                    $valor += $var[2];
                    $cantidad++;
                }
            }
            $this->FormaConceptoFactura($_REQUEST['Plan'], $valor, $_REQUEST['FechaI'], $_REQUEST['FechaF'], $cantidad, null, null, null, null, $_REQUEST['rango']);
        } else {
            $this->FormaConceptoFactura($_REQUEST['Plan'], '', $_REQUEST['FechaI'], $_REQUEST['FechaF'], '', $CU, $dpto, $filtroFecha, $filtroDocumento, $_REQUEST['rango']);
        }
        return true;
    }

    /**
     *
     */
    function CrearFacturaAgrupadas() {
        if ($_REQUEST['tipoPlan'] == 3) {
            if (!$_REQUEST['concepto']) {
                $this->frmError["MensajeError"] = "DEBE DIGITAR EL CONCEPTO DE LA FACTURA.";
                $this->FormaConceptoFactura($_REQUEST['plan'], $_REQUEST['valor'], $_REQUEST['fechaI'], $_REQUEST['fechaF'], $_REQUEST['cantidad']);
                return true;
            }
            if (!$_REQUEST['valorC']) {
                $this->frmError["MensajeError"] = "DEBE DIGITAR EL VALOR DE LA FACTURA.";
                $this->FormaConceptoFactura($_REQUEST['plan'], $_REQUEST['valor'], $_REQUEST['fechaI'], $_REQUEST['fechaF'], $_REQUEST['cantidad']);
                return true;
            }
        }

        $EmpresaId = $_SESSION['FACTURACION']['EMPRESA'];

        list($dbconn) = GetDBconn();

        $dbconn->BeginTrans();
        //----------------NUMERACION-------------------------
        //cambiamos numeraciones.

        $va = $this->AsignarNumero($_SESSION['FACTURACION']['PREFIJOCREDITO'], &$dbconn);
        $Factura = $va[numero];
        $Prefijo = $va[prefijo];
        //----------------FIN NUMERACION-----------------------
        //es soat (hay aseguradora)
        if ($_REQUEST['tipoPlan'] == 1) {
            $TipoTercero = $_SESSION['FACTURACION']['TIPOTERCERO'];
            $Tercero = $_SESSION['FACTURACION']['IDTERCERO'];
        } else {
            IncludeLib('funciones_facturacion');
            $k = key($_REQUEST['plan']);

            $planes = DatosPlan($k);
            $TipoTercero = $planes['tipo_tercero_id'];
            $Tercero = $planes['tercero_id'];
        }

        $filtro = $plan_re = $rango_re = "";
        if (is_array($_REQUEST['plan'])) {
            $plan_re = key($_REQUEST['plan']);
            if (empty($_REQUEST['rango'])) {
                $planes = "";
                foreach ($_REQUEST['plan'] as $key => $dtl)
                    $planes .= (($planes == "") ? "" : ",") . $key;

                $filtro .= "AND    a.plan_id IN (" . $planes . ") ";
            } else {
                foreach ($_REQUEST['plan'] as $k1 => $dtl) {
                    if ($_REQUEST['rango'][$k1] != '-1')
                        $filtro .= (($filtro == "") ? "" : "OR") . " (a.plan_id = " . $k1 . " AND a.rango = '" . $_REQUEST['rango'][$k1] . "' ) ";
                    else
                        $filtro .= (($filtro == "") ? "" : "OR") . " (a.plan_id = " . $k1 . ") ";
                }
                $filtro = "AND   (" . $filtro . ") ";
            }
        }
        else {
            $filtro = "AND    a.plan_id = " . $_REQUEST['plan'] . " ";
            $plan_re = $_REQUEST['plan'];
        }

        //CAPITACION
        if ($_REQUEST['tipoPlan'] == 3) {
            $query = "INSERT INTO fac_facturas
                    (
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
                      concepto,
                      total_factura,
                      total_capitacion_real,
                      documento_id,
                      tipo_factura,
                      rango
                    )
                    VALUES
                    (
                      '" . $EmpresaId . "',
                      '" . $Prefijo . "',
                       " . $Factura . ",
                      0,
                       " . UserGetUID() . ",
                      'now()',
                       " . $plan_re . ",
                      '" . $TipoTercero . "',
                      '" . $Tercero . "',
                      1,
                      '" . $_REQUEST['concepto'] . "',
                       " . $_REQUEST['valorC'] . ",
                       " . $_REQUEST['valor'] . ",
                       " . $_SESSION['FACTURACION']['PREFIJOCREDITO'] . ",
                      '3',
                      " . (($_REQUEST['rango'][$plan_re]) ? "'" . $_REQUEST['rango'][$plan_re] . "'" : "NULL") . "
                    )";
        } else {
            $query = "INSERT INTO fac_facturas
                    (
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
					  concepto,
					  total_factura,
                      documento_id,
                      tipo_factura,
                      rango
                    )
                    VALUES
                    (
                      '" . $EmpresaId . "',
                      '" . $Prefijo . "',
                       " . $Factura . ",
                      0,
                      " . UserGetUID() . ",
                       NOW(),
                       " . $plan_re . ",
                      '" . $TipoTercero . "',
                      '" . $Tercero . "',
                      1,
					  '" . $_REQUEST['concepto'] . "',
					  " . $_REQUEST['valor'] . ",
                       " . $_SESSION['FACTURACION']['PREFIJOCREDITO'] . ",
                      '4',
                      " . (($_REQUEST['rango'][$plan_re]) ? "'" . $_REQUEST['rango'][$plan_re] . "'" : "NULL") . "
                    )";
        }
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar3";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __LINE__ . "]";
            $dbconn->RollbackTrans();
            return false;
        }

        $i = 0;
        //SI SE SELECCIONAN CON checks
        if ($_SESSION['FACTURACION']['SELECCION']['AGRUPADA'] == '') {
            foreach ($_SESSION['FACTURACION']['SELECCION'] as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    $var = explode(',', $v1);
                    $Cuenta = $var[0];
                    $Ingreso = $var[4];
                    $query = "INSERT INTO fac_facturas_cuentas
                        (
                          empresa_id,
                          prefijo,
                          factura_fiscal,
                          numerodecuenta,
                          sw_tipo
                        )
                        VALUES('$EmpresaId','$Prefijo',$Factura,$Cuenta,'1') ";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar4";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __LINE__ . "]";
                        $dbconn->RollbackTrans();
                        return false;
                    }

                    $query = "UPDATE cuentas SET estado='0' WHERE numerodecuenta=$Cuenta";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar5";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __LINE__ . "]";
                        $dbconn->RollbackTrans();
                        return false;
                    }

                    $query = "SELECT  COUNT(*) 
                      FROM    cuentas
                      WHERE   ingreso= " . $Ingreso . " 
                      AND     estado not in('0','5')";
                    $result = $dbconn->Execute($query);

                    if ($result->fields[0] == 1) {
                        $query = "UPDATE  ingresos 
                        SET     estado='0',
                                fecha_cierre='now()'
                        WHERE   ingreso = " . $Ingreso . " ";

                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Guardar6";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __LINE__ . "]";
                            $dbconn->RollbackTrans();
                            return false;
                        }
                    }
                    $i++;
                }
            }
        }//SI SE SELECCIONAN TODAS LAS AGRUPADAS
        elseif ($_SESSION['FACTURACION']['SELECCION']['AGRUPADA'] !== '') {
            $CU = $_REQUEST['CU'];
            $dpto1 = $_REQUEST['dpto'];
            $filtroFecha1 = $_REQUEST['filtroFecha'];
            $filtroDocumento = $_REQUEST['filtroDocumento'];
            $Plan = $_REQUEST['plan'];
            $filtroFecha = str_replace("\\", "'", $filtroFecha1); //REEMPLAZAR  \' por '
            $filtroDocumento = str_replace("\\", "'", $filtroDocumento); //REEMPLAZAR  \' por '
            $filtroFecha = str_replace("''", "'", $filtroFecha); //REEMPLAZAR  \' por '
            $filtroDocumento = str_replace("''", "'", $filtroDocumento); //REEMPLAZAR  \' por '
            $dpto = str_replace("\'", "'", $dpto1); //REEMPLAZAR  \' por '
            $query = "INSERT INTO fac_facturas_cuentas
                  (
                      empresa_id,
                      prefijo,
                      factura_fiscal,
                      numerodecuenta,
                      sw_tipo
                  )
                  SELECT '$EmpresaId',
                         '$Prefijo',
                        $Factura,
                        a.numerodecuenta,
                        '1'
                  FROM cuentas as a,
                       ingresos as b
                  WHERE a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                  $CU
                  AND a.estado=3
                  AND a.valor_total_empresa > 0
                  AND a.ingreso=b.ingreso
                  $dpto
                  $filtroFecha
                  $filtroDocumento
                  " . $filtro . " ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar fac_facturas_cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __LINE__ . "]";
                $dbconn->RollbackTrans();
                return false;
            }
            $query = "UPDATE  cuentas 
                  SET     estado='0'
                  WHERE   numerodecuenta IN 
                        (
                          SELECT a.numerodecuenta
                          FROM cuentas as a,
                                      ingresos as b
                          WHERE a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                          $CU
                          AND a.estado=3
                          AND a.valor_total_empresa > 0
                          AND a.ingreso=b.ingreso
                          $dpto
                          $filtroFecha
                          $filtroDocumento
                          " . $filtro . "
                        );";

            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar5";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __LINE__ . "]";
                $dbconn->RollbackTrans();
                return false;
            }

            $query = "SELECT a.ingreso
                  FROM    cuentas as a,
                              ingresos as b
                  WHERE a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                  $CU
                  AND a.estado=3
                  AND a.valor_total_empresa > 0
                  AND a.ingreso=b.ingreso
                  $dpto
                  $filtroFecha
                  $filtroDocumento
                  " . $filtro . " ;";
            $result = $dbconn->Execute($query);
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            foreach ($var as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    $query = "SELECT count(*)
                      FROM    cuentas
                      WHERE   ingreso=$v1 
                      AND     estado not in(0,5)";
                    $result = $dbconn->Execute($query);
                    if ($result->fields[0] == 1) {
                        $query = "UPDATE ingresos SET estado='0',fecha_cierre='now()'
                        WHERE ingreso=$v1";
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Guardar6";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __LINE__ . "]";
                            $dbconn->RollbackTrans();
                            return false;
                        }
                    }
                }
            }
        }//FIN DE LA SELECCION DE TODAS

        foreach ($_REQUEST['plan'] as $key => $dtl) {
            $sql = "INSERT INTO fac_facturas_planes_agrupado ";
            $sql .= "   ( ";
            $sql .= "     empresa_id, ";
            $sql .= "     prefijo, ";
            $sql .= "     factura_fiscal, ";
            $sql .= "     plan_id ";
            $sql .= "   ) ";
            $sql .= "VALUES ";
            $sql .= "   ( ";
            $sql .= "     '" . $_SESSION['FACTURACION']['EMPRESA'] . "', ";
            $sql .= "     '" . $Prefijo . "', ";
            $sql .= "      " . $Factura . ", ";
            $sql .= "      " . $key . " ";
            $sql .= "   );";

            $result = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar Planes por factura";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __LINE__ . "]";
                $dbconn->RollbackTrans();
                return false;
            }
        }
        //ACTUALIZAR TOTALES FACTURAS
        $query = "SELECT actualizar_totales_facturas('$EmpresaId','$Prefijo',$Factura);";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error funcion actualizar_totales_facturas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __LINE__ . "]";
            $dbconn->RollbackTrans();
            return false;
        }
        //FIN ACTUALIZAR TOTALES FACTURAS
        $dbconn->CommitTrans();

        $query = " UPDATE fac_facturas SET saldo = 1 ";
        $query .= " WHERE empresa_id = '$EmpresaId' ";
        $query .= " AND   prefijo = '$Prefijo' ";
        $query .= " AND   factura_fiscal = $Factura ";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error Actualizar Saldo Factura Agrupada";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __LINE__ . "]";
            $dbconn->RollbackTrans();
            return false;
        }

        if ($i == 0) {
            $dat = explode(',', $_SESSION['FACTURACION']['SELECCION']['AGRUPADA']['TOTALES']);
            $i = $dat[1];
        }
        $mensaje = 'Se Facturaron ' . $i . ' Cuentas Agrupadas. El numero de Factura Cliente asignado es:' . $Prefijo . $Factura;
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarAgrupadas');
        if (!$this->FormaMensaje($mensaje, 'FACTURAR CUENTAS AGRUPADAS', $accion, 'ACEPTAR')) {
            return false;
        }
        return true;
    }

    function AsignarNumero($prefijo, &$dbconn) {
        if ((!empty($prefijo))) {
            $sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE";
            $result = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                die(MsgOut("Error al iniciar la transaccion", "Error DB : " . $dbconn->ErrorMsg()));
                $dbconn->RollbackTrans();
                return false;
            }
            //actualizacion contado
            $sql = "UPDATE documentos set numeracion=numeracion + 1
                                    WHERE  documento_id=$prefijo and empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'";
            /* $sql="UPDATE fac_tipos_facturas set numeracion=numeracion + 1
              WHERE  prefijo='$prefijo' and empresa_id='".$_SESSION['FACTURACION']['EMPRESA']."'"; */
            $result = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                die(MsgOut("Error al actualizar numeracion", "Error DB : " . $dbconn->ErrorMsg()));
                $dbconn->RollbackTrans();
                return false;
            }
            if ($dbconn->Affected_Rows() == 0) {
                die(MsgOut("Error al actualizar numeracion", "El prefijo '$prefijo' no existe."));
                $dbconn->RollbackTrans();
                return false;
            }

            //sacamos el numero de la factura de contado.
            $sql = "SELECT numeracion,prefijo FROM documentos
                                    WHERE documento_id=$prefijo  and empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'";
            /* $sql="SELECT numeracion,prefijo FROM fac_tipos_facturas
              WHERE prefijo='$prefijo'  and empresa_id='".$_SESSION['FACTURACION']['EMPRESA']."'"; */
            $result = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                die(MsgOut("Error al traer numeracion", "Error DB : " . $dbconn->ErrorMsg()));
                $dbconn->RollbackTrans();
                return false;
            }

            if ($result->EOF) {
                die(MsgOut("Error al actualizar numeracion", "El tipo de numeracion '$prefijo' no existe."));
                $dbconn->RollbackTrans();
                return false;
            }
            list($numerodoc['numero'], $numerodoc['prefijo']) = $result->fetchRow();

            return $numerodoc;
        }

        die(MsgOut("Error al actualizar numeracion", "El prefijo &nbsp;['$prefijo']&nbsp; esta vacio."));
        return false;
    }

    /*
     * aqui finiquitamos la transaccion de
     * la insercion de las facturas
     * si enviamos TRUE,por el contrario si enviamos FALSE hara un rollback.
     *
     */

    function GuardarNumero($commit=true) {
        list($dbconn) = GetDBconn();
        if ($commit) {
            $sql = "COMMIT";
        } else {
            $sql = "ROLLBACK";
        }

        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            die(MsgOut("Error al terminar la transaccion", "Error DB : " . $dbconn->ErrorMsg()));
            return false;
        }
        return true;
    }

//--------------------------------------------------------------------------------------
    /**
     *
     */
    function BuscarCuenta() {
        $EmpresaId = $_SESSION['FACTURACION']['EMPRESA'];
        $filtroTipoDocumento = '';
        $filtroDocumento = '';
        $filtroNombres = '';
        $filtroIngreso = '';
        $filtroDepto = '';
        $filtroCuenta = '';

        if ($_REQUEST[Departamento] != -1) {
            $filtroDepto = "and b.departamento='" . $_REQUEST[Departamento] . "'";
        }

        if ($_REQUEST[TipoDocumento] != '') {
            $filtroTipoDocumento = " AND b.tipo_id_paciente = '" . $_REQUEST[TipoDocumento] . "'";
        }

        if (!empty($_REQUEST[Documento])) {
            $filtroDocumento = " AND b.paciente_id LIKE '" . $_REQUEST[Documento] . "%'";
        }

        if ($_REQUEST[Nombres] != '') {
            $a = explode(' ', $_REQUEST[Nombres]);
            foreach ($a as $k => $v) {
                if (!empty($v)) {
                    $filtroNombres.=" and (upper(c.primer_nombre||' '||c.segundo_nombre||' '||
                                                                                                                        c.primer_apellido||' '||c.segundo_apellido) like '%" . strtoupper($_REQUEST[Nombres]) . "%')";
                }
            }
        }

        if (!empty($_REQUEST[Ingreso])) {
            $filtroIngreso = " AND a.ingreso =" . $_REQUEST[Ingreso] . "";
        }

        if (!empty($_REQUEST[Cuenta])) {
            $filtroCuenta = " AND a.numerodecuenta =" . $_REQUEST[Cuenta] . "";
        }

        if (empty($_REQUEST['Of'])) {
            $_REQUEST['Of'] = 0;
        }
        list($dbconn) = GetDBconn();
        if (empty($_REQUEST['paso'])) {
            $query = "select a.*,
                                            (a.valor_total_paciente - (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo,
                                            c.tipo_id_paciente,
                                            c.paciente_id,  c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre,
                                            a.rango,
                                            case when a.estado=1 then 'A' when a.estado=2 then 'I' when a.estado=3 then 'C' end as estado
                                            from cuentas as a, ingresos as b, pacientes as c
                                            where a.estado in('1','2','3')
                                            $filtroTipoDocumento $filtroDocumento
                                            $filtroNombres $filtroCuenta $filtroIngreso $filtroDepto
                                            and a.ingreso=b.ingreso and
                                            b.tipo_id_paciente=c.tipo_id_paciente and
                                            b.paciente_id=c.paciente_id";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al buscar";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if (!$result->EOF) {
                $_SESSION['SPY'] = $result->RecordCount();
            }
            $result->Close();
        }

        $query = "select * from(select a.*,
                                    (a.valor_total_paciente - (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo,
                                    c.tipo_id_paciente,
                                    c.paciente_id,  c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre,
                                    a.rango,
                                    case when a.estado=1 then 'A' when a.estado=2 then 'I' when a.estado=3 then 'C' end as estado
                                    from cuentas as a, ingresos as b, pacientes as c
                                    where a.estado in('1','2','3')
                                    $filtroTipoDocumento $filtroDocumento
                                    $filtroNombres $filtroCuenta $filtroIngreso $filtroDepto
                                    and a.ingreso=b.ingreso and
                                    b.tipo_id_paciente=c.tipo_id_paciente and
                                    b.paciente_id=c.paciente_id ) as a
                                    LIMIT " . $this->limit . " OFFSET " . $_REQUEST['Of'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al buscar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $this->FormaMetodoBuscar($var);
        return true;
    }

    /**
     *
     */
    function SaldoPaciente($Cuenta, $Plan) {
        $EmpresaId = $_SESSION['FACTURACION']['EMPRESA'];

        list($dbconn) = GetDBconn();
        $query = "select (a.valor_total_paciente - (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo
                  from cuentas as a
                  where a.empresa_id='$EmpresaId'
                  $CU $est and a.plan_id='$Plan' and a.numerodecuenta=$Cuenta";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var = $result->fields[0];
        $result->Close();
        return $var;
    }

    /**
     *
     */
    function BuscarCargoAjusteDes($Cuenta) {
        $des = ModuloGetVar('app', 'Facturacion_Fiscal', 'CargoDescuento');

        list($dbconn) = GetDBconn();
        $query = "select a.precio from cuentas_detalle as a
                                    where a.numerodecuenta=$Cuenta
                                    and a.cargo='$des'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var = $result->fields[0];
        $result->Close();
        return $var;
    }

    function BuscarCargoAjusteApro($Cuenta) {
        $apr = ModuloGetVar('app', 'Facturacion_Fiscal', 'CargoAprovechamiento');

        list($dbconn) = GetDBconn();
        $query = "select a.precio from cuentas_detalle as a
                                    where a.numerodecuenta=$Cuenta
                                    and a.cargo='$apr'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var = $result->fields[0];
        $result->Close();
        return $var;
    }

//----------------------------ENVIOS-------------------------------------

    /**
     *
     */
    function LlamarFormaBuscarEnvios() {
        $_SESSION['ENVIOS']['TERCERO'] = $_REQUEST['Plan'];
        $this->FormaBuscarEnvios();
        return true;
    }

    /**
     *
     */
    function BuscarEnvios() {
        $filtroDpto = '';
        $filtroFecha = '';
        $filtroPlan = '';
        $filtroDoc = '';
        if ($_REQUEST['adicionar'] == '1') {
            $adicionar = $_REQUEST['adicionar'];
            $_REQUEST = $_SESSION['ENVIOS']['TMP'];
        }
        else
            $_SESSION['ENVIOS']['TMP'] = $_REQUEST;


        unset($_SESSION['ENVIOS']['FECHAI']);
        unset($_SESSION['ENVIOS']['FECHAF']);
        unset($_SESSION['ENVIOS']['DPTO']);

        if (empty($_REQUEST['FechaI']) OR empty($_REQUEST['FechaF'])) {
            if (empty($_REQUEST['FechaI'])) {
                $this->frmError["FechaI"] = 1;
            }
            if (empty($_REQUEST['FechaF'])) {
                $this->frmError["FechaF"] = 1;
            }
            $this->frmError["MensajeError"] = "Los rangos de las fechas son Obligatorios.";
            $this->FormaBuscarEnvios();
            return true;
        }

        if ($_REQUEST['modificarenvio'] == '1') {
            $x = explode(',', $_SESSION['ENVIOS']['ADICIONAR']);
        } else {
            $x = explode(',', $_SESSION['ENVIOS']['TERCERO']);
        }

        if (!empty($_REQUEST['FechaI'])) {
            $f = explode('/', $_REQUEST['FechaI']);
            $_REQUEST['FechaI'] = $f[2] . '-' . $f[1] . '-' . $f[0];
        }
        if (!empty($_REQUEST['FechaF'])) {
            $f = explode('/', $_REQUEST['FechaF']);
            $_REQUEST['FechaF'] = $f[2] . '-' . $f[1] . '-' . $f[0];
        }

        $y = $this->ValidarFecha($_REQUEST['FechaI']);
        if (empty($y)) {
            $this->frmError["FechaI"] = 1;
            $this->frmError["MensajeError"] = "Formato de Fecha Incorrecto.";
            $this->FormaBuscarEnvios($var);
            return true;
        }

        $z = $this->ValidarFecha($_REQUEST['FechaF']);
        if (empty($z)) {
            $this->frmError["FechaF"] = 1;
            $this->frmError["MensajeError"] = "Formato de Fecha Incorrecto.";
            $this->FormaBuscarEnvios($var);
            return true;
        }

        $_SESSION['ENVIOS']['FECHAI'] = $_REQUEST['FechaI'];
        $_SESSION['ENVIOS']['FECHAF'] = $_REQUEST['FechaF'];

        $d = 0;
        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'plan')) {
                $d++;
            }
        }

        $planes = "";
        $rangos = "";
        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'plan')) {
                ($planes == "") ? $planes = $v : $planes .= "," . $v;
                if ($_REQUEST['rango_' . $v] != '-1')
                    ($rangos == "") ? $rangos = "'" . $_REQUEST['rango_' . $v] . "'" : $rangos .= ",'" . $_REQUEST['rango_' . $v] . "'";
            }
        }
        if ($planes != "")
            $filtroPlan = " AND a.plan_id IN (" . $planes . ") ";
        if ($rangos != "")
            $filtroRangos = " AND d.rango IN (" . $rangos . ") ";

        if (!empty($_REQUEST['Todos'])) {
            $filtroPlan = '';
        }

        if ($_REQUEST['Dpto'] != -1 AND !empty($_REQUEST['Dpto'])) {
            $filtroDpto = " AND e.departamento_actual = '" . $_REQUEST['Dpto'] . "'";
            $_SESSION['ENVIOS']['DPTO'] = "'" . $_REQUEST['Dpto'] . "'";
        } else {
            $_SESSION['ENVIOS']['DPTO'] = 'NULL';
        }

        if (!empty($_REQUEST['FechaI']) AND !empty($_REQUEST['FechaF'])) {
            $filtroFecha = "and a.fecha_registro::date <= '" . $_REQUEST['FechaF'] . "'::date and a.fecha_registro::date >= '" . $_REQUEST['FechaI'] . "'::date ";
        } elseif (!empty($_REQUEST['FechaF'])) {
            $filtroFecha = " and a.fecha_registro::date <= '" . $_REQUEST['FechaF'] . "'::date ";
        } elseif (!empty($_REQUEST['FechaI'])) {
            $filtroFecha = " and a.fecha_registro::date >= '" . $_REQUEST['FechaI'] . "'::date ";
        }

        //FILTRO POR DOCUMENTOS
        if (!empty($_REQUEST['numero'])) {
            $filtroDoc = "and a.factura_fiscal=" . $_REQUEST['numero'] . "";
        }
        if (!empty($_REQUEST['prefijo'])) {
            $filtroDoc = "and a.prefijo='" . $_REQUEST['prefijo'] . "'";
        }

        //FILTRO PARA SERVICIOS
        $DatosServicios = $this->Servicios();
        $servicio = "";
        $filtroServicios = "";
        foreach ($DatosServicios AS $i => $v) {
            if ($_REQUEST['Servicios' . $i]) {
                if (!$servicio)
                    $servicio .= "'" . $_REQUEST['Servicios' . $i] . "'";
                else
                    $servicio .= ",'" . $_REQUEST['Servicios' . $i] . "'";
                $filtroServicios = " AND dep.servicio IN ($servicio) ";
                $tablaServicios = ", departamentos dep ";
                $whereServicios = " AND e.departamento_actual = dep.departamento ";
            }
        }
        //FIN FILTRO PARA SERVICIOS
        //FILTRO PARA TIPOS DE USUARIOS
        $DatosUsuarios = $this->TiposUsuarios();
        $TiposUsuarios = "";
        $filtroTiposUsuarios = "";
        foreach ($DatosUsuarios AS $i => $v) {
            if ($_REQUEST['TiposUsuarios' . $i] <> '') {
                if (!$TiposUsuarios)
                    $TiposUsuarios .= "'" . $_REQUEST['TiposUsuarios' . $i] . "'";
                else
                    $TiposUsuarios .= ",'" . $_REQUEST['TiposUsuarios' . $i] . "'";
                $filtroTiposUsuarios = " AND e.tipos_condicion_usuarios_planes_id IN ($TiposUsuarios) ";
            }
        }
        //FIN FILTRO PARA TIPOS DE USUARIOS
        //
      list($dbconn) = GetDBconn();

        $query = "select distinct *,
                       (select  count(b.envio_id)
                        from    envios as b,
                                envios_detalle as c
                        where   c.prefijo=a.prefijo
                        and     b.envio_id=c.envio_id
                        and     c.factura_fiscal=a.factura_fiscal
                        and     b.sw_estado in ('0','1') 
                       ) as porqueria
                from  (
                        SELECT  a.empresa_id,
                                a.prefijo,
                                a.factura_fiscal,
                                a.usuario_id,
                                a.fecha_registro,
                                a.total_factura,
                                a.plan_id,
                                b.plan_descripcion,
                                h.nombre as usuario,
                                b.sw_tipo_plan as tipo_plan,
                                d.ingreso,
                                c.numerodecuenta,
                                a.rango
                        FROM    fac_facturas as a,
                                planes as b,
                                system_usuarios as h,
                                fac_facturas_cuentas as c,
                                cuentas as d
                                $tablaServicios
                        WHERE a.plan_id=b.plan_id
                        and a.usuario_id=h.usuario_id
                        and a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                        and a.estado='0'
                        and a.sw_clase_factura='1'
                        and a.tipo_id_tercero='$x[0]'
                        and a.tercero_id='$x[1]'
                        and a.prefijo=c.prefijo and a.factura_fiscal=c.factura_fiscal
                        and c.numerodecuenta=d.numerodecuenta 
                        $whereServicios
                        $filtroPlan
                        $filtroFecha
                        $filtroDpto
                        $filtroDoc
                        $filtroServicios
                        $filtroTiposUsuarios
                        $filtroRangos
                        order by a.prefijo, a.factura_fiscal
                      ) as a
                      LEFT JOIN 	( 	SELECT 	a.prefijo, 
									a.factura_fiscal, 
									b.sw_estado
							FROM 	envios_detalle a, 
									envios b
							WHERE 	a.envio_id = b.envio_id
						) b 
						ON (a.prefijo = b.prefijo AND a.factura_fiscal = b.factura_fiscal)
			WHERE 	b.factura_fiscal IS NULL OR b.sw_estado = '2'";


        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                if ($_REQUEST['modificarenvio'] == '1') {
                    $var[] = $result->GetRowAssoc($ToUpper = false);
                } else {
                    $var[$result->fields[1]][$result->fields[2]] = $result->GetRowAssoc($ToUpper = false);
                }
                $result->MoveNext();
            }
        } else {
            $this->frmError["MensajeError"] = "La Busqueda no Arrojo Resultados.";
            $var = 'si';
        }

        $result->Close();
        if ($_REQUEST['modificarenvio'] == '1') {
            $this->FormaModificarFacturasEnvios($var);
        } else {
            $this->FormaBuscarEnvios($var, $adicionar);
        }
        return true;
    }

    /**
     *
     */
    function Facturas($prefijo, $numero, $fechai, $fechaf) {//and a.fecha_registro <'2005-01-30' para hacer envios cuando han pasado muchos meses
        $f = explode(',', $_SESSION['ENVIOS']['TERCERO']);
        //FILTRO POR DOCUMENTOS
        $filtroDoc = '';
        if (!empty($prefijo) AND !empty($numero)) {
            $filtroDoc = "and a.factura_fiscal=" . $numero . "
        and a.prefijo='" . $prefijo . "'";
        } else
        if (!empty($numero)) {
            $filtroDoc = "and a.factura_fiscal=" . $numero . "";
        } elseif (!empty($prefijo)) {
            $filtroDoc = "and a.prefijo='" . $prefijo . "'";
        }
        $fecha = "and a.fecha_registro > '" . $fechai . "' and a.fecha_registro < '" . $fechaf . "'";
        if (!empty($fechai) AND !empty($fechaf)) {
            $filtroFecha = "and date(a.fecha_registro) <= date('" . $fechaf . "') and date(a.fecha_registro) >= date('" . $fechai . "')";
        } elseif (!empty($fechaf)) {
            $filtroFecha = " and date(a.fecha_registro) <= date('" . $fechaf . "')";
        } elseif (!empty($fechai)) {
            $filtroFecha = " and date(a.fecha_registro) >= date('" . $fechai . "')";
        }
        //
        list($dbconn) = GetDBconn();
        $query = " select distinct *,
                (   select count(b.envio_id)
                                    from envios as b
                                            join envios_detalle as c
                                                on(b.envio_id=c.envio_id)
                                    where c.prefijo=a.prefijo
                                                and c.factura_fiscal=a.factura_fiscal
                                                and (b.sw_estado='0' or b.sw_estado='1')) as porqueria
                from (select    a.empresa_id,
                                                            a.prefijo,
                                                            a.factura_fiscal,
                                            a.usuario_id,
                                                            a.fecha_registro,
                                                            a.total_factura,
                                                            a.plan_id,
                                                            b.plan_descripcion,
                                            h.nombre as usuario,
                                                            btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                                            f.tipo_id_paciente||' '||f.paciente_id as id,
                                                            b.sw_tipo_plan as tipo_plan,
                                                            b.tipo_tercero_id,
                                                            b.tercero_id
                            from    fac_facturas as a,
                                                        planes as b,
                                                        system_usuarios as h,
                                                fac_facturas_cuentas c,
                                                        cuentas d,
                                                        ingresos e,
                                                        pacientes f
                            where a.plan_id=b.plan_id
                                                        and a.usuario_id=h.usuario_id
                                        and a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                                                        and a.estado='0'
                                                        and a.sw_clase_factura='1'
                                                        and b.tipo_tercero_id='$f[0]'
                                                        and b.tercero_id='$f[1]'
                                                        and a.prefijo=c.prefijo
                                                        and a.factura_fiscal=c.factura_fiscal
                                                        and a.empresa_id=c.empresa_id
                                                        and c.numerodecuenta=d.numerodecuenta
                                                        and d.ingreso=e.ingreso
                                                        and e.tipo_id_paciente = f.tipo_id_paciente
                                                        and e.paciente_id = f.paciente_id
                                                $filtroDoc
                                                        $filtroFecha
                            order by a.prefijo, a.factura_fiscal
                ) as a
                join(select
                                                        b.factura_fiscal,
                                                        b.prefijo
                                            from    fac_facturas as b
                                        left join envios_detalle as a
                                                            on(a.prefijo=b.prefijo
                                                                    and a.factura_fiscal=b.factura_fiscal)
                                        left join envios as c
                                                            on(a.envio_id=c.envio_id)
                            where (a.factura_fiscal is null or c.sw_estado='2')) as b
                on (b.factura_fiscal=a.factura_fiscal
                                        and b.prefijo=a.prefijo
                )";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
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

    function DetalleFactura($prefijo, $factura, $prnAnuladas) {
        $tabla_cuenta = "cuentas";
        $sq1 = "";
        if ($prnAnuladas) {
            $tabla_cuenta = "cuentas_facturas_anuladas";
            $sql = " a.estado in ('2','3') ";
            $sq1 = "AND     d.prefijo = '" . $prefijo . "' ";
            $sq1 .= "AND     d.factura_fiscal = " . $factura . " ";
            $sq1 .= "AND     d.empresa_id = '" . $_SESSION['FACTURACION']['EMPRESA'] . "' ";
        } else {
            $sql = "a.estado IN ('0','1')";
        } //0 -> FACTURADA - 1 -> PAGADA

        if (empty($prefijo)) {
            $prefijo = $_REQUEST['prefijo'];
            $factura = $_REQUEST['numero'];
        }

        list($dbconn) = GetDBconn();

        if (empty($_SESSION['SPYA'])) {
            $query = "SELECT count(c.numerodecuenta)
                      FROM   fac_facturas as a, 
                             fac_facturas_cuentas as c
                      WHERE  a.prefijo='$prefijo' and a.factura_fiscal='$factura'
                      AND    a.prefijo=c.prefijo and a.factura_fiscal=c.factura_fiscal
                      AND    a.empresa_id=c.empresa_id
                      AND    a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                      AND    $sql";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Tabal autorizaiones";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $_SESSION['SPYA'] = $result->fields[0];
            $result->Close();
        }

        if (!$_REQUEST['Ofagru']) {
            $Of = '0';
        } else {
            $Of = $_REQUEST['Ofagru'];
        }


        $query = "SELECT a.empresa_id,
                        a.prefijo,
                        a.factura_fiscal,
                        a.usuario_id,
                        a.fecha_registro,
                        a.total_factura,
                        a.plan_id,
						a.sw_clase_factura,
                        b.plan_descripcion,
                        b.sw_facturacion_agrupada,
                        c.numerodecuenta,
                        d.ingreso,
                        e.departamento_actual,
                        e.tipo_id_paciente,
                        e.paciente_id,
                        f.primer_nombre||' '||f.segundo_nombre||' '||f.primer_apellido||' '||f.segundo_apellido as nombre,
                        d.plan_id,
                        d.rango,
                        d.fecha_registro as fecha,
                        d.total_cuenta,
                        b.sw_tipo_plan as tipo_plan,
                        a.estado
                 FROM   fac_facturas as a,
                        planes as b,
                        fac_facturas_cuentas as c,
                        " . $tabla_cuenta . " as d,
                        ingresos as e,
                        pacientes as f
                WHERE   a.prefijo='$prefijo'
                AND     a.factura_fiscal='$factura'
                AND     a.plan_id=b.plan_id
                AND     a.prefijo=c.prefijo
                AND     a.factura_fiscal=c.factura_fiscal
                AND     a.empresa_id=c.empresa_id
                AND     c.numerodecuenta=d.numerodecuenta
                AND     d.ingreso=e.ingreso
                AND     e.tipo_id_paciente=f.tipo_id_paciente
                AND     e.paciente_id=f.paciente_id
                AND     a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                AND     $sql
                " . $sq1 . "
                LIMIT " . $this->limit . " OFFSET $Of;";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        if (!empty($_REQUEST['paso'])) {
            $this->FormaFacturasAgrupadas($var);
            return true;
        } else {
            return $var;
        }
    }

    /**
     * Llama la forma ConfirmarAccion (forma de mensaje de dos botones).
     * @ access public
     * @ return boolean
     */
    function ConfirmarAccion($Titulo, $mensaje, $boton1, $boton2, $arreglo, $c, $m, $me, $me2) {
        if (empty($Titulo)) {
            $arreglo = $_REQUEST['arreglo'];
            $Cuenta = $_REQUEST['Cuenta'];
            $c = $_REQUEST['c'];
            $m = $_REQUEST['m'];
            $me = $_REQUEST['me'];
            $me2 = $_REQUEST['me2'];
            $mensaje = $_REQUEST['mensaje'];
            $Titulo = $_REQUEST['titulo'];
            $boton1 = $_REQUEST['boton1'];
            $boton2 = $_REQUEST['boton2'];
        }

        $this->salida = ConfirmarAccion($Titulo, $mensaje, $boton1, $boton2, array($c, $m, 'user', $me, $arreglo), array($c, $m, 'user', $me2, $arreglo));
        return true;
    }

    /**
     *
     */
    function LlamarFormaEnvio() {
        unset($_SESSION['FACTURACION']['ENVIO']['SELECCION']);
        $f = 0;
        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'Envio')) {
                if (!empty($v)) {
                    $f = 1;
                }
            }
        }

        if ($f == 0) {
            $this->frmError["MensajeError"] = "Debe Elegir las Facturas.";
            $this->FormaBuscarEnvios($_SESSION['FACTURACION']['ENVIO']['ARREGLO']);
            return true;
        } else {
            $_SESSION['FACTURACION']['ENVIO']['SELECCION'] = $_REQUEST;
            $this->FormaEnvio($_REQUEST);
            return true;
        }
    }

    function AdicionarFacturaEnvio() {
        $adicionar = $_REQUEST['adicionar'];
        $arr = $_SESSION['FACTURACION']['ENVIO']['ARREGLO'];
        $this->FormaBuscarEnvios($arr, '', '', $adicionar);
        return true;
    }

    /**
     *
     */
    function TercerosPlanes() {
        list($dbconn) = GetDBconn();
        $query = "select distinct a.tipo_tercero_id, a.tercero_id, b.nombre_tercero
                from planes as a, terceros as b
                where a.tipo_tercero_id=b.tipo_id_tercero
                and a.tercero_id=b.tercero_id
                and a.fecha_final >= now() and a.estado=1
                and a.fecha_inicio <= now()
                                and a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                                order by b.nombre_tercero";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
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
     *
     */
    function HacerEnvio() {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query = "SELECT nextval('envios_envio_id_seq')";
        $result = $dbconn->Execute($query);
        $envio = $result->fields[0];
        $fecha_envio = $this->ConvFecha($_REQUEST['Fecha_Envio']);

        $query = "INSERT INTO envios (
                            envio_id,
                            fecha_inicial,
                            fecha_final,
                            fecha_radicacion,
                            departamento,
                            usuario_id,
                            fecha_registro,
                            sw_estado,
                            fecha_registro_sistema,
                            observaciones)
                VALUES( $envio,
                                                '" . $_SESSION['ENVIOS']['FECHAI'] . "',
                                                '" . $_SESSION['ENVIOS']['FECHAF'] . "',
                                                NULL,
                                                " . $_SESSION['ENVIOS']['DPTO'] . ",
                                                " . UserGetUID() . ",
                                                '" . $fecha_envio . "',
                                                '0',
                                                now(),
                                                '$_REQUEST[Observaciones]')";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $i = 0;
        foreach ($_SESSION['FACTURACION']['ENVIO']['SELECCION'] as $k => $v) {
            if (substr_count($k, 'Envio')) {
                if ($i % 2) {
                    $estilo = 'modulo_list_claro';
                } else {
                    $estilo = 'modulo_list_oscuro';
                }

                //0 prefijo 1 factura 2 tipoid y paciente 3 nombre
                //4 total 5 plan 6 plan_des 7empresa 8 centro
                $x = explode('||', $v);
                if (!isset($arr[$envio])) {
                    $arr[$envio]["envio_id"] = $envio;
                    $arr[$envio]["plan_descripcion"] = $x[6];
                    $arr[$envio]["cantidad_facturas"] = 0; //Cantidad de facturas del envio
                    $arr[$envio]["total_envio"] = 0; //Sumatoria del total de las facturas
                }
                $arr[$envio]['total_envio'] += $x[4];
                $arr[$envio]['cantidad_facturas']++;
                //Se cargan en el vertor con un indice para determinar en el momento
                //de la impresion si el envio contiene varios planes, tipo_planes o terceros
                $arr[$envio]['print'][$i]["plan_id"] = $x[5];
                $arr[$envio]['print'][$i]["tipo_plan"] = $x[9];
                $arr[$envio]['print'][$i]["tipo_tercero_id"] = $x[10];
                $arr[$envio]['print'][$i]["tercero_id"] = $x[11];

                $query = "select envio_id,plan_id from envios_planes
                          where plan_id='$x[5]' and envio_id=$envio";
                $results = $dbconn->Execute($query);
                if ($results->EOF) {
                    /*
                      if($paso==0)
                      {
                      $query="SELECT nextval('envios_envio_id_seq')";
                      $result=$dbconn->Execute($query);
                      $envio=$result->fields[0];

                      $query = "INSERT INTO envios (
                      envio_id,
                      fecha_inicial,
                      fecha_final,
                      fecha_radicacion,
                      departamento,
                      usuario_id,
                      fecha_registro,
                      sw_estado)
                      VALUES($envio,'".$_SESSION['ENVIOS']['FECHAI']."','".$_SESSION['ENVIOS']['FECHAF']."',NULL,".$_SESSION['ENVIOS']['DPTO'].",".UserGetUID().",'now()','0')";
                      $dbconn->Execute($query);
                      if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error al Guardar en envios";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      $dbconn->RollbackTrans();
                      return false;
                      }
                      }
                      /* */
                    $paso = 0;
                    $query = "INSERT INTO envios_planes (
                                                                                                                    envio_id,
                                                                                                                    plan_id)
                                                                                        VALUES($envio,'$x[5]')";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en envios_planes";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                } else {  //ya esta en envios
                    $envio = $results->fields[0];
                }
                //exit;

                $query = "select * from envios_detalle
                                                    where envio_id=$envio
                                                                and factura_fiscal='$x[1]'
                                                                and prefijo='$x[0]'";
                $result = $dbconn->Execute($query);
                if ($result->EOF) {
                    $query = "INSERT INTO envios_detalle (
                                          envio_id,
                                          prefijo,
                                          factura_fiscal,
                                          empresa_id)
                              VALUES($envio,'$x[0]','$x[1]','$x[7]')";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en envios_de";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                }
            }
            $i++;
        }//fin foreach

        $dbconn->CommitTrans();
        $this->FormaImpresionEnvio($titulo = "", $arr);

        return true;
    }

    /**
     * Busca los diferentes tipos de responsable (planes)
     * @access public
     * @return array
     */
    function Inactivos($tipo, $tercero) {
        $EmpresaId = $_SESSION['FACTURACION']['EMPRESA'];
        $CentroU = $_SESSION['FACTURACION']['CENTROUTILIDAD'];
        if (!empty($_SESSION['FACTURACION']['CU'])) {
            $CU = "and a.centro_utilidad='$CentroU'";
        }

        $var = '';
        if (!empty($tipo) or !empty($tercero)) {
            $var = " and a.tipo_tercero_id='$tipo' and a.tercero_id='$tercero'";
        }

        list($dbconn) = GetDBconn();
        $query = "( SELECT DISTINCT a.plan_id, a.plan_descripcion, a.tercero_id, a.tipo_tercero_id
                FROM planes as a
                WHERE a.fecha_final < now() and a.fecha_inicio > now()
                or a.estado=0 and a.tipo_id_tercero='$tipo'
                and a.tercero_id='$tercero'
              )";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            if ($result->EOF) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'planes' esta vacia ";
                return false;
            }
            while (!$result->EOF) {
                $planes[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $planes;
    }

    /**
     *
     */
    function BuscarEnviosRad() {
        $filtroFecha = '';
        $filtroResponsable = '';
        $filtroEnvio = '';
        $filtroFactura = '';
        $tablascentroutilidad = '';
        $filtroCentroUtilidad = '';

        if (!empty($_REQUEST['FechaI'])) {
            $f = explode('/', $_REQUEST['FechaI']);
            $_REQUEST['FechaI'] = $f[2] . '-' . $f[1] . '-' . $f[0];
        }
        if (!empty($_REQUEST['FechaF'])) {
            $f = explode('/', $_REQUEST['FechaF']);
            $_REQUEST['FechaF'] = $f[2] . '-' . $f[1] . '-' . $f[0];
        }

        if (!empty($_REQUEST['FechaI']) AND !empty($_REQUEST['FechaF'])) {
            $filtroFecha = "and date(a.fecha_final) <= date('" . $_REQUEST['FechaF'] . "') and date(a.fecha_inicial) >= date('" . $_REQUEST['FechaI'] . "')";
        } elseif (!empty($_REQUEST['FechaF'])) {
            $filtroFecha = " and date(a.fecha_final) <= date('" . $_REQUEST['FechaF'] . "')";
        } elseif (!empty($_REQUEST['FechaI'])) {
            $filtroFecha = " and date(a.fecha_inicial) >= date('" . $_REQUEST['FechaI'] . "')";
        }

        if ($_REQUEST['Responsable'] != -1) {
            $x = explode(',', $_REQUEST['Responsable']);
            $filtroResponsable = " and c.tercero_id='$x[1]' and c.tipo_tercero_id='$x[0]'";
        }
        if (!empty($_REQUEST['envio'])) {
            $filtroEnvio = " and a.envio_id=" . $_REQUEST['envio'] . "";
        }
        if (!empty($_REQUEST['factura']) AND !empty($_REQUEST['prefijo'])) {
            $filtroFactura = " and d.factura_fiscal=" . $_REQUEST['factura'] . " and  d.prefijo='" . $_REQUEST['prefijo'] . "'";
        }

        if ($_REQUEST['Dpto'] != -1) {
            $filtroEnvio = " and a.departamento=" . $_REQUEST['Dpto'] . "";
        }
        if (!empty($_REQUEST['centro_utilidad'])) {
            $tablascentroutilidad = "";
            $filtroCentroUtilidad = "";
            if (ModuloGetVar('', '', 'envios_x_centro_utilidad_' . $_REQUEST['empresa_id'] . '_' . $_REQUEST['centro_utilidad'] . '')) {
                $tablascentroutilidad = " ,fac_facturas_cuentas FAC,cuentas CTA ";
                $filtroCentroUtilidad = " and CTA.centro_utilidad='" . $_REQUEST['centro_utilidad'] . "'
									  and CTA.empresa_id='" . $_REQUEST['empresa_id'] . "'
									  and CTA.empresa_id=FAC.empresa_id
									  and CTA.numerodecuenta=FAC.numerodecuenta  
									  and FAC.factura_fiscal=d.factura_fiscal 
									  and FAC.prefijo=d.prefijo
									  and FAC.empresa_id= d.empresa_id ";
            }
        }
        list($dbconn) = GetDBconn();
//$dbconn->debug=true;
        $query = "select  distinct  a.*,
                          e.nombre_tercero,
                          f.nombre,
                          c.sw_tipo_plan
                  from    envios as a,
                          envios_detalle as d,
                          envios_planes as b,
                          planes as c,
                          terceros as e,
                          system_usuarios as f
                          $tablascentroutilidad
                  where   a.envio_id=b.envio_id
                  and     a.envio_id=d.envio_id
                  and     b.plan_id=c.plan_id
                  and     sw_estado<>2
                  $filtroResponsable
                  $filtroFecha
                  $filtroEnvio
                  $filtroFactura
                  $filtroCentroUtilidad
                  and     c.tercero_id=e.tercero_id
                  and     c.tipo_tercero_id=e.tipo_id_tercero
                  and     a.usuario_id=f.usuario_id
                  order by a.envio_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        } else {
            $this->frmError["MensajeError"] = "La Busqueda no Arrojo Resultados.";
            $var = 'si';
        }

        $query = "select  distinct     a.*,
                                                                            e.nombre_tercero,
                                                                            f.nombre,
                                                                            c.plan_id,
                                                                            c.sw_tipo_plan as tipo_plan,
                                                                            e.tipo_id_tercero ,
                                                                            e.tercero_id
                    from    envios as a,
                                                    envios_detalle as d,
                                                    envios_planes as b,
                                planes as c,
                                                    terceros as e,
                                                    system_usuarios as f
                    where a.envio_id=b.envio_id
                                                    and a.envio_id=d.envio_id
                                                    and b.plan_id=c.plan_id
                                                    and sw_estado<>2
                                                    $filtroResponsable
                                                    $filtroFecha
                                                    $filtroEnvio
                                                    $filtroFactura
                                                    and c.tercero_id=e.tercero_id
                                                    and c.tipo_tercero_id=e.tipo_id_tercero
                                                    and a.usuario_id=f.usuario_id
                                        order by a.envio_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            $i = 0;
            while (!$result->EOF) {
                $datos = $result->GetRowAssoc($ToUpper = false);
                $pr['print'][$datos['envio_id']]['print'][$i]['plan_id'] = $datos['plan_id'];
                $pr['print'][$datos['envio_id']]['print'][$i]['tipo_plan'] = $datos['tipo_plan'];
                $pr['print'][$datos['envio_id']]['print'][$i]['tipo_tercero_id'] = $datos['tipo_tercero_id'];
                $pr['print'][$datos['envio_id']]['print'][$i]['tercero_id'] = $datos['tercero_id'];
                $result->MoveNext();
                $i++;
            }
        }
        $var = array_merge($var, $pr);
        $this->FormaBuscarRad($var);
        return true;
    }

    /**
     *
     */
    function DetalleEnvio($envio) {
        unset($_SESSION['DETALLE']['ENVIO']);
        if ($envio) {
            $_REQUEST['envio'] = $envio;
        } else {
            $_REQUEST['envio'] = $_REQUEST['envio'];
        }
        list($dbconn) = GetDBconn();
        $query = "SELECT a.*, b.*, c.numerodecuenta, d.total_factura,
            d.valor_cuota_paciente, d.plan_id, e.plan_descripcion, f.ingreso,
            i.nombre_tercero, e.tipo_tercero_id, e.tercero_id, a.fecha_registro,e.sw_tipo_plan as tipo_plan
            FROM envios as a, envios_detalle as b, fac_facturas_cuentas as c,
            fac_facturas as d, planes as e, cuentas as f,
                        terceros as i
            WHERE a.envio_id=" . $_REQUEST['envio'] . "
            and a.envio_id=b.envio_id and b.prefijo=c.prefijo
            and b.factura_fiscal=c.factura_fiscal and d.prefijo=c.prefijo
            and d.factura_fiscal=c.factura_fiscal and d.plan_id=e.plan_id
            and c.numerodecuenta=f.numerodecuenta
            and e.tipo_tercero_id=i.tipo_id_tercero and e.tercero_id=i.tercero_id
                        order by b.prefijo, b.factura_fiscal";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "DetalleEnvio";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            $i = 0;
            while (!$result->EOF) {
                $datos = $result->GetRowAssoc($ToUpper = false);
                $arr[] = $datos;
                $arr['print'][$datos['envio_id']]['print'][$i]['plan_id'] = $datos['plan_id'];
                $arr['print'][$datos['envio_id']]['print'][$i]['tipo_plan'] = $datos['tipo_plan'];
                $arr['print'][$datos['envio_id']]['print'][$i]['tipo_tercero_id'] = $datos['tipo_tercero_id'];
                $arr['print'][$datos['envio_id']]['print'][$i]['tercero_id'] = $datos['tercero_id'];
                $result->MoveNext();
                $i++;
            }
        }
        $_SESSION['DETALLE']['ENVIO'] = $arr;
        $result->Close();
        $this->FormaDetalleEnvio();
        return true;
    }

    /**
     *
     */
    function NombreEmpresa($empresa) {
        list($dbconn) = GetDBconn();
        $query = "select razon_social from empresas
                where empresa_id='$empresa'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $result->Close();
        return $result->fields[0];
    }

    /**
     *
     */
    function NombreDpto($dpto) {
        list($dbconn) = GetDBconn();
        $query = "select descripcion from departamentos
                where departamento='$dpto'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $result->Close();
        return $result->fields[0];
    }

    /**
     *
     */
    function InsertarRadicacion() {
        IncludeLib('funciones_admision');

        if (empty($_REQUEST['Fecha'])) {
            $this->frmError["Fecha"] = 1;
            $this->frmError["MensajeError"] = "Debe digitar la Fecha de Radicaci�.";
            $this->FormaRadicacion();
            return true;
        }
        $arr = $_SESSION['DETALLE']['ENVIO'];


        $f_envio = explode(' ', $arr[0]['fecha_registro']);

        $f = explode('/', $_REQUEST['Fecha']);
        $_REQUEST['Fecha'] = $f[2] . '-' . $f[1] . '-' . $f[0];

        if (strtotime($_REQUEST['Fecha']) < strtotime($f_envio[0])) {
            $this->frmError["Fecha"] = 1;
            $this->frmError["MensajeError"] = "La Fecha de radicaci� debe ser mayor o igual a la de este.";
            $this->FormaRadicacion();
            return true;
        }

        $validar = $this->ValidarFecha($_REQUEST['Fecha']);
        if (empty($validar)) {
            $this->FormaRadicacion();
            return true;
        }

        if (!empty($_REQUEST['rad'])) {
            if ($_REQUEST['rad'] == $_REQUEST['Fecha'] . ' 00:00:00') {
                $this->frmError["Fecha"] = 1;
                $this->frmError["MensajeError"] = "La Nueva Fecha de Radicaci� debe ser diferente a la Actual.";
                $this->FormaRadicacion();
                return true;
            }
        }
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        if (!empty($_REQUEST['rad'])) {
            $query = "insert into auditoria_fechas_radicacion
                    values(" . $_REQUEST['envio'] . ",'" . $_REQUEST['rad'] . "','" . $_REQUEST['Fecha'] . "'," . UserGetUID() . ",'now()')";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en envios";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }

        //la fecha de vencimiento de factura
        $Fecha = FechaStamp($_REQUEST['rad']);
        //$infoCadena = explode ('/',$Fecha);

        $query = "SELECT a.dias_credito_cartera, a.plan_id
                                FROM planes as a, envios_planes as b
                                WHERE b.envio_id=" . $_REQUEST['envio'] . " and b.plan_id=a.plan_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            $dbconn->RollbackTrans();
            return false;
        }

        while (!$result->EOF) {
            $vencimiento = '';
            if (!empty($result->fields[0])) {
                $vencimiento = date("Y-m-d H:i:s", mktime(0, 0, 0, $f[1], ($f[0] + $result->fields[0]), $f[2]));
                $query1 = " UPDATE envios_planes SET fecha_vencimiento_facturas='" . $vencimiento . "'
                                                        WHERE envio_id=" . $_REQUEST['envio'] . " and plan_id=" . $result->fields[1] . "";
                $dbconn->Execute($query1);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en envios";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    $dbconn->RollbackTrans();
                    return false;
                }
            }
            $result->MoveNext();
        }
        $result->Close();

        $query = "UPDATE envios SET fecha_radicacion='" . $_REQUEST['Fecha'] . "',
                        sw_estado=1
                WHERE envio_id=" . $_REQUEST['envio'] . "";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $dbconn->CommitTrans();

        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarRad');
        if (empty($_REQUEST['rad'])) {
            $mensaje = 'El Envio No. ' . $_REQUEST['envio'] . ' fue Radicado.';
        } else {
            $mensaje = 'Se Modifico la Fecha de Radicaci� del Envio No. ' . $_REQUEST['envio'];
        }
        $this->FormaMensaje($mensaje, 'ENVIO No. ' . $_REQUEST['envio'], $accion, 'ACEPTAR');
        return true;
    }

    /**
     *
     */
    function ValidarFecha($fecha) {
        $x = explode("-", $fecha);
        if (!checkdate($x[1], $x[2], $x[0])) {
            $this->frmError["MensajeError"] = "Formato de Fecha Incorrecto ";
            return false;
        }
        return true;
    }

    /**
     *
     */
    function AnularEnvio() {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query = "insert into auditoria_anulacion_envios
                values(" . $_REQUEST['envio'] . ",'" . $_REQUEST['Observacion'] . "'," . UserGetUID() . ",'now()')";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $query = "UPDATE envios SET sw_estado=2
                WHERE envio_id=" . $_REQUEST['envio'] . "";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $dbconn->CommitTrans();
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarRad');
        $mensaje = 'El Envio No ' . $_REQUEST['envio'] . ' fue Anulado.';
        $this->FormaMensaje($mensaje, 'ANULAR ENVIO No. ' . $_REQUEST['envio'], $accion, 'ACEPTAR');
        return true;
    }

//---------------------BUSCAR FACTURAS--------------------------------------

    /**
     * Busca los diferentes tipos de identificacion de los paciente
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
            return false;
        } else {
            if ($result->EOF) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
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
     *
     */

    function ObtenerTipoIdTercero() {
        $fct = new app_Facturacion_Permisos();
        $tid = $fct->ObternerTiposIdTerceros();
        return $tid;
    }

    /*
     *
     */

    function BuscarFacturas() {
        unset($_SESSION['FACTURACION']['VAR']);

        $this->post = $_REQUEST;
        $this->post['PrefijoFac'] = $this->post['PrefijoFac'];

        $cantidad = $_SESSION['SPY'];
        $this->Emp = $_SESSION['FACTURACION']['EMPRESA'];

        if (empty($_REQUEST['paso']))
            $cantidad = 0;

        $cuenta = $this->post['Cuenta'];
        $documento = $this->post['Documento'];
        $factura = $this->post['Factura'];
        $historia = $this->post['Historia'];
        $ingreso = $this->post['Ingreso'];
        $nombres = $this->post['Nombres'];
        $apellidos = $this->post['Apellidos'];
        $prefijofac = $this->post['PrefijoFac'];
        $tercero_dc = $this->post['DocumentoTercero'];
        $tercero_td = $this->post['TipoDocumentoTercero'];
        $tipo_documento = $this->post['TipoDocumento'];

        if (!empty($factura) && !is_numeric($factura)) {
            $this->frmError["MensajeError"] = "EL CAMPO No. FACTURA DEBE SER NUMERICO";
            $this->FormaBuscarFacturas();
            return true;
        }

        if (!empty($cuenta) && !is_numeric($cuenta)) {
            $this->frmError["MensajeError"] = "EL CAMPO No. CUENTA DEBE SER NUMERICO";
            $this->FormaBuscarFacturas();
            return true;
        }

        if (!empty($ingreso) && !is_numeric($ingreso)) {
            $this->frmError["MensajeError"] = "EL CAMPO No. INGRESO DEBE SER NUMERICO";
            $this->FormaBuscarFacturas();
            return true;
        }

        $fct = new app_Facturacion_Permisos();
        $paso = false;
        if (!empty($prefijofac)) {
            $paso = true;
            if (empty($factura))
                $this->frmError['MensajeError'] = "SE DEBE INDICAR EL NUMERO DE FACTURA A BUSCAR";
            else
                $var = $fct->ObtenerFacturasXPrefijo($this->post, $this->Emp);
        }
        else if (!empty($ingreso) || !empty($cuenta)) {
            $paso = true;
            $var = $fct->ObtenerFacturasXCuenta($this->post, $this->Emp, SessionGetVar("DocumentosFacturacion"), $_REQUEST['Of'], $cantidad);
        } else if (!empty($documento)) {
            $paso = true;
            if (empty($tipo_documento))
                $this->frmError['MensajeError'] = "SE DEBE INDICAR EL TIPO DE DOCUMENTO CON EL QUE SE REALIZARA LA BUSQUEDA";
            else
                $var = $fct->ObtenerFacturasXPaciente($this->post, $this->Emp, SessionGetVar("DocumentosFacturacion"), $_REQUEST['Of'], $cantidad);
        }
        else if (!empty($tercero_dc)) {
            $paso = true;
            if (empty($tercero_td))
                $this->frmError['MensajeError'] = "SE DEBE INDICAR EL TIPO DE TERCERO A BUSCAR";
            else
                $var = $fct->ObtenerFacturasXTerceroId($this->post, $this->Emp, SessionGetVar("DocumentosFacturacion"), $_REQUEST['Of'], $cantidad);
        }
        else if (!empty($nombres) || !empty($apellidos)) {
            $paso = true;
            $var = $fct->ObtenerFacturasXNombrePaciente($this->post, $this->Emp, SessionGetVar("DocumentosFacturacion"), $_REQUEST['Of'], $cantidad);
        } else {
            $this->frmError['MensajeError'] = "SE DEBEN INDICAR PARAMETROS DE BUSQUEDA";
        }

        if (empty($var) && $paso)
            $this->frmError['MensajeError'] = "LA BUSQUEDA NO ARROJO NINGUN RESULTADO";

        if (empty($_REQUEST['paso']))
            $_SESSION['SPY'] = $fct->conteo;

        $this->FormaBuscarFacturas($var);
        return true;
    }

//-------------------------ANULAR FACTURA-------------------------------

    /**
     *
     */
    function BuscarFactura($Cuenta) {
        list($dbconn) = GetDBconn();
        $query = "select distinct *,
                                    (select count(*) from envios as b join envios_detalle as c on(b.envio_id=c.envio_id) where c.prefijo=a.prefijo and c.factura_fiscal=a.factura_fiscal and (b.sw_estado=0 or b.sw_estado=1)) as porqueria
                                    from (
                                            select a.empresa_id, a.prefijo, a.factura_fiscal,
                                            a.usuario_id, a.fecha_registro, a.total_factura, a.plan_id,
                                            c.numerodecuenta
                                            from fac_facturas as a, fac_facturas_cuentas as c
                                            where a.prefijo=c.prefijo and a.factura_fiscal=c.factura_fiscal
                                            and a.empresa_id=c.empresa_id
                                            and a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                                            and c.numerodecuenta=$Cuenta
                                            and a.estado=0
                                            order by a.prefijo, a.factura_fiscal) as a
                                    join
                                    (select b.factura_fiscal,b.prefijo from fac_facturas as b
                                    left join envios_detalle as a on(a.prefijo=b.prefijo and a.factura_fiscal=b.factura_fiscal)
                                    left join envios as c on(a.envio_id=c.envio_id)
                                    where (a.factura_fiscal is null or c.sw_estado=2)) as b
                                    on (b.factura_fiscal=a.factura_fiscal and b.prefijo=a.prefijo)";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
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
     *
     */
    function AnularFactura() {
        $Transaccion = $_REQUEST['Transaccion'];
        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Nivel = $_REQUEST['Nivel'];
        $PlanId = $_REQUEST['PlanId'];
        $Fecha = $_REQUEST['Fecha'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Cuenta = $_REQUEST['Cuenta'];
        $Estado = $_REQUEST['Estado'];

        if (empty($_REQUEST['observacion'])) {
            $this->frmError["Observaciones"] = 1;
            $this->frmError["MensajeError"] = "Debe escribir la justificaci�.";
            $this->FormaAnular($Transaccion, $TipoId, $PacienteId, $Nivel, $PlanId, $Fecha, $Ingreso, $Cuenta, $Estado);
            return true;
        }

        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        //para ver si ya tiene una cuenta activa
        $query = " SELECT a.ingreso, c.numerodecuenta
                                        FROM ingresos as a, cuentas as c
                                        WHERE a.estado=1 and a.paciente_id='$TipoId' AND a.tipo_id_paciente ='$PacienteId'
                                        AND a.ingreso=c.ingreso AND c.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                                        AND c.estado=1";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        //tiene una cuenta activa
        if (!$result->EOF) {
            //se pone la cuenta inactiva
            $query = "UPDATE cuentas SET estado=2
                                                WHERE numerodecuenta='" . $_REQUEST['Cuenta'] . "'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error UPDATE cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            //se activa el ingreso
            $query = "UPDATE ingresos SET estado='0',fecha_cierre='now()'
                                                WHERE ingreso=$Ingreso";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error UPDATE ingresos ";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        } else {
            //se pone la cuenta activa
            $query = "UPDATE cuentas SET estado=1
                                                WHERE numerodecuenta='" . $_REQUEST['Cuenta'] . "'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error UPDATE cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            //se activa el ingreso
            $query = "UPDATE ingresos SET estado='0',fecha_cierre='now()'
                                                WHERE ingreso=$Ingreso";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error UPDATE ingresos ";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }

        $query = "UPDATE fac_facturas SET estado=2
                                    WHERE prefijo='" . $_SESSION['FACTURACION']['VAR']['prefijo'] . "'
                                    and factura_fiscal='" . $_SESSION['FACTURACION']['VAR']['factura'] . "'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar UPDATE fac_facturas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        IncludeLib("funciones_facturacion");
        $des = BuscarCargoAjusteDes($Cuenta);
        $apro = BuscarCargoAjusteApro($Cuenta);

        if ($des['transaccion']) {
            $query = "DELETE FROM cuentas_detalle WHERE transaccion=" . $des['transaccion'] . "";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "1Error DELETE FROM cuentas_detalle";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }
        if ($apro['transaccion']) {
            $query = "DELETE FROM cuentas_detalle WHERE transaccion=" . $apro['transaccion'] . "";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "2Error DELETE FROM cuentas_detalle";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }

        $query = "INSERT INTO auditoria_anulacion_fac_facturas
                                    VALUES('" . $_SESSION['FACTURACION']['VAR']['empresa'] . "',
                                    '" . $_SESSION['FACTURACION']['VAR']['prefijo'] . "'," . $_SESSION['FACTURACION']['VAR']['factura'] . ",
                                    '" . $_REQUEST['observacion'] . "','now()'," . UserGetUID() . ")";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error INSERT INTO auditoria_anulacion_fac_facturas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $dbconn->CommitTrans();

        $mensaje = 'La Factura No. ' . $_SESSION['FACTURACION']['VAR']['prefijo'] . $_SESSION['FACTURACION']['VAR']['factura'] . ' ha sido Anulada.';
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarFacturas');
        if (!$this->FormaMensaje($mensaje, 'ANULAR FACTURA FISCAL', $accion, 'ACEPTAR')) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    function AnularFacturaAgrupada() {
        if (empty($_REQUEST['observacion'])) {
            $this->frmError["Observaciones"] = 1;
            $this->frmError["MensajeError"] = "Debe escribir la justificaci�.";
            $this->FormaAnularAgrupadas();
            return true;
        }

        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query = "UPDATE fac_facturas SET estado=2
                WHERE prefijo='" . $_REQUEST['prefijo'] . "'
                and factura_fiscal='" . $_REQUEST['numero'] . "'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar UPDATE fac_facturas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $query = "INSERT INTO auditoria_anulacion_fac_facturas
                VALUES('" . $_REQUEST['empresa'] . "',
                '" . $_REQUEST['prefijo'] . "'," . $_REQUEST['numero'] . ",
                '" . $_REQUEST['observacion'] . "','now()'," . UserGetUID() . ")";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error INSERT INTO auditoria_anulacion_fac_facturas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $dat = $_REQUEST['datos'];
        for ($i = 0; $i < sizeof($dat); $i++) {
            $query = "UPDATE cuentas SET estado=3, fecha_cierre='now()',
                                        usuario_cierre=" . UserGetUID() . "
                                        WHERE numerodecuenta='" . $dat[$i]['cuenta'] . "'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error UPDATE cuentas";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }

            $query = "UPDATE ingresos SET estado='0',fecha_cierre='now()'
                                        WHERE ingreso=" . $dat[$i]['ingreso'] . "";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error UPDATE ingresos ";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }
        $dbconn->CommitTrans();

        $mensaje = 'La Factura No. ' . $_REQUEST['prefijo'] . $_REQUEST['numero'] . ' ha sido Anulada.';
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarFacturas');
        if (!$this->FormaMensaje($mensaje, 'ANULAR FACTURA FISCAL', $accion, 'ACEPTAR')) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    function BuscarTipoFactura($prefijo, $empresa) {
        list($dbconn) = GetDBconn();
        $query = " select tipo_factura_id from fac_tipos_facturas
                    where prefijo='$prefijo' and empresa_id='$empresa'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            $var = $result->GetRowAssoc($ToUpper = false);
        }
        $result->Close();
        return $var;
    }

//--------------------------REPORTES-------------------------------------

    function LlamarFormaBuscarUsuario() {
        unset($_SESSION['FACTURACION']['FECHAREPORTE']);
        if (!empty($_REQUEST['Fecha'])) {
            $f = explode('/', $_REQUEST['Fecha']);
            $_REQUEST['Fecha'] = $f[2] . '-' . $f[1] . '-' . $f[0];
            $val = $this->ValidarFecha($_REQUEST['Fecha']);
            if (empty($val)) {
                $f = explode('-', $_REQUEST['Fecha']);
                $_REQUEST['Fecha'] = $f[2] . '/' . $f[1] . '/' . $f[0];
                $this->frmError["Fecha"] = 1;
                $this->frmError["MensajeError"] = "Formato de fecha incorrecto.";
                $this->SeleccionarFecha();
                return true;
            }
        } else {
            $this->frmError["Fecha"] = 1;
            $this->frmError["MensajeError"] = "Debe digitar la fecha del reporte.";
            $this->SeleccionarFecha();
            return true;
        }

        $_SESSION['FACTURACION']['FECHAREPORTE'] = $_REQUEST['Fecha'];
        $this->FormaBuscarUsuario();
        return true;
    }

    /**
     *
     */
    function BuscarUsuariosSistema($filtro) {
        list($dbconn) = GetDBconn();
        if (empty($_REQUEST['conteo'])) {
            $query = "select a.usuario_id,d.usuario,d.nombre,d.descripcion
                      from system_usuarios_empresas a, system_usuarios as d
                      where a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                      and a.usuario_id=d.usuario_id
                      $filtro order by d.nombre";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if (!$result->EOF) {
                $this->conteo = $result->RecordCount();
            }
        } else {
            $this->conteo = $_REQUEST['conteo'];
        }
        if (!$_REQUEST['Of']) {
            $Of = '0';
        } else {
            $Of = $_REQUEST['Of'];
        }
        $query = " select a.usuario_id,d.usuario,d.nombre,d.descripcion
                  from system_usuarios_empresas a, system_usuarios as d
                  where a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                  and a.usuario_id=d.usuario_id
                  $filtro order by d.nombre
                  LIMIT " . $this->limit . " OFFSET $Of";
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

    function GetFiltroUsuarios($num, $busca) {
        switch ($num) {
            case "1": {
                    if (is_numeric($busca)) {
                        $filtro = "AND d.usuario_id=" . trim($busca) . "";
                    } else {
                        $filtro = "";
                    }
                    $_SESSION['CENTRAL']['negrilla'] = 1;
                    break;
                }
            case "2": {
                    $filtro = "AND lower(d.usuario) like '%" . strtolower(trim($busca)) . "%'";
                    //or lower(d.usuario) like '%".strtolower(trim($busca))."'
                    // or lower(d.usuario) like '".strtolower(trim($busca))."%'
                    $_SESSION['CENTRAL']['negrilla'] = 2;
                    break;
                }
            case "3": {
                    $filtro = "AND lower(d.nombre) like '%" . strtolower(trim($busca)) . "%'";
                    //or lower(d.nombre) like '%".strtolower(trim($busca))."'
                    //or lower(d.nombre) like '".strtolower(trim($busca))."%'
                    $_SESSION['CENTRAL']['negrilla'] = 3;
                    break;
                }
        }
        return $filtro;
    }

    function BuscarFacturasUsuario() {
        list($dbconn) = GetDBconn();
        $query = " select a.usuario_id,d.usuario,d.nombre,d.descripcion
                  from system_usuarios_empresas a, system_usuarios as d
                  where a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                  and a.usuario_id=d.usuario_id
                  $filtro order by d.nombre
                  LIMIT " . $this->limit . " OFFSET $Of";
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
     *
     */
    function DatosReporte($usuario) {      //solo trae las facturas de cliente sw_tipo=1 cliente
        list($dbconn) = GetDBconn();
        $query = "( select c.estado, a.tipo_id_tercero, a.tercero_id, a.empresa_id, a.prefijo, a.factura_fiscal, a.total_factura,
                    a.valor_cuota_paciente, a.plan_id,
                    b.numerodecuenta, d.plan_descripcion, e.nombre_tercero, c.total_cuenta,
                    case b.sw_tipo when 0 then 'PACIENTE' else 'CLIENTE' end as tipo,
                    f.tipo_id_paciente, f.paciente_id, g.primer_nombre||' '||g.primer_apellido as nombre,
                    h.razon_social, h.tipo_id_tercero as tipoid, h.id, p.usuario, p.nombre as nombreu,
                                        r.total_bonos, '" . $_SESSION['FACTURACION']['FECHAREPORTE'] . "' as fecha,
                                        b.sw_tipo,
                                        c.valor_total_paciente, c.valor_total_empresa,
                                        a.valor_cuota_moderadora, a.total_factura, a.valor_cargos, a.gravamen
                    from fac_facturas as a, fac_facturas_cuentas as b
                    left join rc_detalle_hosp as q on(q.numerodecuenta=b.numerodecuenta)
                    left join  recibos_caja as r on(q.prefijo=r.prefijo and q.recibo_caja=r.recibo_caja),
                    cuentas as c,
                    planes as d, terceros as e, ingresos as f, pacientes as g,
                    empresas as h, system_usuarios as p
                    where a.usuario_id=$usuario and a.estado=0
                    and a.prefijo=b.prefijo and a.factura_fiscal=b.factura_fiscal
                    and b.sw_tipo=1 and b.numerodecuenta=c.numerodecuenta and a.plan_id=d.plan_id
                    and a.tipo_id_tercero=e.tipo_id_tercero and a.tercero_id=e.tercero_id
                    and c.ingreso=f.ingreso and f.tipo_id_paciente=g.tipo_id_paciente
                    and f.paciente_id=g.paciente_id
                    and a.empresa_id=h.empresa_id
                    and a.usuario_id=p.usuario_id
                    and a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                    and date(a.fecha_registro)='" . $_SESSION['FACTURACION']['FECHAREPORTE'] . "'
                    order by e.nombre_tercero, a.plan_id, a.prefijo, a.factura_fiscal
                  )
                  union
                  ( select c.estado, d.tipo_tercero_id, d.tercero_id, c.empresa_id, NULL, NULL, c.total_cuenta,
                                        c.valor_cuota_paciente, c.plan_id,
                                        c.numerodecuenta,d.plan_descripcion, e.nombre_tercero, c.total_cuenta,
                                        NULL,f.tipo_id_paciente, f.paciente_id,
                                        g.primer_nombre||' '||g.primer_apellido as nombre,
                                        h.razon_social, h.tipo_id_tercero as tipoid, h.id, p.usuario,
                                        p.nombre, r.total_bonos, '" . $_SESSION['FACTURACION']['FECHAREPORTE'] . "' as fecha,
                                        NULL,c.valor_total_paciente, c.valor_total_empresa,
                                        c.valor_cuota_moderadora, NULL, c.valor_total_cargos,NULL
                                        from  cuentas as c left join rc_detalle_hosp as q on(q.numerodecuenta=c.numerodecuenta)
                                        left join recibos_caja as r on(q.prefijo=r.prefijo and q.recibo_caja=r.recibo_caja),
                                        planes as d, terceros as e, ingresos as f, pacientes as g, empresas as h, system_usuarios as p
                                        where c.usuario_cierre=$usuario and c.estado=3
                                        and c.plan_id=d.plan_id and d.tipo_tercero_id=e.tipo_id_tercero
                                        and d.tercero_id=e.tercero_id and c.ingreso=f.ingreso
                                        and f.tipo_id_paciente=g.tipo_id_paciente and f.paciente_id=g.paciente_id
                                        and d.empresa_id=h.empresa_id and d.usuario_id=p.usuario_id
                                        and d.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                                        and date(c.fecha_registro)='" . $_SESSION['FACTURACION']['FECHAREPORTE'] . "'
                                        order by e.nombre_tercero, c.plan_id
                                    )";
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

    //------------------------REPORTES------------------------------
    /**
     *
     */
    function DatosFactura($cuenta, $PlanId, $TipoId, $PacienteId, $prnAnuladas) {
        $sql = "";
        $tabla_cuenta = "cuentas";
        if ($prnAnuladas) {
            $tabla_cuenta = "cuentas_facturas_anuladas";
            $sql = "AND     c.prefijo = '" . $_SESSION['FACTURACION']['VAR']['prefijo'] . "' ";
            $sql .= "AND     c.factura_fiscal = " . $_SESSION['FACTURACION']['VAR']['factura'] . " ";
            $sql .= "AND     c.empresa_id = '" . $_SESSION['FACTURACION']['EMPRESA'] . "' ";
        }

        list($dbconn) = GetDBconn();
        $query = "SELECT  a.abonos,
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
                        --d.fecha_cierre,
                        y.fecha_egreso as fecha_cierre_movimientos_habitacion,
                        z.fecha_registro as fecha_cierre,
                        e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
                        e.residencia_telefono,
                        e.residencia_direccion,
                        a.prefijo,
                        a.factura_fiscal,
                        d.departamento_actual as dpto,
                        h.descripcion,
                        i.razon_social,
                        w.ubicacion as direccion,
                        w.telefono as telefonos,
                        w.descripcion as centro_utilidad,
                        i.tipo_id_tercero as tipoid,
                        i.id,
                        j.departamento,
                        k.municipio,
                        d.fecha_registro,
						d.fecha_ingreso,
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
                        a.valor_cuota_moderadora,
                        x.texto1,
                        x.texto2,
                        x.mensaje,
                        x.numero_digitos,
                        a.fechafac,
                        c.direccion AS direccion_tercero,
                        c.telefono AS telefono_tercero
                FROM    (
                          SELECT  a.empresa_id,
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
                                  c.valor_total_empresa,
                                  c.valor_cuota_moderadora,
                                  c.centro_utilidad
                          FROM    fac_facturas a,
                                  fac_facturas_cuentas b,
                                  " . $tabla_cuenta . " c
                          WHERE   a.empresa_id = '" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                          AND     a.prefijo = '" . $_SESSION['FACTURACION']['VAR']['prefijo'] . "'
                          AND     a.factura_fiscal = " . $_SESSION['FACTURACION']['VAR']['factura'] . "
                          AND     b.empresa_id = '" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                          AND     b.prefijo = '" . $_SESSION['FACTURACION']['VAR']['prefijo'] . "'
                          AND     b.factura_fiscal = " . $_SESSION['FACTURACION']['VAR']['factura'] . "
                          AND     b.numerodecuenta = " . $cuenta . "
                          AND     c.numerodecuenta = " . $cuenta . "
                          " . $sql . "
                        ) a,
                        planes as b,
                        terceros as c,
                        ingresos as d LEFT JOIN movimientos_habitacion y
                        ON (d.ingreso = y.ingreso)
                        LEFT JOIN ingresos_salidas z
                        ON (d.ingreso = z.ingreso),
                        pacientes as e,
                        departamentos as h,
                        empresas as i,
                        tipo_dptos as j,
                        tipo_mpios as k,
                        documentos as x,
                        centros_utilidad w
                WHERE   b.plan_id = a.plan_id
                AND     c.tipo_id_tercero = b.tipo_tercero_id
                AND     c.tercero_id = b.tercero_id
                AND     d.ingreso = a.ingreso
                AND     e.paciente_id = d.paciente_id
                AND     e.tipo_id_paciente = d.tipo_id_paciente
                AND     h.departamento = d.departamento_actual
                AND     i.empresa_id = a.empresa_id
                AND     j.tipo_pais_id = w.tipo_pais_id
                AND     j.tipo_dpto_id = w.tipo_dpto_id
                AND     k.tipo_pais_id = w.tipo_pais_id
                AND     k.tipo_dpto_id = w.tipo_dpto_id
                AND     k.tipo_mpio_id = w.tipo_mpio_id
                AND     x.documento_id = a.documento_id
                AND     x.empresa_id = a.empresa_id
                AND     w.empresa_id = a.empresa_id
                AND     w.centro_utilidad = a.centro_utilidad ";



        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $vars = $result->GetRowAssoc($ToUpper = false);

        $result->Close();
        return $vars;
    }

    function DatosResumenFactura($cuenta, $PlanId, $TipoId, $PacienteId) {//f.tipo_factura=g.tipo_factura and lo que se corto del query
        list($dbconn) = GetDBconn();
        $query = "select (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos) as abonos,
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
                         e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
                         e.residencia_telefono,
                         e.residencia_direccion,
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
						 d.fecha_ingreso,
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
                         a.valor_total_empresa
                  from   cuentas as a,
                         planes as b,
                         terceros as c,
                         pacientes as e,
                         departamentos as  h,
                         empresas as i,
                         tipo_dptos as j,
                         tipo_mpios as k,
                         ingresos as d
                  where  a.numerodecuenta=$cuenta
                  and    a.plan_id=" . $PlanId . "
                  and    a.plan_id=b.plan_id and b.tercero_id=c.tercero_id
                  and    b.tipo_tercero_id=c.tipo_id_tercero
                  and    d.ingreso=a.ingreso
                  and    d.tipo_id_paciente='" . $TipoId . "'
                  and    d.paciente_id='" . $PacienteId . "'
                  and    d.tipo_id_paciente=e.tipo_id_paciente
                  and    d.paciente_id=e.paciente_id
                  and    a.empresa_id=i.empresa_id and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
                  and    i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id
                  and    d.departamento_actual=h.departamento";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $vars = $result->GetRowAssoc($ToUpper = false);

        $result->Close();
        return $vars;
    }

//----------------------------------
    function LlamarVentanaFinal($boton=false) {
        $cont = 'app';
        $mod = 'Facturacion_Fiscal';
        $tipo = 'user';
        $metodo = 'Facturacion';
        $array = array('Cuenta' => $_REQUEST['numerodecuenta'],
            'TipoId' => $_REQUEST['tipoid'],
            'PacienteId' => $_REQUEST['pacienteid'],
            'PlanId' => $_REQUEST['plan_id'],
            'Nivel' => $_REQUEST['Nivel'],
            'Fecha' => $_REQUEST['Fecha'],
            'Ingreso' => $_REQUEST['Ingreso'],
            'Transaccion' => $_REQUEST['Transaccion'],
            'Estado' => $_REQUEST['Estado'],
            'tipo_factura' => $_REQUEST['tipo_factura'],
            'Dev' => $_REQUEST['Dev'],
            'vars' => $_REQUEST['vars'],
            'verhojas' => '1',
            'prnAnuladas' => $_REQUEST['prnAnuladas'],
            'prefijo' => $_REQUEST['prefijo'],
            'numero' => $_REQUEST['numero']
        );

        $accion = ModuloGetURL($cont, $mod, $tipo, $metodo, $array);

        if (!empty($_REQUEST['numerodecuenta']) AND $_REQUEST['tiporeporte'] == 'hojacargos') {
            $boton = $_REQUEST['tiporeporte'];
            $msg = 'HOJA CARGOS GENERADA SATISFACTORIAMENTE';
            $arreglo = array('cuenta' => $_REQUEST['numerodecuenta'], 'switche_emp' => $a);
        } elseif (!empty($_REQUEST['numerodecuenta']) AND $_REQUEST['tiporeporte'] == 'hojacargos2') {
            $boton = $_REQUEST['tiporeporte'];
            $msg = 'HOJA CARGOS2 GENERADA SATISFACTORIAMENTE';
            $arreglo = array('cuenta' => $_REQUEST['numerodecuenta'], 'switche_emp' => $a);
        } elseif (!empty($_REQUEST['numerodecuenta']) AND $_REQUEST['tiporeporte'] == 'recibo_caja') {
            $boton = $_REQUEST['tiporeporte'];
            $numero_recib_caja = explode("-", $_REQUEST['reportes_recibocaja']);
            //var_dump($numero_recib_caja); 
            $msg = 'RECIBO DE CAJA SATISFACTORIAMENTE';
            $arreglo = array('cuenta' => $_REQUEST['numerodecuenta'],
                'plan_id' => $_REQUEST['plan_id'],
                'tipoid' => $_REQUEST['tipoid'],
                'pacienteid' => $_REQUEST['pacienteid'],
                'ingreso' => $_REQUEST['ingreso'],
                'recibo_caja' => $numero_recib_caja[0],
                'prefijo' => $numero_recib_caja[3],
                'empresa' => $_REQUEST['empresa'],
                'centro_utilidad' => $numero_recib_caja[2],
                'caja_id' => $numero_recib_caja[1],
                'switche_emp' => $a);
        } elseif (!empty($_REQUEST['numerodecuenta']) AND $_REQUEST['tiporeporte'] == 'recibo_devolucion') {
            $boton = $_REQUEST['tiporeporte'];
            $numero_recib_dev = explode("-", $_REQUEST['reportes_recibodev']);
            //var_dump($numero_recib_caja); 
            $msg = 'RECIBO DE CAJA SATISFACTORIAMENTE DEVOLUCION';
            $arreglo = array('cuenta' => $_REQUEST['numerodecuenta'],
                'plan_id' => $_REQUEST['plan_id'],
                'tipoid' => $_REQUEST['tipoid'],
                'pacienteid' => $_REQUEST['pacienteid'],
                'ingreso' => $_REQUEST['ingreso'],
                'recibo_caja' => $numero_recib_dev[0],
                'prefijo' => $numero_recib_dev[3],
                'empresa' => $_REQUEST['empresa'],
                'centro_utilidad' => $numero_recib_dev[2],
                'caja_id' => $numero_recib_dev[1],
                'switche_emp' => $a);
        } elseif (!empty($_REQUEST['numerodecuenta']) AND $_REQUEST['tiporeporte'] == 'hojacargos3') {
            $boton = $_REQUEST['tiporeporte'];
            $msg = 'HOJA CARGOS3 GENERADA SATISFACTORIAMENTE';
            $arreglo = array('cuenta' => $_REQUEST['numerodecuenta'], 'switche_emp' => $a);
        } elseif (!empty($_REQUEST['numerodecuenta']) AND $_REQUEST['tiporeporte'] == 'hojacargos4') {
            $boton = $_REQUEST['tiporeporte'];
            $msg = 'HOJA CARGOS4 GENERADA SATISFACTORIAMENTE';
            $arreglo = array('cuenta' => $_REQUEST['numerodecuenta'], 'switche_emp' => $a);
        } elseif (!empty($_REQUEST['numerodecuenta']) AND ($_REQUEST['tiporeporte'] == 'factura' OR $_REQUEST['tiporeporte'] == 'facturapaciente')) {
            $boton = $_REQUEST['tiporeporte'];
            $msg = 'FACTURA GENERADA SATISFACTORIAMENTE';
            $arreglo = array('cuenta' => $_REQUEST['numerodecuenta'], 'plan_id' => $_REQUEST['plan_id'], 'tipoid' => $_REQUEST['tipoid'], 'pacienteid' => $_REQUEST['pacienteid'], 'prefijo' => $_REQUEST['prefijo'], 'numero' => $_REQUEST['numero'], 'switche_emp' => $a);
        } elseif (!empty($_REQUEST['numerodecuenta']) AND $_REQUEST['tiporeporte'] == 'resumen') {
            $boton = $_REQUEST['tiporeporte'];
            $msg = 'RESUMEN FACTURA GENERADA SATISFACTORIAMENTE';
            $arreglo = array('cuenta' => $_REQUEST['numerodecuenta'], 'plan_id' => $_REQUEST['plan_id'], 'tipoid' => $_REQUEST['tipoid'], 'pacienteid' => $_REQUEST['pacienteid'], 'switche_emp' => $a);
        } elseif (!empty($_REQUEST['numerodecuenta']) AND $_REQUEST['tiporeporte'] == 'conceptos') {
            $boton = $_REQUEST['tiporeporte'];
            if ($_REQUEST[prnAnuladas]) {
                $conceptos = '';
            } else {
                $conceptos = 'CONCEPTOS ';
            }
            $msg = 'FACTURA ' . $conceptos . 'GENERADA SATISFACTORIAMENTE';
            $arreglo = array('cuenta' => $_REQUEST['numerodecuenta'], 'plan_id' => $_REQUEST['plan_id'], 'tipoid' => $_REQUEST['tipoid'], 'pacienteid' => $_REQUEST['pacienteid'], 'switche_emp' => $a, 'prnAnuladas' => $_REQUEST[prnAnuladas]);
        } elseif (!empty($_REQUEST['numerodecuenta']) AND $_REQUEST['tiporeporte'] == 'reportes' AND !empty($_REQUEST['reporteshojacargos'])) {//REPORTES HOJA CARGOS, FACTURAS y  FACTURAS AGRUPADAS
            $dat = explode(',', $_REQUEST['reporteshojacargos']);
            if ($dat[1] == 'factura_agrupada') {
                $dat[1] = '';
                $cont = 'app';
                $mod = 'Facturacion_Fiscal';
                $tipo = 'user';
                $metodo = 'MostrarDetalle';
                $array = array('prefijo' => $_REQUEST['prefijo'],
                    'numero' => $_REQUEST['factura_fiscal'],
                    "prnAnuladas" => $_REQUEST['prnAnuladas']
                );
                $accion = ModuloGetURL($cont, $mod, $tipo, $metodo, $array);
            }
            $boton = $_REQUEST['tiporeporte'];
            $msg = $dat[1] . ' GENERADA SATISFACTORIAMENTE';
            $arreglo = array('cuenta' => $_REQUEST['numerodecuenta'], 'prefijo' => $_REQUEST['prefijo'], 'factura_fiscal' => $_REQUEST['factura_fiscal'], 'plan_id' => $_REQUEST['plan_id'], 'tipoid' => $_REQUEST['tipoid'], 'pacienteid' => $_REQUEST['pacienteid'], 'switche_emp' => $a, 'ruta_hoja' => $dat[0]);
        } else {
            $boton = 'cuentacobro';
            $msg = 'CUENTA DE COBRO GENERADA SATISFACTORIAMENTE';
            $arreglo = array('PlanId' => $_REQUEST['PlanId'], 'Fecha' => $_REQUEST['Fecha'], 'Ingreso' => $_REQUEST['Ingreso'],
                'numero' => $_REQUEST['numero'], 'prefijo' => $_REQUEST['prefijo'], 'empresa' => $_REQUEST['empresa'],
                'tipo_factura' => $_REQUEST['tipo_factura'], 'cuenta' => $_REQUEST['Cuenta']);
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'BuscarFacturas', $array);
        }
        $this->FormaMensaje($msg, 'CONFIRMACION', $accion, 'Volver', $boton, $arreglo);
        return true;
    }

    /**
     *
     */
    function LlamarFormaBuscarAgrupadas() {
        unset($_SESSION['FACTURACION']['arreglo']);
        unset($_SESSION['FACTURACION']['aseguradora']);
        unset($_SESSION['FACTURACION']['SELECCION']);
        unset($_SESSION['FACTURACION']['TIPOTERCERO']);
        unset($_SESSION['FACTURACION']['IDTERCERO']);

        $_SESSION['FACTURACION']['aseguradora'] = '';

        if (empty($_SESSION['FACTURACION']['SWCUENTAS'])) {
            $_SESSION['FACTURACION']['SWCUENTAS'] = $_REQUEST['SWCUENTAS'];
        }

        if (!empty($_REQUEST['terceros'])) {
            $aux = explode("*", $_REQUEST['terceros']);

            $sql = "SELECT COUNT(*) ";
            $sql .= "FROM   planes ";
            $sql .= "WHERE  tipo_tercero_id = '" . $aux[0] . "' ";
            $sql .= "AND    tercero_id = '" . $aux[1] . "' ";
            $sql .= "AND    estado = '1' ";
            $sql .= "AND    fecha_final >= NOW() ";
            $sql .= "AND    fecha_inicio <= NOW() ";
            $sql .= "AND    sw_tipo_plan = '1' ";

            list($dbconn) = GetDBconn();
            $results = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $results->Close();
            if ($results->fields[0] > 0)
                $_SESSION['FACTURACION']['aseguradora'] = 'Si';
        }

        if (!$this->FormaBuscarAgrupadas('', $_REQUEST['res']))
            return false;

        return true;
    }

    /**
     *
     */
    function BuscarFacturaAgrupada() {
        $EmpresaId = $_SESSION['FACTURACION']['EMPRESA'];

        if (!empty($_REQUEST['BuscarCuentas'])) {
            unset($_SESSION['FACTURACION']['SELECCION']);
        }

        if ($_REQUEST['terceros'] == -1) {
            $this->frmError["terceros"] = 1;
            $this->frmError["MensajeError"] = "DEBE SELECCIONAR EL TERCERO";
            $this->FormaBuscarAgrupadas($var);
            return true;
        }

        if ($_REQUEST['Plan'] == -1 || empty($_REQUEST['Plan'])) {
            $this->frmError["Plan"] = 1;
            $this->frmError["MensajeError"] = "DEBE ELEGIR EL PLAN.";
            $this->FormaBuscarAgrupadas($var);
            return true;
        }

        $filtroFecha = '';

        $NUM = ($_REQUEST['Of']) ? $_REQUEST['Of'] : "0";
        $limit = $this->limit;

        if (!empty($_REQUEST['FechaI'])) {
            if (eregi('/', $_REQUEST['FechaI'])) {
                $f = explode('/', $_REQUEST['FechaI']);
                $_REQUEST['FechaI'] = $f[2] . '-' . $f[1] . '-' . $f[0];
            }
            $y = $this->ValidarFecha($_REQUEST['FechaI']);
        }
        if (!empty($_REQUEST['FechaF'])) {
            if (eregi('/', $_REQUEST['FechaF'])) {
                $f = explode('/', $_REQUEST['FechaF']);
                $_REQUEST['FechaF'] = $f[2] . '-' . $f[1] . '-' . $f[0];
            }
            $z = $this->ValidarFecha($_REQUEST['FechaF']);
        }

        if (empty($y) AND !empty($_REQUEST['FechaI'])) {
            $this->frmError["FechaI"] = 1;
            $this->frmError["MensajeError"] = "1Formato de Fecha Incorrecto.";
            $this->FormaBuscarAgrupadas($var);
            return true;
        }

        if (empty($z) AND !empty($_REQUEST['FechaF'])) {
            $this->frmError["FechaF"] = 1;
            $this->frmError["MensajeError"] = "2Formato de Fecha Incorrecto.";
            $this->FormaBuscarAgrupadas($var);
            return true;
        }

        $filtro = "";
        if (is_array($_REQUEST['Plan'])) {
            if (empty($_REQUEST['rango'])) {
                $planes = "";
                foreach ($_REQUEST['Plan'] as $key => $dtl)
                    $planes .= (($planes == "") ? "" : ",") . $key;

                $filtro .= "AND    a.plan_id IN (" . $planes . ") ";
            } else {
                foreach ($_REQUEST['Plan'] as $k1 => $dtl) {
                    if ($_REQUEST['rango'][$k1] != '-1')
                        $filtro .= (($filtro == "") ? "" : "OR") . " (a.plan_id = " . $k1 . " AND a.rango = '" . $_REQUEST['rango'][$k1] . "' ) ";
                    else
                        $filtro .= (($filtro == "") ? "" : "OR") . " (a.plan_id = " . $k1 . ") ";
                }
                $filtro = "AND   (" . $filtro . ") ";
            }
        }
        else {
            $filtro = "AND    a.plan_id = " . $_REQUEST['Plan'] . " ";
        }

        $dpto = "";
        if ($_REQUEST['Departamento'] != -1 AND !empty($_REQUEST['Departamento']))
            $dpto = "AND  b.departamento = '" . $_REQUEST['Departamento'] . "' ";

        if (!empty($_REQUEST['FechaI']) AND !empty($_REQUEST['FechaF'])) {
            $filtroFecha .= "AND    a.fecha_cierre::date <= '" . $_REQUEST['FechaF'] . "'::date ";
            $filtroFecha .= "AND    a.fecha_cierre::date >= '" . $_REQUEST['FechaI'] . "'::date ";
        } elseif (!empty($_REQUEST['FechaF'])) {
            $filtroFecha .= "AND    a.fecha_cierre::date <= '" . $_REQUEST['FechaF'] . "'::date ";
        } elseif (!empty($_REQUEST['FechaI'])) {
            $filtroFecha .= "AND    a.fecha_cierre::date >= '" . $_REQUEST['FechaI'] . "'::date ";
        }

        if (!empty($_REQUEST['TipoDocumento']) AND !empty($_REQUEST['Documento'])) {
            $filtroTipoDoc .= "AND    b.tipo_id_paciente = '" . $_REQUEST['TipoDocumento'] . "' ";
            $filtroTipoDoc .= "AND    b.paciente_id = " . $_REQUEST['Documento'] . " ";
        }

        list($dbconn) = GetDBconn();

        if (empty($_REQUEST['Of'])) {
            $_REQUEST['Of'] = 0;
        }

        if (empty($_REQUEST['paso'])) {
            if ($_SESSION['FACTURACION']['aseguradora'] == 'Si') {
                $var = explode(',', $Aseguradora);
                $_SESSION['FACTURACION']['TIPOTERCERO'] = $var[0];
                $_SESSION['FACTURACION']['IDTERCERO'] = $var[1];

                $query = "SELECT * 
                    FROM    (
                              SELECT  a.*, 
                                      CASE WHEN a.estado=1 THEN 'A' 
                                           WHEN a.estado=2 THEN 'I' 
                                           ELSE 'C' END AS estado,
                                      c.tipo_id_paciente, 
                                      (a.valor_total_paciente - (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo,
                                      c.paciente_id, 
                                      c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre, a.rango, f.nombre_tercero
                              FROM    cuentas as a, 
                                      ingresos as b, 
                                      pacientes as c, 
                                      ingresos_soat as d, 
                                      terceros as f,
                                      soat_polizas as g, 
                                      soat_eventos as h 
                              WHERE   a.empresa_id='" . $EmpresaId . "'
                              " . $CU . "
                              and a.estado='3' 
                              and a.valor_total_empresa > 0
                              and a.ingreso=b.ingreso  
                              " . $dpto . " 
                              " . $filtro . " 
                              and b.tipo_id_paciente=c.tipo_id_paciente 
                              and b.paciente_id=c.paciente_id 
                              and b.ingreso=d.ingreso 
                              AND d.evento=h.evento 
                              AND g.poliza=h.poliza
                              AND g.tipo_id_tercero='" . $var[0] . "' 
                              AND g.tercero_id='" . $var[1] . "'
                              AND g.tipo_id_tercero=f.tipo_id_tercero 
                              AND g.tercero_id=f.tercero_id
                            ) a 
                    WHERE   a.saldo=0 
                    LIMIT " . $limit . " OFFSET " . $NUM . " ";
            } else {
                $query = "select * 
                    from    (
                              select  a.*, 
                                      (a.valor_total_paciente - (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo,
                                      case when a.estado=1 then 'A' 
                                           when a.estado=2 then 'I' 
                                           else 'C' end as estado, 
                                      c.tipo_id_paciente,
                                      c.paciente_id,  
                                      c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre,
                                      a.rango
                              FROM    cuentas as a, 
                                      ingresos as b, 
                                      pacientes as c
                              WHERE   a.empresa_id='" . $EmpresaId . "'
                              " . $CU . "  
                              and     a.estado='3'  
                              and     a.valor_total_empresa > 0
                              and     a.ingreso=b.ingreso  
                              " . $dpto . " 
                              " . $filtro . " 
                              " . $filtroFecha . " 
                              " . $filtroTipoDoc . " 
                              and     b.tipo_id_paciente=c.tipo_id_paciente 
                              and     b.paciente_id=c.paciente_id
                              ORDER BY a.numerodecuenta ASC
                            ) a ";
            }
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al buscar";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError . "[" . get_class($this) . "][" . __LINE__ . "]";
                return false;
            }
            if (!$result->EOF) {
                $_SESSION['SPY'] = $result->RecordCount();
            }
            $result->Close();
        }

        if ($_SESSION['FACTURACION']['aseguradora'] == 'Si') {
            $var = explode(',', $Aseguradora);
            $query = "SELECT * 
                  FROM    (
                            SELECT  a.*, 
                                    CASE WHEN a.estado=1 THEN 'A' 
                                         WHEN a.estado=2 THEN 'I' 
                                         ELSE 'C' END AS estado,
                                    c.tipo_id_paciente, (a.valor_total_paciente - (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo,
                                    c.paciente_id, 
                                    c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre, 
                                    a.rango, 
                                    f.nombre_tercero
                            FROM    cuentas a, 
                                    ingresos b, 
                                    pacientes c, 
                                    ingresos_soat d, 
                                    terceros f,
                                    soat_polizas g, 
                                    soat_eventos h 
                            WHERE   a.empresa_id = '" . $EmpresaId . "'
                            " . $CU . "  
                            AND     a.estado='3' 
                            AND     a.valor_total_empresa > 0
                            AND     a.ingreso=b.ingreso  
                            " . $dpto . " 
                            " . $filtro . " 
                            AND     b.tipo_id_paciente=c.tipo_id_paciente 
                            AND     b.paciente_id=c.paciente_id 
                            AND     b.ingreso=d.ingreso 
                            AND     d.evento=h.evento 
                            AND     g.poliza=h.poliza
                            AND     g.tipo_id_tercero='" . $var[0] . "' 
                            AND     g.tercero_id='" . $var[1] . "'
                            AND     g.tipo_id_tercero=f.tipo_id_tercero 
                            AND     g.tercero_id=f.tercero_id
                          ) A
                  WHERE   a.saldo=0 
                  LIMIT " . $limit . " OFFSET " . $NUM . " ";
        } else {
            $query = "SELECT  * 
                  FROM    (
                            SELECT  a.*, 
                                    (a.valor_total_paciente - (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_letras)) as saldo,
                                    CASE WHEN a.estado=1 THEN 'A' 
                                         WHEN a.estado=2 THEN 'I' 
                                         ELSE 'C' END AS estado, 
                                    c.tipo_id_paciente,
                                    c.paciente_id,  c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre,
                                    a.rango
                            FROM    cuentas a, 
                                    ingresos b, 
                                    pacientes c 
                            WHERE   a.empresa_id='" . $EmpresaId . "'
                            " . $CU . "  
                            AND     a.estado='3'  
                            AND     a.valor_total_empresa > 0
                            AND     a.ingreso=b.ingreso  
                            " . $dpto . " 
                            " . $filtro . " 
                            " . $filtroFecha . " 
                            " . $filtroTipoDoc . " 
                            AND     b.tipo_id_paciente=c.tipo_id_paciente 
                            AND     b.paciente_id=c.paciente_id
                            ORDER BY a.numerodecuenta ASC
                          ) A 
                  LIMIT " . $this->limit . " OFFSET " . $_REQUEST['Of'] . " ";
        }

        if (!empty($query)) {
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError . "[" . get_class($this) . "][" . __LINE__ . "]";
                return false;
            }

            while (!$result->EOF) {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $result->Close();
        }

        $this->FormaBuscarAgrupadas($vars);
        return true;
    }

    /**
     * La funcion BuscarNombresPaciente se encarga de buscar en la base de datos los nombres de los pacientes.
     * @access public
     * @return array
     * @param string tipo de documento
     * @param int numero de documento
     */
    function BuscarNombresPaciente($tipo, $documento) {
        list($dbconn) = GetDBconn();
        $query = "SELECT primer_nombre,segundo_nombre FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            if ($result->EOF) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
                return false;
            }
        }
        $Nombres = $result->fields[0] . " " . $result->fields[1];
        $result->Close();
        return $Nombres;
    }

    /**
     * Se encarga de buscar en la base de datos los apellidos de los pacientes.
     * @access public
     * @return array
     * @param string tipo de documento
     * @param int numero de documento
     */
    function BuscarApellidosPaciente($tipo, $documento) {
        list($dbconn) = GetDBconn();
        $query = "SELECT primer_apellido,segundo_apellido FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            if ($result->EOF) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'paciente' esta vacia ";
                return false;
            }
        }
        $result->Close();
        $Apellidos = $result->fields[0] . " " . $result->fields[1];
        return $Apellidos;
    }

//-------------------------------LA FORMA CON CODIGO AGRUPAMIENTO--------------------

    /**
     *
     */
    function DefinirForma() {

        $TipoId = $_REQUEST['TipoId'];
        $PacienteId = $_REQUEST['PacienteId'];
        $Nivel = $_REQUEST['Nivel'];
        $PlanId = $_REQUEST['PlanId'];
        $Pieza = $_REQUEST['Pieza'];
        $Cama = $_REQUEST['Cama'];
        $Fecha = $_REQUEST['Fecha'];
        $Ingreso = $_REQUEST['Ingreso'];
        $Cuenta = $_REQUEST['Cuenta'];
        if ($_REQUEST['filtro'] == 1) {
            $filtro = " AND a.facturado='0'";
        } elseif ($_REQUEST['filtro'] == 2) {
            $filtro = " AND a.facturado='1'";
        }
        list($dbconn) = GetDBconn();
        //no es nada de medicamentos ni cirugia
        if (empty($_REQUEST['doc']) AND empty($_REQUEST['numeracion']) AND empty($_REQUEST['qx'])) {
            $query = "select a.*, b.descripcion
                                        from cuentas_detalle as a, tarifarios_detalle as b
                                        where a.codigo_agrupamiento_id='" . $_REQUEST['codigo'] . "'
                                        and a.numerodecuenta=$Cuenta
                                        and a.cargo=b.cargo and a.tarifario_id=b.tarifario_id
                                        $filtro";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            while (!$result->EOF) {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $result->Close();

            $this->FormaDetalleCodigo($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Cama, $Fecha, $Ingreso, $vars, $_REQUEST['des'], $_REQUEST['codigo'], $_REQUEST['doc'], $_REQUEST['numeracion']);
            return true;
        }//es una cirugia
        elseif (!empty($_REQUEST['qx'])) {
            $query = "SELECT a.*, b.descripcion, c.tercero_id, c.tipo_tercero_id,
                                        d.valor, d.porcentaje_honorario, d.tipo_tercero_id as tipohono, d.tercero_id as idhono,
                                        x.cuenta_liquidacion_qx_id
                                        FROM cuentas_detalle as a LEFT JOIN cuentas_detalle_profesionales as c ON(a.transaccion=c.transaccion)
                                        LEFT JOIN cuentas_detalle_honorarios as d ON(a.transaccion=d.transaccion),
                                        tarifarios_detalle as b, cuentas_codigos_agrupamiento x
                                        WHERE a.codigo_agrupamiento_id='" . $_REQUEST['codigo'] . "'
                                        and a.numerodecuenta=$Cuenta and a.consecutivo ISNULL
                                        and a.cargo=b.cargo and a.tarifario_id=b.tarifario_id
                                        AND a.codigo_agrupamiento_id=x.codigo_agrupamiento_id
                                        AND x.descripcion='ACTO QUIRURGICO'
                                        AND x.cuenta_liquidacion_qx_id IS NOT NULL";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            while (!$result->EOF) {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $result->Close();

            //busca si la cirugia tiene medicamentos
            //if(!empty($_REQUEST['doc']) AND !empty($_REQUEST['numeracion']))
            //{
            $query = "select a.*, e.descripcion, c.codigo_producto
                                            from cuentas_detalle as a,cuentas_codigos_agrupamiento x,
                                            bodegas_documentos_d as c, inventarios_productos as e
                                            where a.numerodecuenta=$Cuenta
                                            and a.consecutivo=c.consecutivo
                                            and c.codigo_producto=e.codigo_producto
                                            AND a.codigo_agrupamiento_id=x.codigo_agrupamiento_id
                                            AND x.cuenta_liquidacion_qx_id='" . $_REQUEST['qx'] . "'
                                            $filtro";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            while (!$result->EOF) {
                $med[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $result->Close();
            //}//fin buscar medicamentos cirugia

            $this->FormaDetalleCirugia($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Cama, $Fecha, $Ingreso, $vars, $med, $_REQUEST['des'], $_REQUEST['codigo'], $_REQUEST['qx']);
            return true;
        }//es medicamentos
        else {
            $query = "select a.*, e.descripcion, c.codigo_producto
                                    from cuentas_detalle as a,
                                    bodegas_documentos_d as c, inventarios_productos as e
                                    where a.codigo_agrupamiento_id='" . $_REQUEST['codigo'] . "'
                                    and a.numerodecuenta=$Cuenta
                                    and a.consecutivo=c.consecutivo
                                    and c.codigo_producto=e.codigo_producto
                                    $filtro";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            while (!$result->EOF) {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $result->Close();

            $this->FormaDetalleCodigo($Cuenta, $TipoId, $PacienteId, $Nivel, $PlanId, $Cama, $Fecha, $Ingreso, $vars, $_REQUEST['des'], $_REQUEST['codigo'], $_REQUEST['doc'], $_REQUEST['numeracion']);
            return true;
        }
    }

    /**
     * Busca el detalle de una cuenta en la tabla cuentas_detalle.
     * @access public
     * @return array
     * @param int numero de Cuenta
     */
    function CargosNoFacturados() {
        $arre = $this->DetalleCuentaNoFacturados($_REQUEST['Cuenta']);
        if (!$this->FormaFacturas($_REQUEST['Cuenta'], $_REQUEST['TipoId'], $_REQUEST['PacienteId'], $_REQUEST['PlanId'], $_REQUEST['Nivel'], $_REQUEST['Fecha'], $_REQUEST['Ingreso'], $_REQUEST['Transaccion'], $Dev, $vars, $Estado, $arre)) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    function DetalleCuentaNoFacturados($Cuenta) {
        list($dbconn) = GetDBconn();
        $query = "SELECT  transaccion,
                        cargo,
                        cantidad,
                        precio,
                        valor_nocubierto,
                        fecha_registro,
                        tarifario_id,
                        valor_cubierto,
                        valor_cargo,
                        porcentaje_descuento_paciente,
                        porcentaje_descuento_empresa,
                        valor_descuento_empresa,
                        valor_descuento_paciente,
                        case facturado when 1 then valor_cargo else 0 end as fac,
                        autorizacion_int as interna,
                        autorizacion_ext as externa,
                                                codigo_agrupamiento_id
                FROM cuentas_detalle WHERE numerodecuenta='$Cuenta' and facturado=0
                                order by codigo_agrupamiento_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $arre[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $arre;
    }

//---------------------GENERAR RIPS-------------------------------------

    /**
     *
     */
    function RipsFacturas() {
        $_SESSION['RIPS']['FACTURAS'] = $_REQUEST['Plan'];
        $this->FormaBuscarFacturasRips();
        return true;
    }

    /**
     *
     */
    function RipsEnvios() {
        $_SESSION['RIPS']['ENVIOS'] = $_REQUEST['Plan'];
        $this->FormaBuscarEnviosRips();
        return true;
    }

    /**
     *
     */
    function BuscarFacturasRips() {
        $filtroFecha = '';
        $filtroPlan = '';

        $x = explode(',', $_SESSION['RIPS']['FACTURAS']);

        if (!empty($_REQUEST['FechaI'])) {
            $f = explode('/', $_REQUEST['FechaI']);
            $_REQUEST['FechaI'] = $f[2] . '-' . $f[1] . '-' . $f[0];
        }
        if (!empty($_REQUEST['FechaF'])) {
            $f = explode('/', $_REQUEST['FechaF']);
            $_REQUEST['FechaF'] = $f[2] . '-' . $f[1] . '-' . $f[0];
        }


        if (!empty($_REQUEST['FechaI'])) {
            $y = $this->ValidarFecha($_REQUEST['FechaI']);
            if (empty($y)) {
                $this->frmError["FechaI"] = 1;
                $this->frmError["MensajeError"] = "Formato de Fecha Incorrecto.";
                $this->FormaBuscarFacturasRips($var);
                return true;
            }
        }

        if (!empty($_REQUEST['FechaF'])) {
            $z = $this->ValidarFecha($_REQUEST['FechaF']);
            if (empty($z)) {
                $this->frmError["FechaF"] = 1;
                $this->frmError["MensajeError"] = "Formato de Fecha Incorrecto.";
                $this->FormaBuscarFacturasRips($var);
                return true;
            }
        }

        $d = 0;
        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'plan')) {
                $d++;
            }
        }
        if ($d > 1) {
            $j = 0;
            $filtroPlan = "AND (";
            foreach ($_REQUEST as $k => $v) {
                if (substr_count($k, 'plan')) {
                    $filtroPlan .= " a.plan_id = $v";
                    $j++;
                    if ($j < $d) {
                        $filtroPlan .= " OR ";
                    }
                }
            }
            $filtroPlan .= ")";
        } elseif ($d == 1) {
            foreach ($_REQUEST as $k => $v) {
                if (substr_count($k, 'plan')) {
                    $filtroPlan = " AND a.plan_id = $v";
                }
            }
        }

        if (!empty($_REQUEST['FechaI']) AND !empty($_REQUEST['FechaF'])) {
            $filtroFecha = "and date(a.fecha_registro) <= date('" . $_REQUEST['FechaF'] . "') and date(a.fecha_registro) >= date('" . $_REQUEST['FechaI'] . "')";
        } elseif (!empty($_REQUEST['FechaF'])) {
            $filtroFecha = " and date(a.fecha_registro) <= date('" . $_REQUEST['FechaF'] . "')";
        } elseif (!empty($_REQUEST['FechaI'])) {
            $filtroFecha = " and date(a.fecha_registro) >= date('" . $_REQUEST['FechaI'] . "')";
        }

        if (!empty($_REQUEST['Numero'])) {
            $filtroFactura = " and a.fatcura_fiscal=" . $_REQUEST['Numero'] . "";
        }

        if (!empty($_REQUEST['Prefijo'])) {
            $filtroPrefijo = " and a.prefijo=" . $_REQUEST['Prefijo'] . "";
        }

        list($dbconn) = GetDBconn();
        $query = "select a.*, c.plan_descripcion, b.numerodecuenta
                                from fac_facturas as a, fac_facturas_cuentas as b, planes as c
                                where a.tipo_id_tercero='$x[0]' and a.tercero_id='$x[1]'
                                and a.estado=0 and a.prefijo=b.prefijo and a.factura_fiscal=b.factura_fiscal
                                and sw_tipo=1
                                and c.plan_id=a.plan_id
                                and a.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                                $filtroFecha
                                $filtroFactura
                                $filtroPlan
                                $filtroPrefijo
                                order by a.prefijo, a.factura_fiscal";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        } else {
            $this->frmError["MensajeError"] = "La Busqueda no Arrojo Resultados.";
            $var = 'si';
        }

        $this->FormaBuscarFacturasRips($var);
        return true;
    }

    /**
     *
     */
    function BuscarEnviosRips() {
        $filtroFecha = '';
        $filtroPlan = '';

        $x = explode(',', $_SESSION['RIPS']['ENVIOS']);

        if (!empty($_REQUEST['FechaI'])) {
            $f = explode('/', $_REQUEST['FechaI']);
            $_REQUEST['FechaI'] = $f[2] . '-' . $f[1] . '-' . $f[0];
        }
        if (!empty($_REQUEST['FechaF'])) {
            $f = explode('/', $_REQUEST['FechaF']);
            $_REQUEST['FechaF'] = $f[2] . '-' . $f[1] . '-' . $f[0];
        }


        if (!empty($_REQUEST['FechaI'])) {
            $y = $this->ValidarFecha($_REQUEST['FechaI']);
            if (empty($y)) {
                $this->frmError["FechaI"] = 1;
                $this->frmError["MensajeError"] = "Formato de Fecha Incorrecto.";
                $this->FormaBuscarEnviosRips($var);
                return true;
            }
        }

        if (!empty($_REQUEST['FechaF'])) {
            $z = $this->ValidarFecha($_REQUEST['FechaF']);
            if (empty($z)) {
                $this->frmError["FechaF"] = 1;
                $this->frmError["MensajeError"] = "Formato de Fecha Incorrecto.";
                $this->FormaBuscarEnviosRips($var);
                return true;
            }
        }

        $d = 0;
        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'plan')) {
                $d++;
            }
        }

        $planeS = $rangoS = "";
        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'plan')) {
                ($planeS == "") ? $planeS = $v : $planeS .= "," . $v;
                if ($_REQUEST['rango_' . $v] != '-1')
                    ($rangoS == "") ? $rangoS = "'" . $_REQUEST['rango_' . $v] . "'" : $rangoS .= ",'" . $_REQUEST['rango_' . $v] . "'";
            }
        }
        if ($planeS != "")
            $filtroPlan = " AND b.plan_id IN (" . $planeS . ") ";
        if ($rangoS != "")
            $filtroRangos = " AND d.rango IN (" . $rangoS . ") ";

        if (!empty($_REQUEST['FechaI']) AND !empty($_REQUEST['FechaF'])) {
            $filtroFecha = "and date(a.fecha_registro) <= date('" . $_REQUEST['FechaF'] . "') and date(a.fecha_registro) >= date('" . $_REQUEST['FechaI'] . "')";
        } elseif (!empty($_REQUEST['FechaF'])) {
            $filtroFecha = " and date(a.fecha_registro) <= date('" . $_REQUEST['FechaF'] . "')";
        } elseif (!empty($_REQUEST['FechaI'])) {
            $filtroFecha = " and date(a.fecha_registro) >= date('" . $_REQUEST['FechaI'] . "')";
        }

        if (!empty($_REQUEST['Envio'])) {
            $filtroEnvio = " and b.envio_id=" . $_REQUEST['Envio'] . "";
        }

        list($dbconn) = GetDBconn();

        $query = "select  distinct a.*,
                        e.nombre_tercero, 
                        f.nombre, 
                        d.empresa_id
                from    envios as a, 
                        envios_detalle as d,
                        fac_facturas g,
                        envios_planes as b,
                        planes as c, 
                        terceros as e,
                        system_usuarios as f
                where   a.envio_id=b.envio_id 
                and     b.plan_id=c.plan_id
                and     sw_estado<>2
                and     d.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                $filtroResponsable
                $filtroFecha
                $filtroEnvio
                $filtroFactura
                $filtroPlan
                and     c.tipo_tercero_id='$x[0]'
                and     c.tercero_id='$x[1]'
                and     c.tercero_id=e.tercero_id
                and     c.tipo_tercero_id=e.tipo_id_tercero
                and     a.usuario_id=f.usuario_id
                AND     g.prefijo = d.prefijo 
                AND     g.factura_fiscal = d.factura_fiscal
                AND     g.empresa_id = d.empresa_id
                order by a.envio_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        } else {
            $this->frmError["MensajeError"] = "La Busqueda no Arrojo Resultados.";
            $var = 'si';
        }

        $this->FormaBuscarEnviosRips($var, $rangoS);
        return true;
    }

    /**
     *
     */
    function GenerarRips($ripsSoat) {
        if ($ripsSoat)
            $_REQUEST[ripsSoat] = $ripsSoat;

        IncludeLib("rips");
        IncludeLib("rips_bd");
        $dat = $this->DatosEncabezadoEmpresa();
        //datos prestador de servicios
        $razon = $dat[razon_social];
        $id = $dat[tipo_id_tercero];
        $id_iden = $dat[id];
        $tipo = $dat[tercero_id];
        $sgsss = $dat[codigo_sgsss];
        //echo $razon.'<br>'.$id.'<br>'.$tipo.'<br>'.$sgsss;exit;
        $rangos = str_replace("\\", "", $_REQUEST['rangos']);
        if ($_REQUEST['tiporips'] == 'Envio') {
            $f = 0;
            $arregloenvio = '';
            if (empty($_REQUEST['EnvioRips'])) {
                $this->frmError["MensajeError"] = "ERROR DATOS VACIOS: Debe elegir algun Envio.";
                $this->FormaBuscarEnviosRips();
                return true;
            }
            $a = explode('/', $_REQUEST['EnvioRips']);
            $arregloenvio[] = array('envio' => $a[0], 'empresa' => $a[1], 'rangos' => $rangos);

            if (!empty($arregloenvio)) {
                global $VISTA;
                if ($_REQUEST[ripsSoat] === 'RipsEnviosSoat') {
                    include "classes/RipsSoat/RipsSoat.class.php";
                    include "classes/RipsSoat/RipsSoat_ArchivoSobreVehiculos.class.php";
                    include "classes/RipsSoat/RipsSoat_ArchivoAccidentesEventosCatastroficosTerrorista.class.php";
                    include "classes/RipsSoat/RipsSoat_ArchivoControlAccidentesEventosCatastroficosTerrorista.class.php";
                    include "classes/RipsSoat/RipsSoat_ArchivoSobreAtencionVictima.class.php";

                    $rutaRips = GetVarConfigAplication("DirGeneracionRips") . "/ENVIOSOAT" . str_pad($a[0], 6, "0", STR_PAD_LEFT);
                    $a1 = new RipsSoat_ArchivoControlAccidentesEventosCatastroficosTerrorista($a[0], $rutaRips);

                    $b = $a1->ejecutarClasesRips();
                    if (!$b) {
                        $mensaje = "ERROR: " . $a1->mensajeDeError;
                        echo $a1->mensajeDeError;
                    } else {
                        $mensaje = 'SE GENERARON LOS RIPS SOAT DEL ENVIO No. ' . $a[0];
                    }
                    //
                    if ($_REQUEST['download'])
                        return $rutaRips;
                    $Metodo = "FormaBuscarEnviosRips";
                    $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', $Metodo);
                    $download = download($rutaRips, $nombre = "DESCARGAR RIPS SOAT", $link = false, $comprimir = true, $boton = true);
                    $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', $Metodo);
                    $this->FormaRipsGenerados($mensaje, $accion, $download);
                    return true;
                    //echo 'si existe->'.$a[0];exit;
                }
                elseif ($_REQUEST[furips]) {
                    include "classes/FuRips/FuRips.class.php";
                    include "classes/FuRips/FuRips_Eventos.class.php";
                    include "classes/FuRips/FuRips_CuentaCobro.class.php";
                    $rutaRips = GetVarConfigAplication("DirGeneracionRips") . "/FURIPS" . $sgsss . date('dmY');

                    $a1 = new FuRips_Eventos($a[0], $rutaRips, $sgsss);
                    $b = $a1->ejecutarClasesRips();
                    if (!$b) {
                        $mensaje = "ERROR: " . $a1->mensajeDeError;
                        echo $a1->mensajeDeError;
                    } else {
                        $mensaje = 'SE GENERARON LOS RIPS SOAT DEL ENVIO No. ' . $a[0];
                    }

                    $classe = new FuRips_CuentaCobro($a[0], $rutaRips, $sgsss);
                    $c = $classe->ejecutarClasesRipsCuentaCobro();

                    if (!$c) {
                        $mensaje = "ERROR: " . $b->mensajeDeError;
                        echo $b->mensajeDeError;
                    }
                    //
                    if ($_REQUEST['download'])
                        return $rutaRips;
                    $Metodo = "FormaBuscarEnviosRips";
                    $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', $Metodo);
                    $download = download($rutaRips, $nombre = "DESCARGAR FuRips", $link = false, $comprimir = true, $boton = true);
                    $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', $Metodo);
                    $this->FormaRipsGenerados($mensaje, $accion, $download);
                    return true;
                }
                else {
                    //se incluye la nueva clase para la descarga de los rips
                    if (!IncludeFile("classes/rips_bd/rips_bd.class.php")) {
                        $this->error = "No se pudo cargar el Rips";
                        $this->mensajeDeError = "El archivo de vistas 'classes/rips/rips.class.php' no existe.";
                        return false;
                    }//se incluye la clase del buscador.....
                    //se referencia la nueva clase para la descarga de los rips
                    $fileName = "classes/rips/rips.class.php";
                    if (!IncludeFile($fileName)) {
                        $this->error = "No se pudo cargar el Modulo";
                        $this->mensajeDeError = "El archivo de vistas '" . $fileName . "' no existe.";
                        return false;
                    }
                    $clase = "rips_bd";
                    $rips = new $clase();
                    $f = explode(',', $_SESSION['RIPS']['ENVIOS']);
                    // if(!$rips->Envios($f[0],$f[1],$arregloenvio))
                    //se cambia el llamado para la nueva clase que genera los rips (rips_bd)
                    if (!$rips->generaArchivosRips($arregloenvio[0][envio])) {
                        $mensaje = "ERROR: " . $rips->MensajeDeError() . '<br>' . $rips->GetError();
                    } else {
                        $mensaje = 'SE GENERARON LOS RIPS DEL ENVIO No. ' . $a[0];
                    }
                    $rangos = str_replace("'", "", $rangos);
                    $rangos = str_replace(" ", "", $rangos);
                    $rutaRips = GetVarConfigAplication("DirGeneracionRips") . "/ENVIO" . str_pad($a[0], 6, "0", STR_PAD_LEFT) . (($rangos) ? "_" . $rangos : "");
                    if ($_REQUEST['download'])
                        return $rutaRips;
                    $Metodo = "FormaBuscarEnviosRips";
                    $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', $Metodo);
                    $download = download($rutaRips, $nombre = "DESCARGAR RIPS", $link = false, $comprimir = true, $boton = true);
                    $this->FormaRipsGenerados($mensaje, $accion, $download);
                    return true;
                    /*
                      //SI NO ES RIPS SOAT
                      if(!IncludeFile("classes/rips/rips.class.php"))
                      {
                      $this->error = "No se pudo cargar el Rips";
                      $this->mensajeDeError = "El archivo de vistas 'classes/rips/rips.class.php' no existe.";
                      return false;
                      }//se incluye la clase del buscador.....

                      $fileName =  "classes/rips/rips.class.php";
                      if(!IncludeFile($fileName))
                      {
                      $this->error = "No se pudo cargar el Modulo";
                      $this->mensajeDeError = "El archivo de vistas '" . $fileName . "' no existe.";
                      return false;
                      }
                      $clase="rips";
                      $rips = new $clase();
                      $f = explode(',',$_SESSION['RIPS']['ENVIOS']);
                      if(!$rips->Envios($f[0],$f[1],$arregloenvio))
                      {       $mensaje="ERROR: ".$rips->MensajeDeError().'<br>'.$rips->GetError();        }
                      else
                      {   $mensaje='SE GENERARON LOS RIPS DEL ENVIO No. '.$a[0];      }
                      $rangos = str_replace("'","",$rangos);
                      $rangos = str_replace(" ","",$rangos);
                      $rutaRips =  GetVarConfigAplication("DirGeneracionRips")."/ENVIO".str_pad($a[0],6,"0",STR_PAD_LEFT).(($rangos)? "_".$rangos:"");
                      if($_REQUEST['download'])
                      return $rutaRips;
                      $Metodo = "FormaBuscarEnviosRips";
                      $accion=ModuloGetURL('app','Facturacion_Fiscal','user',$Metodo);
                      $download = download($rutaRips,$nombre="DESCARGAR RIPS",$link=false,$comprimir=true,$boton=true);
                      $this->FormaRipsGenerados($mensaje,$accion,$download);
                      return true; */
                }//else de RipsEnviosSoat
            }
        }
        if ($_REQUEST['tiporips'] == 'Factura') {
            $f = 0;
            $arreglofactura = '';
            foreach ($_REQUEST as $k => $v) {
                if (substr_count($k, 'FacturaRips')) {
                    //0 prefijo 1 factura_fiscal 2  plan_id 3 empresa_id
                    $a = explode('/', $v);
                    $arreglofactura[] = array('prefijo' => $a[0], 'factura' => $a[1], 'plan' => $a[2], 'empresa' => $a[3]);
                    $f++;
                }
            }
            if ($f == 0) {
                $this->frmError["MensajeError"] = "ERROR DATOS VACIOS: Debe elegir alguna Factura.";
                $this->FormaBuscarFacturasRips();
                return true;
            }
            if (!empty($arreglofactura)) {
                
            }
        }
    }

    /**
     *
     */
    function DatosRipsEnvios($envio) {
        list($dbconn) = GetDBconn();
        $query = "select a.envio_id, a.fecha_inicial, a.fecha_final, b.prefijo, b.factura_fiscal,
                                c.fecha_registro, c.total_factura, c.valor_cuota_paciente, c.valor_cuota_moderadora,
                                c.descuento, c.tipo_id_tercero, c.tercero_id, e.codigo_sgsss, g.plan_descripcion,
                                g.num_contrato, k.poliza
                                from envios as a, envios_detalle as b, fac_facturas as c
                                left join terceros_sgsss as e on(c.tipo_id_tercero=e.tipo_id_tercero and c.tercero_id=e.tercero_id),
                                planes as g, fac_facturas_cuentas as h,
                                cuentas as i left join ingresos_soat as j on (i.ingreso=j.ingreso)
                                left join soat_eventos as k on(j.evento=k.evento)
                                where a.envio_id=$envio and a.sw_estado=1 and a.envio_id=b.envio_id
                                and b.prefijo=c.prefijo and b.factura_fiscal=c.factura_fiscal
                                and c.plan_id=g.plan_id and h.numerodecuenta=i.numerodecuenta
                                and h.prefijo=c.prefijo and h.factura_fiscal=c.factura_fiscal";
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
     *
     */
    function BuscarSgsss($tipo, $id) {
        list($dbconn) = GetDBconn();
        $query = "select b.codigo_sgsss
                                from terceros_sgsss as b
                                where b.tipo_id_tercero='$tipo' and b.tercero_id='$id'";
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

//-------------------------------DESPACHO--------------------------------------

    /**
     *
     */
    function DespacharEnvio() {
        if (empty($_REQUEST['guia'])) {
            $_REQUEST['guia'] = 'NULL';
        }
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query = "SELECT count(*)
                                            FROM envios_despacho
                                            WHERE envio_id=" . $_REQUEST['envio'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al consultar en envios_despacho";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        if ($result->fields[0] > 0) {
            $query = "UPDATE envios_despacho
                                                SET guia='" . $_REQUEST['guia'] . "',empresa_mensajeria='" . $_REQUEST['mensajeria'] . "',
                                                        responsable='" . $_REQUEST['responsable'] . "',observacion='" . $_REQUEST['observacion'] . "',
                                                        usuario_id=" . UserGetUID() . ",fecha_registro='now()',sw_estado=0
                                                WHERE envio_id=" . $_REQUEST['envio'] . "";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en envios";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        } else {
            $query = "insert into envios_despacho(
                                                                                                                            envio_id,
                                                                                                                            guia,
                                                                                                                            empresa_mensajeria,
                                                                                                                            responsable,
                                                                                                                            observacion,
                                                                                                                            usuario_id,
                                                                                                                            fecha_registro,
                                                                                                                            sw_estado)
                                                                    values(" . $_REQUEST['envio'] . ",'" . $_REQUEST['guia'] . "','" . $_REQUEST['mensajeria'] . "','" . $_REQUEST['responsable'] . "','" . $_REQUEST['observaciones'] . "'," . UserGetUID() . ",'now()',0)";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en envios";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }
        $query = "UPDATE envios SET sw_estado=3
                                                                WHERE envio_id=" . $_REQUEST['envio'] . "";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $dbconn->CommitTrans();

        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'DetalleEnvio', array('envio' => $_REQUEST['envio']));
        $mensaje = 'Se Despacho el Envio No. ' . $_REQUEST['envio'];
        $this->FormaMensaje($mensaje, 'ENVIO No. ' . $_REQUEST['envio'], $accion, 'ACEPTAR');
        return true;
    }

    /**
     *
     */
    function DatosDespacho($envio) {
        list($dbconn) = GetDBconn();
        $query = "select a.*, b.nombre from envios_despacho as a, system_usuarios as b
                                where a.envio_id=$envio and a.usuario_id=b.usuario_id";
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
    function BuscarTotalPaciente($cuenta) {
        list($dbconn) = GetDBconn();
        $query = "select valor_total_paciente from cuentas
                                where numerodecuenta=$cuenta";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $result->Close();
        return $result->fields[0];
    }

    /**
     *
     */
    function InsertarPendientesCargar() {
        IncludeLib('funciones_facturacion');
        $f = 0;
        $arreglo = '';
        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'cargo')) {
                //0tarifario 1 cargo 2cups 3int 4ext
                $var = explode('||', $v);

                $arreglo[] = array('cargo' => $var[1], 'tarifario' => $var[0], 'servicio' => $_REQUEST['servicio'], 'aut_int' => $var[3], 'aut_ext' => $var[4], 'cups' => $var[2], 'cantidad' => 1, 'departamento' => $_REQUEST['departamento'], 'sw_cargue' => 3);
                $f++;
            }
        }

        if ($f == 0) {
            $this->frmError["MensajeError"] = "ERROR DATOS VACIOS: DEBE ELEGIR ALGUN CARGO EQUIVALENTE.";
            $this->FormaFacturas($_REQUEST['Cuenta'], $_REQUEST['TipoId'], $_REQUEST['PacienteId'], $_REQUEST['PlanId'], $_REQUEST['Nivel'], $_REQUEST['Fecha'], $_REQUEST['Ingreso'], '', '', '', '', '');
            return true;
        }

        $insertar = InsertarCuentasDetalle($_REQUEST['empresa'], $_REQUEST['cu'], $_REQUEST['Cuenta'], $_REQUEST['PlanId'], $arreglo);

        if (!empty($insertar)) {
            $mensaje = "EL CARGO FUE AGREGADO A LA CUENTA.";

            list($dbconn) = GetDBconn();
            $query = "DELETE FROM procedimientos_pendientes_cargar
                                        WHERE procedimiento_pendiente_cargar_id=" . $_REQUEST['ID'] . "";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        } else {
            $this->frmError["MensajeError"] = "ERROR: OCURRIO UN ERROR AL INSERTAR.";
        }

        $this->FormaFacturas($_REQUEST['Cuenta'], $_REQUEST['TipoId'], $_REQUEST['PacienteId'], $_REQUEST['PlanId'], $_REQUEST['Nivel'], $_REQUEST['Fecha'], $_REQUEST['Ingreso'], '', '', '', '', '');
        return true;
    }

//----------------------PARA LA LLAMADA EXTERNA--------------------------

    /**
     *
     */
    function LlamadoFacturacion() {
        if ($_REQUEST['tipo_factura']) {
            $tipo_factura = $_REQUEST['tipo_factura'][sw_tipo];
            $_SESSION['FACTURACION']['VAR']['factura'] = $_REQUEST['tipo_factura'][factura_fiscal];
            $_SESSION['FACTURACION']['VAR']['prefijo'] = $_REQUEST['tipo_factura'][prefijo];
            $_SESSION['FACTURACION']['VAR']['empresa'] = $_REQUEST['tipo_factura'][empresa_id];
        }

        if (empty($_SESSION['FACTURACION']['RETORNO'])) {
            $this->error = "FACTURACION ";
            $this->mensajeDeError = "El retorno esta vacio.";
            return false;
        }

        $this->FormaFacturas($_SESSION['FACTURACION']['CUENTA'], $_SESSION['FACTURACION']['tipo_id_paciente'], $_SESSION['FACTURACION']['paciente_id'], $_SESSION['FACTURACION']['plan_id'], $_SESSION['FACTURACION']['nivel'], $_SESSION['FACTURACION']['fecha'], $_SESSION['FACTURACION']['ingreso'], '', '', '', $_SESSION['FACTURACION']['estado'], '', $tipo_factura);
        return true;
    }

//-----------------HABITACIONES------------------------

    function TiposCamasPlan($plan) {
        list($dbconn) = GetDBconn();
        $query = "SELECT b.descripcion, a.cargo, a.tarifario_id, a.cargo_cups, a.tipo_cama_id,
                                a.porcentaje, a.valor_lista, a.valor_excedente, c.descripcion as descar, c.precio
                                FROM planes_tipos_camas as a, tipos_camas as b, tarifarios_detalle as c
                                WHERE a.plan_id=$plan and a.tipo_cama_id=b.tipo_cama_id
                                and a.cargo=c.cargo and a.tarifario_id=c.tarifario_id";
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
        $result->Close();
        return $var;
    }

    function LlamarFormaLiquidacionManualHabitaciones() {
        $_SESSION['FACTURACION']['VARIABLES']['Cuenta'] = $_REQUEST['Cuenta'];
        $_SESSION['FACTURACION']['VARIABLES']['TipoId'] = $_REQUEST['TipoId'];
        $_SESSION['FACTURACION']['VARIABLES']['PacienteId'] = $_REQUEST['PacienteId'];
        $_SESSION['FACTURACION']['VARIABLES']['PlanId'] = $_REQUEST['PlanId'];
        $_SESSION['FACTURACION']['VARIABLES']['Nivel'] = $_REQUEST['Nivel'];
        $_SESSION['FACTURACION']['VARIABLES']['Fecha'] = $_REQUEST['Fecha'];
        $_SESSION['FACTURACION']['VARIABLES']['Ingreso'] = $_REQUEST['Ingreso'];

        $this->FormaLiquidacionManualHabitaciones('');
        return true;
    }

    function EliminarCargoHabitacionVector() {
        unset($_SESSION['CUENTAS']['CAMA']['LIQ'][$_REQUEST['posicion']]);
        $this->frmError["MensajeError"] = "SE ELIMINO EL REGSITRO.";
        $this->FormaLiquidacionManualHabitaciones('');
        return true;
    }

    function ModificarCargoHabitacionVector() {
        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'precio_plan')) {
                $x = explode('precio_plan', $k);
                $_SESSION['CUENTAS']['CAMA']['LIQ'][$x[1]]['precio_plan'] = $v;
            }
            if (substr_count($k, 'dias')) {
                $x = explode('dias', $k);
                $_SESSION['CUENTAS']['CAMA']['LIQ'][$x[1]]['cantidad'] = $v;
            }
            if (substr_count($k, 'cub')) {
                //$x = explode('cub',$k);
                //$_SESSION['CUENTAS']['CAMA']['LIQ'][$x[1]]['valor_cubierto']=$v;
                $_SESSION['CUENTAS']['CAMA']['LIQ'][$x[1]]['valor_cubierto'] = $_SESSION['CUENTAS']['CAMA']['LIQ'][$x[1]]['precio_plan'];
            }
            if (substr_count($k, 'excedente')) {
                $x = explode('excedente', $k);
                $_SESSION['CUENTAS']['CAMA']['LIQ'][$x[1]]['excedente'] = $v;
                $_SESSION['CUENTAS']['CAMA']['LIQ'][$x[1]]['valor_no_cubierto'] = $v;
            }
            /* if(substr_count($k,'excedente'))
              {
              $x = explode('excedente',$k);
              $_SESSION['CUENTAS']['CAMA']['LIQ'][$x[1]]['valor_no_cubierto']=$v;
              } */
        }

        //----revisa q la suma de valor cubierto y no cubierto de el valor del cargo
        //hay q multiplicar val cubierto y no cubierto por la cantidad

        $liq = $_SESSION['CUENTAS']['CAMA']['LIQ'];
        for ($i = 0; $i < sizeof($liq); $i++) {
            //$liq[$i]['valor_cargo']=$liq[$i]['precio_plan']*$liq[$i]['cantidad'];
            /* if(($liq[$i]['valor_no_cubierto']+$liq[$i]['valor_cubierto']) != $liq[$i]['precio_plan'] AND ($liq[$i]['valor_no_cubierto']+$liq[$i]['valor_cubierto']) != $liq[$i]['valor_cargo'])
              {
              $this->frmError["MensajeError"]="LA SUMA DEL VALOR CUBIERTO Y NO CUBIERTO DEBE DAR EL PRECIO.";
              $this->FormaLiquidacionManualHabitaciones($i);
              return true;
              }
              else
              { */           //multiplica estos valores por la cantidad
            $liq[$i]['valor_no_cubierto'] = $liq[$i]['valor_no_cubierto'] * $liq[$i]['cantidad'];
            $liq[$i]['valor_cubierto'] = $liq[$i]['valor_cubierto'] * $liq[$i]['cantidad'];
            $liq[$i]['valor_cargo'] = $liq[$i]['valor_no_cubierto'] + $liq[$i]['valor_cubierto'];

            $_REQUEST['noCub' . $i] = $liq[$i]['valor_no_cubierto'];
            $_REQUEST['cub' . $i] = $liq[$i]['valor_cubierto'];
            //}
        }
        $_SESSION['CUENTAS']['CAMA']['LIQ'] = $liq;
        $this->frmError["MensajeError"] = "SE MODIFICARON LOS CARGOS.";
        $this->FormaLiquidacionManualHabitaciones('');
        return true;
    }

    function InsertarCargoHabitacionVector() {
        if (empty($_REQUEST['tipocama']) || empty($_REQUEST['dpto'])) {
            $this->frmError["MensajeError"] = "DEBE ESPECIFICAR EL TIPO DE CAMA Y EL DEPARTAMENTO.";
            $this->FormaLiquidacionManualHabitaciones('');
            return true;
        }
        /* if(($_REQUEST['cubN']+$_REQUEST['noCubN']) != $_REQUEST['precioN'])
          {
          $this->frmError["MensajeError"]="LA SUMA DEL VALOR CUBIERTO Y NO CUBIERTO DEBE DAR EL PRECIO.";
          $this->FormaLiquidacionManualHabitaciones('');
          return true;
          } */
        if (empty($_REQUEST['diasN'])) {
            $this->frmError["MensajeError"] = "DEBE ESPECIFICAR LA CANTIDAD DE DIAS.";
            $this->FormaLiquidacionManualHabitaciones('');
            return true;
        }
        $_REQUEST['cubN'] = $_REQUEST['precioN'] * $_REQUEST['diasN'];
        $_REQUEST['excedente'] = $_REQUEST['noCubN'];
        $_REQUEST['noCubN'] = $_REQUEST['noCubN'] * $_REQUEST['diasN'];
        $valcargo = $_REQUEST['cubN'] + $_REQUEST['noCubN'];
        //$valcargo=$_REQUEST['precioN']*$_REQUEST['diasN'];
        //0=>valor_excedente 1=>valor_lista 2=>precio 3=>porcentaje 4=>tipo_cama_id
        //5=>tarifario_id 6=>cargo 7=>cargo_cups 8=>descripcion
        $v = explode('||', $_REQUEST['tipocama']);
        $cups = $v[7];
        $cargo = $v[6];
        $tarifario = $v[5];
        $copago = 0;
        if (!empty($_REQUEST['copago'])) {
            $copago = 1;
        }
        //0=>dpto 1=>servicio
        $d = explode('||', $_REQUEST['dpto']);
        $servicio = $d[1];
        $dpto = $d[0];

        $_SESSION['CUENTAS']['CAMA']['LIQ'][] = array('servicio' => $servicio, 'departamento' => $dpto, 'valor_no_cubierto' => $_REQUEST['noCubN'], 'valor_cubierto' => $_REQUEST['cubN'], 'precio_plan' => $_REQUEST['precioN'], 'valor_cargo' => $valcargo, 'cargo_cups' => $cups, 'cantidad' => $_REQUEST['diasN'], 'cargo' => $cargo, 'tarifario_id' => $tarifario, 'facturado' => 1, 'sw_cuota_paciente' => $copago, 'sw_cuota_moderadora' => 0, 'porcentaje_gravamen' => 0, 'descripcion' => $v[8], 'valor_descuento_empresa' => 0, 'valor_descuento_paciente' => 0, 'excedente' => $_REQUEST['excedente']);

        $_REQUEST = '';
        $this->frmError["MensajeError"] = "SE MODIFICARON LOS CARGOS.";
        $this->FormaLiquidacionManualHabitaciones('');
        return true;
    }

    function VolverDetalle() {
        $_REQUEST['Cuenta'] = $_SESSION['FACTURACION']['VARIABLES']['Cuenta'];
        $_REQUEST['TipoId'] = $_SESSION['FACTURACION']['VARIABLES']['TipoId'];
        $_REQUEST['PacienteId'] = $_SESSION['FACTURACION']['VARIABLES']['PacienteId'];
        $_REQUEST['PlanId'] = $_SESSION['FACTURACION']['VARIABLES']['PlanId'];
        $_REQUEST['Nivel'] = $_SESSION['FACTURACION']['VARIABLES']['Nivel'];
        $_REQUEST['Fecha'] = $_SESSION['FACTURACION']['VARIABLES']['Fecha'];
        $_REQUEST['Ingreso'] = $_SESSION['FACTURACION']['VARIABLES']['Ingreso'];
        unset($_SESSION['FACTURACION']['VARIABLES']);

        if (!$this->FormaFacturas($_REQUEST['Cuenta'], $_REQUEST['TipoId'], $_REQUEST['PacienteId'], $_REQUEST['PlanId'], $_REQUEST['Nivel'], $_REQUEST['Fecha'], $_REQUEST['Ingreso'], $_REQUEST['Transaccion'], $Dev, $vars, $_REQUEST['Estado'])) {
            return false;
        }
        return true;
    }

    function LlamadoCargarHabitacionCuenta() {
        if (empty($_SESSION['FACTURACION']['VARIABLES']['Cuenta'])) {
            $_SESSION['FACTURACION']['VARIABLES']['Cuenta'] = $_REQUEST['Cuenta'];
            $_SESSION['FACTURACION']['VARIABLES']['TipoId'] = $_REQUEST['TipoId'];
            $_SESSION['FACTURACION']['VARIABLES']['PacienteId'] = $_REQUEST['PacienteId'];
            $_SESSION['FACTURACION']['VARIABLES']['PlanId'] = $_REQUEST['PlanId'];
            $_SESSION['FACTURACION']['VARIABLES']['Nivel'] = $_REQUEST['Nivel'];
            $_SESSION['FACTURACION']['VARIABLES']['Fecha'] = $_REQUEST['Fecha'];
            $_SESSION['FACTURACION']['VARIABLES']['Ingreso'] = $_REQUEST['Ingreso'];
        }
        IncludeLib('funciones_facturacion');
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query = "UPDATE cuentas SET sw_liquidacion_manual_habitaciones='1' WHERE numerodecuenta=" . $_SESSION['FACTURACION']['VARIABLES']['Cuenta'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            $dbconn->RollbackTrans();
            return false;
        }
        //falta esta tabla
        $query = "INSERT INTO auditoria_liquidacion_manual_habitaciones(
                                                                numerodecuenta,
                                                                usuario_id,
                                                                fecha_registro)
                                        VALUES(" . $_SESSION['FACTURACION']['VARIABLES']['Cuenta'] . "," . UserGetUID() . ",'now()')";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            $dbconn->RollbackTrans();
            return false;
        }

        $val = CargarHabitacionCuenta('', $_SESSION['CUENTAS']['CAMA']['LIQ'], true, &$dbconn, $_SESSION['FACTURACION']['EMPRESA'], $_SESSION['FACTURACION']['VARIABLES']['Cuenta'], 3);

        if (empty($val)) {
            $this->frmError["MensajeError"] = "ERROR AL INSERTAR EN CUENTAS DETALLE.";
            if (empty($_REQUEST['volverDetalle'])) {
                $this->FormaLiquidacionManualHabitaciones('');
                return true;
            } else {
                $this->VolverDetalle();
                return true;
            }
        }

        $dbconn->CommitTrans();
        $this->frmError["MensajeError"] = "SE HICIERON LOS CARGOS DE HABITACIONES A LA CUENTA.";
        $this->VolverDetalle();
        return true;
    }

    function DepartamentosHabitaciones() {
        $EmpresaId = $_SESSION['FACTURACION']['EMPRESA'];

        list($dbconn) = GetDBconn();
        $query = "SELECT a.departamento,a.descripcion, a.servicio
                FROM departamentos as a, servicios as b WHERE a.empresa_id='$EmpresaId'
                and a.servicio=b.servicio and b.sw_asistencial='1'";
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
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $vars;
    }

    function ValidarEgresoPaciente($Ingreso) {
        list($dbconn) = GetDBconn();
        //ARRANQUE CALI
        //revisar el estado
        $query = "SELECT count(*)
            FROM hc_ordenes_medicas a,hc_vistosok_salida_detalle b
            WHERE a.ingreso=$Ingreso AND a.sw_estado IN ('0','1') AND a.hc_tipo_orden_medica_id IN ('99','06','07') AND
            a.ingreso=b.ingreso AND b.visto_id='01' AND a.evolucion_id=b.evolucion_id"; //a.sw_estado='0'
        //FIN ARRANQUE CALI
        /* $query = "SELECT count(c.egreso_dpto_id)
          FROM egresos_departamento as a, hc_evoluciones as b, egresos_departamento_cuentas_x_liquidar as c
          WHERE b.ingreso=$Ingreso and b.evolucion_id=a.evolucion_id
          and a.egreso_dpto_id=c.egreso_dpto_id"; */
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $result->Close();
        return $result->fields[0];
    }

    function BuscarNombreCompletoPaciente($tipo, $documento) {
        list($dbconn) = GetDBconn();
        $query = "SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombre
                                    FROM pacientes
                                    WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            if ($result->EOF) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
                return false;
            }
        }
        $Nombres = $result->fields[0];
        $result->Close();
        return $Nombres;
    }

    function DatosCargosCirugia($transaccion) {
        list($dbconn) = GetDBconn();
        $query = "SELECT b.descripcion as descar, b.cargo
                      FROM cuentas_cargos_qx_procedimientos as a, cuentas_liquidaciones_qx_procedimientos as c,
                                cups as b
                                WHERE a.transaccion=$transaccion  and a.consecutivo_procedimiento=c.consecutivo_procedimiento
                                and c.cargo_cups=b.cargo";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $vars = $result->GetRowAssoc($ToUpper = false);
        $result->Close();
        return $vars;
    }

    function BuscarHonorariosCirugia($transaccion) {
        list($dbconn) = GetDBconn();
        $query = "SELECT
                      FROM cuentas_detalle_honorarios as a
                                WHERE a.transaccion=$transaccion";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $vars = $result->GetRowAssoc($ToUpper = false);
        $result->Close();
        return $vars;
    }

    /**
     * Busca el nombre de un prodesional.
     * @access public
     * @return array
     */
    function GetNombreProfesional($Tipo, $Numero) {
        list($dbconn) = GetDBconn();
        $query = " SELECT nombre FROM profesionales
                  WHERE  tipo_id_tercero='$Tipo' AND tercero_id='$Numero'";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $resulta->Close();
        return $resulta->fields[0];
    }

    /**
     *   consulta el tipo de reporte a generar, dado el plan_id
     *   tercero_id, tipo_tyercero_id y el tipo_plan
     */
    function ConsultaTipoReporte($arr, $reporte) {

        $arreglo = current($arr);

        $envio = $arreglo['envio_id'];
        //Consultamos la concurrencia(interseccion) entre los vectores
        $resultado = current($arreglo['print']);

        foreach ($arreglo['print'] as $print => $datos) {
            $resultado = array_intersect($resultado, $datos);
        }

        $filtro_tipo_plan = 'AND tipos_planes IS NULL';
        $filtro_plan_id = 'AND plan_id  IS NULL';
        $filtro_tercero_id = 'AND tercero_id IS NULL';
        $filtro_tipo_tercero = 'AND tipo_tercero_id IS NULL';

        if (!empty($resultado['plan_id'])) {
            $filtro_plan_id = "AND (plan_id = '" . $resultado['plan_id'] . "' OR plan_id IS NULL)";
        }
        if (!empty($resultado['tipo_plan'])) {
            $filtro_tipo_plan = "AND (tipos_planes = '" . $resultado['tipo_plan'] . "' OR tipos_planes IS NULL)";
        }
        if (!empty($resultado['tercero_id'])) {
            $filtro_tercero_id = "AND (tercero_id = '" . $resultado['tercero_id'] . "' OR tercero_id IS NULL)";
        }
        if (!empty($resultado['tipo_tercero_id'])) {
            $filtro_tipo_tercero = "AND (tipo_tercero_id = '" . $resultado['tipo_tercero_id'] . "' OR tipo_tercero_id IS NULL)";
        }

        $query = "
                        SELECT  tipo_reporte
                        FROM        reportes_envios
                        WHERE       nombre_reporte = '" . $reporte . "'
                                        $filtro_plan_id
                                        $filtro_tipo_plan
                                        $filtro_tercero_id
                                        $filtro_tipo_tercero
                        ORDER BY plan_id ASC, tipos_planes ASC, tercero_id ASC
        ";

        list($dbconn) = GetDBconn();
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al consultar reportes_envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $resulta->Close();
        return $resulta->fields[0];
    }

    /**
     * Retorna un arreglo con los cargos tipo ajuste,
     *
     * Array([] =>array(cargo=>"valor" ,valor_min=>"valor", valor_max=>"valor", sw_descuento=>"valor"))
     *
     * @return array
     */
    function GetCargosAjuste() {
        $sql = "
            SELECT
                A.cargo, A.valor_min, A.valor_max, A.sw_descuento,
                B.descripcion
            FROM
                fact_cargos_ajuste_cuentas AS A,
                tarifarios_detalle AS B
            WHERE   A.tarifario_id='SYS'
            AND A.empresa_id='" . $_SESSION['CUENTAS']['EMPRESA'] . "'
            AND B.tarifario_id=A.tarifario_id
            AND B.cargo=A.cargo
            ORDER BY 2;";
        list($dbconn) = GetDBconn();
        GLOBAL $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Consultar motivos_cambio_copago";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if ($result->EOF)
            return array();
        $motivos = $result->GetRows();
        return $motivos;
    }

//Fin GetMotivosCambioCopago

    function IdentificarTotalCuentaFueraRangoSoat($cuenta) {
        $query = "SELECT

              (CASE WHEN a.valor_cubierto > s.saldo_inicial THEN '1'
                    WHEN a.valor_cubierto = s.saldo_inicial THEN '2'
                    ELSE '0'
               END) as limite_saldo

              FROM cuentas a,   planes b , ingresos_soat i ,  soat_eventos s
              WHERE a.numerodecuenta='$cuenta'
              AND a.plan_id=b.plan_id
              AND b.sw_tipo_plan='1'
              AND a.ingreso=i.ingreso
              AND i.evento=s.evento";
        list($dbconn) = GetDBconn();
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al consultar reportes_envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $resulta->Close();
        return $resulta->fields[0];
    }

    function CargosMedicamentosCuentaPaciente($NoLiquidacion) {
        $query = "SELECT c.codigo_producto,sum(c.cantidad) as cantidad,sum(b.valor_cubierto) as valor_cubierto,
    (SELECT d.descripcion FROM inventarios_productos d WHERE c.codigo_producto=d.codigo_producto) as descripcion
    FROM cuentas_codigos_agrupamiento a,cuentas_detalle b,bodegas_documentos_d c
    WHERE a.cuenta_liquidacion_qx_id='" . $NoLiquidacion . "' AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND b.cargo='IMD' AND
    a.bodegas_doc_id=c.bodegas_doc_id AND a.numeracion=c.numeracion AND b.consecutivo=c.consecutivo
    GROUP BY c.codigo_producto,b.valor_cubierto";
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            if ($result->RecordCount() > 0) {
                while (!$result->EOF) {
                    $vars[] = $result->GetRowAssoc($toUpper = false);
                    $result->MoveNext();
                }
            }
        }
        return $vars;
    }

    function CargosMedicamentosCuentaPacienteDevol($NoLiquidacion) {
        $query = "SELECT c.codigo_producto,sum(c.cantidad) as cantidad,sum(b.valor_cubierto) as valor_cubierto,
    (SELECT d.descripcion FROM inventarios_productos d WHERE c.codigo_producto=d.codigo_producto) as descripcion
    FROM cuentas_codigos_agrupamiento a,cuentas_detalle b,bodegas_documentos_d c
    WHERE a.cuenta_liquidacion_qx_id='" . $NoLiquidacion . "' AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND b.cargo='DIMD' AND
    a.bodegas_doc_id=c.bodegas_doc_id AND a.numeracion=c.numeracion AND b.consecutivo=c.consecutivo
    GROUP BY c.codigo_producto,b.valor_cubierto";
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            if ($result->RecordCount() > 0) {
                while (!$result->EOF) {
                    $vars[] = $result->GetRowAssoc($toUpper = false);
                    $result->MoveNext();
                }
            }
        }
        return $vars;
    }

//------------------------------------------------------------------------

    function EliminarRadicacionEnvio() {
        if (empty($_REQUEST['observaciones'])) {
            $this->frmError["observaciones"] = 1;
            $this->frmError["MensajeError"] = "El campo observaciones es obligatorio.";
            $this->FormaEliminarRadicacion($_REQUEST['fecha_radicacion'], $_REQUEST['envio_id']);
            return true;
        }

        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query = "UPDATE envios SET fecha_radicacion=NULL,
                                                sw_estado=0
                                WHERE envio_id=" . $_REQUEST['envio_id'] . ";";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $plan = $_SESSION['DETALLE']['ENVIO'][0]['plan_id'];
        $query = "UPDATE envios_planes SET fecha_vencimiento_facturas=NULL
                                WHERE envio_id=" . $_REQUEST['envio_id'] . " AND plan_id=" . $plan . ";";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $SystemId = UserGetUID();
        $empresa_id = $_SESSION['DETALLE']['ENVIO'][0]['empresa_id'];
        $query = "INSERT INTO auditoria_modificacion_radicacion
                                (
                                    empresa_id,
                                    envio_id,
                                    observacion,
                                    usuario_id,
                                    fecha_radicacion_anterior,
                                    fecha_registro
                                )
                                VALUES
                                (
                                    '" . $empresa_id . "',
                                    " . $_REQUEST['envio_id'] . ",
                                    '" . $_REQUEST['observaciones'] . "',
                                    " . $SystemId . ",
                                    '" . $_REQUEST['fecha_radicacion'] . "',
                                    now()
                                );";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en auditoria_modificacion_radicacion";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $dbconn->CommitTrans();
        $envio_id = $_REQUEST['envio_id'];
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarRad');
        //$accion=ModuloGetURL('app','Facturacion_Fiscal','user','FormaDetalleEnvio');
        //$accion=ModuloGetURL('app','Facturacion_Fiscal','user','BuscarEnviosRad',array('envio'=>$envio_id));
        $mensaje = 'Se Elimin�la Fecha de Radicaci� del Envio No. ' . $_REQUEST['envio_id'];
        $this->FormaMensaje($mensaje, 'ENVIO No. ' . $envio_id, $accion, 'ACEPTAR');
        return true;
    }

    function ModificarEnvio() {
        $f = 0;
        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'Envio')) {
                if (!empty($v)) {
                    $f = 1;
                }
            }
        }
        if ($f == 0) {
            $this->frmError["MensajeError"] = "Debe Elegir la(s) Factura(s) a adicionar o eliminar.";
            $this->FormaModificarFacturasEnvios($_SESSION['DETALLE']['SELECCION']);
        } else {
            $this->ActulizarEnviosfacturas($_REQUEST);
            //$this->FormaDetalleEnvio($_REQUEST);
        }
        return true;
    }

    function ActulizarEnviosfacturas($_REQUEST) {
        $envio = $_SESSION['DETALLE']['ENVIO'][0]['envio_id'];

        $query = "SELECT B.prefijo, b.factura_fiscal
                        FROM    envios A,
                                    envios_detalle B
                        WHERE A.envio_id=$envio
                        AND A.envio_id=B.envio_id;";
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            if ($result->RecordCount() > 0) {
                while (!$result->EOF) {
                    $vars[] = $result->GetRowAssoc($toUpper = false);
                    $result->MoveNext();
                }
            }
        }
        $g = 0;
        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'Envio')) {
                //0 prefijo 1 factura 2 ingreso 3 numerodecuenta
                //4 total_factura 5 plan_id 6 plan_des 7 empresa
                //8 tipo_id_paciente 9 nombre
                $y = explode('||', $v);
                $seleccion[$g] = $y[0] . '**' . $y[1];
                $g++;
            }
        }
        $tmp = $l = 0;

        UNSET($_SESSION['DETALLE']['ENVIO']['print']);

        for ($i = 0; $i < sizeof($_SESSION['DETALLE']['ENVIO']); $i++) {
            foreach ($seleccion as $i2 => $v) {
                $dat = explode('**', $v);
                if ($_SESSION['DETALLE']['ENVIO'][$i][prefijo] == $dat[0]
                        AND $_SESSION['DETALLE']['ENVIO'][$i][factura_fiscal] == $dat[1]) {
                    $tmp++;
                    $val[$l] = $v;
                    UNSET($seleccion[$i2]);
                    $l++;
                    //$j=sizeof($seleccion);
                }
            }
        }

        if ($tmp != sizeof($_SESSION['DETALLE']['ENVIO'])) {
            $dbconn->BeginTrans();
            $query = "DELETE
                                FROM envios_detalle
                                WHERE envio_id=" . $envio . "
                                AND empresa_id='" . $_SESSION['DETALLE']['ENVIO'][0]['empresa_id'] . "';";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al eliminar en envios detalle";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            for ($i = 0; $i < sizeof($val); $i++) {
                $inser = explode('**', $val[$i]);
                if ($val[$i] != $val[$i - 1]) {
                    $query = "INSERT INTO envios_detalle
										(
											envio_id,
											prefijo,
											factura_fiscal,
											empresa_id
										)
										VALUES
										(
											" . $envio . ",
											'" . $inser[0] . "',
											" . $inser[1] . ",
											'" . $_SESSION['DETALLE']['ENVIO'][0]['empresa_id'] . "'
										);";

                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en envios";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                }
            }
        }

        foreach ($seleccion as $i => $v) {
            $inser = explode('**', $v);
            if ($val[$i] != $val[$i - 1]) {
                $query = "INSERT INTO envios_detalle
										(
											envio_id,
											prefijo,
											factura_fiscal,
											empresa_id
										)
										VALUES
										(
											" . $envio . ",
											'" . $inser[0] . "',
											" . $inser[1] . ",
											'" . $_SESSION['DETALLE']['ENVIO'][0]['empresa_id'] . "'
										);";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en envios";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
            }
        }

        $dbconn->CommitTrans();
        $this->DetalleEnvio($envio);
        return true;
    }

    function EditarFecha() {
        $envio = $_REQUEST['envio_id'];
        $dat = $_REQUEST['FechaRegistro'];
        $dat = explode('/', $_REQUEST['FechaRegistro']);
        $fecha = $dat[2] . '-' . $dat[1] . '-' . $dat[0] . ' 00:00:00';
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query = "UPDATE envios
                            SET fecha_registro='" . $fecha . "'
                            WHERE envio_id=" . $envio . ";";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"] = "La fecha de registro del envio " . $envio . " se modifico";
        $this->DetalleEnvio($envio);
        return true;
    }

    /*     * **********************************************************************************
     * Busca si el paciente tiene una cuenta abierta
     * @access public
     * @return boolean
     * ********************************************************************************** */

    function BuscarIngresoPaciente() {
        
        $empresa = $EmpresaId = $_SESSION['FACTURACION']['EMPRESA'];

        $this->Documento = trim($_REQUEST['Documento']);
        $this->TipoDocumento = $_REQUEST['TipoDocumento'];
        $this->Responsable = $this->Plan = $_REQUEST['Responsable'];

        $validar = $this->ValidarDatosPrincipales();
        $Paciente = $this->ReturnModuloExterno('app', 'Pacientes', 'user');

        if ($validar) {
            if (($this->TipoDocumento == 'AS' OR $this->TipoDocumento == 'MS')
                    AND empty($this->Documento)) {
                $this->Documento = $this->CallMetodoExterno('app', 'Pacientes', 'user', 'IdentifiacionNN');
            }

            if (!is_object($Paciente)) {
                $this->error = "La clase Pacientes no se pudo instanciar";
                $this->mensajeDeError = "";
                return false;
            }

            $dat = $this->ValidarIngresoPaciente();
            if (is_array($dat)) {
                unset($Paciente);
                $this->frmError['MensajeError'] = "EL PACIENTE YA SE ENCUENTRA CON UN INGRESO ACTIVO, EL CUAL POSEE LA SIGUIENTE INFORMACI�";
                $this->FormaMostrarInfoIngreso($dat);
                return true;
            } else {
                $request = $_REQUEST;

                $datos = array();
                $datos['tipo_id_paciente'] = $request['TipoDocumento'];
                if ($request['Documento'])
                    $datos['paciente_id'] = $request['Documento'];
                $datos['plan_id'] = $request['Responsable'];

                $_REQUEST['tipo_id_paciente'] = $request['TipoDocumento'];
                $_REQUEST['paciente_id'] = $request['Documento'];
                $_REQUEST['plan_id'] = $request['Responsable'];

                $this->action['cancelar'] = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FrmAperturaAdmision');
                $this->action['volver'] = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaElegirDepartamento', $datos);

                //$this->PedirDatosPaciente();
                $this->FormaDatosPaciente();
                return true;
            }
        }
        else {
            unset($Paciente);
            //$this->Buscar();
            $this->FrmAperturaAdmision();
            return true;
        }
    }

    /*     * **********************************************************************************
     * Valida los datos de la ventana inicial para buscar el paciente
     * @access public
     * @return boolean
     * *********************************************************************************** */

    function ValidarDatosPrincipales() {
        
        
        $tip_afiliado_DA = trim($_REQUEST['tip_afiliado_DA']);
        $rangoDA = trim($_REQUEST['rangoDA']);
        
        
        $this->TipoId = $this->TipoDocumento;
        $this->PacienteId = $this->Documento;
        $this->Responsable = $this->Plan;
        if (strlen($tip_afiliado_DA) == 0 || strlen($rangoDA) == 0){
            $this->frmError["MensajeError"] = "DEBE SELECCIONAR TIPO DE AFILIADO Y RANGO.";                    
            return false;
        }

        if ($_REQUEST['estado_paciente'] == 'AC') {
            if (!($_REQUEST['plan_id_pac_actual'] == $_REQUEST['plan_id_est_actual_sel'])) {
                if (!($_REQUEST['sw_estado_plan'] == 2)) { //ESTADO DEL PLAN SELECCIONADO, DEL CUAL EL PACIENTE NO HACE PARTE
                    $this->frmError["MensajeError"] = "El USUARIO NO ESTA EN EL PLAN SELECCIONADO.";                    
                    return false;
                }
            }
        } else {
            if (!(($_REQUEST['sw_estado_plan'] == 2) or ($_REQUEST['sw_estado_plan'] == 0))) { //ESTADO DEL PLAN SELECCIONADO, DEL CUAL EL PACIENTE NO HACE PARTE
                $this->frmError["MensajeError"] = "El USUARIO NO ESTA EN EL PLAN SELECCIONADO.";                    
                return false;
            }
        }
        
        
        
        if ($this->TipoDocumento != 'AS' && $this->TipoDocumento != 'MS') {
            if (!$this->Documento || !$this->TipoDocumento || $this->Plan == -1) {
                if (!$this->Documento) {
                    $this->frmError["MensajeError"] = "FALTA EL NUMERO DEL DOCUMENTO";
                }
                if (!$this->TipoDocumento) {
                    $this->frmError["MensajeError"] = "FAVOR SELECCIONAR EL TIPO DE DOCUMENTO";
                }
                if ($this->Plan == "-1") {
                    $this->frmError["MensajeError"] = "FAVOR SELECCIONAR EL PLAN";
                }
                return false;
            }
            return true;
        } else {
            if ($this->Plan == -1) {
                if ($this->Plan == -1) {
                    $this->frmError["MensajeError"] = "FAVOR SELECCIONAR EL PLAN";
                }
                return false;
            }
            return true;
        }
    }

    /*     * **********************************************************************************
     *
     * *********************************************************************************** */

    function ValidarIngresoPaciente() {
        list($dbconn) = GetDBconn();
        $query = "SELECT   COUNT(*)
                                                        FROM        ingresos I, cuentas C
                                                        WHERE   I.paciente_id = '" . $this->Documento . "'
                                                        AND     I.tipo_id_paciente = '" . $this->TipoDocumento . "'
                                                        AND     I.estado ='1'
                                                        AND     I.ingreso = C.ingreso 
                                                        AND     C.estado = '1' ";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al seleccionar ingresos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            $cantidad = $result->fields[0];
            $result->MoveNext();
        }
        $result->Close();

        if ($cantidad > 0) {
            $query = " SELECT  IG.ingreso,
                                                                TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso,
                                                                        PC.paciente_id,
                                                                PC.tipo_id_paciente,
                                                                PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,
                                                                PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres,
                                                                VI.via_ingreso_nombre
                                                                FROM        ingresos IG, pacientes PC,vias_ingreso VI
                                                                WHERE   IG.paciente_id = '" . $this->Documento . "'
                                                                AND     IG.tipo_id_paciente = '" . $this->TipoDocumento . "'
                                                                AND     IG.estado ='1'
                                                                AND     IG.paciente_id = PC.paciente_id
                                                                AND     IG.tipo_id_paciente = PC.tipo_id_paciente
                                                                AND     VI.via_ingreso_id = IG.via_ingreso_id ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al seleccionar ingresos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while (!$result->EOF) {
                $cuentas[ingreso][] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $result->Close();

            $query = "SELECT CU.numerodecuenta,
                                                CU.total_cuenta,
                                                CE.descripcion,
                                                PL.plan_descripcion
                FROM    cuentas CU,
                                                planes PL,
                                                cuentas_estados CE
                WHERE   CU.ingreso = " . $cuentas[ingreso][0]['ingreso'] . "
                AND     CU.plan_id = PL.plan_id
                AND     CU.estado = CE.estado ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al seleccionar ingresos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while (!$result->EOF) {
                $cuentas[cuentas][] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $result->Close();
            $this->frmError['Informacion'] = "EL PACIENTE YA SE ENCUENTRA CON UN INGRESO ACTIVO, EL CUAL POSEE LA SIGUIENTE INFORMACI�";
            return $cuentas;
        }
        return false;
    }

    /**
     * Llama el modulo de autorizaciones
     * @access public
     * @return boolean
     * @param string tipo de documento
     * @param int numero de documento
     * @param int plan_id
     */
    function AutorizarPaciente($td = null, $doc= null, $plan= null) {
        $datos = $request = $_REQUEST;

        $datos['idp'] = $request['paciente_id'];
        $datos['tipoid'] = $request['tipo_id_paciente'];
        $datos['plan_id'] = $request['plan_id'];
        $datos['rango'] = $request['afilia']['rango'];
        $datos['Semanas'] = $request['afilia']['Semanas'];
        $datos['tipoafiliado'] = $request['afilia']['tipoafiliado'];
        $datos['fecha'] = date("d/m/Y");
        $datos['hora'] = date("H");
        $datos['minuto'] = date("i");
        $datos['tipoautoriza_interna'] = "AT";
        $datos['tipo_autorizacion'] = "I";

        SessionSetVar("DatosPaciente", $datos);
        IncludeClass('Autorizaciones', '', 'app', 'NCAutorizaciones');

        $aut = new Autorizaciones();
        $planes = $aut->ObtenerTiposPlanes($datos['plan_id']);
        $action2 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'RetornoAutorizacion');
        $Autoriza = $this->ReturnModuloExterno('app', 'NCAutorizaciones', 'user');
        $Autoriza->SetActionAceptar($action2);

        if ($planes['sw_tipo_plan'] == '0' || $planes['sw_tipo_plan'] == '1' || $planes['sw_tipo_plan'] == '2' || $planes['sw_tipo_plan'] == '3') {
            $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'] = $_SESSION['AUTORIZACIONES1']['RETORNO']['contenedor'] = 'app';
            $_SESSION['AUTORIZACIONES']['RETORNO']['modulo'] = $_SESSION['AUTORIZACIONES1']['RETORNO']['modulo'] = 'Facturacion_Fiscal';
            $_SESSION['AUTORIZACIONES']['RETORNO']['metodo'] = $_SESSION['AUTORIZACIONES1']['RETORNO']['metodo'] = 'FormaElegirDepartamento';
            $_SESSION['AUTORIZACIONES']['RETORNO']['tipo'] = $_SESSION['AUTORIZACIONES1']['RETORNO']['tipo'] = 'user';

            $Autoriza->SetClaseAutorizacion('AD');
            $rst = $Autoriza->IngresarAutorizacion($planes, $datos);
            if ($rst) {
                $Autoriza->action['aceptar'] .= "&departamentos=" . $datos['departamentos'];
                $this->salida .= "<script>\n";
                $this->salida .= "	location.href = \"" . $Autoriza->action['aceptar'] . "\"\n";
                $this->salida .= "</script>\n";
            }
        } else {
            $this->FrmAperturaAdmision();
        }
        return true;
    }

    /**
     * Llama el modulo de autorizaciones
     * @access public
     * @return boolean
     */
    function RetornoAutorizacion() {
        unset($_SESSION['ADMISIONES']);
        $datos = $_REQUEST;

        $_SESSION['ADMISIONES']['PACIENTE']['rango'] = $datos['autorizacion']['rango'];
        $_SESSION['ADMISIONES']['PACIENTE']['semanas'] = $datos['autorizacion']['semanas'];
        $_SESSION['ADMISIONES']['PACIENTE']['plan_id'] = $datos['autorizacion']['plan_id'];
        $_SESSION['ADMISIONES']['PACIENTE']['paciente_id'] = $datos['autorizacion']['paciente_id'];
        $_SESSION['ADMISIONES']['PACIENTE']['AUTORIZACION'] = $datos['autorizacion']['numero_autorizacion'];
        $_SESSION['ADMISIONES']['PACIENTE']['tipo_id_paciente'] = $datos['autorizacion']['tipo_id_paciente'];
        $_SESSION['ADMISIONES']['PACIENTE']['tipo_afiliado_id'] = $datos['autorizacion']['tipoafiliado'];

        $Mensaje = 'Auto';
        $TipoServicio = "URGENCIAS";
        ;

        if (empty($_SESSION['AUTORIZACIONES']['RETORNO']['ext'])) {
            $_SESSION['AUTORIZACIONES']['RETORNO']['ext'] = 'NULL';
        }

        $_SESSION['FACTURACION']['paciente']['autorizacion'] = $datos['autorizacion']['numero_autorizacion'];
        //empleador
        if (!empty($datos['autorizacion']['id_empleador'])) {
            $_SESSION['ADMISIONES']['PACIENTE']['id_empleador'] = $datos['autorizacion']['id_empleador'];
            $_SESSION['ADMISIONES']['PACIENTE']['tipo_empleador'] = $datos['autorizacion']['tipo_empleador'];
        }

        if (!empty($_SESSION['AUTORIZACIONES']['NOAUTO'])) {
            if (empty($Mensaje)) {
                $Mensaje = 'NO SE AUTORIZO LA ADMISION.';
            }
            $titulo = 'AUTORIZACIONES';
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FrmAperturaAdmision');
            $boton = 'Aceptar';
            $this->FormaMensaje($Mensaje, $titulo, $accion, $boton);
            return true;
        }

        unset($_SESSION['AUTORIZACIONES']);
        if (empty($_SESSION['FACTURACION']['paciente']['autorizacion'])) {
            if (empty($Mensaje)) {
                $Mensaje = 'NO SE PUDO REALIZAR LA AUTORIZACI� PARA LA ADMISI� HOSPITALIZACI�.';
            }

            //$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar',array('TIPOORDEN'=>'Externa'));
            $titulo = 'AUTORIZACIONES';
            $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FrmAperturaAdmision', array('TIPOORDEN' => 'Externa'));
            $boton = 'Aceptar';

            $this->FormaMensaje($Mensaje, $titulo, $accion, $boton);
            return true;
        }
        unset($_SESSION['PACIENTE']['INGRESO']);

        //llamado al metodo de ingreso
        $_SESSION['ADMISIONES']['RETORNO']['tipo'] = 'user';
        $_SESSION['ADMISIONES']['RETORNO']['modulo'] = 'Facturacion_Fiscal';
        $_SESSION['ADMISIONES']['RETORNO']['metodo'] = 'RetornoIngreso';
        $_SESSION['ADMISIONES']['RETORNO']['contenedor'] = 'app';
        $_SESSION['ADMISIONES']['RETORNO']['argumentos'] = array();
        $_SESSION['ADMISIONES']['TIPO'] = '';
        $_SESSION['ADMISIONES']['SOAT'] = ''; // SIN SOAT
        $_SESSION['ADMISIONES']['SWSOAT'] = $this->GetTipoPlan($datos['autorizacion']['plan_id']);
        $_SESSION['ADMISIONES']['EMPRESA'] = $_SESSION['FACTURACION']['EMPRESA'];
        $dat = explode(',', $_REQUEST['departamentos']);
        $_SESSION['ADMISIONES']['CENTROUTILIDAD'] = $dat[0]; //centro utilidad
        $_SESSION['ADMISIONES']['PACIENTE']['REMISION'] = '';  //SIN REMISION
        $_SESSION['ADMISIONES']['PACIENTE']['triage_id'] = ''; //SIN TRIAGE
        $_SESSION['ADMISIONES']['PACIENTE']['departamento'] = $dat[1]; //departamento
        $_SESSION['ADMISIONES']['PACIENTE']['departamento_actual'] = $dat[1];
        $_SESSION['ADMISIONES']['PACIENTE']['punto_admision_id'] = ''; //SIN punto_admision_id
        $_SESSION['ADMISIONES']['INGRESO']['ingreso'] = true;

        //$this->ReturnMetodoExterno('app','Admisiones','user','InsertarIngreso');
        $this->ReturnMetodoExterno('app', 'Admisiones', 'user', 'LlamarFormaIngreso', array('SW_APERTURA' => '1'));
        return true;
    }

    function DecidirRetorno() {

        if (!$_SESSION['PACIENTES']['RETORNO']['PASO']) {
            //$this->Buscar();
            $this->FrmAperturaAdmision();
            return true;
        }

        unset($_SESSION['PACIENTES']);
        $this->AutorizarPaciente();
        //$this->RetornoAutorizacion();
        return true;
    }

    function TraerDepartamentos() {
        $empresa = $_SESSION['FACTURACION']['EMPRESA'];
        list($dbconn) = GetDBconn();
        $query = "SELECT empresa_id, centro_utilidad,
                                                                                                departamento, descripcion
                                                                FROM departamentos
                                                                WHERE empresa_id='$empresa'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $vars[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $vars;
    }

    function RetornoIngreso() {
        if (!empty($_SESSION['ADMISIONES']['RETORNO']['CANCELAR'])) {
            $this->FrmAperturaAdmision();
            return true;
        }
//
        $query = "UPDATE ingresos SET
																estado = '0'
													WHERE ingreso=" . $_SESSION['ADMISIONES']['PACIENTE']['INGRESO'] . "";
        list($dbconn) = GetDBconn();
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error UPDATE ingresos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
//
        $accion = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaMenus');
        $mensaje = "SE REALIZ�LA ADMISI� DEL PACIENTE, CON INGRESO Nro " . $_SESSION['ADMISIONES']['PACIENTE']['INGRESO'];
        $titulo = "FACTURACI�";
        $boton = "Aceptar";
        $this->FormaMensaje($mensaje, $titulo, $accion, $boton, $botonC, $arreglo);
        return true;
    }

    function TraerTipoFactura($Cuenta) {
        $empresa = $_SESSION['FACTURACION']['EMPRESA'];
        list($dbconn) = GetDBconn();
        $query = "SELECT B.sw_tipo
                                        FROM fac_facturas A, fac_facturas_cuentas B
                                        WHERE A.empresa_id='$empresa'
                                        AND B.numerodecuenta=$Cuenta
                                        AND A.empresa_id=B.empresa_id
                                        AND A.prefijo=B.prefijo
                                        AND A.factura_fiscal=B.factura_fiscal
                                        AND A.estado='0'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al consultar fac_facturas A, fac_facturas_cuentas B";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            $vars = $result->GetRowAssoc($ToUpper = false);
        }
        $result->Close();
        return $vars[sw_tipo];
    }

    function TraerTipoRecibo($prefijo, $factura_fiscal) {
        $empresa = $_SESSION['FACTURACION']['EMPRESA'];
        list($dbconn) = GetDBconn();
        $query = "SELECT A.sw_cuota_moderadora
                                        FROM fac_facturas_contado A
                                        WHERE A.prefijo='" . $prefijo . "'
                                        AND A.factura_fiscal=" . $factura_fiscal . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al consultar fac_facturas A, fac_facturas_cuentas B";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            $vars = $result->GetRowAssoc($ToUpper = false);
        }
        $result->Close();
        return $vars[sw_cuota_moderadora];
    }

    function GetTipoPlan($plan_id) {
        list($dbconn) = GetDBconn();
        $query = "SELECT sw_tipo_plan
                                        FROM planes
                                        WHERE estado='1'
                                        AND plan_id='" . $plan_id . "'
                                        AND fecha_final >= now()
                                        AND fecha_inicio <= now()";
        $results = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $results->Close();
        return $results->fields[0];
    }

    //
    function ValidarDatosInformeSoat() {//
        list($dbconn) = GetDBconn();
        $var = explode('/', $_POST['fechadradi']);
        $day = $var[0];
        $mon = $var[1];
        $yea = $var[2];
        if (checkdate($mon, $day, $yea) == 0) {
            $_POST['fechadradi'] = '';
            $this->frmError["fechadradi"] = 1;
        } else {
            $fech = date("Y-m-d");
            if ($fech < date("Y-m-d", mktime(1, 1, 1, $mon, $day, $yea))) {
                $_POST['fechadradi'] = '';
                $this->frmError["fechadradi"] = 1;
            } else {
                $_SESSION['FACTURACION']['reportes_soat']['fechadradi'] = $yea . '-' . $mon . '-' . $day;
                $_SESSION['FACTURACION']['reportes_soat']['fechadrad2'] = $_POST['fechadradi'];
            }
        }
        $var = explode('/', $_POST['fechainici']);
        $day = $var[0];
        $mon = $var[1];
        $yea = $var[2];
        if (checkdate($mon, $day, $yea) == 0) {
            $_POST['fechainici'] = '';
            $this->frmError["fechainici"] = 1;
        } else {
            $fech = date("Y-m-d");
            if ($fech < date("Y-m-d", mktime(1, 1, 1, $mon, $day, $yea))) {
                $_POST['fechainici'] = '';
                $this->frmError["fechainici"] = 1;
            } else {
                $_SESSION['FACTURACION']['reportes_soat']['fechainici'] = $yea . '-' . $mon . '-' . $day;
                $_SESSION['FACTURACION']['reportes_soat']['fechainic2'] = $_POST['fechainici'];
            }
        }
        $var = explode('/', $_POST['fechafinal']);
        $day = $var[0];
        $mon = $var[1];
        $yea = $var[2];
        if (checkdate($mon, $day, $yea) == 0) {
            $_POST['fechafinal'] = '';
            $this->frmError["fechafinal"] = 1;
        } else {
            $fech = date("Y-m-d");
            if ($fech < date("Y-m-d", mktime(1, 1, 1, $mon, $day, $yea))) {
                $_POST['fechafinal'] = '';
                $this->frmError["fechafinal"] = 1;
            } else {
                $_SESSION['FACTURACION']['reportes_soat']['fechafinal'] = $yea . '-' . $mon . '-' . $day;
                $_SESSION['FACTURACION']['reportes_soat']['fechafina2'] = $_POST['fechafinal'];
            }
        }
        $var = explode('/', $_POST['periodorec']);
        $mon = $var[0];
        $yea = $var[1];
        if (checkdate($mon, 1, $yea) == 0) {
            $_POST['periodorec'] = '';
            $this->frmError["periodorec"] = 1;
        } else {
            $fech = date("Y-m-d");
            if ($fech < date("Y-m-d", mktime(1, 1, 1, $mon, 1, $yea))) {
                $_POST['periodorec'] = '';
                $this->frmError["periodorec"] = 1;
            } else {
                $_SESSION['FACTURACION']['reportes_soat']['salariomon'] = $this->SalarioAnoSoat($yea);
                $_SESSION['FACTURACION']['reportes_soat']['periodorec'] = $yea . '-' . $mon;
                $query = "SELECT A.ingreso,
                            B.fecha_ingreso,
                            C.fecha_cierre,
                            F.fecha_registro
                            FROM ingresos_soat AS A,
                            ingresos AS B,
                            cuentas AS C,
                            planes AS D,
                            fac_facturas_cuentas AS E,
                            fac_facturas AS F
                            WHERE A.ingreso=B.ingreso
                            AND B.ingreso=C.ingreso
                            AND C.empresa_id='" . $_SESSION['FACTURACION']['EMPRESA'] . "'
                            AND C.estado='0'
                            AND C.plan_id=D.plan_id
                            AND C.numerodecuenta=E.numerodecuenta
                            AND E.prefijo=F.prefijo
                            AND E.factura_fiscal=F.factura_fiscal
                            AND F.fecha_registro LIKE '" . $_SESSION['FACTURACION']['reportes_soat']['periodorec'] . "%'
                            AND F.total_factura<=" . $_SESSION['FACTURACION']['reportes_soat']['salariomon'] . "
                            ORDER BY A.ingreso;";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $i = 0;
                while (!$resulta->EOF) {
                    $datosreporte[$i] = $resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                    $resu1 = explode(' ', $datosreporte[$i]['fecha_ingreso']);
                    $resu2 = explode(' ', $datosreporte[$i]['fecha_cierre']);
                    $resu3 = explode(' ', $datosreporte[$i]['fecha_registro']);
                    $datosreporte[$i]['fecha_ingreso'] = $resu1[0];
                    $datosreporte[$i]['fecha_cierre'] = $resu2[0];
                    $datosreporte[$i]['fecha_registro'] = $resu3[0];
                    $i++;
                }
            }
        }
        if ($_SESSION['FACTURACION']['reportes_soat']['fechainici'] <> NULL AND $_SESSION['FACTURACION']['reportes_soat']['fechafinal'] <> NULL) {
            $j = 0;
            for ($i = 0; $i < sizeof($datosreporte); $i++) {
                /*                  if($datosreporte[$i]['fecha_registro']>=$_SESSION['FACTURACION']['reportes_soat']['fechainici']
                  AND $datosreporte[$i]['fecha_registro']<=$_SESSION['FACTURACION']['reportes_soat']['fechafinal'])
                  { */
                $datosdefinit[$j] = $datosreporte[$i];
                $j++;
//                  }
            }
        }
        if (sizeof($datosdefinit) > 0) {
            /* $query ="SELECT NEXTVAL ('soat_fosyga_id_seq');";
              $resulta = $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0)
              {
              $this->frmError["MensajeError"]="OCURRI�UN ERROR AL GENERAR EL N�ERO DE REMISI�";
              $_SESSION['FACTURACION']['reportes_soat']['numeroradi']='';
              }
              else
              {
              $_SESSION['FACTURACION']['reportes_soat']['numeroradi']=print str_pad($resulta->fields[0], 6, "0", STR_PAD_LEFT);
              echo $_SESSION['FACTURACION']['reportes_soat']['numeroradi'];
              } */
            $_SESSION['FACTURACION']['reportes_soat']['numeroradi'] = 1;
            $_SESSION['FACTURACION']['reportes_soat']['numeroradi'] = str_pad($_SESSION['FACTURACION']['reportes_soat']['numeroradi'], 6, "0", STR_PAD_LEFT); //print
        } else {
            $this->frmError["MensajeError"] = "LA CONSULTA NO ARROJ�RESULTADOS";
        }
        $_SESSION['FACTURACION']['reportes_soat']['datovector'] = $datosdefinit;
        if ($_POST['fechadradi'] == NULL || $_POST['periodorec'] == NULL ||
                $_POST['fechainici'] == NULL || $_POST['fechafinal'] == NULL ||
                empty($_SESSION['FACTURACION']['reportes_soat']['numeroradi'])) {
            if ($this->frmError["MensajeError"] == NULL) {
                $this->frmError["MensajeError"] = "FECHA(S) CON FORMATO(S) NO V�IDO(S) O VALOR(ES) VACIO(S)";
            }
            $this->uno = 1;
            $this->FormaDatosInformeSoat();
            return true;
        } else {
            $_SESSION['FACTURACION']['reportes_soat']['envio'] = $_REQUEST['envio'];
            //$this->GenerarInformeSoat();
            $this->FormaReportesSoat();
            return true;
        }
    }

    function SalarioAnoSoat($ano) {//Funci� que establece el saldo inicial al crear el evento
        list($dbconn) = GetDBconn();
        $query = "SELECT salario_mes
                    FROM salario_minimo_ano
                    WHERE ano='" . $ano . "';";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Seleccionar salario_minimo_ano";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $salmin = doubleval($resulta->fields[0]);
        $salmin = number_format(($salmin / 4), 2, '.', '');
        return $salmin;
    }

    function BuscarDatosInformeSoat() {//
        $var['fechadradi'] = $_SESSION['FACTURACION']['reportes_soat']['fechadradi'];
        $var['numeroradi'] = $_SESSION['FACTURACION']['reportes_soat']['numeroradi'];
        $var['periodorec'] = $_SESSION['FACTURACION']['reportes_soat']['periodorec'];
        $var['fechainici'] = $_SESSION['FACTURACION']['reportes_soat']['fechainici'];
        $var['fechafinal'] = $_SESSION['FACTURACION']['reportes_soat']['fechafinal'];
        $var['datovector'] = $_SESSION['FACTURACION']['reportes_soat']['datovector'];
        $var['salariomon'] = $_SESSION['FACTURACION']['reportes_soat']['salariomon'];
        $var['envio'] = $_SESSION['FACTURACION']['reportes_soat']['envio'];

        $var['empresa'] = $_SESSION['FACTURACION']['EMPRESA'];
        UNSET($_SESSION['FACTURACION']['reportes_soat']);
        return $var;
    }

    function TraerFechasEnvio($envio) {
        list($dbconn) = GetDBconn();
        $query = "SELECT fecha_inicial, fecha_final,
                                        fecha_radicacion, fecha_registro
                                FROM envios
                                WHERE envio_id=" . $envio . ";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Seleccionar fechas envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $fechas = $resulta->GetRowAssoc();
        return $fechas;
    }

    /**
     * Cambia el formato de la fecha de YYYY-mm-dd hh:mm:ss a dd/mm/YYYY
     * @access private
     * @return string
     * @param date fecha
     * @var    cad   Cadena con el nuevo formato de la fecha
     */
    function FormatoFecha($f) {
        $fecha = explode(' ', $f);

        if ($f) {
            $fech = strtok($fecha[0], "-");
            for ($i = 0; $i < 3; $i++) {
                $date[$i] = $fech;
                $fech = strtok("-");
            }
            $cad = $date[2] . "/" . $date[1] . "/" . $date[0];
            return $cad;
        }
    }

    /*     * ******************************************************************
     *
     * ******************************************************************** */

    function ObtenerPrefijos() {
        //echo SessionGetVar("DocumentosFacturacion");
        $fct = new app_Facturacion_Permisos();
        $prefijo = $fct->ObtenerPrefijosFacturas(null, SessionGetVar("EmpresaFacturacion"));
        return $prefijo;
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
        $this->action1 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarTerceros', array("empresa" => $this->Emp));
        if ($this->pst['tercero_id'] || $this->pst['nombre_tercero'] || $this->pst['tipo_id_tercero']) {
            $fct = new app_Facturacion_Permisos();
            $this->pst['empresa'] = $this->rqs['empresa'];
            $this->Terceros = $fct->ObtenerFacturasTerceros($this->pst, $this->Emp, SessionGetVar("DocumentosFacturacion"), $this->rqs['offset']);

            $this->conteo = $fct->conteo;
            $this->paginaActual = $fct->paginaActual;
            $this->action2 = ModuloGetURL('app', 'Facturacion_Fiscal', 'user', 'FormaBuscarTerceros', $this->pst);
            if (empty($this->Terceros))
                $this->frmError['MensajeError'] = "LA BUSQUEDA NO ARROJO NINGUN RESULTADO";
        }
    }

    function TraerReportesHojaCargos() {
        list($dbconn) = GetDBconn();
        $query = "SELECT ruta_reporte, titulo
                      FROM 	reportes_facturas_clientes_planes
                      WHERE empresa_id='" . $_SESSION['CUENTAS']['EMPRESA'] . "'
                      AND sw_hoja_cargos = '1';";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Seleccionar fechas envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $vars[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $vars;
    }

    function TraerRecibosCaja($numerocuenta) {
        list($dbconn) = GetDBconn();
        $query = "SELECT b.*
                      FROM   rc_detalle_hosp as a,recibos_caja as b
                      WHERE  a.numerodecuenta=" . $numerocuenta . "
                      AND    b.recibo_caja=a.recibo_caja
                      AND    b.centro_utilidad=a.centro_utilidad
                      AND    b.empresa_id=a.empresa_id ";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Seleccionar fechas envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $vars[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $vars;
    }

    function TraerRecibosDevolucion($numerocuenta) {
        list($dbconn) = GetDBconn();
        $query = "SELECT a.*
                      FROM   rc_devoluciones a
                      WHERE  a.numerodecuenta=" . $numerocuenta . "
                       ";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Seleccionar fechas envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $vars[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $vars;
    }

    /* function TraerRecibosPagare($numerocuenta)
      {
      list($dbconn) = GetDBconn();
      $query = "SELECT a.*
      FROM   rc_detalle_pagare a,recibos_caja as b
      WHERE  a.numerodecuenta=".$numerocuenta."
      AND    b.recibo_caja=a.recibo_caja
      AND    b.centro_utilidad=a.centro_utilidad
      AND    b.empresa_id=a.empresa_id
      ";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
      $this->error = "Error al Seleccionar fechas envios";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      while(!$result->EOF)
      {
      $vars[]=$result->GetRowAssoc($ToUpper = false);
      $result->MoveNext();
      }
      $result->Close();
      return $vars;
      } */

    function InfoRecibosCaja($datos) {
        list($dbconn) = GetDBconn();
        $query = " SELECT  a.fecha_ingcaja,
                              a.recibo_caja,
                              a.prefijo,
                              a.caja_id,
                              a.fecha_registro,
                              a.total_abono,
                              a.total_efectivo,
                              a.total_tarjetas,
                              a.total_cheques,
                              a.total_bonos,
                              b.razon_social,
                              c.descripcion,
                              d.plan_descripcion,
                              e.usuario,
                              btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                              f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                              f.tipo_id_paciente||' '||f.paciente_id as id
                      FROM    recibos_caja a,
                              empresas b,
                              centros_utilidad c,
                              planes d,
                              system_usuarios e,
                              pacientes f
                      WHERE   a.recibo_caja='" . $datos['recibo_caja'] . "'
                      AND     a.prefijo='" . $datos['prefijo'] . "'
                      AND     a.empresa_id=b.empresa_id
                      AND     c.empresa_id='" . $datos['empresa'] . "'
                      AND     c.centro_utilidad='" . $datos['centro_utilidad'] . "'
                      AND     d.plan_id='" . $datos['plan_id'] . "'
                      AND     a.usuario_id=e.usuario_id
                      AND     f.tipo_id_paciente='" . $datos['tipoid'] . "'
                      AND     f.paciente_id='" . $datos['pacienteid'] . "'
                      AND     a.caja_id='" . $datos['caja_id'] . "'
                      AND     a.estado IN ('0')
                    ";

        $resulta = $dbconn->execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $var = $resulta->GetRowAssoc($ToUpper = false);
        return $var;
    }

    function InfoRecibosDevolucion($datos) {

        list($dbconn) = GetDBconn();
        $query = " SELECT  a.recibo_caja,
                              a.prefijo,
                              a.caja_id,
                              a.fecha_registro,
                              a.total_devolucion,
                              b.razon_social,
                              c.descripcion,
                              d.plan_descripcion,
                              e.usuario,
                              btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                              f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                              f.tipo_id_paciente||' '||f.paciente_id as id
                       FROM   rc_devoluciones a,
                              empresas b,
                              centros_utilidad c,
                              planes d,
                              system_usuarios e,
                              pacientes f
                       WHERE  a.recibo_caja='" . $datos['recibo_caja'] . "'
                       AND    a.prefijo='" . $datos['prefijo'] . "'
                       AND    a.empresa_id=b.empresa_id
                       AND    c.empresa_id='" . $datos['empresa'] . "'
                       AND    c.centro_utilidad='" . $datos['centro_utilidad'] . "'
                       AND    d.plan_id='" . $datos['plan_id'] . "'
                       AND    a.usuario_id=e.usuario_id
                       AND    f.tipo_id_paciente='" . $datos['tipoid'] . "'
                       AND    f.paciente_id='" . $datos['pacienteid'] . "'
                       AND    a.caja_id='" . $datos['caja_id'] . "' 
                    ";

        $resulta = $dbconn->execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }
        $var = $resulta->GetRowAssoc($ToUpper = false);
        return $var;
    }

    function GetRecaudoRecibosNotas($offset) {
        list($dbconn) = GetDBconn();
        $query = "SELECT RC.*, T.nombre_tercero
											FROM recibos_caja RC,
														rc_detalle_hosp RCDH,
														cuentas C,
														planes P,
														terceros T
											WHERE RC.fecha_registro::date >= now()::date
											AND RC.fecha_registro::date >= now()::date
											AND RC.empresa_id = RCDH.empresa_id
											AND RC.centro_utilidad = RCDH.centro_utilidad
											AND RC.recibo_caja = RCDH.recibo_caja
											AND RC.prefijo = RCDH.prefijo
											AND RCDH.numerodecuenta = C.numerodecuenta
											AND C.plan_id = P.plan_id
											AND P.tercero_id = T.tercero_id
											AND P.tipo_tercero_id = T.tipo_id_tercero
											AND RC.estado IN ('0');"; //activo
        //***********************************
        $db = new app_Facturacion_Permisos();
        //$sql2 = "SELECT COUNT(*) FROM ($query) AS A ";
        //$db->ProcesarSqlConteo($sql2,null,$offset);
        //$query .= "LIMIT ".$this->limit." OFFSET ".$db->offset;
        //$this->conteo = $db->conteo;
        //$this->paginaActual = $db->paginaActual;
        if (!$result = $db->ConexionBaseDatos($query))
            return false;
        //***********************************
        while (!$result->EOF) {
            $vars[RECIBOS][] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        $query = "SELECT RCD.*, T.nombre_tercero
											FROM rc_devoluciones RCD,
														cuentas C,
														planes P,
														terceros T
											WHERE RCD.fecha_registro::date >= now()::date
											AND RCD.fecha_registro::date <= now()::date
											AND RCD.numerodecuenta = C.numerodecuenta
											AND C.plan_id = P.plan_id
											AND P.tercero_id = T.tercero_id
											AND P.tipo_tercero_id = T.tipo_id_tercero
											AND RCD.estado IN ('0');";
        //***********************************
        //$sql2 = "SELECT COUNT(*) FROM ($query) AS A ";
        //$db->ProcesarSqlConteo($sql2,null,$offset);
        //$query .= "LIMIT ".$this->limit." OFFSET ".$db->offset;
        //$this->conteo = $db->conteo;
        //$this->paginaActual = $db->paginaActual;
        if (!$result = $db->ConexionBaseDatos($query))
            return false;
        //***********************************
        while (!$result->EOF) {
            $vars[DEVOLUCIONES][] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();

        $query = "SELECT PG.*, TFP.descripcion AS forma_pago,
														T.nombre_tercero
											FROM pagares PG, tipos_formas_pago TFP,
													cuentas C,planes P, terceros T
											WHERE PG.fecha_registro::date >= now()::date
											AND PG.fecha_registro::date <= now()::date
											AND PG.tipo_forma_pago_id = TFP.tipo_forma_pago_id
											AND PG.numerodecuenta= C.numerodecuenta
											AND C.plan_id = P.plan_id
											AND P.tercero_id = T.tercero_id
											AND P.tipo_tercero_id = T.tipo_id_tercero
											AND PG.sw_estado IN ('1');"; //ACTIVO
        //***********************************
        //$sql2 = "SELECT COUNT(*) FROM ($query) AS A ";
        //$db->ProcesarSqlConteo($sql2,null,$offset);
        //$query .= "LIMIT ".$this->limit." OFFSET ".$db->offset;
        //$this->conteo = $db->conteo;
        //$this->paginaActual = $db->paginaActual;
        if (!$result = $db->ConexionBaseDatos($query))
            return false;
        //***********************************
        while (!$result->EOF) {
            $vars[PAGARES][] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $vars;
    }

    //
    function GetDatosComprobanteDiario($offset) {
        list($dbconn) = GetDBconn();
        $query = "(SELECT FF.prefijo,
														FF.factura_fiscal,
														--FF.sw_clase_factura, 0 contado, 1 credito
														CASE WHEN FF.sw_clase_factura = '0' THEN 'CONTADO'
																	WHEN FF.sw_clase_factura = '1' THEN 'CREDITO' END AS sw_clase_factura,
														FF.fecha_registro as fecha_factura,
														FF.tipo_factura,
														FF.usuario_id,
														FF.total_factura AS valor,
														SU.nombre,
														PL.plan_descripcion AS cliente,
														P.primer_nombre||' '||P.segundo_nombre||' '||P.primer_apellido||' '||P.segundo_apellido AS paciente, 
														--C.*
														C.abono_efectivo,
														C.abono_cheque,
														C.abono_tarjetas,
														C.abono_chequespf,
														C.abono_letras
											FROM fac_facturas AS FF,
													--LEFT JOIN tarjetasf_mov_debito TMD ON (FF.empresa_id = TMD.empresa_id
													--                                       AND FF.factura_fiscal = TMD.factura_fiscal
													--                                       AND FF.prefijo = TMD.prefijo)
													--LEFT JOIN tarjetasf_mov_credito TMC ON (FF.empresa_id = TMC.empresa_id
													--                                      AND FF.factura_fiscal = TMC.factura_fiscal
													--                                       AND FF.prefijo = TMC.prefijo)
													--LEFT JOIN chequesf_mov CM ON (FF.empresa_id = CM.empresa_id
													--                                       AND FF.factura_fiscal = CM.factura_fiscal
														--                                      AND FF.prefijo = CM.prefijo),
													fac_facturas_cuentas FFC
													JOIN cuentas C ON (FFC.numerodecuenta = C.numerodecuenta
																									AND C.estado IN ('0')),
													system_usuarios SU,
													ingresos I, 
													pacientes P, 
													planes PL
											WHERE FF.fecha_registro::date >= now()::date 
											AND FF.fecha_registro::date <= now()::date
											--WHERE FF.fecha_registro::date >= '2007-07-01'
											--AND FF.fecha_registro::date <= '2007-07-03'
											AND FF.usuario_id =  SU.usuario_id
											AND FF.estado IN ('0','1') --FACTURADO = 0 PAGADO = 1
											AND FF.empresa_id = FFC.empresa_id
											AND FF.prefijo = FFC.prefijo
											AND FF.factura_fiscal = FFC.factura_fiscal
											AND FF.tipo_factura NOT IN ('4')
											AND		C.ingreso = I.ingreso 
											AND		I.tipo_id_paciente = P.tipo_id_paciente 
											AND		I.paciente_id = P.paciente_id
											AND		C.plan_id = PL.plan_id
											AND		FF.plan_id = PL.plan_id
											ORDER BY FF.sw_clase_factura,FF.fecha_registro)
									UNION
									(SELECT FF.prefijo,
														FF.factura_fiscal,
														--FF.sw_clase_factura, 0 contado, 1 credito
														CASE WHEN FF.sw_clase_factura = '0' THEN 'CONTADO'
																	WHEN FF.sw_clase_factura = '1' THEN 'CREDITO' END AS sw_clase_factura,
														FF.fecha_registro as fecha_factura,
														FF.tipo_factura,
														FF.usuario_id,
														FF.total_factura AS valor,
														SU.nombre,
														PL.plan_descripcion AS cliente,
														'FACTURA AGRUPADA' AS paciente,
														--C.*
														0 AS abono_efectivo,
														0 AS abono_cheque,
														0 AS abono_tarjetas,
														0 AS abono_chequespf,
														0 AS abono_letras
											FROM fac_facturas AS FF,
													system_usuarios SU, 
													planes PL 

											WHERE FF.fecha_registro::date >= '2008-03-14'
											AND FF.fecha_registro::date <= '2008-03-14'
											AND FF.usuario_id =  SU.usuario_id
											AND FF.estado IN ('0','1') --FACTURADO = 0 PAGADO = 1
											AND FF.tipo_factura IN ('4')
											AND		FF.plan_id = PL.plan_id
											ORDER BY FF.sw_clase_factura,FF.fecha_registro
											) ";
        //***********************************
        $db = new app_Facturacion_Permisos();
        //$sql2 = "SELECT COUNT(*) FROM ($query) AS A ";
        //$db->ProcesarSqlConteo($sql2,null,$offset);
        //$query .= "LIMIT ".$this->limit." OFFSET ".$db->offset;
        //$this->conteo = $db->conteo;
        //$this->paginaActual = $db->paginaActual;
        if (!$result = $db->ConexionBaseDatos($query))
            return false;
        //***********************************
        while (!$result->EOF) {
            $vars[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $vars;
    }

    function GetFacturacionNotasAnulacion($offset) {
        list($dbconn) = GetDBconn();
        $query = "SELECT FF.prefijo,
														FF.factura_fiscal,
														--FF.sw_clase_factura, 0 contado, 1 credito
														CASE WHEN FF.sw_clase_factura = '0' THEN 'CONTADO'
																	WHEN FF.sw_clase_factura = '1' THEN 'CREDITO' END AS sw_clase_factura,
														FF.fecha_registro as fecha_factura,
														FF.tipo_factura,
														FF.usuario_id,
														FF.total_factura AS valor,
														FF.estado AS estado_factura,
														SU.nombre,
														PL.plan_descripcion AS cliente,
														P.primer_nombre||' '||P.segundo_nombre||' '||P.primer_apellido||' '||P.segundo_apellido AS paciente, 
														C.*,
														NCAF.prefijo AS prefijo_nota,
														NCAF.nota_credito_id,
														NCAF.prefijo_factura,
														NCAF.factura_fiscal AS factura_fiscal_nota,
														NCAF.valor_nota,
														NCAF.fecha_registro AS fecha_registro_nota,
														USU.nombre AS usuario_nota
											FROM fac_facturas AS FF
													LEFT JOIN notas_credito_anulacion_facturas NCAF
													ON (FF.empresa_id = NCAF.empresa_id
													    AND FF.factura_fiscal = NCAF.factura_fiscal
													    AND FF.prefijo = NCAF.prefijo_factura)
													LEFT JOIN system_usuarios USU 
													ON (NCAF.usuario_id = USU.usuario_id),
													fac_facturas_cuentas FFC
													JOIN cuentas C ON (FFC.numerodecuenta = C.numerodecuenta
																						AND C.estado IN ('0')),
													system_usuarios SU,
													ingresos I, 
													pacientes P, 
													planes PL
											WHERE FF.fecha_registro::date >= now()::date 
											AND FF.fecha_registro::date <= now()::date
											--WHERE FF.fecha_registro::date >= '2007-06-29'
											--AND FF.fecha_registro::date <= '2007-06-30'
											AND FF.usuario_id = SU.usuario_id
											AND FF.estado IN ('0','1','3') --FACTURADO = 0 PAGADO = 1 ANULADA CON NOTA = 3
											AND FF.empresa_id = FFC.empresa_id
											AND FF.prefijo = FFC.prefijo
											AND FF.factura_fiscal = FFC.factura_fiscal
											AND C.ingreso = I.ingreso 
											AND I.tipo_id_paciente = P.tipo_id_paciente 
											AND I.paciente_id = P.paciente_id
											AND C.plan_id = PL.plan_id
											AND FF.plan_id = PL.plan_id
											ORDER BY FF.sw_clase_factura,FF.fecha_registro ";
        //***********************************
        $db = new app_Facturacion_Permisos();
        //$sql2 = "SELECT COUNT(*) FROM ($query) AS A ";
        //$db->ProcesarSqlConteo($sql2,null,$offset);
        //$query .= "LIMIT ".$this->limit." OFFSET ".$db->offset;
        //$this->conteo = $db->conteo;
        //$this->paginaActual = $db->paginaActual;
        if (!$result = $db->ConexionBaseDatos($query))
            return false;
        //***********************************
        while (!$result->EOF) {
            $vars[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $vars;
    }

    /**
     * **
     * */
    function LlamaFrmEliminarPendientesXCargar() {
        IncludeClass('CargosPendientesPorCargarHTML', '', 'app', 'Facturacion_Fiscal');
        $objeto = new CargosPendientesPorCargarHTML();
        $Cuenta = $_REQUEST[Cuenta];
        $accionE = ModuloGetUrl("app", "Facturacion_Fiscal", "user", "LlamaEliminarCargoPendiente", array('EmpresaId' => $_REQUEST[EmpresaId], 'CentroUtilidad' => $_REQUEST[CentroUtilidad], 'Cuenta' => $_REQUEST[Cuenta], 'cargo_cups' => $_REQUEST[cargo_cups], 'procedimiento_pendiente_cargar_id' => $_REQUEST[procedimiento_pendiente_cargar_id], 'TipoId' => $_REQUEST[TipoId], 'PacienteId' => $_REQUEST[PacienteId], 'Nivel' => $_REQUEST[Nivel], 'PlanId' => $_REQUEST[PlanId], 'Cama' => $_REQUEST[Cama], 'Fecha' => $_REQUEST[Fecha], 'Ingreso' => $_REQUEST[Ingreso], 'Transaccion' => $_REQUEST['Transaccion'], 'Pieza' => $_REQUEST['Pieza']));
        $accionC = ModuloGetUrl("app", "Facturacion_Fiscal", "user", "Facturacion", array('Cuenta' => $_REQUEST[Cuenta], 'TipoId' => $_REQUEST[TipoId], 'PacienteId' => $_REQUEST[PacienteId], 'Nivel' => $_REQUEST[Nivel], 'PlanId' => $_REQUEST[PlanId], 'Cama' => $_REQUEST[Cama], 'Fecha' => $_REQUEST[Fecha], 'Ingreso' => $_REQUEST[Ingreso], 'Transaccion' => $_REQUEST['Transaccion'], 'Pieza' => $_REQUEST['Pieza']));
        $this->salida = $objeto->FrmEliminarPendientesXCargar($Cuenta, $accionE, $accionC, $mensaje);
        return true;
    }

    /**
     * **
     * */
    function LlamaEliminarCargoPendiente() {
        IncludeClass('CargosPendientesPorCargar', '', 'app', 'Facturacion_Fiscal');
        $objeto = new CargosPendientesPorCargar();
        $this->salida = $objeto->EliminarCargoPendiente(&$this);
        return true;
    }

    /**
     *
     */
    function LlamaActivarCuentaCuadrada() {
        $fact = new Facturar();
        $fact->ActivarCuentaCuadrada(&$this, $_REQUEST[Cuenta]);
        return true;
    }

    /**
     * **@ retunr boolean
     * */
    function AplicarDescuento() {
        list($dbconn) = GetDBconn();
        $query = "UPDATE envios
											SET porcentaje_descuento = " . $_REQUEST["Descuento"] . "
											WHERE envio_id = " . $_REQUEST["envio_id"] . ";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al actualizar porcentaje_descuento";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->DetalleEnvio($_REQUEST["envio_id"]);
        return true;
    }

    function Servicios() {
        $EmpresaId = $_SESSION['FACTURACION']['EMPRESA'];
        $CentroU = $_SESSION['FACTURACION']['CENTROUTILIDAD'];
        if ($CentroU) {
            $CU = "and centro_utilidad='$CentroU'";
        }

        list($dbconn) = GetDBconn();
        $query = "SELECT DISTINCT b.servicio,b.descripcion
									FROM departamentos as a,
												servicios as b
									WHERE a.empresa_id='$EmpresaId'
									AND a.servicio=b.servicio
									AND b.sw_asistencial = '1'";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __line__ . "]";
            return false;
        } else {
            if ($result->EOF) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla maestra 'departamentos y servicios' esta vacia ";
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

    function TiposUsuarios() {
        list($dbconn) = GetDBconn();
        $query = "SELECT *
									FROM  tipos_condicion_usuarios_planes;";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __line__ . "]";
            return false;
        } else {
            if ($result->EOF) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'tipos_condicion_usuarios_planes' esta vacia ";
                return false;
            }
            while (!$result->EOF) {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $vars;
    }

    function ActulizarEstado() {
        list($dbconn) = GetDBconn();
        if ($_REQUEST['Estado'] == 'A') {
            $estado = '2';
        } elseif ($_REQUEST['Estado'] == 'I') {
            $estado = '1';
        }
        $query = "UPDATE cuentas
											SET estado = '" . $estado . "'
											WHERE numerodecuenta = '" . $_REQUEST["numerodecuenta"] . "';";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al actualizar cuentas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if ($_REQUEST['Estado'] == 'A')
            $_REQUEST['Estado'] = 'I';
        elseif ($_REQUEST['Estado'] == 'I')
            $_REQUEST['Estado'] = 'A';
        //'numerodecuenta'=>$Cuenta,'plan_id'=>$PlanId,'tipoid'=>$TipoId,'pacienteid'=>$PacienteId,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Transaccion'=>$Transaccion,'Dev'=>$Dev,'vars'=>$vars,'Estado'=>$Estado
        $this->FormaFacturas($_REQUEST['numerodecuenta'], $_REQUEST['tipoid'], $_REQUEST['pacienteid'], $_REQUEST['plan_id'], $_REQUEST['Nivel'], $_REQUEST['Fecha'], $_REQUEST['Ingreso'], $_REQUEST['Transaccion'], $_REQUEST['Dev'], $_REQUEST['vars'], $_REQUEST['Estado'], '', $_REQUEST['tipo_factura'], '', $_REQUEST['prefijo'], $_REQUEST['numero']);
        return true;
    }

    function ActualizaEstadoImpFactura() {
        list($dbconn) = GetDBconn();
        $query = "UPDATE fac_facturas
											SET sw_imp_copia = '0'
											WHERE prefijo = '" . $_REQUEST["prefijo"] . "'
											AND factura_fiscal = " . $_REQUEST["numero"] . ";";

        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al actualizar cuentas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->FormaFacturas($_REQUEST['numerodecuenta'], $_REQUEST['tipoid'], $_REQUEST['pacienteid'], $_REQUEST['plan_id'], $_REQUEST['Nivel'], $_REQUEST['Fecha'], $_REQUEST['Ingreso'], $_REQUEST['Transaccion'], $_REQUEST['Dev'], $_REQUEST['vars'], $_REQUEST['Estado'], '', $_REQUEST['tipo_factura'], '', $_REQUEST['prefijo'], $_REQUEST['numero']);
        return true;
    }

//

    function GetDatosTipoReporte() {
        list($dbconn) = GetDBconn();
        $query = "SELECT *
									FROM  reportes_envios";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al cargar reportes_envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }
        while (!$result->EOF) {
            $vars[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        return $vars;
    }

    function LlamaFrmVerReporteEnvio() {
        list($dbconn) = GetDBconn();
        $query = "SELECT *
									FROM reportes_envios
									WHERE reportes_envios_id = " . $_REQUEST[tiporeporte] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al cargar reportes_envios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }
        if (!$result->EOF) {
            $vars = $result->GetRowAssoc($ToUpper = false);
        }
        $this->FrmVerReporteEnvio($_REQUEST[envio], $vars);
        return true;
    }

    function ConsultaEstadoImpFactura() {
        list($dbconn) = GetDBconn();
        $query = "SELECT sw_imp_copia
									FROM fac_facturas
									WHERE prefijo = '" . $_REQUEST[prefijo] . "'
									AND factura_fiscal = " . $_REQUEST[numero] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al consultar el estado de impresion de l factura";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }
        while (!$result->EOF) {
            $vars[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        return $vars;
    }

    function PermisoCambioEstadoImpFactura() {
        list($dbconn) = GetDBconn();
        $query = "SELECT *
                  FROM userpermisos_facturacion
                  WHERE usuario_id = " . UserGetUID() . "
                    AND sw_cambio_imp_factura = '1';";

        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __line__ . "]";
            return false;
        } else {
            if ($result->EOF) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Metodo donde se obtienen los terceros que poseen planes que facturan de manera
     * agrupada
     *
     * @param string $empresa Identificador de la empresa
     *
     * @return mixed
     */
    function ObtenerTercerosPlanesAgrupados($empresa, $plan) {
        $sql = "SELECT DISTINCT a.tipo_tercero_id,";
        $sql .= "       a.tercero_id,";
        $sql .= "       b.nombre_tercero ";
        $sql .= "FROM   planes a, ";
        $sql .= "       terceros b ";
        $sql .= "WHERE  a.tipo_tercero_id=b.tipo_id_tercero ";
        $sql .= "AND    a.tercero_id = b.tercero_id ";
        $sql .= "AND    a.estado='1' ";
        $sql .= "AND    a.sw_facturacion_agrupada='1' ";
        $sql .= "AND    a.fecha_final >= now() ";
        $sql .= "AND    a.fecha_inicio <= now() ";
        $sql .= "AND    a.empresa_id = '" . $empresa . "' ";
        if ($plan != "")
            $sql .= "AND    a.plan_id =  " . $plan . " ";

        $sql .= "ORDER BY b.nombre_tercero ";

        $cxn = new ConexionBD();

        if (!$rst = $cxn->ConexionBaseDatos($sql))
            return false;

        $datos = array();

        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }
    function Tipo_AfiliadoS($plan) {
        list($dbconn) = GetDBconn();
        $where = "";
        if (!(empty($plan))) {
            $where = " WHERE b.plan_id='" . $plan . "'";
        }

        $query = " SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id ";
        $query .= " FROM tipos_afiliado as a INNER JOIN planes_rangos as b ON (b.tipo_afiliado_id=a.tipo_afiliado_id) ";
        $query .= $where;

        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while (!$resulta->EOF) {
            $vars[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        $resulta->Close();
        return $vars;
    }

}

//fin clase user
?>