<?php

/*
  $Id: datosbusquedaCargos.php,v 1.11 2011/06/24 16:36:53 hugo Exp $
 */

//*****************************************
//METODOS NUEVOS PARA LA BUSQUEDA DE CARGOS
//*****************************************

function reqBuscarDatosCargos($EmpresaId, $CU, $PlanId, $Cuenta, $codigo, $descripcion, $departamento, $descripcion_dpto, $profesional, $fecha_cargo, $fechaIngreso, $fechaEgreso, $Ingreso) {
    $objResponse = new xajaxResponse();
    $html = "";

    IncludeClass('AgregarCargos', '', 'app', 'Cuentas');
    $fact = new AgregarCargos();

    $fechaActual = date("Y-m-d H:i:s");
    $fechaActualFComparar = strtotime($fechaActual);

    $fechaDelCargo = explode("/", $fecha_cargo);
    $fechaCargoFComparar = "" . $fechaDelCargo[2] . "-" . $fechaDelCargo[1] . "-" . $fechaDelCargo[0] . " 00:00:00";
    $fechaCargoFComparar = strtotime($fechaCargoFComparar);

    if ($fechaActualFComparar < $fechaCargoFComparar) {
        $html = "    <label class=\"label_error\" >LA FECHA DEL CARGO NO DEBE DE SER SUPERIOR A LA FECHA ACTUAL.</label>\n";
    } else {
        $fechaCargoFComparar = "" . $fechaDelCargo[2] . "-" . $fechaDelCargo[1] . "-" . $fechaDelCargo[0] . " 23:59:59";
        $fechaCargoFComparar = strtotime($fechaCargoFComparar);

        $fechaIngreso = substr($fechaIngreso, 0, 19);
        $fechaIngresoFComparar = strtotime($fechaIngreso);
        if ($fechaCargoFComparar < $fechaIngresoFComparar)
            $html = "    <label class=\"label_error\" >LA FECHA DEL CARGO NO PUEDE SER MENOR A LA FECHA DE INGRESO DEL PACIENTE: " . $fechaIngreso . ".</label>\n";
        else {
            if (!empty($fechaEgreso)) {
                $fechaCargoFComparar = "" . $fechaDelCargo[2] . "-" . $fechaDelCargo[1] . "-" . $fechaDelCargo[0] . " 00:00:00";
                $fechaCargoFComparar = strtotime($fechaCargoFComparar);

                $fechaEgreso = substr($fechaEgreso, 0, 19);
                $fechaEgresoFComparar = strtotime($fechaEgreso);
                if ($fechaCargoFComparar > $fechaEgresoFComparar)
                    $html = "    <label class=\"label_error\" >LA FECHA DEL CARGO NO PUEDE SER MAYOR A LA FECHA DE SALIDA DEL PACIENTE: " . $fechaEgreso . ".</label>\n";
            }
        }
    }

    if (empty($html)) {
        $ubicacionPaciente = $fact->BuscarFechaEgresoMaxEstacionPaciente($Cuenta);

        if (!empty($ubicacionPaciente)) {
            if (!empty($ubicacionPaciente[0]['fecha_egreso'])) {
                $fechaCargoFComparar = "" . $fechaDelCargo[2] . "-" . $fechaDelCargo[1] . "-" . $fechaDelCargo[0] . " 00:00:00";
                $fechaCargoFComparar = strtotime($fechaCargoFComparar);

                $fechaEgresoEstacion = substr("" . $ubicacionPaciente[0]['fecha_egreso'], 0, 19);
                $fechaEgresoEstacionFComparar = strtotime($fechaEgresoEstacion);
                if ($fechaCargoFComparar > $fechaEgresoEstacionFComparar) {
                    $html = "    <label class=\"label_error\" >EL PACIENTE TIENE UN INGRESO PENDIENTE EN LA ESTACI&Oacute;N: " . $ubicacionPaciente[0]['descripcion_estacion_destino'] . ", POR LO QUE LA FECHA DEL CARGO NO PUEDE SER SUPERIOR A: " . $fechaEgresoEstacion . ".</label>\n";
                }
            }
            if (empty($ubicacionPaciente[0]['estacion_origen'])) {
                $html = "    <label class=\"label_error\" >EL PACIENTE TIENE UN INGRESO PENDIENTE EN LA ESTACI&Oacute;N: " . $ubicacionPaciente[0]['descripcion_estacion_destino'] . " Y DEBE DE SER INGRESADO EN UNA ESTACI&Oacute;N DE ENFERMER&Iacute;A,<BR>PARA PODER ADICIONARLE EL CARGO.</label>\n";
            }
        }
    }
    if (!empty($html)) {
        $objResponse->assign("tablacargosbusqueda", "innerHTML", "");
        $objResponse->assign("cargosbusqueda", "style.display", "none");

        $objResponse->assign("errorConsultarCargosFechas", "innerHTML", $html);
        $objResponse->assign("errorConsultarCargosFechas", "style.display", "block");
        return $objResponse;
    }

    if ($codigo != null OR $descripcion != null) {
        $objResponse->assign("errorConsultarCargosFechas", "innerHTML", "");
        $objResponse->assign("errorConsultarCargosFechas", "style.display", "none");

        if ($descripcion) {
            $filtro = " lower (a.descripcion) ILIKE '%$descripcion%' ";
        } else {
            $filtro = " lower (a.cargo) ILIKE '$codigo%' ";
        }
        $sql = "SELECT a.descripcion, a.cargo, a.precio, sw_costo_variable
							FROM cups a 
							WHERE $filtro ";

        if (!$rst = ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        if ($datos) {
            $html = "";
            foreach ($datos as $key => $val) {
                $html .= "	<tr onmouseout=mOut(this,document.getElementById('tablacargosbusqueda').style.background); onmouseover=mOvr(this,'#FFFFFF'); class=\"modulo_table_list\">\n";
                $html .= "		<td width=\"15%\" align=\"center\">" . $descripcion_dpto . "</td>\n";
                $html .= "    <td width=\"13%\" align=\"center\">" . $val[cargo] . "</td>\n";
                $html .= "    <td width=\"65%\">" . $val[descripcion] . "</td>\n";
                $html .= "    <td width=\"3%\">\n";
                $html .= "      <input type='text' class='input-text' name=\"cantidad/**/" . $departamento . "/**/" . $val[cargo] . '/**/' . $val[sw_costo_variable] . "\" id=\"cantidad/**/" . $departamento . "/**/" . $val[cargo] . '/**/' . $val[sw_costo_variable] . "\" size=\"6\" maxlength=\"6\" value=\"1\">\n";
                $html .= "    </td>\n";
                $html .= "    <td width=\"5%\" onclick=\"xajax_reqSeleccionarCargo('$EmpresaId','$CU','$PlanId','$Cuenta','$val[cargo]','$val[descripcion]',document.getElementById('cantidad/**/" . $departamento . '/**/' . $val[cargo] . '/**/' . $val[sw_costo_variable] . "').value,'$departamento','$descripcion_dpto','$profesional','$fecha_cargo',$val[precio],'$val[sw_costo_variable]')\">\n";
                $html .= "      <img src=\"" . GetThemePath() . "/images/arriba.png\" tittle=\"SELECCIONAR GARGO\">\n";
                $html .= "    </td>\n"; //document.FormabuscargosSel.cantidad.value
                $html .= "	</tr>\n";
            }
        }
    }

    $objResponse->assign("tablacargosbusqueda", "innerHTML", $html);
    $objResponse->assign("cargosbusqueda", "style.display", "block");
    return $objResponse;
}

function validarFormularioSolicitudesOrdenesNoEjecutadas($form) {
    $objResponse = new xajaxResponse();

    echo(" que la vida se me vuelve un 8 ");

    return $objResponse;
}

function reqSeleccionarCargo($EmpresaId, $CU, $PlanId, $Cuenta, $cargo, $descripcion, $cantidad, $departamento, $descripcion_dpto, $profesional, $fecha_cargo, $precio, $sw_costo_variable) {
    $objResponse = new xajaxResponse();
    if ($sw_costo_variable == '1') {
        CargoCostoVariable(&$objResponse, $EmpresaId, $CU, $PlanId, $Cuenta, $cargo, $descripcion, $cantidad, $departamento, $descripcion_dpto, $profesional, $fecha_cargo, $precio);
    } else {
        $total_cargo = $cantidad * $precio;
        $html = "<td>$descripcion_dpto</td><td align=\"center\">$cargo</td><td>$descripcion</td><td>$cantidad</td><td>$precio</td><td>$total_cargo</td><td align=\"center\" onclick=\"xajax_reqEliminarDatosCargos('$departamento','$cargo')\"><img src=\"" . GetThemePath() . "/images/elimina.png\" tittle=\"ELIMINAR GARGO\"></td>\n";
        //$html = "<td>$descripcion_dpto</td><td align=\"center\">$cargo</td><td>$descripcion</td><td align=\"center\">$cantidad</td><td>&nbsp;</td><td>&nbsp;</td><td align=\"center\" onclick=\"xajax_reqEliminarDatosCargos('$departamento','$cargo')\"><img src=\"".GetThemePath()."/images/elimina.png\" title=\"ELIMINAR GARGO\"></td>\n";
        $objResponse->create("tablacargosseleccionados", "tr", $departamento . '/**/' . $cargo);
        $objResponse->assign($departamento . '/**/' . $cargo, "className", "modulo_table_list");
        $objResponse->assign($departamento . '/**/' . $cargo, "innerHTML", $html);
        $objResponse->assign("cargosseleccionados", "style.display", "block");
        $objResponse->assign("guardar", "style.display", "block");
        if ($_SESSION['CUENTAS']['ADD_CARGOS']) {
            array_push($_SESSION['CUENTAS']['ADD_CARGOS'], array('PlanId' => $PlanId, 'Cuenta' => $Cuenta, 'codigo' => $cargo, 'descripcion' => $descripcion, 'departamento' => $departamento, 'profesional' => $profesional, 'fecha_cargo' => $fecha_cargo, 'cantidad' => $cantidad));
        } else {
            $_SESSION['CUENTAS']['ADD_CARGOS'] = array(array('PlanId' => $PlanId, 'Cuenta' => $Cuenta, 'codigo' => $cargo, 'descripcion' => $descripcion, 'departamento' => $departamento, 'profesional' => $profesional, 'fecha_cargo' => $fecha_cargo, 'cantidad' => $cantidad));
        }
        $objResponse->call("Contar");
    }
    return $objResponse;
}

function CargoCostoVariable(&$obj, $EmpresaId, $CU, $PlanId, $Cuenta, $cargo, $descripcion, $cantidad, $departamento, $descripcion_dpto, $profesional, $fecha_cargo, $precio) {
    //$objResponse = new xajaxResponse();     
    $ventana = CrearVentanaCargoCostoVariable($EmpresaId, $CU, $PlanId, $Cuenta, $cargo, $descripcion, $cantidad, $departamento, $descripcion_dpto, $profesional, $fecha_cargo, $precio);
    $obj->assign("d2Contents", "innerHTML", $ventana);
    $obj->call('Iniciar');
    $obj->call('MostrarVentana');
    return $obj;
}

function CrearVentanaCargoCostoVariable($EmpresaId, $CU, $PlanId, $Cuenta, $cargo, $descripcion, $cantidad, $departamento, $descripcion_dpto, $profesional, $fecha_cargo, $precio) {
    $ventana = "  <form name=\"formaCostoVariable\" action=\"$action\" method=\"post\">";
    $ventana .= "  <table align=\"center\">";
    if ($mensaje) {
        $ventana .= "    <tr align=\"center\"><td align=\"center\" class=\"label_error\" colspan=\"6\">$mensaje</td></tr>";
    }
    $ventana .= "    <tr align=\"center\">";
    $ventana .= "    <td align=\"center\" class=\"Menu\" colspan=\"6\"><b>CAMBIAR COSTO VARIABLE : $cargo  $descripcion </b></td>";
    $ventana .= "    </tr>";
    $ventana .= "    <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">$cargo - DEPTO: $descripcion_dpto</td></tr>\n";
    $ventana .= "    <tr class=\"modulo_list_claro\">\n";
    $ventana .= "      <td class=\"label\">CANTIDAD</td>\n";
    $ventana .= "      <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"cantidadvariable\" value=\"$cantidad\"></td>\n";
    $ventana .= "      <td class=\"label\">PRECIO</td>\n";
    $ventana .= "      <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"preciovariable\" value=\"$precio\"></td>\n";
    $ventana .= "    <tr><td></td></tr>\n";
    $ventana .= "    <tr><td colspan=\"6\" align=\"center\">\n";
    $ventana .= "    <input type=\"button\" class=\"input-submit\" name=\"insertar\" value=\"ACEPTAR\" onclick=\"xajax_InsertarCantidadCosto(document.formaCostoVariable.cantidadvariable.value,document.formaCostoVariable.preciovariable.value,'$cargo','$descripcion','$departamento','$descripcion_dpto','$PlanId','$Cuenta','$profesional','$fecha_cargo')\"></td></tr>\n";
    $ventana .= "  </table><BR>";
    //$ventana .= MostrarFechasVencimiento($codigo_producto,$valor,$cantidad);    
    $ventana .= "  </form>";
    return $ventana;
}

function InsertarCantidadCosto($cantidad, $precio, $cargo, $descripcion, $departamento, $descripcion_dpto, $PlanId, $Cuenta, $profesional, $fecha_cargo) {
    $objResponse = new xajaxResponse();
    $total_cargo = $cantidad * $precio;
    $html = "<td>$descripcion_dpto</td><td align=\"center\">$cargo</td><td>$descripcion</td><td>$cantidad</td><td>$precio</td><td>$total_cargo</td><td align=\"center\" onclick=\"xajax_reqEliminarDatosCargos('$departamento','$cargo')\"><img src=\"" . GetThemePath() . "/images/elimina.png\" tittle=\"ELIMINAR GARGO\"></td>\n";
    $objResponse->create("tablacargosseleccionados", "tr", $departamento . '/**/' . $cargo);
    $objResponse->assign($departamento . '/**/' . $cargo, "className", "modulo_table_list");
    $objResponse->assign($departamento . '/**/' . $cargo, "innerHTML", $html);
    $objResponse->assign("cargosseleccionados", "style.display", "block");
    $objResponse->assign("guardar", "style.display", "block");
    if ($_SESSION['CUENTAS']['ADD_CARGOS']) {
        array_push($_SESSION['CUENTAS']['ADD_CARGOS'], array('PlanId' => $PlanId, 'Cuenta' => $Cuenta, 'codigo' => $cargo, 'descripcion' => $descripcion, 'departamento' => $departamento, 'profesional' => $profesional, 'fecha_cargo' => $fecha_cargo, 'cantidad' => $cantidad, 'precio' => $precio));
    } else {
        $_SESSION['CUENTAS']['ADD_CARGOS'] = array(array('PlanId' => $PlanId, 'Cuenta' => $Cuenta, 'codigo' => $cargo, 'descripcion' => $descripcion, 'departamento' => $departamento, 'profesional' => $profesional, 'fecha_cargo' => $fecha_cargo, 'cantidad' => $cantidad, 'precio' => $precio));
    }
    $_SESSION['CUENTAS']['ADD_CARGOS_VARIABLES'] = $_SESSION['CUENTAS']['ADD_CARGOS'];

    $objResponse->call("Contar");
    $objResponse->call("Cerrar");
    $objResponse->assign("d2Contents", "style.display", "none");
    return $objResponse;
}

function reqEliminarDatosCargos($departamento, $codigo) {
    $objResponse = new xajaxResponse();
    $objResponse->remove($departamento . '/**/' . $codigo);
    foreach ($_SESSION['CUENTAS']['ADD_CARGOS'] AS $i => $v) {
        if ($v[codigo] == $codigo) {
            UNSET($_SESSION['CUENTAS']['ADD_CARGOS'][$i]);
        }
    }
    $objResponse->call("VerificarDatos");
    return $objResponse;
}

//*********************************************
//FIN METODOS NUEVOS PARA LA BUSQUEDA DE CARGOS
//*********************************************
//*********************************************
//FUNCIONES NUEVAS IYM
//*********************************************
function reqBuscarDatosIyM($codigo, $descripcionIyM, $EmpresaId, $CU, $Cuenta, $PlanId, $Ingreso, $Bodega, $departamento, $bodega_des, $departamento_des, $fecha_cargo, $precio_venta, $TipoId, $PacienteId) {
    $html = "";
    if ($codigo != null OR $descripcionIyM != null) {
        if ($descripcionIyM) {
            $filtro = " a.descripcion ILIKE '%" . $descripcionIyM . "%' ";
        } else {
            $filtro = " d.codigo_producto ILIKE '" . $codigo . "%' ";
        }

        $sql = "SELECT  X.*, 
                      CASE WHEN Y.sw_pos='1' THEN 'POS' 
                           WHEN Y.sw_pos='0' THEN 'NO POS' END as sw_pos
							FROM	(
                      SELECT  d.codigo_producto, 
                              LF.existencia_actual AS existencia, 
                              --a.descripcion,
						      fc_descripcion_producto(d.codigo_producto) as descripcion,
                              e.precio_venta, 
                              a.porc_iva,
                              b.descripcion||' POR '|| a.contenido_unidad_venta AS presentacion,
                              LF.lote,
                              TO_CHAR(LF.fecha_vencimiento,'DD/MM/YYYY') AS fecha_vencimiento 
                      FROM  inventarios_productos a INNER JOIN unidades b ON  (b.unidad_id=a.unidad_id)
				INNER JOIN existencias_bodegas d ON (d.codigo_producto=a.codigo_producto)
				INNER JOIN existencias_bodegas_lote_fv LF 
					ON  (d.empresa_id = LF.empresa_id AND d.centro_utilidad = LF.centro_utilidad
						 AND d.bodega = LF.bodega AND d.codigo_producto = LF.codigo_producto)
			    INNER JOIN inventarios e ON (e.empresa_id=d.empresa_id AND e.codigo_producto=d.codigo_producto)
                      WHERE   " . $filtro . "
                            AND     d.empresa_id= '" . $EmpresaId . "'
                            AND     d.centro_utilidad = '" . $CU . "'
                            AND     d.bodega = '" . $Bodega . "'
                            AND     d.estado = '1'
                            AND     e.sw_vende='1'
                            AND     LF.existencia_actual > 0
                            AND     d.existencia > 0
                    ) as X                           
                    LEFT JOIN medicamentos as Y 
                    ON (X.codigo_producto = Y.codigo_medicamento)";

        if (!$rst = ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        if ($datos) {
            $html = "<input type=\"hidden\" name=\"empresa_id\" value=\"" . $EmpresaId . "\">\n";
            $html .= "<input type=\"hidden\" name=\"centro_utilidad\" value=\"" . $CU . "\">\n";
            $html .= "<input type=\"hidden\" name=\"plan_id\" value=\"" . $PlanId . "\">\n";
            $html .= "<input type=\"hidden\" name=\"numerodecuenta\" value=\"" . $Cuenta . "\">\n";
            $html .= "<input type=\"hidden\" name=\"departamento\" value=\"" . $departamento . "\">\n";
            $html .= "<input type=\"hidden\" name=\"departamento_des\" value=\"" . utf8_decode($departamento_des) . "\">\n";
            $html .= "<input type=\"hidden\" name=\"bodega\" value=\"" . $Bodega . "\">\n";
            $html .= "<input type=\"hidden\" name=\"bodega_des\" value=\"" . $bodega_des . "\">\n";
            $html .= "<input type=\"hidden\" name=\"profesional\" value=\"" . $profesional . "\">\n";
            $html .= "<input type=\"hidden\" name=\"fecha_cargo\" value=\"" . $fecha_cargo . "\">\n";
            $html .= "  <table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
            $html .= "    <tr class=\"formulacion_table_list\">";
            $html .= "      <td align=\"center\" colspan=\"10\">RESULTADO BUSQUEDA IyM</td>";
            $html .= "    </tr>";
            $html .= "    <tr class=\"formulacion_table_list\">";
            $html .= "      <td align=\"center\" width=\"15%\">Dpto</td>";
            $html .= "      <td align=\"center\" width=\"15%\">Bodega</td>";
            $html .= "      <td align=\"center\" width=\"12%\">Codigo</td>";
            $html .= "      <td align=\"center\" width=\"30%\">Descripción</td>";
            $html .= "      <td align=\"center\" width=\"10%\">Lote</td>";
            $html .= "      <td align=\"center\" width=\"10%\">F. Vencimiento</td>";
            $html .= "      <td align=\"center\" width=\"7%\">Precio</td>";
            $html .= "      <td align=\"center\" width=\"5%\">Exist.</td>";
            $html .= "      <td align=\"center\" width=\"5\">Cant</td>";
            $html .= "      <td align=\"center\" width=\"1%\">Add</td>";
            $html .= "    </tr>";
            foreach ($datos as $key => $val) {
                $html .= "<tr class=\"modulo_list_claro\" onmouseout=mOut(this,document.getElementById('tablacargosIyMBusqueda').style.background); onmouseover=mOvr(this,'#FFFFFF');>\n";
                $html .= "  <td align=\"center\">" . utf8_decode($departamento_des) . "</td>\n";
                $html .= "  <td align=\"center\">" . $bodega_des . "</td>\n";
                $html .= "  <td >" . $val[codigo_producto] . "</td>\n";
                $html .= "  <td >" . $val[descripcion] . "</td>\n";
                $html .= "  <td >" . $val['lote'] . "</td>\n";
                $html .= "  <td >" . $val['fecha_vencimiento'] . "</td>\n";
                $html .= "  <td >" . $val[precio_venta] . "</td>\n";
                $html .= "  <td >\n";
                $html .= "    <label class=\"label_error\">" . $val[existencia] . "</label>\n";
                $html .= "  </td>\n";
                $html .= "  <td >\n";
                //$html .= "    <input type='text' class='input-text' name=\"cantidad/**/".$Bodega."/**/".$val[codigo_producto]."\" id=\"cantidad/**/".$Bodega."/**/".$val[codigo_producto]."\" size=\"6\" maxlength=\"6\" value=\"1\">\n";
                $html .= "    <input type='text' class='input-text' name=\"imd[" . $val['codigo_producto'] . "][" . $val['lote'] . "][" . $val['fecha_vencimiento'] . "][cantidad]\"  size=\"6\" maxlength=\"6\" value=\"1\">\n";
                $html .= "    <input type='hidden' name=\"imd[" . $val['codigo_producto'] . "][" . $val['lote'] . "][" . $val['fecha_vencimiento'] . "][precio]\" value=\"" . $val['precio_venta'] . "\">\n";
                $html .= "    <input type='hidden' name=\"imd[" . $val['codigo_producto'] . "][" . $val['lote'] . "][" . $val['fecha_vencimiento'] . "][descripcion]\" value=\"" . $val['descripcion'] . "\">\n";
                $html .= "    <input type='hidden' name=\"imd[" . $val['codigo_producto'] . "][" . $val['lote'] . "][" . $val['fecha_vencimiento'] . "][existencia]\" value=\"" . $val['existencia'] . "\">\n";
                $html .= "  </td>\n";
                $html .= "  <td onclick=\"xajax_reqSeleccionarCargoIYM(xajax.getFormValues('FormaResultadosIyM'),'" . $val['codigo_producto'] . "','" . $val['lote'] . "','" . $val['fecha_vencimiento'] . "')\">\n";
                $html .= "    <img src=\"" . GetThemePath() . "/images/arriba.png\" tittle=\"SELECCIONAR INSUMO O MEDICAMENTO\">\n";
                $html .= "  </td>\n"; //document.FormabuscargosSel.cantidad.value
                $html .= "</tr>\n";
            }
            $html .= "</table>\n";
        }
    }
    $objResponse = new xajaxResponse();
    $objResponse->assign("tablacargosIyMBusqueda", "innerHTML", $html);
    $objResponse->assign("BusquedaIyM", "style.display", "block");
    return $objResponse;
}

function SinExistencia($codigo, $descripcion, $bodega_des) {
    $objResponse = new xajaxResponse();
    $msg = "El Producto " . $codigo . " - " . $descripcion . ", NO tiene existencias en la bodega " . $bodega_des . ".";
    $objResponse->alert($msg);
    return $objResponse;
}

//function reqSeleccionarCargoIYM($EmpresaId,$CU,$PlanId,$Cuenta,$codigo,$descripcionIyM,$cantidad,$departamento,$descripcion_dpto,$Bodega,$bodega_des,$profesional,$fecha_cargo,$precio_venta)
function reqSeleccionarCargoIYM($form, $codigo_producto, $lote, $fecha_vencimiento) {
    $objResponse = new xajaxResponse();

    if ($form['imd'][$codigo_producto][$lote][$fecha_vencimiento]['existencia'] == 0) {
        $objResponse->alert("EL PRODUCTO " . $form['imd'][$codigo_producto][$lote][$fecha_vencimiento]['descripcion'] . " NO POSEE EXISTENCIAS EN LA BODEGA " . $form['bodega_des'] . " ");
        return $objResponse;
    }

    $existencias = SessionGetVar("ValidacionExistencias");
    $existencias[$codigo_producto][$lote][$fecha_vencimiento]['existencia'] = $form['imd'][$codigo_producto][$lote][$fecha_vencimiento]['existencia'];
    $existencias[$codigo_producto][$lote][$fecha_vencimiento]['cantidad'] += $form['imd'][$codigo_producto][$lote][$fecha_vencimiento]['cantidad'];

    if ($existencias[$codigo_producto][$lote][$fecha_vencimiento]['existencia'] < $existencias[$codigo_producto][$lote][$fecha_vencimiento]['cantidad']) {
        $objResponse->alert("LAS CANTIDADES SOLICITADAS (" . $existencias[$codigo_producto][$lote][$fecha_vencimiento]['cantidad'] . ") DEL PRODUCTO " . $form['imd'][$codigo_producto][$lote][$fecha_vencimiento]['descripcion'] . " \nES MAYOR A LA EXISTENCIA ACTUAL (" . $existencias[$codigo_producto][$lote][$fecha_vencimiento]['existencia'] . ") ");
        return $objResponse;
    }

    $insumos = ($_SESSION['CUENTAS']['ADD_IYM']) ? $_SESSION['CUENTAS']['ADD_IYM'] : array();
    $datos = array('EmpresaId' => $form['empresa_id'],
        'CU' => $form['centro_utilidad'],
        'departamento' => $form['departamento'],
        'Bodega' => $form['bodega'],
        'PlanId' => $form['plan_id'],
        'Cuenta' => $form['numerodecuenta'],
        'codigo' => $codigo_producto,
        'descripcion' => $form['imd'][$codigo_producto][$lote][$fecha_vencimiento]['descripcion'],
        'precio' => $form['imd'][$codigo_producto][$lote][$fecha_vencimiento]['precio'],
        'fecha_cargo' => $form['fecha_cargo'],
        'cantidad' => $form['imd'][$codigo_producto][$lote][$fecha_vencimiento]['cantidad'],
        'lote' => $lote,
        'fecha_vencimiento' => $fecha_vencimiento,
        'departamento_des' => utf8_decode($form['departamento_des']),
        'bodega_des' => $form['bodega_des']
    );
    array_push($insumos, $datos);

    $html = FormaInsumos($insumos);

    $objResponse->assign("SeleccionadosIyM", "innerHTML", $html);
    $objResponse->assign("SeleccionadosIyM", "style.display", "block");
    $objResponse->assign("guardarIyM", "style.display", "block");
    $_SESSION['CUENTAS']['ADD_IYM'] = $insumos;
    SessionSetVar("ValidacionExistencias", $existencias);

    $objResponse->call("ContarIyM");
    return $objResponse;
}

/**
 * Funcion donde se crea la forma de los insumos
 * 
 * @param array $insumos Arreglo de datos de los insumos
 *
 * @return string
 */
function FormaInsumos($insumos) {
    $html = "";
    if (!empty($insumos)) {
        $html .= "<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">";
        $html .= "  <tr class=\"formulacion_table_list\">";
        $html .= "    <td align=\"center\" colspan=\"10\">IyM SELECCIONADOS</td>";
        $html .= "  </tr>";
        $html .= "  <tr class=\"formulacion_table_list\">";
        $html .= "    <td width=\"15%\">Dpto</td>";
        $html .= "    <td width=\"15%\">Bodega</td>";
        $html .= "    <td width=\"12%\">Codigo</td>";
        $html .= "    <td width=\"30%\">Descripción</td>";
        $html .= "    <td width=\"10%\">Lote</td>";
        $html .= "    <td width=\"10%\">F. Vencimiento</td>";
        $html .= "    <td width=\"7%\">Precio</td>";
        $html .= "    <td width=\"5\">Cant</td>";
        $html .= "    <td width=\"5\">Total</td>";
        $html .= "    <td width=\"1%\">Elim</td>";
        $html .= "  </tr>\n";

        $est = "modulo_list_claro";
        foreach ($insumos as $k => $dtl) {
            $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";

            $html .= "  <tr class=\"" . $est . "\">";
            $html .= "    <td align=\"center\">" . $dtl['departamento_des'] . "</td>\n";
            $html .= "    <td align=\"center\">" . $dtl['bodega_des'] . "</td>\n";
            $html .= "    <td >" . $dtl['codigo'] . "</td>\n";
            $html .= "    <td >" . $dtl['descripcion'] . "</td>\n";
            $html .= "    <td >" . $dtl['lote'] . "</td>\n";
            $html .= "    <td >" . $dtl['fecha_vencimiento'] . "</td>\n";
            $html .= "    <td >" . $dtl['precio'] . "</td>\n";
            $html .= "    <td >" . $dtl['cantidad'] . "</td>\n";
            $html .= "    <td align=\"right\">$" . FormatoValor($dtl['cantidad'] * $dtl['precio']) . "</td>\n";
            $html .= "    <td onclick=\"xajax_reqEliminarCargoIYM('" . $k . "')\">\n";
            $html .= "      <img src=\"" . GetThemePath() . "/images/elimina.png\" tittle=\"ELIMINAR GARGO\">\n";
            $html .= "    </td>\n";
        }
        $html .= "</table>";
    }
    return $html;
}

/**
 *
 */
function reqEliminarCargoIYM($key) {
    $objResponse = new xajaxResponse();
    $insumos = $_SESSION['CUENTAS']['ADD_IYM'];
    $existencias = SessionGetVar("ValidacionExistencias");
    $existencias[$insumos[$key]['codigo']][$insumos[$key]['lote']][$insumos[$key]['fecha_vencimiento']]['cantidad'] -= $insumos[$key]['cantidad'];

    unset($insumos[$key]);
    $html = FormaInsumos($insumos);

    $objResponse->assign("SeleccionadosIyM", "innerHTML", $html);
    $objResponse->assign("SeleccionadosIyM", "style.display", ((empty($insumos)) ? "none" : "block"));

    $objResponse->assign("guardarIyM", "style.display", ((empty($insumos)) ? "none" : "block"));
    $_SESSION['CUENTAS']['ADD_IYM'] = $insumos;
    SessionSetVar("ValidacionExistencias", $existencias);

    return $objResponse;
}

//*********************************************
//FIN FUNCIONES NUEVAS IYM
//*********************************************

function reqObtenerDatosCargos($key, $EmpresaId, $CU, $Bodega, $sw_descripcion) {
    //$obj = new app_EJEMPLO_user();
    //$html = $obj->ConsultaPais($key);
    $objResponse = new xajaxResponse();
    $html = ObtenerDatosCargos($key, $EmpresaId, $CU, $Bodega, $sw_descripcion);
    if ($sw_descripcion) {
        if (!$html) {
            $objResponse->assign("lista_descripcion", "style.display", "none");
        } else {
            $html = $objResponse->setTildes($html);
            //$objResponse->setCharEncoding("ISO-8859-1");
            $objResponse->assign("lista_descripcion", "style.display", "block");
            $objResponse->assign("lista_descripcion", "innerHTML", $html);
            //$objResponse->call("AsignarValor");
        }
    } else {
        if (!$html) {
            $objResponse->assign("lista", "style.display", "none");
        } else {
            $html = $objResponse->setTildes($html);
            //$objResponse->setCharEncoding("ISO-8859-1");
            $objResponse->assign("lista", "style.display", "block");
            $objResponse->assign("lista", "innerHTML", $html);
            //$objResponse->call("AsignarValor");
        }
    }
    //$objResponse->alert("Hola KEY $key");
    return $objResponse;
}

function ObtenerDatosCargos($cod, $EmpresaId, $CU, $Departamento, $sw_descripcion) {
    $html = "";
    if ($cod != null) {
        if ($sw_descripcion) {
            $filtro = " lower (a.descripcion) ILIKE '%$cod%' ";
        } else {
            $filtro = " lower (a.cargo) ILIKE '$cod%' ";
        }
        $sql = "SELECT a.descripcion, a.cargo 
							FROM cups a 
							WHERE $filtro ";

        if (!$rst = ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        if ($datos AND $sw_descripcion) {
            $cod = strtoupper($cod);
            $html .= "<table class=\"normal_10A\" width=\"100%\">\n";
            foreach ($datos as $key => $val) {
                $nuevo = str_replace($cod, "<b>" . $cod . "</b>", $val['descripcion']);
                $html .= "	<tr onmouseout=mOut(this,document.getElementById('lista_descripcion').style.background); onmouseover=mOvr(this,'#FFFFFF');>\n";
                $html .= "		<td onclick=\"AsignarValorCargos('" . $val['cargo'] . "','" . $val['descripcion'] . "','" . $sw_descripcion . "')\">" . $nuevo . "</td>\n";
                $html .= "	</tr>\n";
            }
            $html .= "</table>\n";
        } elseif ($datos) {
            $cod = strtoupper($cod);
            $html .= "<table class=\"normal_10A\" width=\"100%\">\n";
            foreach ($datos as $key => $val) {
                $nuevo = str_replace($cod, "<b>" . $cod . "</b>", $val['cargo']);
                $html .= "	<tr onmouseout=mOut(this,document.getElementById('lista').style.background); onmouseover=mOvr(this,'#FFFFFF');>\n";
                $html .= "		<td onclick=\"AsignarValorCargos('" . $val['cargo'] . "','" . $val['descripcion'] . "','" . $sw_descripcion . "')\">" . $nuevo . "</td>\n";
                $html .= "	</tr>\n";
            }
            $html .= "</table>\n";
        }
    }
    return $html;
}

function reqAdicionarDatosCargos($PlanId, $Cuenta, $codigo, $descripcion, $departamento, $descripcion_dpto, $profesional, $fecha_cargo, $cantidad) {
    $objResponse = new xajaxResponse();
    $html = "<td>$descripcion_dpto</td><td align=\"center\">$codigo</td><td>$descripcion</td><td>$cantidad</td><td align=\"center\" onclick=\"xajax_reqEliminarDatosCargos('$codigo')\"><img src=\"" . GetThemePath() . "/images/elimina.png\" tittle=\"ELIMINAR GARGO\"></td>\n";
    $objResponse->create("cargos", "tr", $codigo);
    $objResponse->assign($codigo, "className", "modulo_table_list");
    $objResponse->assign($codigo, "innerHTML", $html);
    $objResponse->assign("x", "style.display", "block");
    $objResponse->assign("guardar", "style.display", "block");
    if ($_SESSION['CUENTAS']['ADD_CARGOS']) {
        array_push($_SESSION['CUENTAS']['ADD_CARGOS'], array('PlanId' => $PlanId, 'Cuenta' => $Cuenta, 'codigo' => $codigo, 'descripcion' => $descripcion, 'departamento' => $departamento, 'profesional' => $profesional, 'fecha_cargo' => $fecha_cargo, 'cantidad' => $cantidad));
    } else {
        $_SESSION['CUENTAS']['ADD_CARGOS'] = array(array('PlanId' => $PlanId, 'Cuenta' => $Cuenta, 'codigo' => $codigo, 'descripcion' => $descripcion, 'departamento' => $departamento, 'profesional' => $profesional, 'fecha_cargo' => $fecha_cargo, 'cantidad' => $cantidad));
    }
    $objResponse->call("Contar");
    return $objResponse;
}

//FUNCIONES PARA MANEJO DE INSUMOS Y MEDICAMENTOS
function reqObtenerDatosIyM($key, $EmpresaId, $CU, $Bodega, $Departamento, $sw_descripcion) {
    //$obj = new app_EJEMPLO_user();
    $objResponse = new xajaxResponse();
    //$html = $obj->ConsultaPais($key);
    $html = ObtenerDatosIyM($key, $EmpresaId, $CU, $Bodega, $Departamento, $sw_descripcion);
    if ($sw_descripcion) {
        if (!$html) {
            $objResponse->assign("lista_descripcionIyM", "style.display", "none");
        } else {
            $html = $objResponse->setTildes($html);
            //$objResponse->setCharEncoding("ISO-8859-1");
            $objResponse->assign("lista_descripcionIyM", "style.display", "block");
            $objResponse->assign("lista_descripcionIyM", "innerHTML", $html);
            //$objResponse->call("AsignarValor");
        }
    } else {
        if (!$html) {
            $objResponse->assign("listaIyM", "style.display", "none");
        } else {
            $html = $objResponse->setTildes($html);
            //$objResponse->setCharEncoding("ISO-8859-1");
            $objResponse->assign("listaIyM", "style.display", "block");
            $objResponse->assign("listaIyM", "innerHTML", $html);
            //$objResponse->call("AsignarValor");
        }
    }
    //$objResponse->alert("Hola KEY $key");
    return $objResponse;
}

function ObtenerDatosIyM($cod, $EmpresaId, $CU, $Bodega, $Departamento, $sw_descripcion) {
    $html = "";
    if ($cod != null) {
        /* 			$sql  = "SELECT pais ";
          $sql .= "FROM	 	tipo_pais ";
          $sql .= "WHERE	pais ILIKE '".$cod."%'";
          $sql .= "AND		pais <> '".strtoupper($cod)."' ";
          $sql .= "ORDER BY 1"; */
        if ($sw_descripcion) {
            $filtro = " a.descripcion ILIKE '" . $cod . "%' ";
        } else {
            $filtro = " d.codigo_producto ILIKE '" . $cod . "%' ";
        }
        $sql = "SELECT X.*, CASE WHEN Y.sw_pos='1' THEN 'POS' WHEN Y.sw_pos='0' THEN 'NO POS' END as sw_pos
							FROM
							(SELECT d.codigo_producto, d.existencia, a.descripcion, e.precio_venta, a.porc_iva,
								b.descripcion||' POR '|| a.contenido_unidad_venta AS presentacion
							FROM 
							inventarios_productos a, unidades b, existencias_bodegas d, inventarios e
							WHERE $filtro
							AND b.unidad_id=a.unidad_id
							AND d.empresa_id='$EmpresaId'
							AND d.centro_utilidad='$CU'
							AND d.bodega='$Bodega'
							AND d.estado='1'
							AND d.codigo_producto=a.codigo_producto
							AND e.empresa_id=d.empresa_id
							AND e.codigo_producto=d.codigo_producto
							AND e.sw_vende='1') as X                           
							LEFT JOIN medicamentos as Y ON (X.codigo_producto = Y.codigo_medicamento)";

        if (!$rst = ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        if ($datos AND $sw_descripcion) {
            $cod = strtoupper($cod);
            $html .= "<table class=\"normal_10A\" width=\"100%\">\n";
            foreach ($datos as $key => $val) {
                $nuevo = str_replace($cod, "<b>" . $cod . "</b>", $val['descripcion']);
                $html .= "	<tr onmouseout=mOut(this,document.getElementById('lista_descripcionIyM').style.background); onmouseover=mOvr(this,'#FFFFFF');>\n";
                $html .= "		<td onclick=\"AsignarValorIyM('" . $val['codigo_producto'] . "','" . $val['descripcion'] . "','" . $val['precio_venta'] . "','" . $val['existencia'] . "','" . $sw_descripcion . "')\">" . $nuevo . "</td>\n";
                $html .= "	</tr>\n";
            }
            $html .= "</table>\n";
        } elseif ($datos) {
            $cod = strtoupper($cod);
            $html .= "<table class=\"normal_10A\" width=\"100%\">\n";
            foreach ($datos as $key => $val) {
                $nuevo = str_replace($cod, "<b>" . $cod . "</b>", $val['codigo_producto']);
                $html .= "	<tr onmouseout=mOut(this,document.getElementById('listaIyM').style.background); onmouseover=mOvr(this,'#FFFFFF');>\n";
                $html .= "		<td onclick=\"AsignarValorIyM('" . $val['codigo_producto'] . "','" . $val['descripcion'] . "','" . $val['precio_venta'] . "','" . $val['existencia'] . "','" . $sw_descripcion . "')\">" . $nuevo . "</td>\n";
                $html .= "	</tr>\n";
            }
            $html .= "</table>\n";
        }
    }
    return $html;
}

function reqAdicionarDatosIyM($codigo, $descripcionIyM, $EmpresaId, $CU, $Cuenta, $PlanId, $Ingreso, $bodega, $departamento, $bodega_des, $departamento_des, $fecha_cargo, $cantidad, $precio_venta, $existencia, $TipoId, $PacienteId) {
    $objResponse = new xajaxResponse();
    $html = "<td>$departamento_des</td>\n";
    $html .= "<td>$bodega_des</td>\n";
    $html .= "<td align=\"center\">$codigo</td>\n";
    $html .= "<td>$descripcionIyM</td>\n";
    $html .= "<td>$precio_venta</td>\n";
    $html .= "<td>$cantidad</td>\n";
    $html .= "<td>$existencia</td>\n";
    $html .= "<td align=\"center\" onclick=\"xajax_reqEliminarDatosIyM('$departamento','$bodega','$codigo')\">\n";
    $html .= "  <img src=\"" . GetThemePath() . "/images/elimina.png\" tittle=\"ELIMINAR GARGO\">\n";
    $html .= "</td>\n";
    $objResponse->create("cargosIyM", "tr", $departamento . '/**/' . $bodega . '/**/' . $codigo);
    //$objResponse->assign($codigo,"style.background","red");
    $objResponse->assign($departamento . '/**/' . $bodega . '/**/' . $codigo, "className", "modulo_table_list");
    $objResponse->assign($departamento . '/**/' . $bodega . '/**/' . $codigo, "innerHTML", $html);
    $objResponse->assign("IyM", "style.display", "block");
    $objResponse->assign("guardarIyM", "style.display", "block");
    if ($_SESSION['CUENTAS']['ADD_CARGOS']) {
        array_push($_SESSION['CUENTAS']['ADD_CARGOS'], array('EmpresaId' => $EmpresaId, 'CU' => $CU, 'PlanId' => $PlanId, 'cuenta' => $Cuenta, 'codigo' => $codigo, 'descripcionIyM' => $descripcionIyM, 'departamento' => $departamento, 'bodega' => $bodega, 'fecha_cargo' => $fecha_cargo, 'cantidad' => $cantidad, 'precio' => $precio_venta, 'Ingreso' => $Ingreso, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId));
    } else {
        $_SESSION['CUENTAS']['ADD_CARGOS'] = array(array('EmpresaId' => $EmpresaId, 'CU' => $CU, 'PlanId' => $PlanId, 'cuenta' => $Cuenta, 'codigo' => $codigo, 'descripcionIyM' => $descripcionIyM, 'departamento' => $departamento, 'bodega' => $bodega, 'fecha_cargo' => $fecha_cargo, 'cantidad' => $cantidad, 'precio' => $precio_venta, 'Ingreso' => $Ingreso, 'TipoId' => $TipoId, 'PacienteId' => $PacienteId));
    }
    $objResponse->call("ContarIyM");

    return $objResponse;
}

function reqEliminarDatosIyM($departamento, $bodega, $codigo) {
    $objResponse = new xajaxResponse();
    $objResponse->remove($departamento . '/**/' . $bodega . '/**/' . $codigo);
    foreach ($_SESSION['CUENTAS']['ADD_CARGOS'] AS $i => $v) {
        if ($v[departamento] == $departamento
                AND $v[bodega] == $bodega
                AND $v[codigo] == $codigo) {
            UNSET($_SESSION['CUENTAS']['ADD_CARGOS'][$i]);
        }
    }
    $objResponse->call("VerificarDatosIyM");
    return $objResponse;
}

//FIN FUNCIONES PARA EL MENEJO DE INSUMOS Y MEDICAMENTOS
//********************************************************************
//********************************************************************
//DIVISION DE CUENTA
//********************************************************************
//********************************************************************

function reqCambiarCargoPlan($transaccion, $valor) {
    $objResponse = new xajaxResponse();
    (list($indice, $plan) = (explode('||//', $valor)));
    $valor = ActualizarPlanValor($transaccion, $indice, $plan, true);

    foreach ($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice => $vector)
        foreach ($vector as $plan => $plan_nom)
            $objResponse->assign("valor_" . $plan, "innerHTML", FormatoValor($valor[$plan]));

    return $objResponse;
}

function reqCambiarAbonoPlan($abono, $valor) {

    $objResponse = new xajaxResponse();
    (list($prefijo, $reciboCaja) = (explode('||//', $abono)));
    (list($indice, $plan, $Cuenta, $fecha_ingcaja, $total_efectivo, $total_cheques, $total_tarjetas, $total_bonos, $total_abono) = (explode('||//', $valor)));

    EliminarPlanAbono($prefijo, $reciboCaja, $Cuenta);
    ActualizarPlanAbono($plan, $indice, $Cuenta, $prefijo, $reciboCaja, $fecha_ingcaja, $total_efectivo, $total_cheques, $total_tarjetas, $total_bonos, $total_abono);

    $valor = ObtenerValoresRecibos($Cuenta);
    foreach ($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice => $vector)
        foreach ($vector as $plan => $plan_nom)
            $objResponse->assign("abono_" . $plan, "innerHTML", FormatoValor($valor[$plan]));

    return $objResponse;
}

function reqCambiarCargoPlanTotalPage($seleccion, $Cuenta, $limite, $off, $valor, $plan_ini, $pagina_actual) {

    $objResponse = new xajaxResponse();


    if ($seleccion == 'true') {
        (list($indice, $plan) = (explode('||//', $valor)));
    } else {
        (list($indice, $plan) = (explode('||//', $valor)));
        $indice = '0';
        $plan = $plan_ini;
    }


    $cargos = DetalleDivisionCuenta($Cuenta, $limite, $off);
    unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']);
    $_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']['SELECCION_TOTAL'][$indice] = $pagina_actual;
    for ($i = 0; $i < sizeof($cargos); $i++) {
        ActualizarPlanValor($cargos[$i]['transaccion'], $indice, $plan);
    }


    $det = DetalleDivisionCuenta($Cuenta, $limite, $off);
    $contcols = (sizeof($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']));
    $html .= "   <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "          <tr class=\"modulo_table_list_title\">";
    $html .= "            <td align=\"center\" colspan=\"" . (12 + $contcols) . "\">CARGOS DE LA CUENTA ACTUAL</td>";
    $html .= "          </tr>";
    $html .= "          <tr class=\"modulo_table_list_title\">";
    $html .= "            <td align=\"center\" colspan=\"11\">&nbsp;</td>";
    ksort($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);
    foreach ($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice => $vector) {
        foreach ($vector as $plan => $plan_nom) {
            if ($indice != '0') {
                $chequeado = '';
                if ($_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']['SELECCION_TOTAL'][$indice] == $pagina_actual) {
                    $chequeado = 'checked';
                }
                $html .= "            <td align=\"center\"><input type=\"checkbox\" name=\"SeleccionTotal$indice\" value=\"" . $indice . "||//" . $plan . "\" onclick=\"xajax_reqCambiarCargoPlanTotalPage(this.checked,'$Cuenta','$limite','$off',this.value,'$plan_ini','$pagina_actual')\" align=\"center\" $chequeado></td>";
            } else {
                $plan_ini = $plan;
                $html .= "            <td align=\"center\">&nbsp;</td>";
            }
        }
    }
    $html .= "          </tr>";
    $html .= "          <tr class=\"modulo_table_list_title\">";
    $html .= "            <td width=\"7%\">TARIFARIO</td>";
    $html .= "            <td width=\"5%\">CARGO</td>";
    $html .= "            <td width=\"10%\">CODIGO</td>";
    $html .= "            <td>DESCRIPCION</td>";
    $html .= "            <td width=\"8%\">FECHA CARGO</td>";
    $html .= "            <td width=\"5%\">HORA</td>";
    $html .= "            <td width=\"7%\">CANT</td>";
    $html .= "            <td width=\"8%\">VALOR CARGO</td>";
    $html .= "            <td width=\"8%\">VAL. NO CUBIERTO</td>";
    $html .= "            <td width=\"8%\">VAL. CUBIERTO</td>";
    $html .= "            <td width=\"10%\">DPTO.</td>";
    foreach ($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice => $vector) {
        foreach ($vector as $plan => $plan_nom) {
            if ($indice != '0') {
                $indice = $indice;
            } else {
                $indice = '';
            }
            $html .= "            <td width=\"3%\">$indice</td>";
        }
    }
    $html .= "          </tr>";
    $car = $cubi = $nocub = 0;
    if (!empty($det)) {
        for ($i = 0; $i < sizeof($det); $i++) {
            if ($i % 2) {
                $estilo = "modulo_list_claro";
            } else {
                $estilo = "modulo_list_oscuro";
            }
            //suma los totales del final
            $car+=$det[$i][valor_cargo];
            $cubi+=$det[$i][valor_cubierto];
            $nocub+=$det[$i][valor_nocubierto];
            $html .= "            <tr class=\"$estilo\">";
            $html .= "            <td width=\"7%\" align=\"center\">" . $det[$i][tarifario_id] . "</td>";
            $html .= "            <td width=\"5%\" align=\"center\">" . $det[$i][cargo] . "</td>";
            $html .= "            <td width=\"10%\" align=\"center\">" . $det[$i][codigo_producto] . "</td>";
            $html .= "            <td>" . $det[$i][descripcion] . "</td>";
            $html .= "            <td width=\"8%\" align=\"center\">" . FechaStampDiv($det[$i][fecha_cargo]) . "</td>";
            $html .= "            <td width=\"5%\" align=\"center\">" . HoraStampDiv($det[$i][fecha_cargo]) . "</td>";
            $html .= "            <td width=\"7%\" align=\"center\">" . FormatoValor($det[$i][cantidad]) . "</td>";
            $html .= "            <td width=\"8%\" align=\"center\">" . FormatoValor($det[$i][valor_cargo]) . "</td>";
            $html .= "            <td width=\"8%\" align=\"center\">" . FormatoValor($det[$i][valor_nocubierto]) . "</td>";
            $html .= "            <td width=\"8%\" align=\"center\">" . FormatoValor($det[$i][valor_cubierto]) . "</td>";
            $html .= "            <td>" . $det[$i][departamento] . "</td>";
            $valor = $det[$i - 1][transaccion] . '||//' . $det[$i - 1][cargo_cups] . '||//' . $det[$i - 1][codigo_agrupamiento_id] . '||//' . $det[$i - 1][consecutivo];
            //$html .= "            <td align=\"center\"><a href=\"javascript:CargoOtraCuenta(document.forma,'$valor','$Cuenta');\"><img border=\"0\" src=\"".GetThemePath()."/images/abajo.png\" title=\"Cargar a Otra Cuenta\"></a></td>";
            foreach ($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice => $vector) {
                foreach ($vector as $plan => $plan_nom) {
                    $che = '';
                    if ($det[$i][cuenta] == $indice) {
                        $che = 'checked';
                    }
                    $html .= "            <td width=\"3%\" align=\"center\"><input $che title=\"$plan_nom\" type=\"radio\" name=\"" . $det[$i][transaccion] . "\" value=\"" . $indice . "||//" . $plan . "\" onclick=\"xajax_reqCambiarCargoPlan(this.name,this.value)\"></td>";
                }
            }
            $html .= "            </tr>";
        }
        if ($i % 2) {
            $estilo = "modulo_list_claro";
        } else {
            $estilo = "modulo_list_oscuro";
        }
        $html .= "          <tr class=\"$estilo\">";
        $html .= "            <td colspan=\"7\" class=\"label\"  align=\"right\">TOTALES:  </td>";
        $html .= "            <td align=\"center\" class=\"label\">" . FormatoValor($car) . "</td>";
        $html .= "            <td align=\"center\" class=\"label\">" . FormatoValor($nocub) . "</td>";
        $html .= "            <td align=\"center\" class=\"label\">" . FormatoValor($cubi) . "</td>";
        $html .= "            <td>&nbsp;</td>";
        //$html .= "            <td align=\"center\"><a href=\"javascript:Bajar(document.forma);\"><img border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a></td>";
        foreach ($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice => $vector) {
            foreach ($vector as $plan => $plan_nom) {
                $html .= "            <td width=\"3%\"></td>";
            }
        }
        $html .= "          </tr>";
        $html .= "          </table>";
    }
    $objResponse->assign("capa_cargos", "innerHTML", $html);
    return $objResponse;
}

/**
 * Actualiza el plan del cargo de la cuenta.
 *
 * @access public         
 * @return mixed
 */
function ActualizarPlanValor($transaccion, $indice, $plan, $flag) {
    $query = "UPDATE tmp_division_cuenta
               SET cuenta='" . $indice . "',plan_id='" . $plan . "'
               WHERE transaccion='" . $transaccion . "'";
    if (!$resultado = ConexionBaseDatos($query))
        return false;

    $resultado->Close();
    if ($flag) {
        $sql = "SELECT plan_id, ";
        $sql .= "       SUM(valor_cargo) AS valor ";
        $sql .= "FROM   tmp_division_cuenta ";
        $sql .= "WHERE  numerodecuenta = (";
        $sql .= "         SELECT numerodecuenta ";
        $sql .= "         FROM   tmp_division_cuenta ";
        $sql .= "         WHERE  plan_id= " . $plan . " ";
        $sql .= "         AND    transaccion = " . $transaccion . " ";
        $sql .= "       ) ";
        $sql .= "GROUP BY plan_id ";

        $valor = array();
        if (!$rst = ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF) {
            $valor[$rst->fields[0]] = $rst->fields[1];
            $rst->MoveNext();
        }

        $rst->Close();
        return $valor;
    }
    return true;
}

/* * ********************************************************************************
 * Actualiza el plan del abono en la cuenta.
 *
 * @access public        
 * @return boolean
 * ********************************************************************************* */

function ActualizarPlanAbono($plan, $indice, $Cuenta, $prefijo, $recibo_caja, $fecha_ingcaja, $total_efectivo, $total_cheques, $total_tarjetas, $total_bonos, $total_abono) {

    $query = "INSERT INTO tmp_division_cuenta_abonos(plan_id, numerodecuenta,
                                                        recibo_caja, prefijo,
                                                        fecha_ingcaja, total_abono,
                                                        total_efectivo, total_cheques,
                                                        total_tarjetas, total_bonos,
                                                        cuenta)
                          VALUES(" . $plan . "," . $Cuenta . "," . $recibo_caja . ",'" . $prefijo . "',
                          '" . $fecha_ingcaja . "'," . $total_abono . ", " . $total_efectivo . ",
                          " . $total_cheques . "," . $total_tarjetas . "," . $total_bonos . ",
                          '" . $indice . "')";
    if (!$resultado = ConexionBaseDatos($query))
        return false;
    $resultado->Close();
    return true;
}

/**
 * Funcion donde se obtiene el valor de los recibos que
 * cambian de plan
 *
 * @param integer $Cuenta Identificador del cuenta
 *
 * @return mixed
 */
function ObtenerValoresRecibos($Cuenta) {
    $sql = "SELECT plan_id, ";
    $sql .= "       SUM(total_abono) AS valor ";
    $sql .= "FROM   tmp_division_cuenta_abonos ";
    $sql .= "WHERE  numerodecuenta = " . $Cuenta . " ";
    $sql .= "GROUP BY plan_id ";

    $valor = array();
    if (!$rst = ConexionBaseDatos($sql))
        return false;

    while (!$rst->EOF) {
        $valor[$rst->fields[0]] = $rst->fields[1];
        $rst->MoveNext();
    }

    $rst->Close();
    return $valor;
}

/* * ********************************************************************************
 * Elimina el abono en la cuenta.
 *
 * @access public          
 * @return array
 * ********************************************************************************* */

function EliminarPlanAbono($prefijo, $recibo_caja, $Cuenta) {

    $query = "DELETE FROM tmp_division_cuenta_abonos
                 WHERE recibo_caja='" . $recibo_caja . "' 
                 AND prefijo='" . $prefijo . "'                 
                 AND numerodecuenta='" . $Cuenta . "'";
    if (!$resultado = ConexionBaseDatos($query))
        return false;
    $resultado->Close();
    return true;
}

/* * ********************************************************************************
 * Consulta el detalle de la division de la cuenta.
 *
 * @access public          
 * @return array
 * ********************************************************************************* */

function DetalleDivisionCuenta($Cuenta, $limite, $off) {

    $query = "SELECT a.*,d.codigo_producto,
                          (CASE WHEN d.consecutivo IS NOT NULL 
                          THEN e.descripcion 
                          ELSE b.descripcion 
                          END) as descripcion,
                          c.plan_descripcion,
                          (CASE a.facturado WHEN 1 
                          THEN a.valor_cargo 
                          ELSE 0 
                          END) as fac,dpto.descripcion as departamento
                   FROM tmp_division_cuenta as a
                   LEFT JOIN cuentas_codigos_agrupamiento f ON (a.codigo_agrupamiento_id=f.codigo_agrupamiento_id)
                   LEFT JOIN bodegas_documentos_d d ON (a.consecutivo=d.consecutivo AND f.bodegas_doc_id=d.bodegas_doc_id AND f.numeracion=d.numeracion)
                   LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
                   LEFT JOIN departamentos dpto ON (a.departamento=dpto.departamento)
                   , tarifarios_detalle as b, planes as c
                   WHERE a.numerodecuenta=$Cuenta AND
                         a.cargo=b.cargo 
                   AND a.tarifario_id=b.tarifario_id
                   AND a.plan_id=c.plan_id
                   ORDER BY a.fecha_cargo,a.codigo_agrupamiento_id,a.transaccion";
    $query.=" LIMIT " . $limite . " OFFSET " . $off . "";
    if (!$resultado = ConexionBaseDatos($query))
        return false;
    while (!$resultado->EOF) {
        $vars[] = $resultado->GetRowAssoc($toUpper = false);
        $resultado->MoveNext();
    }
    $resultado->Close();
    return $vars;
}

/* * **************************************************************
 * Se encarga de separar la fecha del formato timestamp
 * @access private
 * @return string
 * @param date fecha
 */

function FechaStampDiv($fecha) {
    if ($fecha) {
        $fech = strtok($fecha, "-");
        for ($l = 0; $l < 3; $l++) {
            $date[$l] = $fech;
            $fech = strtok("-");
        }
        //return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
        return ceil($date[2]) . "/" . str_pad(ceil($date[1]), 2, 0, STR_PAD_LEFT) . "/" . str_pad(ceil($date[0]), 2, 0, STR_PAD_LEFT);
    }
}

/* * **************************************************************
 * Se encarga de separar la hora del formato timestamp
 * @access private
 * @return string
 * @param date hora
 */

function HoraStampDiv($hora) {
    $hor = strtok($hora, " ");
    for ($l = 0; $l < 4; $l++) {
        $time[$l] = $hor;
        $hor = strtok(":");
    }
    $x = explode('.', $time[3]);
    return $time[1] . ":" . $time[2] . ":" . $x[0];
}

//********************************************************************
//********************************************************************
//FIN DIVISION DE CUENTA
//********************************************************************
//********************************************************************
function ConexionBaseDatos($sql) {
    list($dbconn) = GetDBConn();
    //$dbconn->debug=true;
    $rst = $dbconn->Execute($sql);

    if ($dbconn->ErrorNo() != 0) {
        $frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
        echo "<b class=\"label\">" . $this->frmError['MensajeError'] . "</b>";
        return false;
    }
    return $rst;
}

?>