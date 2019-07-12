<?php

/* * ************************************************************************************
 * $Id: definirBodegas_E008.php,v 1.2 2010/08/31 22:04:25 hugo Exp $ 
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 * 
 * @author Jaime gomez
 * ************************************************************************************ */

$VISTA = "HTML";
$_ROOT = "../../../";
//include "../../../app_modules/InvTomaFisica/classes/TomaFisicaSQL.class.php";
include "../../../app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/E008/doc_Bodegas_E008.class.php";
include "../../../classes/ClaseHTML/ClaseHTML.class.php";

//
/* * ******************************
 * pop up para imprimir
 * ********************************* */
function RetornarImpresionDoc($direccion, $alt, $imagen, $empresa_id, $prefijo, $numero) {
    global $VISTA;
    $imagen1 = "<sub><img src=\"" . $imagen . "\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
    $salida1 = "<a title='" . $alt . "' href=javascript:Imprimir('$direccion','$empresa_id','$prefijo','$numero')>" . $imagen1 . "</a>";
    return $salida1;
}

/**
 * Funcion donde se realiza la creacion del documento
 * 
 * @param intger $bodegas_doc_id
 * @param intger $doc_tmp_id
 * @param string $tipo_doc_bodega_id
 * @param string $identificador Identificador del documento que se esta creando
 *
 * @return object
 */
function CrearDocumentoFinalx($bodegas_doc_id, $doc_tmp_id, $tipo_doc_bodega_id, $identificador) {

    /* $path = SessionGetVar("rutaImagenes");
      $consulta = new MovBodegasSQL();
      $e8 = new doc_bodegas_E008();
      $objResponse = new xajaxResponse();
      $pedido = array();
      $sirotulo = $e8->ObtenerSiEstaRotulo($doc_tmp_id);
      $sirotulo = true;

      //if ($identificador == "FM" && $pedido["farmacia_id"] == "01") {
      // Sincronizar con Cosmitet
      $cosmitet_ws = AutoCarga::factory("SincronizacionCosmitet", "SincronizacionCosmitet", "", "");

      $pedido_farmacia = $e8->consultarPedidoFarmacia(64989);

      // Ingresar Cabecera
      //$resultado_ws = $cosmitet_ws->ingresarDocumentoTemporal($productos['prefijo'], $productos['documento_id'], $productos['observacion']);
      $resultado_ws = $cosmitet_ws->ingresarDocumentoTemporal("BS", 418, "na");
      //$resultado_ws = $cosmitet_ws->DocumentoTemporal();


      if (count($resultado_ws) > 0) {

      if ($resultado_ws['estado']) {

      $documentoTemporalCosmitet = $resultado_ws['docTmpId'];


      //$pedido_farmacia = $e8->consultarPedidoFarmacia($pedido['solicitud_prod_a_bod_ppal_id']);
      // agregar items
      //$productos_seleccionados = $consulta->SacarDocumento(SessionGetVar("empresa_id"), $productos['prefijo'], $productos['numero']);
      $productos_seleccionados = $consulta->SacarDocumento('03', 'EFC', '82750');


      $mensaje_error = array();
      foreach ($productos_seleccionados['DETALLE'] as $producto) {
      $resultado_ws = $cosmitet_ws->ingresarDetalleDocumentoTemporal($documentoTemporalCosmitet, $producto);

      array_push($mensaje_error, " Producto {$producto['codigo_producto']} ". $resultado_ws['descripcion'] );

      }
      } else {
      // No se pudo crear Docuumento
      $objResponse->alert("No se Pudo Ingresar Documento en Cosmitet por que - " . $resultado_ws['descripcion']);
      }
      }
      // }

      echo "<pre>";
      print_r($mensaje_error);
      echo "</pre>";
      exit();
      EXIT(); */

    $path = SessionGetVar("rutaImagenes");
    $consulta = new MovBodegasSQL();
    $e8 = new doc_bodegas_E008();
    $objResponse = new xajaxResponse();
    $pedido = array();
    $sirotulo = $e8->ObtenerSiEstaRotulo($doc_tmp_id);
    $sirotulo = true;
    //$salida .= "<pre>".print_r($sirotulo)."</pre>";
    if ($sirotulo) {
        if ($identificador == "FM")
            $pedido = $consulta->FarmaciaPedidosTmp($doc_tmp_id);
        else if ($identificador == "CL")
            $pedido = $consulta->ClientesPedidosTmp($doc_tmp_id);

        $numero = ($pedido['solicitud_prod_a_bod_ppal_id']) ? $pedido['solicitud_prod_a_bod_ppal_id'] : $pedido['pedido_cliente_id'];

        /* $si_rotulocaja = $e8->ObtenerDatosRotuloCaja($tmp_doc_id,$numero);
          if(empty($si_rotulocaja))
          {
          $objResponse->alert("NO SE HA GUARDADO LA INFORMCIÓN DEL ROTULO");
          return $objResponse;
          } */
        $datosItem = $e8->SacarProductosTMP($doc_tmp_id, UserGetUID());
        $existencias = $e8->ObtnerCantidadesIngresadasTodos($doc_tmp_id);

        $pendientes = $e8->ObtenerPendientesFarmacia($pedido['solicitud_prod_a_bod_ppal_id'], $doc_tmp_id, SessionGetVar("empresa_id"), SessionGetVar("centro_utilidad"), SessionGetVar("bodega"));

        /* print_r($pendientes); */

        $solicitudes = array();

        /* if($identificador == "FM")
          {
          $solicitudes = $e8->ObtenerCantidadesSolicitadasFarmacia($numero);
          $justificaciones = $e8->ObtenerObservacion($doc_tmp_id);
          foreach($datosItem as $key => $valor)
          {
          if($existencias[$valor['codigo_producto']] && $solicitudes[$valor['codigo_producto']]['cantidad_solic'] > $existencias[$valor['codigo_producto']]['cantidad']
          && trim($justificaciones[$valor['codigo_producto']]['observacion_cambio']) == "")
          {
          $mensaje = "PARA EL PRODUCTO CON CODIGO ".$valor['codigo_producto']." SE HAN INGRESADO ".$existencias[$valor['codigo_producto']]['cantidad']." UNIDADES";
          $mensaje .= "\nCANTIDAD SOLIICITADA: ".$solicitudes[$valor['codigo_producto']]['cantidad_solic'];
          $mensaje .= "\nCANTIDAD EXISTENTE: ".$existencias[$valor['codigo_producto']]['existencia'];
          $mensaje .= "\nFAVOR JUSTIFICAR EL CAMBIO";
          $objResponse->alert($mensaje);
          return $objResponse;
          }
          }
          } */

        if ($identificador == "FM") {
            if (!empty($pendientes)) {
                $mensaje .= "PRODUCTOS PENDIENTES PARA DESPACHOS A LA FARMACIA:";
                $mensaje .= "\n";
                /* foreach($pendientes as $key => $valor)
                  {
                  $mensaje .= "\nPRODUCTO: ".$valor['codigo_producto']."-".$valor['descripcion'];

                  $mensaje .= "\nCANTIDAD PENDIENTE: ".FormatoValor($valor['cantidad_pendiente']);
                  $mensaje .= "\n";
                  } */
                $mensaje .= "\nHAY (" . count($pendientes) . ")PRODUCTO(s) PENDIENTES POR DESPACHAR..";
                $mensaje .= "\nPOR FAVOR JUSTIFICAR EL DESPACHO INCOMPLETO!!";
                $objResponse->alert($mensaje);
                $objResponse->script("xajax_ObservacionesDespachoCliente('','" . $bodegas_doc_id . "','" . $doc_tmp_id . "','" . $tipo_doc_bodega_id . "','" . $identificador . "','" . $pedido['solicitud_prod_a_bod_ppal_id'] . "');");
                return $objResponse;
            }
        }



        $aumento = " AND((COALESCE(a.numero_unidades,0)-COALESCE(a.cantidad_despachada,0))-COALESCE(c.cantidad,0)) > 0 ";
        $aumento .= " AND e.observacion IS NULL ";
        $datosItem = $e8->ObtenerProductosPedidoCliente($doc_tmp_id, UserGetUID(), $pedido['pedido_cliente_id'], trim(SessionGetVar("empresa_id")), $param, $aumento, SessionGetVar("centro_utilidad"), SessionGetVar("bodega"));
        if ($identificador == "CL") {
            if (!empty($datosItem)) {
                $mensaje .= "PRODUCTOS PENDIENTES PARA DESPACHOS AL CLIENTE:";
                $mensaje .= "\n";
                /* foreach($datosItem as $key => $valor)
                  {
                  $mensaje .= "\nPRODUCTO: ".$valor['codigo_producto']."-".$valor['descripcion'];
                  $mensaje .= "\nCANTIDAD PENDIENTE: ".FormatoValor($valor['cantidad_pendiente']);
                  $mensaje .= "\n";
                  } */
                $mensaje .= "\nHAY (" . count($datosItem) . ")PRODUCTO(s) PENDIENTES POR DESPACHAR..";
                $mensaje .= "\nFAVOR JUSTIFICAR EL DESPACHO INCOMPLETO!!";
                $objResponse->alert($mensaje);
                $objResponse->script("xajax_ObservacionesDespachoCliente('','" . $bodegas_doc_id . "','" . $doc_tmp_id . "','" . $tipo_doc_bodega_id . "','" . $identificador . "','" . $pedido['pedido_cliente_id'] . "');");
                return $objResponse;
            }
        }

        $cliente = ($identificador == "FM") ? 1 : 2;




        $productos = $consulta->CrearDocumentoOriginalF($bodegas_doc_id, $doc_tmp_id, $cliente, $pedido, UserGetUID());

        // ====================================== Sincronizar con Cosmitet ======================================

        if ($identificador == "FM" && $pedido["farmacia_id"] == "01") {


            // Sincronizar con Cosmitet
            $cosmitet_ws = AutoCarga::factory("SincronizacionCosmitet", "SincronizacionCosmitet", "", "");

            $pedido_farmacia = $e8->consultarPedidoFarmacia($pedido['solicitud_prod_a_bod_ppal_id']);
            // Ingresar Cabecera
            $resultado_ws_cabecera = $cosmitet_ws->ingresarDocumentoTemporal($pedido_farmacia['bodega'], 418, $productos['observacion']);

            if (count($resultado_ws_cabecera) > 0) {

                if ($resultado_ws_cabecera['estado']) {

                    $documentoTemporalCosmitet = $resultado_ws_cabecera['docTmpId'];

                    // agregar items
                    $productos_seleccionados = $consulta->SacarDocumento(SessionGetVar("empresa_id"), $productos['prefijo'], $productos['numero']);

                    $mensaje_error = array();
                    $error = false;

                    foreach ($productos_seleccionados['DETALLE'] as $producto) {
                        $resultado_ws = $cosmitet_ws->ingresarDetalleDocumentoTemporal($documentoTemporalCosmitet, $producto);
                        array_push($mensaje_error, " Producto {$producto['codigo_producto']} " . $resultado_ws['descripcion']);

                        if (!$resultado_ws['estado']) {
                            $error = true;
                        }
                    }


                    if ($error) {
                        $objResponse->alert("Problemas al enviar los datos a cosmitet");
                    }else{
                        $objResponse->alert("Datos enviados correctamente a cosmitet");
                        
                    }
                } else {
                    // No se pudo crear Docuumento
                    $objResponse->alert("No se Pudo Ingresar Documento en Cosmitet por que - " . $resultado_ws['descripcion']);
                }
            }

            /* var_dump($resultado_ws_cabecera);
              echo "******************************";
              var_dump($mensaje_error); */
        }

        // ====================================== FIN SINCRONIZACION COSMITET =========================


        $objResponse->assign("justificaciones", "style.display", "none");
        $objResponse->assign("justificaciones", "innerHTML", "");

        if ($productos === false) {
            $objResponse->alert("1" . $consulta->mensajeDeError);
            return $objResponse;
        }

        $rst = true;
        if ($identificador == "FM")
            $rst = $consulta->BorrarTmpInv_Farmacias($doc_tmp_id, $pedido['solicitud_prod_a_bod_ppal_id']);
        else
            $rst = $consulta->BorrarTmpInv_Clientes($doc_tmp_id, $pedido['pedido_cliente_id']);

        if ($rst === false) {
            $objResponse->alert($consulta->mensajeDeError);
            return $objResponse;
        }

        $rst = $consulta->ActuInvCrotulo($productos['documento_id'], $doc_tmp_id);
        if ($rst === false) {
            $objResponse->alert($consulta->mensajeDeError);
            return $objResponse;
        }

        if (!empty($productos)) {
            $salida .= "                  <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td align=\"center\" width=\"12%\">\n";
            $salida .= "                        <a title='DOCUMENTO ID'>DOC ID<a> ";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"12%\">\n";
            $salida .= "                        <a title='PREFIJO-NUMERO'>PREFIJO<a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"40%\">\n";
            $salida .= "                        <a title='OBSERVACIONES DEL DOCUMENTO'>OBSERVACIONES<a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"27%\">\n";
            $salida .= "                        <a title='FECHA'>FECHA<a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" colspan=\"3\" width=\"7%\">\n";
            $salida .= "                        <a title='ACCIONES SOBRE EL DOCUMENTO'>ACCIONES<a>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    <tr class=\"modulo_list_claro\" >\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                        " . $productos['documento_id'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                         " . $productos['prefijo'] . "-" . $productos['numero'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                        " . $productos['observacion'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                         " . substr($productos['fecha_registro'], 0, 10);
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\">\n";
            $file = "app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/E008/imprimir/imprimir_docE008.php";
            $imgn = GetThemePath() . "/images/imprimir.png";

            $salida .= RetornarImpresionDoc($file, "IMPRIMIR DOCUMENTO", $imgn, SessionGetVar("EMPRESA"), $productos['prefijo'], $productos['numero']);
            $salida .= "                      </td>\n";

            $xml = AutoCarga::factory("ReportesCsv");
            SessionSetVar("DocumentoDespacho_E008", array("empresa_id" => SessionGetVar("EMPRESA"), "prefijo" => $productos['prefijo'], "numero" => $productos['numero']));
            $fnc = SessionGetVar("funcion_E008");
            $salida .= "  <td align=\"center\">\n";
            $salida .= "	  <a title=\"DETALLE DOCUMENTO\" class=\"label_error\" href=\"javascript:" . $fnc . "\">\n";
            $salida .= "	    <image src=\"" . GetThemePath() . "/images/pinactivo.png\" border=\"0\">PDF\n";
            $salida .= "    </a>\n";
            $salida .= "  </td>\n";
            $fnc = SessionGetVar("rotulo_E008");
            $salida .= "  <td align=\"center\">\n";
            $salida .= "	  <a title=\"ROTULO\" class=\"label_error\" href=\"javascript:" . $fnc . "\">\n";
            $salida .= "	    <image src=\"" . GetThemePath() . "/images/panulado.png\" border=\"0\">PDF\n";
            $salida .= "    </a>\n";
            $salida .= "  </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    </table>\n";
            //BorrarTmpFarmacias($doc_tmp_id)
            $objResponse->assign("ventana1", "innerHTML", $salida);
            $objResponse->call("superoff");
            $objResponse->script($scpt);
            $objResponse->assign("tablaoide", "innerHTML", "");
            $objResponse->assign("listadoP", "innerHTML", "");
            $objResponse->assign("productos_ordenCompra", "innerHTML", "");
            $objResponse->alert("SE HA CREADO EL DOCUMENTO EXITOSAMENTE");
            //$e8->Actualizar_ETrigger(SessionGetVar("EMPRESA"),$productos['prefijo'],$productos['numero']);
        }
    } else {
        $objResponse->alert("DEBE GUARDAR EL ROTULO");
        //return $objResponse;
    }
    return $objResponse;
}

function BorrarTmpAfirmativo1($tmp, $bodega_doc_id) {
    $consulta = new MovBodegasSQL();
    $objResponse = new xajaxResponse();
    $buscar = $consulta->EliminarDocTemporal($bodega_doc_id, $tmp, UserGetUID());
//       var_dump($buscar);
    if ($buscar == 1) {
        $objResponse->alert("EL DOCUMENTO TEMPORAL $tmp FUE ELIMINADO EXITOSAMENTE");
        $objResponse->call("AfirmaciondeEliminar");
    } else {
        $objResponse->alert("NO SE PUEDE BORRAR");
    }

    return $objResponse;
}

/**
 *
 */
function GuardarRotuloCaja($tmp_doc_id, $cliente, $direccion, $cantidad, $ruta, $descripcion, $solicitud_prod_a_bod_ppal_id, $identificador) {
    $objResponse = new xajaxResponse();
    $objClass = new doc_bodegas_E008();

    $mensaje = "";
    if ($cliente == "")
        $mensaje = "<label class=\"label_error\">FALTA INDICAR EL NOMBRE DEL CLIENTE</label>";
    else if ($direccion == "")
        $mensaje = "<label class=\"label_error\">FALTA INDICAR LA DIRECCION DEL CLIENTE</label>";
    else if ($ruta == "")
        $mensaje = "<label class=\"label_error\">FALTA INDICAR LA RUTA DE VIAJE</label>";

    if ($mensaje == "") {
        $si_esta = $objClass->ObtenerInformcionRotulo($tmp_doc_id, $solicitud_prod_a_bod_ppal_id);
        if (empty($si_esta)) {
            $GuardaCaja = $objClass->GuardarCaja($tmp_doc_id, $cliente, $direccion, $cantidad, $ruta, $descripcion, $solicitud_prod_a_bod_ppal_id);
            if (!$GuardaCaja)
                $mensaje = "<label class=\"label_error\">" . $objClass->frmError['MensajeError'] . "</label>";
            else {
                $tabla = ($identificador == "FM") ? "farmacias" : "clientes";
                $GuardaRuta = $objClass->ActuRuta($tmp_doc_id, $ruta, $tabla);
                if (!$GuardaRuta)
                    $mensaje = "<label class=\"label_error\">" . $objClass->frmError['MensajeError'] . "</label>";
                else
                    $mensaje = "<label class=\"normal_10AN\">DATOS GUARDADOS CORRECTAMENTE</label>";
            }
        }
        else {
            $ActCaja = $objClass->ActuCaja($tmp_doc_id, $cliente, $direccion, $cantidad, $ruta, $descripcion, $solicitud_prod_a_bod_ppal_id);
            if (!$ActCaja)
                $mensaje = "<label class=\"label_error\">" . $objClass->frmError['MensajeError'] . "</label>";
            else {
                $tabla = ($identificador == "FM") ? "farmacias" : "clientes";
                $GuardaRuta = $objClass->ActuRuta($tmp_doc_id, $ruta, $tabla);
                if (!$GuardaRuta)
                    $mensaje = "<label class=\"label_error\">" . $objClass->frmError['MensajeError'] . "</label>";
                else
                    $mensaje = "<label class=\"normal_10AN\">DATOS GUARDADOS CORRECTAMENTE</label>";
            }
        }
    }
    $objResponse->assign("mensajes", "innerHTML", $mensaje);
    return $objResponse;
}

function Devolver($tipo_doc_bodega_id, $doc_tmp_id, $empresa_id) {
    $consulta = new MovBodegasSQL();
    //$productos=$consulta->CrearDocumentoOriginal($bodegas_doc_id,$doc_tmp_id,UserGetUID());
    $objResponse = new xajaxResponse();
    $productos = $consulta->Consultausuaritmp($doc_tmp_id, $bodegas_doc_id);

    $salida .= " <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
    $salida .= "  <tr>\n";
    //$salida .= "<pre>".print_r($doc_tmp_id,true)."</pre>";
    $salida .= "     <td align=\"center\" width=\"30%\" class=\"modulo_table_list_title\">\n";
    $salida .= "      <a title='creador'>USUARIO<a> ";
    $salida .= "     </td>\n";
    $salida .= "     <td align=\"left\" class=\"normal_10AN\" class=\"modulo_list_claro\">\n";
    $salida .= "      " . UserGetUID() . "";
    $salida .= "     </td>\n";
    $salida .= "   </tr>\n";
    $salida .= "   <tr>\n";
    $salida .= "     <td align=\"center\" width=\"30%\" class=\"modulo_table_list_title\">\n";
    $salida .= "       <a title='observacion'>OBSERVACION<a> ";
    $salida .= "     </td>\n";
    $salida .= "     <td align=\"left\" class=\"normal_10AN\" class=\"modulo_list_claro\">\n";
    $salida .= "       <input type=\"text\" class=\"input-text\"  name=\"observacion\" id=\"observacion\" maxlength=\"80\" style=\"width:100%;height:100%\" value=\"\">\n";
    $salida .= "     </td>\n";
    $salida .= "    </tr>\n";
    $salida .= "    <td id='MENGANO' align=\"center\"  colspan='7'>\n";
    $salida .= "      <input type=\"button\" id='enar' class=\"input-submit\" value=\"GUARDAR\"onclick=\"GuardarDevolucion('" . $tipo_doc_bodega_id . "','" . $empresa_id . "',document.getElementById('observacion').value,'" . $doc_tmp_id . "');\">\n";
    $salida .= "    </td>\n";
    $salida .= " </table>";
    $objResponse->assign("ventana1", "innerHTML", $salida);
    return $objResponse;
}

function GuardarDevolucion($tipo_doc_bodega_id, $empresa_id, $observacion, $doc_tmp_id) {
    $consulta = new MovBodegasSQL();
    $objResponse = new xajaxResponse();
    //print_r($tipo_doc_bodega_id);
    $guardar = $consulta->GuardarDevolucion($tipo_doc_bodega_id, $empresa_id, $observacion, $doc_tmp_id);

    return $objResponse;
}

function Actualizartmp($bodega_doc_id, $tmp, $estado, $tipo_documento) {
    $objResponse = new xajaxResponse();
    if ($estado != '-1') {
        $consulta = new MovBodegasSQL();
        $buscar = $consulta->ActuEstado($estado, UserGetUID(), $tmp, $tipo_documento);

        $documentos = $consulta->ConsultaPardocg($tmp);
        foreach ($documentos as $key => $dtl) {
            if ($dtl['sw_verifico'] == '1') {
                $objResponse->assign("rotulo_empresa", "style.display", "block");
                $objResponse->script("document.getElementById('enar').disabled = false;");
            }
        }
    }
    return $objResponse;
}

function BuscarProductoPendiente($empresa_id, $param) {
    $consulta = new MovBodegasSQL();
    $objClass = new doc_bodegas_E008();
    $objResponse = new xajaxResponse();
    $aumento = "AND b.codigo_producto='" . $param . "'";
    $buscar = $objClass->SacarProductosFarmaciaSW($empresa_id, $aumento);
    //print_r($buscar);  
    //if($buscar)
    //{
    $i = 0;
    $c = 0;
    $salida .= "<table colspan=\"7\"border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $salida .= "	<tr colspan=\"3\"class=\"modulo_table_list_title\">";
    $salida .= "		<td width=\"15%\">CODIGO</td>";
    $salida .= "		<td width=\"15%\">DESCRIPCION</td>";
    $salida .= "		<td width=\"6%\">ID SOLICITUD</td>";
    $salida .= "		<td width=\"3%\">CANTIDAD EXISTENTE</td>";
    $salida .= "		<td width=\"4%\">CANTIDAD SOLICITADA</td>";
    $salida .= "		<td width=\"4%\">LOTE</td>";
    $salida .= "		<td width=\"10%\">FECHA VENCIMIENTO</td>";
    $salida .= "	</tr>";
    foreach ($buscar as $key => $valor) {

        if ($i % 2 == 0)
            $estilo = "modulo_list_claro";
        else
            $estilo = "modulo_list_oscuro";

        $salida .= "<tr style=\"background:#A9D0F5\"class=\"$estilo\" id=\"capa1$i\">";

        //$salida .= "<pre>".print_r($datos2,true)."</pre>";
        //$salida .= "<pre>".print_r($datosItem,true)."</pre>";
        $salida .= "	<td>" . $valor['codigo_producto'] . "</td>";
        $salida .= "	<td>" . $valor['descripcion'] . "</td>";
        $salida .= "	<td>" . $valor['solicitud_prod_a_bod_ppal_id'] . "</td>";
        $salida .= "	<td>" . $valor['existencia_actual'] . "</td>";
        $salida .= "	<td>" . $valor['cantidad_solic'] . "</td>";
        $salida .= "	<td>" . $valor['fecha_vencimiento'] . "</td>";
        $salida .= "	<td>" . $valor['lote'] . "</td>";
    }

    $salida .= "</table>";
    //}
    //$contar=count($datos);
    //print_r($contar);
    //for($i=0;$i<$contar;$i++)
    //{
    //print_r($i);
    //  $salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecha_vencimiento\"id=\"fecha_vencimiento\"value=\"".$buscar['fecha_vencimiento']."\" disabled>\n";
    // $salida .= "       <input type=\"hidden\" id=\"fecha_venci\" value='".$buscar['fecha_vencimiento']."'>"; 
    //}

    $objResponse->assign("BuscarProdutoPendientes", "innerHTML", $salida);
    return $objResponse;
}

function MostrarProductox($bodegas_doc_id, $doc_tmp_id, $usuario_id) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new doc_bodegas_E008();
    $vector = $consulta->SacarProductosTMP($doc_tmp_id, $usuario_id);

    if (!empty($vector)) {
        $salida .= "                  <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td align=\"center\" width=\"12%\">\n";
        $salida .= "                        <a title='CODIGO DEL PRODUCTO'>CODIGO<a> ";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"30%\">\n";
        $salida .= "                        <a title='DESCRIPCION DEL PRODUCTO'>DESCRIPCION<a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"30%\">\n";
        $salida .= "                        <a title='FECHA VENCIMIENTO'>FECHA VENCIMIENTO<a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"15%\">\n";
        $salida .= "                        <a title='LOTE'>LOTE<a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"12%\">\n";
        $salida .= "                        <a title='UNIDAD DEL PRODUCTO'>UNIDAD<a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"12%\">\n";
        $salida .= "                        CANTIDAD";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"12%\">\n";
        $salida .= "                        <a title='PORCENTAJE DEL GRAVAMEN'> % GRAVAMEN<a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"20%\">\n";
        $salida .= "                        <a title='COSTO TOTAL'>COSTO<a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"2%\">\n";
        $salida .= "                        <a title='ELIMINAR REGISTRO'>X<a>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        foreach ($vector as $valor => $productos) {
            $tr = $bodegas_doc_id . "@" . $productos['doc_tmp_id'];
            $salida .= "                    <tr id='" . $tr . "' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
            $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
            $salida .= "                        " . $productos['codigo_producto'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
            $salida .= "                         " . $productos['descripcion'];
            $salida .= "                      </td>\n";

            $fech_vencmodulo = ModuloGetVar('app', 'AdministracionFarmacia', 'dias_vencimiento_product_bodega_farmacia_02');

            $fecha_actual = date("m/d/Y");
            /*
             * Para Sacar los numeros de días entre fechas
             */
            $fecha = $productos['fecha_vencimiento'];  //esta es la que viene de la DB
            list( $dia, $mes, $ano ) = split('[/.-]', $fecha);
            $fecha = $mes . "/" . $dia . "/" . $ano;
            //Mes/Dia/Año  "02/02/2010
            $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual)) / 86400);
            if ($int_nodias > $fech_vencmodulo) {
                $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                $salida .= "                         " . $productos['fecha_vencimiento'];
                $salida .= "                      </td>\n";
            } else {
                $salida .= "                      <td style=\"background:#A9D0F5\" align=\"left\" class=\"label_mark\">\n";
                $salida .= "                         " . $productos['fecha_vencimiento'];
                $salida .= "                      </td>\n";
            }
            $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
            $salida .= "                         " . $productos['lote'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
            $salida .= "                        " . $productos['descripcion_unidad'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
            $salida .= "                         " . $productos['cantidad'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\">\n";
            $salida .= "                        " . $productos['porcentaje_gravamen'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                        " . FormatoValor($productos['total_costo']);
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\">\n";
            $jaxx = "javascript:MostrarCapa('ContenedorB3');BorrarAjustes('" . $tr . "','" . $productos['item_id'] . "','ContenidoB3');IniciarB3('ELIMINAR REGISTRO');";
            $salida .= "                        <a title='ELIMINAR REGISTRO' href=\"" . $jaxx . "\">\n";
            $salida .= "                          <sub><img src=\"" . $path . "/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
            $salida .= "                         </a>\n";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
        }

        $salida .= "                    </table>\n";
        $objResponse->call("super");
    } else {
        $salida .= "                  <table width=\"80%\" align=\"center\">\n";
        $salida .= "                  <tr>\n";
        $salida .= "                  <td align=\"center\">\n";
        $salida .= "                      <label class='label_error'> ESTE DOCUMENTO NO TIENE PRODUCTOS ASIGNADOS</label>";
        $salida .= "                  </td>\n";
        $salida .= "                  </tr>\n";
        $salida .= "                  </table>\n";
    }
    $objResponse->assign("tablaoide", "innerHTML", $salida);

    return $objResponse;
}

function GetItems($doc_tmp_id, $bodegas_doc_id, $identificador) {
    $objResponse = new xajaxResponse();

    $objClass = new doc_bodegas_E008();
    $consulta1 = new MovBodegasSQL();
    $pedido = $consulta1->FarmaciaPedidosTmp($doc_tmp_id);
    $datosItem = $objClass->SacarProductosTMP($doc_tmp_id, $usuario_id);
    //$datosItem = $objClass->ConsultarItems($doc_tmp_id,$bodegas_doc_id);
    if (!empty($datosItem)) {
        $documentos = $consulta1->ConsultaPardocg($doc_tmp_id);
        $disa = "true";
        foreach ($documentos as $key => $dtl)
            if ($dtl['sw_verifico'] == '1')
                $disa = "false";

        $objResponse->assign("crearDoc", "style.display", "block");
        $objResponse->script("document.getElementById('enar').disabled = " . $disa . ";");
    }
    else {
        $objResponse->script("document.getElementById('enar').disabled=true;");
    }
    $existencias = $objClass->ObtnerCantidadesIngresadasTodos($doc_tmp_id);
    $salida = FormaItems_HTML($datosItem, $doc_tmp_id, $bodegas_doc_id, $identificador, $existencias);
    $objResponse->assign("listadoP", "innerHTML", $objResponse->setTildes($salida));
    return $objResponse;
}

function FormaItems_HTML($datosItem, $doc_tmp_id, $bodegas_doc_id, $identificador, $existencias) {

    $fech_vencmodulo = ModuloGetVar('app', 'AdminFarmacia', 'dias_vencimiento_product_bodega_farmacia_' . SessionGetVar("EMPRESA"));

    if ($datosItem) {
        $e08 = new doc_bodegas_E008();
        $mbs = new MovBodegasSQL();
        $pedido = array();
        $solicitudes = array();
        if ($identificador == "FM") {
            $pedido = $mbs->FarmaciaPedidosTmp($doc_tmp_id);
            $solicitudes = $e08->ObtenerCantidadesSolicitadasFarmacia($pedido['solicitud_prod_a_bod_ppal_id']);
        }
        /* else
          {
          $pedido = $mbs->ClientesPedidosTmp($doc_tmp_id);
          $solicitudes = $e08->ObtenerCantidadesSolicitadasFarmacia($pedido['pedido_cliente_id']);
          } */
        $salida .= "<br>";
        $salida .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "		<tr  class=\"modulo_table_list_title\">\n";
        $salida .= "			<td >\n";
        $salida .= "				CODIGO";
        $salida .= "			</td>\n";
        $salida .= "			<td  >\n";
        $salida .= "				DESCRIPCION";
        $salida .= "			</td>\n";
        $salida .= "			<td >\n";
        $salida .= "				CANTIDAD";
        $salida .= "			</td>\n";
        $salida .= "			<td >\n";
        $salida .= "				LOTE";
        $salida .= "			</td>\n";
        $salida .= "			<td >\n";
        $salida .= "				FECHA VENCIMIENTO";
        $salida .= "			</td>\n";
        $salida .= "			<td >\n";
        $salida .= "				GRAVAMEN";
        $salida .= "			</td>\n";
        $salida .= "			<td >\n";
        $salida .= "				TOTAL";
        $salida .= "      </td>\n";
        $salida .= "			<td  width=\"2%\">\n";
        $salida .= "				ACCION";
        $salida .= "      </td>\n";
        $salida .= "		</tr>\n";
        $i = 0;
        $cantidad = $gravamen = $total = 0;
        foreach ($datosItem as $key => $valor) {


            $salida1 .= "<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
            $salida1 .= "<td>" . $valor['codigo_producto'] . "</td>";
            $salida1 .= "<td>" . $valor['descripcion'] . "</td>";
            $salida1 .= "<td align=\"right\">" . FormatoValor($valor['cantidad']) . "</td>";
            $salida1 .= "<td>" . $valor['lote'] . "</td>";


            $fecha = $valor['fecha_vencimiento'];  //esta es la que viene de la DB
            list($ano, $mes, $dia) = split('[/.-]', $fecha);
            $fecha = $mes . "/" . $dia . "/" . $ano;

            $fecha_actual = date("m/d/Y");
            $fecha_compara_actual = date("Y-m-d");
            //Mes/Dia/Año  "02/02/2010
            $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual)) / 86400);
            $colores['PV'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_proximo_vencer');
            $colores['VN'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_vencido');

            $fecha_uno_act = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $fecha_dos = mktime(0, 0, 0, $mes, $dia, $ano);
            $color = "";
            if ($int_nodias < $fech_vencmodulo) {
                $color = "style=\"background:" . $colores['PV'] . "\"";
            }

            if ($fecha_dos <= $fecha_uno_act) {
                $color = "style=\"background:" . $colores['VN'] . "\"";
            }


            $salida1 .= "<td " . $color . ">" . $valor['fecha_vencimiento'] . "</td>";
            $salida1 .= "<td align=\"right\">" . FormatoValor($valor['porcentaje_gravamen'], 2) . " % </td>";
            if ($identificador == "FM") {
                $salida1 .= "<td align=\"right\"> $ " . FormatoValor($valor['total_costo'], 4) . "</td>";
            } else {
                $salida1 .= "<td align=\"right\"> $ " . FormatoValor($valor['total_costo'] + ($valor['total_costo'] * ($valor['porcentaje_gravamen'] / 100)), 4) . "</td>";
            }
            $salida1 .= "<td align=\"center\" width=\"2%\">\n";
            $salida1.=" <a href=\"javascript:RemoverItem('" . $doc_tmp_id . "','" . $bodegas_doc_id . "','" . $key . "','" . $identificador . "')\">\n";
            $salida1.="   <img src=\"" . GetThemePath() . "/images/delete2.gif\" border=\"0\">\n";
            $salida1.=" </a>\n";
            $salida1.="</td>";


            if ($existencias[$valor['codigo_producto']] && $solicitudes[$valor['codigo_producto']]['cantidad_solic'] > $existencias[$valor['codigo_producto']]['cantidad']) {
                $salida1 .= "<td align=\"center\">\n";
                $salida1 .= " <a href=\"javascript:Observacion('" . $doc_tmp_id . "','" . $valor['codigo_producto'] . "')\">\n";
                $salida1 .= "   <img src=\"" . GetThemePath() . "/images/pmodificar.png\" border=\"0\">\n";
                $salida1 .= " </a>\n";
                $salida1 .= "</td>";
            }


            $salida1 .= "	</tr>\n";
            $cantidad += $valor['cantidad'];
            $gravamen += $valor['porcentaje_gravamen'];
            if ($identificador == "FM") {
                $total += $valor['total_costo'];
            } else {
                $total += $valor['total_costo'] + ($valor['total_costo'] * ($valor['porcentaje_gravamen'] / 100));
            }
            $i++;
        }

        $salida1 .= "	<tr  class=\"modulo_list_claro\">\n";
        $salida1 .= "		<td  align=\"right\" colspan=\"2\">\n";
        $salida1 .= "			<label class=\"label_error\"><b>TOTALES: </b>";
        $salida1 .= "		</td>\n";
        $salida1 .= "		<td  align=\"right\" >\n";
        $salida1 .= "			<b>" . FormatoValor($cantidad) . "</b>";
        $salida1 .= "		</td>\n";

        $salida1 .= "		<td  align=\"right\">\n";
        $salida1 .= "		</td>\n";
        $salida1 .= "		<td  align=\"right\">\n";
        $salida1 .= "		</td>\n";

        $salida1 .= "		<td  align=\"right\" >\n";
        $salida1 .= "			<b>" . FormatoValor($gravamen, 2) . "%</b>";
        $salida1 .= "		</td>\n";
        $salida1 .= "		<td  align=\"right\" >\n";
        $salida1 .= "			<b>$" . FormatoValor($total, 4) . "</b>";
        $salida1 .= "		</td>\n";
        $salida1 .= "		<td  align=\"center\">\n";
        $salida1 .= "			&nbsp;";
        $salida1 .= "		</td>\n";
        $salida1 .= "	</tr>\n";

        //
        $salida .= "	$salida1\n";
        $salida .= "</table>\n";
    }

    return $salida;
}

function AgregarItem($doc_tmp_id, $codigo, $can, $valor, $iva, $bodegas_doc_id, $capa, $lote, $fecha_vencimiento, $ItemId, $existencia_actual, $identificador, $cantidadSol, $sw_requiereautorizacion_despachospedidos) {
    $objResponse = new xajaxResponse();
    $fecha_actual = date("m/d/Y");
    $fecha_actual_2 = date("Y-m-d");
    $dias_vencimiento = ModuloGetVar('app', 'AdminFarmacia', 'dias_vencimiento_product_bodega_farmacia_' . SessionGetVar("EMPRESA"));

    $fecha = $fecha_vencimiento;  //esta es la que viene de la DB
    list( $ano, $mes, $dia ) = split('[/.-]', $fecha);
    $fecha = $mes . "/" . $dia . "/" . $ano;

    //Mes/Dia/Año  "02/02/2010"
    $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual)) / 86400);
    $datosItem = 1;
    $objClass = new doc_bodegas_E008();
    $datox = $objClass->DatosParaEditar($doc_tmp_id, UserGetUID());

    $total_costo = $can * ($valor + ($valor * $iva) / 100);
    //$datosItem = $objClass->ConsultarItemsExistencias($codigo,SessionGetVar('Empresa_id'),SessionGetVar('centro_utilidad'),SessionGetVar('bodega'),$lote,$fecha_vencimiento);
    // $objResponse->alert($can);
    //$objResponse->alert($existencia_actual); 

    /* $objResponse->alert($int_nodias); */

    if (count($datosItem) == 0) {
        $objResponse->alert("El producto [ $codigo ] no se encuentra en bodegas_existencias");
    } else
    if ($can > $existencia_actual)
        $objResponse->alert("La cantidad debe ser menor a la existente!!!"); /* .$int_nodias."-".$dias_vencimiento */

    else {

        if ($int_nodias <= $dias_vencimiento)
            $objResponse->alert("Producto Proximo a Vencer!!!");
        if ($fecha_vencimiento < $fecha_actual_2)
            $objResponse->alert("PRODUCTO VENCIDO!!!");


        $consulta1 = new MovBodegasSQL();
        $cantidadI = $objClass->ObtnerCantidadIngresada($codigo, $doc_tmp_id);
        //$objResponse->alert(print_r($cantidadI),true);
        $cantidadS = $objClass->ObtnerCantidadSolicitada($codigo, $doc_tmp_id, $identificador);
        /* $objResponse->alert($cantidadS['solicitud']);
          $objResponse->alert($cantidadI['cantidad']); */
        $cantidadI['cantidad'];

        if ($cantidadS['solicitud'] >= ($cantidadI['cantidad'] + $can)) {
            $fecha_vencim = explode("-", $fecha_vencimiento);
            $fechavencimiento = $fecha_vencim[2] . "-" . $fecha_vencim[1] . "-" . $fecha_vencim[0];
            if ($identificador == 'FM') {
                /* GuardarTemporal($bodegas_doc_id,$doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id=null,$fecha_venc,$lotec,$total_costo_ped) */
                if ($sw_requiereautorizacion_despachospedidos == '0')
                    $item_id = $objClass->GuardarTemporal($bodegas_doc_id, $doc_tmp_id, $codigo, $can, 0, $total_costo, UserGetUID(), $fechavencimiento, $lote, 0);
                else
                    $item_id = $objClass->GuardarTemporalAutorizacion($datox['empresa_id'], $datox['centro_utilidad'], $datox['bodega'], $doc_tmp_id, $codigo, $can, $total_costo, $fechavencimiento, $lote);
            }
            else {
                $item_id = $objClass->GuardarTemporal($bodegas_doc_id, $doc_tmp_id, $codigo, $can, $iva, $total_costo, UserGetUID(), $fechavencimiento, $lote, $total_costo);
            }
            if ($item_id) {
                /*
                 * Fecha: 04-02-2010
                 * Nueva línea: Para ingresar lote y fecha de vencimiento a un producto en la orden de compra.
                 */
                $objResponse->script("xajax_GetItems('" . $doc_tmp_id . "','" . $bodegas_doc_id . "','" . $identificador . "');");
                $html = "  BuscarProductos('1',document.buscador.tip_bus.value,document.buscador.criterio.value,'" . $doc_tmp_id . "','" . $bodegas_doc_id . "','" . $datox['empresa_id'] . "','" . $datosx['centro_utilidad'] . "','" . $identificador . "');";
                //$html = "  BuscarProductos('1','0','0','".$doc_tmp_id."','".$bodegas_doc_id."','".$datox['empresa_id']."','".$datosx['centro_utilidad']."','".$identificador."');";
                $objResponse->script($html);
            }
        } else {
            $objResponse->alert("ACTUALMENTE SE HAN AGREGADO " . FormatoValor($cantidadI['cantidad']) . " DE " . FormatoValor($cantidadS['solicitud']) . " SOLICITADAS PARA LOS PRODUCTO " . $cantidadI['descripcion']);
        }
    }
    return $objResponse;
}

function ListadoProductos($pagina, $tipo_param, $param, $doc_tmp_id, $bodegas_doc_id, $empresa_id, $centro_utilidad, $identificador) {

    $objResponse = new xajaxResponse();
    $objClass = new doc_bodegas_E008();
    $consulta1 = new MovBodegasSQL();

    if ($param == "") {
        $mensaje = "DEBE ESPECIFICAR SU BUSQUEDA";
        $objResponse->assign("error_buscador", "innerHTML", $mensaje);
        return $objResponse;
    }

    $objResponse->assign("error_buscador", "innerHTML", "");
    $productos = $objClass->ConsultarItems($doc_tmp_id, $bodegas_doc_id);

    $pedido = array();
    if ($identificador == "FM")
        $pedido = $consulta1->FarmaciaPedidosTmp($doc_tmp_id);
    else
        $pedido = $consulta1->ClientesPedidosTmp($doc_tmp_id);


    $aumento = "";
    if ($tipo_param == 1) {
        $consulta1->RegistrarBusqueda(UserGetUID(), $empresa_id);
        $aumento = "AND  b.codigo_barras='" . $param . "'";
    } else if ($tipo_param == 2)
        $aumento = " AND    b.descripcion ILIKE '%" . $param . "%'";

    $datos = $datos2 = array();
    //print_r($identificador);
    if ($identificador == "FM") {
        $datos = $objClass->SacarProductosFarmaciaSW($empresa_id, $aumento, $pedido['solicitud_prod_a_bod_ppal_id']);
        if (empty($datos))
            $datos = $objClass->SacarProductosFarmacia($doc_tmp_id, $usuario_id, $pedido['solicitud_prod_a_bod_ppal_id'], $empresa_id, $param, $aumento);
        $conteo = $objClass->conteo;
        $datosItem = $objClass->ConsultarItemsAgrupados($doc_tmp_id, $bodegas_doc_id);
        $datosItemsAutorizar = $objClass->ConsultarItemsAutorizar($doc_tmp_id, $bodegas_doc_id);
        /* print_r($datosItemsAutorizar); */
        $salida = ListadoProductos_HTML($datos, $datos2, $pagina, $tipo_param, $param, $conteo, $datosItem, $datosItemsAutorizar, $doc_tmp_id, $bodegas_doc_id, $empresa_id, $centro_utilidad, $productos, $identificador);
    }
    else {
        $datos = $objClass->ObtenerProductosPedidoCliente($doc_tmp_id, $usuario_id, $pedido['pedido_cliente_id'], $empresa_id, $param, $aumento, SessionGetVar("centro_utilidad"), SessionGetVar("bodega"));
        $conteo = $objClass->conteo;
        $salida = ListadoProductosCliente_HTML($datos, $datos2, $pagina, $tipo_param, $param, $conteo, $doc_tmp_id, $bodegas_doc_id, $empresa_id, $centro_utilidad, $productos, $_REQUEST['DATOS']['bodegax'], $identificador);
    }


    $objResponse->assign("productos_ordenCompra", "innerHTML", $objResponse->setTildes($salida));

    return $objResponse;
}

function ListadoProductos_HTML($datos, $datos2, $pagina, $tipo_param, $param, $conteo, $datosItem, $datosItemsAutorizar, $doc_tmp_id, $bodegas_doc_id, $empresa_id, $centro_utilidad, $productos, $identificador) {
    $objClass = new doc_bodegas_E008();
    $consulta1 = new MovBodegasSQL();


    //SessionGetVar('bodega')

    $existencias = $objClass->ObtnerCantidadesIngresadasTodos($doc_tmp_id);
    $existencias_autorizar = $objClass->ObtnerCantidadesIngresadasAutorizar($doc_tmp_id);
    print_r($existencias_autorizar, true);

    $fech_vencmodulo = ModuloGetVar('app', 'AdminFarmacia', 'dias_vencimiento_product_bodega_farmacia_' . SessionGetVar("EMPRESA"));
    $salida .= "<br>";
    $salida .= "<div id=\"cabecera_facturaventa\"></div>";
    //$salida .= "<pre>".print_r($datos,true)."</pre>"; 
    if ($datos) {
        $salida .= "<center><label class=\"label_error\">Resultado(s) Encontrado(s). (" . sizeof($datos) . ")</label></center>";

        $i = 0;
        foreach ($datos as $key => $valor) {
            $salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
            $salida .= "  <tr class=\"modulo_table_list_title\">";
            $salida .= "  	<td colspan=\"11\" align=\"center\">PRODUCTOS DESPACHO DE FARMACIA</td>";
            $salida .= "  </tr>";
            foreach ($valor as $key1 => $valor2) {
                $salida .= "	<tr class=\"formulacion_table_list\">\n";
                $salida .= "		<td colspan=\"3\"width=\"30%\">CODIGO : " . $key . " </td>";
                $salida .= "		<td colspan=\"4\width=\"30%\">DESCRIPCION: " . $key1 . "</td>";
                $salida .= "	</tr>\n";
                $salida .= "	<tr class=\"modulo_table_list_title\">\n";
                $salida .= "		<td width=\"3%\">CANTIDAD EXISTENTE</td>";
                $salida .= "		<td width=\"4%\">CANTIDAD SOLICITADA</td>";
                $salida .= "		<td width=\"4%\">CANTIDAD</td>";
                $salida .= "		<td width=\"4%\">PENDIENTE</td>";
                $salida .= "		<td width=\"4%\">LOTE</td>";
                $salida .= "		<td width=\"10%\">FECHA VENCIMIENTO</td>";
                $salida .= "		<td width=\"5%\">OP</td>";
                $salida .= "	</tr>\n";
                $salida .= "  <input type=\"hidden\" id=\"codigo_proveedor_id\" value='" . $datos[0]['codigo_proveedor_id'] . "'>";

                $c = $k = 0;
                $color = ModuloGetVar('app', 'ReportesInventariosGral', 'color_producto_pendiente');
                $bloqueo = ModuloGetVar('app', 'ReportesInventariosGral', 'color_producto_bloqueo_envio');
                $indice = date("w");
                /* if($indice == 0) $indice = 7;//Restringe los despachos los Domingos
                  else $indice--; */

                foreach ($valor2 as $key3 => $valor3) {
                    $cnt = $existencias[$valor3['codigo_producto']]['cantidad'];
                    if ($cnt == "")
                        $cnt = $valor3['cantidad_solic'];

                    $envio = explode(":", $valor3['dias_envio']);

                    if ($i % 2 == 0)
                        $estilo = "class=\"modulo_list_claro\"";
                    else
                        $estilo = "class=\"modulo_list_oscuro\"";
                    //$salida .= "<pre>".print_r($valor3,true)."</pre>";  
                    $Detalle = $objClass->ConsulPedidoSw($valor3['codigo_producto'], $valor3['solicitud_prod_a_bod_ppal_id']);
                    if ($Detalle[$k]['solicitud_prod_a_bod_ppal_id'] == $valor3['solicitud_prod_a_bod_ppal_id'] && $Detalle[$k]['codigo_producto'] == $valor3['codigo_producto'] && $Detalle[$k]['fecha_vencimiento'] == $valor3['fecha_vencimiento'] && $Detalle[$k]['lote'] == $valor3['lote'] && $Detalle[$k]['sw_pendiente'] == 1)
                        $salida .= "<tr style=\"background:#A9D0F5\"class=\"$estilo\" id=\"capa1$i\">\n";
                    else {
                        if ($envio[$indice] == '1')
                            $estilo = ($cnt < $valor3['cantidad_solic']) ? "style=\"background:" . $color . "\"" : $estilo;
                        else
                            $estilo = "style=\"background:" . $bloqueo . "\"";

                        $salida .= "<tr " . $estilo . " id=\"capa1$i\">\n";
                    }
                    //$salida .= "	<td>".$valor3['codigo_producto']."</td>\n";
                    //$salida .= "	<td>".$valor3['descripcion']."</td>\n";
                    //$salida .= "	<td>".$valor['unidad_id']."</td>\n";
                    //$salida .= "	<td>".$valor['solicitud_prod_a_bod_ppal_id']."</td>";
                    $salida .= "	<td>" . FormatoValor($valor3['existencia_actual']) . "</td>";
                    $salida .= "	<td>" . FormatoValor($valor3['cantidad_solic']) . "</td>";
                    $salida .= "  <input type=\"hidden\" id=\"id_soli \" value=\'" . $valor3['solicitud_prod_a_bod_ppal_det_id'] . "'>";
                    $salida .= "  <td>\n";

                    if ($Detalle[$k]['solicitud_prod_a_bod_ppal_id'] == $valor3['solicitud_prod_a_bod_ppal_id'] && $Detalle[$k]['codigo_producto'] == $valor3['codigo_producto'] && $Detalle[$k]['fecha_vencimiento'] == $valor3['fecha_vencimiento'] && $Detalle[$k]['lote'] == $valor3['lote'])
                        $salida .= "  <input type=\"text\" class=\"input-text\" name=\"cantida_sol\"id=\"cantida_sol\"value=\"" . $Detalle['cantidad_solic'] . "\" disabled>\n";
                    else
                        $salida .= "	<input type=\"text\" class=\"input-text\" id=\"ucantidad$i\" value=\"" . $cnt . "\" size=\"10\" onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('ucantidad$i',xGetElementById('ucantidad$i').value,'" . $valor3['cantidad'] . "','hell$i');\">";

                    $salida .= "  </td>\n";
                    $pendiente = $valor3['cantidad_solic'] - $cnt;
                    /* if($pendiente >=0) */
                    $salida .= "	<td align=\"right\">" . FormatoValor($pendiente) . "</td>";
                    $salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"lote\"id=\"lote\"value=\"" . $valor3['lote'] . "\" disabled></td>\n";


                    /*
                     * Para Sacar los numeros de días entre fechas
                     */
                    $fecha = $valor3['fecha_vencimiento'];  //esta es la que viene de la DB
                    list($ano, $mes, $dia) = split('[/.-]', $fecha);
                    $fecha = $mes . "/" . $dia . "/" . $ano;

                    $fecha_actual = date("m/d/Y");
                    $fecha_compara_actual = date("Y-m-d");
                    //Mes/Dia/Año  "02/02/2010
                    $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual)) / 86400);
                    $colores['PV'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_proximo_vencer');
                    $colores['VN'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_vencido');

                    $fecha_uno_act = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                    $fecha_dos = mktime(0, 0, 0, $mes, $dia, $ano);
                    $color = "";
                    if ($int_nodias < $fech_vencmodulo) {
                        $color = "style=\"background:" . $colores['PV'] . "\"";
                    }

                    if ($fecha_dos <= $fecha_uno_act) {
                        $color = "style=\"background:" . $colores['VN'] . "\"";
                    }

                    $salida .= "  <td><input " . $color . " type=\"text\" class=\"input-text\" name=\"fecha_vencimiento\"id=\"fecha_vencimiento\"value=\"" . $valor3['fecha_vencimiento'] . "\" disabled></td>\n";


                    /* if($dtl['sw_requiereautorizacion_despachospedidos']=='1')
                      $html .= " <img title=\"EL PRODUCTO REQUIERE AUTORIZACION PARA SER DESPACHADO\" src=\"".GetThemePath()."/images/alarma.gif\" border='0' >	"; */
                    /* $salida .= "	<td >\n"; */
                    $salida .= "	<td id=\"checkear$i\">\n";
                    $salida .= "		<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
                    $salida .= "			<tr>";
                    $salida .= "				<td width=\"50%\" align=\"center\">";
                    if (!empty($datosItem[$valor3['codigo_producto']][$valor3['lote']][$valor3['fecha_vencimiento']]) || !empty($datosItemsAutorizar[$valor3['codigo_producto']][$valor3['lote']][$valor3['fecha_vencimiento']])) {
                        $salida .= "    <a class=\"label_error\">\n";
                        $salida .= "    	<img src=\"" . GetThemePath() . "/images/checksi.png\" border=\"0\" width=\"15\" height=\"15\">\n";
                        $salida .= "    </a>\n";
                        $salida .= "					" . $datosItemsAutorizar[$valor3['codigo_producto']][$valor3['lote']][$valor3['fecha_vencimiento']]['mensaje'];
                    } else {
                        /* $salida .= "	<td id=\"checkear$i\">\n"; */
                        if ($envio[$indice] == '1') {
                            $salida .= "    <a class=\"label_error\" href=\"javascript:AgregarItem('$doc_tmp_id','" . $valor3['codigo_producto'] . "',xGetElementById('ucantidad$i').value,'" . $valor3['costo'] . "','" . $valor3['porc_iva'] . "','$bodegas_doc_id','checkear$i','" . $valor3['lote'] . "','" . $valor3['fecha_vencimiento'] . "','" . $valor3['item_id'] . "','" . $valor3['existencia_actual'] . "','" . $identificador . "','" . $valor3['cantidad_solic'] . "','" . $valor3['sw_requiereautorizacion_despachospedidos'] . "');\">\n";
                            $salida .= "      <img id=\"hell$i\" src=\"" . GetThemePath() . "/images/checkno.png\" border=\"0\" width=\"15\" height=\"15\">SEL\n";
                            $salida .= "    </a>\n";
                        }
                    }
                    $salida .= "				</td>";
                    $salida .= "				<td width=\"50%\" align=\"center\">";
                    if ($valor3['sw_requiereautorizacion_despachospedidos'] == '1')
                        $salida .= " 				<img title=\"EL PRODUCTO REQUIERE AUTORIZACION PARA SER DESPACHADO\" src=\"" . GetThemePath() . "/images/alarma.gif\" border='0' >	";
                    $salida .= "				</td>";
                    $salida .= "			</tr>";
                    $salida .= "		</table>";
                    $salida .= "  </td>\n";
                    $salida .= "</tr>\n";
                    $i++;
                    $k++;
                    //se desplegará Formulario para un producto adicional
                    $salida .= " <tr class=\"$estilo\">\n";
                    $salida .= "    <td colspan=\"11\">\n";
                    $salida .= "      <a name=\"#producto_adicional" . $valor3['codigo_producto'] . "" . $valor3['cantidad_solic'] . "\"></a>\n";
                    $salida .= "      <div id=\"producto_adicional" . $valor3['codigo_producto'] . "" . $valor3['cantidad_solic'] . "\"></div>\n";
                    $salida .= "    </td>\n";
                    $salida .= "  </tr>\n";

                    //$PendientesProducto=$objClass->SacarProductosFarmaciaS($empresa_id,$valor['codigo_producto']);
                }
            }
            $salida .= "</table>";
            $salida .= "<br>";
        }
    }
    else {
        $salida .= "<center>\n";
        $salida .= "  <label class=\"label_error\">\n";
        $salida .= "    EL PRODUCTO BUSCADO NO HACE PARTE DEL PEDIDO, FAVOR REVISAR PARAMETROS DE LA BUSQUEDA\n";
        $salida .= "  </label>\n";
        $salida .= "</center>\n";
    }
    /* $salida .= "</table><br>"; */

    $limite = 10;
    $salida.= "" . ObtenerPaginado($pagina, GetThemePath(), $conteo, 1, $tipo_param, $param, $doc_tmp_id, $bodegas_doc_id, $limite);
    $salida .= "";

    return $salida;
}

function ListadoProductosCliente_HTML($datos, $datos2, $pagina, $tipo_param, $param, $conteo, $doc_tmp_id, $bodegas_doc_id, $empresa_id, $centro_utilidad, $bodega, $productos, $identificador) {
    $objClass = new doc_bodegas_E008();
    $consulta1 = new MovBodegasSQL();

    $fech_vencmodulo = ModuloGetVar('app', 'AdminFarmacia', 'dias_vencimiento_product_bodega_farmacia_' . SessionGetVar("EMPRESA"));
    $salida .= "<br>";
    $salida .= "<div id=\"cabecera_facturaventa\"></div>";
    /* print_r($datos); */
    if (!empty($datos)) {
        $salida .= "<center><label class=\"label_error\">Resultado(s) Encontrado(s).. (" . sizeof($datos) . ")</label></center>";
        foreach ($datos as $k => $valor) {
            $salida .= "                 <form id=\"forma" . $valor['pedido_cliente_id'] . "@" . $valor['codigo_producto'] . "\" name=\"" . $valor['pedido_cliente_id'] . "@" . $valor['codigo_producto'] . "\" action=\"\" method=\"post\">\n";
            $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td width=\"50%\">PRODUCTO: " . $valor['codigo_producto'] . " " . $valor['descripcion'] . ". </td><td>CANTIDAD SOLICITADA <input readonly=\"true\" type=\"input-text\" name=\"cantidad_solicitada\" id=\"cantidad_solicitada\" value=\"" . FormatoValor($valor['cantidad_total'], 0) . "\" class=\"input-text\"></td><td>CANTIDAD PENDIENTE <input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" id=\"cantidad_pendiente\" value=\"" . FormatoValor($valor['cantidad_pendiente'], 0) . "\" class=\"input-text\"></td>\n";
            $salida .= "                        <input type=\"hidden\" name=\"pedido_cliente_id\" id=\"pedido_cliente_id\" value=\"" . $valor['pedido_cliente_id'] . "\">";
            $salida .= "                        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"" . $valor['codigo_producto'] . "\">";
            $salida .= "                        <input type=\"hidden\" name=\"valor\" id=\"valor\" value=\"" . $valor['valor_unitario'] . "\">";
            $salida .= "                        <input type=\"hidden\" name=\"porc_iva\" id=\"porc_iva\" value=\"" . $valor['porc_iva'] . "\">";
            $salida .= "                      </td>";
            $salida .= "                    </tr>\n";

            $salida .= "                    <tr class=\"modulo_list_claro\">\n";
            $salida .="                       <td colspan=\"3\" align=\"center\">";
            $Existencias = $objClass->Consultar_ExistenciasBodegas($valor['codigo_producto'], SessionGetVar("empresa_id"), SessionGetVar("centro_utilidad"), SessionGetVar("bodega"), $doc_tmp_id);
            //print_r($Existencias);

            $salida .= "                                    <table width=\"80%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
            $salida .= "                                        <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                                        <td width=\"20%\">";
            $salida .= "                                              LOTE";
            $salida .= "                                        </td>";
            $salida .= "                                        <td width=\"20%\">";
            $salida .= "                                              FECHA VENCIMIENTO";
            $salida .= "                                        </td>";
            $salida .= "                                        <td width=\"5%\">";
            $salida .= "                                              EXISTENCIA";
            $salida .= "                                        </td>";
            $salida .= "                                        <td width=\"5%\">";
            $salida .= "                                              CANTIDAD";
            $salida .= "                                        </td>";
            $salida .= "                                        <td width=\"5%\">";
            $salida .= "                                              SEL";
            $salida .= "                                        </td>";
            $salida .= "                                        </tr>\n";
            $i = 0;
            foreach ($Existencias as $key => $v) {
                /* $ProductoLote=$consulta->Buscar_ProductoLote($FormularioBuscador['doc_tmp_id'],$valor['codigo_producto'],$v['lote']); */

                /* Para Nomenclatura de Productos a Vencer y Proximos a Vencer */
                $fech_vencmodulo = ModuloGetVar('app', 'AdminFarmacia', 'dias_vencimiento_product_bodega_farmacia_' . SessionGetVar("empresa_id"));
                //$fech_vencmodulo=ModuloGetVar('app','AdministracionFarmacia','dias_vencimiento_product_bodega_farmacia_02');
                /*
                 * Para Sacar los numeros de días entre fechas
                 */
                $fecha = $v['fecha_vencimiento'];  //esta es la que viene de la DB
                list($ano, $mes, $dia) = split('[/.-]', $fecha);
                $fecha = $mes . "/" . $dia . "/" . $ano;

                $fecha_actual = date("m/d/Y");
                $fecha_compara_actual = date("Y-m-d");
                //Mes/Dia/Año  "02/02/2010
                $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual)) / 86400);
                $colores['PV'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_proximo_vencer');
                $colores['VN'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_vencido');

                $fecha_uno_act = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                $fecha_dos = mktime(0, 0, 0, $mes, $dia, $ano);
                $color = " style=\"width:100%\" ";
                $vencido = 0;
                if ($int_nodias < $fech_vencmodulo) {
                    $color = "style=\"width:100%;background:" . $colores['PV'] . ";\"";
                    $vencido = 0;
                }

                if ($fecha_dos <= $fecha_uno_act) {
                    $color = "style=\"width:100%;background:" . $colores['VN'] . ";\"";
                    $vencido = 1;
                }


                $salida .= "                                        <tr class=\"modulo_list_claro\">";
                $salida .= "                                            <td>";
                $salida .= "                                              <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"" . $v['lote'] . "\" name=\"lote" . $i . "\" id=\"lote" . $i . "\" >";
                $salida .= "                                            </td>";
                $salida .= "                                            <td>";
                $fecha_vencimiento = explode("-", $v['fecha_vencimiento']);
                $fechavencimiento = $fecha_vencimiento[2] . "-" . $fecha_vencimiento[1] . "-" . $fecha_vencimiento[0];
                $salida .= "                                              <input " . $color . "  type=\"text\" readonly=\"text\" class=\"input-text\" value=\"" . $fechavencimiento . "\" name=\"fecha_vencimiento" . $i . "\" id=\"fecha_vencimiento" . $i . "\" >";
                $salida .= "                                            </td>";
                $salida .= "                                            <td>";
                $salida .= "                                              <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"" . $v['existencia_actual'] . "\" name=\"existencia_actual" . $i . "\" id=\"existencia_actual" . $i . "\" >";
                $salida .= "                                            </td>";
                $salida .= "                                            <td>";
                $salida .= "                                              <input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"cantidad" . $i . "\" id=\"cantidad" . $valor['pedido_cliente_id'] . "@" . $valor['codigo_producto'] . "" . $i . "\" onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('cantidad" . $valor['pedido_cliente_id'] . "@" . $valor['codigo_producto'] . "" . $i . "',xGetElementById('cantidad" . $valor['pedido_cliente_id'] . "@" . $valor['codigo_producto'] . "" . $i . "').value,'" . $v['existencia_actual'] . "','hell$i');\">";
                $salida .= "                                            </td>";
                $salida .= "                                            <td>";
                $salida .= "													<table width=\"100%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
                $salida .= "														<tr>";
                $salida .= "															<td width=\"50%\" align=\"center\">";
                if ($vencido != 1)
                    $salida .= "                                              				<input " . $v['bloqueo'] . " style=\"width:100%\" type=\"checkbox\" class=\"input-text\" name=\"" . $i . "\" id=\"" . $i . "\" value=\"" . $i . "\" >";
                $salida .= "																	" . $v['mensaje'];
                $salida .= "                                              				<input type=\"hidden\" name=\"sw_requiereautorizacion" . $i . "\" id=\"sw_requiereautorizacion" . $i . "\" value=\"" . $v['sw_requiereautorizacion_despachospedidos'] . "\" >";
                $salida .= "                                              				<input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"" . $i . "\" >";
                $salida .= "															</td>";
                $salida .= "															<td width=\"50%\" align=\"center\">";
                if ($v['sw_requiereautorizacion_despachospedidos'] == '1')
                    $salida .= " 															<img title=\"EL PRODUCTO REQUIERE AUTORIZACION PARA SER DESPACHADO\" src=\"" . GetThemePath() . "/images/alarma.gif\" border='0' >	";
                $salida .= "															</td>";
                $salida .= "														</tr>";
                $salida .= "													</table>";
                $salida .= "                                            </td>";
                $salida .= "                                        </tr>";
                $i++;
            }
            $salida .= "                                        <tr>";
            $salida .= "                                            <td colspan=\"4\" align=\"center\">";
            $salida .= "													<div class=\"label_error\" id=\"" . $valor['pedido_cliente_id'] . "@" . $valor['codigo_producto'] . "\"></div>";
            $salida .= "                                            </td>";
            $salida .= "                                        </tr>";
            $salida .= "                                    </table>\n";
            $salida .="                       </td>";
            $salida .= "                    </tr>\n";
            $salida .= "                                        <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                                        <td width=\"20%\" colspan=\"3\" align=\"center\">";
            $salida .= "												<input type=\"hidden\" name=\"doc_tmp_id\" id=\"doc_tmp_id\" value=\"" . $doc_tmp_id . "\">";
            $salida .= "												<input type=\"hidden\" name=\"bodegas_doc_id\" id=\"bodegas_doc_id\" value=\"" . $bodegas_doc_id . "\">";
            $salida .= "                                              <input class=\"input-submit\" type=\"button\" value=\"GUARDAR TEMPORAL\" onclick=\"xajax_GuardarPT(xajax.getFormValues('forma" . $valor['pedido_cliente_id'] . "@" . $valor['codigo_producto'] . "'));\">";
            $salida .= "                                        </td>";
            $salida .= "                                        </tr>\n";

            $salida .= "                </table>\n";
            $salida .= "              </form>";
            $salida .= "                <br>\n";
        }
    }
    else {
        $salida .= "<center>\n";
        $salida .= "  <label class=\"label_error\">\n";
        $salida .= "    EL PRODUCTO BUSCADO NO HACE PARTE DEL PEDIDO, FAVOR REVISAR PARAMETROS DE LA BUSQUEDA\n";
        $salida .= "  </label>\n";
        $salida .= "</center>\n";
    }
    $salida .= "</table><br>";

    $limite = 10;
    $salida.= "" . ObtenerPaginado($pagina, GetThemePath(), $conteo, 1, $tipo_param, $param, $doc_tmp_id, $bodegas_doc_id, $limite);
    $salida .= "";

    return $salida;
}

function GuardarPT($Formulario) {
    $objResponse = new xajaxResponse();
    $consulta = new doc_bodegas_E008();
    $k = 0;
    $fech_vencmodulo = ModuloGetVar('app', 'AdminFarmacia', 'dias_vencimiento_product_bodega_farmacia_' . SessionGetVar("empresa_id"));
    for ($i = 0; $i <= $Formulario['registros']; $i++) {

        if ($Formulario[$i] != "") {
            $aumento = " and a.codigo_producto = '" . $Formulario['codigo_producto'] . "'  ";

            $cantidad = $consulta->ObtenerProductosPedidoCliente($Formulario['doc_tmp_id'], UserGetUID(), $Formulario['pedido_cliente_id'], SessionGetVar("empresa_id"), $param, $aumento, SessionGetVar("centro_utilidad"), SessionGetVar("bodega"));
            /* print_r($cantidad); */
            /*
             * Para Sacar los numeros de días entre fechas
             */
            list($dia_, $mes_, $ano_) = split('[/.-]', $Formulario['fecha_vencimiento' . $i]);
            $fecha_vencimiento = $ano_ . "/" . $mes_ . "/" . $dia_;

            $fecha = $fecha_vencimiento;  //esta es la que viene de la DB
            list($ano, $mes, $dia) = split('[/.-]', $fecha);
            $fecha = $mes . "/" . $dia . "/" . $ano;

            $fecha_actual = date("m/d/Y");
            $fecha_compara_actual = date("Y-m-d");
            //Mes/Dia/Año  "02/02/2010
            $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual)) / 86400);

            $fecha_uno_act = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $fecha_dos = mktime(0, 0, 0, $mes, $dia, $ano);
            $color = "";
            if ($int_nodias < $fech_vencmodulo) {
                $objResponse->alert("EL PRODUCTO QUE INTENTA INGRESAR, EST�? PROXIMO A VENCER!!");
            }



            //print_r($Formulario['cantidad'.$i]);
            if ($Formulario['cantidad' . $i] <= $cantidad[0]['cantidad_pendiente']) {
                if ($Formulario['cantidad' . $i] == "") {
                    $objResponse->assign('error_doc', "innerHTML", "NO HA DILIGENCIADO UNA CANTIDAD A INGRESAR");
                }
                if ($Formulario['sw_requiereautorizacion' . $i] == '0')
                    $Retorno = $consulta->GuardarTemporal($Formulario['bodegas_doc_id'], $Formulario['doc_tmp_id'], $Formulario['codigo_producto'], $Formulario['cantidad' . $i], $Formulario['porc_iva'], $Formulario['valor'] * $Formulario['cantidad' . $i], UserGetUID(), $Formulario['fecha_vencimiento' . $i], $Formulario['lote' . $i]);
                else
                    $Retorno = $consulta->GuardarTemporalAutorizacion(SessionGetVar("empresa_id"), SessionGetVar("centro_utilidad"), SessionGetVar("bodega"), $Formulario['doc_tmp_id'], $Formulario['codigo_producto'], $Formulario['cantidad' . $i], $Formulario['valor'] * $Formulario['cantidad' . $i], $Formulario['fecha_vencimiento' . $i], $Formulario['lote' . $i], $Formulario['porc_iva']);
                $objResponse->assign("" . $Formulario['orden_requisicion_id'] . "@" . $Formulario['codigo_producto'] . "", "innerHTML", $consulta->mensajeDeError);


                if ($Retorno)
                    $k++;
            }
        }
    }

    if ($k > 0) {
        $fnc = "BuscarProductos('1',document.buscador.tip_bus.value,document.buscador.criterio.value,'" . $Formulario['doc_tmp_id'] . "','" . $Formulario['bodegas_doc_id'] . "','" . SessionGetVar("empresa_id") . "','" . SessionGetVar("centro_utilidad") . "','CL');";
        $objResponse->script($fnc);
        $objResponse->script("xajax_GetItems('" . $Formulario['doc_tmp_id'] . "','" . $Formulario['bodegas_doc_id'] . "','CL');");
        /* $objResponse->script("xajax_MostrarProductox('".$Formulario['bodegas_doc_id']."','".$Formulario['doc_tmp_id']."',".UserGetUID().");"); */
    }
    if ($Retorno === false) {
        $objResponse->assign('error_doc', 'innerHTML', $consulta->mensajeDeError);
    }

    /* $objResponse->assign("tablaoide","innerHTML",$salida);
      $objResponse->script("Clear();"); */
    return $objResponse;
}

//ActuPendiente('".$valor['codigo_producto']."','".$valor['descripcion']."',xGetElementById('ucantidad$i').value,'".$valor['solicitud_prod_a_bod_ppal_id']."','".$valor['lote']."','".$valor['fecha_vencimiento']."','".$doc_tmp_id."','".$bodegas_doc_id."')
function ActuPendiente($CodigoProducto, $descripcion, $Cantidades, $num_pedido, $lote, $fecha_venc, $doc_tmp_id, $bodegas_doc_id) {
    $objResponse = new xajaxResponse();
    $objClass = new doc_bodegas_E008;
    $Detalle = $objClass->ConsulPedidoDetalle($CodigoProducto, $num_pedido);
    // print_r($Cantidades);
    //print_r($Detalle);
    /*
     * Insertar un producto en la orden de compra que simule la entrada de un mismo producto pero con el mismo lote.
     *
     */
    $sql = $objClass->IngresarProductoPedido($CodigoProducto, $Cantidades, $num_pedido, $Detalle['farmacia_id'], $Detalle['centro_utilidad'], $Detalle['bodega'], $Detalle['tipo_producto'], $lote, $fecha_venc);

    $sql2 = $objClass->ModificarProductoPedido($CodigoProducto, $Cantidades, $num_pedido, $Detalle['farmacia_id'], $Detalle['centro_utilidad'], $Detalle['bodega'], $lote, $fecha_venc, $Detalle['solicitud_prod_a_bod_ppal_det_id']);

    $objResponse->script("BuscarProductos('1','0','0','" . $doc_tmp_id . "','" . $bodegas_doc_id . "','" . SessionGetVar("EMPRESA") . "','01');");

    return $objResponse;
}

function MostrarProductoPedFarm($bodegas_doc_id, $doc_tmp_id, $usuario_id, $datos) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new doc_bodegas_E008();
    $consulta1 = new MovBodegasSQL();
    $datos = $consulta->TraerDatos($bodegas_doc_id);
    $pedido = $consulta1->FarmaciaPedidosTmp($doc_tmp_id);
    //print_r($pedido);
    //print_r($datos."sjdkjsk");
    $vector = $consulta->SacarProductosFarmacia($doc_tmp_id, $usuario_id, $pedido['solicitud_prod_a_bod_ppal_id']);
    //var_dump($vector);
    if (!empty($vector)) {
        $salida .= " <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "  <tr class=\"modulo_table_list_title\">\n";
        $salida .= "   <td align=\"center\" width=\"12%\">\n";
        $salida .= "    <a title='CODIGO DEL PRODUCTO'>CODIGO<a> ";
        $salida .= "   </td>\n";
        //$salida .= "<pre>".print_r($datos,true)."</pre>";
        $salida .= "   <td align=\"center\" width=\"30%\">\n";
        $salida .= "    <a title='DESCRIPCION DEL PRODUCTO'>DESCRIPCION<a>";
        $salida .= "   </td>\n";
        $salida .= "   <td align=\"center\" width=\"30%\">\n";
        $salida .= "    <a title='FECHA VENCIMIENTO'>FECHA VENCIMIENTO<a>";
        $salida .= "   </td>\n";
        $salida .= "   <td align=\"center\" width=\"15%\">\n";
        $salida .= "    <a title='LOTE'>LOTE<a>";
        $salida .= "   </td>\n";
        $salida .= "   <td align=\"center\" width=\"12%\">\n";
        $salida .= "    <a title='UNIDAD DEL PRODUCTO'>UNIDAD<a>";
        $salida .= "   </td>\n";
        $salida .= "   <td align=\"center\" width=\"12%\">\n";
        $salida .= "    CANTIDAD SOLICITADA";
        $salida .= "   </td>\n";

        $salida .= "   <td align=\"center\" width=\"2%\">\n";
        $salida .= "    <a title='CANTIDAD'>CANTIDAD<a>";
        $salida .= "   </td>\n";
        $salida .= "   </tr>\n";
        foreach ($vector as $valor => $productos) {
            $tr = $bodegas_doc_id . "@" . $productos['doc_tmp_id'];
            $salida .= " <tr id='" . $tr . "' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
            $salida .= "  <td align=\"left\" class=\"label_mark\">\n";
            $salida .= "   " . $productos['codigo_producto'];
            $salida .= "  </td>\n";
            $salida .= "  <td align=\"left\" class=\"label_mark\">\n";
            $salida .= "   " . $productos['descripcion'];
            $salida .= "  </td>\n";
            $salida .= "  <td  width='10%'  align=\"left\" class=\"modulo_list_claro\">\n";
            $salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecha_vencimiento\" id=\"fecha_vencimiento\" value=\"\" onkeypress=\"return acceptNum(event)\" size=\"10\" disabled>\n";
            $salida .= "   </td>\n";
            $salida .= "   <td  width='10%'  align=\"left\" class=\"modulo_list_claro\">\n";
            $salida .= "      <input type=\"text\" class=\"input-text\" name=\"lote\" id=\"lote\" value=\"\" onkeypress=\"return acceptNum(event)\" size=\"8\" disabled>\n";
            $salida .= "   </td>\n";
            $salida .= "  <td align=\"left\" class=\"label_mark\">\n";
            $salida .= "   " . $productos['descripcion_unidad'];
            $salida .= "  </td>\n";
            $salida .= "  <td align=\"left\" class=\"label_mark\">\n";
            $salida .= "   " . $productos['cantidad_solic'];
            $salida .= "  </td>\n";
            $salida .= "   <td  width='10%'  align=\"left\" class=\"modulo_list_claro\">\n";
            $salida .= "      <input type=\"text\" class=\"input-text\" name=\"pedido_farmacia\" id=\"pedido_farmacia\" value=\"\" onkeypress=\"return acceptNum(event)\" size=\"5\" disabled>\n";
            $salida .= "   </td>\n";
            //
            //$salida .= "   <td width='15%' align=\"center\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
            //$salida .= "         <a href=\"#\" onclick=\"xajax_BuscarProductoFarm('".$datos['empresa_id']."','".$datos['centro_utilidad']."','".$datos['bodega']."','0','0','1')\" class=\"label_error\">BUSCADOR PRODUCTO</a>\n";
            //////BuscarProductoFarm($empresa_id,$centro_utilidad,$bodega,$tip_bus,$criterio,$offset)
            //$java = "javascript:MostrarCapa('ContenedorBus');Bus_Pro('".$datos['empresa_id']."','".$datos['centro_utilidad']."','".$datos['bodega']."','0','0','1');Iniciar4('BUSCAR PRODUCTO');Clear3000();\"";
            //$salida .= "                         <a title='BUSCADOR PRODUCTO' class=\"label_error\" href=\"".$java."\">\n";
            //$salida .= "                          BUSCAR PRODUCTO\n";
            //$salida .= "                         </a>\n";
            //$salida .= "                       </td>\n";     
            $salida .= " </tr>\n";
        }

        $salida .= "                    </table>\n";
        //$objResponse->call("super");
    } else {
        $salida .= "                  <table width=\"80%\" align=\"center\">\n";
        $salida .= "                  <tr>\n";
        $salida .= "                  <td align=\"center\">\n";
        $salida .= "                      <label class='label_error'> ESTE DOCUMENTO NO TIENE PRODUCTOS ASIGNADOS</label>";
        $salida .= "                  </td>\n";
        $salida .= "                  </tr>\n";
        $salida .= "                  </table>\n";
    }
    $objResponse->assign("farmacia_ped", "innerHTML", $salida);

    return $objResponse;
}

function ObtenerPaginado($pagina, $path, $slc, $op, $orden, $tipo_param, $param, $doc_tmp_id, $bodegas_doc_id, $limite) {
    $TotalRegistros = $slc;
    $TablaPaginado = "";

    if ($limite == null) {
        $uid = UserGetUID();
        $LimitRow = intval(GetLimitBrowser());
    } else {
        $LimitRow = $limite;
    }
    if ($TotalRegistros > 0) {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros / $LimitRow);

        if ($TotalRegistros % $LimitRow > 0) {
            $NumeroPaginas++;
        }

        $Inicio = $pagina;
        if ($NumeroPaginas - $pagina < 9) {
            $Inicio = $NumeroPaginas - 9;
        } elseif ($pagina > 1) {
            $Inicio = $pagina - 1;
        }

        if ($Inicio <= 0) {
            $Inicio = 1;
        }

        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

        $TablaPaginado .= "<tr>\n";
        if ($NumeroPaginas > 1) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Paginas:</td>\n";
            if ($pagina > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                //     na,criterio1,criterio2,criterio,div,forma
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarProductos('1','" . $orden . "','" . $tipo_param . "','" . $param . "','$doc_tmp_id','$bodegas_doc_id')\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarProductos('" . ($pagina - 1) . "','" . $orden . "','" . $tipo_param . "','" . $param . "','$doc_tmp_id','$bodegas_doc_id')\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $Fin = $NumeroPaginas + 1;
            if ($NumeroPaginas > 10) {
                $Fin = 10 + $Inicio;
            }

            for ($i = $Inicio; $i < $Fin; $i++) {
                if ($i == $pagina) {
                    $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                } else {
                    $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BuscarProductos('" . $i . "','" . $orden . "','" . $tipo_param . "','" . $param . "','$doc_tmp_id','$bodegas_doc_id')\">" . $i . "</a></td>\n";
                }
                $columnas++;
            }
        }
        if ($pagina < $NumeroPaginas) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarProductos('" . ($pagina + 1) . "','" . $orden . "','" . $tipo_param . "','" . $param . "','$doc_tmp_id','$bodegas_doc_id')\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BuscarProductos('" . $NumeroPaginas . "','" . $orden . "','" . $tipo_param . "','" . $param . "','$doc_tmp_id','$bodegas_doc_id')\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
        $aviso .= "     Pagina&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
        $aviso .= "   </tr>\n";

        if ($op == 2) {
            $TablaPaginado .= $aviso;
        } else {
            $TablaPaginado = $aviso . $TablaPaginado;
        }
    }
    $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
    $Tabla .= $TablaPaginado;
    $Tabla .= "</table>";


    return $Tabla;
}

/* * *************************************************************
 * ELIMINAR AJUSTES DE PRODUCTOS
 * ************************************************************* */

function BorrarAjuste($tr, $item) {
    $consulta = new doc_bodegas_E008();
    $objResponse = new xajaxResponse();
    $buscar = $consulta->EliminarItem($tr, $item);
    if ($buscar == 1) {
        $objResponse->alert("EL ITEM $item FUE ELIMINADO EXITOSAMENTE");
        $objResponse->remove($tr);
    } else {
        $objResponse->alert("NO SE PUEDE BORRAR");
    }

    return $objResponse;
}

/**
 * Funcion donde se elimina un item de la lista de seleccionado
 *
 */
function RemoverItem($doc_tmp_id, $bodegas_doc_id, $item, $identificador) {
    $consulta = new doc_bodegas_E008();
    $objResponse = new xajaxResponse();
    $buscar = $consulta->RemoverItem($bodegas_doc_id, $item);
    if (!$buscar) {
        $objResponse->alert($consulta->mensajeDeError);
        return $objResponse;
    }
    //$datosItem = $objClass->ConsultarItemsExistencias($codigo,SessionGetVar('Empresa_id'),SessionGetVar('centro_utilidad'),SessionGetVar('bodega'),$lote,$fecha_vencimiento);
    /* $datosItem = $consulta->ConsultarItems($doc_tmp_id,$bodegas_doc_id);
      $html = "";
      if(!empty($datosItem))
      { */
    //$existencias = $consulta->ObtnerCantidadesIngresadasTodos($doc_tmp_id);
    //$html = FormaItems_HTML($datosItem,$doc_tmp_id,$bodegas_doc_id,$identificador,$existencias);
    $objResponse->script("xajax_GetItems('" . $doc_tmp_id . "','" . $bodegas_doc_id . "','" . $identificador . "');");
    //$objResponse->assign("crearDoc","style.display","block");
    //}
    $objResponse->assign("listadoP", "innerHTML", $html);
    $objResponse->alert("EL ITEM $item FUE ELIMINADO EXITOSAMENTE");

    $datox = $consulta->DatosParaEditar($doc_tmp_id, UserGetUID());
    $html = "BuscarProductos('1',document.buscador.tip_bus.value,document.buscador.criterio.value,'" . $doc_tmp_id . "','" . $bodegas_doc_id . "','" . $datox['empresa_id'] . "','" . $datosx['centro_utilidad'] . "','" . $identificador . "');";
    $objResponse->script($html);

    return $objResponse;
}

FUNCTION Borrar($tr, $item, $CONTENIDOR) {
    $objResponse = new xajaxResponse();
    $da .= "      <table width='100%' border='0'>\n";
    $da .= "       <tr>\n";
    $da .= "        <td colspan='2' class=\"label_error\">\n";
    $da .= "          ESTA SEGURO DE ELIMINAR ESTE PRODUCTO ?";
    $da .= "        </td>\n";
    $da .= "       </tr>\n";
    $da .= "       <tr>\n";
    $da .= "        <td align='center' colspan='2'>\n";
    $da .= "          &nbsp;";
    $da .= "        </td>\n";
    $da .= "       </tr>\n";
    $da .= "       <tr>\n";
    $da .= "        <td align='center'>\n";
    $C = substr($CONTENIDOR, (strlen($CONTENIDOR) - 2), 2);
    $da .= "          <input type=\"button\" class=\"input-submit\" value=\"ELIMINAR\" name=\"ELIMINAR_MOV\" onclick=\"xajax_BorrarAjuste('" . $tr . "','" . $item . "');Cerrar('Contenedor" . $C . "');\">\n";
    $da .= "        </td>\n";
    $da .= "        <td align='center'>\n";
    $da .= "          <input type=\"button\" class=\"input-submit\" value=\"CANCELAR\" name=\"CANCELAR\" onclick=\"Cerrar('Contenedor" . $C . "');\">\n";
    $da .= "        </td>\n";
    $da .= "       </tr>\n";
    $da .= "      </table>\n";
    $objResponse->assign($CONTENIDOR, "innerHTML", $da);
    return $objResponse;
}

/* function GuardarPT($bodegas_doc_id,$doc_tmp_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$usuario_id,$fecha_venc,$lotec)
  {

  $objResponse = new xajaxResponse();

  $path = SessionGetVar("rutaImagenes");
  $consulta=new doc_bodegas_E008();

  $Retorno=$consulta->GuardarTemporal($bodegas_doc_id,$doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id=null,$fecha_venc,$lotec,$total_costo_ped);
  $vector=$consulta->SacarProductosTMP($doc_tmp_id,$usuario_id);
  $objResponse->assign("codigo","value","");
  $objResponse->call("super");

  $path = SessionGetVar("rutaImagenes");
  $salida .= "                  <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
  $salida .= "                    <tr class=\"modulo_table_list_title\">\n";

  $salida .= "                      <td align=\"center\" width=\"12%\">\n";
  $salida .= "                        <a title='CODIGO DEL PRODUCTO'>CODIGO<a> ";
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"center\" width=\"30%\">\n";
  $salida .= "                        <a title='DESCRIPCION DEL PRODUCTO'>DESCRIPCION<a>";
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"center\" width=\"30%\">\n";
  $salida .= "                        <a title='FECHA VENCIMIENTO'>FECHA VENCIMIENTO<a>";
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"center\" width=\"15%\">\n";
  $salida .= "                        <a title='LOTE'>LOTE<a>";
  $salida .= "                      </td>\n";

  $salida .= "                      <td align=\"center\" width=\"12%\">\n";
  $salida .= "                        <a title='UNIDAD DEL PRODUCTO'>dsdUNIDAD<a>";
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"center\" width=\"12%\">\n";
  $salida .= "                        CANTIDAD";
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"center\" width=\"12%\">\n";
  $salida .= "                        <a title='PORCENTAJE DEL GRAVAMEN'> % GRAVAMEN<a>";
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"center\" width=\"20%\">\n";
  $salida .= "                        <a title='COSTO TOTAL'>COSTO<a>";
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"center\" width=\"2%\">\n";
  $salida .= "                        <a title='ELIMINAR REGISTRO'>X<a>";
  $salida .= "                      </td>\n";
  $salida .= "                    </tr>\n";
  foreach($vector as $valor=>$productos)
  {

  $tr=$bodegas_doc_id."@".$productos['doc_tmp_id'];
  $salida .= "                    <tr id='".$tr."' class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
  $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
  $salida .= "                        ".$productos['codigo_producto'];
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
  $salida .= "                         ".$productos['descripcion'];
  $salida .= "                      </td>\n";
  $fech_vencmodulo=ModuloGetVar('app','AdministracionFarmacia','dias_vencimiento_product_bodega_farmacia_02');

  $fecha_actual=date("m/d/Y");

  $fecha =$productos['fecha_vencimiento'];
  list( $dia, $mes, $ano ) = split( '[/.-]', $fecha );
  $fecha = $mes."/".$dia."/".$ano;

  $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
  if($int_nodias<$fech_vencmodulo)
  {
  $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
  $salida .= "                         ".$productos['fecha_vencimiento'];
  $salida .= "                      </td>\n";
  }
  else
  {
  $salida .= "                      <td style=\"background:#A9D0F5\" align=\"left\" class=\"label_mark\">\n";
  $salida .= "                         ".$productos['fecha_vencimiento'];
  $salida .= "                      </td>\n";
  }
  $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
  $salida .= "                         ".$productos['lote'];
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
  $salida .= "                        ".$productos['descripcion_unidad'];
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
  $salida .= "                         ".$productos['cantidad'];
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"right\">\n";
  $salida .= "                        ".$productos['porcentaje_gravamen'];
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"right\" class=\"normal_10AN\">\n";
  $salida .= "                        ".FormatoValor($productos['total_costo']);
  $salida .= "                      </td>\n";
  $salida .= "                      <td align=\"center\">\n";
  $jaxx = "javascript:MostrarCapa('ContenedorB3');BorrarAjustes('".$tr."','".$productos['item_id']."','ContenidoB3');IniciarB3('ELIMINAR REGISTRO');";
  $salida .= "                        <a title='ELIMINAR REGISTRO' href=\"".$jaxx."\">\n";
  $salida .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
  $salida .= "                         </a>\n";
  $salida .= "                      </td>\n";
  $salida .= "                    </tr>\n";

  }

  $salida .= "                    </table>\n";
  $objResponse->assign("tablaoide","innerHTML",$salida);
  return $objResponse;

  } */
/* * **********************************************
 * funcion pra buscar productos
 * *********************************************** */

function BuscarProducto1($empresa_id, $centro_utilidad, $bodega, $tip_bus, $criterio, $offset) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new doc_bodegas_E008();
    //echo $tip_bus; 
    if ($tip_bus == 2) {
        $aumento = "AND b.codigo_producto='" . $criterio . "'";
    } elseif ($tip_bus == 1) {
        $aumento = "AND b.descripcion LIKE '%" . strtoupper($criterio) . "%'";
    } else {
        $aumento = "";
    }

    if ($criterio != "0" && $criterio != "") {
        $busqueda = $consulta->BuscarProducto($empresa_id, $centro_utilidad, $bodega, $aumento, $offset);
        if (!empty($busqueda)) {
            $salida .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
            //$salida .=                     $busqueda;
            //codigo_producto descripcion unidad_id descripcion_unidad
            $salida .= "                 </div>\n";
            $salida .= "                 <form name=\"adicionar\">\n";
            $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td align=\"center\"width=\"15%\">\n";
            $salida .= "                        CODIGO PRODUCTO";
            $salida .= "                      </td>\n";
            //$salida .= "<pre>".print_r($criterio,true)."</pre>";
            $salida .= "                      <td align=\"center\" width=\"35%\">\n";
            $salida .= "                        <a title='DESCRIPCION PRODUCTO'>DESCRIPCION<a> ";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            $salida .= "                        UNIDAD";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            $salida .= "                        EXISTENCIA";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            $salida .= "                        COSTO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"20%\">\n";
            $salida .= "                        FECHA VEN";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            $salida .= "                        LOTE";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        <a title='SELECCIONAR PRODUCTO'>SL<a>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            for ($i = 0; $i < count($busqueda); $i++) {
                $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                        " . $busqueda[$i]['codigo_producto'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                $salida .= "                        " . $busqueda[$i]['descripcion'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                $salida .= "                         " . $busqueda[$i]['descripcion_unidad'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
                $salida .= "                         " . $busqueda[$i]['existencia'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\">\n";
                $salida .= "                         <a title='COSTO PROMEDIO'>\n";
                $salida .= "                         " . $busqueda[$i]['costo'];
                $salida .= "                         </a>\n";
                $salida .= "                      </td>\n";
                $fechaven = explode("-", $busqueda[$i]['fecha_vencimiento']);
                $fechavencimiento = $fechaven[2] . "-" . $fechaven[1] . "-" . $fechaven[0];
                $salida .= "                      <td align=\"right\">\n";
                $salida .= "                         <a title='FECHA VENCIMIENTO'>\n";
                $salida .= "                         " . $fechavencimiento;
                $salida .= "                         </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\">\n";
                $salida .= "                         <a title='LOTE'>\n";
                $salida .= "                         " . $busqueda[$i]['lote'];
                $salida .= "                         </a>\n";
                $salida .= "                      </td>\n";
                if ($busqueda[$i]['existencia'] > 0) {
                    $salida .= "                      <td align=\"center\" onclick=\"AsignarPro('" . $busqueda[$i]['codigo_producto'] . "','" . $busqueda[$i]['descripcion'] . "','" . $busqueda[$i]['descripcion_unidad'] . "','" . $busqueda[$i]['costo'] . "','" . $busqueda[$i]['existencia'] . "','" . $fechavencimiento . "','" . $busqueda[$i]['lote'] . "');\">\n";
                    $salida .= "                         <a title='SELECCIONAR PRODUCTO'>\n";
                    $salida .= "                          <sub><img src=\"" . $path . "/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                    $salida .= "                         </a>\n";
                } else {
                    $salida .= "                      <td align=\"center\" onclick=\"\">\n";
                }
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
            }
            $salida .= "                </table>\n";

            $Cont = $consulta->ContarProStip($empresa_id, $centro_utilidad, $bodega, $aumento);
            $malo = $Cont[0]['count'];

            $action = "Bus_Pro('" . $empresa_id . "','" . $centro_utilidad . "','" . $bodega . "','" . $tip_bus . "','" . $criterio . "' ";
            $ctl = AutoCarga::factory("ClaseHTML");
            $salida .= $ctl->ObtenerPaginadoXajax($consulta->conteo, $consulta->paginaActual, $action, "0", 10);
        } else {
            $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr>\n";
            $salida .= "                      <td align=\"center\">\n";
            $salida .="                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    </table>\n";
        }
    } else {
        $salida .= "  <table width=\"95%\" align=\"center\">\n";
        $salida .= "    <tr>\n";
        $salida .= "      <td align=\"center\" class=\"normal_10AN\">\n";
        $salida .= "        INGRESE UN CRITERIO DE BUSQUEDA";
        $salida .= "      </td>\n";
        $salida .= "    </tr>\n";
        $salida .= "  </table>\n";
    }

    $objResponse->assign("tabelos", "innerHTML", $salida);
    return $objResponse;
}

/* * **********************************************
 * funcion pra buscar productos
 * *********************************************** */

function BuscarProductoFarm($empresa_id, $centro_utilidad, $bodega, $tip_bus, $criterio, $offset) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new doc_bodegas_E008();
    //echo $tip_bus; 
    if ($tip_bus == 2) {
        $aumento = "AND b.codigo_producto='" . $criterio . "'";
    } elseif ($tip_bus == 1) {
        $aumento = "AND b.descripcion LIKE '%" . strtoupper($criterio) . "%'";
    } else {
        $aumento = "";
    }

    if ($criterio != "0" && $criterio != "") {
        $busqueda = $consulta->BuscarProducto($empresa_id, $centro_utilidad, $bodega, $aumento, $offset);
        if (!empty($busqueda)) {
            $salida .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
            //$salida .=                     $busqueda;
            //codigo_producto descripcion unidad_id descripcion_unidad
            $salida .= "                 </div>\n";
            $salida .= "                 <form name=\"adicionar\">\n";
            $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td align=\"center\"width=\"15%\">\n";
            $salida .= "                        CODIGO PRODUCTO";
            $salida .= "                      </td>\n";
            //$salida .= "<pre>".print_r($criterio,true)."</pre>";
            $salida .= "                      <td align=\"center\" width=\"35%\">\n";
            $salida .= "                        <a title='DESCRIPCION PRODUCTO'>DESCRIPCION<a> ";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            $salida .= "                        UNIDAD";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            $salida .= "                        EXISTENCIA";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            $salida .= "                        COSTO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"20%\">\n";
            $salida .= "                        FECHA VEN";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            $salida .= "                        LOTE";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        <a title='SELECCIONAR PRODUCTO'>SL<a>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            for ($i = 0; $i < count($busqueda); $i++) {
                $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                        " . $busqueda[$i]['codigo_producto'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                $salida .= "                        " . $busqueda[$i]['descripcion'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                $salida .= "                         " . $busqueda[$i]['descripcion_unidad'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
                $salida .= "                         " . $busqueda[$i]['existencia'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\">\n";
                $salida .= "                         <a title='COSTO PROMEDIO'>\n";
                $salida .= "                         " . $busqueda[$i]['costo'];
                $salida .= "                         </a>\n";
                $salida .= "                      </td>\n";
                $fechaven = explode("-", $busqueda[$i]['fecha_vencimiento']);
                $fechavencimiento = $fechaven[2] . "-" . $fechaven[1] . "-" . $fechaven[0];
                $salida .= "                      <td align=\"right\">\n";
                $salida .= "                         <a title='FECHA VENCIMIENTO'>\n";
                $salida .= "                         " . $fechavencimiento;
                $salida .= "                         </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\">\n";
                $salida .= "                         <a title='LOTE'>\n";
                $salida .= "                         " . $busqueda[$i]['lote'];
                $salida .= "                         </a>\n";
                $salida .= "                      </td>\n";
                if ($busqueda[$i]['existencia'] > 0) {
                    $salida .= "                      <td align=\"center\" onclick=\"AsignarPro('" . $busqueda[$i]['codigo_producto'] . "','" . $busqueda[$i]['descripcion'] . "','" . $busqueda[$i]['descripcion_unidad'] . "','" . $busqueda[$i]['costo'] . "','" . $busqueda[$i]['existencia'] . "','" . $fechavencimiento . "','" . $busqueda[$i]['lote'] . "');\">\n";
                    $salida .= "                         <a title='SELECCIONAR PRODUCTO'>\n";
                    $salida .= "                          <sub><img src=\"" . $path . "/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                    $salida .= "                         </a>\n";
                } else {
                    $salida .= "                      <td align=\"center\" onclick=\"\">\n";
                }
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
            }
            $salida .= "                </table>\n";

            $Cont = $consulta->ContarProStip($empresa_id, $centro_utilidad, $bodega, $aumento);
            $malo = $Cont[0]['count'];

            $action = "Bus_Pro('" . $empresa_id . "','" . $centro_utilidad . "','" . $bodega . "','" . $tip_bus . "','" . $criterio . "' ";
            $ctl = AutoCarga::factory("ClaseHTML");
            $salida .= $ctl->ObtenerPaginadoXajax($consulta->conteo, $consulta->paginaActual, $action, "0", 10);
        } else {
            $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr>\n";
            $salida .= "                      <td align=\"center\">\n";
            $salida .="                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    </table>\n";
        }
    } else {
        $salida .= "  <table width=\"95%\" align=\"center\">\n";
        $salida .= "    <tr>\n";
        $salida .= "      <td align=\"center\" class=\"normal_10AN\">\n";
        $salida .= "        INGRESE UN CRITERIO DE BUSQUEDA";
        $salida .= "      </td>\n";
        $salida .= "    </tr>\n";
        $salida .= "  </table>\n";
    }
    $objResponse->assign("Contenido", "innerHTML", $salida);
    $objResponse->call("MostrarSpan");
    //$objResponse->assign("farmacia_ped","innerHTML",$salida);
    return $objResponse;
}

/* * ******************************************************************************
 * para mostrar la tabla de clientes
 * ******************************************************************************* */

function ObtenerPaginadoPro($path, $slc, $op, $empresa_id, $centro_utilidad, $bodega, $tip_bus, $criterio, $pagina) {

    //echo "io";
    $TotalRegistros = $slc[0]['count'];
    $TablaPaginado = "";

    if ($limite == null) {
        $uid = UserGetUID();
        $LimitRow = 10; //;intval(GetLimitBrowser());
    } else {
        $LimitRow = $limite;
    }
    if ($TotalRegistros > 0) {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros / $LimitRow);

        if ($TotalRegistros % $LimitRow > 0) {
            $NumeroPaginas++;
        }

        $Inicio = $pagina;
        if ($NumeroPaginas - $pagina < 9) {
            $Inicio = $NumeroPaginas - 9;
        } elseif ($pagina > 1) {
            $Inicio = $pagina - 1;
        }

        if ($Inicio <= 0) {
            $Inicio = 1;
        }

        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

        $TablaPaginado .= "<tr>\n";
        if ($NumeroPaginas > 1) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P�inas:</td>\n";
            if ($pagina > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";   //$empresa_id,$centro_utilidad,$bodega,$tip_bus,$criterio,$offset      
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_Pro('" . $empresa_id . "','" . $centro_utilidad . "','" . $bodega . "','" . $tip_bus . "','" . $criterio . "','1')\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_Pro('" . $empresa_id . "','" . $centro_utilidad . "','" . $bodega . "','" . $tip_bus . "','" . $criterio . "','" . ($pagina - 1) . "')\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $Fin = $NumeroPaginas + 1;
            if ($NumeroPaginas > 10) {
                $Fin = 10 + $Inicio;
            }

            for ($i = $Inicio; $i < $Fin; $i++) {
                if ($i == $pagina) {
                    $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                } else {
                    $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:Bus_Pro('" . $empresa_id . "','" . $centro_utilidad . "','" . $bodega . "','" . $tip_bus . "','" . $criterio . "','" . $i . "')\">" . $i . "</a></td>\n";
                }
                $columnas++;
            }
        }
        if ($pagina < $NumeroPaginas) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_Pro('" . $empresa_id . "','" . $centro_utilidad . "','" . $bodega . "','" . $tip_bus . "','" . $criterio . "','" . ($pagina + 1) . "')\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:Bus_Pro('" . $empresa_id . "','" . $centro_utilidad . "','" . $bodega . "','" . $tip_bus . "','" . $criterio . "','" . $NumeroPaginas . "')\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
        $aviso .= "     P�ina&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
        $aviso .= "   </tr>\n";

        if ($op == 2) {
            $TablaPaginado .= $aviso;
        } else {
            $TablaPaginado = $aviso . $TablaPaginado;
        }
    }

    $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
    $Tabla .= $TablaPaginado;
    $Tabla .= "</table>";

    return $Tabla;
}

/* * ****************************************************************************
 * PARA HACER EL FAMOSISIMO SUBMIT
 * ****************************************************************************** */

function Subtimit() {
    $objResponse = new xajaxResponse();
    $objResponse->call("mar");
    return $objResponse;
}

/* * *****************************************************************************
  funcion para buscar tecero por id
 * ***************************************************************************** */

function BusUnTer($tipo_id, $id) {

    $objResponse = new xajaxResponse();

    $path = SessionGetVar("rutaImagenes");
    $consulta = new MovBodegasSQL();
    $Tercero = $consulta->Nombres($tipo_id, $id);
    if (!empty($Tercero)) {
        $tercero_tipo_id = $Tercero[0]['tipo_id_tercero'];
        $tercero_id = $Tercero[0]['tercero_id'];
        $tercero_ids = $Tercero[0]['tipo_id_tercero'] . "-" . $Tercero[0]['tercero_id'];
        $tercero_nombre = $Tercero[0]['nombre_tercero'];
        $objResponse->assign("tercerito_tip", "value", $tercero_tipo_id);
        $objResponse->assign("tercerito", "value", $tercero_id);
        $objResponse->assign("id_tercerox", "value", $tercero_id);
        $objResponse->assign("td_terceros_nue_mov", "innerHTML", $tercero_nombre);
        $objResponse->assign("ter_id_nuedoc", "value", $tercero_ids);
        $objResponse->assign("ter_nom_nue_doc", "value", $tercero_nombre);
        $objResponse->assign("nombre_tercero", "innerHTML", $tercero_nombre);
        $objResponse->assign("nom_terc", "value", $tercero_id);
        $objResponse->assign("tipo_id_tercero_sel", "value", $tercero_tipo_id);
        $objResponse->assign("id_tercero_sel", "value", $tercero_id);
        $objResponse->assign("nombre_tercero_sel", "value", $tercero_nombre);
    } else {
        $clear = "<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">NO EXISTE CON ESA IDENTIFICACION</label>";
        $objResponse->assign("td_terceros_nue_mov", "innerHTML", $clear);
        $objResponse->assign("nombre_tercero", "innerHTML", $clear);
    }
    return $objResponse;
}

/* * *****************************************************************************
 * Cuadra el select de tipo id terceros
 * ****************************************************************************** */

function Cuadrar_ids_terceros($id) {
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta = new MovBodegasSQL();
    $TiposTercerosId = $consulta->Terceros_id();
    $salida .= "                         <select name=\"tipox_id\" id=\"tipox_id\" class=\"select\" onchange=\"\">";
    for ($i = 0; $i < count($TiposTercerosId); $i++) {
        if ($TiposTercerosId[$i]['tipo_id_tercero'] == $id) {
            $salida .="                           <option value=\"" . $TiposTercerosId[$i]['tipo_id_tercero'] . "\" selected>" . $TiposTercerosId[$i]['tipo_id_tercero'] . "</option> \n";
        } else {
            $salida .="                           <option value=\"" . $TiposTercerosId[$i]['tipo_id_tercero'] . "\">" . $TiposTercerosId[$i]['tipo_id_tercero'] . "</option> \n";
        }
    }
    $salida .="                         </select>\n";
    $objResponse->assign("tercero_identic", "innerHTML", $salida);
    $objResponse->assign("tipos_ids_terceroxa", "innerHTML", $salida);

    return $objResponse;
}

/* * ******************************************************************************
  trae numero de movimiento segun lapso
 * ******************************************************************************* */

function Departamento2($id_pais) {
    $consulta = new MovBodegasSQL();
    $objResponse = new xajaxResponse();
    $Departamentos = $consulta->DePX($id_pais);
    $path = SessionGetVar("rutaImagenes");
    //$objResponse->alert("sss $id_pais");

    if ($id_pais != "0") {
        //  var_dump($Departamentos);
        if (!empty($Departamentos)) {
            $salida = "                       <select id=\"dptox\" name=\"dptox\" class=\"select\" onchange=\"Municipios1(document.getElementById('paisex').value,document.getElementById('dptox').value);\">";
            $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
            for ($i = 0; $i < count($Departamentos); $i++) {
                $salida .= "                           <option value=\"" . $Departamentos[$i]['tipo_dpto_id'] . "\">" . $Departamentos[$i]['departamento'] . "</option> \n";
            }
            $salida .= "                           <option value=\"" . "otro" . "\">OTRO</option> \n";
            $salida .= "                       </select>\n";
            $salida = $objResponse->setTildes($salida);
            $objResponse->assign("depart", "innerHTML", $salida);
            $salida1 = "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" disabled>";
            $salida1 .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
            $salida1 .= "                       </select>\n";
            $objResponse->assign("muni", "innerHTML", $salida1);
            $objResponse->assign("h_departamento", "value", "0");
            $objResponse->assign("h_municipio", "value", "0");
        } else {
            //$objResponse->alert("saaa $id_pais");
            $inc = "<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">INSERTAR</label>";
            $salida = " <input type=\"text\" class=\"input-text\" id=\"dptox\" name=\"dptox\" size=\"30\" onkeypress=\"\" value=\"\">\n";
            $salida.=$inc;
            $salida1 = " <input type=\"text\" class=\"input-text\" id=\"mpios\" name=\"mpios\" size=\"30\" onkeypress=\"\" value=\"\">\n";
            $salida1.=$inc;
            $objResponse->assign("depart", "innerHTML", $salida);
            $objResponse->assign("muni", "innerHTML", $salida1);
            //$salida .= "                       <input type=\"hidden\" id=\"h_departamento\" name=\"h_departamento\" value=\"0\">\n";
            //$salida .= "                       <input type=\"hidden\" id=\"h_municipio\" name=\"h_municipio\" value=\"0\">\n";
            $objResponse->assign("h_departamento", "value", "1");
            $objResponse->assign("h_municipio", "value", "1");
        }
    } else {
        $salida = "                       <select id=\"dptox\" name=\"dptox\" class=\"select\" disabled>";
        $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
        $salida .= "                       </select>\n";
        $objResponse->assign("depart", "innerHTML", $salida);
        $salida1 = "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" disabled>";
        $salida1 .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
        $salida1 .= "                       </select>\n";
        $objResponse->assign("muni", "innerHTML", $salida1);
        $objResponse->assign("h_departamento", "value", "0");
        $objResponse->assign("h_municipio", "value", "0");
    }

    return $objResponse;
}

/* * ****************************************************************************
 * MUNICIPIOS
 * ****************************************************************************** */

function Municipios($id_pais, $id_dpto) {
    $consulta = new MovBodegasSQL();
    $objResponse = new xajaxResponse();
    $Municipios = $consulta->DeMX($id_pais, $id_dpto);
    $path = SessionGetVar("rutaImagenes");
    //$objResponse->alert("sss $id_dpto");

    if ($id_dpto != "0" && $id_dpto != "otro") {

        //  var_dump($Departamentos);Municipio3(municipio)
        if (!empty($Municipios)) {
            $salida = "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" onchange=\"Municipio3(this.value);\">"; //
            $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
            for ($i = 0; $i < count($Municipios); $i++) {
                $salida .= "                           <option value=\"" . $Municipios[$i]['tipo_mpio_id'] . "\">" . $Municipios[$i]['municipio'] . "</option> \n";
            }

            $salida .= "                           <option value=\"" . "otro" . "\">OTRO</option> \n";
            $salida .= "                       </select>\n";
            $salida = $objResponse->setTildes($salida);
            $objResponse->assign("muni", "innerHTML", $salida);
        } else {
            $inc = "<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">INSERTAR</label>";
            $salida1 = " <input type=\"text\" class=\"input-text\" id=\"mpios\" name=\"mpios\" size=\"30\" onkeypress=\"\" value=\"\">\n";
            $salida1.=$inc;
            $objResponse->assign("muni", "innerHTML", $salida1);
            $objResponse->assign("h_municipio", "value", "1");
        }
    } elseif ($id_dpto == "otro") {
        //$objResponse->alert("serasss $id_dpto");
        $inc = "<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">INSERTAR</label>";
        $salida = " <input type=\"text\" class=\"input-text\" id=\"dptox\" name=\"dptox\" size=\"30\" onkeypress=\"\" value=\"\">\n";
        $salida.=$inc;
        $salida1 = " <input type=\"text\" class=\"input-text\" id=\"mpios\" name=\"mpios\" size=\"30\" onkeypress=\"\" value=\"\">\n";
        $salida1.=$inc;
        $objResponse->assign("depart", "innerHTML", $salida);
        $objResponse->assign("muni", "innerHTML", $salida1);
        $objResponse->assign("h_departamento", "value", "1");
        $objResponse->assign("h_municipio", "value", "1");
    } else {
        $salida1 = "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" disabled>";
        $salida1 .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
        $salida1 .= "                       </select>\n";
        $objResponse->assign("muni", "innerHTML", $salida1);
    }

    return $objResponse;
}

/* * *******************************************************************************
 * FUNCION PARA GUARDAR PERSONAS
 * ******************************************************************************** */

function GuardarPersona($tipo_identificacion, $id_tercero, $nombre, $pais, $departamento, $municipio, $direccion, $telefono, $faz, $email, $celular, $perjur) {
    $path = SessionGetVar("rutaImagenes");
    $objResponse = new xajaxResponse();
    $consulta = new MovBodegasSQL();
    //$objResponse->alert("Hoddla $direccion");
    $REGISTRAR = $consulta->GuardarPersonas($tipo_identificacion, $id_tercero, strtoupper($nombre), $pais, $departamento, $municipio, $direccion, $telefono, $faz, $email, $celular, $perjur);

    if ($REGISTRAR == "EXITO") {

        $objResponse->call("CerrarTrocha");
        $Tercero = $consulta->Nombres($tipo_identificacion, $id_tercero);
        if (!empty($Tercero)) {
            $tercero_tipo_id = $Tercero[0]['tipo_id_tercero'];
            $tercero_id = $Tercero[0]['tercero_id'];
            $tercero_ids = $Tercero[0]['tipo_id_tercero'] . "-" . $Tercero[0]['tercero_id'];
            $tercero_nombre = $Tercero[0]['nombre_tercero'];
            //$objResponse->alert("Hola1 $tercero_id");
            $objResponse->assign("nom_terc", "value", $tercero_id);
            //$objResponse->alert("Hola2 $tercero_id");
            $objResponse->assign("tercerito_tip", "value", $tercero_tipo_id);
            $objResponse->assign("tercerito", "value", $tercero_id);
            $objResponse->assign("id_tercerox", "value", $tercero_id);
            $objResponse->assign("td_terceros_nue_mov", "innerHTML", $tercero_nombre);
            $objResponse->assign("ter_id_nuedoc", "value", $tercero_ids);
            $objResponse->assign("ter_nom_nue_doc", "value", $tercero_nombre);
            $objResponse->assign("nombre_tercero", "innerHTML", $tercero_nombre);

            $objResponse->assign("tipo_id_tercero_sel", "value", $tercero_tipo_id);
            $objResponse->assign("id_tercero_sel", "value", $tercero_id);
            $objResponse->assign("nombre_tercero_sel", "value", $tercero_nombre);
        }

        $TiposTercerosId = $consulta->Terceros_id();
        $salida = "<select name=\"tipox_id\" id=\"tipox_id\" class=\"select\" onchange=\"\">";
        for ($i = 0; $i < count($TiposTercerosId); $i++) {
            if ($TiposTercerosId[$i]['tipo_id_tercero'] == $tipo_identificacion) {
                $salida .="                           <option value=\"" . $TiposTercerosId[$i]['tipo_id_tercero'] . "\" selected>" . $TiposTercerosId[$i]['tipo_id_tercero'] . "</option> \n";
            } else {
                $salida .="                           <option value=\"" . $TiposTercerosId[$i]['tipo_id_tercero'] . "\">" . $TiposTercerosId[$i]['tipo_id_tercero'] . "</option> \n";
            }
        }
        $salida .="                         </select>\n";
        $objResponse->assign("tercero_identic", "innerHTML", $salida);
        $objResponse->assign("tipos_ids_terceroxa", "innerHTML", $salida);
    } else {
        $objResponse->assign("error_terco", "innerHTML", $REGISTRAR);
    }
    //$objResponse->alert("Hola $REGISTRAR");  
    //$objResponse->assign("error_terco","innerHTML",$REGISTRAR);   
    return $objResponse;
}

function Guardar_DYM($vienen, $id_pais, $departamentox, $Municipio) {
    $consulta = new MovBodegasSQL();
    $objResponse = new xajaxResponse();
    //$objResponse->alert("VIENEN $vienen");
    if ($vienen == 2) {
        $revisar = $consulta->Consultadpto($departamentox);

        if (empty($revisar)) {
            $departamentox = strtoupper($departamentox);
            $Municipio = strtoupper($Municipio);
            $GuardarD = $consulta->GXD($id_pais, UTF8_DECODE($departamentox));

            $GuardarM = $consulta->GXM($id_pais, $GuardarD, UTF8_DECODE($Municipio));

            $LISTO = "YA ESTAN" . $GuardarD . "Y" . $GuardarM;

            //$objResponse->alert("r $LISTO");

            $objResponse->assign("dptox", "value", $GuardarD);

            $objResponse->assign("mpios", "value", $GuardarM);

            $objResponse->assign("ban_dep", "value", "1");

            $objResponse->assign("ban_mun", "value", "1");
        } elseif (Is_array($revisar)) {
            $GuardarD = $revisar[0]['tipo_dpto_id'];
            //var_dump($revisar);
            $LISTO = "YA ESTA REPETIDO DEPATAMENTO" . $GuardarD;

            //$objResponse->alert("r $LISTO");

            $revisar = $consulta->Consultampio($id_pais, $GuardarD, $Municipio);

            if (empty($revisar)) {
                $Municipio = strtoupper($Municipio);
                $GuardarM = $consulta->GXM($id_pais, $GuardarD, UTF8_DECODE($Municipio));
            } elseif (Is_array($revisar)) {
                $GuardarM = $revisar[0]['tipo_mpio_id'];

                $toca = "municipio ya existe" . $GuardarM;

                //$objResponse->alert("r $toca");
            }


            $objResponse->assign("dptox", "value", $GuardarD);

            $objResponse->assign("mpios", "value", $GuardarM);

            $objResponse->assign("ban_dep", "value", "1");

            $objResponse->assign("ban_mun", "value", "1");
        }
    } elseif ($vienen == 1) {
        $revisar = $consulta->Consultampio($id_pais, $departamentox, $Municipio);
        //var_dump($revisar);
        if (empty($revisar)) {
            $Municipio = strtoupper($Municipio);
            $GuardarM = $consulta->GXM($id_pais, $departamentox, UTF8_DECODE($Municipio));

            $LISTO = "MUNICIPIO GRABDO" . $GuardarM;

            //$objResponse->alert("r $LISTO");
        } elseif (Is_array($revisar)) {
            $GuardarM = $revisar[0]['tipo_mpio_id'];

            $toca = "municipio ya existe" . $GuardarM;

            //$objResponse->alert("r $toca");
        }

        $objResponse->assign("mpios", "value", $GuardarM);

        $objResponse->assign("ban_dep", "value", "1");

        $objResponse->assign("ban_mun", "value", "1");
    }


    $objResponse->call("Guardaralfa");

    return $objResponse;
}

/* * ************************************************************************************
 * FUNCION PARA CREAR UN USUARIO
 * ************************************************************************************ */

function CrearUSA() {

    $path = SessionGetVar("rutaImagenes");
    $objResponse = new xajaxResponse();
    $consulta = new MovBodegasSQL();
    $salida = "                <div id=\"ventana_terceros\">\n";
    $salida .= "                 <form name=\"formcreausu\">\n";
    $salida .= "                  <div id='error_terco' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $salida .= "                   <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
    $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    $salida .= "                      <td  align=\"center\" colspan='2'>\n";
    $salida .= "                         CREAR TERCERO";
    $salida .= "                      </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
    $salida .= "                        TIPO ID TERCERO";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"70%\" align=\"left\" >\n";
    $tipos_id_ter3 = $consulta->Terceros_id();
    if (!empty($tipos_id_ter3)) {
        $salida .= "                       <select id=\"tipos_idx3\" name=\"tipos_idx3\" class=\"select\" onchange=\"\">";


        for ($i = 0; $i < count($tipos_id_ter3); $i++) {
            $salida .="                           <option value=\"" . $tipos_id_ter3[$i]['tipo_id_tercero'] . "\">" . $tipos_id_ter3[$i]['tipo_id_tercero'] . "</option> \n";
        }
        $salida .= "                       </select>\n";
    }
    $salida .= "                        &nbsp; TERCERO ID";
    $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"terco_id\" name=\"terco_id\" maxlength=\"20\" size=\"20\" value=\"\" onkeypress=\"return acceptNum(event)\">";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
    $salida .= "                        NOMBRE";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
    $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"nom_man\" name=\"nom_man\" size=\"50\" value=\"\" onkeypress=\"\">";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
    $salida .= "                        PAIS";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
    $Pais = $consulta->Paises();

    if (!empty($Pais)) {
        $salida .= "                       <select id=\"paisex\" name=\"paisex\" class=\"select\" onchange=\"Departamentos2(this.value);\">";
        $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";

        for ($i = 0; $i < count($Pais); $i++) {
            $salida .="                           <option value=\"" . $Pais[$i]['tipo_pais_id'] . "\">" . $Pais[$i]['pais'] . "</option> \n";
        }
        $salida .= "                       </select>\n";
    }
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
    $salida .= "                        DEPARTAMENTO";
    $salida .= "                       </td>\n";
    $salida .= "                       <input type=\"hidden\" id=\"ban_dep\" name=\"ban_dep\" value=\"0\">\n";
    $salida .= "                       <input type=\"hidden\" id=\"h_departamento\" name=\"h_departamento\" value=\"0\">\n";
    $salida .= "                       <td width=\"70%\"  align=\"left\" id=\"depart\">\n";
    $salida .= "                       <select id=\"dptox\" name=\"dptox\" class=\"select\" disabled>";
    $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
    $salida .= "                       </select>\n";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
    $salida .= "                        MUNICIPIO";
    $salida .= "                       </td>\n";
    $salida .= "                       <input type=\"hidden\" id=\"ban_mun\" name=\"ban_mun\" value=\"0\">\n";
    $salida .= "                       <input type=\"hidden\" id=\"h_municipio\" name=\"h_municipio\" value=\"0\">\n";
    $salida .= "                       <td width=\"70%\"  align=\"left\" id=\"muni\">\n";
    $salida .= "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" disabled>";
    $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
    $salida .= "                       </select>\n";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
    $salida .= "                        DIRECCION";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
    $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"direc\" id=\"direc\" maxlength=\"50\" size=\"50\" value=\"\" onkeypress=\"\">";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
    $salida .= "                        TELEFONO";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
    $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"phone\" name=\"phone\" maxlength=\"30\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
    $salida .= "                        FAX";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
    $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"fax\" name=\"fax\" maxlength=\"30\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
    $salida .= "                        E-MAIL";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
    $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"e_mail\" name=\"e_mail\" maxlength=\"50\" size=\"50\" value=\"\" onkeypress=\"\">";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
    $salida .= "                        CELULAR";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
    $salida .= "                         <input type=\"text\" class=\"input-text\" id=\"cel\" name=\"cel\" maxlength=\"30\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $salida .= "                       <td colspan='2'  align=\"center\">\n";
    $salida .= "                          PERSONA NATURAL";
    $salida .= "                          <input type=\"radio\" class=\"input-text\" id=\"persona1\" name=\"persona1\" value=\"0\" checked>\n";
    $salida .= "                          PERSONA JURIDICA";
    $salida .= "                          <input type=\"radio\" class=\"input-text\" id=\"persona2\" name=\"persona1\" value=\"1\" >\n";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $salida .= "                       <td colspan='2'  align=\"center\">\n";
    $salida .= "                         <input type=\"button\" class=\"input-submit\" onclick=\"ValidadorUltraTercero();\" value=\"Registrar\">\n";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                 </table>\n";
    $salida .= "                </form>\n";
    $salida .= "         </div>\n";
    $salida = $objResponse->setTildes($salida);
    $objResponse->assign("ContenidoCre", "innerHTML", $salida);
    return $objResponse;
}

/* * ******************************************************************************
 * para mostrar la tabla de terceros
 * ******************************************************************************* */

function ObtenerPaginadoter($pagina, $path, $slc, $op, $criterio1, $criterio2, $criterio, $div, $forma) {

    //echo "io";
    $TotalRegistros = $slc[0]['count'];
    $TablaPaginado = "";

    if ($limite == null) {
        $uid = UserGetUID();
        $LimitRow = intval(GetLimitBrowser());
    } else {
        $LimitRow = $limite;
    }
    if ($TotalRegistros > 0) {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros / $LimitRow);

        if ($TotalRegistros % $LimitRow > 0) {
            $NumeroPaginas++;
        }

        $Inicio = $pagina;
        if ($NumeroPaginas - $pagina < 9) {
            $Inicio = $NumeroPaginas - 9;
        } elseif ($pagina > 1) {
            $Inicio = $pagina - 1;
        }

        if ($Inicio <= 0) {
            $Inicio = 1;
        }

        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

        $TablaPaginado .= "<tr>\n";
        if ($NumeroPaginas > 1) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P�inas:</td>\n";
            if ($pagina > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                //     na,criterio1,criterio2,criterio,div,forma
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_ter('1','" . $criterio1 . "','" . $criterio2 . "','" . $criterio . "','" . $div . "','" . $forma . "')\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_ter('" . ($pagina - 1) . "','" . $criterio1 . "','" . $criterio2 . "','" . $criterio . "','" . $div . "','" . $forma . "')\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $Fin = $NumeroPaginas + 1;
            if ($NumeroPaginas > 10) {
                $Fin = 10 + $Inicio;
            }

            for ($i = $Inicio; $i < $Fin; $i++) {
                if ($i == $pagina) {
                    $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                } else {
                    $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:Bus_ter('" . $i . "','" . $criterio1 . "','" . $criterio2 . "','" . $criterio . "','" . $div . "','" . $forma . "')\">" . $i . "</a></td>\n";
                }
                $columnas++;
            }
        }
        if ($pagina < $NumeroPaginas) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_ter('" . ($pagina + 1) . "','" . $criterio1 . "','" . $criterio2 . "','" . $criterio . "','" . $div . "','" . $forma . "')\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:Bus_ter('" . $NumeroPaginas . "','" . $criterio1 . "','" . $criterio2 . "','" . $criterio . "','" . $div . "','" . $forma . "')\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
        $aviso .= "     P�ina&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
        $aviso .= "   </tr>\n";

        if ($op == 2) {
            $TablaPaginado .= $aviso;
        } else {
            $TablaPaginado = $aviso . $TablaPaginado;
        }
    }

    $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
    $Tabla .= $TablaPaginado;
    $Tabla .= "</table>";

    return $Tabla;
}

/* * ************************************************************************************
 * Separa la Fecha del formato timestamp  @access private @return string @param date fecha
 * ************************************************************************************ */

function FechaStamp($fecha) {
    if ($fecha) {
        $fech = strtok($fecha, "-");
        for ($l = 0; $l < 3; $l++) {
            $date[$l] = $fech;
            $fech = strtok("-");
        }

        return ceil($date[2]) . "-" . str_pad(ceil($date[1]), 2, 0, STR_PAD_LEFT) . "-" . str_pad(ceil($date[0]), 2, 0, STR_PAD_LEFT);
    }
}

/* * ******************************************************************************
 * para mostrar la tabla de vinculacion de cuentas con paginador incluido
 * ******************************************************************************* */

function ObtenerPaginadoPN($pagina, $path, $slc, $op, $prefijo, $numero) {
    $TotalRegistros = $slc[0]['count'];
    $TablaPaginado = "";

    if ($limite == null) {
        $uid = UserGetUID();
        $LimitRow = 10; //intval(GetLimitBrowser());
        //return $LimitRow;
    } else {
        $LimitRow = $limite;
    }


    if ($TotalRegistros > 0) {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros / $LimitRow);

        if ($TotalRegistros % $LimitRow > 0) {
            $NumeroPaginas++;
        }

        $Inicio = $pagina;
        if ($NumeroPaginas - $pagina < 9) {
            $Inicio = $NumeroPaginas - 9;
        } elseif ($pagina > 1) {
            $Inicio = $pagina - 1;
        }

        if ($Inicio <= 0) {
            $Inicio = 1;
        }

        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

        $TablaPaginado .= "<tr>\n";
        if ($NumeroPaginas > 1) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Paginas:</td>\n";
            if ($pagina > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";                //     $lapso,$tip_doc,$prefijo,$numero                       
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('1','" . $prefijo . "','" . $numero . "');\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('" . ($pagina - 1) . "','" . $prefijo . "','" . $numero . "');\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $Fin = $NumeroPaginas + 1;
            if ($NumeroPaginas > 10) {
                $Fin = 10 + $Inicio;
            }

            for ($i = $Inicio; $i < $Fin; $i++) {
                if ($i == $pagina) {
                    $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                } else {
                    $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:LlamarDocus('" . $i . "','" . $prefijo . "','" . $numero . "');\">" . $i . "</a></td>\n";
                }
                $columnas++;
            }
        }
        if ($pagina < $NumeroPaginas) {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('" . ($pagina + 1) . "','" . $prefijo . "','" . $numero . "')\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:LlamarDocus('" . $NumeroPaginas . "','" . $prefijo . "','" . $numero . "')\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
        $aviso .= "     Pagina&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
        $aviso .= "   </tr>\n";

        if ($op == 2) {
            $TablaPaginado .= $aviso;
        } else {
            $TablaPaginado = $aviso . $TablaPaginado;
        }
    }

    $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
    $Tabla .= $TablaPaginado;
    $Tabla .= "</table>";

    return $Tabla;
}

/* * **************************************************************
 * lapsos en el creardoc
 * *************************************************************** */

function ColocarDias($lapso, $div) {
    $objResponse = new xajaxResponse();
    //$objResponse->alert("Hay $lapso");
    $consulta = new TomaFisicaSQL();
    $anho = substr($lapso, 0, 4);
    $mes = substr($lapso, 4, 2);


    //$objResponse->alert("Hyy $anho");
    $dias = date("d", mktime(0, 0, 0, $mes + 1, 0, $anho));
    //$objResponse->alert("Hyy $dias");
    $salida = "                    <select name=\"mesito\" class=\"select\" onchange=\"limpiar()\">";
    $salida .="                      <option value=\"0\" selected>---</option> \n";
    for ($i = 1; $i <= $dias; $i++) {
        $salida .="                   <option value=\"" . $i . "\">" . $i . "</option> \n";
    }
    $salida .="                   </select>\n";
    $objResponse->assign($div, "innerHTML", $salida);
    return $objResponse;
}

/**
 * Funcion donde se muestra la opcion de selccionar la farmacia o el tercero
 *
 * @param array $forma Arreglo de datos de la forma
 *
 * @return object
 */
function MostrarFarmacia_Cliente($forma) {
    $consulta = new MovBodegasSQL();
    $objResponse = new xajaxResponse();

    $html = "";
    if ($forma['tipo_idfc'] == 1) {
        $farmacias = $consulta->ObtenerFarmaciasPedidos($forma['empresa_id']);
        //print_r($farmacias);

        $html .= "<table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td align=\"left\" width=\"25%\" >FARMACIA</td>\n";
        $html .= "    <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "      <select name=\"farmacia_id\" id=\"farmacia_id\" class=\"select\" onChange=\"BuscarDocumentosPedidos()\" style=\"width:100%\">\n";
        $html .= "        <option value=\"-1\">--SELECCIONAR--</option>\n";
        foreach ($farmacias as $key => $dtl)
            $html .= "        <option value=\"" . $dtl['empresa_id'] . "@" . $dtl['centro_utilidad'] . "@" . $dtl['bodega'] . "\">" . $dtl['razon_social'] . "</option>\n";
        $html .= "      </select>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"formulacion_table_list\">\n";
        $html .= "    <td align=\"left\">NUMERO DE PEDIDO</td>\n";
        $html .= "    <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "      <select name=\"pedido_farmacia\" id=\"pedido_farmacia\" class=\"select\" onchange=\"MostrarBotonGuardar(this.value)\">\n";
        $html .= "        <option value=\"-1\">--SELEC--</option>\n";
        $html .= "      </select>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
    } else if ($forma['tipo_idfc'] == 2) {
        $html .= "<table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td align=\"left\" width=\"25%\">TERCERO</td>\n";
        $html .= "      <input type=\"hidden\" id=\"tercerito_tip\" name=\"tercerito_tip\" value=\"\">\n";
        $html .= "      <input type=\"hidden\" id=\"tercerito\" name=\"tercerito\" value=\"\">\n";
        $html .= "      <input type=\"hidden\" id=\"htmp_id\" name=\"htmp_id\" value=\"0\">\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"left\" class=\"modulo_list_claro\" id=\"td_terceros_nue_mov\"> \n";
        $html .= "    </td>\n";
        $html .= "    <td width='20%' align=\"center\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
        $java = "javascript:MostrarCapa('ContenedorMov1');Bus_ter('','','','ventana_terceros','unocreate','0');Iniciar2('BUSCAR TERCERO');\""; //
        $html .= "      <a  title=\"SELECIONAR TERCERO\" class=\"label_error\" href=\"" . $java . "\"> BUSCAR TERCERO</a>\n";
        $html .= "    </td>";
        $html .= "   </tr>\n";
        $html .= "  <tr  class=\"formulacion_table_list\">\n";
        $html .= "    <td align=\"left\">NUMERO DE PEDIDO</td>\n";
        $html .= "    <td align=\"left\" colspan=\"2\" class=\"modulo_list_claro\">\n";
        $html .= "      <select name=\"pedido_farmacia\" id=\"pedido_farmacia\" class=\"select\" onchange=\"MostrarBotonGuardar(this.value)\">\n";
        $html .= "        <option value=\"-1\">--SELEC--</option>\n";
        $html .= "      </select>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  </table>";
    }

    $objResponse->assign("tipo_farmaclie", "innerHTML", $html);
    return $objResponse;
}

/**
 * Funcion donde se buscan los documentos relacionados con la farmacia o el tercero
 *
 * @param array $form Arreglo de datos de la forma
 *
 * @return object
 */
function BuscarDocumentosPedidos($form) {
    $objResponse = new xajaxResponse();
    $mbs = new MovBodegasSQL();

    $html .= "        <option value=\"-1\">--SELEC--</option>\n";

    $documentos = $mbs->ObtenerPedidosFarmacia($form['empresa_id'], $form['farmacia_id']);
    foreach ($documentos as $key => $dtl)
        $html .= "        <option value=\"" . $dtl['solicitud_prod_a_bod_ppal_id'] . "\">" . $dtl['solicitud_prod_a_bod_ppal_id'] . "</option>\n";

    $objResponse->assign("pedido_farmacia", "innerHTML", $html);
    return $objResponse;
}

/**
 * Funcion donde se crea la forma para la busqueda de los terceros
 *
 * @param integer $pagina pagina que se muestra en la busqueda
 * @param string $criterio1 Criterio de busqueda
 * @param string $criterio2 Criterio de busqueda
 * @param string $criterio Criterio de busqueda
 * @param string $div Identificador del objeto div
 * @param string $Forma Identificador de la forma
 *
 * @return object
 */
function Buscadorter($pagina, $criterio1, $criterio2, $criterio, $div, $Forma) {
    $objResponse = new xajaxResponse();

    $e8 = new doc_Bodegas_E008();
    $vector = $e8->ObtenerTerceros($pagina, $criterio1, $criterio2, $criterio);
    $html = "";
    if (empty($vector)) {
        $html .= "  <div id=\"erro\" class='label_error' >\n";
        $html .= "    NO SE ENCONTRARON RESULTADOS CON ESE TIPO DE DESCRIPCIÓN";
        $html .= "  </div>\n";
    } else {
        $action['paginador'] = "Bus_ter(document.getElementById('buscar_x').value,document.getElementById('buscar').value,document.getElementById('nom_buscar').value,'" . $div . "','" . $Forma . "'";
        $pghtml = AutoCarga::factory("ClaseHTML");
        $html .= $pghtml->ObtenerPaginadoXajax($e8->conteo, $e8->paginaActual, $action['paginador'], null, 10);

        $html .= "    <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "      <tr class=\"modulo_table_list_title\">\n";
        $html .= "        <td width=\"23%\">IDENTIFICACION</td>\n";
        $html .= "        <td width=\"%\">NOMBRE TERCERO</td>\n";
        $html .= "        <td width=\"5%\">OP</td>\n";
        $html .= "      </tr>\n";

        foreach ($vector as $key => $dtl) {
            $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";
            $html .= "      <tr class=\"" . $est . "\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
            $html .= "        <td>" . $dtl['tipo_id_tercero'] . " - " . strtoupper($dtl['tercero_id']) . "</td>\n";
            $html .= "        <td>" . $dtl['nombre_tercero'] . "</td>\n";
            $html .= "        <td align=\"center\">\n";
            if ($dtl['tipo_bloqueo_id'] == '1') {
                $java = "javascript:MarcarTercero('" . $Forma . "','" . $dtl['tipo_id_tercero'] . "','" . strtoupper($dtl['tercero_id']) . "','" . $dtl['nombre_tercero'] . "');";
                $html .= "          <a title='SELECCIONAR TERCERO' class=\"label_error\" href=\"" . $java . "\">\n";
                $html .= "            <img src=\"" . GetThemePath() . "/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";
                $html .= "          </a>\n";
            } else {
                $html .= "            <img src=\"" . GetThemePath() . "/images/bloqueo.png\" title=\"" . $dtl['bloqueo'] . "\" border=\"0\" width=\"14\" height=\"14\">\n";
            }
            $html .= "        </td>\n";
            $html .= "      </tr>\n";
        }
        $html .= "    </table>\n";
    }
    $html .= "         <br>\n";

    $objResponse->assign("" . $div . "", "innerHTML", $html);

    return $objResponse;
}

/**
 * Funcion donde se crea la forma para la busqueda de los terceros
 *
 * @param string $form Identificador de la forma
 * @param string $tipo_id_tercero C
 * @param string $tercero_id 
 * @param string $nombre_tercero 
 *
 * @return object
 */
function MarcarTercero($form, $tipo_id_tercero, $tercero_id, $nombre_tercero) {
    $objResponse = new xajaxResponse();

    $mbs = new doc_Bodegas_E008();

    $html .= "        <option value=\"-1\">--SELEC--</option>\n";
    $documentos = $mbs->ObtenerPedidosClientes($form['empresa_id'], $tipo_id_tercero, $tercero_id);
    foreach ($documentos as $key => $dtl)
        $html .= "        <option value=\"" . $dtl['pedido_cliente_id'] . "\">" . $dtl['pedido_cliente_id'] . "</option>\n";

    $objResponse->assign("tercerito_tip", "value", $tipo_id_tercero);
    $objResponse->assign("tercerito", "value", $tercero_id);
    $objResponse->assign("td_terceros_nue_mov", "innerHTML", $tipo_id_tercero . "-" . $tercero_id . " " . $nombre_tercero);

    $objResponse->assign("pedido_farmacia", "innerHTML", $html);
    $objResponse->script("Cerrar('ContenedorMov1');");
    return $objResponse;
}

/**
 * Funcion donde se guarda el documento temporal
 *
 * @param integer $bodegas_doc_id
 * @param array $form Arreglo de datos de la forma
 *
 * @return object
 */
function GuardarTmpDoc($bodegas_doc_id, $form) {
    $objResponse = new xajaxResponse();

    $consulta1 = new doc_bodegas_E008();

    $valor = $consulta1->CrearDoc($bodegas_doc_id, $form['obser'], $form['tipo_idfc'], $form['pedido_farmacia'], $form['tercerito_tip'], $form['tercerito'], $form['farmacia_id'], UserGetUID());

    if (!$valor) {
        $objResponse->assign("errorcreartmp", "innerHTML", $consulta1->mensajeDeError);
    } else {
        $objResponse->assign("doc_tmp_id_h", "value", $valor['doc_tmp_id']);
        sleep(1);
        $objResponse->assign("accion_h", "value", 'EDITAR');
        $objResponse->call("mar1");
    }
    return $objResponse;
}

/**
 * @param integer $bodegas_doc_id
 * @param array $form Arreglo de datos de la forma
 *
 * @return object
 */
function IngresarObservacion($doc_tmp_id, $codigo_producto, $observacion) {
    $objResponse = new xajaxResponse();
    $e08 = new doc_bodegas_E008();
    if ($observacion != "") {
        $rst = $e08->ActualizarObservacion($doc_tmp_id, $codigo_producto, $observacion);
        if (!$rst) {
            $objResponse->alert($e08->mensajeDeError);
        } else {
            $objResponse->script("Cerrar('d2Container2');");
        }
    } else {
        $observa = $e08->ObtenerObservacion($doc_tmp_id, $codigo_producto);
        $html .= "<table width=\"100%\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td>JUSTIFICACION DEL DESPACHO DE UNA CANTIDAD MENOR A LA SOLICITADA</TD>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"modulo_list_claro\">\n";
        $html .= "    <td>\n";
        $html .= "      <textarea style=\"width:100%\" rows=\"3\" class=\"textarea\" id=\"justificacion\">" . $observa[$codigo_producto]['observacion_cambio'] . "</textarea>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"modulo_list_claro\">\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <input type=\"button\" class=\"input-submit\" name=\"Guardar\" value=\"Guardar\" onclick=\"xajax_IngresarObservacion('" . $doc_tmp_id . "','" . $codigo_producto . "',document.getElementById('justificacion').value)\">\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";

        $objResponse->assign("d2Contents2", "innerHTML", $html);
        $objResponse->script("Iniciar2('JUSTIFICACION');MostrarSpan('d2Container2');");
    }
    return $objResponse;
}

/**
 * @param integer $bodegas_doc_id
 * @param array $form Arreglo de datos de la forma
 *
 * @return object
 */
function ObservacionesDespachoCliente($Formulario, $bodegas_doc_id, $doc_tmp_id, $tipo_doc_bodega_id, $identificador, $pedido_cliente_id) {
    $objResponse = new xajaxResponse();
    $e08 = new doc_bodegas_E008();



    if (!empty($Formulario)) {
        for ($i = 0; $i < $Formulario['registros']; $i++) {
            if ($Formulario[$i] != "" && trim($Formulario['justificacion' . $i]) != "") {
                $sql .= " INSERT INTO inv_bodegas_movimiento_tmp_justificaciones_pendientes";
                $sql .= " (	";
                $sql .= " usuario_id,	";
                $sql .= " doc_tmp_id,	";
                $sql .= " codigo_producto,	";
                $sql .= " cantidad_pendiente,	";
                $sql .= " observacion,	";
                $sql .= " existencia	";
                $sql .= " )	";
                $sql .= " VALUES	";
                $sql .= " (	";
                $sql .= "	'" . UserGetUID() . "',";
                $sql .= "	'" . $doc_tmp_id . "',";
                $sql .= "	'" . $Formulario[$i] . "',";
                $sql .= "	" . $Formulario['cantidad_pendiente' . $i] . ",";
                $sql .= "	'" . $Formulario['justificacion' . $i] . "',";
                $sql .= "	" . $Formulario['existencia' . $i] . " ";
                $sql .= " );";
            }
        }
        $token = $e08->IngresoJustificacionesDespacho($sql);
    }
    /* print_r($sql); */

    if (trim($identificador) == 'CL') {
        $aumento = " AND((COALESCE(a.numero_unidades,0)-COALESCE(a.cantidad_despachada,0))-COALESCE(c.cantidad,0)) > 0 ";
        $aumento .= " AND e.observacion IS NULL ";
        $datosItem = $e08->ObtenerProductosPedidoCliente($doc_tmp_id, UserGetUID(), $pedido_cliente_id, trim(SessionGetVar("empresa_id")), $param, $aumento, SessionGetVar("centro_utilidad"), SessionGetVar("bodega"));
        if (!empty($datosItem)) {
            $html .= "<form name=\"Justificaciones\" id=\"Justificaciones\" method=\"POST\">";
            $html .= "<table width=\"100%\" class=\"modulo_table_list\">\n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "    <td colspan=\"4\">JUSTIFICACION DEL DESPACHO CON PENDIENTES AL CLIENTE</TD>\n";
            $html .= "  </tr>\n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "    <td >PRODUCTO</TD>\n";
            $html .= "    <td >STOCK</TD>\n";
            $html .= "    <td >PENDIENTE</TD>\n";
            $html .= "    <td >JUSTIFICACION</TD>\n";
            $html .= "  </tr>\n";
            $i = 0;
            foreach ($datosItem as $key => $valor) {
                $html .= "  <tr class=\"modulo_list_claro\">\n";
                $html .= "    <td>\n";
                $html .= "      \n" . $valor['descripcion'];
                $html .= "    </td>\n";
                $html .= "    <td align=\"center\">\n";
                $html .= "      \n<b>" . FormatoValor($valor['existencia']) . "</b>";
                $html .= "		<input type=\"hidden\" name=\"existencia" . $i . "\" id=\"existencia" . $i . "\" value=\"" . $valor['existencia'] . "\">";
                $html .= "    </td>\n";
                $html .= "    <td align=\"center\">\n";
                $html .= "      \n<b>" . FormatoValor($valor['cantidad_pendiente_']) . "</b>";
                $html .= "		<input type=\"hidden\" name=\"cantidad_pendiente" . $i . "\" id=\"cantidad_pendiente" . $i . "\" value=\"" . $valor['cantidad_pendiente_'] . "\">";
                $html .= "    </td>\n";
                $html .= "    <td>\n";
                $html .= "      \n<textarea style=\"width:100%\" id=\"justificacion" . $i . "\" name=\"justificacion" . $i . "\" class=\"textarea\"></textarea>";
                $html .= "		<input type=\"hidden\" name=\"" . $i . "\" id=\"" . $i . "\" value=\"" . $valor['codigo_producto'] . "\">";
                $html .= "    </td>\n";
                $html .= "  </tr>\n";
                $i++;
            }
            $html .= "  <tr class=\"modulo_list_claro\">\n";
            $html .= "    <td align=\"center\" colspan=\"4\">\n";
            $html .= "		<input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"" . $i . "\">";
            $html .= "      <input type=\"button\" class=\"input-submit\" value=\"GUARDAR JUSTIFICACION\" onclick=\"xajax_ObservacionesDespachoCliente(xajax.getFormValues('Justificaciones'),'" . $bodegas_doc_id . "','" . $doc_tmp_id . "','" . $tipo_doc_bodega_id . "','" . $identificador . "','" . $pedido_cliente_id . "')\">\n";
            $html .= "    </td>\n";
            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= "</form>";
            $objResponse->assign("justificaciones", "innerHTML", $html);
            $objResponse->assign("justificaciones", "style.display", "");
        } else {
            $objResponse->script("xajax_CrearDocumentoFinalx('" . $bodegas_doc_id . "','" . $doc_tmp_id . "','" . $tipo_doc_bodega_id . "','" . $identificador . "');");
        }

        /* $objResponse->script("Iniciar4('JUSTIFICACION');MostrarSpan('d2Container');"); */
    } else
    if (trim($identificador) == 'FM') {
        $datosItem = $e08->ObtenerPendientesFarmacia($pedido_cliente_id, $doc_tmp_id, SessionGetVar("empresa_id"), SessionGetVar("centro_utilidad"), SessionGetVar("bodega"));
        if (!empty($datosItem)) {
            $html .= "<form name=\"Justificaciones\" id=\"Justificaciones\" method=\"POST\">";
            $html .= "<table width=\"100%\" class=\"modulo_table_list\">\n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "    <td colspan=\"4\">JUSTIFICACION DEL DESPACHO CON PENDIENTES A LA FARMACIA</TD>\n";
            $html .= "  </tr>\n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "    <td >PRODUCTO</TD>\n";
            $html .= "    <td >STOCK</TD>\n";
            $html .= "    <td >PENDIENTE</TD>\n";
            $html .= "    <td >JUSTIFICACION</TD>\n";
            $html .= "  </tr>\n";
            $i = 0;
            foreach ($datosItem as $key => $valor) {
                $html .= "  <tr class=\"modulo_list_claro\">\n";
                $html .= "    <td>\n";
                $html .= "      \n" . $valor['descripcion'];
                $html .= "    </td>\n";
                $html .= "    <td align=\"center\">\n";
                $html .= "      \n<b>" . FormatoValor($valor['existencia']) . "</b>";
                $html .= "		<input type=\"hidden\" name=\"existencia" . $i . "\" id=\"existencia" . $i . "\" value=\"" . $valor['existencia'] . "\">";
                $html .= "    </td>\n";
                $html .= "    <td align=\"center\">\n";
                $html .= "      \n<b>" . FormatoValor($valor['cantidad_pendiente']) . "</b>";
                $html .= "		<input type=\"hidden\" name=\"cantidad_pendiente" . $i . "\" id=\"cantidad_pendiente" . $i . "\" value=\"" . $valor['cantidad_pendiente'] . "\">";
                $html .= "    </td>\n";
                $html .= "    <td>\n";
                $html .= "      \n<textarea style=\"width:100%\" id=\"justificacion" . $i . "\" name=\"justificacion" . $i . "\" class=\"textarea\"></textarea>";
                $html .= "		<input type=\"hidden\" name=\"" . $i . "\" id=\"" . $i . "\" value=\"" . $valor['codigo_producto'] . "\">";
                $html .= "    </td>\n";
                $html .= "  </tr>\n";
                $i++;
            }
            $html .= "  <tr class=\"modulo_list_claro\">\n";
            $html .= "    <td align=\"center\" colspan=\"4\">\n";
            $html .= "		<input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"" . $i . "\">";
            $html .= "      <input type=\"button\" class=\"input-submit\" value=\"GUARDAR JUSTIFICACION\" onclick=\"xajax_ObservacionesDespachoCliente(xajax.getFormValues('Justificaciones'),'" . $bodegas_doc_id . "','" . $doc_tmp_id . "','" . $tipo_doc_bodega_id . "','" . $identificador . "','" . $pedido_cliente_id . "')\">\n";
            $html .= "    </td>\n";
            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= "</form>";
            $objResponse->assign("justificaciones", "innerHTML", $html);
            $objResponse->assign("justificaciones", "style.display", "");
        } else {
            $objResponse->script("xajax_CrearDocumentoFinalx('" . $bodegas_doc_id . "','" . $doc_tmp_id . "','" . $tipo_doc_bodega_id . "','" . $identificador . "');");
        }
        /* $objResponse->script("Iniciar4('JUSTIFICACION');MostrarSpan('d2Container');"); */
    }


    return $objResponse;
}

?>