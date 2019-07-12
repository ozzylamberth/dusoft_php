<?php

/* * ************************************************************************************  
 * $Id: app_RecibosCaja_user.php,v 1.3 2010/04/12 19:35:44 hugo Exp $ 
 * 
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS-FI
 * 
 * $Revision: 1.3 $ 
 * 
 * @autor Hugo F  Manrique 
 * ************************************************************************************* */
IncludeClass('app_RecibosCaja_Funciones', '', 'app', 'RecibosCaja');

class app_RecibosCaja_user extends classModulo {

    function app_RecibosCaja_user()
    {
        return true;
    }

    /*     * ******************************************************************************** 
     * Función principal del módulo 
     * 
     * @access private 
     * ********************************************************************************* */

    function main()
    {
        $this->MostrarMenuEmpresasRC();
        return true;
    }

    /*     * *********************************************************************************
     * Muestra el menu de las empresas y centros de utilidad 
     * 
     * @access public 
     * ********************************************************************************* */

    function MostrarMenuEmpresasRC()
    {
        unset($_SESSION['RCFactura']);

        $Empresas = $this->BuscarEmpresasUsuario();
        $titulo[0] = 'EMPRESAS';

        $url[0] = 'app';          //contenedor 
        $url[1] = 'RecibosCaja';      //módulo 
        $url[2] = 'user';          //clase 
        $url[3] = 'MostrarMenuPrincipalRC'; //método 
        $url[4] = 'permisos_rc';      //indice del request

        $accion = ModuloGetURL('system', 'Menu');
        $forma .= gui_theme_menu_acceso("RECIBOS DE CAJA", $titulo, $Empresas, $url, $accion);

        $this->FormaMostrarMenuEmpresasRC($forma);
        return true;
    }

    /*     * **********************************************************************************   
     * Retorna las empresas a las cuales se les ha dado permiso de usar este modulo 
     * 
     * @access public 
     * @return array 
     * *********************************************************************************** */

    function BuscarCentroUtilidad($caja)
    {
        $sql = "SELECT centro_utilidad ";
        $sql .= "FROM		cajas ";
        $sql .= "WHERE	caja_id = " . $caja . " ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $centro_utilidad[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $centro_utilidad;
    }

    function var_empresas()
    {
        $sql = "SELECT valor ";
        $sql .= "FROM		system_modulos_variables ";
        $sql .= "WHERE	variable = 'sw_todas_empresas'";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $var_empresas[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $var_empresas;
    }

    function BuscarEmpresasUsuario()
    {
        $sql = "SELECT E.empresa_id AS empresa,";
        $sql .= "				E.razon_social AS razon_social,";
        $sql .= "				RC.caja_id AS caja ";
        $sql .= "FROM		userpermisos_recibos_caja RC,empresas E ";
        $sql .= "WHERE	RC.usuario_id = " . UserGetUID() . " ";
        $sql .= "AND		RC.empresa_id = E.empresa_id";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $empresas[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $empresas;
    }

    /*     * *************************************************************************************
     * Funcion que permite mostrar el menu de los recibos de caja 
     * 
     * @return boolean 
     * ************************************************************************************** */

    function MostrarMenuPrincipalRC()
    {

        if (empty($_SESSION['RCFactura']['empresa']))
        {
            $_SESSION['RCFactura']['caja'] = $_REQUEST['permisos_rc']['caja'];
            $_SESSION['RCFactura']['empresa'] = $_REQUEST['permisos_rc']['empresa'];
            $_SESSION['RCFactura']['nombreE'] = $_REQUEST['permisos_rc']['razon_social'];
        }

        unset($_SESSION['SqlContar']);
        unset($_SESSION['SqlBuscar']);

        $rcf = new app_RecibosCaja_Funciones();
        $centro_utilidad = $this->BuscarCentroUtilidad($_SESSION['RCFactura']['caja']);
        $centro = $centro_utilidad['']['centro_utilidad'];
        $this->menus = $rcf->ObtenerMenuRecibos($_SESSION['RCFactura']['empresa'], $centro);

        /* echo '<pre>menus';
          print_r($this->menus);
          echo '</pre>'; */

        $this->accion = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarMenuEmpresasRC');
        $this->FormaMostrarMenuPrincipalRC();
        return true;
    }

    /*     * *************************************************************************************
     * Funcion que permite mostrar el listado de clientes 
     * 
     * @return boolean 
     * ************************************************************************************** */

    function MostrarClientes()
    {
        $this->request = $_REQUEST;
        $this->TerceroNombre = $this->request['nombre_tercero'];
        $this->TerceroTipoId = $this->request['tipo_id_tercero'];
        $this->TerceroDocumento = $this->request['tercero_id'];

        if ($this->request['menu'])
            SessionSetVar("Documentos", $this->request['menu']); 

        $request = array();
        $request["tercero_id"] = $this->request['tercero_id'];
        $request["nombre_tercero"] = $this->request['nombre_tercero'];
        $request["tipo_id_tercero"] = $this->request['tipo_id_tercero'];

        $this->actionB = ModuloGetURL('app', 'RecibosCaja', 'user', 'ObtenerSqlBuscarDatosCliente');
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'ObtenerSqlBuscarDatosCliente');
        $this->actionPg = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarClientes', $request);

        $this->action = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarMenuPrincipalRC');
        $this->FormaMostrarClientes();
        return true;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function BuscarTerceros()
    {
        $this->request = $_REQUEST;


        $datos['tercero_id'] = $this->request['tercero_id'];
        $datos['nombre_tercero'] = $this->request['nombre_tercero'];
        $datos['tipo_id_tercero'] = $this->request['tipo_id_tercero'];

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'FormaBuscarTerceros');
        $this->actionB = ModuloGetURL('app', 'RecibosCaja', 'user', 'FormaBuscarTerceros', $datos);
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function ObtenerTerceros()
    {
        $menu = SessionGetVar("Documentos");
        $var_empresa = $this->var_empresas();
        $sw_todas_empresas = $var_empresa['']['valor'];


        $sql .= "SELECT 	T.tipo_id_tercero,";
        $sql .= "					T.tercero_id, ";
        $sql .= "					T.nombre_tercero ";
        if ($menu['sw_cruzar_anticipos'] == '1' && $menu['sw_cruce_endosos'] == '1')
            $sql .= "       ,RA.saldo ";

        $where .= "FROM 	terceros T ";
        if ($menu['sw_cruzar_anticipos'] == '1' && $menu['sw_cruce_endosos'] == '1')
            $where .= "				,rc_control_anticipos RA ";
        $where .= "WHERE	T.tercero_id IS NOT NULL ";
        $where .= "AND		T.nombre_tercero != '' ";


        if ($menu['sw_cruzar_anticipos'] == '1' && $menu['sw_cruce_endosos'] == '1')
        {
            $where .= "AND	RA.tercero_id = T.tercero_id ";
            $where .= "AND	RA.tipo_id_tercero = T.tipo_id_tercero ";
            if ($sw_todas_empresas == '0')
            {
                $where .= "AND	RA.empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";
            }
            $where .= "AND	RA.saldo > 0 ";
        }

        if ($this->request['tipo_id_tercero'] != "" && $this->request['tipo_id_tercero'] != '0')
            $where .= "AND T.tipo_id_tercero = '" . $this->request['tipo_id_tercero'] . "' ";

        if ($this->request['nombre_tercero'] != "")
            $where .= "AND T.nombre_tercero ILIKE '%" . $this->request['nombre_tercero'] . "%' ";

        if ($this->request['tercero_id'] != "")
            $where .= "AND T.tercero_id = '" . $this->request['tercero_id'] . "' ";

        $this->ProcesarSqlConteo("SELECT COUNT(*) $where");

        $where .= "ORDER BY 3 ";
        $where .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;
//echo "sql".$sql;
//echo "where".$where;
        if (!$rst = $this->ConexionBaseDatos($sql . $where))
            return false;

        while (!$rst->EOF)
        {
            $terceros[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $terceros;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function ObtenerClientesRecibos()
    {
        $documento = ModuloGetVar('app', 'RecibosCaja', 'documento');
        $sql .= "SELECT 	RC.cantidad,";
        $sql .= "					TE.nombre_tercero,";
        $sql .= "					RC.tipo_id_tercero,";
        $sql .= "					RC.tercero_id ";
        $where .= "FROM		terceros TE,";
        $where .= "				(SELECT COUNT(*) AS cantidad,";
        $where .= "								tipo_id_tercero,";
        $where .= "								tercero_id ";
        $where .= "				 FROM   recibos_caja ";
        $where .= "				 WHERE  empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";
        $where .= "			 	 AND    documento_id = " . $documento . " ";
        $where .= "				 GROUP BY 2,3) AS RC ";
        $where .= "WHERE	TE.tipo_id_tercero = RC.tipo_id_tercero ";
        $where .= "AND		TE.tercero_id = RC.tercero_id ";

        if ($this->TerceroDocumento != "")
            $where .= "AND RC.tercero_id = '" . $this->TerceroDocumento . "' ";

        if ($this->TerceroNombre != "")
            $where .= "AND TE.nombre_tercero ILIKE '%" . $this->TerceroNombre . "%' ";

        if ($this->TerceroTipoId != "0" && $this->TerceroTipoId != "")
            $where .= "AND RC.tipo_id_tercero = '" . $this->TerceroTipoId . "' ";

        $sqlC = "SELECT DISTINCT COUNT(*) $where ";
        $this->ProcesarSqlConteo($sqlC);

        $sql .= $where;
        $sql .= "ORDER BY 2 ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $clientes[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $clientes;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function ObtenerRecibosPagados()
    {
        $documento = ModuloGetVar('app', 'RecibosCaja', 'documento');

        $sql .= "SELECT RC.prefijo,";
        $sql .= "				RC.recibo_caja,";
        $sql .= "				RC.total_abono,";
        $sql .= " 			RC.total_efectivo,";
        $sql .= " 			RC.total_cheques,";
        $sql .= " 			RC.total_tarjetas,";
        $sql .= "				RC.total_consignacion,";
        $sql .= "				TO_CHAR(RC.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
        $where .= "FROM		recibos_caja RC ";
        $where .= "WHERE	RC.tercero_id = '" . $this->Cliente[0] . "' ";
        $where .= "AND		RC.tipo_id_tercero = '" . $this->Cliente[2] . "' ";
        $where .= "AND		RC.empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";
        $where .= "AND		RC.documento_id = " . $documento . " ";

        $sqlC = "SELECT COUNT(*) $where";
        $this->ProcesarSqlConteo($sqlC);

        $sql .= "$where ";
        $sql .= "ORDER BY 2 DESC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $arreglo = array("pagina" => $_REQUEST['pagina'], "tercero_id" => $_REQUEST['tercero_id'],
            "tercero_tipo" => $_REQUEST['tercero_tipo'], "tercero_nombre" => $_REQUEST['tercero_nombre']);

        $i = 0;
        while (!$rst->EOF)
        {
            $pago = "";
            $recibos[$i] = $rst->GetRowAssoc($ToUpper = false);

            if ($recibos[$i]['total_cheques'] > 0)
                $pago .= "<li>CHEQUE " . formatoValor($recibos[$i]['total_cheques']);

            if ($recibos[$i]['total_efectivo'] > 0)
                $pago .= "<li>EFECTIVO " . formatoValor($recibos[$i]['total_efectivo']);

            if ($recibos[$i]['total_tarjetas'] > 0)
                $pago .= "<li>TARJETA " . formatoValor($recibos[$i]['total_tarjetas']);

            if ($recibos[$i]['total_consignacion'] > 0)
                $pago .= "<li>CONSIGNACIÓN " . formatoValor($recibos[$i]['total_consignacion']);

            $recibos[$i]['forma_pago'] = $pago;

            $arreglo['prefijo'] = $recibos[$i]['prefijo'];
            $arreglo['recibo_caja'] = $recibos[$i]['recibo_caja'];

            $this->action3[$i] = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarDetalleReciboCaja', $arreglo);

            $rst->MoveNext();
            $i++;
        }
        $rst->Close();

        return $recibos;
    }

    /*     * *************************************************************************************
     * Funcion que permite mostrar la interfaz de generar un RC 
     * 
     * @return boolean 
     * ************************************************************************************** */

    function GenerarReciboCaja()
    {
        $this->request = $_REQUEST;

        /* echo "<pre> Request";
          print_r($this->request);
          echo "</pre>"; */


        unset($_SESSION['SqlBuscarF']);
        unset($_SESSION['SqlContarF']);
        unset($_SESSION['SqlBuscar']);

        $this->Cliente[0] = $_REQUEST['tercero_id'];
        $this->Cliente[1] = $_REQUEST['tercero_nombre'];

        $this->menu = SessionGetVar("Documentos");

        /* echo "<pre> Menu";
          print_r($this->menu);
          echo "</pre>"; */

        if ($this->menu['sw_cruzar_anticipos'] == "1")
        {
            $rcf = new app_RecibosCaja_Funciones();
            $this->saldo_anticipo = $rcf->ObtenerSaldoAnticipos($this->request, $_SESSION['RCFactura']['empresa'], $this->menu['rc_tipo_documento']);
        }

        //FORMAS DE PAGO
        //$rcf = new app_RecibosCaja_Funciones();
        //$forma_pago_cod = $rcf->getFormasDePago();
        //$this->FormaPago = $forma_pago_cod['id_forma_pago'];
        //$this->DescripcionFormaPago = $forma_pago_cod;

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarClientes', array("offset" => $_REQUEST['pagina']));
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'EvaluarRequestGenerarRC', array("pagina" => $_REQUEST['pagina'], "tercero_id" => $_REQUEST['tercero_id'],
            "tercero_tipo" => $_REQUEST['tercero_tipo'], "tercero_nombre" => $_REQUEST['tercero_nombre']));
        $this->actionT = ModuloGetURL('app', 'RecibosCaja', 'user', 'FormaBuscarTerceros', array());
        $this->FormaGenerarReciboCaja();
        return true;
    }

    /*     * *************************************************************************************
     * Funcion que permite ingresar un recibo de caja a la base de datos, se evalua el tipo 
     * de documento con el se esta pagando y se ingresan los datos en la tabla correspondiente 
     * 
     * @return boolean 
     * ************************************************************************************** */

    function GenerarReciboCajaBD()
    {

        $caja_id = $_SESSION['RCFactura']['caja'];
        $empresa = $_SESSION['RCFactura']['empresa'];
        $centro_utilidad = $this->BuscarCentroUtilidad($caja_id);
        $centro = $centro_utilidad['']['centro_utilidad'];

        $this->menu = SessionGetVar("Documentos");

        $this->request = $_REQUEST;

        $tercero_id = $_REQUEST['tercero_id'];
        $tercero_tipo = $_REQUEST['tercero_tipo'];

        $this->ValorRC = $_REQUEST['valor_rc'];
        $this->FormaPago = $_REQUEST['forma_pago'];
        $this->Observacion = $_REQUEST['observa'];

        list($dbconn) = GetDBConn();

        $sql = "SELECT 'DC',COALESCE(MAX(tmp_recibo_id),0)+1 FROM tmp_recibos_caja ";

        $numeracion = $this->ObtenerNumeracion($sql, &$dbconn);

        switch ($this->FormaPago)
        {
            case 'CH':
                $insert .= "	total_cheques, ";
                $sql2 .= "INSERT INTO tmp_cheques_mov_rc(";
                $sql2 .= "		cheque_mov_id , ";
                $sql2 .= "		empresa_id , ";
                $sql2 .= "		centro_utilidad , ";
                $sql2 .= "		recibo_caja, ";
                $sql2 .= "		banco , ";
                $sql2 .= "		cheque, ";
                $sql2 .= "		girador, ";
                $sql2 .= "		fecha_cheque, ";
                $sql2 .= "		total, ";
                $sql2 .= "		fecha , ";
                $sql2 .= "		estado, ";
                $sql2 .= "		usuario_id, ";
                $sql2 .= "		fecha_registro, ";
                $sql2 .= "		consecutivo, ";
                $sql2 .= "		cta_cte )";
                $sql2 .= "VALUES(";
                $sql2 .= "			(SELECT COALESCE(MAX(cheque_mov_id),0)+1 FROM tmp_cheques_mov_rc),";
                $sql2 .= "	   '" . $empresa . "', ";
                $sql2 .= "	   '" . $centro . "', ";
                $sql2 .= "		" . $numeracion[1] . ", ";
                $sql2 .= "	   '" . $this->BancoS . "',";
                $sql2 .= "	   '" . $this->NumeroCheque . "',";
                $sql2 .= "	   '" . $this->Girador . "', ";
                $sql2 .= "	   '" . $this->FechaCheque . "', ";
                $sql2 .= "	   '" . $this->ValorRC . "', ";
                $sql2 .= "	   '" . $this->FechaTransaccion . "', ";
                $sql2 .= "		 '0',";
                $sql2 .= "		" . UserGetUID() . ", ";
                $sql2 .= "		 NOW(),";
                $sql2 .= "		 (SELECT COALESCE(MAX(consecutivo),0)+1 FROM tmp_cheques_mov_rc),";
                $sql2 .= "	   '" . $this->NumeroCuenta . "' ";
                $sql2 .= "	); ";

                break;
            case 'CO':
                $insert .= "	total_consignacion, ";
                $sql2 .= "INSERT INTO tmp_bancos_consignaciones(";
                $sql2 .= "			tmp_banco_id,";
                $sql2 .= "			tmp_recibo_id,";
                $sql2 .= "			empresa_id , ";
                $sql2 .= "			centro_utilidad , ";
                $sql2 .= "			numero_cuenta, ";
                $sql2 .= "			valor, ";
                $sql2 .= "			numero_transaccion, ";
                $sql2 .= "			fecha_transaccion)";
                $sql2 .= "VALUES(";
                $sql2 .= "			(SELECT COALESCE(MAX(tmp_banco_id),0)+1 FROM tmp_bancos_consignaciones),";
                $sql2 .= "			" . $numeracion[1] . ", ";
                $sql2 .= "	   '" . $empresa . "', ";
                $sql2 .= "	   '" . $centro . "', ";
                $sql2 .= "	   '" . $this->NumeroCuenta . "',";
                $sql2 .= "	   '" . $this->ValorRC . "', ";
                $sql2 .= "	   '" . $this->NumeroTransaccion . "', ";
                $sql2 .= "	   '" . $this->FechaTransaccion . "' ";
                $sql2 .= "		); ";
                break;
            case 'EF':
                $insert = "		total_efectivo, ";
                break;
            case 'OT':
                $insert = "		otros, ";
                break;
            case 'TC':
                $insert .= "	total_tarjetas,";
                $sql2 .= "INSERT INTO tmp_tarjetas_mov_credito(";
                $sql2 .= "			tarjeta ,";
                $sql2 .= "			empresa_id , ";
                $sql2 .= "			centro_utilidad , ";
                $sql2 .= "			recibo_caja, ";
                $sql2 .= "			autorizacion ,";
                $sql2 .= "			socio ,";
                $sql2 .= "			fecha_expira ,";
                $sql2 .= "			autorizado_por ,";

                $sql2 .= "			total ,";
                $sql2 .= "			usuario_id ,";
                $sql2 .= "			fecha,";
                $sql2 .= "			fecha_registro,";
                $sql2 .= "			tarjeta_numero )";
                $sql2 .= "VALUES(";
                $sql2 .= "	   '" . $this->TarjetasS . "',";
                $sql2 .= "	   '" . $empresa . "', ";
                $sql2 .= "     '" . $centro . "', ";
                $sql2 .= "		" . $numeracion[1] . ", ";
                $sql2 .= "	   '" . $this->NumeroAutorizacion . "',";
                $sql2 .= "	   '" . $this->Socio . "',";
                $sql2 .= "	   '" . $this->FechaExpiracion . "', ";
                $sql2 .= "	   '" . $this->AutorizadoPor . "', ";
                $sql2 .= "	   '" . $this->ValorRC . "', ";
                $sql2 .= "		" . UserGetUID() . ", ";
                $sql2 .= "	   '" . $this->FechaTransaccion . "', ";
                $sql2 .= "	   	NOW(),";
                $sql2 .= "	   '" . $this->NumeroTarjeta . "' ";
                $sql2 .= "		); ";

                break;
            case 'TD':
                $insert .= "	total_tarjetas,";
                $sql2 .= "INSERT INTO tmp_tarjetas_mov_debito(";
                $sql2 .= "			empresa_id , ";
                $sql2 .= "			centro_utilidad , ";
                $sql2 .= "			recibo_caja, ";
                $sql2 .= "			autorizacion ,";
                $sql2 .= "			tarjeta ,";
                $sql2 .= "			total ,";
                $sql2 .= "			tarjeta_numero )";
                $sql2 .= "VALUES(";
                $sql2 .= "	   '" . $empresa . "', ";
                $sql2 .= "		 '" . $centro . "', ";
                $sql2 .= "			" . $numeracion[1] . ", ";
                $sql2 .= "	   '" . $this->NumeroAutorizacion . "',";
                $sql2 .= "	   '" . $this->TarjetasS . "',";
                $sql2 .= "	   '" . $this->ValorRC . "', ";
                $sql2 .= "	   '" . $this->NumeroTarjeta . "' ";
                $sql2 .= "		); ";
                break;
        }

        ($this->Observacion) ? $this->Observacion = "'" . $this->Observacion . "'" : $this->Observacion = "NULL";
        if ($this->request['tipo_id_tercero_endoso'])
        {
            $this->request['tipo_id_tercero_endoso'] = "'" . $this->request['tipo_id_tercero_endoso'] . "'";
            $this->request['tercero_id_endoso'] = "'" . $this->request['tercero_id_endoso'] . "'";
        }
        else
        {
            $this->request['tipo_id_tercero_endoso'] = "NULL";
            $this->request['tercero_id_endoso'] = "NULL";
        }
        
        $empresa_recibo = "0";
        
        
        if(isset($_REQUEST["empresa_recibo"]) && $_REQUEST["empresa_recibo"] != ""){
            $empresa_recibo = $_REQUEST["empresa_recibo"];
        }
        
        $sql = "INSERT INTO tmp_recibos_caja(";
        $sql .= "				tmp_recibo_id,";
        $sql .= "				empresa_id,";
        $sql .= "				centro_utilidad,";
        $sql .= "				fecha_ingcaja,";
        $sql .= "				total_abono,";
        $sql .= "				" . $insert . " ";
        $sql .= "				tipo_id_tercero,";
        $sql .= "				tercero_id,";
        $sql .= "				fecha_registro,";
        $sql .= "				usuario_id,";
        $sql .= "				observacion, ";
        $sql .= "				tipo_id_tercero_endoso,";
        $sql .= "				tercero_id_endoso, ";
        $sql .= "				rc_tipo_documento, ";
        $sql .= "                               empresa_recibo";
        $sql .= ")";
        $sql .= "VALUES(";
        $sql .= "				" . $numeracion[1] . ", ";
        $sql .= "	   		'" . $empresa . "', ";
        $sql .= "				'" . $centro . "', ";
        $sql .= "		 			NOW(),";
        $sql .= "				" . $this->ValorRC . ",";
        $sql .= "				" . $this->ValorRC . ", ";
        $sql .= "	   		'" . $tercero_tipo . "', ";
        $sql .= "	   		'" . $tercero_id . "', ";
        $sql .= "		 		NOW(),";
        $sql .= "				" . UserGetUID() . ", ";
        $sql .= "				" . $this->Observacion . ", ";
        $sql .= "				" . $this->request['tipo_id_tercero_endoso'] . ",";
        $sql .= "				" . $this->request['tercero_id_endoso'] . ", ";
        $sql .= "				" . $this->menu['rc_tipo_documento'] . ", ";
         $sql .= "				'" . $empresa_recibo . "' ";
        $sql .= "	); ";
        $sql .= $sql2;
        

        /* if($_REQUEST['rc_traslado']==1)
          {
          $sql.= " INSERT INTO facturas_rc_tmp(tmp_recibo_id,
          empresa_id,
          centro_utilidad,
          valor_abonado)
          VALUES (".$numeracion[1].",
          '".$empresa."',
          '".$centro."',
          ".$this->ValorRC."); ";
          } */

        $rst = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->frmError['MensajeError'] = "Error " . $dbconn->ErrorMsg();
            die(MsgOut("Error al iniciar la transaccion", "Error DB : " . $dbconn->ErrorMsg()));
            $dbconn->RollbackTrans();
            return false;
        }

        $dbconn->CommitTrans();

        $this->ValorRC = "";
        $this->FormaPago = "";
        $this->Observacion = "";

        $this->parametro = "Informacion";
        $this->frmError['Informacion'] .= "EL/LA " . strtoupper(trim($this->menu['descripcion'])) . " HA SIDO CREADO(A)";

        $this->GenerarReciboCaja();

        return true;
    }

    /*     * ************************************************************************************
     * Funcion que permite desplegar la interface para realizar un pago a una factura con un
     * recibo de caja
     * 
     * @return boolean    
     * ************************************************************************************* */

    function RealizarPago()
    {
        $this->BancoS = $_REQUEST['banco'];
        $this->FormaPago = $_REQUEST['forma_pago'];
        $this->Cliente[0] = $_REQUEST['tercero_nombre'];
        $this->Cliente[1] = $_REQUEST['tercero_id'];
        $this->Cliente[2] = $_REQUEST['tercero_tipo'];

        if ($this->FechaTransaccion == "")
            $this->FechaTransaccion = date("d/m/Y");

        $arreglo = array("centro" => "01", "pagina" => $_REQUEST['pagina'], "tercero_id" => $_REQUEST['tercero_id'],
            "tercero_tipo" => $_REQUEST['tercero_tipo'], "tercero_nombre" => $_REQUEST['tercero_nombre']);

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'GenerarReciboCaja', $arreglo);

        $arreglo['forma_pago'] = $_REQUEST['forma_pago'];
        $arreglo['valor_rc'] = $_REQUEST['valor_rc'];
        $arreglo['observa'] = $_REQUEST['observa'];
        $this->action4 = ModuloGetURL('app', 'RecibosCaja', 'user', 'RealizarPago', $arreglo);

        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'RealizarPagoBD', $arreglo);

        $this->FormaRealizarPago();
        return true;
    }

    /*     * *************************************************************************************
     * Funcion donde se cruzan las facturas con el recibo de caja en la base de datos
     * 
     * @return boolean
     * ************************************************************************************** */

    function RealizarPagoBD()
    {
        $bool = true;
        $this->FormaPago = $_REQUEST['forma_pago'];
        switch ($this->FormaPago)
        {
            case 'CH':
                $this->BancoS = $_REQUEST['banco'];
                $this->Girador = $_REQUEST['girador'];
                $this->FechaCheque = $_REQUEST['fecha_cheque'];
                $this->NumeroCuenta = $_REQUEST['numero_cuenta'];
                $this->NumeroCheque = $_REQUEST['numero_cheque'];
                $this->FechaTransaccion = $_REQUEST['fecha_transaccion'];
                if ($this->NumeroCheque == "")
                {
                    $this->frmError['MensajeError'] = "SE DEBE INGRESAR EL NÚMERO DEL CHEQUE";
                    $bool = false;
                    break;
                }
                if ($this->BancoS == "FA")
                {
                    $this->frmError['MensajeError'] = "SE DEBE SELECCIONAR EL BANCO AL CUAL PERTENECE EL CHEQUE";
                    $bool = false;
                    break;
                }
                if ($this->NumeroCuenta == "")
                {
                    $this->frmError['MensajeError'] = "SE DEBE INGRESAR EL NÚMERO DE CUENTA CORRIENTE";
                    $bool = false;
                    break;
                }
                if (!$this->EvaluarFecha(&$this->FechaCheque))
                {
                    $this->frmError['MensajeError'] = "LA FECHA DEL CHEQUE ES INVALIDA";
                    $bool = false;
                    break;
                }
                if (!$this->EvaluarFecha(&$this->FechaTransaccion))
                {
                    $this->frmError['MensajeError'] = "LA FECHA DE TRANSACCION ES INVALIDA";
                    $bool = false;
                    break;
                }
                break;
            case 'CO':
                $this->BancoS = $_REQUEST['banco'];
                $this->NumeroCuenta = $_REQUEST['numero_cuenta'];
                $this->NumeroTransaccion = $_REQUEST['num_transaccion'];
                $this->FechaTransaccion = $_REQUEST['fecha_transaccion'];
                if ($this->BancoS == "FA")
                {
                    $this->frmError['MensajeError'] = "SE DEBE SELECCIONAR EL BANCO AL CUAL PERTENECE EL CHEQUE";
                    $bool = false;
                    break;
                }
                if ($this->NumeroCuenta == "NC")
                {
                    $this->frmError['MensajeError'] = "SE DEBE INDICAR CUAL ES EL NUMERO DE CUENTA";
                    $bool = false;
                    break;
                }
                if (!$this->EvaluarFecha(&$this->FechaTransaccion))
                {
                    $this->frmError['MensajeError'] = "LA FECHA DE TRANSACCION ES INVALIDA";
                    $bool = false;
                    break;
                }
                break;
            case 'TC':
                $this->TarjetasS = $_REQUEST['tarjeta'];
                $this->NumeroTarjeta = $_REQUEST['num_tarjeta'];
                $this->NumeroAutorizacion = $_REQUEST['num_autorizacion'];
                $this->Socio = $_REQUEST['socio'];
                $this->FechaExpiracion = $_REQUEST['fecha_expiracion'];
                $this->AutorizadoPor = $_REQUEST['autorizado'];
                $this->FechaTransaccion = $_REQUEST['fecha_transaccion'];

                if ($this->TarjetasS == "FA")
                {
                    $this->frmError['MensajeError'] = "SE DEBE SELECCIONAR LA TARJETA CON LA QUE SE HARÁ EL PAGO";
                    $bool = false;
                    break;
                }
                if ($this->NumeroTarjeta == "")
                {
                    $this->frmError['MensajeError'] = "SE DEBE INGRESAR EL NÚMERO DE LA TARJETA";
                    $bool = false;
                    break;
                }
                if ($this->NumeroAutorizacion == "")
                {
                    $this->frmError['MensajeError'] = "SE DEBE INGRESAR EL NÚMERO DE AUTORIZACIÓN";
                    $bool = false;
                    break;
                }
                if ($this->Socio == "")
                {
                    $this->frmError['MensajeError'] = "SE DEBE INGRESAR EL NOMBRE DEL SOCIO";
                    $bool = false;
                    break;
                }
                if (!$this->EvaluarFecha(&$this->FechaExpiracion))
                {
                    $this->frmError['MensajeError'] = "LA FECHA DE EXPIRACIÓN ES INVALIDA";
                    $bool = false;
                    break;
                }
                if (!$this->EvaluarFecha(&$this->FechaTransaccion))
                {
                    $this->frmError['MensajeError'] = "LA FECHA DE TRANSACCION ES INVALIDA";
                    $bool = false;
                    break;
                }
                break;
            case 'TD':
                $this->TarjetasS = $_REQUEST['tarjeta'];
                $this->NumeroTarjeta = $_REQUEST['num_tarjeta'];
                $this->NumeroAutorizacion = $_REQUEST['num_autorizacion'];

                if ($this->TarjetasS == "FA")
                {
                    $this->frmError['MensajeError'] = "SE DEBE SELECCIONAR LA TARJETA CON LA QUE SE HARÁ EL PAGO";
                    $bool = false;
                    break;
                }
                if ($this->NumeroTarjeta == "")
                {
                    $this->frmError['MensajeError'] = "SE DEBE INGRESAR EL NÚMERO DE LA TARJETA";
                    $bool = false;
                    break;
                }
                if ($this->NumeroAutorizacion == "")
                {
                    $this->frmError['MensajeError'] = "SE DEBE INGRESAR EL NÚMERO DE AUTORIZACIÓN";
                    $bool = false;
                    break;
                }
                break;
        }

        if (!$bool)
        {
            $this->RealizarPago();
        }
        else
        {
            $this->GenerarReciboCajaBD();
        }
        return true;
    }

    /*     * *************************************************************************************
     * Funcion donde se muestran las facturas que posse un cliente y que se pueden cancelar 
     * 
     * @return 
     * ************************************************************************************** */

    function MostrarFacturas()
    {
        if (!$_SESSION['SqlBuscarF'] || !$_SESSION['SqlContarF'])
        {
            $this->PrimeraVez = 1;
            $this->ObtenerSqlFacturas();      
        }

        $arreglo = array("pagina" => $_REQUEST['pagina'], "tercero_id" => $_REQUEST['tercero_id'],
            "tercero_tipo" => $_REQUEST['tercero_tipo'], "recibo_caja" => $_REQUEST['recibo_caja'],
            "prefijo" => $_REQUEST['prefijo'], "centro" => $_REQUEST['centro'], "tercero_nombre" => $_REQUEST['tercero_nombre'], "campo_ordenar" => $_REQUEST['campo_ordenar']);

        $this->actionB = ModuloGetURL('app', 'RecibosCaja', 'user', 'ObtenerSqlFacturas', $arreglo);
        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'GenerarReciboCaja', $arreglo);
        $this->actionPg = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarFacturas', $arreglo);

        $arreglo['offset'] = $_REQUEST['offset'];
        $this->action3 = ModuloGetURL('app', 'RecibosCaja', 'user', 'PagarFacturas', $arreglo);

        $arreglo['todas'] = "1";
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'ObtenerSqlFacturas', $arreglo);

        $arreglo['campo_ordenar'] = "FF.prefijo,FF.factura-fiscal";
        $this->actionH[0] = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarFacturas', $arreglo);
        $arreglo['campo_ordenar'] = "fecha1";
        $this->actionH[1] = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarFacturas', $arreglo);
        $arreglo['campo_ordenar'] = "envio_id";
        $this->actionH[2] = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarFacturas', $arreglo);
        $arreglo['campo_ordenar'] = "fecha2";
        $this->actionH[3] = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarFacturas', $arreglo);
        $arreglo['campo_ordenar'] = "FF.total_factura";
        $this->actionH[4] = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarFacturas', $arreglo);
        $arreglo['campo_ordenar'] = "FF.saldo";
        $this->actionH[5] = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarFacturas', $arreglo);

        $this->parametros = $arreglo;
        //**$this->FormaMostrarFacturas();
        $this->visualizaFormas();
        return true;
    }

    /*     * ********************************************************************************
     * Funcion que permite mostrar la forma de adicionar conceptos al recibo de caja 
     * 
     * @return boolean 
     * ********************************************************************************* */

    function AdicionarConceptos()
    {
        $datos = $_REQUEST['datos'];
        $this->ValorEnNotas = $datos['total_nota'];
        $this->menu = SessionGetVar("Documentos");

        $_REQUEST = $datos;
        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'GenerarReciboCaja', $datos);
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'AdicionarConceptosBD', array("datos" => $datos));
        $this->FormaAdicionarConceptos();
        return true;
    }

    /*     * ********************************************************************************
     * funciuon donde se adicionan los conceptos a la base de datos en la tabla 
     * rc_detalle_tesoreria_conceptos 
     * 
     * @return boolean 
     * ********************************************************************************* */

    function AdicionarConceptosBD()
    {
        $datos = $_REQUEST['datos'];
        $this->parametro = "MensajeError";
        $this->Concepto = $_REQUEST['concepto'];
        $this->ValorCRC = $_REQUEST['valor_concepto'];
        $this->Departamento = $_REQUEST['departamento'];

        $caja_id = $_SESSION['RCFactura']['caja'];
        $centro_utilidad = $this->BuscarCentroUtilidad($caja_id);
        $centro = $centro_utilidad['']['centro_utilidad'];

        if ($this->Concepto == '0')
        {
            $this->frmError['MensajeError'] = "SE DEBE SELECCIONAR UN CONCEPTO PARA SER AGREGADO";
            $this->AdicionarConceptos();
            return true;
        }
        if (!is_numeric($this->ValorCRC))
        {
            $this->frmError['MensajeError'] = "EL VALOR INGRESADO NO ES VALIDO";
            $this->AdicionarConceptos();
            return true;
        }

        $arreglo = explode("-", $this->Concepto);

        if ($arreglo[2] == '1' && $this->Departamento == '0')
        {
            $this->Script = 1;
            $this->frmError['MensajeError'] = "SE DEBE SELECCIONAR EL DEPARTAMENTO ASOCIADO AL CONCEPTO";
            $this->AdicionarConceptos();
            return true;
        }

        ($this->Departamento == '0' || empty($this->Departamento)) ? $this->Departamento = "NULL" : $this->Departamento = "'" . $this->Departamento . "'";

        $sql = "INSERT INTO tmp_rc_detalle_tesoreria_conceptos( ";
        $sql .= "			tmp_rc_id,";
        $sql .= "			empresa_id, ";
        $sql .= "			centro_utilidad, ";
        $sql .= "			tmp_recibo_id, ";
        $sql .= "			concepto_id, ";
        $sql .= "			naturaleza, ";
        $sql .= "			valor,";
        $sql .= "			departamento ) ";
        $sql .= "VALUES ( ";
        $sql .= "			(SELECT COALESCE(MAX(tmp_rc_id),0)+1 FROM tmp_rc_detalle_tesoreria_conceptos),";
        $sql .= "			'" . $_SESSION['RCFactura']['empresa'] . "', ";
        $sql .= "			'" . $centro . "', ";
        $sql .= "			 " . $datos['recibo_caja'] . ", ";
        $sql .= "			'" . $arreglo[0] . "', ";
        $sql .= "			'" . $arreglo[1] . "', ";
        $sql .= "			 " . $this->ValorCRC . ",";
        $sql .= "			 " . $this->Departamento . " ) ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $this->Concepto = "";
        $this->ValorCRC = "";
        $this->Departamento = '0';

        $this->menu = SessionGetVar("Documentos");

        $this->parametro = "Informacion";
        $this->frmError['Informacion'] = "EL VALOR DEL CONCEPTO SE ADICIONO A(LA) " . strtoupper(trim($this->menu['descripcion'])) . " Nº: " . $datos['recibo_caja'] . "";

        $this->AdicionarConceptos();
        return true;
    }

    /*     * ********************************************************************************
     * Funcion que permite mostrar la confirmacion de eliminar un concepto, en pantalla 
     * 
     * @return boolean 
     * ********************************************************************************* */

    function EliminarConceptos()
    {
        $datos = $_REQUEST['datos'];
        $this->menu = SessionGetVar("Documentos");

        $this->actionM = ModuloGetURL('app', 'RecibosCaja', 'user', 'AdicionarConceptos', array("datos" => $datos));
        $this->action = ModuloGetURL('app', 'RecibosCaja', 'user', 'EliminarConceptosBD', array("datos" => $datos));

        $informacion = "<br>ESTA SEGURO QUE DESEA ELIMINAR EL CONCEPTO SELECCIONADO, DEL DETALLE DE EL/LA " . strtoupper(trim($this->menu['descripcion'])) . "? <br>";
        $this->FormaInformacion($informacion);
        return true;
    }

    /*     * ********************************************************************************
     * Funcion que permite eliminar un concepto de la base de datos 
     * 
     * @return boolean 
     * ********************************************************************************* */

    function EliminarConceptosBD()
    {
        $datos = $_REQUEST['datos'];
        $id = $datos['concepto'];

        $sql = "DELETE FROM tmp_rc_detalle_tesoreria_conceptos ";
        $sql .= "WHERE 	tmp_rc_id = " . $id . " ";
        $sql .= "AND		tmp_recibo_id = " . $datos['recibo_caja'] . " ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $this->menu = SessionGetVar("Documentos");

        $this->parametro = "Informacion";
        $this->frmError['Informacion'] = "EL CONCEPTO FUE REMOVIDO DEL DETALLE DE EL/LA " . strtoupper(trim($this->menu['descripcion'])) . "";

        $this->AdicionarConceptos();
        return true;
    }

    /*     * ********************************************************************************
     * Funcion que permite mostrar la confirmacion de cerrar un recibo de caja 
     * 
     * @return boolean 
     * ********************************************************************************* */

    function CerrarReciboCaja()
    {
        $datos = $_REQUEST['datos'];

        $debito = FormatoValor($datos['debito']);
        $credito = FormatoValor($datos['credito']);

        $this->menu = SessionGetVar("Documentos");

        /* echo "<pre> 1. ==== CerrarReciboCaja-> Request====";
          print_r($_REQUEST);
          echo "</pre>";
          echo "<pre> 1. ==== CerrarReciboCaja ====";
          print_r($datos);
          echo "</pre>"; */

        if ($debito == $credito)
        {
            $this->action = ModuloGetURL('app', 'RecibosCaja', 'user', 'CerrarReciboCajaBD', $datos);
            $this->actionM = ModuloGetURL('app', 'RecibosCaja', 'user', 'GenerarReciboCaja', $datos);
            $informacion = "<br>DESEA HACER EL CIERRE DEL DOCUMENTO " . strtoupper(trim($this->menu['descripcion'])) . " Nº " . $datos['recibo_caja'] . " ?<br>";
            $this->menu = SessionGetVar("Documentos");

            $fcn = new app_RecibosCaja_Funciones();
            $this->DatosRecibo = $fcn->ObtenerInformacionRecibo($datos, $this->menu['rc_tipo_documento'], $_SESSION['RCFactura']['empresa']);
            if ($this->DatosRecibo['fecha'] < $this->DatosRecibo['limite'])
                $this->DatosRecibo['fecha_registro'] = date("d/m/Y");
        }
        else
        {
            $this->action = ModuloGetURL('app', 'RecibosCaja', 'user', 'GenerarReciboCaja', $datos);
            $informacion = "<br> EL DOCUMENTO " . strtoupper(trim($this->menu['descripcion'])) . " Nº " . $datos['recibo_caja'] . " NO PUEDE SER CERRADO, LOS VALORES DE LOS CREDITOS Y LOS DEBITOS NO SON IGUALES<br>";
        }

        $this->FormaInformacion($informacion, true);
        
        return true;
    }

    /*     * ********************************************************************************
     * Funcion donde se cierra un recibo de caja en la base de datos cambiando el estado 
     * del recibo de caja a 2 
     * 
     * @return boolean 
     * ********************************************************************************* */

    function CerrarReciboCajaBD()
    {
        $sql = "SELECT COUNT(*) AS cantidad ";
        $sql .= "FROM 	tmp_rc_detalle_tesoreria_facturas ";
        $sql .= "WHERE	tmp_recibo_id = " . $_REQUEST['recibo_caja'] . " ";
        $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

        /* echo "======== CerrarReciboCajaBD ==========</br>";
          echo "<pre>====== 1.sql=========";
          var_dump($sql);
          echo "===============</pre>"; */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$rst->EOF)
        {
            $this->cantidad = $rst->fields[0];
            $rst->MoveNext();
        }
        $rst->Close();

        $sql = "SELECT * ";
        $sql .= "FROM 	tmp_recibos_caja ";
        $sql .= "WHERE	tmp_recibo_id = " . $_REQUEST['recibo_caja'] . " ";
        $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "'; ";

        /* echo "<pre>====== 2.sql=========";
          var_dump($sql);
          echo "===============</pre>"; */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $this->recibo = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        if ($this->recibo['total_cheques'] != 0)
        {
            $sql = "SELECT * ";
            $sql .= "FROM 	tmp_cheques_mov_rc ";
            $sql .= "WHERE	recibo_caja = " . $_REQUEST['recibo_caja'] . " ";
            $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

            /* echo "<pre>====== 3.sql=========";
              var_dump($sql);
              echo "===============</pre>"; */

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            while (!$rst->EOF)
            {
                $this->cheques[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();
        }

        if ($this->recibo['total_tarjetas'] != 0)
        {
            $sql = "SELECT * ";
            $sql .= "FROM 	tmp_tarjetas_mov_debito ";
            $sql .= "WHERE	recibo_caja = " . $_REQUEST['recibo_caja'] . " ";
            $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

            /* echo "<pre>====== 4.sql=========";
              var_dump($sql);
              echo "===============</pre>"; */

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            while (!$rst->EOF)
            {
                $this->tarjeta_debito[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();

            $sql = "SELECT * ";
            $sql .= "FROM 	tmp_tarjetas_mov_credito ";
            $sql .= "WHERE	recibo_caja = " . $_REQUEST['recibo_caja'] . " ";
            $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

            /* echo "<pre>====== 5.sql=========";
              var_dump($sql);
              echo "===============</pre>"; */
            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            while (!$rst->EOF)
            {
                $this->tarjeta_credito[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();
        }

        if ($this->recibo['total_consignacion'] != 0)
        {
            $sql = "SELECT * ";
            $sql .= "FROM 	tmp_bancos_consignaciones ";
            $sql .= "WHERE	tmp_recibo_id = " . $_REQUEST['recibo_caja'] . " ";
            $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

            /* echo "<pre>====== 6.sql=========";
              var_dump($sql);
              echo "===============</pre>"; */

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            while (!$rst->EOF)
            {
                $this->bancos[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();
        }

        $sql = "SELECT * ";
        $sql .= "FROM 	tmp_rc_detalle_tesoreria_notas_credito ";
        $sql .= "WHERE	tmp_recibo_id = " . $_REQUEST['recibo_caja'] . " ";
        $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "'; ";

        /* echo "<pre>====== 7.sql=========";
          var_dump($sql);
          echo "===============</pre>"; */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $this->notas_credito[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        //exit();
        $this->menu = SessionGetVar("Documentos");

        $this->parametro = "Informacion";
        if (!$this->GenerarReciboBD())
        {
            $this->parametro = "MensajeError";
        }
        else
        {
            $this->frmError['Informacion'] = " EL DOCUMENTO " . strtoupper(trim($this->menu['descripcion'])) . " Nº " . $_REQUEST['prefijo'] . " " . $_REQUEST['recibo_caja'] . " HA SIDO CERRADO<br>";
            if ($this->Arreglo['mensaje_ws'])
            {
                $this->frmError['Informacion'] .= "<br> WS FI: " . $this->Arreglo['mensaje_ws'];
            }
            $this->Imprimir = "1";
        }

        $this->GenerarReciboCaja();
        return true;
    }

    /*     * ********************************************************************************
     * Funcion donde se copia de las tablas temporales, los datos que seran registrados
     * en las tablas de recibos de caja y recibos caja tesoreria
     * 
     * @return boolean
     * ********************************************************************************* */
    
    function MostrarRecibosSincronizados(){
        
         $this->SetXajax(array("sincronizar_recibos_pendientes_ws_fi"), "app_modules/RecibosCaja/RemoteXajax/definirRec.php", "ISO-8859-1");
         $recibo = null;
         
         if(isset($_REQUEST['recibo']) && trim($_REQUEST['recibo']) != ""){
             $recibo = $_REQUEST['recibo'];
         }
         
        $recibos = $this->obtenerRecibosDeSincronizacion($recibo);
        $this->formaMostrarRecibos($recibos);
        return true;
    }
    
    
    function obtenerRecibosDeSincronizacion($recibo = null){
        
        $filtro = "";
        if(!is_null($recibo)){
            $filtro = " and numero_documento = {$recibo} ";
        }
        
        $sql = " select * from logs_recibos_ws_fi where estado='1' {$filtro} order by id limit 40;";
        
        
           if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $datos = Array();
        while (!$resultado->EOF)
        {
            $datos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        
        
        return $datos;
    }
    
    function GenerarReciboBD()
    {

        //echo "<br/> ======== GenerarReciboBD =========== </br>";

        $documento = $this->menu['documento_id'];

        /* echo "<pre>YO misamo";
          print_r($this);
          echo "</pre>"; */

        /* echo "<pre>Session";
          print_r($_SESSION);
          echo "</pre>"; */

        /* echo "<pre>Request";
          print_r($_REQUEST);
          echo "</pre>"; */
        //exit();

        $caja_id = $_SESSION['RCFactura']['caja'];

        $sql1 = "";
        //	if($this->cantidad > 500)
        //		$sql1 .= "ALTER TABLE rc_detalle_tesoreria_facturas DISABLE TRIGGER trigger_rc_detalle_tesoreria_facturas_numero_recibo;";

        $sql1 = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE; "; //Bloqueo de tabla 
        list($dbconn) = GetDBConn();

        $dbconn->BeginTrans();

        //$dbconn->debug = true;


        /* echo "<pre>====== 1.sql =========";
          var_dump($sql1);
          echo "===============</pre>"; */


        $result = $dbconn->Execute($sql1);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->frmError['MensajeError'] = "Error al iniciar la transaccion Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $fech = explode("/", $_REQUEST['fecha_registro']);

        $sql = "SELECT prefijo,numeracion FROM documentos ";
        $sql .= "WHERE documento_id = " . $documento . " AND empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

        /* echo "<pre>====== 2.sql =========";
          var_dump($sql);
          echo "===============</pre>"; */


        $numeracion = $this->ObtenerNumeracion($sql, &$dbconn);

        /* echo "<pre>====== 3.sql =========";
          var_dump($numeracion);
          echo "===============</pre>"; */
        //exit();

        $sql = "INSERT INTO recibos_caja(
        			empresa_id,
        			centro_utilidad,
        			recibo_caja,
        			prefijo,
        			fecha_ingcaja,
        			total_abono,
        			total_efectivo, 
        			total_cheques, 
        			total_tarjetas,total_consignacion, 
        			otros, 
        			tipo_id_tercero,
        			tercero_id,
        			estado,
        			fecha_registro,
        			usuario_id,
        			caja_id,
        			documento_id,
        			cuenta_tipo_id,
        			rc_tipo_documento,
        			sw_recibo_tesoreria, 
        			tercero_id_endoso, 
        			tipo_id_tercero_endoso, 
        			observacion,
                                empresa_recibo)
                SELECT empresa_id, 
        		 	centro_utilidad, 
        			" . $numeracion[1] . " , 
        	   	'" . $numeracion[0] . "', 
        		 	'" . $fech[2] . "-" . $fech[1] . "-" . $fech[0] . "'::date,
        			total_abono,
        			total_efectivo, 
        			total_cheques, 
        			total_tarjetas, 
        			total_consignacion, 
        			otros, 
        	   	tipo_id_tercero, 
        	   	tercero_id, 
        		 	'2' AS estado,
        		 	NOW(),
        			usuario_id, 
        			" . $caja_id . ", 
        			" . $documento . ",
        		 	'07',
        		 	rc_tipo_documento,
        		 	'1',
        			tercero_id_endoso, 
        			tipo_id_tercero_endoso, 
        		 	observacion,
                                empresa_recibo
                    FROM 	tmp_recibos_caja 
                    WHERE	tmp_recibo_id = " . $_REQUEST['recibo_caja'] . " 
                    AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "'; ";


        /* cho "<pre>====== 4.sql insertar recibos_caja =========";
          var_dump($sql);
          echo "===============</pre>"; */


        //STEVEN
        if ($_REQUEST['rc_traslado'] == 0)
        {
            $sql .= "INSERT INTO rc_detalles (empresa_id, 
                                                    centro_utilidad, 
                                                    recibo_caja, 
                                                    prefijo, 
                                                    valor_actual)
                                    SELECT     empresa_id, 
                                                centro_utilidad, 
                                                " . $numeracion[1] . ", 
                                                '" . $numeracion[0] . "', 
                                                total_abono 
                                    FROM 	tmp_recibos_caja 
                                    WHERE	tmp_recibo_id = " . $_REQUEST['recibo_caja'] . " 
                                    AND    empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "'; ";
            /* echo "<pre>====== 5.sql INSERT rc_detalles =========";
              var_dump($sql);
              echo "===============</pre>"; */
        }
        else
        {
            //GUARDAR LOS DETALLES Y EL MAESTRO DE LAS FACTURAS CON VALORES DE RC
            $facturas_tmp = $this->obtenerFacturasTMP($_REQUEST['recibo_caja']);
            $facturas_det_tmp = $this->obtenerDetallesFacturasTMP($_REQUEST['recibo_caja']); 
            for ($q = 0; $q < count($facturas_tmp); $q++)
            {
                $sql .= "INSERT INTO facturas_rc (
                                                                    rc_id,
                                                                    rc_prefijo,
                                                                    prefijo_factura,
                                                                    factura_fiscal,
                                                                    valor_abonado
                                                                 )
                                                VALUES ( '" . $numeracion[1] . "',
                                                         '" . $numeracion[0] . "',
                                                         '" . $facturas_tmp[$q]['prefijo_factura'] . "',
                                                         '" . $facturas_tmp[$q]['factura_fiscal'] . "',
                                                         '" . $facturas_tmp[$q]['valor_abonado'] . "'
                                                        ); ";
            }

            /* echo "<pre>====== 6.sql INSERT INTO facturas_rc =========";
              var_dump($sql);
              echo "===============</pre>"; */

            for ($m = 0; $m < count($facturas_det_tmp); $m++)
            {
                $sql .= "INSERT INTO facturas_rc_detalles(  rc_id_tras,
                                                                            rc_prefijo_tras,
                                                                            prefijo_factura,
                                                                            factura_fiscal,
                                                                            prefijo_rc,
                                                                            recibo_caja,
                                                                            empresa_id,
                                                                            centro_utilidad,
                                                                            valor_detalle,
                                                                            usuario_id ) 
                                                 VALUES (   '" . $numeracion[1] . "', 
                                                            '" . $numeracion[0] . "', 
                                                            '" . $facturas_det_tmp[$m]['prefijo_factura'] . "', 
                                                            '" . $facturas_det_tmp[$m]['factura_fiscal'] . "', 
                                                            '" . $facturas_det_tmp[$m]['prefijo_rc'] . "', 
                                                            '" . $facturas_det_tmp[$m]['recibo_caja'] . "', 
                                                            '" . $facturas_det_tmp[$m]['empresa_id'] . "', 
                                                            '" . $facturas_det_tmp[$m]['centro_utilidad'] . "', 
                                                            '" . $facturas_det_tmp[$m]['valor_detalle'] . "', 
                                                            '" . UserGetUID() . "' ); ";
            }

            /* echo "<pre>====== 7.sql INSERT INTO facturas_rc_detalles =========";
              var_dump($sql);
              echo "===============</pre>"; */
        }
        // FIN STEVEN
        /* echo "SQLs: ".$sql."<br>";

          echo '<pre>';
          print_r($_REQUEST);
          echo '</pre>';
         */
        //exit(0);
        //exit();

        /* echo "<pre>====== 8.sql =========";
          var_dump($sql);
          echo "===============</pre>"; */

        $rst = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->mensajeDeError = $sql;
            $this->frmError['MensajeError'] = "Error al iniciar la transaccion 1  Error DB : " . $dbconn->ErrorMsg()." ".$sql;
            $dbconn->RollbackTrans();
            return false;
        }

        $sql = "";

        if ($this->recibo['total_cheques'] != 0)
        {
            for ($i = 0; $i < sizeof($this->cheques); $i++)
            {
                $sql .= "INSERT INTO cheques_mov(";
                $sql .= "				empresa_id , ";
                $sql .= "				centro_utilidad , ";
                $sql .= "				recibo_caja, ";
                $sql .= "				prefijo, ";
                $sql .= "				banco , ";
                $sql .= "				cheque, ";
                $sql .= "				girador, ";
                $sql .= "				fecha_cheque, ";
                $sql .= "				total, ";
                $sql .= "				fecha , ";
                $sql .= "				estado, ";
                $sql .= "				usuario_id, ";
                $sql .= "				fecha_registro, ";
                $sql .= "				cta_cte)";
                $sql .= "VALUES(";
                $sql .= "	   '" . $this->cheques[$i]['empresa_id'] . "', ";
                $sql .= "		 '" . $this->cheques[$i]['centro_utilidad'] . "', ";
                $sql .= "			" . $numeracion[1] . ", ";
                $sql .= "	   '" . $numeracion[0] . "',";
                $sql .= "	   '" . $this->cheques[$i]['banco'] . "',";
                $sql .= "	   '" . $this->cheques[$i]['cheque'] . "',";
                $sql .= "	   '" . $this->cheques[$i]['girador'] . "', ";
                $sql .= "	   '" . $this->cheques[$i]['fecha_cheque'] . "', ";
                $sql .= "	   '" . $this->cheques[$i]['total'] . "', ";
                $sql .= "	   '" . $this->cheques[$i]['fecha'] . "', ";
                $sql .= "		 '0',";
                $sql .= "			" . $this->cheques[$i]['usuario_id'] . ", ";
                $sql .= "		 '" . $this->cheques[$i]['fecha_registro'] . "',";
                $sql .= "	   '" . $this->cheques[$i]['cta_cte'] . "' ";
                $sql .= "	); ";
            }
        }
        if ($this->recibo['total_tarjetas'] != 0)
        {
            if (sizeof($this->tarjeta_debito) > 0)
            {
                for ($i = 0; $i < sizeof($this->tarjeta_debito); $i++)
                {
                    $sql .= "INSERT INTO tarjetas_mov_debito(";
                    $sql .= "				empresa_id , ";
                    $sql .= "				centro_utilidad , ";
                    $sql .= "				recibo_caja, ";
                    $sql .= "				prefijo, ";
                    $sql .= "				autorizacion ,";
                    $sql .= "				tarjeta ,";
                    $sql .= "				total ,";
                    $sql .= "				tarjeta_numero )";
                    $sql .= "VALUES(";
                    $sql .= "	   '" . $this->tarjeta_debito[$i]['empresa_id'] . "', ";
                    $sql .= "		 '" . $this->tarjeta_debito[$i]['centro_utilidad'] . "', ";
                    $sql .= "			" . $numeracion[1] . ", ";
                    $sql .= "	   '" . $numeracion[0] . "', ";
                    $sql .= "	   '" . $this->tarjeta_debito[$i]['autorizacion'] . "',";
                    $sql .= "	   '" . $this->tarjeta_debito[$i]['tarjeta'] . "',";
                    $sql .= "	   '" . $this->tarjeta_debito[$i]['total'] . "', ";
                    $sql .= "	   '" . $this->tarjeta_debito[$i]['tarjeta_numero'] . "' ";
                    $sql .= "		); ";
                }
            }
            elseif (sizeof($this->tarjeta_credito) > 0)
            {
                for ($i = 0; $i < sizeof($this->tarjeta_credito); $i++)
                {
                    $sql .= "INSERT INTO tarjetas_mov_credito(";
                    $sql .= "					tarjeta ,";
                    $sql .= "					empresa_id , ";
                    $sql .= "					centro_utilidad , ";
                    $sql .= "					recibo_caja, ";
                    $sql .= "					prefijo, ";
                    $sql .= "					autorizacion ,";
                    $sql .= "					socio ,";
                    $sql .= "					fecha_expira ,";
                    $sql .= "					autorizado_por ,";
                    $sql .= "					total ,";
                    $sql .= "					usuario_id ,";
                    $sql .= "					fecha,";
                    $sql .= "					fecha_registro,";
                    $sql .= "					tarjeta_numero )";
                    $sql .= "VALUES(";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['tarjeta'] . "',";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['empresa_id'] . "', ";
                    $sql .= "		 '" . $this->tarjeta_credito[$i]['centro_utilidad'] . "', ";
                    $sql .= "			" . $numeracion[1] . ", ";
                    $sql .= "	   '" . $numeracion[0] . "', ";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['autorizacion'] . "',";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['socio'] . "',";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['fecha_expira'] . "', ";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['autorizado_por'] . "', ";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['total'] . "', ";
                    $sql .= "			" . $this->tarjeta_credito[$i]['usuario_id'] . ", ";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['fecha'] . "', ";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['fecha_registro'] . "',";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['tarjeta_numero'] . "' ";
                    $sql .= "		); ";
                }
            }
        }
        if ($this->recibo['total_consignacion'] != 0)
        {
            for ($i = 0; $i < sizeof($this->bancos); $i++)
            {
                $sql .= "INSERT INTO bancos_consignaciones(";
                $sql .= "				empresa_id , ";
                $sql .= "				centro_utilidad , ";
                $sql .= "				recibo_caja, ";
                $sql .= "				prefijo,";
                $sql .= "				numero_cuenta, ";
                $sql .= "				valor, ";
                $sql .= "				numero_transaccion, ";
                $sql .= "				fecha_transaccion)";
                $sql .= "VALUES(";
                $sql .= "	   '" . $this->bancos[$i]['empresa_id'] . "', ";
                $sql .= "		 '" . $this->bancos[$i]['centro_utilidad'] . "', ";
                $sql .= "			" . $numeracion[1] . ", ";
                $sql .= "	   '" . $numeracion[0] . "', ";
                $sql .= "	   '" . $this->bancos[$i]['numero_cuenta'] . "',";
                $sql .= "	   '" . $this->bancos[$i]['valor'] . "', ";
                $sql .= "	   '" . $this->bancos[$i]['numero_transaccion'] . "', ";
                $sql .= "	   '" . $this->bancos[$i]['fecha_transaccion'] . "' ";
                $sql .= "		); ";
            }
        }

        /* echo "<pre>====== 9-1.sql =========";
          var_dump($sql);
          echo "===============</pre>"; */


        if ($sql != "")
        {
            $rst = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->mensajeDeError = $sql;
                $this->frmError['MensajeError'] = "1 Error al iniciar la transaccion Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }

        $sql = "INSERT INTO rc_detalle_tesoreria_facturas( ";
        $sql .= "			empresa_id ,";
        $sql .= "			centro_utilidad ,";
        $sql .= "			recibo_caja ,";
        $sql .= "			prefijo ,";
        $sql .= "			prefijo_factura ,";
        $sql .= "			factura_fiscal ,";
        $sql .= "			valor_abonado, ";
        $sql .= "			sw_voucher,  ";
        $sql .= "			valor_efectivo,  ";
        $sql .= "			retefuente,  ";
        $sql .= "			retecre,  ";
        $sql .= "			reteica ) ";
        $sql .= "SELECT                         empresa_id, ";
        $sql .= "				centro_utilidad,";
        $sql .= "				 " . $numeracion[1] . " AS numeracion, ";
        $sql .= "                               '" . $numeracion[0] . "' AS prefijo_nota, ";
        $sql .= "				prefijo_factura,";
        $sql .= "		 		factura_fiscal,";
        $sql .= "		 		valor_abonado, ";
        $sql .= "                               '1' AS sw_voucher, ";
        $sql .= "                               valor_efectivo, ";
        $sql .= "                               retefuente, ";
        $sql .= "                               retecre, ";
        $sql .= "                               reteica ";
        $sql .= "FROM 	tmp_rc_detalle_tesoreria_facturas ";
        $sql .= "WHERE	tmp_recibo_id = " . $_REQUEST['recibo_caja'] . " ";
        $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";


        /* echo "<pre>====== 8.sql INSERT INTO rc_detalle_tesoreria_facturas =========";
          var_dump($sql);
          echo "===============</pre>"; */

        $rst = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->mensajeDeError = $sql;
            $this->frmError['MensajeError'] = "Error al iniciar la transaccion 3  Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        //DESCUENTA EL SALDO DE TABLA "inv_facturas_despacho"
        //EL DESCUENTO DEL SALDO DE "fac_facturas" SE REALIZA POR EL TRIGGER: actualizar_saldo_factura_rc, EN LA TABLA: rc_detalle_tesoreria_facturas
        //TRAE LOS RC CREADOS CON EL PREFIJO BC
        $rc_inv_facturas_despacho = $this->getRCFacturasInvFacturasDespacho($numeracion[0], $numeracion[1]);
        for ($st = 0; $st < count($rc_inv_facturas_despacho); $st++)
        {
            $sql = "UPDATE  inv_facturas_despacho 
                    SET     saldo = saldo-" . (int) ($rc_inv_facturas_despacho[$st]['valor_abonado']) . " 
                    WHERE   factura_fiscal = '" . $rc_inv_facturas_despacho[$st]['factura_fiscal'] . "' 
                    AND     prefijo = '" . $rc_inv_facturas_despacho[$st]['prefijo_factura'] . "' 
                    AND     empresa_id = '" . $rc_inv_facturas_despacho[$st]['empresa_id'] . "'; ";

            $sql .= "UPDATE  inv_facturas_agrupadas_despacho 
                    SET     saldo = saldo-" . (int) ($rc_inv_facturas_despacho[$st]['valor_abonado']) . " 
                    WHERE   factura_fiscal = '" . $rc_inv_facturas_despacho[$st]['factura_fiscal'] . "' 
                    AND     prefijo = '" . $rc_inv_facturas_despacho[$st]['prefijo_factura'] . "' 
                    AND     empresa_id = '" . $rc_inv_facturas_despacho[$st]['empresa_id'] . "'; ";

            /* echo "<pre>====== 9.sql descontar saldos =========";
              var_dump($sql);
              echo "===============</pre>"; */

            $rst = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->mensajeDeError = $sql;
                $this->frmError['MensajeError'] = "Error al actualizar el saldo a inv_facturas_despacho: " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }

        $sql = "INSERT INTO rc_detalle_tesoreria_conceptos( ";
        $sql .= "				empresa_id, ";
        $sql .= "				centro_utilidad, ";
        $sql .= "				recibo_caja, ";
        $sql .= "				prefijo, ";
        $sql .= "				concepto_id, ";
        $sql .= "				naturaleza, ";
        $sql .= "				departamento, ";
        $sql .= "				valor ) ";
        $sql .= "SELECT empresa_id, ";
        $sql .= "				centro_utilidad, ";
        $sql .= "			 	" . $numeracion[1] . " AS numeracion, ";
        $sql .= "	   		'" . $numeracion[0] . "' AS prefijo_nota, ";
        $sql .= "				concepto_id, ";
        $sql .= "				naturaleza, ";
        $sql .= "				departamento, ";
        $sql .= "				valor ";
        $sql .= "FROM 	tmp_rc_detalle_tesoreria_conceptos ";
        $sql .= "WHERE	tmp_recibo_id = " . $_REQUEST['recibo_caja'] . " ";
        $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";


        /* echo "<pre>====== 10.sql INSERT INTO rc_detalle_tesoreria_conceptos AGREGAR CONCEPTOS=========";
          var_dump($sql);
          echo "===============</pre>"; */

        $rst = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->mensajeDeError = $sql;
            $this->frmError['MensajeError'] = "Error al iniciar la transaccion 3  Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $sql = "INSERT INTO rc_detalle_tesoreria_notas_credito( ";
        $sql .= "				empresa_id, ";
        $sql .= "				centro_utilidad, ";
        $sql .= "				recibo_caja, ";
        $sql .= "				prefijo, ";
        $sql .= "				prefijo_nota, ";
        $sql .= "				nota_credito_ajuste, ";
        $sql .= "				valor) ";
        $sql .= "SELECT empresa_id, ";
        $sql .= "				'" . $this->recibo['centro_utilidad'] . "' AS centro, ";
        $sql .= "			 	" . $numeracion[1] . " AS numeracion, ";
        $sql .= "	   		'" . $numeracion[0] . "' AS prefijo_rc, ";
        $sql .= "				prefijo_nota, ";
        $sql .= "			 	nota_credito_ajuste, ";
        $sql .= "			 	valor ";
        $sql .= "FROM 	tmp_rc_detalle_tesoreria_notas_credito ";
        $sql .= "WHERE	tmp_recibo_id = " . $_REQUEST['recibo_caja'] . " ";
        $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "'; ";

        /* echo "<pre>====== 11.sql INSERT INTO rc_detalle_tesoreria_notas_credito =========";
          var_dump($sql);
          echo "===============</pre>"; */

        foreach ($this->notas_credito as $key => $notas)
        {
            $sql .= "UPDATE notas_credito_ajuste ";
            $sql .= "SET 		estado = '1' ";
            $sql .= "WHERE 	prefijo = '" . $notas['prefijo_nota'] . "' ";
            $sql .= "AND 		nota_credito_ajuste = " . $notas['nota_credito_ajuste'] . " ";
            $sql .= "AND 		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "'; ";
        }

        /* echo "<pre>====== 12.sql UPDATE notas_credito_ajuste=========";
          var_dump($sql);
          echo "===============</pre>"; */

        $rst = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->mensajeDeError = $sql;
            $this->frmError['MensajeError'] = "Error al iniciar la transaccion 3  Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }


        $sql = "UPDATE documentos ";
        $sql .= "SET 		numeracion = numeracion + 1 ";
        $sql .= "WHERE 	documento_id = " . $documento . " AND empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "'; ";

        $sql .= "DELETE FROM tmp_rc_detalle_tesoreria_conceptos ";
        $sql .= "WHERE	tmp_recibo_id = '" . $this->recibo['tmp_recibo_id'] . "' ";
        $sql .= "AND		empresa_id = '" . $this->recibo['empresa_id'] . "'; ";

        $sql .= "DELETE FROM tmp_rc_detalle_tesoreria_facturas ";
        $sql .= "WHERE	tmp_recibo_id = '" . $this->recibo['tmp_recibo_id'] . "' ";
        $sql .= "AND		empresa_id = '" . $this->recibo['empresa_id'] . "'; ";

        //STEVEN
        $sql .= "DELETE FROM facturas_rc_detalles_tmp
                                 WHERE  tmp_recibo_id = '" . $this->recibo['tmp_recibo_id'] . "' 
                                 AND    empresa_id = '" . $this->recibo['empresa_id'] . "'; ";

        $sql .= "DELETE FROM facturas_rc_tmp
                                 WHERE  tmp_recibo_id = '" . $this->recibo['tmp_recibo_id'] . "' 
                                 AND    empresa_id = '" . $this->recibo['empresa_id'] . "'; ";
        //FIN ST

        $sql .= "DELETE FROM tmp_recibos_caja ";
        $sql .= "WHERE	tmp_recibo_id = '" . $this->recibo['tmp_recibo_id'] . "' ";
        $sql .= "AND		empresa_id = '" . $this->recibo['empresa_id'] . "'; ";

        $sql .= "DELETE FROM 	tmp_cheques_mov_rc ";
        $sql .= "WHERE	recibo_caja = " . $this->recibo['tmp_recibo_id'] . " ";
        $sql .= "AND		empresa_id = '" . $this->recibo['empresa_id'] . "'; ";

        $sql .= "DELETE FROM tmp_tarjetas_mov_debito ";
        $sql .= "WHERE	recibo_caja = " . $this->recibo['tmp_recibo_id'] . " ";
        $sql .= "AND		empresa_id = '" . $this->recibo['empresa_id'] . "'; ";

        $sql .= "DELETE FROM 	tmp_tarjetas_mov_credito ";
        $sql .= "WHERE	recibo_caja = " . $this->recibo['tmp_recibo_id'] . " ";
        $sql .= "AND		empresa_id = '" . $this->recibo['empresa_id'] . "'; ";

        $sql .= "DELETE FROM 	tmp_bancos_consignaciones ";
        $sql .= "WHERE	tmp_recibo_id = " . $this->recibo['tmp_recibo_id'] . " ";
        $sql .= "AND		empresa_id = '" . $this->recibo['empresa_id'] . "'; ";

        $sql .= "DELETE FROM tmp_rc_detalle_tesoreria_notas_credito ";
        $sql .= "WHERE	tmp_recibo_id = '" . $this->recibo['tmp_recibo_id'] . "' ";
        $sql .= "AND		empresa_id = '" . $this->recibo['empresa_id'] . "'; ";


        /* echo "<pre>====== 13.sql bORRAR TMOPS=========";
          var_dump($sql);
          echo "===============</pre>"; */

        //	if($this->cantidad > 500)
        //		$sql .= "ALTER TABLE rc_detalle_tesoreria_facturas ENABLE TRIGGER trigger_rc_detalle_tesoreria_facturas_numero_recibo;";

        $rst = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->mensajeDeError = $sql;
            $this->frmError['MensajeError'] = "2 Error al iniciar la transaccion Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

       $tipo_doc = $this->getTipoDocumento($_SESSION['Documentos']['rc_tipo_documento']);
       $logs = new app_RecibosCaja_Funciones();
      // $logs->registrar_resultado_sincronizacion($numeracion[0], $numeracion[1], "Pendiente por sincronizar*", 1);
        //comentado temporalmente para cambiar fechas de recibos

             //WS 
           $dusoft_fi = AutoCarga::factory("SincronizacionDusoftFI", "SincronizacionDusoftFI", "", "");

            $resultado_ws = $dusoft_fi->enviarRCWSFI($numeracion[1], $numeracion[0], $documento, $_SESSION['RCFactura']['empresa']);
            $logs->registrar_resultado_sincronizacion($numeracion[0], $numeracion[1], "Pendiente por sincronizar*", 1);

            if($resultado_ws['crearInformacionContableResult']['estado']=='true')
            {
                $insert_ws = $this->insertDataWSFacturacionFI($numeracion[0], $numeracion[1], $resultado_ws['crearInformacionContableResult']['descripcion'], '0');
                $logs->registrar_resultado_sincronizacion($numeracion[0], $numeracion[1], "sincronizado*** - ". $resultado_ws['crearInformacionContableResult']['descripcion'] , 0);
            }
            else
            {
               $insert_ws = $this->insertDataWSFacturacionFI($numeracion[0], $numeracion[1], $resultado_ws['crearInformacionContableResult']['descripcion'], '1');
               $logs->registrar_resultado_sincronizacion($numeracion[0], $numeracion[1], "Pendiente por sincronizar***  - ". $resultado_ws['crearInformacionContableResult']['descripcion'] , 1);
            }
        
        
        $numerofi = "";
        if(isset($resultado_ws['crearInformacionContableResult']['numerodoc'])){
            $numerofi = ", Numero documento creado: ".$resultado_ws['crearInformacionContableResult']['numerodoc'];
        }


        $this->Arreglo = array('prefijo' => $numeracion[0], 'recibo_caja' => $numeracion[1],
            'valor_recibo' => $this->recibo['total_abono'], 'tercero_id' => $_REQUEST['tercero_id'],
            'tipo_id_tercero' => $_REQUEST['tercero_tipo'], 'tercero_nombre' => $_REQUEST['tercero_nombre'], 'mensaje_ws' => $resultado_ws['crearInformacionContableResult']['descripcion']. $numerofi);

        $dbconn->CommitTrans();

        return true;
    }

    /*
      function enviarRCWSFISiPago($encabezado,$cuenta_tercero,$cuenta_forma_pago)
      {
      require_once ('nusoap/lib/nusoap.php');
      $url_wsdl = "http://10.0.6.58:8080/SinergiasFinanciero3-ejb/getGestionInformacionContableWS/getGestionInformacionContableWS?wsdl";
      $soapclient = new nusoap_client($url_wsdl,true);
      $function = "crearInformacionContable";

      if(strlen($encabezado['observacion'])<10)
      {
      $encabezado['observacion'] = "SIN OBSERVACION PARA EL ENCABEZADO";
      }
      else
      {
      $encabezado['observacion'] = $encabezado['observacion'];
      }
      $encabezado_rc = array('coddocumentoencabezado'=>'SS001',
      'codempresa'=>'COS',
      'estadoencabezado'=>'4',
      'fecharegistroencabezado'=>$encabezado['fecha_registro'],
      'identerceroencabezado'=>$encabezado['tercero_id'],
      'numerodocumentoencabezado'=>$encabezado['recibo_caja'],
      'observacionencabezado'=>$encabezado['observacion'],
      'usuariocreacion'=>$encabezado['usuario_id']
      );
      $asiento = array();

      //DEBITO
      $asiento[0] = array('codcentrocostoasiento'=>'0',
      'codcentroutilidadasiento'=>'0',
      'codcuentaasiento'=>$cuenta_tercero,
      'codlineacostoasiento'=>'0',
      'identerceroasiento'=>$encabezado['tercero_id'],
      'observacionasiento'=>'SIN OBSERVACION PARA EL ASIENTO',
      'valorbaseasiento'=>'0',
      'valorcreditoasiento'=>'0',
      'valordebitoasiento'=>(int)($encabezado['total_abono']),
      'valortasaasiento'=>'0'
      );

      //CREDITO
      $asiento[1] = array('codcentrocostoasiento'=>'0',
      'codcentroutilidadasiento'=>'0',
      'codcuentaasiento'=>$cuenta_forma_pago,
      'codlineacostoasiento'=>'0',
      'identerceroasiento'=>$encabezado['tercero_id'],
      'observacionasiento'=>'SIN OBSERVACION PARA EL ASIENTO',
      'valorbaseasiento'=>'0',
      'valorcreditoasiento'=>(int)($encabezado['total_abono']),
      'valordebitoasiento'=>'0',
      'valortasaasiento'=>'0'
      );

      $inputs = array('encabezadofactura'=>$encabezado_rc,
      'asientoscontables'=>$asiento);

      $resultado = $soapclient->call($function,$inputs);

      return $resultado;
      }

      function getTipoTarjeta($tabla,$prefijo,$id_rc)
      {
      $sql = "";
      $sql .= "SELECT *
      FROM   ".$tabla."
      WHERE  prefijo = '".$prefijo."'
      AND    recibo_caja = '".$id_rc."' ; ";

      if (!$rst = $this->ConexionBaseDatos($sql))
      return false;

      //$datos = array();
      while (!$rst->EOF) {
      $datos = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
      }

      $rst->Close();
      return $datos;
      }

      function getCuentaContableFormaPago($id_forma_pago)
      {
      $sql = "";
      $sql .= "SELECT cuenta_contable
      FROM   forma_pago_rt
      WHERE  id_forma_pago = '".$id_forma_pago."'; ";

      if (!$rst = $this->ConexionBaseDatos($sql))
      return false;

      //$datos = array();
      while (!$rst->EOF) {
      $datos = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
      }

      $rst->Close();
      return $datos;
      }

      function getCuentaContableTercero($tercero)
      {
      require_once ('nusoap/lib/nusoap.php');
      $url_wsdl = "http://10.0.6.58:8080/SinergiasFinanciero3-ejb/getTercerosClienteWS/getTercerosClienteWS?wsdl";
      $soapclient = new nusoap_client($url_wsdl,true);
      $function = "buscarTerceroCliente";

      $inputs = array('idempresa'=>'1',
      'numeroidentificacion'=>$tercero);

      $resultado = $soapclient->call($function,$inputs);

      return $resultado;
      }

      //Datos para el WS FI
      function getEncabezadoRCWSFI($rc_id,$prefijo)
      {
      $sql = "";
      $sql .= "   SELECT  RC.*,
      TO_CHAR(RC.fecha_registro, 'YYYY-MM-DD') as fecha_registro
      FROM    recibos_caja RC
      WHERE   RC.recibo_caja = '".$rc_id."'
      AND     RC.prefijo = '".$prefijo."'; ";
      if (!$rst = $this->ConexionBaseDatos($sql))
      return false;

      //$datos = array();
      while (!$rst->EOF) {
      $datos = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
      }

      $rst->Close();
      return $datos;
      }

      function getDetalleRCWSFI($rc_id,$prefijo)
      {
      $sql = "";
      $sql .= "   SELECT  RDTF.*,
      '27052501' AS cuenta_debito,
      CASE WHEN CXP.cuenta != ''
      THEN CXP.cuenta
      ELSE '0'
      END AS cuenta_credito,
      '0' AS centro_costo,
      '0' AS centro_utilidad,
      '0' AS linea_costo
      FROM    rc_detalle_tesoreria_facturas RDTF
      LEFT JOIN fac_facturas FF ON (RDTF.prefijo_factura = FF.prefijo AND RDTF.factura_fiscal = FF.factura_fiscal)
      LEFT JOIN cg_mov_01.cuentas_x_planes CXP ON (CXP.plan_id = FF.plan_id)
      WHERE RDTF.recibo_caja = '".$rc_id."'
      AND RDTF.prefijo = '".$prefijo."'; ";

      if (!$rst = $this->ConexionBaseDatos($sql))
      return false;

      $datos = array();
      while (!$rst->EOF) {
      $datos[] = $rst->GetRowAssoc($ToUpper = false);
      ;
      $rst->MoveNext();
      }

      $rst->Close();
      return $datos;
      }

      function enviarRCWSFI($encabezado, $detalle)
      {
      require_once ('nusoap/lib/nusoap.php');
      $url_wsdl = "http://10.0.6.58:8080/SinergiasFinanciero3-ejb/getGestionInformacionContableWS/getGestionInformacionContableWS?wsdl";
      $soapclient = new nusoap_client($url_wsdl,true);
      $function = "crearInformacionContable";

      if(strlen($encabezado['observacion'])<10)
      {
      $encabezado['observacion'] = "SIN OBSERVACION PARA EL ENCABEZADO";
      }
      else
      {
      $encabezado['observacion'] = $encabezado['observacion'];
      }
      $encabezado_rc = array('coddocumentoencabezado'=>'SS001',
      'codempresa'=>'COS',
      'estadoencabezado'=>'4',
      'fecharegistroencabezado'=>$encabezado['fecha_registro'],
      'identerceroencabezado'=>$encabezado['tercero_id'],
      'numerodocumentoencabezado'=>$encabezado['recibo_caja'],
      'observacionencabezado'=>$encabezado['observacion'],
      'usuariocreacion'=>$encabezado['usuario_id']
      );
      $asiento = array();
      $total_saldo = 0;
      for($i=0;$i<count($detalle);$i++)
      {
      $asiento[] = array('codcentrocostoasiento'=>$detalle[$i]['centro_costo'],
      'codcentroutilidadasiento'=>$detalle[$i]['centro_utilidad'],
      'codcuentaasiento'=>$detalle[0]['cuenta_credito'],
      'codlineacostoasiento'=>$detalle[$i]['linea_costo'],
      'identerceroasiento'=>$encabezado['tercero_id'],
      'observacionasiento'=>'SIN OBSERVACION PARA EL ASIENTO',
      'valorbaseasiento'=>'0',
      'valorcreditoasiento'=>(int)($detalle[$i]['valor_efectivo']),
      'valordebitoasiento'=>'0',
      'valortasaasiento'=>'0'
      );
      $total_saldo += $detalle[$i]['valor_efectivo'];
      }

      $asiento[] = array('codcentrocostoasiento'=>$detalle[0]['centro_costo'],
      'codcentroutilidadasiento'=>$detalle[0]['centro_utilidad'],
      'codcuentaasiento'=>$detalle[0]['cuenta_debito'],
      'codlineacostoasiento'=>$detalle[0]['linea_costo'],
      'identerceroasiento'=>$encabezado['tercero_id'],
      'observacionasiento'=>'SIN OBSERVACION PARA EL ASIENTO',
      'valorbaseasiento'=>'0',
      'valorcreditoasiento'=>'0',
      'valordebitoasiento'=>$total_saldo,
      'valortasaasiento'=>'0'
      );

      $inputs = array('encabezadofactura'=>$encabezado_rc,
      'asientoscontables'=>$asiento);

      $resultado = $soapclient->call($function,$inputs);

      return $resultado;
      }

      function insertDataWSFacturacionFI($prefijo,$factura,$descripcion,$estado)
      {
      $sql = "";
      $sql .= "INSERT INTO facturacion_ws_fi (prefijo,factura_fiscal,mensaje,estado)
      VALUES ('".$prefijo."',
      '".$factura."',
      '".$descripcion."',
      '".$estado."');";

      if (!$rst = $this->ConexionBaseDatos($sql))
      return false;

      return true;
      }
      //FIN datos FI
     */
    
    function insertDataWSFacturacionFI($prefijo,$factura,$descripcion,$estado)
      {
      $sql = "";
      $sql .= "INSERT INTO facturacion_ws_fi (prefijo,factura_fiscal,mensaje,estado)
      VALUES ('".$prefijo."',
      '".$factura."',
      '".$descripcion."',
      '".$estado."');";

      if (!$rst = $this->ConexionBaseDatos($sql))
      return false;

      return true;
      }

    function getTipoDocumento($rc_id)
    {
        $sql = "";
        $sql .= "SELECT tipo_documento_descripion as rc_tipo
                 FROM   rc_tipos_documentos
                 WHERE  rc_tipo_documento = '" . $rc_id . "'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        //$datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    function getRCFacturasInvFacturasDespacho($prefijo, $recibo)
    {
        $sql = "";
        $sql .= "SELECT *
                 FROM   rc_detalle_tesoreria_facturas 
                 WHERE  recibo_caja = '" . $recibo . "' 
                 AND    prefijo = '" . $prefijo . "' 
                 AND    prefijo_factura IN('ME','FDC','FDB','BM'); ";


        /* echo "<pre> ===== SQL getRCFacturasInvFacturasDespacho ========";
          print_r($sql);
          echo "</pre>"; */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    function obtenerDetallesFacturasTMP($tmp_recibo_id)
    {
        $sql = "";
        $sql .= "   SELECT  recibo_caja,
                                        prefijo_rc,
                                        factura_fiscal,
                                        prefijo_factura,
                                        empresa_id,
                                        centro_utilidad,
                                        valor_detalle
                                FROM    facturas_rc_detalles_tmp 
                                WHERE   tmp_recibo_id = '" . $tmp_recibo_id . "'";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    function obtenerFacturasTMP($tmp_recibo_id)
    {
        $sql = "";
        $sql .= "   SELECT  prefijo_factura, 
                                        factura_fiscal, 
                                        valor_abonado
                                FROM    facturas_rc_tmp 
                                WHERE   tmp_recibo_id = '" . $tmp_recibo_id . "'";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    /*     * ********************************************************************************
     * Funcion donde se le asigna a las facturas los correspondientes abonos 
     * 
     * @return boolean 
     * ********************************************************************************* */

    function PagarFacturas()
    {
        $total = $_REQUEST['total'];
        $saldoRC = $_REQUEST['saldoRC'];

        /* echo "<pre>";
          print_r($_REQUEST);
          echo "</pre>";
          exit(0); */

        for ($i = 0; $i < $total; $i++)
        {
            if (trim($_REQUEST['retefuente' . $i]) == "")
            {
                $_REQUEST['retefuente' . $i] = 0;
            }
            if (trim($_REQUEST['retecre' . $i]) == "")
            {
                $_REQUEST['retecre' . $i] = 0;
            }
            if (trim($_REQUEST['reteica' . $i]) == "")
            {
                $_REQUEST['reteica' . $i] = 0;
            }

            /*
              $this->Valor[$i] = $_REQUEST['valorfac'.$i];
              $this->ValorS[$i] = $_REQUEST['valorsaldo'.$i];
              $this->Factura[$i] = explode("*",$_REQUEST['factura'.$i]);
             */
            $this->Valor[$i] = $_REQUEST['valorfac' . $i] + $_REQUEST['retefuente' . $i] + $_REQUEST['retecre' . $i] + $_REQUEST['reteica' . $i];
            $this->ValorRc[$i] = $_REQUEST['valorfac' . $i];
            $this->ValorS[$i] = $_REQUEST['valorsaldo' . $i];
            $this->Factura[$i] = explode("*", $_REQUEST['factura' . $i]);
            //IMPUESTOS
            $this->Retefuente[$i] = $_REQUEST['retefuente' . $i];
            $this->Retecre[$i] = $_REQUEST['retecre' . $i];
            $this->Reteica[$i] = $_REQUEST['reteica' . $i];

            /* echo $this->Valor[$i]."<br>";
              echo $this->ValorRc[$i]."<br>";
              echo $this->Retefuente[$i]."<br>";
              echo $this->Retecre[$i]."<br>";
              echo $this->Reteica[$i]."<br><br>"; */
        }

        $this->frmError['MensajeError'] = "";
        for ($i = 0; $i < $total; $i++)
        {
            if ($this->Valor[$i] != "" && $this->Valor[$i] != "0")
            {
                if ($this->ValorS[$i] < $this->Valor[$i])
                {
                    $this->frmError['MensajeError'] = "EL VALOR PAGADO DE LA FACTURA Nª " . $this->Factura[$i][0] . $this->Factura[$i][1] . " NO DEBE SER MAYOR QUE EL VALOR DEL SALDO";
                    $this->MostrarFacturas();
                    return true;
                }
            }
        }

        for ($i = 0; $i < $total; $i++)
        {

            if ($this->Valor[$i] != "" && $this->Valor[$i] != "0")
            {
                $sql .= "INSERT INTO tmp_rc_detalle_tesoreria_facturas( 
               				tmp_rc_id, 
               				empresa_id ,
               				centro_utilidad ,
               				tmp_recibo_id ,
               				prefijo_factura ,
               				factura_fiscal ,
               				valor_abonado,  
               				valor_efectivo,  
               				retefuente,  
               				retecre,  
               				reteica)  
               VALUES(
               				(SELECT COALESCE(MAX(tmp_rc_id),0)+1 FROM tmp_rc_detalle_tesoreria_facturas), 
               			'" . $_SESSION['RCFactura']['empresa'] . "' , 
               			'" . $_REQUEST['centro'] . "' ,
               			 " . $_REQUEST['recibo_caja'] . " ,
               			'" . $this->Factura[$i][0] . "' ,
               			 " . $this->Factura[$i][1] . " ,
               			 " . $this->Valor[$i] . ", 
               			 " . $this->ValorRc[$i] . ", 
               			 " . $this->Retefuente[$i] . ", 
               			 " . $this->Retecre[$i] . ", 
               			 " . $this->Reteica[$i] . "); ";

                //STEVEN
                $sql .= "INSERT INTO facturas_rc_tmp (
                                                                                tmp_recibo_id,
                                                                                empresa_id,
                                                                                centro_utilidad,
                                                                                valor_abonado,
                                                                                prefijo_factura,
                                                                                factura_fiscal)
                                                        VALUES('" . $_REQUEST['recibo_caja'] . "', 
                                                               '" . $_SESSION['RCFactura']['empresa'] . "',
                                                               '" . $_REQUEST['centro'] . "',
                                                               '" . $this->Valor[$i] . "',
                                                               '" . $this->Factura[$i][0] . "', 
                                                               '" . $this->Factura[$i][1] . "' ); ";
                //STEVEN

                $fac .= " " . $this->Factura[$i][0] . $this->Factura[$i][1] . ", ";



                //$sql .= "update inv_facturas_despacho set saldo = saldo - {$this->Valor[$i]} where prefijo='{$this->Factura[$i][0]}' and factura_fiscal = '{$this->Factura[$i][1]}'; ";
                //$sql .= "update inv_facturas_agrupadas_despacho set saldo = saldo - {$this->Valor[$i]} where prefijo='{$this->Factura[$i][0]}' and factura_fiscal = '{$this->Factura[$i][1]}'; ";


                /* echo "<pre>========= facturas =========";
                  print_r($fac);
                  echo "============</pre>"; */
            }
        }

        /* echo "<pre>======7.sql insert... =========";
          var_dump($sql);
          echo "===============</pre>"; */

        //echo $sql."<br>";
        //exit(0);
        /* echo 'REQUEST SQL:<pre>';
          print_r($_REQUEST);
          echo '</pre>'; */
        //STEVEN
        for ($st = 0; $st < count($_REQUEST['factura_rc']); $st++)
        {
            $prefijos_id = explode('*', $_REQUEST['factura_rc'][$st]);
            if ($_REQUEST['rc_ti'][$st] > 0)
            {
                //echo 'prefijos: '.var_dump($prefijos_id)."<br><br>";
                $sql .= "INSERT INTO facturas_rc_detalles_tmp(
                                                                                tmp_recibo_id,
                                                                                prefijo_factura,
                                                                                factura_fiscal,
                                                                                prefijo_rc,
                                                                                recibo_caja,
                                                                                empresa_id,
                                                                                centro_utilidad,
                                                                                valor_detalle,
                                                                                usuario_id) 
                                                            VALUES ('" . $_REQUEST['recibo_caja'] . "', 
                                                                    '" . $prefijos_id[0] . "', 
                                                                    '" . $prefijos_id[1] . "', 
                                                                    '" . $prefijos_id[2] . "', 
                                                                    '" . $prefijos_id[3] . "', 
                                                                    '" . $_SESSION['RCFactura']['empresa'] . "', 
                                                                    '" . $_REQUEST['centro'] . "', 
                                                                    '" . $_REQUEST['rc_ti'][$st] . "', 
                                                                    '" . UserGetUID() . "' ); ";
            }
        }

        /* echo "<pre>======8.sql insert =========";
          var_dump($sql);
          echo "===============</pre>"; */

        for ($q = 0; $q < $_REQUEST['cantidad_rc_h']; $q++)
        {
            $rc_datos = explode('*', $_REQUEST['rc_name_h_' . $q]);
            $excedente = 0;

            for ($t = 0; $t < count($_REQUEST['factura_rc_valor_' . $q]); $t++)
            {
                $excedente += $_REQUEST['factura_rc_valor_' . $q][$t];
            }

            $excedente = $_REQUEST['rc_exceden_h_' . $q] - $excedente;


            /* $sql .= "UPDATE rc_detalles 
              SET    valor_actual='" . $excedente . "'
              WHERE  empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "'
              AND    centro_utilidad= '" . $_REQUEST['centro'] . "'
              AND    recibo_caja = '" . $rc_datos[1] . "'
              AND    prefijo = '" . $rc_datos[0] . "'; "; */

            //--[RQ-13564]-------------------------------------------------------------------------------------
            /* $sql .= "UPDATE rc_detalles
              SET    valor_actual='" . $excedente . "'
              WHERE  empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "'
              AND    recibo_caja = '" . $rc_datos[1] . "'
              AND    prefijo = '" . $rc_datos[0] . "'; "; */

            $sql .= "UPDATE rc_detalles
                                     SET    valor_actual='" . $excedente . "'
                                     WHERE  recibo_caja = '" . $rc_datos[1] . "'
                                     AND    prefijo = '" . $rc_datos[0] . "'; ";

            //--------------------------------------------------------------------------------------------------
        }
        /* echo "<pre>======9.sql upate =========";
          var_dump($sql);
          echo "===============</pre>"; */
        //STEVEN

        /* echo $sql."<br><br>";
          echo '<pre>';
          print_r($_REQUEST);
          echo '</pre>'; */
        /* echo $sql;
          exit(0); */

        if ($sql != "")
        {
            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;
        }
        else
        {
            $this->MostrarFacturas();
            return true;
        }

        $Debitos = $Creditos = 0;
        $recibos = $this->ObtenerValorReciboCaja();
        $Debitos += $recibos[0];
        if ($recibos[1] > 0)
            $Creditos += $recibos[1];

        $Conceptos = $this->ObtenerValorConceptos();

        if (sizeof($Conceptos) > 0)
        {
            for ($i = 0; $i < sizeof($Conceptos); $i++)
            {
                $Celdas = $Conceptos[$i];

                switch ($Celdas['naturaleza'])
                {
                    case 'C': $Creditos += $Celdas['valor'];
                        break;
                    case 'D': $Debitos += $Celdas['valor'];
                        break;
                }
            }
        }

      /*  echo "<pre>== Pagarfacturas ";
        print_r($sql);
        echo "</pre>";*/

        $metodo = "MostrarFacturas";

        $arreglo = array("pagina" => $_REQUEST['pagina'], "tercero_id" => $_REQUEST['tercero_id'], "campo_ordenar" => $_REQUEST['campo_ordenar'],
            "tercero_tipo" => $_REQUEST['tercero_tipo'], "recibo_caja" => $_REQUEST['recibo_caja'], "offset" => $_REQUEST['offset'],
            "prefijo" => $_REQUEST['prefijo'], "centro" => $_REQUEST['centro'], "tercero_nombre" => $_REQUEST['tercero_nombre']);

        $this->action = ModuloGetURL('app', 'RecibosCaja', 'user', $metodo, $arreglo);
        //$informacion = "<br>A LAS SIGUIENTES FACTURAS SE LES REALIZO UN ABONO :".$fac." <br>";
        $informacion = "<br><label class=\"normal_10AN\">EL ABONO DE LAS FACTURAS SE REALIZO CORRECTAMENTE</label><br>";
        $this->FormaInformacion($informacion);
        return true;
    }

    /*     * ********************************************************************************
     * Funcion donde se evaluan los parametros pasados por request cuando se va a 
     * generar un recibo de caja
     *
     * @return boolean 
     * ********************************************************************************* */

    function EvaluarRequestGenerarRC()
    {
        $this->ValorRC = $_REQUEST['valor_rc'];
        $this->FormaPago = $_REQUEST['forma_pago'];
        $this->Observacion = $_REQUEST['observa'];

        $this->parametro = "MensajeError";
        if (!is_numeric($this->ValorRC))
        {
            $this->frmError['MensajeError'] .= "EL VALOR INGRESADO, PARA EL RECIBO DE CAJA ES INCORRECTO <BR>";
            $this->GenerarReciboCaja();
            return true;
        }

        if ($this->FormaPago == "0")
        {
            $this->frmError['MensajeError'] = "SE DEBE SELECCIONAR UNA FORMA DE PAGO";
            $this->GenerarReciboCaja();
            return true;
        }

        switch ($this->FormaPago)
        {
            case 'EF': $this->GenerarReciboCajaBD();
                break;
            case 'OT': $this->GenerarReciboCajaBD();
                break;
            default: $this->RealizarPago();
                break;
        }

        return true;
    }

    /*     * ******************************************************************************** 
     * Funcion donde se procesan los sql, uno que cuenta el numero de facturas segun las 
     * condiciones de busqueda que se den y otro que trae los datos de las mismas 
     * 
     * @return array datos de las facturas 
     * ********************************************************************************** */

    function ObtenerDatosFacturas()
    {
        /* $sql = $_SESSION['SqlContarF'];
          $this->ProcesarSqlConteo($sql);

          $sql = $_SESSION['SqlBuscarF'];

          if ($_SESSION['combo'] != 'BC') {
          if (!$_REQUEST['campo_ordenar']) {
          //$sql .= "ORDER BY FF.prefijo1,FF.factura_fiscal ";
          $sql .= "ORDER BY factura_fiscal desc ";
          }
          else
          $sql .= "ORDER BY " . $_REQUEST['campo_ordenar'] . " ";
          }
          else {
          if (!$_REQUEST['campo_ordenar'])
          $sql .= "ORDER BY IFD.prefijo,IFD.factura_fiscal ";
          else
          $sql .= "ORDER BY " . $_REQUEST['campo_ordenar'] . " ";
          }
          $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;

          if (!$rst = $this->ConexionBaseDatos($sql))
          return false;

          while (!$rst->EOF) {
          $datos[] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
          }
          $rst->Close();

          return $datos; */

        $sql = $_SESSION['SqlContarF'];
        
        //echo "<pre>".$sql."</pre>";

        $this->ProcesarSqlConteo($sql);

        $sql = $_SESSION['SqlBuscarF'];
        

        if (!$_REQUEST['campo_ordenar'])
            $sql .= "ORDER BY factura_fiscal desc ";
        else
            $sql .= "ORDER BY " . $_REQUEST['campo_ordenar'] . " ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;

        /* echo "<pre> SQL ObtenerDatosFacturas";
          print_r($sql);
          echo "</pre>"; */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    /*     * *************************************************************************************
     * Funcion donde se procesan los sql que buscan los datos de los clientes 
     * 
     * @return array 
     * ************************************************************************************* */

    function ObtenerDatosCliente()
    {
        $clientes = array();
        if ($_SESSION['SqlBuscar'] && $_SESSION['SqlContar'])
        {
            $sql = $_SESSION['SqlContar'];

            //if(!$rst = $this->ConexionBaseDatos($sql))
            //return false;
            //$cont = 0;
            //if(!$rst->EOF) $cont = $rst->RecordCount();

            $this->ProcesarSqlConteo($sql, null, $cont);

            $sql = $_SESSION['SqlBuscar'];
            $sql .= "ORDER BY 3 ";
            $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            while (!$rst->EOF)
            {
                $clientes[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();
        }
        return $clientes;
    }

    /*     * *************************************************************************************
     * Funcion donde se obtiene el sql que busca los clientes
     * 
     * @return boolean 
     * ************************************************************************************** */

    function ObtenerSqlBuscarDatosCliente()
    {
        $this->TerceroDocumento = $_REQUEST['tercero_id'];
        $this->TerceroNombre = $_REQUEST['nombre_tercero'];
        $this->TerceroTipoId = $_REQUEST['tipo_id_tercero'];

        if (!$this->TerceroDocumento && $this->TerceroTipoId == "0" && !$this->TerceroTipoId && !$this->TerceroNombre)
        {
            unset($_SESSION['SqlBuscar']);
            $this->frmError['MensajeError'] = "SE DEBE INDICAR UN CRITERIO DE BUSQUEDAD";
            $this->MostrarClientes();
            return true;
        }

        $sql = "SELECT 	T.tipo_id_tercero,";
        $sql .= "					T.tercero_id, ";
        $sql .= "					T.nombre_tercero, ";
        $sql .= "					T.tipo_bloqueo_id ";
        $where = "FROM 	terceros T ";

        $menu = SessionGetVar("Documentos");

        //print_r($menu);
        if ($menu['sw_cruzar_anticipos'] == '1' && $menu['sw_cruce_endosos'] == '0')
        {
            $where .= "				,rc_control_anticipos RA ";
            $where .= "WHERE	RA.tercero_id = T.tercero_id ";
            $where .= "AND		RA.tipo_id_tercero = T.tipo_id_tercero ";
            $where .= "AND		RA.empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";
            $where .= "AND		RA.saldo > 0 ";
            $where .= "AND	  T.tercero_id IS NOT NULL ";
            $where .= "AND		T.nombre_tercero != '' ";
        }
        else
        {
            $where .= "WHERE	T.tercero_id IS NOT NULL ";
            $where .= "AND		T.nombre_tercero != '' ";
        }

        if ($this->TerceroDocumento != "")
            $where .= "AND T.tercero_id = '" . $this->TerceroDocumento . "' ";

        if ($this->TerceroTipoId != "0" && $this->TerceroTipoId != "")
            $where .= "AND T.tipo_id_tercero = '" . $this->TerceroTipoId . "' ";

        if ($this->TerceroNombre != "")
            $where .= "AND T.nombre_tercero LIKE '%" . strtoupper($this->TerceroNombre) . "%' ";

        $_SESSION['SqlBuscar'] = $sql . $where;
        $_SESSION['SqlContar'] = "SELECT COUNT(*) " . $where;

        if ($this->PrimeraVez != 1)
        {
            $this->MostrarClientes();
        }

        return true;
    }

    /*     * *****************************************************************************
     * Funcion donde se obtienen los recibos pendientes de cada cliente 
     * 
     * @return array 
     * ****************************************************************************** */

    function ObtenerRecibosPendientes()
    {
        $documento = ModuloGetVar('app', 'RecibosCaja', 'documento');

        $this->menu = SessionGetVar("Documentos");

        $sql .= "SELECT	RC.tmp_recibo_id, ";
        $sql .= "				TO_CHAR(RC.fecha_registro,'DD/MM/YYYY') AS registro,";
        $sql .= "				RC.total_abono + COALESCE(TD.valor,0) AS debito, ";
        $sql .= "				RC.centro_utilidad, ";
        $sql .= "				COALESCE(TF.valor_abonado,0)+COALESCE(TC.valor,0) AS credito, ";
        $sql .= "				RC.total_abono, ";
        $sql .= "				COALESCE(TN.valor,0) AS total_nota ";
        $sql .= "FROM 	tmp_recibos_caja RC ";
        $sql .= "				LEFT JOIN ( SELECT	SUM(valor_abonado) AS valor_abonado,";
        $sql .= "														tmp_recibo_id, ";
        $sql .= "														empresa_id ";
        $sql .= "										FROM		tmp_rc_detalle_tesoreria_facturas ";
        $sql .= "										GROUP BY 2,3) AS TF ";
        $sql .= "				ON( TF.tmp_recibo_id = RC.tmp_recibo_id AND ";
        $sql .= "						TF.empresa_id = RC.empresa_id ";
        $sql .= "					) ";
        $sql .= "				LEFT JOIN ( SELECT 	SUM(valor) AS valor,";
        $sql .= "														empresa_id,";
        $sql .= "														tmp_recibo_id ";
        $sql .= "										FROM 		tmp_rc_detalle_tesoreria_conceptos ";
        $sql .= "										WHERE		naturaleza = 'D' ";
        $sql .= "										GROUP BY 2,3) AS TD ";
        $sql .= "				ON( TD.tmp_recibo_id = RC.tmp_recibo_id AND ";
        $sql .= "						TD.empresa_id = RC.empresa_id ";
        $sql .= "					) ";
        $sql .= "				LEFT JOIN ( SELECT 	SUM(valor) AS valor,";
        $sql .= "														empresa_id,";
        $sql .= "														tmp_recibo_id ";
        $sql .= "										FROM 		tmp_rc_detalle_tesoreria_conceptos ";
        $sql .= "										WHERE 	naturaleza = 'C' ";
        $sql .= "										GROUP BY 2,3) AS TC ";
        $sql .= "				ON( TC.tmp_recibo_id = RC.tmp_recibo_id AND ";
        $sql .= "						TC.empresa_id = RC.empresa_id ";
        $sql .= "					) ";
        /*         * **NUEVO********* */
        $sql .= "				LEFT JOIN ( SELECT	SUM(valor) AS valor,";
        $sql .= "														tmp_recibo_id, ";
        $sql .= "														empresa_id ";
        $sql .= "										FROM		tmp_rc_detalle_tesoreria_notas_credito ";
        $sql .= "										GROUP BY 2,3) AS TN ";
        $sql .= "				ON( TN.tmp_recibo_id = RC.tmp_recibo_id AND ";
        $sql .= "						TN.empresa_id = RC.empresa_id ";
        $sql .= "					) ";
        /*         * **FIN NUEVO********* */
        $sql .= "WHERE 	RC.tercero_id = '" . $_REQUEST['tercero_id'] . "' ";
        $sql .= "AND 		RC.tipo_id_tercero = '" . $_REQUEST['tercero_tipo'] . "' ";
        //$sql .= "AND		RC.usuario_id = ".UserGetUID()." ";
        $sql .= "AND		RC.rc_tipo_documento = " . $this->menu['rc_tipo_documento'] . " ";
        $sql .=" ORDER BY RC.tmp_recibo_id ";

        //echo "sql:$sql<br>";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $arreglo = array("pagina" => $_REQUEST['pagina'], "tercero_id" => $_REQUEST['tercero_id'],
            "tercero_tipo" => $_REQUEST['tercero_tipo'], "tercero_nombre" => $_REQUEST['tercero_nombre']);

        $i = 0;
        while (!$rst->EOF)
        {
            $documentos[$i] = $rst->GetRowAssoc($ToUpper = false);

            $arreglo['centro'] = $documentos[$i]['centro_utilidad'];
            $arreglo['centro_utilidad'] = $documentos[$i]['centro_utilidad'];
            $arreglo['total_nota'] = $documentos[$i]['total_nota'];
            $arreglo['recibo_caja'] = $documentos[$i]['tmp_recibo_id'];

            $this->action3[$i] = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarFacturas', $arreglo);
            $this->action4[$i] = ModuloGetURL('app', 'RecibosCaja', 'user', 'AdicionarConceptos', array("datos" => $arreglo));
            $this->action6[$i] = ModuloGetURL('app', 'RecibosCaja', 'user', 'EliminarReciboCaja', $arreglo);
            $this->action7[$i] = ModuloGetURL('app', 'RecibosCaja', 'user', 'EliminarFacturasReciboCaja', $arreglo);
            $this->action8[$i] = ModuloGetURL('app', 'RecibosCaja', 'user', 'ModificarInformacion', array("datos" => $arreglo));
            $this->action9[$i] = ModuloGetURL('app', 'RecibosCaja', 'user', 'CruzarNotasCredito', array("datos" => $arreglo));
            $this->actionA[$i] = ModuloGetURL('app', 'RecibosCaja', 'user', 'FormaCruceAutomatico', array("datos" => $arreglo));
            $this->moddocumento[$i] = ModuloGetURL('app', 'RecibosCaja', 'user', 'ModificarValorDocumento', array("documento" => $documentos[$i]['tmp_recibo_id'], "datos" => $arreglo));

            $arreglo['debito'] = $documentos[$i]['debito'] + $documentos[$i]['total_nota'];
            $arreglo['credito'] = $documentos[$i]['credito'];
            $arreglo['total_abono'] = $documentos[$i]['total_abono'];
            $this->action5[$i] = ModuloGetURL('app', 'RecibosCaja', 'user', 'CerrarReciboCaja', array("datos" => $arreglo));
            $rst->MoveNext();
            $i++;
        }
        $rst->Close();

        return $documentos;
    }

    /*     * ***************************************************************************** 
     * Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
     * importantes a la hora de referenciar al paginador
     * 
     * @param String Cadena que contiene la consulta sql del conteo 
     * @param int numero que define el limite de datos,cuando no se desa el del 
     * 			 usuario,si no se pasa se tomara por defecto el del usuario 
     * @return boolean 
     * ****************************************************************************** */

    function ProcesarSqlConteo($consulta, $limite = null, $cantidad = null)
    {
        $this->offset = 0;
        $this->paginaActual = 1;
        if ($limite == null)
        {
            $this->limit = UserGetVar(UserGetUID(), 'LimitRowsBrowser');
            if (!$this->limit)
                $this->limit = 20;
        }
        else
        {
            $this->limit = $limite;
        }

        if ($_REQUEST['offset'])
        {
            $this->paginaActual = intval($_REQUEST['offset']);
            if ($this->paginaActual > 1)
            {
                $this->offset = ($this->paginaActual - 1) * ($this->limit);
            }
        }

        if ($cantidad === null)
        {
            if (!$result = $this->ConexionBaseDatos($consulta))
                return false;

            if (!$result->EOF)
            {
                $this->conteo = $result->fields[0];
                $result->MoveNext();
            }
            $result->Close();
        }
        else
            $this->conteo = $cantidad;
        return true;
    }

    /*     * ******************************************************************************** 
     * Funcion domde se seleccionan los tipos de id de los terceros 
     * 
     * @return array datos de tipo_id_terceros 
     * ********************************************************************************* */

    function ObtenerTipoIdTerceros()
    {
        $sql = "SELECT tipo_id_tercero,descripcion FROM tipo_id_terceros";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $i = 0;
        while (!$rst->EOF)
        {
            $documentos[$i] = $rst->fields[0] . "/" . $rst->fields[1];
            $rst->MoveNext();
            $i++;
        }
        $rst->Close();

        return $documentos;
    }

    /*     * **************************************************************************************
     * Funcion donde se obtiene la numeracion de los recibos de caja 
     * 
     * @return array 
     * *************************************************************************************** */

    function ObtenerNumeracion($sql, &$dbconn)
    {
        $rst = $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0)
        {
            $this->frmError['MensajeError'] = "Error BD " . $dbconn->ErrorMsg();
            return false;
        }

        if (!$rst->EOF)
        {
            $retorno[0] = $rst->fields[0];
            $retorno[1] = $rst->fields[1];
            $rst->MoveNext();
        }

        return $retorno;
    }

    /*     * ******************************************************************************** 
     * Funcion en donde se obtienen los prefijos que maneja la empresa 
     * 
     * @return array datos de la tabla documentos
     * ********************************************************************************** */

    function ObtenerPrefijos()
    {
        $sql = "SELECT DISTINCT prefijo  ";
        $sql .= "FROM ( ";
        $sql .= "   SELECT DISTINCT prefijo ";
        $sql .= "   FROM   fac_facturas ";
        $sql .= "   WHERE  empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";
        $sql .= "   AND    tercero_id = '" . $_REQUEST['tercero_id'] . "' ";
        $sql .= "   AND    tipo_id_tercero = '" . $_REQUEST['tercero_tipo'] . "' ";
        $sql .= "   AND    estado = '0'::bpchar  ";
        $sql .= "   AND    sw_clase_factura = '1'::bpchar ";
        $sql .= "   AND    saldo > 0  ";
        $sql .= "   UNION DISTINCT  ";
        $sql .= "   SELECT DISTINCT prefijo  ";
        $sql .= "   FROM   facturas_externas  ";
        $sql .= "   WHERE  empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";
        $sql .= "   AND    tercero_id = '" . $_REQUEST['tercero_id'] . "' ";
        $sql .= "   AND    tipo_id_tercero = '" . $_REQUEST['tercero_tipo'] . "' ";
        $sql .= "   AND    estado = '0'::bpchar  ";
        $sql .= "   AND saldo > 0  ";
        $sql .= "   UNION DISTINCT  ";
        $sql .= "   SELECT DISTINCT prefijo  ";
        $sql .= "   FROM   inv_facturas_despacho  ";
        $sql .= "   WHERE  empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";
        $sql .= "   AND    saldo > 0  ";
        $sql .= "   ) as F ";

        /*echo "<pre>==========4.SQL Obtener Prefijos===========";
          var_dump($sql);
          echo "=========================</pre>"; */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $i = 0;
        while (!$rst->EOF)
        {
            $datos[$i] = $rst->fields[0];
            $rst->MoveNext();
            $i++;
        }
        $rst->Close();

        return $datos;
    }

    /*     * ******************************************************************************** 
     * Funcion donde se obtiene el sql que permite buscar las facturas 
     * 
     * @return boolean 
     * ********************************************************************************* */

    function ObtenerSqlFacturas()
    {

         /*echo "<pre>";
          print_r($_REQUEST);
          echo "</pre>"; */

        $empresa = $_SESSION['RCFactura']['empresa'];
        $_SESSION['combo'] = $_REQUEST['combo'];
        if (!$_REQUEST['todas'] && $_REQUEST['combo'])
        {

                $this->Numero = $_REQUEST['numero'];
                if ($this->Numero != "")
                {
                    $this->ComboBSQ = $_REQUEST['combo'];
                    switch ($this->ComboBSQ)
                    {
                        case '01':
                            //$where .= "AND (EN.envio_id = " . $this->Numero . " OR FF.numero_envio = " . $this->Numero . " )";
                        break;
                        case 'ME' || 'FDC':
                            $sql = "    SELECT  
                                        TO_CHAR(IFD.fecha_registro, 'DD/MM/YYYY') AS fecha1,
                                        '0' AS envio_id,
                                        '0' AS fecha2,
                                        IFD.valor_total AS total_factura,
                                        IFD.saldo AS saldo,
                                        IFD.prefijo AS prefijo,
                                        IFD.factura_fiscal AS factura_fiscal,
                                        '0' AS valor_glosa,
                                        '0' AS valor_aceptado,
                                        '0' AS abono,
                                        '0' AS retencion_fuente, 1
                                        FROM    inv_facturas_despacho IFD
                                        WHERE   IFD.saldo > 0  
                                        AND IFD.prefijo = '{$this->ComboBSQ}'                               
                                        AND IFD.factura_fiscal = {$this->Numero} 
                                        union all 
                                        SELECT  
                                        TO_CHAR(IFD.fecha_registro, 'DD/MM/YYYY') AS fecha1,
                                        '0' AS envio_id,
                                        '0' AS fecha2,
                                        IFD.valor_total AS total_factura,
                                        IFD.saldo AS saldo,
                                        IFD.prefijo AS prefijo,
                                        IFD.factura_fiscal AS factura_fiscal,
                                        '0' AS valor_glosa,
                                        '0' AS valor_aceptado,
                                        '0' AS abono,
                                        '0' AS retencion_fuente,2 
                                        FROM    inv_facturas_agrupadas_despacho IFD
                                        WHERE   IFD.saldo > 0  
                                        AND IFD.prefijo = '{$this->ComboBSQ}'                               
                                        AND IFD.factura_fiscal = {$this->Numero}  ";
                            break;
                        
                        default:
                            $sql = "
                                            SELECT  
                                                TO_CHAR(FFA.fecha_registro, 'DD/MM/YYYY') AS fecha1,
                                                '0' AS envio_id,
                                                '0' AS fecha2,
                                                FFA.total_factura AS total_factura,
                                                FFA.saldo AS saldo,
                                                FFA.prefijo AS prefijo,
                                                FFA.factura_fiscal AS factura_fiscal,
                                                '0' AS valor_glosa,
                                                '0' AS valor_aceptado,
                                                '0' AS abono,
                                                '0' AS retencion_fuente, 1
                                                FROM    fac_facturas FFA
                                                WHERE   FFA.saldo > 0  
                                                AND FFA.prefijo = '{$this->ComboBSQ}'                               
                                                AND FFA.factura_fiscal = {$this->Numero} 
                                ";
                            
                         break;
                    }
                }
            }
        else
        {

            $sql = "SELECT  
                    TO_CHAR(IFD.fecha_registro, 'DD/MM/YYYY') AS fecha1,
                    '0' AS envio_id,
                    '0' AS fecha2,
                    IFD.valor_total AS total_factura,
                    IFD.saldo AS saldo,
                    IFD.prefijo AS prefijo,
                    IFD.factura_fiscal AS factura_fiscal,
                    '0' AS valor_glosa,
                    '0' AS valor_aceptado,
                    '0' AS abono,
                    '0' AS retencion_fuente,1
                    FROM    inv_facturas_despacho IFD
                    WHERE   IFD.saldo > 0 and IFD.tercero_id={$_REQUEST['tercero_id']} and IFD.tipo_id_tercero='{$_REQUEST['tercero_tipo']}' and IFD.empresa_id='{$_SESSION['RCFactura']['empresa']}' 
                    UNION ALL
                    SELECT  
                    TO_CHAR(IFD.fecha_registro, 'DD/MM/YYYY') AS fecha1,
                    '0' AS envio_id,
                    '0' AS fecha2,
                    IFD.valor_total AS total_factura,
                    IFD.saldo AS saldo,
                    IFD.prefijo AS prefijo,
                    IFD.factura_fiscal AS factura_fiscal,
                    '0' AS valor_glosa,
                    '0' AS valor_aceptado,
                    '0' AS abono,
                    '0' AS retencion_fuente,2
                    FROM    inv_facturas_agrupadas_despacho IFD
                    WHERE   IFD.saldo > 0 and IFD.tercero_id={$_REQUEST['tercero_id']} and IFD.tipo_id_tercero='{$_REQUEST['tercero_tipo']}' and IFD.empresa_id='{$_SESSION['RCFactura']['empresa']}' 
                    UNION ALL
                    SELECT  
                    TO_CHAR(IFD.fecha_registro, 'DD/MM/YYYY') AS fecha1,
                    '0' AS envio_id,
                    '0' AS fecha2,
                    IFD.total_factura,
                    IFD.saldo AS saldo,
                    IFD.prefijo AS prefijo,
                    IFD.factura_fiscal AS factura_fiscal,
                    '0' AS valor_glosa,
                    '0' AS valor_aceptado,
                    '0' AS abono,
                    '0' AS retencion_fuente,2
                    FROM    fac_facturas IFD
                    WHERE   IFD.saldo > 0 and IFD.tercero_id={$_REQUEST['tercero_id']} and IFD.tipo_id_tercero='{$_REQUEST['tercero_tipo']}'  and IFD.empresa_id='{$_SESSION['RCFactura']['empresa']}'
                    
                    ";
        }

        $sql2 = "SELECT COUNT(*) FROM ({$sql}) AS a";
        //$sql2 .= $where;
        //$sql .= $where;

        $_SESSION['SqlBuscarF'] = $sql;
        $_SESSION['SqlContarF'] = $sql2;
     //  echo "<pre>".$sql."</pre>";

        if ($this->PrimeraVez != 1)
        {
            $this->MostrarFacturas();
        }


        /* echo "<pre>";
          print_r($sql);
          echo "</pre>"; */

        return true;
    }

    /*     * *************************************************************************************
     * Funcion donde se obtiene el valor del recibo de caja generado y el valor abonado 
     * 
     * @return array 
     * ************************************************************************************** */

    function ObtenerValorReciboCaja($opc)
    {
        if ($opc == null)
        {
            $sql .= "SELECT	RC.total_abono AS debitos, ";
            $sql .= "				COALESCE(SUM(RCT.valor_abonado),0) AS creditos ";
        }
        else
        {
            $sql .= "SELECT	RC.total_abono + COALESCE(SUM(RCD.valor),0) AS debitos, ";
            $sql .= "				COALESCE(SUM(RCT.valor_abonado),0)+COALESCE(SUM(RCC.valor),0) AS creditos  ";
        }

        $sql .= "FROM 	tmp_recibos_caja RC ";
        $sql .= "				LEFT JOIN ( SELECT	SUM(valor_abonado) AS valor_abonado,tmp_recibo_id ";
        $sql .= "										FROM		tmp_rc_detalle_tesoreria_facturas RCT";
        $sql .= "										GROUP BY 2) AS RCT ";
        $sql .= "				ON(	RCT.tmp_recibo_id = RC.tmp_recibo_id) ";
        if ($opc != null)
        {
            $sql .= "				LEFT JOIN ( SELECT	valor,tmp_recibo_id ";
            $sql .= "										FROM		tmp_rc_detalle_tesoreria_conceptos ";
            $sql .= "										WHERE 	naturaleza = 'D') AS RCD ";
            $sql .= "				ON(	RCD.tmp_recibo_id = RC.tmp_recibo_id ) ";
            $sql .= "				LEFT JOIN ( SELECT	valor,tmp_recibo_id ";
            $sql .= "										FROM		tmp_rc_detalle_tesoreria_conceptos ";
            $sql .= "										WHERE 	naturaleza = 'C') AS RCC ";
            $sql .= "				ON(	RCC.tmp_recibo_id = RC.tmp_recibo_id ) ";
        }
        $sql .= "WHERE	RC.tmp_recibo_id = '" . $_REQUEST['recibo_caja'] . "' ";
        $sql .= "AND		RC.empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";
        $sql .= "GROUP BY RC.total_abono ";


        /* echo "<pre>======10.sql  ObtenerValorReciboCaja =========";
          var_dump($sql);
          echo "===============</pre>"; */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$rst->EOF)
        {
            $datos[0] = $rst->fields[0];
            $datos[1] = $rst->fields[1];
            $rst->MoveNext();
        }
        $rst->Close();
        //echo $sql;
        return $datos;
    }

    /*     * *************************************************************************************
     * Funcion donde se obtiene el valor de los conceptos que pertenecen a un recibo de caja 
     * 
     * @return array datos de los conceptos 
     * ************************************************************************************** */

    function ObtenerValorConceptos()
    {
        $sql .= "SELECT	COALESCE(RCT.valor,0) AS valor,";
        $sql .= " 			RCT.naturaleza, ";
        $sql .= " 			RC.descripcion, ";
        $sql .= " 			RCT.tmp_rc_id, ";
        $sql .= " 			COALESCE(DE.descripcion,'NO ASOCIADO') AS departamento  ";
        $sql .= "FROM 	tmp_rc_detalle_tesoreria_conceptos RCT ";
        $sql .= "				LEFT JOIN departamentos DE ";
        $sql .= "				ON(DE.departamento = RCT.departamento), ";
        $sql .= "				rc_conceptos_tesoreria RC ";
        $sql .= "WHERE	RCT.tmp_recibo_id = '" . $_REQUEST['recibo_caja'] . "' ";
        $sql .= "AND		RCT.empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";
        $sql .= "AND		RCT.concepto_id = RC.concepto_id ";
        $sql .= "AND		RCT.empresa_id = RC.empresa_id ";


        /* echo "<pre>====== 11. sql ObtenerValorConceptos=========";
          var_dump($sql);
          echo "===============</pre>"; */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;


        $i = 0;
        $arreglo = array("pagina" => $_REQUEST['pagina'], "tercero_id" => $_REQUEST['tercero_id'], "total_nota" => $_REQUEST['total_nota'],
            "tercero_tipo" => $_REQUEST['tercero_tipo'], "recibo_caja" => $_REQUEST['recibo_caja'],
            "prefijo" => $_REQUEST['prefijo'], "centro" => $_REQUEST['centro'], "tercero_nombre" => $_REQUEST['tercero_nombre']);

        while (!$rst->EOF)
        {
            $datos[$i] = $rst->GetRowAssoc($ToUpper = false);
            $arreglo['concepto'] = $datos[$i]['tmp_rc_id'];
            $this->actionX[$i] = ModuloGetURL('app', 'RecibosCaja', 'user', 'EliminarConceptos', array("datos" => $arreglo));
            $rst->MoveNext();
            $i++;
        }
        $rst->Close();
        //echo $sql;	
        return $datos;
    }

    /*     * *************************************************************************************
     * Funcion donde se obtienen los conceptos que pueden ser adicionados a un recibo de caja 
     * 
     * @return array datos de los conceptos de tesoreria 
     * ************************************************************************************** */

    function ObtenerConceptosTesoreria()
    {
        $sql = "SELECT concepto_id,";
        $sql .= "				sw_naturaleza, ";
        $sql .= "				descripcion, ";
        $sql .= "				sw_centro_costo ";
        $sql .= "FROM		rc_conceptos_tesoreria ";
        $sql .= "WHERE 	empresa_id ='" . $_SESSION['RCFactura']['empresa'] . "' ";
        $sql .= "ORDER BY 3 ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $i = 0;
        while (!$rst->EOF)
        {
            $datos[$i] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
            $i++;
        }
        $rst->Close();

        return $datos;
    }

    /*     * *************************************************************************************
     * Funcion donde se obtienen las entidades bancarias existentes en la base de datos
     * 
     * @return array
     * ************************************************************************************* */

    function ObtenerBancos()
    {
        $sql = "SELECT banco,";
        $sql .= "		descripcion ";
        $sql .= "FROM	bancos ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $i = 0;
        while (!$rst->EOF)
        {
            $datos[$i] = $rst->fields[0] . "*" . $rst->fields[1];
            $rst->MoveNext();
            $i++;
        }
        $rst->Close();

        return $datos;
    }

    /*     * *************************************************************************************
     * Funcion donde se obtienen los bancos en los cuales la entidad tiene cuentas
     * 
     * @return array 
     * ************************************************************************************* */

    function ObtenerBancosCuentas()
    {
        $sql = "SELECT DISTINCT ";
        $sql .= "		B.banco,";
        $sql .= "		B.descripcion ";
        $sql .= "FROM	bancos B,";
        $sql .= "		bancos_cuentas BC ";
        $sql .= "WHERE	B.banco = BC.banco ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $i = 0;
        while (!$rst->EOF)
        {
            $datos[$i] = $rst->fields[0] . "*" . $rst->fields[1];
            $rst->MoveNext();
            $i++;
        }
        $rst->Close();

        return $datos;
    }

    /*     * *************************************************************************************
     * Funcion donde se obtienen las tarjetas registradas en la base de datos
     * 
     * @return array 
     * ************************************************************************************* */

    function ObtenerTarjetas()
    {
        $sql = "SELECT tarjeta,";
        $sql .= "		descripcion ";
        $sql .= "FROM	tarjetas ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $i = 0;
        while (!$rst->EOF)
        {
            $datos[$i] = $rst->fields[0] . "*" . $rst->fields[1];
            $rst->MoveNext();
            $i++;
        }
        $rst->Close();
        return $datos;
    }

    /*     * *************************************************************************************
     * Funcion donde se evalua una fecha ingresada y se convierte a un formato validopara 
     * ingresar a la base de datos 
     * 
     * @return boolean 
     * ************************************************************************************** */

    function EvaluarFecha(&$fecha1)
    {
        $fecha = explode("/", &$fecha1);
        $resultado = checkdate($fecha[1], $fecha[0], $fecha[2]);
        if ($resultado != 1 || sizeof($fecha) != 3)
        {
            return false;
        }
        $fecha1 = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];
        return true;
    }

    /*     * **********************************************************************************
     * Funcion donde se obtienen los departamentos, de la base de dartos
     * 
     * @return array
     * *********************************************************************************** */

    function ObtenerDepartamentos()
    {
        $sql .= "SELECT	departamento,";
        $sql .= "				descripcion ";
        $sql .= "FROM		departamentos ";
        $sql .= "WHERE	empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $departamentos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();

        return $departamentos;
    }

    /*     * **********************************************************************************
     * Funcion donde se crean las variables para mostrar la confirmacion de la eliminacion 
     * del recibo de caja de tesoreria
     *
     * @return boolean
     * *********************************************************************************** */

    function EliminarReciboCaja()
    {
        $this->menu = SessionGetVar("Documentos");
        $arreglo = array("tercero_id" => $_REQUEST['tercero_id'], "centro" => $_REQUEST['centro'],
            "tercero_nombre" => $_REQUEST['tercero_nombre'], "tmp_id" => $_REQUEST['tmp_id'],
            "recibo_caja" => $_REQUEST['recibo_caja'], "tercero_tipo" => $_REQUEST['tercero_tipo']);

        $this->action = ModuloGetURL('app', 'RecibosCaja', 'user', 'EliminarReciboCajaBD', $arreglo);
        $this->actionM = ModuloGetURL('app', 'RecibosCaja', 'user', 'GenerarReciboCaja', $arreglo);

        $informacion .= "ESTA SEGURO DE QUE DESEA ELIMINAR EL/LA " . strtoupper(trim($this->menu['descripcion'])) . " ?";

        $this->FormaInformacion($informacion);
        return true;
    }

    /*     * *************************************************************************************
     * Funcion donde se eliminan los recibos de caja temporales
     * 
     * @return boolean 
     * ************************************************************************************** */

    function EliminarReciboCajaBD()
    {
        if ($_REQUEST['recibo_caja'])
        {
            //NUEVO ST.
            $facturas_tmp = $this->obtenerDatosFacturaTmp($_REQUEST['recibo_caja']);
            for ($i = 0; $i < count($facturas_tmp); $i++)
            {
                $rc_datos = $this->obtenerDatosFacturaRcTmp($_REQUEST['recibo_caja'], $facturas_tmp[$i]['prefijo_factura'], $facturas_tmp[$i]['factura_fiscal']);
                for ($j = 0; $j < count($rc_datos); $j++)
                {
                    $exce_rc = $this->obtenerExcedenteRCDetalle($rc_datos[$j]['recibo_caja'], $rc_datos[$j]['prefijo_rc']);
                    $valor_new = 0;
                    $valor_new = $exce_rc['valor_actual'] + $rc_datos[$j]['valor_detalle'];
                    $update_valor = $this->updateValorRCDetalles($valor_new, $rc_datos[$j]['recibo_caja'], $rc_datos[$j]['prefijo_rc']);
                }
            }

            $sql .= "DELETE FROM facturas_rc_detalles_tmp WHERE tmp_recibo_id = '" . $_REQUEST['recibo_caja'] . "'; ";
            $sql .= "DELETE FROM facturas_rc_tmp WHERE tmp_recibo_id = '" . $_REQUEST['recibo_caja'] . "'; ";
            //FIN
            $sql .= "DELETE FROM tmp_rc_detalle_tesoreria_conceptos WHERE tmp_recibo_id = " . $_REQUEST['recibo_caja'] . "; ";
            $sql .= "DELETE FROM tmp_rc_detalle_tesoreria_facturas WHERE tmp_recibo_id = " . $_REQUEST['recibo_caja'] . "; ";
            $sql .= "DELETE FROM tmp_recibos_caja WHERE tmp_recibo_id = " . $_REQUEST['recibo_caja'] . "; ";

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;


            $this->menu = SessionGetVar("Documentos");

            $_REQUEST['recibo_caja'] = "";
            $this->parametro = "Informacion";
            $this->frmError['Informacion'] .= "EL/LA " . strtoupper(trim($this->menu['descripcion'])) . " HA SIDO ELIMINADO";
        }
        $this->GenerarReciboCaja();
        $this->parametro = "";
        return true;
    }

    function updateValorRCDetalles($valor, $recibo_caja, $prefijo)
    {
        $sql = "";
        $sql .= "UPDATE rc_detalles
                             SET    valor_actual = '" . $valor . "' 
                             WHERE  recibo_caja = '" . $recibo_caja . "' 
                             AND    prefijo = '" . $prefijo . "' ;";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        return true;
    }

    function obtenerExcedenteRCDetalle($recibo_caja, $prefijo)
    {
        $sql = "";
        $sql .= "   SELECT  valor_actual
                                FROM    rc_detalles
                                WHERE   recibo_caja = '" . $recibo_caja . "'
                                AND     prefijo='" . $prefijo . "'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function obtenerDatosFacturaRcTmp($tmp_recibo_id, $prefijo_factura, $factura_fiscal)
    {
        $sql = "";
        $sql .= "   SELECT  recibo_caja, 
                                        prefijo_rc, 
                                        valor_detalle
                                FROM    facturas_rc_detalles_tmp
                                WHERE   tmp_recibo_id = '" . $tmp_recibo_id . "'
                                AND     prefijo_factura = '" . $prefijo_factura . "' 
                                AND     factura_fiscal = '" . $factura_fiscal . "'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();

        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function obtenerDatosFacturaTmp($tmp_recibo_id)
    {
        $sql = "";
        $sql .= "   SELECT  prefijo_factura, 
                                        factura_fiscal
                                FROM    facturas_rc_tmp 
                                WHERE   tmp_recibo_id = '" . $tmp_recibo_id . "'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();

        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function MostrarRecibosCerrados()
    {
        $this->Usuario = $_REQUEST['usuario'];
        $this->RNNumero = $_REQUEST['numero_recibo'];
        $this->FechaFin = $_REQUEST['fecha_fin'];
        $this->FechaInicio = $_REQUEST['fecha_inicio'];

        $this->arreglo = array("numero_recibo" => $this->RNNumero, "fecha_inicio" => $this->FechaInicio,
            "fecha_fin" => $this->FechaFin, "usuario" => $this->Usuario);

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarMenuPrincipalRC');
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados', $this->arreglo);
        $this->action3 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados');
        $this->FormaMostrarRecibosCerrados();
        return true;
    }

    /*     * *************************************************************************************
     * Steven H. Gamboa
     * ************************************************************************************** */

    function MostrarOpcionesAnulacionDocumentos()
    {
        $this->Usuario = $_REQUEST['usuario'];
        $this->RNNumero = $_REQUEST['numero_recibo'];
        $this->FechaFin = $_REQUEST['fecha_fin'];
        $this->FechaInicio = $_REQUEST['fecha_inicio'];

        $this->arreglo = array("numero_recibo" => $this->RNNumero, "fecha_inicio" => $this->FechaInicio,
            "fecha_fin" => $this->FechaFin, "usuario" => $this->Usuario);

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarMenuPrincipalRC');
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados', $this->arreglo);
        $this->action3 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados');
        $this->FormaMostrarOpcionesAnulacionDocumentos();
        return true;
    }

    /*     * *************************************************************************************
     * Steven H. Gamboa
     * ************************************************************************************** */

    function MostrarNoIdentificados()
    {

        $this->Usuario = $_REQUEST['usuario'];
        $this->RNNumero = $_REQUEST['numero_recibo'];
        $this->FechaFin = $_REQUEST['fecha_fin'];
        $this->FechaInicio = $_REQUEST['fecha_inicio'];

        $this->arreglo = array("numero_recibo" => $this->RNNumero, "fecha_inicio" => $this->FechaInicio, "fecha_fin" => $this->FechaFin, "usuario" => $this->Usuario);

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarMenuPrincipalRC');
        $this->volver = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarOpcionesAnulacionDocumentos');
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados', $this->arreglo);
        $this->action3 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados');
        $this->FormaMostrarNoIdentificados();
        return true;
    }

    function MostrarBusquedaAnulados()
    {
        $this->Usuario = $_REQUEST['usuario'];
        $this->RNNumero = $_REQUEST['numero_recibo'];
        $this->FechaFin = $_REQUEST['fecha_fin'];
        $this->FechaInicio = $_REQUEST['fecha_inicio'];

        $this->arreglo = array("numero_recibo" => $this->RNNumero, "fecha_inicio" => $this->FechaInicio,
            "fecha_fin" => $this->FechaFin, "usuario" => $this->Usuario);

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarMenuPrincipalRC');
        $this->volver = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarOpcionesAnulacionDocumentos');
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados', $this->arreglo);
        $this->action3 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados');
        $this->FormaMostrarBusquedaAnulados();
        return true;
    }

    function MostrarAdvertencia()
    {
        $this->Usuario = $_REQUEST['usuario'];
        $this->RNNumero = $_REQUEST['numero_recibo'];
        $this->FechaFin = $_REQUEST['fecha_fin'];
        $this->FechaInicio = $_REQUEST['fecha_inicio'];

        $this->arreglo = array("numero_recibo" => $this->RNNumero, "fecha_inicio" => $this->FechaInicio,
            "fecha_fin" => $this->FechaFin, "usuario" => $this->Usuario);

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarMenuPrincipalRC');
        $this->volver = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarOpcionesAnulacionDocumentos');
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados', $this->arreglo);
        $this->action3 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados');
        $this->FormaMostrarAdvertencia();
        return true;
    }

    function MostrarFormaAnularRC()
    {
        $this->Usuario = $_REQUEST['usuario'];
        $this->RNNumero = $_REQUEST['numero_recibo'];
        $this->FechaFin = $_REQUEST['fecha_fin'];
        $this->FechaInicio = $_REQUEST['fecha_inicio'];

        $this->arreglo = array("numero_recibo" => $this->RNNumero, "fecha_inicio" => $this->FechaInicio,
            "fecha_fin" => $this->FechaFin, "usuario" => $this->Usuario);

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarMenuPrincipalRC');
        $this->volver = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarOpcionesAnulacionDocumentos');
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados', $this->arreglo);
        $this->action3 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados');
        $this->FormaAnularRC();
        return true;
    }

    function MostrarAnularContraFacturaRC()
    {
        $this->Usuario = $_REQUEST['usuario'];
        $this->RNNumero = $_REQUEST['numero_recibo'];
        $this->FechaFin = $_REQUEST['fecha_fin'];
        $this->FechaInicio = $_REQUEST['fecha_inicio'];

        $this->arreglo = array("numero_recibo" => $this->RNNumero, "fecha_inicio" => $this->FechaInicio,
            "fecha_fin" => $this->FechaFin, "usuario" => $this->Usuario);

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarMenuPrincipalRC');
        $this->volver = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarOpcionesAnulacionDocumentos');
        $this->volver2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarFormaAnularContraFactura');
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados', $this->arreglo);
        $this->action3 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados');
        $this->FormaMostrarAnularContraFacturaRC();
        return true;
    }

    function MostrarMensajeAdvertenciaAnulacionContraFactura()
    {
        $this->Usuario = $_REQUEST['usuario'];
        $this->RNNumero = $_REQUEST['numero_recibo'];
        $this->FechaFin = $_REQUEST['fecha_fin'];
        $this->FechaInicio = $_REQUEST['fecha_inicio'];

        $this->arreglo = array("numero_recibo" => $this->RNNumero, "fecha_inicio" => $this->FechaInicio,
            "fecha_fin" => $this->FechaFin, "usuario" => $this->Usuario);

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarMenuPrincipalRC');
        $this->volver = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarOpcionesAnulacionDocumentos');
        $this->volver2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarFormaAnularContraFactura');
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados', $this->arreglo);
        $this->action3 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados');
        $this->FormaMostrarMensajeAdvertenciaAnulacionContraFactura();
        return true;
    }

    function MostrarFormaAnularContraFactura()
    {
        $this->Usuario = $_REQUEST['usuario'];
        $this->RNNumero = $_REQUEST['numero_recibo'];
        $this->FechaFin = $_REQUEST['fecha_fin'];
        $this->FechaInicio = $_REQUEST['fecha_inicio'];

        $this->arreglo = array("numero_recibo" => $this->RNNumero, "fecha_inicio" => $this->FechaInicio,
            "fecha_fin" => $this->FechaFin, "usuario" => $this->Usuario);

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarMenuPrincipalRC');
        $this->volver = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarOpcionesAnulacionDocumentos');
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados', $this->arreglo);
        $this->action3 = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados');
        $this->FormaAnularContraFactura();
        return true;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function AnularReciboCaja()
    {
        $datos = $_REQUEST['datos'];

        $this->actionM = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados', $datos);
        $this->action = ModuloGetURL('app', 'RecibosCaja', 'user', 'AnularReciboCajaBD', array("datos" => $datos));

        $informacion = "<br>ESTA SEGURO, DE QUE DESEA ANULAR EL DOCUMENTO Nº " . $datos['prefijo'] . " " . $datos['recibo_caja'] . " ?<br>";
        $this->FormaInformacion($informacion);
        return true;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function AnularReciboCajaBD()
    {
        $datos = $_REQUEST['datos'];

        $sql = "SELECT * ";
        $sql .= "FROM 	recibos_caja ";
        $sql .= "WHERE	recibo_caja = " . $datos['recibo_caja'] . " ";
        $sql .= "AND		prefijo = '" . $datos['prefijo'] . "' ";
        $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$rst->EOF)
        {
            $this->recibo = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        $sql = "SELECT * ";
        $sql .= "FROM 	rc_detalle_tesoreria_conceptos ";
        $sql .= "WHERE	recibo_caja = " . $datos['recibo_caja'] . " ";
        $sql .= "AND		prefijo = '" . $datos['prefijo'] . "' ";
        $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $this->conceptos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        $sql = "SELECT * ";
        $sql .= "FROM 	rc_detalle_tesoreria_facturas ";
        $sql .= "WHERE	recibo_caja = " . $datos['recibo_caja'] . " ";
        $sql .= "AND		prefijo = '" . $datos['prefijo'] . "' ";
        $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $this->facturas[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        if ($this->recibo['total_cheques'] != 0)
        {
            $sql = "SELECT * ";
            $sql .= "FROM 	cheques_mov ";
            $sql .= "WHERE	recibo_caja = " . $datos['recibo_caja'] . " ";
            $sql .= "AND		prefijo = '" . $datos['prefijo'] . "' ";
            $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            while (!$rst->EOF)
            {
                $this->cheques[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();
        }

        if ($this->recibo['total_tarjetas'] != 0)
        {
            $sql = "SELECT * ";
            $sql .= "FROM 	tarjetas_mov_debito ";
            $sql .= "WHERE	recibo_caja = " . $datos['recibo_caja'] . " ";
            $sql .= "AND		prefijo = '" . $datos['prefijo'] . "' ";
            $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            while (!$rst->EOF)
            {
                $this->tarjeta_debito[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();

            $sql = "SELECT * ";
            $sql .= "FROM 	tarjetas_mov_credito ";
            $sql .= "WHERE	recibo_caja = " . $datos['recibo_caja'] . " ";
            $sql .= "AND		prefijo = '" . $datos['prefijo'] . "' ";
            $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            while (!$rst->EOF)
            {
                $this->tarjeta_credito[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();
        }

        if ($this->recibo['total_consignacion'] != 0)
        {
            $sql = "SELECT * ";
            $sql .= "FROM 	bancos_consignaciones ";
            $sql .= "WHERE	recibo_caja = " . $datos['recibo_caja'] . " ";
            $sql .= "AND		prefijo = '" . $datos['prefijo'] . "' ";
            $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            while (!$rst->EOF)
            {
                $this->bancos[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();
        }

        $sql = "SELECT * ";
        $sql .= "FROM 	rc_detalle_tesoreria_notas_credito ";
        $sql .= "WHERE	recibo_caja = " . $datos['recibo_caja'] . " ";
        $sql .= "AND		prefijo = '" . $datos['prefijo'] . "' ";
        $sql .= "AND		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $this->notascredito[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        if (!$this->CopiarDatosTemporal($datos))
            return false;

        $this->action = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados', $datos);
        $informacion .= "EL DOCUMENTO HA SIDO ANULADO";

        $this->FormaInformacion($informacion);
        return true;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function CopiarDatosTemporal($datos)
    {
        $caja_id = $_SESSION['RCFactura']['caja'];

        list($dbconn) = GetDBConn();
        $dbconn->BeginTrans();

        $sql = "SELECT COALESCE(MAX(tmp_recibo_id),0)+1 FROM tmp_recibos_caja ";
        $rst = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            die(MsgOut("Error al iniciar la transaccion", "Error DB : " . $dbconn->ErrorMsg()));
            $dbconn->RollbackTrans();
            return false;
        }
        if (!$rst->EOF)
        {
            $this->serial = $rst->fields[0];
            $rst->MoveNext();
        }

        if ($this->recibo['tipo_id_tercero_endoso'])
        {
            $this->recibo['tipo_id_tercero_endoso'] = "'" . $this->recibo['tipo_id_tercero_endoso'] . "'";
            $this->recibo['tercero_id_endoso'] = "'" . $this->recibo['tercero_id_endoso'] . "'";
        }
        else
        {
            $this->recibo['tipo_id_tercero_endoso'] = "NULL";
            $this->recibo['tercero_id_endoso'] = "NULL";
        }

        $sql = "INSERT INTO tmp_recibos_caja(";
        $sql .= "			tmp_recibo_id,";
        $sql .= "			empresa_id,";
        $sql .= "			centro_utilidad,";
        $sql .= "			fecha_ingcaja,";
        $sql .= "			total_abono,";
        $sql .= "			total_efectivo, ";
        $sql .= "			total_cheques, ";
        $sql .= "			total_tarjetas,";
        $sql .= "			total_consignacion, ";
        $sql .= "			otros, ";
        $sql .= "			tipo_id_tercero,";
        $sql .= "			tercero_id,";
        $sql .= "			fecha_registro,";
        $sql .= "			usuario_id,";
        $sql .= "			rc_tipo_documento, ";
        $sql .= "			tipo_id_tercero_endoso,";
        $sql .= "			tercero_id_endoso, ";
        $sql .= "			observacion )";
        $sql .= "VALUES(";
        $sql .= "			" . $this->serial . ", ";
        $sql .= "	   '" . $this->recibo['empresa_id'] . "', ";
        $sql .= "		 '" . $this->recibo['centro_utilidad'] . "', ";
        $sql .= "		  NOW(),";
        $sql .= "			" . $this->recibo['total_abono'] . ",";
        $sql .= "			" . $this->recibo['total_efectivo'] . ", ";
        $sql .= "			" . $this->recibo['total_cheques'] . ", ";
        $sql .= "			" . $this->recibo['total_tarjetas'] . ", ";
        $sql .= "			" . $this->recibo['total_consignacion'] . ", ";
        $sql .= "			" . $this->recibo['otros'] . ", ";
        $sql .= "	   '" . $this->recibo['tipo_id_tercero'] . "', ";
        $sql .= "	   '" . $this->recibo['tercero_id'] . "', ";
        $sql .= "		 	NOW(),";
        $sql .= "			" . UserGetUID() . ", ";

        $sql .= "		 	" . $this->recibo['rc_tipo_documento'] . ", ";
        $sql .= "			" . $this->recibo['tipo_id_tercero_endoso'] . ",";
        $sql .= "			" . $this->recibo['tercero_id_endoso'] . ", ";
        $sql .= "		 '" . $this->recibo['observacion'] . "' ";
        $sql .= "	); ";

        if ($this->recibo['total_cheques'] != 0)
        {
            for ($i = 0; $i < sizeof($this->cheques); $i++)
            {
                $sql .= "INSERT INTO tmp_cheques_mov_rc(";
                $sql .= "				empresa_id , ";
                $sql .= "				centro_utilidad , ";
                $sql .= "				recibo_caja, ";
                $sql .= "				banco , ";
                $sql .= "				cheque, ";
                $sql .= "				girador, ";
                $sql .= "				fecha_cheque, ";
                $sql .= "				total, ";
                $sql .= "				fecha , ";
                $sql .= "				estado, ";
                $sql .= "				usuario_id, ";
                $sql .= "				fecha_registro, ";
                $sql .= "				cta_cte)";
                $sql .= "VALUES(";
                $sql .= "	   '" . $this->cheques[$i]['empresa_id'] . "', ";
                $sql .= "		 '" . $this->cheques[$i]['centro_utilidad'] . "', ";
                $sql .= "			" . $this->serial . ", ";
                $sql .= "	   '" . $this->cheques[$i]['banco'] . "',";
                $sql .= "	   '" . $this->cheques[$i]['cheque'] . "',";
                $sql .= "	   '" . $this->cheques[$i]['girador'] . "', ";
                $sql .= "	   '" . $this->cheques[$i]['fecha_cheque'] . "', ";
                $sql .= "	   '" . $this->cheques[$i]['total'] . "', ";
                $sql .= "	   '" . $this->cheques[$i]['fecha'] . "', ";
                $sql .= "		 '0',";
                $sql .= "			" . UserGetUID() . ", ";
                $sql .= "		 '" . $this->cheques[$i]['fecha_registro'] . "',";
                $sql .= "	   '" . $this->cheques[$i]['cta_cte'] . "' ";
                $sql .= "	); ";
            }
        }
        if ($this->recibo['total_tarjetas'] != 0)
        {
            if (sizeof($this->tarjeta_debito) > 0)
            {
                for ($i = 0; $i < sizeof($this->tarjeta_debito); $i++)
                {
                    $sql .= "INSERT INTO tmp_tarjetas_mov_debito(";
                    $sql .= "				empresa_id , ";
                    $sql .= "				centro_utilidad , ";
                    $sql .= "				recibo_caja, ";
                    $sql .= "				autorizacion ,";
                    $sql .= "				tarjeta ,";
                    $sql .= "				total ,";
                    $sql .= "				tarjeta_numero )";
                    $sql .= "VALUES(";
                    $sql .= "	   '" . $this->tarjeta_debito[$i]['empresa_id'] . "', ";
                    $sql .= "		 '" . $this->tarjeta_debito[$i]['centro_utilidad'] . "', ";
                    $sql .= "			" . $this->serial . ", ";
                    $sql .= "	   '" . $this->tarjeta_debito[$i]['autorizacion'] . "',";
                    $sql .= "	   '" . $this->tarjeta_debito[$i]['tarjeta'] . "',";
                    $sql .= "	   '" . $this->tarjeta_debito[$i]['total'] . "', ";
                    $sql .= "	   '" . $this->tarjeta_debito[$i]['tarjeta_numero'] . "' ";
                    $sql .= "		); ";
                }
            }
            elseif (sizeof($this->tarjeta_credito) > 0)
            {
                for ($i = 0; $i < sizeof($this->tarjeta_credito); $i++)
                {
                    $sql .= "INSERT INTO tmp_tarjetas_mov_credito(";
                    $sql .= "					tarjeta ,";
                    $sql .= "					empresa_id , ";
                    $sql .= "					centro_utilidad , ";
                    $sql .= "					recibo_caja, ";
                    $sql .= "					autorizacion ,";
                    $sql .= "					socio ,";
                    $sql .= "					fecha_expira ,";
                    $sql .= "					autorizado_por ,";
                    $sql .= "					total ,";
                    $sql .= "					usuario_id ,";
                    $sql .= "					fecha,";
                    $sql .= "					fecha_registro,";
                    $sql .= "					tarjeta_numero )";
                    $sql .= "VALUES(";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['tarjeta'] . "',";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['empresa_id'] . "', ";
                    $sql .= "		 '" . $this->tarjeta_credito[$i]['centro_utilidad'] . "', ";
                    $sql .= "			" . $this->serial . ", ";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['autorizacion'] . "',";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['socio'] . "',";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['fecha_expira'] . "', ";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['autorizado_por'] . "', ";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['total'] . "', ";
                    $sql .= "			" . $this->tarjeta_credito[$i]['usuario_id'] . ", ";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['fecha'] . "', ";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['fecha_registro'] . "',";
                    $sql .= "	   '" . $this->tarjeta_credito[$i]['tarjeta_numero'] . "' ";
                    $sql .= "		); ";
                }
            }
        }
        if ($this->recibo['total_consignacion'] != 0)
        {
            for ($i = 0; $i < sizeof($this->bancos); $i++)
            {
                $sql .= "INSERT INTO tmp_bancos_consignaciones(";
                $sql .= "				tmp_banco_id , ";
                $sql .= "				empresa_id , ";
                $sql .= "				centro_utilidad , ";
                $sql .= "				tmp_recibo_id, ";
                $sql .= "				numero_cuenta, ";
                $sql .= "				valor, ";
                $sql .= "				numero_transaccion, ";
                $sql .= "				fecha_transaccion)";
                $sql .= "VALUES(";
                $sql .= "			(SELECT COALESCE(MAX(tmp_banco_id),0)+1 FROM tmp_bancos_consignaciones), ";
                $sql .= "	   '" . $this->bancos[$i]['empresa_id'] . "', ";
                $sql .= "		 '" . $this->bancos[$i]['centro_utilidad'] . "', ";
                $sql .= "	   '" . $this->serial . "', ";
                $sql .= "	   '" . $this->bancos[$i]['numero_cuenta'] . "',";
                $sql .= "	   '" . $this->bancos[$i]['valor'] . "', ";
                $sql .= "	   '" . $this->bancos[$i]['numero_transaccion'] . "', ";
                $sql .= "	   '" . $this->bancos[$i]['fecha_transaccion'] . "' ";
                $sql .= "		); ";
            }
        }

        $rst = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->mensajeDeError = $sql;
            $this->frmError['MensajeError'] = "1 Error al iniciar la transaccion Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $sql = "";
        for ($i = 0; $i < sizeof($this->facturas); $i++)
        {
            $sql .= "INSERT INTO tmp_rc_detalle_tesoreria_facturas( ";
            $sql .= "			tmp_rc_id,";
            $sql .= "			empresa_id ,";
            $sql .= "			centro_utilidad ,";
            $sql .= "			tmp_recibo_id ,";

            $sql .= "			prefijo_factura ,";
            $sql .= "			factura_fiscal ,";
            $sql .= "			valor_abonado ) ";
            $sql .= "VALUES(";
            $sql .= "	   		(SELECT COALESCE(MAX(tmp_rc_id),0)+1 FROM tmp_rc_detalle_tesoreria_facturas), ";
            $sql .= "				'" . $this->facturas[$i]['empresa_id'] . "' , ";
            $sql .= "				'" . $this->facturas[$i]['centro_utilidad'] . "' ,";
            $sql .= "				 " . $this->serial . ", ";
            $sql .= "				'" . $this->facturas[$i]['prefijo_factura'] . "' ,";
            $sql .= "		 		 " . $this->facturas[$i]['factura_fiscal'] . " ,";
            $sql .= "		 		 " . $this->facturas[$i]['valor_abonado'] . "); ";
        }

        for ($i = 0; $i < sizeof($this->conceptos); $i++)
        {
            ($this->conceptos[$i]['departamento']) ? $this->conceptos[$i]['departamento'] = "'" . $this->conceptos[$i]['departamento'] . "'" : $this->conceptos[$i]['departamento'] = "NULL";
            $sql .= "INSERT INTO tmp_rc_detalle_tesoreria_conceptos( ";
            $sql .= "				tmp_rc_id,";
            $sql .= "				empresa_id, ";
            $sql .= "				centro_utilidad, ";
            $sql .= "				tmp_recibo_id, ";
            $sql .= "				concepto_id, ";
            $sql .= "				naturaleza, ";
            $sql .= "				departamento, ";
            $sql .= "				valor ) ";
            $sql .= "VALUES ( ";
            $sql .= "	   		(SELECT COALESCE(MAX(tmp_rc_id),0)+1 FROM tmp_rc_detalle_tesoreria_conceptos), ";
            $sql .= "			'" . $this->conceptos[$i]['empresa_id'] . "', ";
            $sql .= "			'" . $this->conceptos[$i]['centro_utilidad'] . "', ";
            $sql .= "			 " . $this->serial . ", ";
            $sql .= "			'" . $this->conceptos[$i]['concepto_id'] . "', ";
            $sql .= "			'" . $this->conceptos[$i]['naturaleza'] . "', ";
            $sql .= "			 " . $this->conceptos[$i]['departamento'] . ", ";
            $sql .= "			 " . $this->conceptos[$i]['valor'] . " ); ";
        }

        for ($i = 0; $i < sizeof($this->notascredito); $i++)
        {
            $sql .= "INSERT INTO tmp_rc_detalle_tesoreria_notas_credito ";
            $sql .= "				(";
            $sql .= "				tmp_recibo_id,";
            $sql .= "				empresa_id,";
            $sql .= "				prefijo_nota,";
            $sql .= " 			nota_credito_ajuste,";
            $sql .= "				valor ";
            $sql .= "				) ";
            $sql .= "VALUES (";
            $sql .= "				 " . $this->serial . ",";
            $sql .= "				'" . $this->notascredito[$i]['empresa_id'] . "',";
            $sql .= "				'" . $this->notascredito[$i]['prefijo_nota'] . "',";
            $sql .= "				 " . $this->notascredito[$i]['nota_credito_ajuste'] . ",";
            $sql .= "				 " . $this->notascredito[$i]['valor'] . " ";
            $sql .= "				);";

            $sql .= "UPDATE notas_credito_ajuste ";
            $sql .= "SET 		estado = '3' ";
            $sql .= "WHERE	nota_credito_ajuste = " . $this->notascredito[$i]['nota_credito_ajuste'] . " ";
            $sql .= "AND		prefijo = '" . $this->notascredito[$i]['prefijo_nota'] . "' ";
            $sql .= "AND 		empresa_id = '" . $this->notascredito[$i]['empresa_id'] . "'; ";
        }

        $sql .= "UPDATE rc_detalle_tesoreria_facturas ";
        $sql .= "SET 		sw_estado = '1' ";
        $sql .= "WHERE	recibo_caja = " . $datos['recibo_caja'] . " ";
        $sql .= "AND		prefijo = '" . $datos['prefijo'] . "' ";
        $sql .= "AND 		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "'; ";

        $sql .= "UPDATE recibos_caja ";
        $sql .= "SET 		estado = '1' ";
        $sql .= "WHERE	recibo_caja = " . $datos['recibo_caja'] . " ";
        $sql .= "AND		prefijo = '" . $datos['prefijo'] . "' ";
        $sql .= "AND 		empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "'; ";

        $rst = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->mensajeDeError = $sql;
            $this->frmError['MensajeError'] = "2 Error al iniciar la transaccion Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $dbconn->CommitTrans();
        return true;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function ObtenerRecibosCajaCerrados()
    {
        $sql .= "SELECT 	RC.prefijo,";
        $sql .= "					RC.recibo_caja,";
        $sql .= "					RC.total_abono,";
        $sql .= " 				RC.total_efectivo,";
        $sql .= " 				RC.total_cheques,";
        $sql .= " 				RC.total_tarjetas,";
        $sql .= "					RC.total_consignacion,";
        $sql .= "					RC.otros,";
        $sql .= "					TO_CHAR(RC.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
        $sql .= "					SU.nombre, ";
        $sql .= "					TE.nombre_tercero, ";
        $sql .= "					TE.tercero_id, ";
        $sql .= "					TE.tipo_id_tercero, ";
        $sql .= "					COALESCE(SUM(RT.valor),0) AS valor ";
        $where .= "FROM		recibos_caja RC";
        $where .= "				LEFT JOIN rc_detalle_tesoreria_conceptos RT ";
        $where .= "				ON(	RT.recibo_caja = RC.recibo_caja AND ";
        $where .= "						RT.prefijo = RC.prefijo AND ";
        $where .= "						RT.naturaleza = 'D'), ";
        $where .= "				terceros TE,";
        $where .= "				system_usuarios SU ";
        $where .= "WHERE	RC.empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";
        $where .= "AND		SU.usuario_id = RC.usuario_id ";
        $where .= "AND		RC.estado = '2' ";
        $where .= "AND		TE.tercero_id = RC.tercero_id ";
        $where .= "AND		TE.tipo_id_tercero = RC.tipo_id_tercero ";

        if ($this->RNNumero && is_numeric($this->RNNumero))
            $where .= "AND		RC.recibo_caja = " . $this->RNNumero . " ";

        if ($this->FechaInicio)
        {
            $arr = explode("/", $this->FechaInicio);
            $where .= "AND		RC.fecha_registro >= '" . $arr[2] . "-" . $arr[1] . "-" . $arr[0] . " 00:00:00' ";
        }

        if ($this->FechaFin)
        {
            $arr = explode("/", $this->FechaFin);
            $where .= "AND		RC.fecha_registro <= '" . $arr[2] . "-" . $arr[1] . "-" . $arr[0] . " 23:59:59' ";
        }

        if ($this->Usuario != 0 || $this->Usuario)
        {
            $where .= "AND		RC.usuario_id = " . $this->Usuario . " ";
        }

        $sqlC = "SELECT COUNT(*) $where";
        $this->ProcesarSqlConteo($sqlC);

        $sql .= "$where ";
        $sql .= "GROUP BY 1,2,3,4,5,6,7,9,10,11,12,13,RC.fecha_registro,RC.otros ";
        $sql .= "ORDER BY RC.fecha_registro DESC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $i = 0;
        while (!$rst->EOF)
        {
            $pago = "";
            $recibos[$i] = $rst->GetRowAssoc($ToUpper = false);

            $this->datos[$i] = array("tercero_id" => $recibos[$i]['tercero_id'], "tipo_id_tercero" => $recibos[$i]['tipo_id_tercero'],
                "tercero_nombre" => $recibos[$i]['nombre_tercero'], "prefijo" => $recibos[$i]['prefijo'],
                "recibo_caja" => $recibos[$i]['recibo_caja'], "valor_recibo" => $recibos[$i]['total_abono']);

            if ($recibos[$i]['otros'] > 0)
                $pago .= "<li>OTRO CONCEPTO ";
            if ($recibos[$i]['total_cheques'] > 0)
                $pago .= "<li>CHEQUE ";

            if ($recibos[$i]['total_efectivo'] > 0)
                $pago .= "<li>EFECTIVO ";

            if ($recibos[$i]['total_tarjetas'] > 0)
                $pago .= "<li>TARJETA ";

            if ($recibos[$i]['total_consignacion'] > 0)
                $pago .= "<li>CONSIGNACIÓN ";

            $recibos[$i]['forma_pago'] = $pago;

            $rst->MoveNext();
            $i++;
        }
        $rst->Close();
//echo $sql;
        return $recibos;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function ObtenerUsuariosRecibos()
    {
        $sql .= "SELECT SU.nombre, SU.usuario_id ";
        $sql .= "FROM		system_usuarios SU, userpermisos_recibos_caja UR ";
        $sql .= "WHERE	SU.usuario_id = UR.usuario_id ";
        $sql .= "AND		UR.empresa_id = '" . $_SESSION['RCFactura']['empresa'] . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $usuarios[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $usuarios;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function EliminarFacturasReciboCaja()
    {
        $this->menu = SessionGetVar("Documentos");
        $arreglo = array("pagina" => $_REQUEST['pagina'], "tercero_id" => $_REQUEST['tercero_id'],
            "tercero_tipo" => $_REQUEST['tercero_tipo'], "recibo_caja" => $_REQUEST['recibo_caja'],
            "prefijo" => $_REQUEST['prefijo'], "tercero_nombre" => $_REQUEST['tercero_nombre']);

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'GenerarReciboCaja', $arreglo);
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'DesvincularFacturas', $arreglo);
        $this->action3 = ModuloGetURL('app', 'RecibosCaja', 'user', 'EliminarFacturasReciboCaja', $arreglo);
        $this->FormaEliminarFacturasReciboCaja();
        return true;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function ModificarValorDocumento()
    {
        $this->menu = SessionGetVar("Documentos");
        $arreglo = array("pagina" => $_REQUEST['datos']['pagina'], "tercero_id" => $_REQUEST['datos']['tercero_id'],
            "tercero_tipo" => $_REQUEST['datos']['tercero_tipo'], "recibo_caja" => $_REQUEST['datos']['recibo_caja'],
            "prefijo" => $_REQUEST['datos']['prefijo'], "tercero_nombre" => $_REQUEST['datos']['tercero_nombre']);

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'GenerarReciboCaja', $arreglo);
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'DesvincularFacturas', $arreglo);
        $this->action3 = ModuloGetURL('app', 'RecibosCaja', 'user', 'EliminarFacturasReciboCaja', $arreglo);
        $this->FormaModificarValorDocumento();
        return true;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function ObtenerFacturasAbonadas()
    {
        $empresa = $_SESSION['RCFactura']['empresa'];


        $sql = "SELECT TO_CHAR(IFD.fecha_registro,'DD/MM/YYYY') AS fecha1, 
                '0' AS envio_id, 
                '0' AS fecha2, 
                IFD.valor_total, 
                IFD.saldo, 
                IFD.prefijo, 
                IFD.factura_fiscal, 
                '0' AS valor_glosa, 
                '0' AS valor_aceptado, 
                TM.valor_abonado AS abono, 
                TM.tmp_rc_id             FROM 	inv_facturas_despacho IFD
                INNER JOIN tmp_rc_detalle_tesoreria_facturas TM ON(TM.prefijo_factura=IFD.prefijo AND IFD.factura_fiscal=TM.factura_fiscal)
                WHERE TM.tmp_recibo_id='{$_REQUEST['recibo_caja']}'
                union all 
                SELECT TO_CHAR(IFD.fecha_registro,'DD/MM/YYYY') AS fecha1, 
                '0' AS envio_id, 
                '0' AS fecha2, 
                IFD.valor_total, 
                IFD.saldo, 
                IFD.prefijo, 
                IFD.factura_fiscal, 
                '0' AS valor_glosa, 
                '0' AS valor_aceptado, 
                TM.valor_abonado AS abono, 
                TM.tmp_rc_id             FROM 	inv_facturas_agrupadas_despacho IFD
                INNER JOIN tmp_rc_detalle_tesoreria_facturas TM ON(TM.prefijo_factura=IFD.prefijo AND IFD.factura_fiscal=TM.factura_fiscal)
                WHERE TM.tmp_recibo_id='{$_REQUEST['recibo_caja']}' ";

              //  echo "<pre>".$sql."</pre>";ed
        $sum_sql = "select count(*) from ({$sql}) as a; ";



        $sqlC = $sum_sql;
        $this->ProcesarSqlConteo($sqlC);

        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $facturasPagas[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        return $facturasPagas;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function DesvincularFacturas()
    {
        $recibos = $_REQUEST['recibo'];
        $facturas = $_REQUEST['factura'];
        if (sizeof($facturas) > 0)
        {
            $sql = "";
            $borrada = "";
            for ($i = 0; $i < sizeof($recibos); $i++)
            {
                if ($facturas[$i] != "")
                {
                    $vector = explode("*", $facturas[$i]);
                    $sql .= "DELETE FROM tmp_rc_detalle_tesoreria_facturas ";
                    $sql .= "WHERE 	prefijo_factura = '" . $vector[0] . "'";
                    $sql .= "AND		factura_fiscal = " . $vector[1] . " ";
                    $sql .= "AND		tmp_rc_id = " . $recibos[$i] . " ";
                    $sql .= "AND		tmp_recibo_id = " . $_REQUEST['recibo_caja'] . "; ";

                    $borrada .= "<li>" . $vector[0] . " " . $vector[1];
                }
            }
            /* echo '<pre>';
              print_r($_REQUEST);
              print_r($_SESSION);
             */
            if (count($_REQUEST['factura']) != count($_REQUEST['factura_hidden']))
            {
                //echo "<b>diferentes</b>";
                $facturas_req = $_REQUEST['factura'];
            }
            else
            {
                //echo "<b>iguales</b>";
                $facturas_req = $_REQUEST['factura_hidden'];
            }
            //echo "<br>";
            for ($k = 0; $k < count($_REQUEST['factura']); $k++)
            {
                $fac_delete = explode('*', $facturas_req[$k]);
                $valor_rc = $this->obtenerDetalleRCTmp($_REQUEST['recibo_caja'], $_SESSION['RCFactura']['empresa'], $_REQUEST['centro'], $fac_delete[0], $fac_delete[1]);
                for ($p = 0; $p < count($valor_rc); $p++)
                {
                    $valor_new = 0;
                    $valor_ex = $this->obtenerSaldoExcedenteRC($_SESSION['RCFactura']['empresa'], $_REQUEST['centro'], $valor_rc[$p]['recibo_caja'], $valor_rc[$p]['prefijo_rc']);
                    $valor_new = $valor_ex['valor_actual'] + $valor_rc[$p]['valor_detalle'];

                    //echo "<br><b>".$valor_ex['valor_actual']."+".$valor_rc[$p]['valor_detalle']."=".$valor_new."</b><br>";

                    $update_valor = $this->updateSaldoRCDetalles($valor_new, $_SESSION['RCFactura']['empresa'], $_REQUEST['centro'], $valor_rc[$p]['recibo_caja'], $valor_rc[$p]['prefijo_rc']);
                    $delete_fac_det = $this->deleteRegistrosFacturasDetalleTmp($_REQUEST['recibo_caja'], $valor_rc[$p]['recibo_caja'], $valor_rc[$p]['prefijo_rc'], $fac_delete[0], $fac_delete[1], $_SESSION['RCFactura']['empresa'], $_REQUEST['centro']);
                    /* echo "valor_new:".$valor_new."<br>";
                      echo "valor_ex:".$valor_ex['valor_actual']."<br>";
                      echo "valor_rc:".$valor_rc[$p]['valor_detalle']."<br>"; */
                    //echo "<b>sql update:</b>".$update_valor."<br>";
                    //echo "<b>sql delete:</b>".$delete_fac_det."<br>";
                }
                $delete_fac = $this->deleteRegistrosFacturasTmp($_REQUEST['recibo_caja'], $_SESSION['RCFactura']['empresa'], $_REQUEST['centro'], $fac_delete[0], $fac_delete[1]);
                //echo "<H3><b>DELETE FAC</b>: ".$delete_fac."</H3><br>";
            }

            /* echo 'consulta1: <pre>';
              print_r($valor_rc);
              echo '</pre>'; */
            /*
              echo 'consulta2: <pre>';
              print_r($valor_ex);
              echo '</pre>'; */

            //echo "SQL: ".$sql;
            //exit(0);
            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            $arreglo = array("pagina" => $_REQUEST['pagina'], "tercero_id" => $_REQUEST['tercero_id'],
                "tercero_tipo" => $_REQUEST['tercero_tipo'], "recibo_caja" => $_REQUEST['recibo_caja'],
                "prefijo" => $_REQUEST['prefijo'], "tercero_nombre" => $_REQUEST['tercero_nombre']);

            $this->action = ModuloGetURL('app', 'RecibosCaja', 'user', 'EliminarFacturasReciboCaja', $arreglo);

            $informacion .= "LAS FACTURAS FUERON DESVINCULADAS DEL DOCUMENTO SATISFACTORIAMENTE ";

            $this->FormaInformacion($informacion);
        }
        else
        {
            $this->EliminarFacturasReciboCaja();
        }
        return true;
    }

    function deleteRegistrosFacturasTmp($tmp_recibo_id, $empresa_id, $centro_utilidad, $prefijo_factura, $factura_fiscal)
    {
        $sql = "";
        $sql .= "DELETE FROM facturas_rc_tmp 
                             WHERE  tmp_recibo_id = '" . $tmp_recibo_id . "' 
                             AND    empresa_id = '" . $empresa_id . "' 
                             AND    centro_utilidad = '" . $centro_utilidad . "' 
                             AND    prefijo_factura = '" . $prefijo_factura . "' 
                             AND    factura_fiscal = '" . $factura_fiscal . "'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        return true;
    }

    function deleteRegistrosFacturasDetalleTmp($tmp_recibo_id, $recibo_caja, $prefijo_rc, $prefijo_factura, $factura_fiscal, $empresa_id, $centro_utilidad)
    {
        $sql = "";
        $sql .= "DELETE FROM facturas_rc_detalles_tmp
                             WHERE  tmp_recibo_id = '" . $tmp_recibo_id . "' 
                             AND    recibo_caja = '" . $recibo_caja . "' 
                             AND    prefijo_rc = '" . $prefijo_rc . "' 
                             AND    prefijo_factura = '" . $prefijo_factura . "' 
                             AND    factura_fiscal = '" . $factura_fiscal . "' 
                             AND    empresa_id = '" . $empresa_id . "' 
                             AND    centro_utilidad = '" . $centro_utilidad . "'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        return true;
    }

    function updateSaldoRCDetalles($valor, $empresa, $centro, $recibo_caja, $prefijo)
    {
        $sql = "";
        /* $sql .= "   UPDATE  rc_detalles
          SET     valor_actual = '" . $valor . "'
          WHERE   empresa_id = '" . $empresa . "'
          AND     centro_utilidad = '" . $centro . "'
          AND     recibo_caja = '" . $recibo_caja . "'
          AND     prefijo = '" . $prefijo . "'; "; */

        //--[RQ-13564]-------------------------------------------------------------------------
        /* $sql .= "   UPDATE  rc_detalles
          SET     valor_actual = '" . $valor . "'
          WHERE   empresa_id = '" . $empresa . "'
          AND     recibo_caja = '" . $recibo_caja . "'
          AND     prefijo = '" . $prefijo . "'; "; */

        $sql .= "   UPDATE  rc_detalles
                                SET     valor_actual = '" . $valor . "'
                                WHERE   recibo_caja = '" . $recibo_caja . "'
                                AND     prefijo = '" . $prefijo . "'; ";
        //-------------------------------------------------------------------------------------

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        return true;
    }

    function obtenerSaldoExcedenteRC($empresa_id, $centro, $recibo_caja, $prefijo)
    {
        $sql = "";
        /* $sql .= "   SELECT  valor_actual
          FROM    rc_detalles
          WHERE   empresa_id = '" . $empresa_id . "'
          AND     centro_utilidad='" . $centro . "'
          AND     recibo_caja='" . $recibo_caja . "'
          AND     prefijo ='" . $prefijo . "';"; */

        //--[RQ-13564]-------------------------------------------------------------------
        /* $sql .= "   SELECT  valor_actual
          FROM    rc_detalles
          WHERE   empresa_id = '" . $empresa_id . "'
          AND     recibo_caja='" . $recibo_caja . "'
          AND     prefijo ='" . $prefijo . "';"; */

        $sql .= "   SELECT  valor_actual
                                FROM    rc_detalles
                                WHERE   recibo_caja='" . $recibo_caja . "'
                                AND     prefijo ='" . $prefijo . "';";
        //-------------------------------------------------------------------------------

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function obtenerDetalleRCTmp($tmp_recibo_id, $empresa_id, $centro_utilidad, $prefijo, $factura)
    {
        $sql = "";
        $sql .= "   SELECT  FRT.valor_abonado,
                                        FRDT.recibo_caja,
                                        FRDT.prefijo_rc,
                                        FRDT.valor_detalle
                                FROM  facturas_rc_tmp FRT
                                INNER JOIN facturas_rc_detalles_tmp FRDT ON (FRT.tmp_recibo_id = FRDT.tmp_recibo_id 
                                                                             AND FRT.empresa_id=FRDT.empresa_id
                                                                             AND FRT.centro_utilidad=FRDT.centro_utilidad
                                                                             AND FRT.prefijo_factura = FRDT.prefijo_factura
                                                                             AND FRT.factura_fiscal=FRDT.factura_fiscal)
                                WHERE FRT.tmp_recibo_id = '" . $tmp_recibo_id . "'
                                AND   FRT.empresa_id = '" . $empresa_id . "'
                                AND   FRT.centro_utilidad = '" . $centro_utilidad . "'
                                AND   FRT.prefijo_factura = '" . $prefijo . "'
                                AND   FRT.factura_fiscal = '" . $factura . "';";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();

        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function ModificarInformacion()
    {
        $datos = $_REQUEST['datos'];
        if (sizeof($this->DatosRecibo) == 0)
        {
            $this->menu = SessionGetVar("Documentos");

            $fcn = new app_RecibosCaja_Funciones();
            $this->DatosRecibo = $fcn->ObtenerInformacionRecibo($datos, $this->menu['rc_tipo_documento'], $_SESSION['RCFactura']['empresa']);

            if ($this->DatosRecibo['fecha'] < $this->DatosRecibo['limite'])
                $this->DatosRecibo['fecha_registro'] = date("d/m/Y");
        }

        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'GenerarReciboCaja', $datos);
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'ModificarInformacionRecibo', array("datos" => $datos));
        $this->FormaModificarInformacion();
        return true;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function ModificarInformacionRecibo()
    {
        $datos = $_REQUEST['datos'];
        $this->DatosRecibo['observacion'] = $_REQUEST['observa'];
        $this->DatosRecibo['fecha_limite'] = $_REQUEST['fecha_limite'];
        $this->DatosRecibo['fecha_registro'] = $_REQUEST['fecha_registro'];

        $this->Parametro = "MensajeError";

        if ($this->DatosRecibo['fecha_registro'] == "")
        {
            $this->frmError['MensajeError'] = "LA FECHA DE REGISTRO NO PUEDE SER NULA";
            $this->ModificarInformacion();
            return true;
        }

        $f1 = explode("/", $this->DatosRecibo['fecha_registro']);
        $f2 = explode("/", $this->DatosRecibo['fecha_limite']);

        $fecha = $f1[2] . "/" . $f1[1] . "/" . $f1[0];
        $limite = $f2[2] . "/" . $f2[1] . "/" . $f2[0];

        if ($limite > $fecha)
        {
            $this->frmError["MensajeError"] = "LA FECHA NO SE PUEDE MODIFICAR, LA FECHA DE REGSITRO NO DEBE SER ANTERIOR A LA FECHA DEL ULTIMO RECIBO";
            $this->ModificarInformacion();
            return true;
        }

        $this->DatosRecibo['fecha_registro'] = $fecha;

        $fcn = new app_RecibosCaja_Funciones();
        $fcn->ActualizarDatos($datos, $this->DatosRecibo, $_SESSION['RCFactura']['empresa']);

        $this->action = ModuloGetURL('app', 'RecibosCaja', 'user', 'ModificarInformacion', array("datos" => $datos));
        $informacion .= "<center>LA INFORMACION DEL DOCUEMENTO HA SIDO MODIFICADA CORRECTAMENTE</center>";
        $this->FormaInformacion($informacion);

        return true;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function CruzarNotasCredito()
    {
        $datos = $_REQUEST['datos'];
        $this->NotasC = $this->ObtenerNotasCreditoCruce($_SESSION['RCFactura']['empresa'], $datos['tercero_id'], $datos['tercero_tipo']);
        $this->NotasCruzadas = $this->ObtenerNotasCruzadas($_SESSION['RCFactura']['empresa'], $datos);
        $this->action1 = ModuloGetURL('app', 'RecibosCaja', 'user', 'GenerarReciboCaja', $datos);
        $this->action2 = ModuloGetURL('app', 'RecibosCaja', 'user', 'CruzarNotasCreditoBD', array("datos" => $datos));
        $this->FormaCruzarNotasCredito();
        return true;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function ObtenerNotasCreditoCruce($empresa, $identificacion, $tipoid)
    {
        $sql .= "SELECT NA.prefijo,";
        $sql .= "				NA.nota_credito_ajuste,";
        $sql .= "				NA.observacion,";
        $sql .= "				SUM(NC.valor) AS valor ";
        $sql .= "FROM		notas_credito_ajuste NA LEFT JOIN ";
        $sql .= "				tmp_rc_detalle_tesoreria_notas_credito TN";
        $sql .= "				ON(";
        $sql .= "					NA.empresa_id = TN.empresa_id AND ";
        $sql .= "					NA.nota_credito_ajuste = TN.nota_credito_ajuste AND";
        $sql .= "					NA.prefijo = TN.prefijo_nota ";
        $sql .= "				),";
        $sql .= "				notas_credito_ajuste_detalle_conceptos NC ";
        $sql .= "WHERE	NA.empresa_id = NC.empresa_id ";
        $sql .= "AND		NA.nota_credito_ajuste = NC.nota_credito_ajuste ";
        $sql .= "AND		NA.prefijo = NC.prefijo ";
        $sql .= "AND		NA.empresa_id = '" . $empresa . "' ";
        $sql .= "AND		NA.estado = '3' ";
        $sql .= "AND		NC.naturaleza = 'D' ";
        $sql .= "AND		NA.tercero_id = '" . $identificacion . "' ";
        $sql .= "AND		NA.tipo_id_tercero = '" . $tipoid . "' ";
        $sql .= "AND		TN.tmp_rc_id IS NULL ";
        $sql .= "GROUP BY 1,2,3 ";
        $sql .= "ORDER BY 1,2 ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $notas = array();
        while (!$rst->EOF)
        {
            $notas[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $notas;
    }

    // Steven
    /*
     * TRAE LOS PREFIJOS DE LA TABLA recibos_caja
     */
    function ObtenerPrefijoRecibos($empresa_id)
    {
        $sql .= "SELECT DISTINCT RC.prefijo 
                                 FROM   recibos_caja RC, documentos D
                                 WHERE  RC.documento_id = D.documento_id 
                                 AND    D.tipo_doc_general_id = 'RT01' 
                                 AND    RC.empresa_id = '" . $empresa_id . "' 
                                 ORDER BY RC.prefijo ASC;  ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $prefijos = array();
        while (!$rst->EOF)
        {
            $prefijos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        //echo $sql;
        $rst->Close();
        return $prefijos;
    }

    /*
     * BUSCA LOS RECIBOS DE CAJA 
     */

    function TraerRecibosCajaBusqueda($prefijo, $recibo_caja, $tercero, $tercero_id)
    {
        if ($recibo_caja != '')
        {
            $sql_recibo_caja = " AND RC.recibo_caja = '" . $recibo_caja . "' ";
        }
        if ($tercero != '')
        {
            $sql_tercero = " AND T.nombre_tercero ILIKE '%" . $tercero . "%' ";
        }
        if ($tercero_id != '')
        {
            $sql_tercero_id = " AND T.tercero_id = '" . $tercero_id . "' ";
        }

        if ($tercero != '' OR $tercero_id != '')
        {
            $terceros_t = " , terceros T ";
            $sql_tercero_join = " AND RC.tercero_id = T.tercero_id ";
        }

        $sql .= "   SELECT RC.empresa_id, 
                                       RC.centro_utilidad, 
                                       RC.recibo_caja, 
                                       RC.prefijo, 
                                       TO_CHAR (RC.fecha_ingcaja, 'DD/MM/YYYY') as fecha_ingcaja, 
                                       RC.total_abono, 
                                       RC.total_efectivo, 
                                       RC.total_cheques, 
                                       RC.total_tarjetas, 
                                       RC.tipo_id_tercero, 
                                       RC.tercero_id, 
                                       RC.estado, 
                                       TO_CHAR (RC.fecha_registro, 'DD/MM/YYYY') as fecha_registro, 
                                       RC.usuario_id, 
                                       RC.caja_id, 
                                       RC.cierre_caja_id, 
                                       RC.total_bonos, 
                                       RC.sw_facturado, 
                                       RC.documento_id, 
                                       RC.cuenta_tipo_id, 
                                       RC.total_consignacion, 
                                       RC.observacion, 
                                       RC.otros, 
                                       RC.rc_tipo_documento, 
                                       RC.sw_recibo_tesoreria, 
                                       RC.tipo_id_tercero_endoso, 
                                       RC.tercero_id_endoso 
                                FROM   recibos_caja RC "
                . $terceros_t . "
                                WHERE  RC.prefijo = '" . $prefijo . "' 
                                        AND RC.prefijo||RC.recibo_caja NOT IN(
                                           SELECT fd.prefijo_rc || fd.recibo_caja
                                            FROM facturas_rc_detalles fd
                                            WHERE fd.valor_detalle > 0 AND fd.rc_prefijo_tras || fd.rc_id_tras <>(
                                            	SELECT prefijo || recibo_caja FROM documentos_anulacion_tesoreria WHERE prefijo = fd.rc_prefijo_tras  and recibo_caja = fd.rc_id_tras
                                            )
                                )"
                . $sql_tercero_id . " "
                . $sql_tercero_join . " "
                . $sql_recibo_caja . " "
                . $sql_tercero . " 
                                ORDER BY prefijo,recibo_caja ASC; ";
//       print_r($sql); //-21- CAMBIO DEJA VISUALIZAR EL P2 SIEMPRE Y CUANDO NO TENGA PAGOS
    //    echo "<pre>".$sql."</pre>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
//echo "<pre>".$sql."</pre>"; 
        $recibos = array();
        while (!$rst->EOF)
        {
            $recibos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $recibos;
    }

    function TraerDescripcionTerceros($tercero_id, $tipo_id_tercero)
    {
        $sql .= "   SELECT nombre_tercero 
                                FROM   terceros 
                                WHERE  tercero_id = '" . $tercero_id . "'
                                AND    tipo_id_tercero = '" . $tipo_id_tercero . "'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        //$terceros = array();
        while (!$rst->EOF)
        {
            $terceros = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $terceros;
    }

    function TraerNombreUsuario($usuario_id)
    {

        $sql .= "   SELECT nombre 
                                FROM   system_usuarios  
                                WHERE  usuario_id = " . $usuario_id . "; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        //$terceros = array();
        while (!$rst->EOF)
        {
            $usuario = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $usuario;
    }

    function TraeSaldoRCControlAnticipos($empresa_id, $tipo_id_tercero, $tercero_id)
    {

        $sql .= "   SELECT saldo 
                                FROM   rc_control_anticipos  
                                WHERE  empresa_id = '" . $empresa_id . "' 
                                AND    tipo_id_tercero = '" . $tipo_id_tercero . "' 
                                AND    tercero_id = '$tercero_id'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        //$terceros = array();
        while (!$rst->EOF)
        {
            $saldo = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $saldo;
    }

    function TraerRcIdRDTC($empresa_id, $recibo_caja, $prefijo)
    {

        $sql .= "   SELECT rc_id 
                                FROM   rc_detalle_tesoreria_conceptos  
                                WHERE  empresa_id = '" . $empresa_id . "' 
                                AND    recibo_caja = " . $recibo_caja . " 
                                AND    prefijo = '$prefijo'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        //$terceros = array();
        while (!$rst->EOF)
        {
            $rc_id = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $rc_id;
    }

    function ActualizarSaldoyEstados($saldo_rca, $empresa_id, $tipo_id_tercero, $tercero_id, $recibo_caja, $prefijo, $valor, $usuario_id, $observacion)
    {
        $sql_prefijo = " ";
        $sql_prefijo = "SELECT  prefijo, documento_id
                                    FROM    documentos
                                    WHERE   empresa_id = '" . $empresa_id . "' 
                                    AND     tipo_doc_general_id = 'AA01'; ";
        

        if (!$rst = $this->ConexionBaseDatos($sql_prefijo))
            return false;

        while (!$rst->EOF)
        {
            $prefijo_anulacion = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();

        $sql_consulta = " ";
        $sql_consulta .= "SELECT documentos_anulacion_tesoreria_id,
                                             prefijo_id
                                      FROM   documentos_anulacion_tesoreria
                                      WHERE  empresa_id = '" . $empresa_id . "' 
                                      AND    prefijo = '" . $prefijo . "' 
                                      AND    recibo_caja = " . $recibo_caja . "; ";
        
        //echo $sql_consulta;
        if (!$rst = $this->ConexionBaseDatos($sql_consulta))
            return false;

        while (!$rst->EOF)
        {
            $anulacion_consulta = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();

       
        if ($anulacion_consulta[prefijo_id] != '')
        {
            //$mensaje = "Error";
            return 0;
        }
        else
        {
            //Estados: 1-Anulado, 2-Activo
            $sql = " ";

            $sql .= "UPDATE  rc_control_anticipos 
                                SET     saldo = " . $saldo_rca . " 
                                WHERE   empresa_id = '" . $empresa_id . "' 
                                AND     tipo_id_tercero = '" . $tipo_id_tercero . "' 
                                AND     tercero_id = '" . $tercero_id . "'; ";
            //echo $sql."<br>";
            $sql .= "UPDATE  recibos_caja 
                                SET     estado = '1' 
                                WHERE   empresa_id = '" . $empresa_id . "' 
                                AND     recibo_caja = " . $recibo_caja . " 
                                AND     prefijo = '" . $prefijo . "'; ";
            //echo $sql."<br>";
            $sql .= "UPDATE  bancos_consignaciones 
                                SET     estado = '1' 
                                WHERE   empresa_id = '" . $empresa_id . "' 
                                AND     recibo_caja = " . $recibo_caja . " 
                                AND     prefijo = '" . $prefijo . "'; ";
            //echo $sql."<br>";
            $sql .= "UPDATE  rc_detalle_tesoreria_conceptos 
                                SET     estado = '1' 
                                WHERE   empresa_id = '" . $empresa_id . "' 
                                AND     recibo_caja = " . $recibo_caja . " 
                                AND     prefijo = '" . $prefijo . "'; ";
            //echo $sql."<br>";
            $sql .= "UPDATE documentos 
                                SET    numeracion = numeracion+1 
                                WHERE  documento_id = " . $prefijo_anulacion[documento_id] . " 
                                AND    empresa_id = '" . $empresa_id . "'; ";
            $sql .= "UPDATE rc_detalles 
                                SET    valor_actual = 0 
                                WHERE  prefijo = '" . $prefijo . "' 
                                AND    recibo_caja = '" . $recibo_caja . "'; ";
            //-21- ESTE ULTIMO QUERY ACTUALIZA EL VALOR_ACTUAL DE LA TABLA RC_DETALLES, SOLICITADO 
            //POR EL INGENIERO JHON JAIRO GONZALEZ.
            
           // echo "</br>".$sql."</br>";

            $consecutivoDEA = $this->TraerConsecutivoPrefijo($empresa_id, $prefijo_anulacion[prefijo]);
            if ($consecutivoDEA['consecutivo'] == NULL)
            {
                $numeracion = 1;
            }
            else
            {
                $numeracion = $consecutivoDEA['consecutivo'] + 1;
            }

            $sql .= "INSERT INTO documentos_anulacion_tesoreria(empresa_id,
                                                prefijo_id,
                                                consecutivo,
                                                prefijo,
                                                recibo_caja,
                                                valor,
                                                usuario_id,
                                                fecha_registro,
                                                observacion
                                                )
                                VALUES('" . $empresa_id . "',
                                '" . $prefijo_anulacion[prefijo] . "',
                                " . $numeracion . ",
                                '" . $prefijo . "',
                                " . $recibo_caja . ",
                                " . $valor . ",
                                " . $usuario_id . ",
                                now(),
                                '" . $observacion . "'); ";
           //echo "<PRE>:$sql</pre>";
            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            //$this->parametro = "Informacion";
            //$this->frmError['Informacion'] = "LA NOTA CREDITO ".$datos_nota[2]." ".$datos_nota[3].", FUE ADICIONADA AL DETALLE DEL RECIBO DE CAJA";
            //echo $sql."<br>";
            //echo $sql_prefijo;
            return true;
        }
    }

    function TraerObservacionDAT($empresa_id, $recibo_caja, $prefijo)
    {

        $sql .= "   SELECT observacion 
                                FROM   documentos_anulacion_tesoreria  
                                WHERE  empresa_id = '" . $empresa_id . "' 
                                AND    recibo_caja = " . $recibo_caja . " 
                                AND    prefijo = '$prefijo'; ";
//echo $sql;
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        //$terceros = array();
        while (!$rst->EOF)
        {
            $observacion = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $observacion;
    }

    function TraerDocumentoAnulacionRC($empresa_id, $recibo_caja, $prefijo)
    {

        $sql .= "   SELECT documentos_anulacion_tesoreria_id,
                                       prefijo_id  
                                FROM   documentos_anulacion_tesoreria  
                                WHERE  empresa_id = '" . $empresa_id . "' 
                                AND    recibo_caja = " . $recibo_caja . " 
                                AND    prefijo = '$prefijo'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        //$datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function TraerDocumentosAnuladosDAT($prefijo, $recibo_caja, $id_anulacion)
    {
        if ($recibo_caja)
        {
            $sql_rc = " AND DAT.recibo_caja = " . $recibo_caja . " ";
        }
        else
        {
            $sql_rc = "";
        }

        if ($id_anulacion)
        {
            $sql_anulacion = " AND DAT.documentos_anulacion_tesoreria_id = " . $id_anulacion . " ";
        }
        else
        {
            $sql_anulacion = "";
        }

        $sql .= "   SELECT  DAT.documentos_anulacion_tesoreria_id, 
                                        DAT.prefijo_id, 
                                        DAT.empresa_id, 
                                        DAT.prefijo, 
                                        DAT.recibo_caja, 
                                        DAT.valor, 
                                        DAT.usuario_id, 
                                        TO_CHAR(DAT.fecha_registro, 'DD-MM-YYYY') as fecha_registro, 
                                        DAT.observacion, 
                                        T.nombre_tercero, 
                                        T.tipo_id_tercero, 
                                        T.tercero_id 
                                FROM    documentos_anulacion_tesoreria DAT, 
                                        recibos_caja RC, 
                                        terceros T 
                                WHERE   DAT.prefijo = '" . $prefijo . "' "
                . $sql_rc . " "
                . $sql_anulacion . " 
                                AND     DAT.prefijo = RC.prefijo 
                                AND     DAT.recibo_caja = RC.recibo_caja 
                                AND     RC.tipo_id_tercero = T.tipo_id_tercero 
                                AND     RC.tercero_id = T.tercero_id 
                                ORDER BY DAT.documentos_anulacion_tesoreria_id ASC; ";
//echo $sql;
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function TraerPermisoAnulacionRC($empresa_id, $caja_id, $usuario_id)
    {

        $sql .= "   SELECT sw_permiso  
                                FROM   userpermisos_recibos_caja  
                                WHERE  empresa_id = '" . $empresa_id . "' 
                                AND    caja_id = " . $caja_id . " 
                                AND    usuario_id = " . $usuario_id . "; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        //$datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function ObtenerPrefijoRecibosContraFactura($empresa_id)
    {
        $sql .= "SELECT DISTINCT prefijo 
                                 FROM   rc_detalle_tesoreria_facturas 
                                 WHERE  empresa_id = '" . $empresa_id . "' 
                                 ORDER BY prefijo ASC;  ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $prefijos = array();
        while (!$rst->EOF)
        {
            $prefijos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        //echo $sql;
        $rst->Close();
        return $prefijos;
    }

    function ObtenerTotalesFacturasRC($recibo_caja, $prefijo)
    {
        $sql = "";
        if ($recibo_caja != '')
        {
            $sql .= "   SELECT  DISTINCT recibo_caja, 
                                                sw_estado,
                                                count(recibo_caja) as total_facturas,
                                                sum(valor_abonado) as total_suma
                                        FROM    rc_detalle_tesoreria_facturas 
                                        WHERE   prefijo = '$prefijo' 
                                        AND     recibo_caja = " . $recibo_caja . " 
                                        GROUP BY 1,2; ";
        }
        else
        {
            $sql .= "   SELECT  DISTINCT recibo_caja, 
                                                sw_estado,
                                                count(recibo_caja) as total_facturas,
                                                sum(valor_abonado) as total_suma
                                        FROM    rc_detalle_tesoreria_facturas 
                                        WHERE   prefijo = '$prefijo' 
                                        GROUP BY 1,2; ";
        }

        //echo "OTFRC:$sql<br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $prefijos = array();
        while (!$rst->EOF)
        {
            $prefijos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        //echo $sql;
        $rst->Close();
        return $prefijos;
    }

    function ObtenerFacturasRC($recibo_caja, $prefijo)
    {
        $sql = "";
        if ($recibo_caja != '')
        {
            $criterio_rc = " AND RDTF.recibo_caja = $recibo_caja ";
        }
        else
        {
            $criterio_rc = "";
        }

        /*$sql .= "   SELECT  RDTF.recibo_caja,
                                        RDTF.prefijo,
                                        RDTF.prefijo_factura,
                                        RDTF.factura_fiscal,
                                        RDTF.valor_efectivo,
                                        RDTF.valor_abonado,
                                        RDTF.sw_estado,
                                        TO_CHAR (FF.fecha_registro, 'DD-MM-YYYY') as fecha_registro,

                                        FF.tipo_id_tercero,
                                        FF.tercero_id,
                                        'ff' as tipo
                                FROM    rc_detalle_tesoreria_facturas RDTF
                                        INNER JOIN fac_facturas FF ON (RDTF.prefijo_factura = FF.prefijo) AND (RDTF.factura_fiscal = FF.factura_fiscal)
                                        -- INNER JOIN system_usuarios SU ON (FF.usuario_id = SU.usuario_id)
                                WHERE   RDTF.prefijo = '$prefijo'
                                        $criterio_rc

                    UNION ALL
                    SELECT  RDTF.recibo_caja,
                                        RDTF.prefijo,
                                        RDTF.prefijo_factura,
                                        RDTF.factura_fiscal,
                                        RDTF.valor_efectivo,
                                        RDTF.valor_abonado,
                                        RDTF.sw_estado,
                                        TO_CHAR (FF.fecha_registro, 'DD-MM-YYYY') as fecha_registro,

                                        FF.tipo_id_tercero,
                                        FF.tercero_id,
                                        'ifd' as tipo
                                FROM    rc_detalle_tesoreria_facturas RDTF
                                        INNER JOIN inv_facturas_despacho FF ON (RDTF.prefijo_factura = FF.prefijo) AND (RDTF.factura_fiscal = FF.factura_fiscal)
                                        -- INNER JOIN system_usuarios SU ON (FF.usuario_id = SU.usuario_id)
                                WHERE   RDTF.prefijo = '$prefijo'
                                        $criterio_rc
                    UNION ALL
                    SELECT  RDTF.recibo_caja,
                                        RDTF.prefijo,
                                        RDTF.prefijo_factura,
                                        RDTF.factura_fiscal,
                                        RDTF.valor_efectivo,
                                        RDTF.valor_abonado,
                                        RDTF.sw_estado,
                                        TO_CHAR (FF.fecha_registro, 'DD-MM-YYYY') as fecha_registro,

                                        FF.tipo_id_tercero,
                                        FF.tercero_id,
                                        'fe' as tipo
                                FROM    rc_detalle_tesoreria_facturas RDTF
                                        INNER JOIN facturas_externas FF ON (RDTF.prefijo_factura = FF.prefijo) AND (RDTF.factura_fiscal = FF.factura_fiscal)
                                WHERE   RDTF.prefijo = '$prefijo'
                                        $criterio_rc;";*/
        
        
        $sql .= "   SELECT  RDTF.recibo_caja,
                                        RDTF.prefijo,
                                        RDTF.prefijo_factura,
                                        RDTF.factura_fiscal,
                                        RDTF.valor_efectivo,
                                        RDTF.valor_abonado,
                                        RDTF.sw_estado,
                                        TO_CHAR (FF.fecha_registro, 'DD-MM-YYYY') as fecha_registro,
                                        /*FF.usuario_id,
                                        SU.nombre,*/
                                        FF.tipo_id_tercero,
                                        FF.tercero_id,
                                        'ifda' as tipo
                                FROM    rc_detalle_tesoreria_facturas RDTF
                                        INNER JOIN inv_facturas_agrupadas_despacho FF ON (RDTF.prefijo_factura = FF.prefijo) AND (RDTF.factura_fiscal = FF.factura_fiscal)
                                        -- INNER JOIN system_usuarios SU ON (FF.usuario_id = SU.usuario_id)
                                WHERE   RDTF.prefijo = '$prefijo'
                                        $criterio_rc

                    UNION ALL
                    SELECT  RDTF.recibo_caja,
                                        RDTF.prefijo,
                                        RDTF.prefijo_factura,
                                        RDTF.factura_fiscal,
                                        RDTF.valor_efectivo,
                                        RDTF.valor_abonado,
                                        RDTF.sw_estado,
                                        TO_CHAR (FF.fecha_registro, 'DD-MM-YYYY') as fecha_registro,
                                        /*FF.usuario_id,
                                        SU.nombre,*/
                                        FF.tipo_id_tercero,
                                        FF.tercero_id,
                                        'ifd' as tipo
                                FROM    rc_detalle_tesoreria_facturas RDTF
                                        INNER JOIN inv_facturas_despacho FF ON (RDTF.prefijo_factura = FF.prefijo) AND (RDTF.factura_fiscal = FF.factura_fiscal)
                                        -- INNER JOIN system_usuarios SU ON (FF.usuario_id = SU.usuario_id)
                                WHERE   RDTF.prefijo = '$prefijo'
                                        $criterio_rc
                
                UNION ALL
                    SELECT  RDTF.recibo_caja,
                                        RDTF.prefijo,
                                        RDTF.prefijo_factura,
                                        RDTF.factura_fiscal,
                                        RDTF.valor_efectivo,
                                        RDTF.valor_abonado,
                                        RDTF.sw_estado,
                                        TO_CHAR (FF.fecha_registro, 'DD-MM-YYYY') as fecha_registro,
                                        /*FF.usuario_id,
                                        SU.nombre,*/
                                        FF.tipo_id_tercero,
                                        FF.tercero_id,
                                        'ff' as tipo
                                FROM    rc_detalle_tesoreria_facturas RDTF
                                        INNER JOIN fac_facturas FF ON (RDTF.prefijo_factura = FF.prefijo) AND (RDTF.factura_fiscal = FF.factura_fiscal)
                                        -- INNER JOIN system_usuarios SU ON (FF.usuario_id = SU.usuario_id)
                                WHERE   RDTF.prefijo = '$prefijo'
                                        $criterio_rc; " ;
        
      //  echo "<pre>".$sql."</pre>";
        /* } else {

          $sql .= "   SELECT  RDTF.recibo_caja,
          RDTF.prefijo,
          RDTF.prefijo_factura,
          RDTF.factura_fiscal,
          RDTF.valor_efectivo,
          RDTF.valor_abonado,
          RDTF.sw_estado,
          TO_CHAR (FF.fecha_registro, 'DD-MM-YYYY') as fecha_registro,
          FF.usuario_id,
          SU.nombre
          FROM    rc_detalle_tesoreria_facturas RDTF INNER JOIN fac_facturas FF ON (RDTF.prefijo_factura = FF.prefijo) AND (RDTF.factura_fiscal = FF.factura_fiscal)
          INNER JOIN system_usuarios SU ON (FF.usuario_id = SU.usuario_id)
          WHERE   RDTF.prefijo = '$prefijo'
          UNION ALL
          SELECT  RDTF.recibo_caja,
          RDTF.prefijo,
          RDTF.prefijo_factura,
          RDTF.factura_fiscal,
          RDTF.valor_efectivo,
          RDTF.valor_abonado,
          RDTF.sw_estado,
          TO_CHAR (FF.fecha_registro, 'DD-MM-YYYY') as fecha_registro,
          FF.usuario_id,
          SU.nombre
          FROM    rc_detalle_tesoreria_facturas RDTF INNER JOIN inv_facturas_despacho FF ON (RDTF.prefijo_factura = FF.prefijo) AND (RDTF.factura_fiscal = FF.factura_fiscal)
          INNER JOIN system_usuarios SU ON (FF.usuario_id = SU.usuario_id)
          WHERE   RDTF.prefijo = '$prefijo'; ";


          } */
        //*echo "+++$sql<BR>";
        //echo "<pre>$sql</pre>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $prefijos = array();
        while (!$rst->EOF)
        {
            $prefijos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        //echo $sql;
        $rst->Close();

        if (empty($prefijos))
        {
            
            echo "No se encontaron prefijos";
            exit();
            $sql = "";
            if ($recibo_caja != '')
            {
                $sql .= "   SELECT  RDTF.recibo_caja,
                                                RDTF.prefijo,
                                                RDTF.prefijo_factura, 
                                                RDTF.factura_fiscal, 
                                                RDTF.valor_abonado, 
                                                RDTF.sw_estado, 
                                                TO_CHAR (FF.fecha_registro, 'DD-MM-YYYY') as fecha_registro 
                                        FROM    rc_detalle_tesoreria_facturas RDTF 
                                                INNER JOIN facturas_externas FF ON (RDTF.prefijo_factura = FF.prefijo) AND (RDTF.factura_fiscal = FF.factura_fiscal)
                                        WHERE   RDTF.prefijo = '$prefijo' 
                                        AND     RDTF.recibo_caja = " . $recibo_caja . " 
                            UNION ALL
                            SELECT  RDTF.recibo_caja,
                                            RDTF.prefijo,
                                            RDTF.prefijo_factura, 
                                            RDTF.factura_fiscal, 
                                            RDTF.valor_abonado, 
                                            RDTF.sw_estado,
                                            TO_CHAR (FF.fecha_registro, 'DD-MM-YYYY') as fecha_registro,
                                            FF.usuario_id,
                                            SU.nombre
                                    FROM    rc_detalle_tesoreria_facturas RDTF INNER JOIN inv_facturas_despacho FF ON (RDTF.prefijo_factura = FF.prefijo) AND (RDTF.factura_fiscal = FF.factura_fiscal)
                                            INNER JOIN system_usuarios SU ON (FF.usuario_id = SU.usuario_id)
                                    WHERE   RDTF.prefijo = '$prefijo' 
                                    AND     RDTF.recibo_caja = " . $recibo_caja . "; ";
            }
            else
            {
                $sql .= "   SELECT  RDTF.recibo_caja,
                                                RDTF.prefijo,
                                                RDTF.prefijo_factura, 
                                                RDTF.factura_fiscal, 
                                                RDTF.valor_abonado, 
                                                RDTF.sw_estado,
                                                TO_CHAR (FF.fecha_registro, 'DD-MM-YYYY') as fecha_registro 
                                        FROM    rc_detalle_tesoreria_facturas RDTF 
                                                INNER JOIN facturas_externas FF ON (RDTF.prefijo_factura = FF.prefijo) AND (RDTF.factura_fiscal = FF.factura_fiscal)
                                        WHERE   RDTF.prefijo = '$prefijo'
                            UNION ALL
                            SELECT  RDTF.recibo_caja,
                                            RDTF.prefijo,
                                            RDTF.prefijo_factura, 
                                            RDTF.factura_fiscal, 
                                            RDTF.valor_abonado, 
                                            RDTF.sw_estado,
                                            TO_CHAR (FF.fecha_registro, 'DD-MM-YYYY') as fecha_registro,
                                            FF.usuario_id,
                                            SU.nombre
                                    FROM    rc_detalle_tesoreria_facturas RDTF INNER JOIN inv_facturas_despacho FF ON (RDTF.prefijo_factura = FF.prefijo) AND (RDTF.factura_fiscal = FF.factura_fiscal)
                                            INNER JOIN system_usuarios SU ON (FF.usuario_id = SU.usuario_id)
                                    WHERE   RDTF.prefijo = '$prefijo'; ";
            }
            
            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            $prefijos = array();
            while (!$rst->EOF)
            {
                $prefijos[] = $rst->GetRowAssoc($ToUpper = false);
                ;
                $rst->MoveNext();
            }
            //echo $sql;
            $rst->Close();
        }
        return $prefijos;
    }

    function ObtenerSaldoFacturasFF($empresa_id, $prefijo, $factura_fiscal, $tipofactura)
    {

        if ($tipofactura == "ff")
        {
            $sql .= "SELECT saldo
                                     FROM   fac_facturas
                                     WHERE  empresa_id = '" . $empresa_id . "'
                                     AND    prefijo = '" . $prefijo . "'
                                     AND    factura_fiscal = " . $factura_fiscal . "
                                     ORDER BY prefijo ASC;  ";
        }
        elseif ($tipofactura == "ifd")
        {
            $sql .= "SELECT saldo
                                     FROM   inv_facturas_despacho
                                     WHERE  empresa_id = '" . $empresa_id . "'
                                     AND    prefijo = '" . $prefijo . "'
                                     AND    factura_fiscal = " . $factura_fiscal . "
                                     ORDER BY prefijo ASC;  ";
        }
        elseif ($tipofactura == "fe")
        {
            $sql .= "SELECT saldo
                                     FROM   facturas_externas
                                     WHERE  empresa_id = '" . $empresa_id . "'
                                     AND    prefijo = '" . $prefijo . "'
                                     AND    factura_fiscal = " . $factura_fiscal . "
                                     ORDER BY prefijo ASC;  ";
        }  elseif ($tipofactura == "ifda")
        {
            $sql .= "SELECT saldo
                                     FROM   inv_facturas_agrupadas_despacho
                                     WHERE  empresa_id = '" . $empresa_id . "'
                                     AND    prefijo = '" . $prefijo . "'
                                     AND    factura_fiscal = " . $factura_fiscal . "
                                     ORDER BY prefijo ASC;  ";
        }

        //echo "ObtenerSaldoFacturasFF-SQL:$sql<br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $prefijos = array();
        while (!$rst->EOF)
        {
            $prefijos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        //echo $sql;
        $rst->Close();
        return $prefijos;
    }

    function ActualizarFacturasEstadosContraFactura($empresa_id, $prefijo, $recibo_caja, $prefijo_factura, $factura_fiscal, $tipofactura, $saldo_factura, $valor_efectivo, $tipo_id_tercero, $tercero_id)
    {
        $sql_prefijo = " ";
        $sql_prefijo = "SELECT  prefijo, documento_id
                                    FROM    documentos
                                    WHERE   empresa_id = '" . $empresa_id . "' 
                                    AND     tipo_doc_general_id = 'AA01'; ";
        //echo "ActualizarFacturasEstadosContraFactura<br>sql_prefijo:$sql_prefijo<br>";
        if (!$rst = $this->ConexionBaseDatos($sql_prefijo))
            return false;

        while (!$rst->EOF)
        {
            $prefijo_anulacion = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        $sql_consulta = " ";
        $sql_consulta .= "SELECT documentos_anulacion_tesoreria_id,
                                             prefijo_id
                                      FROM   documentos_anulacion_tesoreria
                                      WHERE  empresa_id = '" . $empresa_id . "' 
                                      AND    prefijo = '" . $prefijo . "' 
                                      AND    recibo_caja = " . $recibo_caja . "; ";
        //echo "sql_consulta:$sql_consulta<br>";
        if (!$rst = $this->ConexionBaseDatos($sql_consulta))
            return false;
        //echo $sql_consulta."<br>";
        while (!$rst->EOF)
        {
            $anulacion_consulta = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        if ($anulacion_consulta[prefijo_id] != '' OR $prefijo_anulacion[prefijo] == '')
        {
            //$mensaje = "Error";
            return 0;
        }
        else
        {
            //EJECUTA UPDATES E INSERTS
            $sql = " ";
            $estado = "";

            if ($prefijo_factura != 'ME')
            {
                //--[RQ-13564]----------------------------------------------------------------------

                if ($saldo_factura > 0)
                {
                    $estado = ", estado = '0' ";
                }
                else
                {
                    $estado = ", estado = '1' ";
                }

                if ($tipofactura == "ff")  
                {
                    $sql = "ALTER TABLE fac_facturas DISABLE TRIGGER actualizar_saldo_factura;";
                    $sql .= "   UPDATE  fac_facturas
                                    SET     saldo = " . $saldo_factura . " $estado
                                    WHERE   prefijo = '" . $prefijo_factura . "'
                                    AND     factura_fiscal = " . $factura_fiscal . "; ";
                    $sql .= "ALTER TABLE fac_facturas ENABLE TRIGGER actualizar_saldo_factura;";
                }
                elseif ($tipofactura == "fe")
                {
                    $sql = "ALTER TABLE facturas_externas DISABLE TRIGGER trigger_actualizar_saldo_factura2;";
                    $sql .= "   UPDATE  facturas_externas
                                SET     saldo = " . $saldo_factura . " $estado
                                WHERE   prefijo = '" . $prefijo_factura . "'
                                AND     factura_fiscal = " . $factura_fiscal . "; ";
                    $sql .= "ALTER TABLE facturas_externas ENABLE TRIGGER trigger_actualizar_saldo_factura2;";
                }
                //----------------------------------------------------------------------------------
            }
            else
            {
                
                if ($tipofactura == "ifd")  
                {
                    $sql .= "   UPDATE  inv_facturas_despacho 
                            SET     saldo = " . $saldo_factura . "
                            WHERE   prefijo = '" . $prefijo_factura . "' 
                            AND     factura_fiscal = " . $factura_fiscal . "; ";    //-- OK
                } else {
                     $sql .= "   UPDATE  inv_facturas_agrupadas_despacho 
                            SET     saldo = " . $saldo_factura . "
                            WHERE   prefijo = '" . $prefijo_factura . "' 
                            AND     factura_fiscal = " . $factura_fiscal . "; ";    //-- OK
                }
                
                
            }

            //--[RQ-13564]----------------------------------------------------------------------
            $sql .= "ALTER TABLE recibos_caja DISABLE TRIGGER disminuir_saldo_anticipos;";
            $sql .= "ALTER TABLE rc_detalle_tesoreria_facturas DISABLE TRIGGER actualizar_saldo_factura_rc;";
            //----------------------------------------------------------------------------------

            $sql .= "   UPDATE  recibos_caja 
                                    SET     estado = '1'
                                    WHERE   empresa_id = '" . $empresa_id . "'
                                    AND     prefijo = '" . $prefijo . "' 
                                    AND     recibo_caja = " . $recibo_caja . "; ";  //-- OK

            $sql .= "   UPDATE  rc_detalle_tesoreria_facturas 
                                    SET     sw_estado = '1'
                                    WHERE   empresa_id = '" . $empresa_id . "'
                                    AND     prefijo_factura = '" . $prefijo_factura . "' 
                                    AND     factura_fiscal = " . $factura_fiscal . "
                                    AND     prefijo = '" . $prefijo . "' 
                                    AND     recibo_caja = " . $recibo_caja . ";";   //--[RQ-13564]-- Modificado: Add prefijo y recibo_caja -------------
            //--[RQ-13564]----------------------------------------------------------------------
            $sql .= "UPDATE rc_detalle_tesoreria_conceptos
                                    SET estado = '1'
                                    WHERE  empresa_id = '" . $empresa_id . "'
                                    AND    prefijo = '" . $prefijo . "'
                                    AND    recibo_caja = " . $recibo_caja . "; ";

            if (trim($tipo_id_tercero) != "" && trim($tercero_id) != "")
            {

                $sql .= "UPDATE  rc_control_anticipos
                                    SET     saldo = saldo + " . $valor_efectivo . "
                                    WHERE   empresa_id = '" . $empresa_id . "'
                                    AND     tipo_id_tercero = '" . $tipo_id_tercero . "'
                                    AND     tercero_id = '" . $tercero_id . "'; ";
            }

            $sql .= "ALTER TABLE recibos_caja ENABLE TRIGGER disminuir_saldo_anticipos;";
            $sql .= "ALTER TABLE rc_detalle_tesoreria_facturas ENABLE TRIGGER actualizar_saldo_factura_rc;";

            //---------------------------------------------------------------------------------
         // echo "<pre>{$sql}</pre>";

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            return true;
        }
    }

    function CrearDocumentoDATContraFactura($empresa_id, $prefijo, $recibo_caja, $valor_saldo_abonado, $observacion)
    {
        $sql_prefijo = " ";
        $sql_prefijo = "SELECT  prefijo, documento_id
                                    FROM    documentos
                                    WHERE   empresa_id = '" . $empresa_id . "' 
                                    AND     tipo_doc_general_id = 'AA01'; ";
        //echo $sql_prefijo."<br>";
        if (!$rst = $this->ConexionBaseDatos($sql_prefijo))
            return false;

        while (!$rst->EOF)
        {
            $prefijo_anulacion = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        $sql_consulta = " ";
        $sql_consulta .= "SELECT documentos_anulacion_tesoreria_id,
                                             prefijo_id
                                      FROM   documentos_anulacion_tesoreria
                                      WHERE  empresa_id = '" . $empresa_id . "' 
                                      AND    prefijo = '" . $prefijo . "' 
                                      AND    recibo_caja = " . $recibo_caja . "; ";
        if (!$rst = $this->ConexionBaseDatos($sql_consulta))
            return false;
        //echo $sql_consulta."<br>";
        while (!$rst->EOF)
        {
            $anulacion_consulta = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();

        //echo $sql_consulta;
        if ($anulacion_consulta[prefijo_id] != '' OR $prefijo_anulacion[prefijo] == '')
        {
            //$mensaje = "Error";
            return 0;
        }
        else
        {
            $consecutivoDEA = $this->TraerConsecutivoPrefijo($empresa_id, $prefijo_anulacion[prefijo]);
            if ($consecutivoDEA['consecutivo'] == NULL)
            {
                $numeracion = 1;
            }
            else
            {
                $numeracion = $consecutivoDEA['consecutivo'] + 1;
            }
            $sql = " ";
            $sql .= "INSERT INTO documentos_anulacion_tesoreria(
                                            prefijo_id,
                                            consecutivo,
                                            empresa_id,
                                            prefijo,
                                            recibo_caja,
                                            valor,
                                            usuario_id,
                                            fecha_registro,
                                            observacion)
                                VALUES ('" . $prefijo_anulacion[prefijo] . "',
                                " . $numeracion . ",
                                '" . $empresa_id . "',
                                '" . $prefijo . "',
                                " . $recibo_caja . ",
                                " . $valor_saldo_abonado . ",
                                " . $_SESSION[SYSTEM_USUARIO_ID] . ",
                                now(),
                                '" . $observacion . "'); ";

            //echo "<br>sql insert: ".$sql."<br>";

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            $rst->Close();

            return true;
        }
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function ObtenerNotasCruzadas($empresa, $datos)
    {
        $sql .= "SELECT TN.prefijo_nota,";
        $sql .= "				TN.nota_credito_ajuste,";
        $sql .= "				NA.observacion,";
        $sql .= "				TN.valor, ";
        $sql .= "				TN.tmp_rc_id ";
        $sql .= "FROM		notas_credito_ajuste NA, ";
        $sql .= "				tmp_rc_detalle_tesoreria_notas_credito TN ";
        $sql .= "WHERE	TN.tmp_recibo_id = " . $datos['recibo_caja'] . " ";
        $sql .= "AND		TN.empresa_id = '" . $empresa . "' ";
        $sql .= "AND		NA.tercero_id = '" . $datos['tercero_id'] . "' ";
        $sql .= "AND		NA.tipo_id_tercero = '" . $datos['tercero_tipo'] . "' ";
        $sql .= "AND		NA.empresa_id = TN.empresa_id  ";
        $sql .= "AND		NA.nota_credito_ajuste = TN.nota_credito_ajuste ";
        $sql .= "AND		NA.prefijo = TN.prefijo_nota ";
        $sql .= "ORDER BY 1,2 ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $notas = array();
        $arreglo = $datos;
        while (!$rst->EOF)
        {
            $notas[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $arreglo['tmp_nota'] = $rst->fields[4];
            $arreglo['prefijo_nota'] = $rst->fields[1];
            $arreglo['nota_credito_ajuste'] = $rst->fields[2];
            $this->actionX[] = ModuloGetURL('app', 'RecibosCaja', 'user', 'EliminarNotasCruce', array("datos" => $arreglo));
            $rst->MoveNext();
        }
        $rst->Close();
        return $notas;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function CruzarNotasCreditoBD()
    {
        $nota = $_REQUEST['notascredito'];
        $datos = $_REQUEST['datos'];
        if (!empty($nota))
        {
            $datos_nota = explode("*", $nota);
            $sql .= "INSERT INTO tmp_rc_detalle_tesoreria_notas_credito ";
            $sql .= "				(";
            $sql .= "				tmp_recibo_id,";
            $sql .= "				empresa_id,";
            $sql .= "				prefijo_nota,";
            $sql .= " 			nota_credito_ajuste,";
            $sql .= "				valor ";
            $sql .= "				) ";
            $sql .= "VALUES (";
            $sql .= "				 " . $datos['recibo_caja'] . ",";
            $sql .= "				'" . $_SESSION['RCFactura']['empresa'] . "',";
            $sql .= "				'" . $datos_nota[2] . "',";
            $sql .= "				 " . $datos_nota[3] . ",";
            $sql .= "				 " . $datos_nota[0] . " ";
            $sql .= "				)";

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            $this->parametro = "Informacion";
            $this->frmError['Informacion'] = "LA NOTA CREDITO " . $datos_nota[2] . " " . $datos_nota[3] . ", FUE ADICIONADA AL DETALLE DEL RECIBO DE CAJA";
        }
        $this->CruzarNotasCredito();
        return true;
    }

    /*
     * 
     */

    function UpdateModificarValorDocumento($numero_documento, $valor_documento, $sw_cruce_endosos)
    {
        if ($sw_cruce_endosos == '0')
        {
            $sql = "";
            $sql .= " UPDATE tmp_recibos_caja
                                  SET   total_abono=" . $valor_documento . ", 
                                        total_efectivo=" . $valor_documento . " 
                                  WHERE tmp_recibo_id = " . $numero_documento . " ";
        }
        else
        {
            $sql = "";
            $sql .= " UPDATE tmp_recibos_caja
                                  SET   total_abono=" . $valor_documento . ", 
                                        otros=" . $valor_documento . " 
                                  WHERE tmp_recibo_id = " . $numero_documento . " ";
        }

        if (!$rst = $this->ConexionBaseDatos($sql))
        {
            return false;
        }

        return true;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function EliminarNotasCruce()
    {
        $datos = $_REQUEST['datos'];

        $this->action = ModuloGetURL('app', 'RecibosCaja', 'user', 'EliminarNotasCruceBD', array("datos" => $datos));
        $this->actionM = ModuloGetURL('app', 'RecibosCaja', 'user', 'CruzarNotasCredito', array("datos" => $datos));

        $informacion = "<br>ESTA SEGURO QUE DESEA DESVINCULAR LA NOTA SELECCIONADA, DEL DETALLE DEL RECIBO DE CAJA? <br>";
        $this->FormaInformacion($informacion);

        return true;
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */

    function EliminarNotasCruceBD()
    {
        $datos = $_REQUEST['datos'];

        $sql .= "DELETE FROM tmp_rc_detalle_tesoreria_notas_credito ";
        $sql .= "WHERE	tmp_rc_id = " . $datos['tmp_nota'] . " ";
        $sql .= "AND		tmp_recibo_id = " . $datos['recibo_caja'] . " ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $this->parametro = "Informacion";
        $this->frmError['Informacion'] = "LA NOTA CREDITO FUE DESVINCULADA DEL DETALLE DEL RECIBO DE CAJA";

        $this->CruzarNotasCredito();
        return true;
    }

    /*     * *************************************************************************************
     * Funcion que permite realizar la conexion a la base de datos y ejecutar la consulta sql 
     * 
     * @param string sentencia sql a ejecutar 
     * @return rst 
     * ************************************************************************************** */

    function ConexionBaseDatos($sql)
    {
        list($dbconn) = GetDBConn();
        //$dbconn->debug = true;
        $rst = $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0)
        {
            $this->parametro = "MensajeError";
            $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg() . "<br>$sql";
            return false;
        }
        return $rst;
    }

    function ObtenerRcDetalles($tercero_tipo, $tercero_id)
    {

        $sql = "SELECT RD.* 
                FROM rc_detalles RD 
                INNER JOIN recibos_caja RC ON(RD.empresa_id = RC.empresa_id AND RD.centro_utilidad = RC.centro_utilidad AND RD.recibo_caja=RC.recibo_caja AND RD.prefijo=RC.prefijo)
                WHERE RC.tipo_id_tercero = '" . $tercero_tipo . "' AND RC.tercero_id = '" . $tercero_id . "' AND RD.valor_actual > 0 
                ORDER BY RD.recibo_caja ASC; ";

        /* echo "<pre>======3.SQL ObtenerRcDetalles =========";
          var_dump($tercero_tipo);
          var_dump($tercero_id);
          print_r($sql);
          echo "===============</pre>"; */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function obtenerFacturas($rc_prefijo, $rc_id)
    {
        $sql = "";
        $sql .= "   SELECT  prefijo_factura, factura_fiscal
                                FROM    facturas_rc
                                WHERE rc_prefijo = '" . $rc_prefijo . "'
                                AND rc_id = '" . $rc_id . "';";
       //echo "SQL:$sql<br>";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function obtenerFacturasDetalles($rc_prefijo_tras, $rc_id_tras, $prefijo_factura, $factura_fiscal)
    {
        $sql = "";
        $sql .= "   SELECT  prefijo_rc, 
                                        recibo_caja, 
                                        valor_detalle
                                FROM    facturas_rc_detalles
                                WHERE   rc_prefijo_tras = '" . $rc_prefijo_tras . "'
                                AND     rc_id_tras = '" . $rc_id_tras . "' 
                                AND     prefijo_factura = '" . $prefijo_factura . "'
                                AND     factura_fiscal = '" . $factura_fiscal . "';";

     //  echo "SQL:$sql<br>";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function obtenerValorRc($recibo_caja, $prefijo)
    {
        $sql = "";
        $sql .= "   SELECT  valor_actual
                                FROM    rc_detalles
                                WHERE   recibo_caja = '" . $recibo_caja . "' 
                                AND     prefijo = '" . $prefijo . "'; ";

       // echo "SQL:$sql<br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function updateValorRc($valor, $recibo_caja, $prefijo)
    {
        $sql = "";
        $sql .= "UPDATE rc_detalles 
                             SET    valor_actual = '" . $valor . "' 
                             WHERE  recibo_caja = '" . $recibo_caja . "' 
                             AND    prefijo = '" . $prefijo . "'; ";

       // echo "SQL:$sql<br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        return true;
        //return $sql;
    }

    function ObtnerSaldoAnticiposTercero($empresa_id, $tipo_id_tercero, $tercero_id)
    {
        $sql = "";
        $sql .= " SELECT saldo 
                              FROM   rc_control_anticipos
                              WHERE  empresa_id = '" . $empresa_id . "' 
                              AND    tipo_id_tercero='" . $tipo_id_tercero . "' 
                              AND    tercero_id = '" . $tercero_id . "' ";

        /* echo "<pre>======5.SQL ObtnerSaldoAnticiposTercero =========";
          var_dump($sql);
          echo "===============</pre>"; */



        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function TraerConsecutivoPrefijo($empresa_id, $prefijo_id)
    {

        $sql .= " SELECT max(consecutivo) as consecutivo
                    FROM documentos_anulacion_tesoreria
                    WHERE empresa_id = '" . $empresa_id . "'
                    AND prefijo_id = '$prefijo_id'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        //$terceros = array();
        while (!$rst->EOF)
        {
            $observacion = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $observacion;
    }

    function getPermisoTiposDocumentos($empresa, $usuario, $rc_tipo_documento)
    {
        $sql = "";
        $sql .= " SELECT  sw_permiso 
                                  FROM    userpermisos_menu_rec_tesoreria 
                                  WHERE   empresa_id = '" . $empresa . "'
                                  AND     usuario_id = '" . $usuario . "' 
                                  AND     rc_tipo_documento = '" . $rc_tipo_documento . "'; ";
        //echo "SQL getPermiso:$sql<br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        //$datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            ;
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function getPermisoAnularDcoumentos($empresa, $usuario)
    {
        $sql = "";
        $sql .= " SELECT  anular_consignaciones,
                                        contra_factura, 
                                        busqueda_documentos_anulados
                                FROM    userpermisos_menu_anulacion_recibos 
                                WHERE   empresa_id = '" . $empresa . "'
                                AND     usuario_id = '" . $usuario . "'; ";
        //echo "PER:$sql<br>";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        //$datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    function ObtenerAbonos($recibo_caja)
    {
        $sql = "SELECT sum(valor_abonado) FROM tmp_rc_detalle_tesoreria_facturas WHERE tmp_recibo_id = $recibo_caja";

        /* echo "<pre>====== 6.sql ObtenerAbonos =========";
          var_dump($sql);
          echo "===============</pre>"; */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        /* while(!$rst->EOF)
          {
          $datos = $rst->GetRowAssoc($ToUpper = false);;
          $rst->MoveNext();
          } */

        $datos = $rst->fields[0];

        $rst->Close();
        return $datos;
    }
	
    function ActualizarSaldoFactura($prefijo,$recibo_caja)
    {
	
		//DESCUENTA EL SALDO DE TABLA "inv_facturas_despacho"
		$rc_inv_facturas_despacho = $this->getRCFacturasInvFacturasDespacho($prefijo,$recibo_caja);
		for ($st = 0; $st < count($rc_inv_facturas_despacho); $st++)
		{	
            $sql .= " UPDATE  inv_facturas_despacho 
                    SET     saldo = saldo +" . (int) ($rc_inv_facturas_despacho[$st]['valor_abonado']) . " 
                    WHERE   factura_fiscal = '" . $rc_inv_facturas_despacho[$st]['factura_fiscal'] . "' 
                    AND     prefijo = '" . $rc_inv_facturas_despacho[$st]['prefijo_factura'] . "' 
                    AND     empresa_id = '" . $rc_inv_facturas_despacho[$st]['empresa_id'] . "'; ";

            $sql .= " UPDATE  inv_facturas_agrupadas_despacho 
                    SET     saldo = saldo +" . (int) ($rc_inv_facturas_despacho[$st]['valor_abonado']) . " 
                    WHERE   factura_fiscal = '" . $rc_inv_facturas_despacho[$st]['factura_fiscal'] . "' 
                    AND     prefijo = '" . $rc_inv_facturas_despacho[$st]['prefijo_factura'] . "' 
                    AND     empresa_id = '" . $rc_inv_facturas_despacho[$st]['empresa_id'] . "'; ";


        }
		
					
			//echo "<PRE>:$sql</pre>";
            if (!$rst = $this->ConexionBaseDatos($sql))
               return false;
		
		return true;
	}

}

?>