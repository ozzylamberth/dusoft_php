<?php

IncludeClass("ClaseHTML");

class Agregar_Actual_HTML extends app_DescargaVentaPublico_controller {

    function Agregar_Actual_HTML() {
        return true;
    }
    
    function listarDocumentos() {
        $html .= ThemeAbrirTabla('DESCARGA DE CSV VENTA AL PUBLICO');
        $html .= "<form name=\"forma\" action=\"#\" method=\"post\">";
        $html .= "<table width=\"50%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" >";
        $html .= "<tr>";
        $html .= "<td>Periodo</td>";
        $html .= "<td >Desde: </td>";
        $html .= "<td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaI\" id=\"FechaI\"  >" . ReturnOpenCalendario('forma', 'FechaI', '/') . "</td>";
        $html .= "<td >Hasta: </td>";
        $html .= "<td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaF\" id=\"FechaF\" >" . ReturnOpenCalendario('forma', 'FechaF', '/') . "</td>";
        $html .= "</tr>";
        $html .= "</table>";
        $html .= "</form>";
        $html .= "<td><input class=\"input-submit\"  onClick =\"javascript:listarDocumento();\" type=\"submit\" name=\"Buscar\" value=\"GENERAR\"></td>";
        $html .= ThemeCerrarTabla();
        $html .= "<div id='lista'></div>";

        return $html;
    }

    function VisualizarVentasPublico($respuesta) {
        $html = "";
        $html .= "<table border=\"-1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "<tr class=\"modulo_table_list_title\">";
        $html .= "<td>1. CODIGO DE DOCUMENTO</td>";
        $html .= "<td>2. NUMERO DE DOCUMENTO</td>";
        $html .= "<td>3. CUENTA CONTABLE REGISTRO</td>";
        $html .= "<td>4. IDENTIFICACION DE TERCERO</td>";
        $html .= "<td>5. CENTRO DE UTILIDAD</td>";
        $html .= "<td>6. CENTRO COSTOS</td>";
        $html .= "<td>7. LINEAS DE COSTO</td>";
        $html .= "<td>8. FECHA DOCUMENTO (dd/mm/yy)</td>";
        $html .= "<td>9. VLR DB</td>";
        $html .= "<td>10. VLR CR</td>";
        $html .= "<td>11. OBSERVACION</td>";
        $html .= "<td>12. ESTADO (en fi se maneja, 3=sin confirmar, 4=confirmado, 2 = anulado )</td>";
        $html .= "<td>13. CODIGO DE CONCEPTO</td>";
        $html .= "<td>14. BASE DE RETENCION</td>";
        $html .= "<td>15. TASA DE RETENCION (PORCENTAJE DE RETENCION)</td>";
        $html .= "</tr>";
        $csv ="1. CODIGO DE DOCUMENTO;2. NUMERO DE DOCUMENTO;3. CUENTA CONTABLE REGISTRO;4. IDENTIFICACION DE TERCERO;5. CENTRO DE UTILIDAD;6. CENTRO COSTOS;7. LINEAS DE COSTO;8. FECHA DOCUMENTO (dd/mm/yy);9. VLR DB;10. VLR CR;11. OBSERVACION;12. ESTADO (en fi se maneja, 3=sin confirmar, 4=confirmado, 2 = anulado );13. CODIGO DE CONCEPTO;14. BASE DE RETENCION;15. TASA DE RETENCION (PORCENTAJE DE RETENCION);\n";
        $color=true;   
        foreach ($respuesta as $clave => $item) {
            $valor = $this->validaCuentas($respuesta[$clave]);
            foreach ($valor as $clave => $item) {   
                if($color){
                    $stilo="modulo_list_claro";
                   $color=false; 
                }else{
                    $stilo="modulo_list_oscuro";
                  $color=true;  
                }
            $html .= "<tr align=\"center\" class=\"$stilo\">";
            $html .= "<td>" . $item['codigo_documento'] . "</td>";
            $html .= "<td>" . $item['numero_documento'] . "</td>";
            $html .= "<td>" . $item['cuenta_contable'] . "</td>";
            $html .= "<td>" . $item['itentificacion_tercero'] . "</td>";
            $html .= "<td>" . $item['centro_utilidad'] . "</td>";
            $html .= "<td>" . $item['centro_costos'] . "</td>";
            $html .= "<td>" . $item['linea_de_costos'] . "</td>";
            $html .= "<td>" . $item['fecha_documento'] . "</td>";
            $html .= "<td>" . $item['vlr_db'] . "</td>";
            $html .= "<td>" . $item['vlr_cr'] . "</td>";
            $html .= "<td>" . $item['observacion'] . "</td>";
            $html .= "<td>" . $item['estado'] . "</td>";
            $html .= "<td>" . $item['codigo_concepto'] . "</td>";
            $html .= "<td>" . $item['base_de_retencion'] . "</td>";
            $html .= "<td>" . $item['tasa_de_retencion'] . "</td>";
            $html .= "</tr>";
            $csv .=$item['codigo_documento'] . ";". $item['numero_documento'] . ";". $item['cuenta_contable'] . ";"
                    . $item['itentificacion_tercero'] . ";". $item['centro_utilidad'] . ";"
                    . $item['centro_costos'] . ";". $item['linea_de_costos'] . ";"
                    . $item['fecha_documento'] . ";". $item['vlr_db'] . ";"
                    . $item['vlr_cr'] . ";". $item['observacion'] . ";". $item['estado'] . ";"
                    . $item['codigo_concepto'] . ";". $item['base_de_retencion'] . ";"
                    . $item['tasa_de_retencion'] . ";\n";
            }
        }

        $abrir=$this->crearArchivo($csv);
        if($abrir){
          $html1 .="<br><div align=\"center\"><a target='_blank' href='data.csv'>Descargar</a></div><br>";
        }else{
          $html1 .="<div align=\"center\">No se puede abrir el archivo</div>";  
        }
        $html .= "</table>";
        $html2=$html1.''.$html;
        return $html2;
    }
   
    function crearArchivo($csv) {
        $nombre_archivo = "data.csv";
// Primero vamos a asegurarnos de que el archivo existe y es escribible.
        if (is_writable($nombre_archivo)) {

            // En nuestro ejemplo estamos abriendo $nombre_archivo en modo de adición.
            // El puntero al archivo está al final del archivo
            // donde irá $contenido cuando usemos fwrite() sobre él.
            if (!$gestor = fopen($nombre_archivo, 'w')) {
                echo "No se puede abrir el archivo ($nombre_archivo)";
                return false;
                exit;
            }

            // Escribir $contenido a nuestro archivo abierto.
            if (fwrite($gestor, $csv) === FALSE) {
                echo "No se puede escribir en el archivo ($nombre_archivo)";
                return false;
                exit;
            }
            return true;
            fclose($gestor);
        } else {
           return false;
        }
    }

   function Valores($item) {
        
        if($item['itentificacion_tercero']==""){
            $item['itentificacion_tercero']='999999999';
        }
        $asientoscontables = array(
            'codigo_documento' => $item['prefijo'],
            'numero_documento' => $item['factura_fiscal'],
            'cuenta_contable' => $item['cuenta_contable'], //
            'itentificacion_tercero' => $item['itentificacion_tercero'],
            'centro_utilidad' => $item['centro_utilidad'] != '' ? $item['centro_utilidad'] : '0',
            'centro_costos' => $item['centro_costos'] != '' ? $item['centro_costos'] : '0',
            'linea_de_costos' => $item['linea_de_costos'] != '' ? $item['linea_de_costos'] : '0',
            'fecha_documento' => $item['fecha_registro'],
            'vlr_db' => $item['vlr_db'] != '' ? $item['vlr_db'] : '0',
            'vlr_cr' => $item['vlr_cr'] != '' ? $item['vlr_cr'] : '0',
            'observacion' => $item['observacion'],
            'estado' => '4', //$item['estado']
            'codigo_concepto' => $item['codigo_concepto'] != '' ? $item['codigo_concepto'] : '',
            'base_de_retencion' => $item['base_de_retencion'] != '' ? $item['base_de_retencion'] : '',
            'tasa_de_retencion' => $item['tasa_de_retencion'] != '' ? $item['tasa_de_retencion'] : '',
        );
        return $asientoscontables;
    }

    function validaCuentas($items) {
        $asientoscontables = array();
        $cuentas = array();

          foreach ($items as $item) {
              $items[0]['vlr_db'] += round($item['valor_total']);
          }
//        $items[0]['vlr_db'] = round($items[0]['total_abono']);
        $items[0]['cuenta_contable'] = '11050510'; //caja farmacia Norte
        $asientoscontables[] = $this->Valores($items[0]);

        foreach ($items as $item) {
            $items[0]['itentificacion_tercero']='999999999';
            if ($item['porc_iva'] > 0) {
                if ($item['sw_medicamento'] == 1) {
                    //venta medicamentos gravados   
                    $cuentas['cuenta_contableMVI'] = '41353803';
                    $cuentas['medicamento_venta_iva'] += round($item['subtotal']);
                    //costo medicamentos gravados
                    $cuentas['cuenta_contableCMVI_cr'] = '14352005';
                    $cuentas['medicamento_costoIva'] += round($item['costo']);
                    $cuentas['cuenta_contableCMVI_db'] = '61353803';
                    if($item['porc_iva'] == 19){
                    $cuentas['valor_iva_19'] += round($item['valor_iva']);
                    $cuentas['porc_iva_19'] = $item['porc_iva'];
                    }
                    if($item['porc_iva'] == 5){
                    $cuentas['valor_iva_5'] += round($item['valor_iva']);
                    $cuentas['porc_iva_5'] = $item['porc_iva'];
                    }
                } else {
                    //venta insumos  gravados  
                    $cuentas['cuenta_contableIVI'] = '41353201';
                    $cuentas['insumo_venta_iva'] += round($item['subtotal']);
                    //costo insumo gravados
                    $cuentas['cuenta_contableCIVI_cr'] = '14350505';
                    $cuentas['insumo_ventaIva'] += round($item['costo']);
                    $cuentas['cuenta_contableCIVI_db'] = '61353201';
                    if($item['porc_iva'] == 19){
                    $cuentas['valor_iva_19'] += round($item['valor_iva']);
                    $cuentas['porc_iva_19'] = $item['porc_iva'];
                    }
                    if($item['porc_iva'] == 5){
                    $cuentas['valor_iva_5'] += round($item['valor_iva']);
                    $cuentas['porc_iva_5'] = $item['porc_iva'];
                    }
                }
            } else {
                if ($item['sw_medicamento'] == 1) {
                    //venta medicamentos no gravados   
                    $cuentas['cuenta_contableMVNI'] = '41353804';
                    $cuentas['medicamento_venta_no_iva'] += round($item['subtotal']);
                    //costo medicamentos no gravados
                    $cuentas['cuenta_contableCMVNI_cr'] = '14352010';
                    $cuentas['medicamento_costoNoIva'] += round($item['costo']);
                    $cuentas['cuenta_contableCMVNI_db'] = '61353804';

                    // $cuentas['medicamento_valor_iva'] += $item['valor_iva'];                  
                } else {
                    //venta insumos no gravados  
                    $cuentas['cuenta_contableIVNI'] = '41353202';
                    $cuentas['insumo_venta_no_iva'] += round($item['subtotal']);
                    //costo insumo no gravados                    
                    $cuentas['cuenta_contableCIVNI_cr'] = '14350510';
                    $cuentas['insumo_ventaNoIva'] += round($item['costo']);
                    $cuentas['cuenta_contableCIVNI_db'] = '61353202';
                    // $cuentas['insumo_venta_iva'] += $item['valor_iva']; 
                }
            }
        }

        $items[0]['vlr_db'] = '0';
        $items[0]['centro_utilidad'] = '03';
        $items[0]['centro_costos'] = '1012';
        $items[0]['linea_de_costos'] = '2';
        //insumos gravados
        if ($cuentas['insumo_venta_iva'] > 0) {
            $items[0]['cuenta_contable'] = $cuentas['cuenta_contableIVI'];
            $items[0]['vlr_cr'] = round($cuentas['insumo_venta_iva']);
            $asientoscontables[] = $this->Valores($items[0]);
        }
        //insumos no gravados
        if ($cuentas['insumo_venta_no_iva'] > 0) {
            $items[0]['cuenta_contable'] = $cuentas['cuenta_contableIVNI'];
            $items[0]['vlr_cr'] = round($cuentas['insumo_venta_no_iva']);
            $asientoscontables[] = $this->Valores($items[0]);
        }
        //medicamentos gravados
        if ($cuentas['medicamento_venta_iva'] > 0) {
            $items[0]['cuenta_contable'] = $cuentas['cuenta_contableMVI'];
            $items[0]['vlr_cr'] = round($cuentas['medicamento_venta_iva']);
            $asientoscontables[] = $this->Valores($items[0]);
        }
        //medicamentos no gravados
        if ($cuentas['medicamento_venta_no_iva'] > 0) {
            $items[0]['cuenta_contable'] = $cuentas['cuenta_contableMVNI'];
            $items[0]['vlr_cr'] = round($cuentas['medicamento_venta_no_iva']);
            $asientoscontables[] = $this->Valores($items[0]);
        }
         
        //----------------------------------------------------------------------------      

        $items[0]['centro_utilidad'] = '03';
        $items[0]['centro_costos'] = '1012';
        $items[0]['linea_de_costos'] = '1';
        $items[0]['vlr_cr'] = '0';
        // insumos costo gravados
        if ($cuentas['insumo_ventaIva'] > 0) {
            $items[0]['itentificacion_tercero']='830080649';
            $items[0]['cuenta_contable'] = $cuentas['cuenta_contableCIVI_db'];
            $items[0]['vlr_db'] = round($cuentas['insumo_ventaIva']);            
            $asientoscontables[] = $this->Valores($items[0]);
        }
        // insumos costo no gravados
        if ($cuentas['insumo_ventaNoIva'] > 0) {
            $items[0]['itentificacion_tercero']='830080649';
            $items[0]['cuenta_contable'] = $cuentas['cuenta_contableCIVNI_db'];
            $items[0]['vlr_db'] = round($cuentas['insumo_ventaNoIva']);
            $asientoscontables[] = $this->Valores($items[0]);
        }
        // medicamentos costo gravados
        if ($cuentas['medicamento_costoIva'] > 0) {
            $items[0]['itentificacion_tercero']='830080649';
            $items[0]['cuenta_contable'] = $cuentas['cuenta_contableCMVI_db'];
            $items[0]['vlr_db'] = round($cuentas['medicamento_costoIva']);
            $asientoscontables[] = $this->Valores($items[0]);
        }
        // medicamentos costo no gravados
        if ($cuentas['medicamento_costoNoIva'] > 0) {
            $items[0]['itentificacion_tercero']='830080649';
            $items[0]['cuenta_contable'] = $cuentas['cuenta_contableCMVNI_db'];
            $items[0]['vlr_db'] = round($cuentas['medicamento_costoNoIva']);
            $asientoscontables[] = $this->Valores($items[0]);
        }
        //--------------------------------------------------------------------------    

        $items[0]['centro_utilidad'] = '0';
        $items[0]['centro_costos'] = '0';
        $items[0]['linea_de_costos'] = '0';
        $items[0]['vlr_db'] = '0';
        // insumos costo gravados
        if ($cuentas['insumo_ventaIva'] > 0) {
            $items[0]['itentificacion_tercero']='830080649';
            $items[0]['cuenta_contable'] = $cuentas['cuenta_contableCIVI_cr'];
            $items[0]['vlr_cr'] = round($cuentas['insumo_ventaIva']);
            $asientoscontables[] = $this->Valores($items[0]);
        }
        // insumos costo no gravados
        if ($cuentas['insumo_ventaNoIva'] > 0) {
            $items[0]['itentificacion_tercero']='830080649';
            $items[0]['cuenta_contable'] = $cuentas['cuenta_contableCIVNI_cr'];
            $items[0]['vlr_cr'] = round($cuentas['insumo_ventaNoIva']);
            $asientoscontables[] = $this->Valores($items[0]);
        }

        // medicamentos costo gravados
        if ($cuentas['medicamento_costoIva'] > 0) {
            $items[0]['itentificacion_tercero']='830080649';
            $items[0]['cuenta_contable'] = $cuentas['cuenta_contableCMVI_cr'];
            $items[0]['vlr_cr'] = round($cuentas['medicamento_costoIva']);
            $asientoscontables[] = $this->Valores($items[0]);
        }
        // medicamentos costo no gravados
        if ($cuentas['medicamento_costoNoIva'] > 0) {
            $items[0]['itentificacion_tercero']='830080649';
            $items[0]['cuenta_contable'] = $cuentas['cuenta_contableCMVNI_cr'];
            $items[0]['vlr_cr'] = round($cuentas['medicamento_costoNoIva']);
            $asientoscontables[] = $this->Valores($items[0]);
        }
        //iva 
        if ($cuentas['porc_iva_19'] == 19 ) {
            $items[0]['itentificacion_tercero']='';
            $items[0]['cuenta_contable'] = '24080604';
            $items[0]['vlr_cr'] = round($cuentas['valor_iva_19']);
            $items[0]['codigo_concepto'] = 'RI64';
            $items[0]['base_de_retencion'] = round(($cuentas['valor_iva_19']*100)/$cuentas['porc_iva_19']);
            $items[0]['tasa_de_retencion'] = round($cuentas['porc_iva_19']);
            $asientoscontables[] = $this->Valores($items[0]);
        }
        if ($cuentas['porc_iva_5'] == 5 ) {
            $items[0]['itentificacion_tercero']='';
            $items[0]['cuenta_contable'] = '24080603';
            $items[0]['vlr_cr'] = round($cuentas['valor_iva_5']);
            $items[0]['codigo_concepto'] = '';
            $items[0]['base_de_retencion'] = round(($cuentas['valor_iva_5']*100)/$cuentas['porc_iva_5']);
            $items[0]['tasa_de_retencion'] = round($cuentas['porc_iva_5']);
            $asientoscontables[] = $this->Valores($items[0]);
        }
        return $asientoscontables;
    }

}
?>