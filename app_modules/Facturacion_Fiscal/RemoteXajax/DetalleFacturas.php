<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: DetalleFacturas.php,v 1.1 2011/02/23 21:54:04 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Hugo F  Manrique
 */
/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.1 $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Hugo F  Manrique
 */

/**
 * Funcion donde se muestra la informacion de la glosa
 *
 * @param string $prefijo Identificador del prefijo de la factura
 * @param integer $factura_fiscal Numero de la factura
 * @param string $empresa Identificador de la empresa
 *
 * @return object
 */
function InformacionGlosa($prefijo, $factura_fiscal, $empresa) {
    $objResponse = new xajaxResponse();
    $ctd = AutoCarga::factory('CarteraDetalle', 'classes', 'app', 'Facturacion_Fiscal');
    $glosas = $ctd->ObtenerGlosasFactura($prefijo, $factura_fiscal, $empresa);

    if (sizeof($glosas) > 1) {
        $html = ListaGlosas($glosas, $prefijo, $factura_fiscal, $empresa);
        $objResponse->assign("informacion_factura", "innerHTML", $html);
    }
    else
        $objResponse = InformacionGlosaDetalle($empresa, key($glosas), true);

    return $objResponse;
}

/**
 * Funcion donde se muestra la informacion de los recibos de caja de tesoreria
 *
 * @param string $prefijo Identificador del prefijo de la factura
 * @param integer $factura_fiscal Numero de la factura
 * @param string $empresa Identificador de la empresa
 *
 * @return object
 */
function InformacionRecibo($prefijo, $factura_fiscal, $empresa) {
    $objResponse = new xajaxResponse();
    $ctd = AutoCarga::factory('CarteraDetalle', 'classes', 'app', 'Facturacion_Fiscal');
    ;
    $datos = $ctd->ObtenerRecibos($prefijo, $factura_fiscal, $empresa);

    $html = ListaRecibos($datos, $prefijo, $factura_fiscal, $empresa, $sistema);
    $objResponse->assign("informacion_factura", "innerHTML", $html);


    return $objResponse;
}

/**
 * Funcion donde se muestra la informacion de las notas de la factura
 * credito y debito
 *
 * @param string $prefijo Identificador del prefijo de la factura
 * @param integer $factura_fiscal Numero de la factura
 * @param string $empresa Identificador de la empresa
 * @param string $sw_clase_factura Identificador de la clase de factura
 *
 * @return object
 */
function InformacionNota($prefijo, $factura_fiscal, $empresa, $sw_clase_factura) {
    $objResponse = new xajaxResponse();
    $ctd = AutoCarga::factory('CarteraDetalle', 'classes', 'app', 'Facturacion_Fiscal');
    $datos = array();
    if ($sw_clase_factura == '1')
        $datos = $ctd->ObtenerNotas($prefijo, $factura_fiscal, $empresa);
    else
        $datos = $ctd->ObtenerNotasContado($prefijo, $factura_fiscal, $empresa);

    $html = ListaNotas($datos, $prefijo, $factura_fiscal, $empresa, $sistema);
    $objResponse->assign("informacion_factura", "innerHTML", $html);


    return $objResponse;
}

/**
 * Funcion donde se muestra la informacion del detalle de la glosa
 *
 * @param string $empresa Identificador de la empresa
 * @param integer $glosaid Identificador de la glosa
 * @param boolean $flag Indica si es detalle de la unica glosa de la factura
 *
 * @return object
 */
function InformacionGlosaDetalle($empresa, $glosaid, $flag = false) {
    $objResponse = new xajaxResponse();
    $ctd = AutoCarga::factory('CarteraDetalle', 'classes', 'app', 'Facturacion_Fiscal');
    $glosa = $ctd->ObtenerInformacionGlosa($empresa, $glosaid);

    $estilo = "class=\"modulo_table_list_title\" style=\"text-indent:4pt;text-align:left\"";
    $html .= "<br>\n";
    $html .= "<center>\n";
    $html .= "	<fieldset class=\"fieldset\" style=\"width:96%\">\n";
    $html .= "		<legend class=\"normal_10AN\">INFORMACION DE LA GLOSA Nº " . $glosaid . "</legend>\n";
    $html .= "		<table align=\"center\" cellpading=\"0\" width=\"80%\" border=\"0\" class=\"modulo_table_list\">\n";
    $html .= "			<tr $estilo>\n";
    $html .= "				<td width=\"25%\"><b>FACTURA</b></td>\n";
    $html .= "				<td width=\"25%\" class=\"modulo_list_claro\">" . $glosa['prefijo'] . " " . $glosa['factura_fiscal'] . "</td>\n";
    $html .= "				<td width=\"25%\"><b>TOTAL FACTURA</b></td>\n";
    $html .= "				<td width=\"25%\" class=\"modulo_list_claro\">\n";
    $html .= "						$" . formatoValor($glosa['total_factura']) . "\n";
    $html .= "				</td>\n";
    $html .= "			</tr>\n";
    $html .= "			<tr $estilo>\n";
    $html .= "				<td >F. REGISTRO</td>\n";
    $html .= "				<td class=\"modulo_list_claro\">" . $glosa['fecha_glosa'] . "</td>\n";
    $html .= "				<td>ESTADO ACTUAL</b></td>\n";
    $html .= "				<td class=\"modulo_list_claro\">\n";
    $html .= "					<label class=\"normal_10AN\">" . $glosa['estado_glosa'] . "</label>\n";
    $html .= "				</td>\n";
    $html .= "			</tr>\n";
    $html .= "			<tr $estilo>\n";
    $html .= "				<td >V. GLOSA</td>\n";
    $html .= "				<td class=\"modulo_list_claro\" align=\"right\">\n";
    $html .= "					$" . formatoValor($glosa['valor_glosa']) . "\n";
    $html .= "				</td>\n";
    $html .= "				<td >V. ACEPTADO</td>\n";
    $html .= "				<td class=\"modulo_list_claro\" align=\"right\">\n";
    $html .= "					$" . formatoValor($glosa['valor_aceptado']) . "\n";
    $html .= "				</td>\n";
    $html .= "			</tr>\n";
    $html .= "			<tr $estilo>\n";
    $html .= "				<td >V. NO ACEPTADO</td>\n";
    $html .= "				<td class=\"modulo_list_claro\" align=\"right\">\n";
    $html .= "					$" . formatoValor($glosa['valor_no_aceptado']) . "\n";
    $html .= "				</td>\n";
    $html .= "				<td><b>V. PENDIENTE</td>\n";
    $html .= "				<td class=\"modulo_list_claro\" align=\"right\">\n";
    $html .= "					$" . formatoValor($glosa['valor_pendiente']) . "\n";
    $html .= "				</td>\n";
    $html .= "			</tr>\n";
    $html .= "			<tr $estilo>\n";
    $html .= "				<td ><b>RESPONSABLE</b></td>\n";
    $html .= "				<td colspan=\"3\" class=\"modulo_list_claro\" >\n";
    $html .= "					" . $glosa['nombre'] . "\n";
    $html .= "				</td>\n";
    $html .= "			</tr>\n";

    if ($glosa['clasificacion'] != "") {
        $html .= "			<tr $estilo>\n";
        $html .= "				<td >CLASIFICACIÓN</td>\n";
        $html .= "				<td colspan=\"3\" class=\"modulo_list_claro\">\n";
        $html .= "					" . $glosa['clasificacion'] . "\n";
        $html .= "				</td>\n";
        $html .= "			</tr>\n";
    }
    if ($glosa['auditor'] != "") {
        $html .= "			<tr $estilo>\n";
        $html .= "				<td ><b>AUDITOR</b></td>\n";
        $html .= "				<td colspan=\"3\" class=\"modulo_list_claro\">\n";
        $html .= "					" . $glosa['auditor'] . "\n";
        $html .= "				</td>\n";
        $html .= "			</tr>\n";
    }

    if ($glosa['documento_interno_cliente_id'] != "") {
        $html .= "			<tr $estilo>\n";
        $html .= "				<td ><b>DOCUMENTO INTERNO DEL CLIENTE Nº</b></td>\n";
        $html .= "				<td colspan=\"3\" class=\"modulo_list_claro\">\n";
        $html .= "					" . $glosa['documento_interno_cliente_id'] . "\n";
        $html .= "				</td>\n";
        $html .= "			</tr>\n";
    }

    if ($glosa['motivo_glosa_descripcion'] != "") {
        $html .= "			<tr class=\"modulo_table_list_title\">\n";
        $html .= "				<td colspan=\"4\">MOTIVO DE LA GLOSA</td>\n";
        $html .= "			</tr>\n";
        $html .= "			<tr>\n";
        $html .= "				<td class=\"modulo_list_claro\" colspan= \"4\">";
        $html .= "					<b>" . $glosa['motivo_glosa_descripcion'] . "</b>\n";
        $html .= "				</td>\n";
        $html .= "			</tr>\n";
    }
    if ($glosa['observacion'] != "") {
        $html .= "			<tr class=\"modulo_table_list_title\">\n";
        $html .= "				<td colspan= \"4\"><b>OBSERVACIÓN</b></td>\n";
        $html .= "			</tr>\n";
        $html .= "			<tr>\n";
        $html .= "				<td class=\"modulo_list_claro\" colspan= \"4\">";
        $html .= "					<b>" . $glosa['observacion'] . "</b>\n";
        $html .= "				</td>\n";
        $html .= "			</tr>\n";
    }
    $html .= "		</table><br>\n";

    if ($glosa['sw_glosa_total_factura'] == '0') {
        $cargos = $ctd->ObtenerInformacionGlosaCargos($glosaid);
        $html .= "		<table align=\"center\" cellpading=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
        foreach ($cargos as $key => $cuentas) {
            if ($key == 'DC' || $key == 'DI') {
                $html .= "			<tr class=\"formulacion_table_list\">\n";
                $html .= "				<td colspan=\"10\" align=\"center\" >\n";
                ($key == 'DC') ? $html .= "CARGOS" : $html .= "INSUMOS Y MEDICAMENTOS";

                $html .= "				</td>\n";
                $html .= "			</tr>\n";
            }

            foreach ($cuentas as $keyI => $detalle) {
                $Motivo = "";
                foreach ($detalle as $keyX => $item) {
                    switch ($key) {
                        case 'DT':
                            $html .= "			<tr class=\"modulo_table_list_title\">\n";
                            $html .= "				<td width=\"10%\" ><b>Nº CUENTA: </b></td>\n";
                            $html .= "				<td width=\"17%\" class=\"modulo_list_claro\" align=\"center\">" . $item['numerodecuenta'] . "</td>\n";
                            $html .= "				<td width=\"10%\" ><b>V. GLOSA</b></td>\n";
                            $html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">" . formatoValor($item['valor_glosa']) . "&nbsp;&nbsp;</td>\n";
                            $html .= "				<td width=\"11%\" ><b>V. ACEPTADO</b></td>\n";
                            $html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">" . formatoValor($item['valor_aceptado']) . "&nbsp;&nbsp;</td>\n";
                            $html .= "				<td width=\"12%\" ><b>V. NO ACEPTADO</b></td>\n";
                            $html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">" . formatoValor($item['valor_no_aceptado']) . "&nbsp;&nbsp;</td>\n";
                            $html .= "			</tr>\n";
                            $html .= "			<tr>\n";
                            $html .= "				<td class=\"modulo_table_list_title\" colspan=\"2\"><b $estilo>MOTIVO DE GLOSA</b></td>\n";
                            $html .= "				<td class=\"modulo_list_claro\" colspan=\"6\" width=\"80%\">" . $item['motivo_glosa_descripcion'] . "</td>\n";
                            $html .= "			</tr>\n";
                            break;

                        case 'DA':
                            $html .= "			<tr>\n";
                            $html .= "				<td $estilo width=\"10%\">Nº CUENTA:</td>\n";
                            $html .= "				<td class=\"modulo_list_claro\" width=\"17%\" align=\"center\">" . $item['numerodecuenta'] . "</td>\n";
                            if ($item['valor_glosa'] > 0) {
                                $html .= "				<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\"><b>V. GLOSA</b></td>\n";
                                $html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">" . formatoValor($item['valor_glosa']) . "&nbsp;&nbsp;</td>\n";
                                $html .= "				<td width=\"11%\" class=\"modulo_table_list_title\" align=\"center\"><b>V. ACEPTADO</b></td>\n";
                                $html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">" . formatoValor($item['valor_aceptado']) . "&nbsp;&nbsp;</td>\n";
                                $html .= "				<td width=\"12%\" class=\"modulo_table_list_title\" align=\"center\"><b>V. NO ACEPTADO</b></td>\n";
                                $html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">" . formatoValor($item['valor_no_aceptado']) . "&nbsp;&nbsp;</td>\n";
                            }
                            else
                                $html .= "				<td class=\"modulo_list_claro\" colspan=\"6\" width=\"50%\"></td>\n";

                            $html .= "			</tr>\n";
                            if ($item['motivo_glosa_descripcion'] != "") {
                                $html .= "			<tr>\n";
                                $html .= "				<td class=\"modulo_table_list_title\" colspan=\"2\" align=\"center\"><b>MOTIVO DE GLOSA:</b></td>\n";
                                $html .= "				<td class=\"modulo_list_claro\" colspan=\"6\" width=\"80%\">" . $item['motivo_glosa_descripcion'] . "</td>\n";
                                $html .= "			</tr>\n";
                            }
                            break;
                        case 'DC':

                            if ($Motivo != $item['motivo_glosa_descripcion']) {
                                $html .= "			<tr>\n";
                                $html .= "				<td  $estilo colspan=\"2\" align=\"center\">MOTIVO DE GLOSA</td>\n";
                                $html .= "				<td  class=\"modulo_list_claro\" colspan=\"6\" width=\"80%\">" . $item['motivo_glosa_descripcion'] . "</td>\n";
                                $html .= "			</tr>\n";
                                $Motivo = $item['motivo_glosa_descripcion'];
                            }

                            $html .= "			<tr>\n";
                            $html .= "				<td width=\"10%\" $estilo >CARGO</td>\n";
                            $html .= "				<td width=\"17%\" class=\"modulo_list_claro\" align=\"center\"   >" . $item['cargo'] . "</td>\n";
                            $html .= "				<td width=\"10%\" class=\"modulo_table_list_title\" >V. GLOSA</td>\n";
                            $html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">" . formatoValor($item['valor_glosa']) . "&nbsp;&nbsp;</td>\n";
                            $html .= "				<td width=\"11%\" class=\"modulo_table_list_title\" ><b>V. ACEPTADO</b></td>\n";
                            $html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">" . formatoValor($item['valor_aceptado']) . "&nbsp;&nbsp;</td>\n";
                            $html .= "				<td width=\"12%\" class=\"modulo_table_list_title\" ><b>V. NO ACEPT.</b></td>\n";
                            $html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">" . formatoValor($item['valor_no_aceptado']) . "&nbsp;&nbsp;</td>\n";
                            $html .= "			</tr>\n";
                            break;
                        case 'DI':
                            if ($Motivo != $item['motivo_glosa_descripcion']) {
                                $Motivo = $item['motivo_glosa_descripcion'];
                                $html .= "			<tr>\n";
                                $html .= "				<td $estilo colspan=\"2\" align=\"center\">MOTIVO DE GLOSA</td>\n";
                                $html .= "				<td class=\"modulo_list_claro\" colspan=\"6\" width=\"80%\">" . $item['motivo_glosa_descripcion'] . "</td>\n";
                                $html .= "			</tr>\n";
                            }

                            $html .= "			<tr>\n";
                            $html .= "				<td $estilo width=\"10%\" align=\"center\">PRODUCTO</td>\n";
                            $html .= "				<td width=\"17%\" class=\"modulo_list_claro\" align=\"center\">" . $item['cargo'] . "</td>\n";
                            $html .= "				<td width=\"10%\" class=\"modulo_table_list_title\" align=\"center\">V. GLOSA</td>\n";
                            $html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">" . formatoValor($item['valor_glosa']) . "&nbsp;&nbsp;</td>\n";
                            $html .= "				<td width=\"11%\" class=\"modulo_table_list_title\" align=\"center\"><b>V. ACEPTADO</b></td>\n";
                            $html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">" . formatoValor($item['valor_aceptado']) . "&nbsp;&nbsp;</td>\n";
                            $html .= "				<td width=\"12%\" class=\"modulo_table_list_title\" align=\"center\"><b>V. NO ACEPT.</b></td>\n";
                            $html .= "				<td width=\"10%\" class=\"modulo_list_claro\" align=\"right\">" . formatoValor($item['valor_no_aceptado']) . "&nbsp;&nbsp;</td>\n";
                            $html .= "			</tr>\n";
                            break;
                    }
                }
            }
        }
        $html .= "		</table><br>\n";
    }
    $html .= "	</fieldset><br>\n";
    if ($flag === false)
        $html .= "	<a href=\"javascript:MostrarDetalleGlosa('" . $glosa['prefijo'] . "','" . $glosa['factura_fiscal'] . "','" . $empresa . "','" . $sistema . "')\" class=\"label_error\">VOLVER</a>&nbsp;&nbsp;&nbsp;\n";

    $html .= "</center>\n";
    $html .= "<br>\n";


    $objResponse->assign("informacion_factura", "innerHTML", $html);


    return $objResponse;
}

/**
 * Funcion donde se muestra la informacion de la glosa
 *
 * @param array $glosas Arreglo de datos con la informacion de las glosas
 * @param string $prefijo Identificador del prefijo de la factura
 * @param integer $factura_fiscal Numero de la factura
 * @param string $empresa Identificador de la empresa
 *
 * @return object
 */
function ListaGlosas($glosas, $prefijo, $factura_fiscal, $empresa) {
    $html .= "<br>\n";
    $html .= "<center>\n";
    $html .= "	<fieldset class=\"fieldset\" style=\"width:96%\">\n";
    $html .= "		<legend class=\"normal_10AN\">LISTADO DE CUENTAS DE LA FACTURA Nº " . $prefijo . " " . $factura_fiscal . "</legend>\n";
    $html .= "		<table width=\"98%\" align=\"center\" class=\"modulo_table_list\" >\n";
    $html .= "			<tr class=\"modulo_table_list_title\" height=\"18\">\n";
    $html .= "				<td width=\"1%\"></td>\n";
    $html .= "				<td width=\"10%\">Nº GLOSA</td>\n";
    $html .= "				<td width=\"14%\">FECHA GLOSA</td>\n";
    $html .= "				<td width=\"14%\">V. GLOSA</td>\n";
    $html .= "				<td width=\"15%\">V. ACEPTADO</td>\n";
    $html .= "				<td width=\"17%\">V. NO ACEPTADO</td>\n";
    $html .= "				<td width=\"20%\">ESTADO</td>\n";
    $html .= "			</tr>\n";

    foreach ($glosas as $key => $glosa) {
        $html .= "			<tr class=\"modulo_list_claro\">\n";
        $html .= "				<td align=\"center\" >\n";
        $html .= "					<a href=\"javascript:MostrarGlosa('" . $key . "','" . $empresa . "')\" title=\"INFORMACION DE LA GLOSA\">\n";
        $html .= "						<img src=\"" . GetThemePath() . "/images/pconsultar.png\" border=\"0\">\n";
        $html .= "					</a>\n";
        $html .= "				</td>\n";
        $html .= "				<td align=\"right\" ><b>" . $glosa['glosa_id'] . "</b></td>\n";
        $html .= "				<td align=\"center\">" . $glosa['fecha'] . "</td>\n";
        $html .= "				<td align=\"right\" >$" . formatoValor($glosa['valor_glosa']) . "</td>\n";
        $html .= "				<td align=\"right\" >$" . formatoValor($glosa['valor_aceptado']) . "</td>\n";
        $html .= "				<td align=\"right\" >$" . formatoValor($glosa['valor_no_aceptado']) . "</td>\n";
        $html .= "				<td align=\"center\" class=\"normal_10AN\">\n";
        $html .= "					" . $glosa['estado'] . "\n";
        $html .= "				</td>\n";
        $html .= "			</tr>\n";
    }
    $html .= "		</table><br>\n";
    $html .= "	</fieldset><br>\n";
    $html .= "</center>\n";
    $html .= "<br>\n";

    return $html;
}

/**
 * Funcion donde se muestra la informacion de los recibos de caja de
 * tesoreria
 *
 * @param array $datos Arreglo de datos con la informacion de los recibos
 * @param string $prefijo Identificador del prefijo de la factura
 * @param integer $factura_fiscal Numero de la factura
 *
 * @return object
 */
function ListaRecibos($datos, $prefijo, $factura_fiscal) {
    $html .= "<br>\n";
    $html .= "<center>\n";
    $html .= "	<fieldset class=\"fieldset\" style=\"width:96%\">\n";
    $html .= "		<legend class=\"normal_10AN\">LISTADO DE RECIBOS DE LA FACTURA Nº " . $prefijo . " " . $factura_fiscal . "</legend>\n";

    $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "	<tr class=\"formulacion_table_list\">\n";
    $html .= "		<td width=\"15%\" ><b>Nº DOC</b></td>\n";
    $html .= "		<td width=\"15%\" ><b>FECHA</b></td>\n";
    $html .= "		<td width=\"20%\" ><b>TIPO PAGO</b></td>\n";
    $html .= "		<td width=\"20%\" ><b>V. ABONADO</b></td>\n";
    $html .= "		<td width=\"20%\" ><b>V. DOCUMENTO</b></td>\n";
    $html .= "	</tr>\n";

    foreach ($datos as $key => $recibo) {
        $html .= "			<tr class=\"modulo_list_claro\">\n";
        $html .= "				<td class=\"label\">" . $recibo['prefijo'] . " " . $recibo['recibo_caja'] . "</td>\n";
        $html .= "				<td class=\"label\" align=\"center\">" . $recibo['fecha_registro'] . "</td>\n";
        $html .= "				<td class=\"normal_10AN\">" . $recibo['forma_pago'] . "</td>\n";
        $html .= "				<td class=\"label\" align=\"right\">" . formatoValor($recibo['abono']) . "</td>\n";
        $html .= "				<td class=\"label\" align=\"right\">" . formatoValor($recibo['valor'] + $recibo['total_abono']) . "</td>\n";
        $html .= "			</tr>\n";
    }
    $html .= "		</table>\n";
    $html .= "	</fieldset><br>\n";
    $html .= "</center>\n";
    $html .= "<br>\n";

    return $html;
}

/**
 * Funcion donde se muestra la informacion de las notas credito o debito
 *
 * @param array $datos Arreglo de datos con la informacion de las notas
 * @param string $prefijo Identificador del prefijo de la factura
 * @param integer $factura_fiscal Numero de la factura
 *
 * @return object
 */
function ListaNotas($datos, $prefijo, $factura_fiscal) {
    $html .= "<br>\n";
    $html .= "<center>\n";
    $html .= "	<fieldset class=\"fieldset\" style=\"width:96%\">\n";
    $html .= "		<legend class=\"normal_10AN\">LISTADO DE NOTAs DE LA FACTURA Nº " . $prefijo . " " . $factura_fiscal . "</legend>\n";

    $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "	<tr class=\"formulacion_table_list\">\n";
    $html .= "		<td width=\"10%\" ><b>Nº DOC</b></td>\n";
    $html .= "		<td width=\"10%\" ><b>FECHA</b></td>\n";
    $html .= "		<td width=\"10%\" ><b>TIPO NOTA</b></td>\n";
    $html .= "		<td width=\"%\" ><b>CONCEPTO</b></td>\n";
    $html .= "		<td width=\"20%\" ><b>V. NOTA</b></td>\n";
    $html .= "	</tr>\n";

    foreach ($datos as $key => $notas) {
        foreach ($notas as $keyI => $recibo) {
            $html .= "			<tr class=\"modulo_list_claro\">\n";
            $html .= "				<td class=\"label\">" . $recibo['prefijo'] . " " . $recibo['numero'] . "</td>\n";
            $html .= "				<td class=\"label\" align=\"center\">" . $recibo['fecha'] . "</td>\n";
            $html .= "				<td class=\"normal_10AN\">" . $recibo['tipo'] . "</td>\n";
            $html .= "				<td class=\"normal_10AN\">" . $recibo['descripcion'] . "</td>\n";
            $html .= "				<td class=\"label\" align=\"right\">" . formatoValor($recibo['valor']) . "</td>\n";
            $html .= "			</tr>\n";
        }
    }
    $html .= "		</table>\n";
    $html .= "	</fieldset><br>\n";
    $html .= "</center>\n";
    $html .= "<br>\n";

    return $html;
}

//JONIER MURILLO HURTADO
function DatosPacienteFP($Tipo, $paciente_id){
    
    IncludeClass('Pacientes', '', 'app', 'Facturacion_Fiscal');
    $gld = new Pacientes();
    $objResponse = new xajaxResponse();

    $plan_actual = -1;
    $pacie = $gld->ObtenerEstadoEPSAfiliados($Tipo, $paciente_id);
    $estadopaciente = "NN";
    $tipafiliado = "";
    $tipafiliado = "";
    $rangoa = "";
    
    $semanacot = 0;
    if (count($pacie) > 0){
        $estadopaciente = $pacie[0]['estado_afiliado_id'];
        $planac = $gld->ObtenerEstadoPlan($pacie[0]['plan_atencion']);
        $plan_actual  = $planac[0]['plan_id'];
        $sw_afiliadosa = $planac[0]['sw_afiliados'];
        $nombreplan = $planac[0]['plan_descripcion'];
        $tipafiliado = $pacie[0]['descripcion_eps_tipo_afiliado'];
        $rangoa = $pacie[0]['rango'];
        $semanacot = $pacie[0]['semanas_cotizadas'];    
        $tipo_afiliado_id = $pacie[0]['tipo_afiliado_id'];   
        $rango_pac = $pacie[0]['rango'];   
    }else{
        $pacie = $gld->PacientesOrdenesServicios($Tipo, $paciente_id);
        $plan_actual  = $pacie[0]['plan_id'];
        $semanacot = $pacie[0]['semanas_cotizadas'];  
        $tipo_afiliado_id = $pacie[0]['tipo_afiliado_id'];
        $rango_pac = $pacie[0]['rango'];  
    }   

    $tipo_afiliado = $gld->Tipo_AfiliadoS($plan_actual);
    
    $combo  = " <select name=\"tip_afiliado\" id=\"tip_afiliado\" class=\"select\" onchange = \"tip_afiliado_DA.value = this.value;\">";
    $combo .= "     <option value=\"-1\">---Seleccione---</option>";
    for ($i = 0; $i < sizeof($tipo_afiliado); $i++) {
        if ($tipo_afiliado[$i][tipo_afiliado_id] == $tipo_afiliado_id){
            $combo .="  <option value=\"" . $tipo_afiliado[$i][tipo_afiliado_id] . "\" selected>" . $tipo_afiliado[$i][tipo_afiliado_nombre] . "</option>";
            $objResponse->assign("tip_afiliado_DA", "value", $tipo_afiliado_id);
        }else{
            $combo .="  <option value=\"" . $tipo_afiliado[$i][tipo_afiliado_id] . "\">" . $tipo_afiliado[$i][tipo_afiliado_nombre] . "</option>";
        }
    }
    $combo .= " </select>";        
    
    $niveles = $gld->NivelesS($plan_actual);
    
    $comniv .="                  <select name=\"Nivel\" class=\"select\" onchange = 'rangoDA.value=this.value'>";
    $comniv .="                       <option value=\"-1\">---Seleccione---</option>";
    for ($i = 0; $i < sizeof($niveles); $i++) {
        if ($niveles[$i][rango] == $rango_pac) {
            $comniv .="               <option value=\"" . $niveles[$i][rango] . "\" selected>" . $niveles[$i][rango] . "</option>";
            $objResponse->assign("rangoDA", "value", $rango_pac);
        } else {
            $comniv .="               <option value=\"" . $niveles[$i][rango] . "\">" . $niveles[$i][rango] . "</option>";
        }
    }
    $comniv .="                  </select>";

    
    $objResponse->assign("estado_paciente", "value", $estadopaciente);
    $objResponse->assign("plan_id_pac_actual", "value", $plan_actual);
    $objResponse->assign("plan_id_est_actual", "value", $sw_afiliadosa);
    $objResponse->assign("divma", "innerHTML", $nombreplan);
    $objResponse->assign("comtip_afiliado", "innerHTML", $combo);   
    $objResponse->assign("div_NivelDA", "innerHTML", $comniv);   
    
    $objResponse->assign("Responsable", "value", $plan_actual);
    $objResponse->assign("tip_afiliado", "value", $tipafiliado);
    $objResponse->assign("semanacot", "value", $semanacot);
    if($plan_actual <> -1){
        $estado = $gld->ObtenerEstadoPlan($plan_actual);
        $estado_sel = $estado[0]['sw_afiliados'];
        $objResponse->assign("plan_id_est_actual_sel", "value", $plan_actual);
        $objResponse->assign("sw_estado_plan", "value", $estado[0]['sw_afiliados']);
    }else{
        $objResponse->assign("plan_id_est_actual_sel", "value", 0);
        $objResponse->assign("sw_estado_plan", "value", 0);
    }
  
//    $objResponse->alert($Tipo." - ".$paciente_id);
    return $objResponse;
}
function DatosPlan($plan_id){
    IncludeClass('Pacientes', '', 'app', 'Facturacion_Fiscal');
    $gld = new Pacientes();

    $objResponse = new xajaxResponse();
    
    $tipo_afiliado = $gld->Tipo_AfiliadoS($plan_id);
    $combo  = " <select name=\"tip_afiliado\" id=\"tip_afiliado\" class=\"select\" onchange = \"tip_afiliado_DA.value = this.value;\">";
    $combo .= "         <option value=\"-1\">---Seleccione---</option>";
    for ($i = 0; $i < sizeof($tipo_afiliado); $i++) {
            $combo .="  <option value=\"" . $tipo_afiliado[$i][tipo_afiliado_id] . "\">" . $tipo_afiliado[$i][tipo_afiliado_nombre] . "</option>";
    }
    $combo .= " </select>";        

    
    $niveles = $gld->NivelesS($plan_id);
    
    $comniv .="                  <select name=\"Nivel\" class=\"select\" onchange = 'rangoDA.value=this.value'>";
    $comniv .="                       <option value=\"-1\">---Seleccione---</option>";
    for ($i = 0; $i < sizeof($niveles); $i++) {
            $comniv .="               <option value=\"" . $niveles[$i][rango] . "\">" . $niveles[$i][rango] . "</option>";
    }
    $comniv .="                  </select>";

    
    $estado = $gld->ObtenerEstadoPlan($plan_id);
    $estado_sel = $estado[0]['sw_afiliados'];
//    $objResponse->alert($plan_id);
    $objResponse->assign("plan_id_est_actual_sel", "value", $plan_id);
    $objResponse->assign("sw_estado_plan", "value", $estado_sel);
    $objResponse->assign("comtip_afiliado", "innerHTML", $combo);    
    $objResponse->assign("div_NivelDA", "innerHTML", $comniv);   
    
    return $objResponse;
}

function DatosPacienteLF($Tipo, $paciente_id){
    IncludeClass('Pacientes', '', 'app', 'Facturacion_Fiscal');
    $gld = new Pacientes();

    $objResponse = new xajaxResponse();
    $plan_actual = -1;
    $pacie = $gld->ObtenerEstadoEPSAfiliados($Tipo, $paciente_id);
    $estadopaciente = "NN";
    $tipafiliado = "";
    $tipafiliado = "";
    $rangoa = "";
    $semanacot = 0;
    if (count($pacie) > 0){
        $estadopaciente = $pacie[0]['estado_afiliado_id'];
        $planac = $gld->ObtenerEstadoPlan($pacie[0]['plan_atencion']);
        $plan_actual  = $planac[0]['plan_id'];
        $sw_afiliadosa = $planac[0]['sw_afiliados'];
        $nombreplan = $planac[0]['plan_descripcion'];
        $tipafiliado = $pacie[0]['descripcion_eps_tipo_afiliado'];
        $rangoa = $pacie[0]['rango'];
        $semanacot = $pacie[0]['semanas_cotizadas'];    
        $tipo_afiliado_id = $pacie[0]['tipo_afiliado_id'];   
        $rango_pac = $pacie[0]['rango'];   
    }else{
        $pacie = $gld->PacientesOrdenesServicios($Tipo, $paciente_id);
        $plan_actual  = $pacie[0]['plan_id'];
        $semanacot = $pacie[0]['semanas_cotizadas'];  
        $tipo_afiliado_id = $pacie[0]['tipo_afiliado_id'];
        $rango_pac = $pacie[0]['rango'];  
//        $objResponse->alert($plan_actual." - ".$semanacot." - ".$tipo_afiliado_id." - ".$rango_pac);
    }   

    
    $objResponse->assign("estado_paciente", "value", $estadopaciente);
    $objResponse->assign("plan_id_pac_actual", "value", $plan_actual);
    $objResponse->assign("plan_id_est_actual", "value", $sw_afiliadosa);
    
    
    $objResponse->assign("Responsable", "value", $plan_actual);
    $objResponse->assign("tip_afiliado", "value", $tipafiliado);
    $objResponse->assign("semanacot", "value", $semanacot);
    if($plan_actual <> -1){
        $estado = $gld->ObtenerEstadoPlan($plan_actual);
        $estado_sel = $estado[0]['sw_afiliados'];
        $objResponse->assign("plan_id_est_actual_sel", "value", $plan_actual);
        $objResponse->assign("sw_estado_plan", "value", $estado[0]['sw_afiliados']);
//        $objResponse->assign("Buscar", "disabled", false);
    }else{
        $objResponse->assign("plan_id_est_actual_sel", "value", 0);
        $objResponse->assign("sw_estado_plan", "value", 0);
//        $objResponse->assign("Buscar", "disabled", false);
    }
    
//    $objResponse->alert("HOLA MUNDO");
    return $objResponse;
}

?>