<?php

/**
 * $Id: reporte_detalle_auditoria.report.php,v 1.1 2010/04/08 20:36:35 hugo Exp $ 
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 * 
 */
IncludeClass('ConexionBD');
IncludeClass('ClaseUtil');
IncludeClass("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

class OrdenCompra_report {

    //VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
    var $datos;
    //PARAMETROS PARA LA CONFIGURACION DEL REPORTE
    //NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
    var $title = '';
    var $author = '';
    var $sizepage = 'leter';
    var $Orientation = '';
    var $grayScale = false;
    var $headers = array();
    var $footers = array();

    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    function OrdenCompra_report($datos = array()) {
        $this->datos = $datos;
        return true;
    }

    /**
     *
     */
    function GetMembrete() {

        $nc = new Compras_Orden_ComprasSQL();
        $parametro['orden'] = $this->datos['orden_pedido_id'];
        $parametro['tipo_id_tercero'] = '-1';
        $OrdenCompra = $nc->ConsultarOrdenComprasGeneradas($parametro);
        $UnidadNegocio = $nc->UnidadesNegocio($this->datos['codigo_unidad_negocio']);
        $est = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:20pt\"";
        $html = "	<table width=\"70%\" align=\"center\" $est rules=\"all\">\n";

        if ($this->datos['codigo_unidad_negocio'] != "") {
            $html .= "		<tr class=\"label\">\n";
            $html .= "			<td align=\"center\">" . $UnidadNegocio[0]['descripcion'] . " - ORDENES DE COMPRA</td>\n";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"label\">\n";
            $html .= "			<td align=\"center\">-- --</td>\n";
            $html .= "		</tr>\n";
            $imagen = $UnidadNegocio[0]['imagen'];
        } else
        if ($OrdenCompra[0]['codigo_unidad_negocio'] != "") {
            $html .= "		<tr class=\"label\">\n";
            //$html .= "			<td align=\"center\">".$OrdenCompra[0]['descripcion']." - ORDENES DE COMPRA</td>\n";
            if ($OrdenCompra[0]['codigo_unidad_negocio'] == 13) {
                $html .= "			<td align=\"center\">NIT: 900592372-9 &nbsp;&nbsp;&nbsp;&nbsp; " . $OrdenCompra[0]['descripcion'] . " - ORDENES DE COMPRA &nbsp;&nbsp; <br>\n";
                $html .= "			Direcci?n: Calle 64G # 92-72 &nbsp;&nbsp;&nbsp;&nbsp; Tel?fono: 7422299 </td>\n";
            } else {
                $html .= "			<td align=\"center\">" . $OrdenCompra[0]['descripcion'] . " - ORDENES DE COMPRA</td>\n";
            }
            $html .= "		</tr>\n";
            $imagen = $OrdenCompra[0]['imagen'];
        } else {
            $html .= "		<tr class=\"label\">\n";
            $html .= "			<td align=\"center\">" . $OrdenCompra[0]['razon_social'] . " - ORDENES DE COMPRA</td>\n";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"label\">\n";
            $html .= "			<td align=\"center\">" . $OrdenCompra[0]['tipo_id_empresa'] . ": " . $OrdenCompra[0]['id_empresa'] . "-" . $OrdenCompra[0]['digito_verificacion'] . "</td>\n";
            $html .= "		</tr>\n";
            $imagen = 'logocliente.png';
        }



        $html .= "</table>";
        //$titulo .= "<b $est >REPORTE DE AUDITORIA SELECCIONADA<br>";
        $titulo .= $html;

        $Membrete = array('file' => false, 'datos_membrete' => array('titulo' => $titulo,
                'subtitulo' => ' ',
                'logo' => $imagen,
                'align' => 'left'));

        return $Membrete;
    }

    //FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
    function CrearReporte() {
        $nc = new Compras_Orden_ComprasSQL();
        $cl = new ClaseUtil();

        $parametro['orden'] = $this->datos['orden_pedido_id'];
        $parametro['tipo_id_tercero'] = '-1';
        $OrdenCompra = $nc->ConsultarOrdenComprasGeneradas($parametro);
        $OrdenCompra_Detalle = $nc->ConsultarDetalleDeOrdenCompra($this->datos['orden_pedido_id']);
        
        $contrato_proveedor = $nc->consultar_contrato_proveedor($OrdenCompra[0]['codigo_proveedor_id']);
        
        if ($OrdenCompra[0]['estado'] == '1')
            $mensaje = "<b><u><i> ACTIVO </i></u></b>";
        else
        if ($OrdenCompra[0]['estado'] == '0')
            $mensaje = "<b><u><i> RECIBIDO COMPLETAMENTE </i></u></b>";
        else
        if ($OrdenCompra[0]['estado'] == '2')
            $mensaje = "<b><u><i> DOCUMENTO ANULADO </i></u></b>";


        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
        // print_r($OrdenCompra_Detalle);

        if (!empty($OrdenCompra)) {
            $html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" >\n";
            $html .= "		<tr class=\"label\" align=\"center\">\n";
            $html .= "			<td class=\"label\" >ORDEN COMPRA : " . $OrdenCompra[0]['orden_pedido_id'] . "</td>\n";
            $html .= "			<td class=\"label\" >FECHA : " . $OrdenCompra[0]['fecha_registro'] . " </td>\n";
            $html .= "			<td class=\"label\" >USUARIO : " . $OrdenCompra[0]['nombre'] . "</td>\n";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"label\" align=\"center\">\n";
            $html .= "			<td class=\"label\" >PROVEEDOR : " . $OrdenCompra[0]['tipo_id_tercero'] . " - " . $OrdenCompra[0]['tercero_id'] . ":</td>\n";
            $html .= "			<td class=\"label\" >" . $OrdenCompra[0]['nombre_tercero'] . " </td>\n";
            $html .= "			<td class=\"label\" >DIRECCION: " . $OrdenCompra[0]['direccion'] . " TELEFONO: " . $OrdenCompra[0]['telefono'] . "</td>\n";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"label\" align=\"center\">\n";
            $html .= "			<td class=\"label\" >ESTADO DE LA ORDEN DE COMPRA : " . $mensaje . " </td>\n";
            $html .= "			<td class=\"label\" ></td>\n";
            $html .= "			<td class=\"label\" ></td>\n";
            $html .= "		</tr>\n";
            $html .= "	</table>";
            $html .= "  <br>";

            $html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" >\n";
            $html .= "		<tr class=\"label\" align=\"center\">\n";
            $html .= "			<td class=\"label\" >ENVIAR CERTIFICADOS DE CALIDAD FECHA DE VENCIMIENTO NO MENOR A 2 A?OS MARCAR USO INSTITUCIONAL PROHIBIDA SU VENTA</td>\n";
            $html .= "		</tr>\n";
            $html .= "	</table>";
            $html .= "  <br>";

            $html .= "	      <fieldset class=\"fieldset\" style=\"width:60%\">\n";
            $html .= "          <legend class=\"normal_10AN\">OBSERVACIONES</legend>\n";
            $html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" >\n";
            $html .= "		<tr class=\"label\" >\n";
            $html .= "			<td class=\"label\" >" . $OrdenCompra[0]['observacion'] . "</td>\n";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"label\" >\n";
            $html .= "			<td class=\"label\" >" . $contrato_proveedor['observaciones'] . "</td>\n";
            $html .= "		</tr>\n";
            $html .= "	</table>";
            $html .= "      </fieldset>";
            $html .= "  <br>";
            //style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"
            $html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;font-size:8.5px\" rules=\"all\">\n";
            $html .= "		<tr class=\"label\">\n";
            $html .= "			<td align=\"center\" colspan=\"7\">PRODUCTOS</td>\n";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"label\" align=\"center\">\n";
            $html .= "			<td >CODIGO</td><td >DESCRIPCION</td><td >OBSERVACION</td><td >CANTIDAD</td><td >%IVA</td><td >VALOR</td><td >VALOR IVA</td><td >VALOR TOTAL</td>\n";
            $html .= "		</tr>\n";

            foreach ($OrdenCompra_Detalle as $key => $valor) {
                
                $politicas_producto = $nc->consultar_politicas_productos_contrato($OrdenCompra[0]['empresa_id'],$OrdenCompra[0]['codigo_proveedor_id'], $valor['codigo_producto']);
                
                if (($valor['numero_unidades']) > 0) {
                    $iva = ($valor['porc_iva'] / 100);
                    $total_producto = ($valor['numero_unidades'] * $valor['valor']);
                    $iva_producto = $total_producto * $iva;

                    $iva_acumulado = $iva_acumulado + $iva_producto;
                    $subtotal = $subtotal + $total_producto;

                    $html .= "		<tr>\n";
                    $html .= "        <td>" . $valor['codigo_producto'] . "</td>";
                    $html .= "        <td>" . $valor['nombre'] . "</td>";
                    $html .= "        <td>" . $politicas_producto['politica'] . "</td>";
                    $html .= "        <td>" . FormatoValor($valor['numero_unidades']) . "</td>";
                    $html .= "        <td>%" . FormatoValor($valor['porc_iva'], 2) . "</td>";
                    $html .= "        <td>$" . FormatoValor($valor['valor'], 2) . "</td>";
                    $html .= "        <td>$" . FormatoValor($iva_producto, 2) . "</td>";
                    $html .= "        <td>$" . FormatoValor(($total_producto + $iva_producto), 2) . "</td>";
                    $html .= "		</tr>\n";
                }
                /* else{
                  $iva =0;
                  $total_producto =0;
                  $iva_producto = 0;
                  $iva_acumulado = 0;
                  $subtotal = 0;
                  } */
            }
            $html .= "		<tr>\n";
            $html .= "        <td colspan=\"7\">SUBTOTAL: <b>$" . FormatoValor($subtotal, 2) . "</b></td>";
            $html .= "		</tr>\n";
            $html .= "		<tr>\n";
            $html .= "        <td colspan=\"7\">IVA : <b>$" . FormatoValor($iva_acumulado, 2) . "</b></td>";
            $html .= "		</tr>\n";
            $html .= "		<tr>\n";
            $html .= "        <td colspan=\"7\">TOTAL : <b>$" . FormatoValor(($subtotal + $iva_acumulado), 2) . "</b></td>";
            $html .= "		</tr>\n";

            $html .= "	</table><br><br>\n";
        }
        $usuario = $nc->ObtenerInformacionUsuario(UserGetUID());
        $html .= "	<br><table border='0' width=\"100%\">\n";
        $html .= "		<tr>\n";
        $html .= "			<td align=\"justify\" width=\"50%\">\n";
        $html .= "				<font size='1' face='arial'>\n";
        $html .= "					Imprimi?:&nbsp;" . $usuario['nombre'] . "\n";
        $html .= "				</font>\n";
        $html .= "			</td>\n";
        $html .= "			<td align=\"right\" width=\"50%\">\n";
        $html .= "				<font size='1' face='arial'>\n";
        $html .= "					Fecha Impresi?n :&nbsp;&nbsp;" . date("d/m/Y - h:i a") . "\n";
        $html .= "				</font>\n";
        $html .= "			</td>\n";
        $html .= "		</tr>\n";
        $html .= "	</table>\n";
        return $html;
    }

}

?>