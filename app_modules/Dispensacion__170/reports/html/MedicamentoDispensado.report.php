<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: MedicamentoPendienteESM.report.php,v 1.5 2010/07/08  
 * @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja T.
 */

/**
 * Clase Reporte: MedicamentoDispensado_report
 * 
 * @package IPSOFT-SIIS
 * @version $Revision: 1.0
 * @copyright (C) 2010  IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja T.
 */
class MedicamentoDispensado_report {

    var $datos;
    var $title = '';
    var $author = '';
    var $sizepage = 'letter';
    var $Orientation = '';
    var $grayScale = false;
    var $headers = array();
    var $footers = array();

    function MedicamentoDispensado_report($datos = array()) {
        $this->datos = $datos;

        return true;
    }

    function GetMembrete() {

        $Membrete = array('file' => false, 'datos_membrete' => array('titulo' => $titulo, 'logo' => '', 'align' => 'left'));
        return $Membrete;
    }

    function CrearReporte() {

        $html = $this->obtener_cabecera_formula();


        switch ($this->datos['tipo_reporte']) {
            case "0":
                $html .= $this->imprimir_ultima_entrega_medicamentos();
                break;
            case "1":
                $html .= $this->imprimir_entregas_medicamentos();
                break;
            case "2":
                $html .= $this->imprimir_pendientes();
                break;
            default:
                $html .= $this->imprimir_ultima_entrega_medicamentos();
        }

        $html .= $this->obtener_footer_formula();

        return $html;
    }

    function obtener_cabecera_formula() {

        IncludeClass('ConexionBD');
        IncludeClass('DispensacionSQL', '', 'app', 'Dispensacion');

        $sql = new DispensacionSQL();


        $datos_formula = $sql->ObtenerFormulasCabecera_por_evolucion($this->datos['evolucion'],$this->datos['tipo_id_paciente'],$this->datos['paciente_id']);
        $datos_profesional = $sql->Profesional_formula($this->datos['evolucion']);


        $style = "style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:9px\"";

//        $html .= "<br><br><fieldset class=\"fieldset\">\n";
        $html .= "<br><br>\n";
        $html .= "  <p class=\"normal_10AN\">DATOS FORMULA -- ENTREGA DE MEDICAMENTOS</p>\n";
        $html .= "      <table width=\"100%\" border ='0' >\n";
        $html .= "          <tr>\n";
        $html .= "              <td align=\"center\">\n";
        $html .= "                  <table width=\"100%\" class=\"label\" $style>\n";
        $html .= "                      <tr >\n";
        $html .= "                          <td  align=\"left\" width=\"10%\">FECHA DE REGISTRO:</td>\n";
        $html .= "                          <td  align=\"left\" width=\"15%\">" . $datos_formula['fecha_registro'] . " </td>\n";
        $html .= "                          <td  align=\"left\" width=\"10%\">FECHA DE FORMULA:</td>\n";
        $html .= "                          <td  align=\"left\" width=\"15%\">" . $datos_formula['fecha_formulacion'] . " </td>\n";
        $html .= "                          <td  align=\"right\" width=\"45%\">FORMULA No:</td>\n";
        $html .= "                          <td  align=\"left\" width=\"5%\">" . $datos_formula['numero_formula'] . " </td>\n";
        $html .= "                      </tr>\n";
        $html .= "                      <tr>\n";
        $html .= "                          <td  align=\"left\">IDENTIFICACION:</td>\n";
        $html .= "                          <td  align=\"left\" > " . $datos_formula['tipo_id_paciente'] . "  " . $datos_formula['paciente_id'] . " </td>\n";
        $html .= "                          <td  colspan=\"1\" align=\"left\"   >NOMBRE COMPLETO: </td>\n";
        $html .= "                          <td  colspan=\"3\" align=\"left\"   >" . $datos_formula['nombres'] . "" . $datos_formula['apellidos'] . " </td>\n";
        $html .= "                      </tr>\n";

        if ($datos_formula['sexo_id'] == 'M') {
            $sexo = 'MASCULINO';
        } else {
            $sexo = 'FEMENINO';
        }
        list($anio, $mes, $dias) = explode(":", $datos_formula['edad']);

        if ($anio != 0) {
            $edad_t = 'A&Ntilde;OS';
            $edad = $anio;
        }
        if ($anio == 0 and $mes != 0) {
            $edad_t = 'MES';
            $edad = $mes;
        } else {
            if ($anio == 0 and $mes == 0) {
                $edad_t = 'DIAS';
                $edad = $dias;
            }
        }

        $html .= "                      <tr>\n";
        $html .= "                          <td  align=\"left\">EDAD:</td>\n";
        $html .= "                          <td  align=\"left\" >" . $edad . " &nbsp; $edad_t </td>\n";
        $html .= "                          <td  colspan=\"1\" align=\"left\">SEXO: </td>\n";
        $html .= "                          <td  colspan=\"1\" align=\"left\">" . $sexo . "\n </td>\n";
        $html .= "                      </tr>\n";
        $html .= "			<tr>\n";
        $html .= "                          <td  align=\"left\">TELEFONO:</td>\n";
        $html .= "                          <td  align=\"left\" > " . $datos_formula['residencia_telefono'] . " </td>\n";
        $html .= "                          <td  colspan=\"1\" align=\"left\">DIRECCION: </td>\n";
        $html .= "                          <td  colspan=\"1\" align=\"left\">" . $datos_formula['residencia_direccion'] . " </td>\n";
        $html .= "                      </tr>\n";
        $html .= "			<tr>\n";
        $html .= "                          <td  align=\"left\">PROFESIONAL:</td>\n";
        $html .= "                          <td  align=\"left\"  colspan=\"3\" >" . $datos_profesional['tipo_id_tercero'] . " " . $datos_profesional['tercero_id'] . " &nbsp; " . $datos_profesional['nombre'] . " - " . $datos_profesional['descripcion'] . " \n";
        $html .= "                      </tr>\n";
        $html .= "			<tr>\n";
        $html .= "                          <td  align=\"left\">TIPO FORMULA:</td>\n";
        $html .= "                          <td  align=\"left\"  colspan=\"3\" >{$datos_formula['descripcion_tipo_formula']}</td> \n";
        $html .= "                      </tr>\n";
        $html .= "                  </table>\n";
        $html .= "              </td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
//        $html .= "</fieldset>\n";

        return $html;
    }

    function obtener_footer_formula() {

        IncludeClass('ConexionBD');
        IncludeClass('DispensacionSQL', '', 'app', 'Dispensacion');

        $sql = new DispensacionSQL();

        $datos_usuario_imprime = $sql->GetNombreUsuarioImprime();

        $style = "style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:9px\"";

        $html .= "<table width=\"100%\" class=\"label\" $style>\n";
        $html .= "  <tr class=\"label\"  valign=\"bottom\" >\n";
        $html .= "      <td align=\"LEFT\" height=\"50\">________________________________________</td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"label\" >\n";
        $html .= "      <td align=\"LEFT\">FIRMA PACIENTE</td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"label\" >\n";
        $html .= "      <td align=\"LEFT\"></td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<br>\n";
        $html .= "<table width=\"100%\" class=\"label\" $style>\n";
        $html .= "  <tr align='right'>\n";
        $html .= "  <td align=\"rigth\" $style></td>\n";
//        $html .= "      <td align=\"rigth\" $style> USUARIO  IMPRIME:" . $datos_usuario_imprime['0']['nombre'] . "&nbsp;- " . $datos_usuario_imprime['0']['descripcion'] . "&nbsp; </td>\n";
//        $html .= "      <td width='50%' align=\"left\" $style> - FECHA DE IMPRESION :" . date("Y-m-d (H:i:s a)") . "&nbsp; </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";

        return $html;
    }

     function imprimir_ultima_entrega_medicamentos() {

        IncludeClass('ConexionBD');
        IncludeClass('DispensacionSQL', '', 'app', 'Dispensacion');

        $sql = new DispensacionSQL();

        $medicamentos_entregados = $sql->Medicamentos_Dispensados_Esm_x_lote($this->datos['evolucion'],1);
        $pendientes_entregados = $sql->pendientes_dispensados_ent($this->datos['evolucion']);
     
        // Medicamentos Entregados  
        $datos_medicamentos = array();
        if (!empty($medicamentos_entregados)) {
            foreach ($medicamentos_entregados as $key => $value) {
                $datos_medicamentos[$value['fecha_entrega']][] = $value; 
            }
        }
         $cantidad_dispensaciones = count($datos_medicamentos); 
        // Medicamentos Pendientes Entregados
        if (!empty($pendientes_entregados)) {
            foreach ($pendientes_entregados as $key => $value) {
                $datos_medicamentos[$value['fecha_entrega']][] = $value;
            }
        }
          krsort($datos_medicamentos);
 
        
        $medicamentos = array_shift($datos_medicamentos);

        //Filtrar Mx Entregados y Pdtes Entregados
        if (!empty($medicamentos)) {

            foreach ($medicamentos as $key => $value) {
                if ($value['pendiente_dispensado'] == "1") {
                    $dispensacion_medicamentos_pendientes[] = $value;
                } else 
                    $dispensacion_medicamentos[] = $value;              
            }
        }
        
        if (!empty($dispensacion_medicamentos)) {

            $msj = "";
            if ($cantidad_dispensaciones > 1) {
                $msj = "- ENTREGA DE MEDICAMENTOS No. {$cantidad_dispensaciones} ";
            }

            $style = "style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:9px\"";

//            $html .= "<fieldset class=\"fieldset\">\n";
            $html .= "<br><br>\n";
            $html .= "<p class=\"normal_10AN\">MEDICAMENTOS DISPENSADOS {$msj}</p>\n";
            $html .= "<table width=\"100%\" class=\"label\" $style>\n";
            $html .= "  <table width=\"100%\" cellspacing=\"2\">\n";
            $html .= "      <tr>\n";
            $html .= "          <td align=\"center\">\n";
            $html .= "              <table width=\"100%\" class=\"label\" $style>\n";
            $html .= "                  <tr >\n";
            $html .= "                      <td  ><U>FECHA ENTREGA</U></td>\n";
            $html .= "                      <td  ><U>CODIGO</U></td>\n";
            $html .= "                      <td colspan=\"2\"  ><U>MEDICAMENTO</U></td>\n";
            $html .= "                      <td ><U>CANTIDAD</U></td>\n";
            $html .= "                      <td colspan=\"1\" ><U>FECHA VENC</U></td>\n";
            $html .= "                      <td  colspan=\"1\"  ><U>LOTE</U></td>\n";
            $html .= "                  </tr>\n";

            $total_formula_D = 0;
            foreach ($dispensacion_medicamentos as $item => $fila) {

                $html .= "              <tr >\n";
                $html .= "                  <td  colspan=\"1\" >" . $fila['fecha_entrega'] . "</td>\n";
                $html .= "                  <td  colspan=\"1\" >" . $fila['codigo_producto'] . "</td>\n";
                $html .= "                  <td colspan=\"2\"  >" . $fila['descripcion_prod'] . "</td>\n";
                $html .= "                  <td  >" . round($fila['numero_unidades']) . "</td>\n";
                $html .= "                  <td  colspan=\"1\">" . $fila['fecha_vencimiento'] . "</td>\n";
                $html .= "                  <td  colspan=\"1\"  >" . $fila['lote'] . "</td>\n";
                $nombreDigito=$fila['nombre'];

                $costo = $fila['total_costo'];
                $V_unitario = $fila['total_costo'] / $fila['numero_unidades'];
                $total_formula_D +=$costo;

                $html .= "              </tr>\n";
            }

            $html .= "              </table>\n";
            $html .= "          </td>\n";
            $html .= "      </tr>\n";
            $html .= "  </table>\n";
            //$html .= "</fieldset>\n";
            $html .= "<div align='right'>Digitado por: {$nombreDigito} </div>";
            
        }

        if (!empty($dispensacion_medicamentos_pendientes)) {

            $msj = "";
           // if ($cantidad_dispensaciones > 1) {
                $msj ="MEDICAMENTOS PENDIENTES-DISPENSADOS";
           // }

            $style = "style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:9px\"";

//            $html .= "<fieldset class=\"fieldset\">\n";
            $html .= "<p class=\"normal_10AN\">{$msj}</p>\n";
            $html .= "<table width=\"100%\" class=\"label\" $style>\n";
            $html .= "  <table width=\"100%\" cellspacing=\"2\">\n";
            $html .= "      <tr>\n";
            $html .= "          <td align=\"center\">\n";
            $html .= "              <table width=\"100%\" class=\"label\" $style>\n";
            $html .= "                  <tr >\n";
            $html .= "                      <td width=\"15%\" ><U>FECHA ENTREGA</U></td>\n";
            $html .= "                      <td width=\"15%\" ><U>CODIGO</U></td>\n";
            $html .= "                      <td width=\"25%\" ><U>MEDICAMENTO</U></td>\n";
            $html .= "                      <td width=\"15%\"><U>CANTIDAD</U></td>\n";
            $html .= "                      <td width=\"15%\" ><U>FECHA VENC</U></td>\n";
            $html .= "                      <td width=\"15%\"  ><U>LOTE</U></td>\n";
            $html .= "                  </tr>\n";

            $total_formula_D = 0;
            foreach ($dispensacion_medicamentos_pendientes as $item => $fila) {
                if(empty($fila['pendiente'])){
                $html .= "              <tr >\n";
                $html .= "                  <td >" . $fila['fecha_entrega'] . "</td>\n";
                $html .= "                  <td >" . $fila['codigo_producto'] . "</td>\n";
                if ($fila['grupo_id'] == '2') {
                    $html .= "              <td >" . $fila['molecula'] . "</td>\n";
                } else {
                    $html .= "              <td >" . $fila['descripcion_prod'] . "</td>\n";
                }
                $html .= "                  <td  >" . round($fila['numero_unidades']) . "</td>\n";
                $html .= "                  <td  >" . $fila['fecha_vencimiento'] . "</td>\n";
                $html .= "                  <td  >" . $fila['lote'] . "</td>\n";

                $costo = $fila['total_costo'];
                $V_unitario = $fila['total_costo'] / $fila['numero_unidades'];
                $total_formula_D +=$costo;

                $html .= "              </tr>\n";
                }
            }

            $html .= "              </table>\n";
            $html .= "          </td>\n";
            $html .= "      </tr>\n";
            $html .= "  </table>\n";
//            $html .= "</fieldset>\n";
        }

        return $html;
    }

    function imprimir_entregas_medicamentos() {

        IncludeClass('ConexionBD');
        IncludeClass('DispensacionSQL', '', 'app', 'Dispensacion');

        $sql = new DispensacionSQL();

        $medicamentos_entregados = $sql->Medicamentos_Dispensados_Esm_x_lote($this->datos['evolucion']);
        $pendientes_entregados = $sql->pendientes_dispensados_ent($this->datos['evolucion']);

          /*echo "<pre>";
          print_r($medicamentos_entregados);
          print_r($pendientes_entregados);
          echo "</pre>";
          exit();*/
        
        // Medicamentos Pendientes Entregados
        $datos_medicamentos_pendientes_entregados = array();
        $pendientes_entregados_entregas = array();
        if (!empty($pendientes_entregados)) {
            foreach ($pendientes_entregados as $key => $value) {
                $datos_medicamentos_pendientes_entregados[$value['fecha_entrega']][] = $value;
            }
        }

        // Medicamentos Entregados  
        $datos_medicamentos = array();
        if (!empty($medicamentos_entregados)) {
            foreach ($medicamentos_entregados as $key => $value) {
                $datos_medicamentos[$value['fecha_entrega']][] = $value;               
            }
        }
        if (!empty($medicamentos_entregados) && !empty($pendientes_entregados)) {
            foreach ($datos_medicamentos as $key => $value) {
                foreach ($pendientes_entregados as $keyp => $valuep) {
                    if ($key == $valuep['fecha_pendiente']) {
                        $datos_medicamentos_pendientes_entregados[$valuep['fecha_entrega']]['pendiente']=$valuep['fecha_pendiente'];
                        //echo $keyp."<pre> --   " . $valuep['fecha_pendiente'];print_r($valuep);
                    }
                }
            }
        }
//          echo "<pre>";
//          print_r($datos_medicamentos);
//          print_r($datos_medicamentos_pendientes_entregados);
//          echo "</pre>";
//          exit(); 
        $style = "style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:9px\"";
        $cantidad_dispensacion = 1;
        if (!empty($datos_medicamentos)) {

            $html .= "<center><h3>- Medicamento Entregados -</h3></center>";
            foreach ($datos_medicamentos as $fecha_dispensacion => $medicamentos_dispensados) {

                

              //  $html .= "<fieldset class=\"fieldset\">\n";
                $html .= "<p class=\"normal_10AN\">MEDICAMENTOS DISPENSADOS FECHA. {$fecha_dispensacion} -ENTREGA No. {$cantidad_dispensacion}</p>\n";
                $pendientes_entregados_entregas[$fecha_dispensacion]=$cantidad_dispensacion;
                $html .= "<table width=\"100%\" class=\"label\" $style>\n";
                $html .= "  <table width=\"100%\" cellspacing=\"2\">\n";
                $html .= "      <tr>\n";
                $html .= "          <td align=\"center\">\n";
                $html .= "              <table width=\"100%\" class=\"label\" $style>\n";
                $html .= "                  <tr >\n";
                $html .= "                      <td width=\"15%\" ><U>FECHA ENTREGA</U></td>\n";
                $html .= "                      <td width=\"15%\" ><U>CODIGO</U></td>\n";
                $html .= "                      <td width=\"25%\" ><U>MEDICAMENTO</U></td>\n";
                $html .= "                      <td width=\"15%\"><U>CANTIDAD</U></td>\n";
                $html .= "                      <td width=\"15%\" ><U>FECHA VENC</U></td>\n";
                $html .= "                      <td width=\"15%\"  ><U>LOTE</U></td>\n";
                $html .= "                  </tr>\n";

                $total_formula_D = 0;
                foreach ($medicamentos_dispensados as $item => $fila) {

                    $html .= "              <tr >\n";
                    $html .= "                  <td   >" . $fila['fecha_entrega'] . "</td>\n";
                    $html .= "                  <td   >" . $fila['codigo_producto'] . "</td>\n";
                    if ($fila['grupo_id'] == '2') {
                        $html .= "              <td   >" . $fila['molecula'] . "</td>\n";
                    } else {
                        $html .= "              <td   >" . $fila['descripcion_prod'] . "</td>\n";
                    }
                    $html .= "                  <td  >" . round($fila['numero_unidades']) . "</td>\n";
                    $html .= "                  <td  >" . $fila['fecha_vencimiento'] . "</td>\n";
                    $html .= "                  <td  >" . $fila['lote'] . "</td>\n";

                    $costo = $fila['total_costo'];
                    $V_unitario = $fila['total_costo'] / $fila['numero_unidades'];
                    $total_formula_D +=$costo;

                    $html .= "              </tr>\n";
                }

                $html .= "              </table>\n";
                $html .= "          </td>\n";
                $html .= "      </tr>\n";
                $html .= "  </table>\n";
              //  $html .= "</fieldset>\n";
                $cantidad_dispensacion++;
            }
        }

        if (!empty($datos_medicamentos_pendientes_entregados)) {

            $html .= "<center><h3>- Medicamento Pendientes Entregados -</h3></center>";
            foreach ($datos_medicamentos_pendientes_entregados as $fecha_dispensacion => $medicamentos_dispensados) {

              //  $style = "style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:9px\"";

               // $html .= "<fieldset class=\"fieldset\">\n";
                $html .= "<p class=\"normal_10AN\">MEDICAMENTOS DISPENSADOS FECHA. {$fecha_dispensacion} - PENDIENTE {$medicamentos_dispensados['pendiente']} ENTREGA No. {$pendientes_entregados_entregas[$medicamentos_dispensados['pendiente']]}</p>\n";
//                $html .= "<p class=\"normal_10AN\">MEDICAMENTOS DISPENSADOS POR PENDIENTE EN LA FECHA. {$medicamentos_dispensados['pendiente']}</p>\n";
                $html .= "<table width=\"100%\" class=\"label\" $style>\n";
                $html .= "  <table width=\"100%\" cellspacing=\"2\">\n";
                $html .= "      <tr>\n";
                $html .= "          <td align=\"center\">\n";
                $html .= "              <table width=\"100%\" class=\"label\" $style>\n";
                $html .= "                  <tr >\n";
                $html .= "                      <td width=\"15%\" ><U>FECHA ENTREGA</U></td>\n";
                $html .= "                      <td width=\"15%\" ><U>CODIGO</U></td>\n";
                $html .= "                      <td width=\"25%\" ><U>MEDICAMENTO</U></td>\n";
                $html .= "                      <td width=\"15%\"><U>CANTIDAD</U></td>\n";
                $html .= "                      <td width=\"15%\" ><U>FECHA VENC</U></td>\n";
                $html .= "                      <td width=\"15%\"  ><U>LOTE</U></td>\n";
                $html .= "                  </tr>\n";

                $total_formula_D = 0;
                
                    foreach ($medicamentos_dispensados as $item => $fila) {
                     if(empty($fila['pendiente'])){
                        $html .= "              <tr >\n";
                        $html .= "                  <td  >" . $fila['fecha_entrega'] . "</td>\n";
                        $html .= "                  <td  >" . $fila['codigo_producto'] . "</td>\n";
                        if ($fila['grupo_id'] == '2') {
                            $html .= "              <td >" . $fila['molecula'] . "</td>\n";
                        } else {
                            $html .= "              <td >" . $fila['descripcion_prod'] . "</td>\n";
                        }
                        $html .= "                  <td  >" . round($fila['numero_unidades']) . "</td>\n";
                        $html .= "                  <td  >" . $fila['fecha_vencimiento'] . "</td>\n";
                        $html .= "                  <td  >" . $fila['lote'] . "</td>\n";

                        $costo = $fila['total_costo'];
                        $V_unitario = $fila['total_costo'] / $fila['numero_unidades'];
                        $total_formula_D +=$costo;

                        $html .= "              </tr>\n";
                    }
                }
                $html .= "              </table>\n";
                $html .= "          </td>\n";
                $html .= "      </tr>\n";
                $html .= "  </table>\n";
              // $html .= "</fieldset>\n";
                $cantidad_dispensacion++;
            }
        }

        return $html;
    }

    function imprimir_pendientes() {

        IncludeClass('ConexionBD');
        IncludeClass('DispensacionSQL', '', 'app', 'Dispensacion');

        $sql = new DispensacionSQL();

        $medicamentos_pendientes = $sql->Medicamentos_Pendientes_($this->datos['evolucion']);


        if (!empty($medicamentos_pendientes)) {

            $html .= "<center><h3>- Medicamento Pendientes -</h3></center>";

            $style = "style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:9px\"";

         //   $html .= "<fieldset class=\"fieldset\">\n";
            $html .= "<p class=\"normal_10AN\">MEDICAMENTOS PENDIENTES</p>\n";
            $html .= "<table width=\"100%\" class=\"label\" $style>\n";
            $html .= "  <table width=\"100%\" cellspacing=\"2\">\n";
            $html .= "      <tr>\n";
            $html .= "          <td align=\"center\">\n";
            $html .= "              <table width=\"100%\" class=\"label\" $style>\n";
            $html .= "                  <tr >\n";
            $html .= "                      <td  ><U>CODIGO</U></td>\n";
            $html .= "                      <td colspan=\"2\"  ><U>MEDICAMENTO</U></td>\n";
            $html .= "                      <td ><U>CANTIDAD</U></td>\n";
            $html .= "                  </tr>\n";

            $total_formula_D = 0;
            foreach ($medicamentos_pendientes as $item => $fila) {

                $html .= "              <tr >\n";
                $html .= "                  <td  colspan=\"1\" >" . $fila['codigo_medicamento'] . "</td>\n";
                if ($fila['grupo_id'] == '2') {
                    $html .= "              <td colspan=\"2\"  >" . $fila['descripcion_prod'] . "</td>\n";
                } else {
                    $html .= "              <td colspan=\"2\"  >" . $fila['descripcion_prod'] . "</td>\n";
                }
                $html .= "                  <td  >" . round($fila['total']) . "</td>\n";

                $costo = $fila['total_costo'];
                $V_unitario = $fila['total_costo'] / $fila['numero_unidades'];
                $total_formula_D +=$costo;

                $html .= "              </tr>\n";
            }

            $html .= "              </table>\n";
            $html .= "          </td>\n";
            $html .= "      </tr>\n";
            $html .= "  </table>\n";
           // $html .= "</fieldset>\n";
        } else {
            $html = "<center><h3>No hay Medicamentos Pendientes</h3></center>";
        }

        return $html;
    }

}

?>