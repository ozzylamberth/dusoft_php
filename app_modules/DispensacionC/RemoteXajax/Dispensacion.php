<?php

/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.3 $
 * @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja Torres
 */

/**
 * Funcion que sirve de enlace con otra funcion
 * @param string $observacion  cadena con la observacion ingresada al  realizar el despacho
 * @return Object $objResponse objeto de respuesta al formulario
 */
function PacienteReclama($observacion, $evolucion, $pendiente, $observacion2, $todo_pendiente) {

    $objResponse = new xajaxResponse();

    $url = ModuloGetURL("app", "Dispensacion", "controller", "GenerarEntregaMedicamentos", array("observacion" => $observacion . "-" . $observacion2, "evolucion_id" => $evolucion, "pendiente" => $pendiente, "todo_pendiente" => $todo_pendiente));
    $objResponse->script('
           window.location="' . $url . '";
          ');
    return $objResponse;
}

/**
 * Funcion que sirve de enlace con otra funcion cuando no es el paciente quien reclama los medicamentos
 * @param string $observacion  cadena con la observacion ingresada al  realizar el despacho
 * @return Object $objResponse objeto de respuesta al formulario
 */
function PersonaRclama($observacion, $entrega, $evolucion) {
    $objResponse = new xajaxResponse();
    $url = ModuloGetURL("app", "Dispensacion", "controller", "DatosPersonaReclama", array("observacion" => $observacion, "evolucion" => $evolucion));
    $objResponse->script('
             window.location="' . $url . '";
              ');
    return $objResponse;
}

/**
 * Funcion que permite insertar datos a la tabla temporal
 * @return Object $objResponse objeto de respuesta al formulario
 */
function InsertarDatosFormula_tmp($tipo_id_paciente, $paciente_id, $cantidad_e, $codigo_medicamento_forumulado, $dosis, $fecha_finalizacion, $fecha_formulacion, $fechaproxima, $evolucion_id, $tiempo_perioricidad, $unidad_perioricidad) {
    $objResponse = new xajaxResponse();
    $fdatos = explode("-", $fecha_finalizacion);
    $fecha_finalizacion1 = $fdatos[2] . "-" . $fdatos[1] . "-" . $fdatos[0];
    $fdat = explode("-", $fecha_formulacion);
    $fecha_formulacion1 = $fdat[2] . "-" . $fdat[1] . "-" . $fdat[0];
    $sel = AutoCarga::factory("DispensacionSQL", "", "app", "Dispensacion");
    $rst = $sel->Medicamento_Farmacia_tmp($tipo_id_paciente, $paciente_id, $cantidad_e, $codigo_medicamento_forumulado, $dosis, $fecha_finalizacion1, $fecha_formulacion1, $fechaproxima, $evolucion_id, $tiempo_perioricidad, $unidad_perioricidad);
    return $objResponse;
}

/* * Funcion que permite eliminar los datos de la tabla temporal
 * @return Object $objResponse objeto de respuesta al formulario
 */

function EliminarDatosFormula_tmp($tipo_id_paciente, $paciente_id, $codigo_medicamento_forumulado, $evolucion_id) {

    $objResponse = new xajaxResponse();
    $sel = AutoCarga::factory("DispensacionSQL", "", "app", "Dispensacion");
    $rst = $sel->EliminarDatosFormula($tipo_id_paciente, $paciente_id, $codigo_medicamento_forumulado, $evolucion_id);
    return $objResponse;
}

/* * Funcion que permite verificar si realmente se a seleccionado un medicamento
 * @return Object $objResponse objeto de respuesta al formulario
 */

function MandarInformacion($tipo_id_paciente, $paciente_id, $cantidad_entrega, $evolucion) {

    $objResponse = new xajaxResponse();
    $sel = AutoCarga::factory("DispensacionSQL", "", "app", "Dispensacion");

    $rst = $sel->ConsultarInformacion($tipo_id_paciente, $paciente_id, $evolucion);
    $num = count($rst);
    $url = ModuloGetURL("app", "Dispensacion", "controller", "Medicamentos_A_Despachar", array("tipo_id_paciente" => $tipo_id_paciente, "paciente_id" => $paciente_id, "cantidad" => $num, "c_entrega" => $cantidad_entrega, "evolucion" => $evolucion));
    $objResponse->script('
          if(' . $num . '.==0)
          {
            xajax_MostrarMensaje();
          }else{
            if(' . $num . '.!=0){
            window.location="' . $url . '";
            }
          }
      ');
    return $objResponse;
}

/* * Funcion que permite mostrar un mensaje cuando no se ha seleccionado nada al momento de hacer el despacho
 * @return Object $objResponse objeto de respuesta al formulario
 */

function MostrarMensaje() {
    $objResponse = new xajaxResponse();
    $html = "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"normal_10AN\" align=\"center\">SE DEBE SELECCIONAR AL MENOS UN PRODUCTO PARA GENERAR LA ENTREGA DE MEDICAMENTOS</legend>\n";
    $html .= " <form name=\"Forma13\" id=\"Forma13\" method=\"post\" >\n";
    $html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CANCELAR\" onclick=\"OcultarSpan();\">   \n";
    $html .= " </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
    $html .= "  </form>\n";
    $html .= "</fieldset><br>\n";
    $objResponse->assign("Contenido", "innerHTML", $html);
    $objResponse->call("MostrarSpan");
    return $objResponse;
}

function BuscarProducto1($FormularioBuscador, $evolucion, $bodega_otra) {
    $objResponse = new xajaxResponse();

    $obje = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
    $empresa = SessionGetVar("DatosEmpresaAF");
    $farmacia = $empresa['empresa_id'];
    $centrou = $empresa['centro_utilidad'];
    $bodega_ac = $empresa['bodega'];




    $privilegios = $obje->Usuario_Privilegios_($FormularioBuscador);

    $busqueda = $obje->Consultar_Medicamentos_Detalle_($FormularioBuscador, $evolucion);


    if (!empty($busqueda)) {

        $medicamentos = $obje->Medicamentos_Dispensados_Esm_x_lote_total($evolucion);

        $datos_medicamentos = array();
        $entrega=true;
        foreach ($medicamentos as $key => $value) {
            $datos_medicamentos[$value['fecha_entrega']][] = $value;
            $fech[]=$value['fecha_entrega'];
        }

        $cantidad_dispensacion = count($datos_medicamentos);

        foreach ($busqueda as $k => $valor) {

            $today = date("Y-m-d");

            /* $today = date("Y-m-d");
              $today = strtotime(date("Y-m-d", strtotime($today)) . " +18 day");
              $today = date("Y-m-d", $today); */

            $calculo_fechas = AutoCarga::factory('CalculoFechas');
            $cantidad_meses = $calculo_fechas->obtener_cantidad_meses($valor['fecha_registro'], $valor['fecha_finalizacion']);
            $cantidad_meses = ($cantidad_meses == 0) ? 1 : $cantidad_meses;

            $fecha_finalizacion = $valor['fecha_finalizacion'];
            $fecha_formulacion = $valor['fecha_formulacion'];
            $dias_vigencia_formula = ModuloGetVar('', '', 'dispensacion_dias_vigencia_formula');
            //comentar despues(esto le suma la variable a la fecha de finalizacion) 2015-10-02
            if($valor['fecha_formulacion']>='2015-10-01'){                
                list($a, $m, $d) = split("-", $valor['fecha_finalizacion']);
                $fecha_finalizacion = date("Y-m-d", (mktime(0, 0, 0, $m, ($d + $dias_vigencia_formula), $a)));
            }//

            if ($cantidad_meses > $cantidad_dispensacion) 
                {
                list($a, $m, $d) = split("-", $fecha_formulacion);
                $fecha_formulacion_con_dias_vigncia = date("Y-m-d", (mktime(0, 0, 0, $m, ($d + $dias_vigencia_formula), $a)));
                if ($fecha_finalizacion < $today && $fecha_formulacion_con_dias_vigncia < $today) {
                    $html .= "<table width=\"70%\" align=\"center\"  class=\"modulo_table_list\">";
                    $html .= "<tr class=\"modulo_list_oscuro\">\n";
                    $html .= " <td align=\"left\" width=\"1%\">";
                    $html .= "  <img border=\"0\"  title=\"MEDICAMENTO DISPENSADO\" src=\"" . GetThemePath() . "/images/alarma.gif\">\n";
                    $html .= " </td>";
                    $html .= " <td align=\"left\" width=\"1%\" bgcolor=\"#8C5B9A\" title=\"PROD. VENCIDO\" >";
                    $html .= "";
                    $html .= " </td>";
                    $html .= " <td align=\"left\" width=\"94%\" class=\"label\" bgcolor=\"#E1C9E7\" >";
                    $html .= "         La formula se vencio en la fecha: {$fecha_finalizacion}";
                    $html .= "  </td>";
                    $html .= " </tr>\n";
                    $html .= "</table >";
                }else{
              
                $dias_dipensados = ModuloGetVar('', '', 'dispensacion_dias_ultima_entrega');
                list($a, $m, $d, ) = split("-", $today);
                $fecha_dias = date("Y-m-d", (mktime(0, 0, 0, $m, ($d - $dias_dipensados), $a)));
                $datos_ex = $obje->ConsultarUltimoResg_Dispens_($valor['cod_principio_activo'], $valor['paciente_id'], $valor['tipo_id_paciente'], $valor['codigo_producto'], $today, $fecha_dias);


                $tiempo_perioricidad_entrega = $valor['tiempo_perioricidad_entrega'];
                $unidad_perioricidad_entrega = $valor['unidad_perioricidad_entrega'];

                if ($unidad_perioricidad_entrega == 'año(s)') {
                    $dias_tt = $tiempo_perioricidad_entrega * 365;
                }
                if ($unidad_perioricidad_entrega == 'mes(es)') {

                    $dias_tt = $tiempo_perioricidad_entrega * 30;
                }
                if ($unidad_perioricidad_entrega == 'semana(s)') {

                    $dias_tt = $tiempo_perioricidad_entrega * 7;
                }
                if ($unidad_perioricidad_entrega == 'dia(s)') {

                    $dias_tt = $tiempo_perioricidad_entrega * 1;
                }
                if (!empty($datos_ex)) {
                    if ($datos_ex['resultado'] == '1') {
                        $fecha_registro_ = $datos_ex['fecha_registro'];
                    } else {
                        $fecha_registro_ = $datos_ex['fecha_registro'];
                    }
                }
                list($a, $m, $d) = split("-", $fecha_registro_);
                $fecha_proxima_e = date("Y-m-d", (mktime(0, 0, 0, $m, ($d + $dias_tt), $a)));

                /* echo "<pre>";
                  echo "<br/> datos ex : ";
                  print_r($datos_ex);
                  echo "<br/> Hoy : ";
                  print_r($today);
                  echo "<br/>";
                  echo "<br/> Fecharegistro_ : ";
                  print_r($fecha_registro_);
                  echo "<br/>";
                  echo "<br/>";
                  echo "<br/> dias tt : ";
                  print_r($dias_tt);
                  echo "<br/>";
                  echo "<br/> fecha proxima entr : ";
                  print_r($fecha_proxima_e);
                  echo "</pre>";
                  exit(); */


                //if ($today >= $fecha_proxima_e) {
                
               if ($today >= $fecha_registro_) {

                    if (!empty($datos_ex)) {
                        if ($datos_ex['resultado'] == '1') {
                            $fecha_despacho = $datos_ex['fecha_registro'];
                        } else {
                            $fecha_despacho = $datos_ex['fecha_registro'];
                        }
                        $dias_dipensados = ModuloGetVar('', '', 'dispensacion_dias_ultima_entrega');
                        list($a, $m, $d) = split("-", $fecha_despacho);
                        $fecha_fin_despacho = date("Y-m-d", (mktime(0, 0, 0, $m, ($d + $dias_dipensados), $a)));
                    }else{
                      $fecha_fin_despacho = "";  
                    }

                    /* echo "<pre>HOY";
                      print_r($today);
                      echo "<br/>FECHA DESPA";
                      print_r($fecha_despacho);
                      echo "<br/>DIAS DE DISPENSACION";
                      print_r($dias_dipensados);
                      echo "<br/> FECHA FIN DESP";
                      var_dump($fecha_fin_despacho);
                      echo "</pre>";
                      exit(); */
//echo "<pre> today: ".$today;
//echo "<pre> fecha_fin_despacho: ".$fecha_fin_despacho;
//echo "<pre> valor['sw_autorizado']: ".$valor['sw_autorizado'];
                    if ($today > $fecha_fin_despacho || $valor['sw_autorizado'] == '1') {
                  

                        /* //5 dias habiles para reclamar el Mx sino, se tiene que reformular nuevamente
                          list($a, $m, $d) = split("-", $fecha_fin_despacho);
                          if (is_null($fecha_fin_despacho) || empty($fecha_fin_despacho)) {
                          list($a, $m, $d) = split("-", $today);
                          }
                          $fecha_limite_dispensacion = date("Y-m-d", (mktime(0, 0, 0, $m, ($d + 5), $a))); */

                        $continuar = true;
                        $calculo_fechas = AutoCarga::factory('CalculoFechas');
                        $cantidad_dias_habiles = $calculo_fechas->obtener_dias_habiles($fecha_fin_despacho, $today);

                        /* echo "<pre>HOY: ";
                          print_r($today);
                          echo "<br/>FECHA fin DESPA: ";
                          print_r($fecha_fin_despacho);
                          echo "<br/>cANTIDAD DIAS: ";
                          print_r($cantidad_dias_habiles);
                          echo "</pre>";
                          exit(); */

                        if ($cantidad_dispensacion >= 1) {
                            $continuar = false;
                            if ($cantidad_dias_habiles <= 5)
                                $continuar = true;
                        }

                        ////////////////////////////////////
                        $today = date("Y-m-d H:i:00");
                        
                        if(sizeof($medicamentos)>0){
                            $coma = "";
                            $coma2 = "";
                            for ($i = 0; $i < $cantidad_meses; $i++) {
                                if($i < $cantidad_dispensacion) {
                                    list($as, $ms, $ds) = split("-", $fech[$i]);
                                    $fechas_entregados.=$coma . $as . "-" . ($ms + $i) . "-" . $ds;
                                    $coma = ",";
                                } else {
                                    $desde = $obje->intervaloFechaformula($fech[0], (25 * $i), "+");
                                    $hasta = $obje->intervaloFechaformula($fech[0], ($dias_vigencia_formula + (30 * $i)), "+");
                                    $fechas_por_entregar.=$coma2 . "Desde " . $desde['fecha'] . " Hasta " . $hasta['fecha'];
                                    $coma2 = ",";
                                }
                            }
                            
                            $today = date("Y-m-d H:i:00");
                            for ($i = 0; $i < $cantidad_meses; $i++) {
                                if($i < $cantidad_dispensacion) {
                                    list($as, $ms, $ds) = split("-", $fech[$i]);
                                    $coma = ",";
                                } else {
                                    $desde = $obje->intervaloFechaformula($fech[0], (25 * $i), "+");
                                    $hasta = $obje->intervaloFechaformula($fech[0], ($dias_vigencia_formula + (30 * $i)), "+");
                                    $coma2 = ",";
                                    $desde1 = strtotime($desde['fecha']);
                                    $hasta1 = strtotime($hasta['fecha']);
                                    $today = strtotime($today);
                                    if ($desde1 <= $today && $today <= $hasta1) {
                                        if($cantidad_dispensacion==$i){
                                          $continuar = true;
                                          break; 
                                        }else{
                                          $continuar = false;
                                          break;     
                                        }
                                        
                                    } else {
                                          $continuar = false;                                       
                                    }
                                }
                            }
                              }else{
                            $fechainicio = $obje->intervaloFechaformula($valor['fecha_registro'], ($dias_vigencia_formula), "+");;
                            $fechainicio= strtotime($fechainicio['fecha']);
                            $today = strtotime($today);
                            if($fechainicio>$today){
                                $continuar = true;
                            }else{
                                $continuar = false;
                            }
                        }
                            $fechas_entregados=" ";
                            $fechas_por_entregar="";
           ///////////////////////////////////
                         $today = date("Y-m-d");
                         
                        if ($continuar) {

                            $html .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
                            $html .= "                 </div>\n";
                            $html .= "                                    <div id=\"error\" class='label_error'></div>";
                            $cantidad = $obje->Cantidad_ProductoTemporal($evolucion, $valor['cod_principio_activo'], $valor['codigo_producto']);
                            $CantidaEntregar = round($valor['cantidad_entrega']);
                            $cantidad_ = 0;
                            if ($cantidad['codigo_formulado'] == $valor['codigo_producto']) {
                                $cantidad_ = $cantidad['total'];
                            }
                            $cantidad_final = $CantidaEntregar - $cantidad_;

                            $html .= "                 <form id=\"forma" . $evolucion . "@" . $valor['codigo_producto'] . "\" name=\"" . $evolucion . "@" . $valor['codigo_producto'] . "\" action=\"\" method=\"post\">\n";
                            $html .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
                            $html .= "                    <tr class=\"modulo_table_list_title\">\n";
                            $html .= "                     <td width=\"50%\">PRODUCTO: " . $valor['codigo_producto'] . " &nbsp; " . $valor['descripcion_prod'] . ". </td>
                                                               <td>CANTIDAD SOLICITADA. <input readonly=\"true\" type=\"input-text\" name=\"cantidad_solicitada\" id=\"cantidad_solicitada\" value=\"" . $CantidaEntregar . "\" class=\"input-text\"></td><td>CANTIDAD PENDIENTE <input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" id=\"cantidad_pendiente\" value=\"" . ($CantidaEntregar - $cantidad_) . "\" class=\"input-text\"></td>\n";
                            $html .= "                        <input type=\"hidden\" name=\"principio_activo\" id=\"principio_activo\" value=\"" . $valor['cod_principio_activo'] . "\">";
                            $html .= "                        <input type=\"hidden\" name=\"medicamento_formulado\" id=\"medicamento_formulado\" value=\"" . $valor['codigo_producto'] . "\">";
                            $html .= "                        <input type=\"hidden\" name=\"evolucion\" id=\"evolucion\" value=\"" . $evolucion . "\">";
                            $html .= "                        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"" . $valor['codigo_producto'] . "\">";
                            $html .= "                        <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"" . $FormularioBuscador['bodega'] . "\">";
                            $html .= "                     </td>";
                            $html .= "                    </tr>\n";

                            $html .= "                   <tr class=\"modulo_list_claro\">\n";
                            $html .= "                      <td colspan=\"3\" align=\"center\">";

                            if ($cantidad_final != 0) {

                                $Existencias = $obje->Consultar_ExistenciasBodegas($valor['cod_principio_activo'], $FormularioBuscador, $farmacia, $centrou, $bodega, $valor['codigo_producto']);


                                if (!empty($Existencias)) {
                                    $html .= "                                   <table width=\"100%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
                                    $html .= "                                       <tr class=\"modulo_table_list_title\">\n";
                                    $html .= "                                       <td width=\"10%\">";
                                    $html .= "                                            CODIGO  ";
                                    $html .= "                                        </td>";
                                    $html .= "                                       <td width=\"55%\">";
                                    $html .= "                                            PRODUCTO  ";
                                    $html .= "                                        </td>";
                                    $html .= "                                       <td width=\"10%\">";
                                    $html .= "                                            LOTE";
                                    $html .= "                                        </td>";
                                    $html .= "                                        <td width=\"10%\">";
                                    $html .= "                                              FECHA VENCIMIENTO";
                                    $html .= "                                        </td>";
                                    $html .= "                                       <td width=\"5%\">";
                                    $html .= "                                             EXIST.";
                                    $html .= "                                      </td>";
                                    $html .= "                                        <td width=\"5%\">";
                                    $html .= "                                              CANTIDAD";
                                    $html .= "                                        </td>";
                                    $html .= "                                        <td width=\"5%\">";
                                    $html .= "                                              SEL";
                                    $html .= "                                        </td>";
                                    $html .= "                                        </tr>\n";
                                    $i = 0;
                                    foreach ($Existencias as $key => $v) {
                                        $ProductoLote = $obje->Buscar_ProductoLote($evolucion, $valor['codigo_producto'], $v['lote'], $v['codigo_producto']);
                                        if (!empty($ProductoLote)) {
                                            $habilitar = " checked=\"true\" disabled ";
                                        }
                                        else
                                            $habilitar = "  ";
                                        $fech_vencmodulo = ModuloGetVar('app', 'AdminFarmacia', 'dias_vencimiento_product_bodega_farmacia_' . $farmacia);
                                        $fecha = $v['fecha_vencimiento'];  //esta es la que viene de la DB
                                        list($ano, $mes, $dia) = split('[/.-]', $fecha);
                                        $fecha = $mes . "/" . $dia . "/" . $ano;

                                        $fecha_actual = date("m/d/Y");
                                        $fecha_compara_actual = date("Y-m-d");
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
                                        if ($vencido == 0) {

                                            $html .= "                                        <tr class=\"modulo_list_claro\">";
                                            $html .= "                                           <td>";
                                            $html .= "                                             <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"" . $v['codigo_producto'] . "\" name=\"codigo_producto" . $i . "\" id=\"codigo_producto" . $i . "\" >";
                                            $html .= "                                            </td>";
                                            $html .= "                                           <td>" . $v['producto'] . " ";
                                            $html .= "                                            </td>";
                                            $html .= "                                           <td>";
                                            $html .= "                                             <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"" . $v['lote'] . "\" name=\"lote" . $i . "\" id=\"lote" . $i . "\" >";
                                            $html .= "                                            </td>";
                                            $html .= "                                           <td>";
                                            $fecha_vencimiento = explode("-", $v['fecha_vencimiento']);
                                            $fechavencimiento = $fecha_vencimiento[2] . "-" . $fecha_vencimiento[1] . "-" . $fecha_vencimiento[0];
                                            $html .= "                                               <input " . $color . "  type=\"text\" readonly=\"text\" class=\"input-text\" value=\"" . $fechavencimiento . "\" name=\"fecha_vencimiento" . $i . "\" id=\"fecha_vencimiento" . $i . "\" >";
                                            $html .= "                                              </td>";
                                            $html .= "                                             <td>";
                                            $html .= "                                              <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"" . $v['existencia_actual'] . "\" name=\"existencia_actual" . $i . "\" id=\"existencia_actual" . $i . "\" >";
                                            $html .= "                                           </td>";
                                            $html .= "                                              <td>";
                                            $html .= "                                                <input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"cantidad" . $i . "\" id=\"cantidad" . $evolucion . "@" . $valor['codigo_producto'] . "" . $i . "\"  value=\"$cantidad_lote\" onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('cantidad" . $evolucion . "@" . $valor['codigo_producto'] . "" . $i . "',xGetElementById('cantidad" . $evolucion . "@" . $valor['codigo_producto'] . "" . $i . "').value,'" . $v['existencia_actual'] . "','hell$i');\">";
                                            $html .= "                                             </td>";
                                            $html .= "                                           <td align=\"center\">";
                                            if ($vencido != 1)
                                                $html .= "                                                <input " . $habilitar . " style=\"width:100%\" type=\"checkbox\" class=\"input-text\" name=\"" . $i . "\" id=\"" . $i . "\" value=\"" . $i . "\" >";
                                            $html .= "                                               <input type=\"hidden\" name=\"registros_\" id=\"registros_\" value=\"" . $i . "\" >";
                                            $html .= "                                             </td>";
                                            $html .= "                                       </tr>";
                                            $i++;
                                        }
                                    }

                                    $html .= "                                       <tr>";
                                    $html .= "                                              <td colspan=\"4\" align=\"center\">";
                                    $html .= "                            <div class=\"label_error\" id=\"" . $valor['evolucion_id'] . "@" . $valor['codigo_producto'] . "\"></div>";
                                    $html .= "                                              </td>";
                                    $html .= "                                          </tr>";
                                    $html .= "                                     </table>\n";
                                    $html .= "                         </td>";
                                    $html .= "                      </tr>\n";
                                    $html .= "                                          <tr >\n";
                                    $html .= "                                         <td class=\"modulo_table_list_title\"   colspan=\"4\" align=\"right\">";
                                    $html .= "                          <input type=\"hidden\" name=\"evolucion\" id=\"evolucion\" value=\"" . $evolucion . "\">";
                                    $html .= "                          <input type=\"hidden\" name=\"bodega_\" id=\"bodega_\" value=\"" . $FormularioBuscador['bodega'] . "\">";

                                    $html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"GUARDAR TEMPORAL...\" onclick=\"xajax_GuardarPT(xajax.getFormValues('forma" . $evolucion . "@" . $valor['codigo_producto'] . "','" . $evolucion . "'));\">";
                                    $html .= "                                          </td>";
                                    $html .= "                                        </tr>\n";
                                }else {

                                    $html .= "<table align=\"center\" border=\"0\" width=\"30%\" >\n";
                                    $html .= "  <tr class=\"label_error\">\n";
                                    $html .= "      <td  class=\"label_error\"  colspan=\"15\" align=\"CENTER\">NO SE ENCONTRARON EXISTENCIAS PARA ESTE PRODUCTO</td>\n";
                                    $html .= "  </tr >\n";
                                    $html .= "</table>";
                                }
                            } 
                            $html .= "                 </table>\n";
                            $html .= "              </form>";
                            $html .= "                <br>\n";
                        } else {

                            $html .= "<table width=\"70%\" align=\"center\"  class=\"modulo_table_list\">";
                            $html .= "<tr class=\"modulo_list_oscuro\">\n";
                            $html .= " <td align=\"left\" width=\"1%\" >";
                            $html .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                            $html .= " </td>";
                            $html .= " <td align=\"left\" width=\"1%\" bgcolor=\"#26A9BA\" title=\"FECHA LIMITE HA CADUCADO\" >";
                            $html .= "";
                            $html .= " </td>";
                            $html .= " <td    bgcolor=\"#BCEAF0\">";
                            $html .= "    <table>";
                            $html .= "      <tr>";
                            $html .= "        <td  align=\"left\" width=\"94%\" class=\"label\" >";
                            $html .= "           Medicamento: {$valor['codigo_producto']}-{$valor['descripcion_prod']}";
                            $html .= "        </td>";
                            $html .= "       </tr>";
                            $html .= "       <tr>";
                            $html .= "        <td  align=\"left\" width=\"94%\" class=\"label\" >";
                            $html .= "         La fecha limite para entregar ha caducado, necesita ser formulado nuevamente.";
                            $html .= "         </td>";
                            $html .= "      </tr>";
                            $html .= "   </table>";
                            $html .= " </td>\n";
                            $html .= " </tr>\n";
                            $html .= "</table >";
                            $obje->tratamiento_finalizado($evolucion, $valor['codigo_producto']);
                        }
                    } else {
                        $html .= "<table width=\"70%\" align=\"center\"  class=\"modulo_table_list\">";
                        $html .= "<tr class=\"modulo_list_oscuro\">\n";
                        $html .= "  <td align=\"left\" width=\"1%\">";
                        $html .= "   <img border=\"0\"  title=\"MEDICAMENTO DISPENSADO\" src=\"" . GetThemePath() . "/images/alarma.gif\">\n";
                        $html .= "  </td>";
                        $html .= " <td align=\"left\" width=\"1%\" bgcolor=\"#05E6D7\" title=\"PROD. VENCIDO\" >";
                        $html .= "";
                        $html .= " </td>";
                        $html .= " <td width=\"98%\" bgcolor=\"#ACF6F1\" >";
                        $html .= "  <table border='1' width=\"100%\">";
                        $html .= "    <tr>";
                        $html .= "      <td colspan='3' align=\"left\" class=\"label\">";
                        $html .= "        Medicamento: {$valor['codigo_producto']}-{$valor['descripcion_prod']}";
                        $html .= "     </td>";
                        $html .= "      <td colspan='1' align=\"left\" class=\"label\">";
                        $html .= "       Evoluci&oacute;n No : " . $evolucion . "\n  ";
                        $html .= "      </td>";
                        $html .= "     </tr>";
                        $html .= "     <tr>";
                        $html .= "      <td colspan='4' align=\"left\" class=\"label\">";
                        $html .= "       Este Medicamento fue despachado hace menos de  $dias_dipensados DIA(S)";//Este Medicamento fue despachado hace menos de
                        $html .= "     </td>";
                        $html .= "     </tr>";
                        $html .= "     <tr>";
                        $html .= "     <td align=\"left\" class=\"label\" width=\"25%\">";
                        $html .= "     Fecha Dispensaci&oacute;n : " . $datos_ex['fecha_registro'] . "\n ";
                        $html .= "     </td>";
                        $html .= "     <td align=\"left\" class=\"label\" width=\"25%\">";
                        $html .= "       Cantidad Despachada : " . round($datos_ex['unidades']) . "\n ";
                        $html .= "     </td>";
                        $html .= "     <td align=\"left\" class=\"label\" width=\"25%\">";
                        $html .= "      Usario que Despacho  : " . $datos_ex['nombre'] . "  \n ";
                        $html .= "     </td>";
                        $html .= "     <td align=\"left\" class=\"label\" width=\"25%\">";
                        $html .= "      Lugar de Despacho :  " . $datos_ex['razon_social'] . "  \n ";
                        $html .= "     </td>";
                        $html .= "    </tr>";
                        $html .= "   </table>";
                        $html .= "   <br>";
                        
                        if ($privilegios['sw_privilegios'] == '1') {

                            $html .= "  <table align=\"center\" border=\"0\" width=\"30%\" >\n";
                            $autorizacion = '1';
                            $html .= "  <tr class=\"formulacion_table_list\">\n";
                            $html .= "      <td   colspan=\"15\" align=\"CENTER\">OBSERVACIONES:</td>\n";
                            $html .= "  </tr >\n";
                            $html .= "  <tr class=\"modulo_table_list_title\">\n";
                            $html .= "      <td   colspan=\"13\"  align=\"left\" class=\"modulo_list_claro\"> <textarea  onkeypress=\"return max(event)\"  name=\"observaciones\"  id=\"observaciones\"   rows=\"2\"  style=\"width:100%\"></textarea>\n";
                            $html .= "       </td>\n";
                            $html .= "  </tr >\n";
                            $html .= "    <tr  align=\"center\">";
                            $html .= "      <td  >";
                            $html .= "      <input type=\"button\" class=\"input-submit\" value=\"AUTORIZAR DESPACHO DEL MEDICAMENTO\" style=\"width:100%\" onclick=\"xajax_Autorizacion_despacho(xajax.getFormValues('buscador'),'" . $evolucion . "','" . $bodega_otra . "',document.getElementById('observaciones').value,'" . $valor['codigo_producto'] . "');\" >";
                            $html .= "      </td>";
                            $html .= "    </tr>\n";
                            $html .= "    </table>";
                        }
                        $html .= "</td>";
                        $html .= "</tr>";
                        $html .= "</table>";
                        
                    }
                } else {
                $html .= "<table width=\"70%\" align=\"center\"  class=\"modulo_table_list\">";
                $html .= "<tr class=\"modulo_list_oscuro\">\n";
                $html .= " <td align=\"left\" width=\"1%\" >";
                $html .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                $html .= " </td>";
                $html .= " <td align=\"left\" width=\"1%\" bgcolor=\"#31860D\" title=\"FECHA LIMITE HA CADUCADO\" >";
                $html .= "";
                $html .= " </td>";
                $html .= " <td    bgcolor=\"#D8FAC9\">";
                $html .= "    <table>";
                $html .= "      <tr>";
                $html .= "        <td  align=\"left\" width=\"94%\" class=\"label\" >";
                $html .= "           Medicamento: {$valor['codigo_producto']}-{$valor['descripcion_prod']}";
                $html .= "        </td>";
                $html .= "       </tr>";
                $html .= "       <tr>";
                $html .= "        <td  align=\"left\" width=\"94%\" class=\"label\" >";
                $html .= "         Proxima fecha de Entrega  $fecha_proxima_e";
                $html .= "         </td>";
                $html .= "      </tr>";
                $html .= "   </table>";
                $html .= " </td>\n";
                $html .= " </tr>\n";
                $html .= "</table >";
                }                
             }  
            } else {
                 $colores['PV'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_proximo_vencer');
                 $colores['VN'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_vencido');

       
                
//                $html .= "<br>";
                $html .= "<table width=\"70%\" align=\"center\"  class=\"modulo_table_list\">";
                $html .= "<tr class=\"modulo_list_oscuro\">\n";
                $html .= " <td align=\"left\" width=\"1%\">";
                $html .= "  <img border=\"0\"  title=\"MEDICAMENTO DISPENSADO\" src=\"" . GetThemePath() . "/images/alarma.gif\">\n";
                $html .= " </td>";
                $html .= " <td align=\"left\" width=\"1%\" bgcolor=\"#FF0000\" title=\"PROD. VENCIDO\" >";
                $html .= "";
                $html .= " </td>";
                $html .= " <td align=\"left\" width=\"94%\" class=\"label\" >";
                $html .= "  El paciente ya finalizo el tratamiento con este producto [{$valor['codigo_producto']}-{$valor['descripcion_prod']}] <br> Fecha Finalizaci&oacute;n: {$valor['fecha_finalizacion']} ";
                $html .= "  </td>";
                $html .= " </tr>\n";
                $html .= "</table >";
                
                $html .= "<br>";
              
           ////////////////estaba en la 170////////////////         
//                $html .= "<table width=\"70%\" align=\"center\"  class=\"modulo_table_list\">";
//                $html .= "<tr class=\"modulo_list_oscuro\">\n";
//                $html .= " <td align=\"left\" width=\"1%\">";
//                $html .= "  <img border=\"0\"  title=\"MEDICAMENTO DISPENSADO\" src=\"" . GetThemePath() . "/images/alarma.gif\">\n";
//                $html .= " </td>";
//                $html .= " <td align=\"left\" width=\"1%\" bgcolor=\"#8C5B9A\" title=\"PROD. VENCIDO\" >";
//                $html .= "";
//                $html .= " </td>";
//                $html .= " <td align=\"left\" width=\"94%\" class=\"label\" bgcolor=\"#E1C9E7\" >";
//                $html .= "         La formula se vencio en la fecha: {$fecha_finalizacion}";
//                $html .= "  </td>";
//                $html .= " </tr>\n";
//                $html .= "</table >";
                /////////////////////////////////////
                $obje->tratamiento_finalizado($evolucion, $valor['codigo_producto']);
               // echo "<pre>";print_r($datos_medicamentos[$value['fecha_entrega']]);exit;
            }
        }
        $html .= "<br>";
    }
    /*
     * Informacion de los Medicamentos Dispensados al Paciente de manera normal y Los Pendientes
     */
    $medicamentos = $obje->Medicamentos_Dispensados_Esm_x_lote_total($evolucion);
    $pendientes_dis = $obje->pendientes_dispensados_ent_TOTAL($evolucion);

    if (!empty($medicamentos)) {

        //===== Permite Mostrar la ultima dispensacion ========    
        $datos_medicamentos = array();

        foreach ($medicamentos as $key => $value) {
            $datos_medicamentos[$value['fecha_entrega']][] = $value;
        }

        $cantidad_dispensacion = 1;
        foreach ($datos_medicamentos as $fecha_dispensacion => $medicamentos_dispensados) {

            $html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
            $html .= "  <tr>\n";
            $html .= "    <td>\n";
            $html .= "      <fieldset class=\"fieldset\">\n";
            $html .= "        <legend class=\"normal_10AN\">MEDICAMENTOS DISPENSADOS FECHA. {$fecha_dispensacion} -ENTREGA No. {$cantidad_dispensacion}</legend>\n";
            $html .= "        <table width=\"100%\" cellspacing=\"2\">\n";
            $html .= "          <tr>\n";
            $html .= "            <td align=\"center\">\n";
            $html .= "              <table width=\"100%\" class=\"label\" $style>\n";
            $html .= "                <tr >\n";

            $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CODIGO</td>\n";
            $html .= "                  <td colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >MEDICAMENTO</td>\n";
            $html .= "                  <td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA VENC</td>\n";
            $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >LOTE</td>\n";
            $html .= "                  <td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CANTIDAD</td>\n";
            $html .= "                  </td>\n";
            $html .= "                </tr>\n";
            foreach ($medicamentos_dispensados as $key => $fila) {
                $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";
                $html .= "  <tr class=\"" . $est . "\"  onmouseout=mOut(this,\"" . $back . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
                $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >" . $fila['codigo_producto'] . "</td>\n";
                $html .= "                  <td colspan=\"2\" style=\"text-align:left;text-indent:3pt\"  >" . $fila['descripcion_prod'] . "</td>\n";
                $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >" . $fila['fecha_vencimiento'] . "</td>\n";
                $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >" . $fila['lote'] . "</td>\n";
                $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" >" . round($fila['numero_unidades']) . "</td>\n";

                $html .= "                  </td>\n";
                $html .= "                </tr>\n";
            }
            $html .= "              </table>\n";
            $html .= "            <td>\n";
            $html .= "          </tr>\n";
            $html .= "          <tr>\n";
            $html .= "            <td align=\"center\">\n";
            $html .= "            </td>\n";
            $html .= "          </tr>\n";
            $html .= "        </table>\n";
            $html .= "      </fieldset>\n";
            $html .= "    </td>\n";
            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $cantidad_dispensacion++;
        }
        //exit();




        /* $html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
          $html .= "  <tr>\n";
          $html .= "    <td>\n";
          $html .= "      <fieldset class=\"fieldset\">\n";
          $html .= "        <legend class=\"normal_10AN\">MEDICAMENTOS DISPENSADOS</legend>\n";
          $html .= "        <table width=\"100%\" cellspacing=\"2\">\n";
          $html .= "          <tr>\n";
          $html .= "            <td align=\"center\">\n";
          $html .= "              <table width=\"100%\" class=\"label\" $style>\n";
          $html .= "                <tr >\n";

          $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CODIGO</td>\n";
          $html .= "                  <td colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >MEDICAMENTO</td>\n";
          $html .= "                  <td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA VENC</td>\n";
          $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >LOTE</td>\n";
          $html .= "                  <td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CANTIDAD</td>\n";
          $html .= "                  </td>\n";
          $html .= "                </tr>\n";


          foreach ($medicamentos as $item => $fila) {
          $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";
          $html .= "  <tr class=\"" . $est . "\"  onmouseout=mOut(this,\"" . $back . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
          $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >" . $fila['codigo_producto'] . "</td>\n";
          $html .= "                  <td colspan=\"2\" style=\"text-align:left;text-indent:3pt\"  >" . $fila['descripcion_prod'] . "</td>\n";
          $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >" . $fila['fecha_vencimiento'] . "</td>\n";
          $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >" . $fila['lote'] . "</td>\n";
          $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" >" . round($fila['numero_unidades']) . "</td>\n";

          $html .= "                  </td>\n";
          $html .= "                </tr>\n";
          }

          $html .= "              </table>\n";
          $html .= "            <td>\n";
          $html .= "          </tr>\n";
          $html .= "          <tr>\n";
          $html .= "            <td align=\"center\">\n";
          $html .= "            </td>\n";
          $html .= "          </tr>\n";
          $html .= "        </table>\n";
          $html .= "      </fieldset>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          $html .= "</table>\n"; */
    }

    if (!empty($pendientes_dis)) {
        $html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
        $html .= "      <fieldset class=\"fieldset\">\n";
        $html .= "        <legend class=\"normal_10AN\">MEDICAMENTOS PENDIENTES-DISPENSADOS </legend>\n";
        $html .= "        <table width=\"100%\" cellspacing=\"2\">\n";
        $html .= "          <tr>\n";
        $html .= "            <td align=\"center\">\n";
        $html .= "              <table width=\"100%\" class=\"label\" $style>\n";
        $html .= "                <tr >\n";
        $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CODIGO</td>\n";
        $html .= "                  <td colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >MEDICAMENTO</td>\n";
        $html .= "                  <td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA VENC</td>\n";
        $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >LOTE</td>\n";
        $html .= "                  <td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CANTIDAD</td>\n";
        $html .= "                  </td>\n";
        $html .= "                </tr>\n";

        foreach ($pendientes_dis as $item => $fila) {
            $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";
            $html .= "  <tr class=\"" . $est . "\"  onmouseout=mOut(this,\"" . $back . "\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
            $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" >" . $fila['codigo_producto'] . "</td>\n";
            $html .= "                  <td colspan=\"2\" style=\"text-align:left;text-indent:3pt\"  >" . $fila['descripcion_prod'] . "</td>\n";
            $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >" . $fila['fecha_vencimiento'] . "</td>\n";
            $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >" . $fila['lote'] . "</td>\n";
            $html .= "                  <td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >" . round($fila['numero_unidades']) . "</td>\n";

            $html .= "                  </td>\n";
            $html .= "                </tr>\n";
        }


        $html .= "              </table>\n";

        $html .= "            <td>\n";
        $html .= "          </tr>\n";
        $html .= "          <tr>\n";
        $html .= "            <td align=\"center\">\n";
        $html .= "            </td>\n";
        $html .= "          </tr>\n";
        $html .= "        </table>\n";
        $html .= "      </fieldset>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
    }

    /*
     * FIN INFORMACION
     */


    $objResponse->assign("BuscadorProductos", "innerHTML", $html);
    $objResponse->assign("fecha_actual", "innerHTML", $today);
    return $objResponse;
}

/* GUARDAR TMP */

function GuardarPT($Formulario, $evolucion) {

    $objResponse = new xajaxResponse();
    $obje = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
    $empresa = SessionGetVar("DatosEmpresaAF");

    $k = 0;
    for ($i = 0; $i <= $Formulario['registros_']; $i++) {


        if ($Formulario[$i] != "" && $Formulario['cantidad' . $i] != "") {
            $cantidad = $obje->Cantidad_ProductoTemporal($Formulario['evolucion'], $Formulario['principio_activo'], $Formulario['medicamento_formulado']);


            if (($cantidad['total'] + $Formulario['cantidad' . $i]) <= $Formulario['cantidad_solicitada']) {

                if ($Formulario['cantidad' . $i] == "") {
                    $objResponse->assign('error_doc', "innerHTML", "NO HA DILIGENCIADO UNA CANTIDAD A INGRESAR");
                }

                $productoTmp = $obje->buscarProductoTemporal($Formulario['evolucion'], $Formulario['codigo_producto' . $i], $Formulario['fecha_vencimiento' . $i], $Formulario['lote' . $i]);
                if (count($productoTmp) == 0) {

                    $Retorno = $obje->GuardarTemporal($Formulario['evolucion'], $Formulario['codigo_producto' . $i], $Formulario['cantidad' . $i], $Formulario['fecha_vencimiento' . $i], $Formulario['lote' . $i], $empresa, $Formulario['bodega_'], $Formulario['medicamento_formulado']);

                    $objResponse->assign("" . $Formulario['evolucion'] . "@" . $Formulario['codigo_producto'] . "", "innerHTML", $consulta->mensajeDeError);
                    if ($Retorno)
                        $k++;
                }
            }
        }
    }

    if ($k > 0) {
        //  $objResponse->script(" Recargar_informacion('".$empresa['bodega']."');");

        $objResponse->script("xajax_BuscarProducto1(xajax.getFormValues('buscador'),'" . $Formulario['evolucion'] . "',1);");
        $objResponse->script("xajax_MostrarProductox('" . $Formulario['evolucion'] . "');");
    }
    if ($Retorno === false) {
        $objResponse->assign('error_doc', 'innerHTML', $obje->mensajeDeError);
    }
    return $objResponse;
}

/* MOSTRAR PRODUCTOS TEMPORALES */

//function MostrarProductox($evolucion,$bandera=0) {
function MostrarProductox($evolucion) {
    $objResponse = new xajaxResponse();

    $obje = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
    $empresa = SessionGetVar("DatosEmpresaAF");
    $farmacia = $empresa['empresa_id'];

    $vector = $obje->Buscar_producto_tmp_c($evolucion);

    if (!empty($vector)) {


        $html .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "                    <tr  class=\"formulacion_table_list\" >\n";
        $html .= "                       <td align=\"center\" width=\"10%\">\n";
        $html .= "                        <a title='CODIGO DEL PRODUCTO'>CODIGO<a> ";
        $html .= "                      </td>\n";
        $html .= "                       <td align=\"center\" width=\"70%\">\n";
        $html .= "                        <a title='DESCRIPCION DEL PRODUCTO'>DESCRIPCION<a>";
        $html .= "                      </td>\n";
        $html .= "                       <td width=\"10%\">LOTE</td>\n";
        $html .= "                      <td width=\"10%\">FECHA VENCIMIENTO</td>\n";
        $html .= "                       <td align=\"center\" width=\"5%\">\n";
        $html .= "                        CANTIDAD";
        $html .= "                      </td>\n";

        $html .= "                      <td align=\"center\" width=\"5%\">\n";
        $html .= "                         <a title='ELIMINAR REGISTRO'>X<a>";
        $html .= "                       </td>\n";
        $html .= "                    </tr>\n";

        foreach ($vector as $key => $detalle) {
            $fech_vencmodulo = ModuloGetVar('app', 'AdminFarmacia', 'dias_vencimiento_product_bodega_farmacia_' . $farmacia);

            $fecha = $detalle['fecha_vencimiento'];  //esta es la que viene de la DB
            list($ano, $mes, $dia) = split('[/.-]', $fecha);
            $fecha = $mes . "/" . $dia . "/" . $ano;

            $fecha_actual = date("m/d/Y");
            $fecha_compara_actual = date("Y-m-d");
            //Mes/Dia/Aï¿½o  "02/02/2010
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

            $html .= "                     <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
            $html .= "                       <td align=\"left\" class=\"label_mark\">\n";
            $html .= "                        " . $detalle['codigo_producto'];
            $html .= "                       </td>\n";
            $html .= "                       <td align=\"left\" class=\"label_mark\">\n";
            $html .= "                         " . $detalle['descripcion_prod'];
            $html .= "                      </td>\n";
            $html .= "                       <td class=\"label_mark\">" . $detalle['lote'] . "</td>\n";
            $html .= "                      <td align=\"center\" class=\"label_mark\" " . $color . ">" . $detalle['fecha_vencimiento'] . "</td>\n";
            $html .= "                      <td align=\"right\" class=\"label_mark\">\n";
            $html .= "                        " . $detalle['cantidad_despachada'];
            $html .= "                      </td>\n";


            $html .= "        <td  width=\"5%\" align=\"center\"  >\n";
            $html .= "          <a href=\"#\" onclick=\"xajax_Eliminar_codigo_prodcto_d('" . $evolucion . "','" . $detalle['codigo_producto'] . "','" . $detalle['hc_dispen_tmp_id'] . "')\" class=\"label_error\"  ><img src=\"" . GetThemePath() . "/images/delete2.gif\" border='0' >\n";
            $html .= "          </a></center>\n";
            $html .= "      </td>\n";

            $html .= "                   </tr>\n";
        }

        $html .= "                    </table><BR>\n";

        $html .= "                 <table width=\"75%\" align=\"center\" >\n";
        $html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
        $html .= "                          <input type=\"hidden\" name=\"evolucion\" id=\"evolucion\" value=\"" . $evolucion . "\">";
        $html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"REALIZAR ENTREGA...\" onclick=\"xajax_Cambiarvetana('" . $evolucion . "');\">";
        $html .= "                                          </td>";
        $html .= "                    </table>\n";
    } else {
        $pendientes = $obje->obtenerCantidadPendienteFormula($evolucion);

        /* echo "<pre>";
          print_r($pendientes);
          echo "</pre>";
          exit(); */

        if ($pendientes['cantidad_pendiente'] > 0) {
            $todo_pendiente = '1';
            $html .= "                 <table width=\"75%\" align=\"center\" >\n";
            $html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
            $html .= "                          <input type=\"hidden\" name=\"formula_id\" id=\"formula_id\" value=\"" . $formula_id . "\">";
            $html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"TODO PENDIENTE\" onclick=\"xajax_Cambiarvetana('" . $evolucion . "','" . $todo_pendiente . "');\">";
            $html .= "                                          </td>";
            $html .= "                    </table>\n";

            $html .= "                  <table width=\"80%\" align=\"center\">\n";
            $html .= "                   <tr>\n";
            $html .= "                   <td align=\"center\">\n";
//            if($bandera==0){
            $html .= "                      <label class='label_error'> ESTE DOCUMENTO NO TIENE PRODUCTOS ASIGNADOS</label>";
//            }
            $html .= "                   </td>\n";
            $html .= "                  </tr>\n";
            $html .= "                  </table>\n";
        } else {
            $html .= "                  <table width=\"80%\" align=\"center\">\n";
            $html .= "                   <tr>\n";
            $html .= "                   <td align=\"center\">\n";
            $html .= "                      <label class='label_error'> LA FORMULA FUE DESPACHADA COMPLETAMENTE</label>";
            $html .= "                   </td>\n";
            $html .= "                  </tr>\n";
            $html .= "                  </table>\n";
        }
    }

    $objResponse->assign("productostmp", "innerHTML", $html);
    return $objResponse;
}

/* ELIMINAR PRODUCTO */

function Eliminar_codigo_prodcto_d($evolucion, $codigo_producto, $serial) {
    $objResponse = new xajaxResponse();
    $obje = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
    $empresa = SessionGetVar("DatosEmpresaAF");
    $farmacia = $empresa['empresa_id'];

    $vector = $obje->EliminarDatosTMP_DISPENSACION($evolucion, $codigo_producto, $serial);

    if ($vector) {
        $objResponse->script("xajax_MostrarProductox('" . $evolucion . "');");
    }
    return $objResponse;
}

/* CAMBIAR DE VENTANA */

function Cambiarvetana($evolucion, $todopendiente) {
    $objResponse = new xajaxResponse();

    $url = ModuloGetURL("app", "Dispensacion", "controller", "Preparar_Documento_Dispensacion", array("evolucion" => $evolucion, "todopendiente" => $todopendiente));
    $objResponse->script('
             window.location="' . $url . '";
              ');
    return $objResponse;
}

/* DISPENSACION DE LO PENDIENTE */

function BuscarProducto2($FormularioBuscador, $evolucion, $bodega_otra) {
    $objResponse = new xajaxResponse();
    $obje = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
    $busqueda = $obje->Consultar_Medicamentos_Detalle_P($FormularioBuscador, $evolucion);


    $cantidad_entrega = $busqueda[0]['cantidad'];

    $empresa = SessionGetVar("DatosEmpresaAF");
    $farmacia = $empresa['empresa_id'];
    $centrou = $empresa['centro_utilidad'];

    $html .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
    $html .= "                 </div>\n";
    $html .= "                                    <div id=\"error\" class='label_error'></div>";

    foreach ($busqueda as $k => $valor) {
        $cantidad = $obje->Cantidad_ProductoTemporal($evolucion, $valor['cod_principio_activo'], $valor['codigo_producto']);

        $cantidad_entrega = round($valor['cantidad']);
        $cantidad_ = 0;
        if ($cantidad['codigo_formulado'] == $valor['codigo_producto']) {
            $cantidad_ = $cantidad['total'];
        }
        $cantidad_final = $cantidad_entrega - $cantidad_;


        $html .= "                 <form id=\"forma" . $evolucion . "@" . $valor['codigo_producto'] . "\" name=\"" . $evolucion . "@" . $valor['codigo_producto'] . "\" action=\"\" method=\"post\">\n";
        $html .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                     <td width=\"50%\">PRODUCTO: " . $valor['codigo_producto'] . " &nbsp; " . $valor['descripcion_prod'] . ". </td>
        <td>CANTIDAD SOLICITADA.. <input readonly=\"true\" type=\"input-text\" name=\"cantidad_solicitada\" id=\"cantidad_solicitada\" value=\"" . $cantidad_entrega . "\" class=\"input-text\"></td><td>CANTIDAD PENDIENTE <input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" id=\"cantidad_pendiente\" value=\"" . ($cantidad_entrega - $cantidad_) . "\" class=\"input-text\"></td>\n";
        $html .= "                        <input type=\"hidden\" name=\"evolucion\" id=\"evolucion\" value=\"" . $evolucion . "\">";
        $html .= "                        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"" . $valor['codigo_producto'] . "\">";
        $html .= "                        <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"" . $FormularioBuscador['bodega'] . "\">";
        $html .= "                        <input type=\"hidden\" name=\"principio_activo\" id=\"principio_activo\" value=\"" . $valor['cod_principio_activo'] . "\">";
        $html .= "                        <input type=\"hidden\" name=\"medicamento_formulado\" id=\"medicamento_formulado\" value=\"" . $valor['codigo_producto'] . "\">";
        $html .= "                     </td>";
        $html .= "                    </tr>\n";

        $html .= "                   <tr class=\"modulo_list_claro\">\n";
        $html .= "                      <td colspan=\"3\" align=\"center\">";

        if ($cantidad_final != 0) {
            $Existencias = $obje->Consultar_ExistenciasBodegas($valor['cod_principio_activo'], $FormularioBuscador, $farmacia, $centrou, $bodega, $valor['codigo_producto']);

            if (!empty($Existencias)) {
                $html .= "                                   <table width=\"100%\" align=\"center\" rules=\"all\" class=\"modulo_table_list\">";
                $html .= "                                       <tr class=\"modulo_table_list_title\">\n";
                $html .= "                                       <td width=\"10%\">";
                $html .= "                                            CODIGO  ";
                $html .= "                                        </td>";
                $html .= "                                       <td width=\"55%\">";
                $html .= "                                            PRODUCTO  ";
                $html .= "                                        </td>";
                $html .= "                                       <td width=\"10%\">";
                $html .= "                                            LOTE";
                $html .= "                                        </td>";
                $html .= "                                        <td width=\"10%\">";
                $html .= "                                              FECHA VENCIMIENTO";
                $html .= "                                        </td>";
                $html .= "                                       <td width=\"5%\">";
                $html .= "                                             EXIST.";
                $html .= "                                      </td>";
                $html .= "                                        <td width=\"5%\">";
                $html .= "                                              CANTIDAD";
                $html .= "                                        </td>";
                $html .= "                                        <td width=\"5%\">";
                $html .= "                                              SEL";
                $html .= "                                        </td>";
                $html .= "                                        </tr>\n";

                $i = 0;

                foreach ($Existencias as $key => $v) {

                    $ProductoLote = $obje->Buscar_ProductoLote($evolucion, $valor['codigo_producto'], $v['lote'], $v['codigo_producto']);
                    if (!empty($ProductoLote)) {
                        $habilitar = " checked=\"true\" disabled ";
                    }
                    else
                        $habilitar = "  ";


                    $fech_vencmodulo = ModuloGetVar('app', 'AdminFarmacia', 'dias_vencimiento_product_bodega_farmacia_' . $farmacia);
                    $fecha = $v['fecha_vencimiento'];  //esta es la que viene de la DB
                    list($ano, $mes, $dia) = split('[/.-]', $fecha);
                    $fecha = $mes . "/" . $dia . "/" . $ano;

                    $fecha_actual = date("m/d/Y");
                    $fecha_compara_actual = date("Y-m-d");

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
                    if ($vencido == 0) {

                        $html .= "                                        <tr class=\"modulo_list_claro\">";
                        $html .= "                                           <td>";
                        $html .= "                                             <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"" . $v['codigo_producto'] . "\" name=\"codigo_producto" . $i . "\" id=\"codigo_producto" . $i . "\" >";
                        $html .= "                                            </td>";
                        $html .= "                                           <td>" . $v['producto'] . " ";
                        $html .= "                                            </td>";
                        $html .= "                                           <td>";
                        $html .= "                                             <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"" . $v['lote'] . "\" name=\"lote" . $i . "\" id=\"lote" . $i . "\" >";
                        $html .= "                                            </td>";
                        $html .= "                                           <td>";
                        $fecha_vencimiento = explode("-", $v['fecha_vencimiento']);
                        $fechavencimiento = $fecha_vencimiento[2] . "-" . $fecha_vencimiento[1] . "-" . $fecha_vencimiento[0];
                        $html .= "                                               <input " . $color . "  type=\"text\" readonly=\"text\" class=\"input-text\" value=\"" . $fechavencimiento . "\" name=\"fecha_vencimiento" . $i . "\" id=\"fecha_vencimiento" . $i . "\" >";
                        $html .= "                                              </td>";
                        $html .= "                                             <td>";
                        $html .= "                                              <input style=\"width:100%\" type=\"text\" readonly=\"text\" class=\"input-text\" value=\"" . $v['existencia_actual'] . "\" name=\"existencia_actual" . $i . "\" id=\"existencia_actual" . $i . "\" >";
                        $html .= "                                           </td>";
                        $html .= "                                              <td>";
                        $html .= "                                                <input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"cantidad" . $i . "\" id=\"cantidad" . $valor['orden_requisicion_id'] . "@" . $valor['codigo_producto'] . "" . $i . "\"   value=\"$cantidad_lote\"  onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('cantidad" . $valor['orden_requisicion_id'] . "@" . $valor['codigo_producto'] . "" . $i . "',xGetElementById('cantidad" . $valor['orden_requisicion_id'] . "@" . $valor['codigo_producto'] . "" . $i . "').value,'" . $v['existencia_actual'] . "','hell$i');\">";
                        $html .= "                                             </td>";
                        $html .= "                                           <td align=\"center\">";
                        if ($vencido != 1)
                            $html .= "                                                <input " . $habilitar . " style=\"width:100%\" type=\"checkbox\" class=\"input-text\" name=\"" . $i . "\" id=\"" . $i . "\" value=\"" . $i . "\" >";
                        $html .= "                                               <input type=\"hidden\" name=\"registros__\" id=\"registros__\" value=\"" . $i . "\" >";
                        $html .= "                                             </td>";
                        $html .= "                                       </tr>";
                        $i++;
                    }
                }
                $html .= "                                       <tr>";
                $html .= "                                              <td colspan=\"4\" align=\"center\">";
                $html .= "                            <div class=\"label_error\" id=\"" . $valor['orden_requisicion_id'] . "@" . $valor['codigo_producto'] . "\"></div>";
                $html .= "                                              </td>";
                $html .= "                                          </tr>";
                $html .= "                                     </table>\n";
                $html .= "                         </td>";
                $html .= "                      </tr>\n";
                $html .= "                                          <tr class=\"modulo_table_list_title\">\n";
                $html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
                $html .= "                          <input type=\"hidden\" name=\"evolucion\" id=\"evolucion\" value=\"" . $evolucion . "\">";

                $html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"GUARDAR TEMPORAL\" onclick=\"xajax_GuardarPTP(xajax.getFormValues('forma" . $evolucion . "@" . $valor['codigo_producto'] . "'),'" . $evolucion . "','" . $valor['f_rango'] . "');\">";
                $html .= "                                          </td>";
                $html .= "                                        </tr>\n";
            }else {
                $html .= "  <table align=\"center\" border=\"0\" width=\"30%\" >\n";

                $html .= "  <tr class=\"label_error\">\n";
                $html .= "      <td  class=\"label_error\"  colspan=\"15\" align=\"CENTER\">NO SE ENCONTRARON EXISTENCIAS PARA ESTE PRODUCTO</td>\n";
                $html .= "  </tr >\n";
                $html .= "    </table>";
            }

            $html .= "                 </table>\n";
            $html .= "              </form>";
            $html .= "                <br>\n";
        }
    }

    $objResponse->assign("BuscadorProductos", "innerHTML", $html);
    return $objResponse;
}

/* GUARDAR TMP PENDIENTES  */

function GuardarPTP($Formulario, $evolucion, $f_rango) {
    $objResponse = new xajaxResponse();


    $obje = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
    $empresa = SessionGetVar("DatosEmpresaAF");


    $k = 0;
    for ($i = 0; $i <= $Formulario['registros__']; $i++) {
        if ($Formulario[$i] != "" && $Formulario['cantidad' . $i] != "") {

            $cantidad = $obje->Cantidad_ProductoTemporal($evolucion, $Formulario['principio_activo'], $Formulario['medicamento_formulado']);
            if (($cantidad['total'] + $Formulario['cantidad' . $i]) <= $Formulario['cantidad_solicitada']) {
                if ($Formulario['cantidad' . $i] == "") {
                    $objResponse->assign('error_doc', "innerHTML", "NO HA DILIGENCIADO UNA CANTIDAD A INGRESAR");
                }
                $Retorno = $obje->GuardarTemporal($evolucion, $Formulario['codigo_producto' . $i], $Formulario['cantidad' . $i], $Formulario['fecha_vencimiento' . $i], $Formulario['lote' . $i], $empresa, $Formulario['bodega'], $Formulario['medicamento_formulado'], $f_rango);
                $objResponse->assign("" . $evolucion . "@" . $Formulario['codigo_producto'] . "", "innerHTML", $consulta->mensajeDeError);


                if ($Retorno)
                    $k++;
            }
        }
    }

    if ($k > 0) {
        //$objResponse->script(" Recargar_informacion('".$empresa['bodega']."');");

        $objResponse->script("xajax_BuscarProducto2(xajax.getFormValues('buscador'),'" . $evolucion . "','');");
        $objResponse->script("xajax_MostrarProductox2('" . $evolucion . "');");
    }
    if ($Retorno === false) {
        $objResponse->assign('error_doc', 'innerHTML', $obje->mensajeDeError);
    }
    return $objResponse;
}

/* MOSTRAR PRODUCTOS TEMPORALES */

function MostrarProductox2($evolucion) {
    $objResponse = new xajaxResponse();
    $obje = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
    $empresa = SessionGetVar("DatosEmpresaAF");
    $farmacia = $empresa['empresa_id'];

    $vector = $obje->Buscar_producto_tmp_c($evolucion);

    if (!empty($vector)) {


        $html .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "                    <tr  class=\"formulacion_table_list\" >\n";
        $html .= "                       <td align=\"center\" width=\"10%\">\n";
        $html .= "                        <a title='CODIGO DEL PRODUCTO'>CODIGO<a> ";
        $html .= "                      </td>\n";
        $html .= "                       <td align=\"center\" width=\"70%\">\n";
        $html .= "                        <a title='DESCRIPCION DEL PRODUCTO'>DESCRIPCION<a>";
        $html .= "                      </td>\n";
        $html .= "                       <td width=\"10%\">LOTE</td>\n";
        $html .= "                      <td width=\"10%\">FECHA VENCIMIENTO</td>\n";
        $html .= "                       <td align=\"center\" width=\"5%\">\n";
        $html .= "                        CANTIDAD";
        $html .= "                      </td>\n";

        $html .= "                      <td align=\"center\" width=\"5%\">\n";
        $html .= "                         <a title='ELIMINAR REGISTRO'>X<a>";
        $html .= "                       </td>\n";
        $html .= "                    </tr>\n";

        foreach ($vector as $key => $detalle) {
            $fech_vencmodulo = ModuloGetVar('app', 'AdminFarmacia', 'dias_vencimiento_product_bodega_farmacia_' . $farmacia);

            $fecha = $detalle['fecha_vencimiento'];  //esta es la que viene de la DB
            list($ano, $mes, $dia) = split('[/.-]', $fecha);
            $fecha = $mes . "/" . $dia . "/" . $ano;

            $fecha_actual = date("m/d/Y");
            $fecha_compara_actual = date("Y-m-d");
            //Mes/Dia/Aï¿½o  "02/02/2010
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

            $html .= "                     <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
            $html .= "                       <td align=\"left\" class=\"label_mark\">\n";
            $html .= "                        " . $detalle['codigo_producto'];
            $html .= "                       </td>\n";
            $html .= "                       <td align=\"left\" class=\"label_mark\">\n";
            $html .= "                         " . $detalle['descripcion_prod'];
            $html .= "                      </td>\n";
            $html .= "                       <td class=\"label_mark\">" . $detalle['lote'] . "</td>\n";
            $html .= "                      <td align=\"center\" class=\"label_mark\" " . $color . ">" . $detalle['fecha_vencimiento'] . "</td>\n";
            $html .= "                      <td align=\"right\" class=\"label_mark\">\n";
            $html .= "                        " . $detalle['cantidad_despachada'];
            $html .= "                      </td>\n";


            $html .= "        <td  align=\"center\"  >\n";
            $html .= "          <a href=\"#\" onclick=\"xajax_Eliminar_codigo_prodcto_d2('" . $evolucion . "','" . $detalle['codigo_producto'] . "','" . $detalle['hc_dispen_tmp_id'] . "')\" class=\"label_error\"  ><img src=\"" . GetThemePath() . "/images/delete2.gif\" border='0' >\n";
            $html .= "          </a></center>\n";
            $html .= "      </td>\n";

            $html .= "                   </tr>\n";
        }

        $html .= "                    </table><BR>\n";

        $html .= "                 <table width=\"75%\" align=\"center\" >\n";
        $html .= "                                         <td width=\"20%\" colspan=\"3\" align=\"center\">";
        $html .= "                          <input type=\"hidden\" name=\"evolucion\" id=\"evolucion\" value=\"" . $evolucion . "\">";
        $html .= "                                                <input class=\"input-submit\" type=\"button\" value=\"REALIZAR ENTREGA.\" onclick=\"xajax_Cambiarvetana2('" . $evolucion . "');\">";
        $html .= "                                          </td>";
        $html .= "                    </table>\n";
    }
    $objResponse->assign("productostmp", "innerHTML", $html);
    return $objResponse;
}

/* ELIMINAR PRODUCTO */

function Eliminar_codigo_prodcto_d2($evolucion, $codigo_producto, $serial) {
    $objResponse = new xajaxResponse();
    $obje = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
    $empresa = SessionGetVar("DatosEmpresaAF");
    $farmacia = $empresa['empresa_id'];
    $vector = $obje->EliminarDatosTMP_DISPENSACION($evolucion, $codigo_producto, $serial);

    if ($vector) {

        $objResponse->script("xajax_MostrarProductox2('" . $evolucion . "');");
    }
    return $objResponse;
}

/* CAMBIAR DE VENTANA PENDIENTES */

function Cambiarvetana2($evolucion) {
    $objResponse = new xajaxResponse();

    $url = ModuloGetURL("app", "Dispensacion", "controller", "Preparar_Documento_Dispensacion_Pendientes", array("evolucion" => $evolucion));
    $objResponse->script('
             window.location="' . $url . '";
              ');
    return $objResponse;
}

/* AUTORIZACION DEL DESPACHO */

function Autorizacion_despacho($Formulario, $evolucion, $bodega_otra, $observacion, $producto) {
    $objResponse = new xajaxResponse();
    $obje = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
    $vector = $obje->UpdateAutorizacion_por_medicamento($evolucion, $observacion, $producto);

    if ($vector == true) {

        $objResponse->script("xajax_BuscarProducto1(xajax.getFormValues('buscador'),'" . $evolucion . "','" . $bodega_otra . "');");
    }
    return $objResponse;
}

function obtener_cantidad_meses($date1, $date2) {

    $ts1 = strtotime($date1);
    $ts2 = strtotime($date2);

    $year1 = date('Y', $ts1);
    $year2 = date('Y', $ts2);

    $month1 = date('m', $ts1);
    $month2 = date('m', $ts2);

    $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

    return $diff;
}

function guardar_tipo_formula($evolucion, $tipo_formula) {

    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");

    $resultado = $sql->guardar_tipo_fornula($evolucion, $tipo_formula);


    if ($resultado) {

        $objResponse->call("habilitar_btn_reclama_paciente(false)");
        $objResponse->alert("Tipo Formula Asignado Correctamente");
    } else {
        $objResponse->call("habilitar_btn_reclama_paciente(true)");
        $objResponse->alert("Ha Ocurrido un ERROR al asignar el tipo de formula");
    }


    return $objResponse;
}
function descartar_despacho($disp_id,$cod_med_id,$nombre_med,$justi){
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
  $resultado = $sql->CambiarestadoPendientesDispensar($disp_id,$justi);
  if($resultado){
     //  $objResponse->alert("SE ACTUALIZO EL ESTADO DEL MEDICAMENTO ".$nombre_med);   
    }else{
      $objResponse->alert("OCURRIO UN ERROR AL ACTUALIZAR EL ESTADO DEL MEDICAMENTO ".$nombre_med);  
    } 
    return $objResponse;
}
?>