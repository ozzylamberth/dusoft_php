<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: DispensacionHTML.class.php,v 1.0
 * @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja Torres
 */
IncludeClass("ClaseHTML");
IncludeClass("ClaseUtil");

class DispensacionHTML {

    /**
     * Constructor de la clase
     */
    function DispensacionHTML() {
        
    }

    /** Function para el Menu de dispensacion
     * @param array $action Vector de links de la aplicacion
     * @return String
     */
    function FormaMenu($action, $Planes) {
        $html = ThemeAbrirTabla('DISPENSACION');
        $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
        $html .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "        <tr>\n";
        $html .= "          <td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL</td>\n";
        $html .= "        </tr>\n";

        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td  class=\"label\"  align=\"center\">\n";
        $html .= "       <a href=\"" . $action['Formulas'] . "\">\n";
        $html .= "       BUSCAR FORMULA</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td  class=\"label\"  align=\"center\">\n";
        $html .= "<a href=\"" . $action['Pendientes'] . "\">BUSCAR PENDIENTES</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "      </table>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\"><br>\n";
        $html .= "      <form name=\"form\" action=\"" . $action['volver'] . "\" method=\"post\">";
        $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
        $html .= "      </form>";
        $html .= "    </td>";
        $html .= "  </tr>";
        $html .= "</table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /**
     * Funcion donde se crea la forma que permite realizar la busqueda de las formulas del paciente
     * @param array $action Vector de links de la aplicaion
     * @param array $Tipo  Vector de tipos de identificacion
     * @param array $request vector que contiene la informacion del request
     * @param array $datos vector que contiene la informacion del paciente
     * @param string $pagina cadena con el numero de la pagina que se esta visualizando
     * @param string $conteo cadena con la cantidad de los datos que se muestran
     *
     * @return String
     */
    function FormaBuscarFomula($action, $Tipo, $request, $datos, $empresa, $conteo, $pagina, $Planes) {

        $sql = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
        $reporte = new GetReports();

        $today = date("Y-m-d");
        $today = strtotime(date("Y-m-d", strtotime($today)) . " +0 day");
        $today = date("Y-m-d", $today);

      $ctl = AutoCarga::factory("ClaseUtil");
      $html = $ctl->LimpiarCampos();
      $html .= ThemeAbrirTabla('BUSCAR FORMULA - PACIENTE');
      $html .= "<center>\n";
      $html .= "<fieldset class=\"fieldset\" style=\"width:40%\">\n";
      $html .= "  <legend class=\"normal_10AN\" align=\"center\">PACIENTE</legend>\n";
      $html .= "  <form name=\"formabuscarE\" action=\"".$action['buscador']."\" method=\"post\">";
      $html .= "    <table   width=\"100%\" align=\"center\" class=\"modulo_table_list\"  >";
      $html .= "      <tr class=\"formulacion_table_list\"> \n";
      $html .= "        <td align=\"left\" >TIPO DOCUMENTO:</td>\n";
      $html .= "        <td align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
      $html .= "          <select name=\"buscador[tipo_id_paciente]\" class=\"select\">\n";
      $html .= "            <option value = '-1'>--  SELECCIONE --</option>\n";
      $csk = "";
      foreach($Tipo as $indice => $valor)
      {
        $sel = ($valor['tipo_id_tercero']==$request['tipo_id_paciente'])? "selected":"";
        $html .= "  <option value=\"".$valor['tipo_id_tercero']."\" ".$sel.">".$valor['descripcion']."</option>\n";
      }
      $html .= "          </select>\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "        <td align=\"left\" >DOCUMENTO:</td>\n";
      $html .= "        <td align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
      $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[paciente_id]\" size=\"20\"  maxlength=\"32\" value=".$request['paciente_id'].">\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "        <td align=\"left\" >NOMBRES:</td>\n";
      $html .= "        <td class=\"modulo_list_claro\" align=\"left\" >\n";
      $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[nombres]\" style=\"width:100%\" value=".$request['nombres'].">\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "        <td align=\"left\" >APELLIDOS:</td>\n";
      $html .= "        <td class=\"modulo_list_claro\" align=\"left\"  >\n";
      $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[apellidos]\" style=\"width:100%\" value=".$request['apellidos'].">\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
    $html .= "      <tr class=\"formulacion_table_list\">\n";
    $html .= "        <td align=\"left\" >No. FORMULA:</td>\n";
    $html .= "        <td class=\"modulo_list_claro\" align=\"left\"  >\n";
    $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[formula]\" size=\"20\"  maxlength=\"32\" value=".$request['formula'].">\n";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"formulacion_table_list\">\n";
    $html .= "        <td align=\"left\" >EVOLUCI&Oacute;N:</td>\n";
    $html .= "        <td class=\"modulo_list_claro\" align=\"left\" >\n";
    $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[evolucion]\" size=\"20\"  maxlength=\"32\" value=".$request['evolucion'].">\n";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
      $html .= "    </table><br>\n";
      $html .= "    <table   width=\"40%\"  class=\"normal_10AN\" align=\"center\" border=\"0\"  >";
      $html .= "      <tr>\n";
      $html .= "        <td align='center'>\n";
      $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
      $html .= "        </td>\n";
      $html .= "        <td align='center' colspan=\"1\">\n";
      $html .= "          <input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.formabuscarE)\" value=\"Limpiar Campos\">\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "    </table><br>\n";
      $html .= "  </form>\n";
      $html .= "</fieldset><br>\n";
      $html .= "</center>\n";
      $html .= $ctl->RollOverFilas();
        if (!empty($datos)) {
            $pghtml = AutoCarga::factory('ClaseHTML');
            $html .= "  <table width=\"100%\" class=\"modulo_table_list\"   align=\"center\">";
            $html .= "    <tr class=\"formulacion_table_list\" >\n";
            $html .= "      <td width=\"5%\">No. EVOLUCION</td>\n";
            $html .= "      <td width=\"5%\">No. FORMULA</td>\n";
            $html .= "      <td width=\"5%\">IDENTIFICACION </td>\n";
            $html .= "      <td width=\"35%\">PACIENTE </td>\n";
            $html .= "      <td width=\"5%\">E</td>\n";
            $html .= "      <td width=\"5%\">FECHA FORMULA</td>\n";
            $html .= "      <td width=\"5%\">FECHA FINALIZA</td>\n";
            $html .= "      <td width=\"15%\">PLAN</td>\n";
            $html .= "      <td width=\"15%\">MEDICO</td>\n";
            $html .= "      <td width=\"15%\">TIPO</td>\n";
            $html .= "      <td width=\"5%\">REIMPRIMIR</td>\n";
            $html .= "      <td colspan=\"4\">OP</td>";
            $html .= "  </tr>\n";
            $est = "modulo_list_claro";
            $back = "#DDDDDD";
            $i = 0;
            $mdl = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
            $dias_vigencia_formula = ModuloGetVar('', '', 'dispensacion_dias_vigencia_formula');

            $coun = 0;
            foreach ($datos as $key => $dtl) {
                $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";
                $html .= "  <tr class=\"" . $est . "\"  onmouseout=mOut(this,'$back'); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
                $html .= "      <td ><b>" . $dtl['evolucion_id'] . "</b></td>\n";
                $html .= "      <td ><b>" . $dtl['numero_formula'] . "</b></td>\n";
                $html .= "      <td ><b>" . $dtl['tipo_id_paciente'] . " " . $dtl['paciente_id'] . "</b></td>\n";
                $html .= "      <td ><b>" . $dtl['apellidos'] . " " . $dtl['nombres'] . "</b></td>\n";
                if ($dtl['tipo_bloqueo_id'] == 1) {
                    $html .= "      <td  width=\"5%\" align=\"center\" class=\"label_error\">\n";
                    $html .= "          <img border=\"0\"  title=\"" . $dtl['bloqueo'] . "\" src=\"" . GetThemePath() . "/images/si.png\">\n";
                    $html .= "      </td>\n";
                } else {
                    $html .= "      <td  width=\"5%\" align=\"center\" class=\"label_error\">\n";
                    $html .= "          <img border=\"0\" title=\"" . $dtl['bloqueo'] . "\"  src=\"" . GetThemePath() . "/images/pass.png\">\n";
                    $html .= "      </td>\n";
                }
                $html .= "      <td ><b>" . $dtl['fecha_formulacion'] . "</b></td>\n";
                $html .= "      <td ><b>" . $dtl['fecha_finalizacion'] . "</b></td>\n";
                $html .= "      <td ><b>" . $dtl['plan_descripcion'] . "</b></td>\n";

                $html .= "      <td ><b>" . $dtl['nombre'] . "</b></td>\n";
                $html .= "      <td ><b>" . $dtl['descripcion_tipo_formula'] . "</b></td>\n";

                $today = date("Y-m-d"); 

                $medicamentos_dispensados_ent = $sql->pendientes_dispensados_ent_TOTAL($dtl['evolucion_id']);
                $medicamentos_dispensados = $sql->Medicamentos_Dispensados_Esm_x_lote_total($dtl['evolucion_id']);
                if (count($medicamentos_dispensados_ent)+ count($medicamentos_dispensados) == 0) {
                    //$html .= "      <td> </td>\n";
                    $html .= "      <td> </td>\n";
                } else {

                    $imprimir_dispensacion_actual = $reporte->GetJavaReport('app', 'Dispensacion', 'MedicamentoDispensado', array("evolucion" => $dtl['evolucion_id'],"tipo_id_paciente" => $dtl['tipo_id_paciente'],"paciente_id" => $dtl['paciente_id'], "tipo_reporte" => 0), array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                    $funcion1 = $reporte->GetJavaFunction();
                    $html .= " " . $imprimir_dispensacion_actual . "\n";

                    $imprimir_dispensacion_completa = $reporte->GetJavaReport('app', 'Dispensacion', 'MedicamentoDispensado', array("evolucion" => $dtl['evolucion_id'],"tipo_id_paciente" => $dtl['tipo_id_paciente'],"paciente_id" => $dtl['paciente_id'], "tipo_reporte" => 1), array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                    $funcion2 = $reporte->GetJavaFunction();
                    $html .= " " . $imprimir_dispensacion_completa . "\n";

                    $imprimir_pendientes = $reporte->GetJavaReport('app', 'Dispensacion', 'MedicamentoDispensado', array("evolucion" => $dtl['evolucion_id'],"tipo_id_paciente" => $dtl['tipo_id_paciente'],"paciente_id" => $dtl['paciente_id'], "tipo_reporte" => 2), array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                    $funcion3 = $reporte->GetJavaFunction();
                    $html .= " " . $imprimir_pendientes . "\n";

                    $html .= "      <td align=\"center\" width=\"33%\"><a href=\"javascript:$funcion1\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'>Entrega</a>";
                    $html .= "      <a href=\"javascript:$funcion2\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'>Todos..</a>";
                    $html .= "      <a href=\"javascript:$funcion3\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'>Pdtes..</a></td>";
                }


                if ($dtl['sw_entrega_med'] == "1") {
                    list($a, $m, $d) = split("-", $dtl['fecha_formulacion']);

                    $fecha_condias = date("Y-m-d", (mktime(0, 0, 0, $m, ($d + $dias_vigencia_formula), $a)));
    ////////////////////////////////////         
    $medicamentos = $mdl->Medicamentos_Dispensados_Esm_x_lote_total($dtl['evolucion_id']);
    $datos_medicamentos = array();
    foreach ($medicamentos as $key => $value) {
        $datos_medicamentos[$value['fecha_entrega']][] = $value;
    }
    list($a, $m, $d) = split("-", $dtl['hoy']);
    $dtl['hoy'] = date("Y-m-d", (mktime(0, 0, 0, $m, ($d + $dias_vigencia_formula), $a)));
    
    $cantidad_dispensacion = count($datos_medicamentos); 
    $calculo_fechas = AutoCarga::factory('CalculoFechas');
    $cantidad_meses = $calculo_fechas->obtener_cantidad_meses($dtl['registro'], $dtl['fecha_finalizacion']);
    $cantidad_mesesHoy = $calculo_fechas->obtener_cantidad_meses($dtl['registro'], $dtl['hoy']);
    $cantidad_meses = ($cantidad_meses == 0) ? 1 : $cantidad_meses;
    ////////////////////////////////////////////////////  
					echo "FECHA CON DIAS " . $fecha_condias ." ";
					echo "today " . $today ." ";
                    if ($fecha_condias >= $today || !empty($medicamentos_dispensados)) {
                        $html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
                        $html .= "        <a href=\"" . $action['consul'] . URLRequest($dtl) . "\">\n";
                        $html .= "          <img border=\"0\" title=\"FORMULA ACTIVA\"  src=\"" . GetThemePath() . "/images/editar.png\">\n";
                        $html .= "        </a>\n";
                        $html .= "      </td>\n";
                    } else {

                        $coun++;
                        //$actualizacion_v=$mdl->UpdateEstad_Form_venci($dtl['evolucion_id']);
                    if (empty($medicamentos_dispensados)&& $cantidad_mesesHoy>$cantidad_dispensacion) {
                         $html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
                         $html .= "          <img border=\"0\"  title=\"LA FORMULA NO SE RECLAMO EN LA ANTERIOR ENTREGA\" src=\"" . GetThemePath() . "/images/no.png\">\n";
                         $html .= "      </td>\n";  
                       }elseif ($fecha_condias < $today) {
                        $html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
                        $html .= "          <img border=\"0\"  title=\"FORMULA VENCIDA\" src=\"" . GetThemePath() . "/images/alarma.gif\">\n";
                        $html .= "      </td>\n";
                       }
                    }
                    if ($coun > 0) {
                        /* $html .= "<embed src=\"".GetBaseURL()."/1.mid\" hidden=\"true\" type=\"midi\" loop=\"true\"></embed > "; */
                    }



                    $informacion = $mdl->Medicamentos_Pendientes($dtl['evolucion_id']);
                    if (!empty($informacion)) {
                        $html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
                        $html .= "        <a href=\"" . $action['pendiente'] . URLRequest($dtl) . "\">\n";
                        $html .= "          <img border=\"0\"  title=\"Pendientes\" src=\"" . GetThemePath() . "/images/pparamedin.png\">\n";
                        $html .= "        </a>\n";
                        $html .= "      </td>\n";
                    } else {
                        $html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
                        $html .= "          <img border=\"0\"  title=\"Pendientes\" src=\"" . GetThemePath() . "/images/pparamed.png\">\n";
                        $html .= "      </td>\n";
                    }
                } else {
                    $html .= "      <td colspan='4' class=\"label_error\" >No puede entregar medicamentos</td>\n";
                }



                $html .= "    </tr>\n";
            }
            $html .= "  </table><br>\n";
            $html .= $pghtml->ObtenerPaginado($conteo, $pagina, $action['paginador']);
        } else {
            if ($request)
                $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
        }

        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }
 /////////////////////////////////////
    /**
     * Funcion donde se crea la forma que permite realizar la busqueda de las formulas del paciente
     * @param array $action Vector de links de la aplicaion
     * @param array $Tipo  Vector de tipos de identificacion
     * @param array $request vector que contiene la informacion del request
     * @param array $datos vector que contiene la informacion del paciente
     * @param string $pagina cadena con el numero de la pagina que se esta visualizando
     * @param string $conteo cadena con la cantidad de los datos que se muestran
     *
     * @return String
     */
    function FormaBuscarPendientes($action, $Tipo, $request, $datos, $empresa, $conteo, $pagina, $Planes) {

        $sql = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
        $reporte = new GetReports();

        $today = date("Y-m-d");
        $today = strtotime(date("Y-m-d", strtotime($today)) . " +0 day");
        $today = date("Y-m-d", $today);

      $ctl = AutoCarga::factory("ClaseUtil");
      $html = $ctl->LimpiarCampos();
      $html .= "<script>";
      $html .= "function descartar(disp_id,medicamento,codigo,r){
                 var justi = document.getElementById('justificacion'+disp_id).value;
                    if(justi==-1){
                    alert('DEBE SELECCIONAR UNA JUSTIFICACION PARA EL MEDICAMENTO '+medicamento);
                    return true;
                    }
                  xajax_descartar_despacho(disp_id,medicamento,codigo,justi);
                  var i = r.parentNode.parentNode.rowIndex;
                  document.getElementById('tablaPendientes').deleteRow(i);
                }
                ";
      $html .= "</script>";
      $html .= ThemeAbrirTabla('BUSCAR PENDIENTES');
      $html .= "<center>\n";
      $html .= "<fieldset class=\"fieldset\" style=\"width:40%\">\n";
      $html .= "  <legend class=\"normal_10AN\" align=\"center\">PACIENTE</legend>\n";
      $html .= "  <form name=\"formabuscarE\" action=\"".$action['buscador']."\" method=\"post\">";
      $html .= "    <table   width=\"100%\" align=\"center\" class=\"modulo_table_list\"  >";
      $html .= "      <tr class=\"formulacion_table_list\"> \n";
      $html .= "        <td align=\"left\" >TIPO DOCUMENTO:</td>\n";
      $html .= "        <td align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
      $html .= "          <select name=\"buscador[tipo_id_paciente]\" class=\"select\">\n";
      $html .= "            <option value = '-1'>--  SELECCIONE --</option>\n";
      $csk = "";
      foreach($Tipo as $indice => $valor)
      {
        $sel = ($valor['tipo_id_tercero']==$request['tipo_id_paciente'])? "selected":"";
        $html .= "  <option value=\"".$valor['tipo_id_tercero']."\" ".$sel.">".$valor['descripcion']."</option>\n";
      }
      $html .= "          </select>\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "        <td align=\"left\" >DOCUMENTO:</td>\n";
      $html .= "        <td align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
      $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[paciente_id]\" size=\"20\"  maxlength=\"32\" value=".$request['paciente_id'].">\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
//      $html .= "      <tr class=\"formulacion_table_list\">\n";
//      $html .= "        <td align=\"left\" >NOMBRES:</td>\n";
//      $html .= "        <td class=\"modulo_list_claro\" align=\"left\" >\n";
//      $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[nombres]\" style=\"width:100%\" value=".$request['nombres'].">\n";
//      $html .= "        </td>\n";
//      $html .= "      </tr>\n";
//      $html .= "      <tr class=\"formulacion_table_list\">\n";
//      $html .= "        <td align=\"left\" >APELLIDOS:</td>\n";
//      $html .= "        <td class=\"modulo_list_claro\" align=\"left\"  >\n";
//      $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[apellidos]\" style=\"width:100%\" value=".$request['apellidos'].">\n";
//      $html .= "        </td>\n";
//      $html .= "      </tr>\n";
    $html .= "      <tr class=\"formulacion_table_list\">\n";
    $html .= "        <td align=\"left\" >No. FORMULA:</td>\n";
    $html .= "        <td class=\"modulo_list_claro\" align=\"left\"  >\n";
    $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[formula]\" size=\"20\"  maxlength=\"32\" value=".$request['formula'].">\n";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"formulacion_table_list\">\n";
    $html .= "        <td align=\"left\" >EVOLUCI&Oacute;N:</td>\n";
    $html .= "        <td class=\"modulo_list_claro\" align=\"left\" >\n";
    $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[evolucion]\" size=\"20\"  maxlength=\"32\" value=".$request['evolucion'].">\n";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
      $html .= "    </table><br>\n";
      $html .= "    <table   width=\"40%\"  class=\"normal_10AN\" align=\"center\" border=\"0\"  >";
      $html .= "      <tr>\n";
      $html .= "        <td align='center'>\n";
      $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
      $html .= "        </td>\n";
      $html .= "        <td align='center' colspan=\"1\">\n";
      $html .= "          <input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.formabuscarE)\" value=\"Limpiar Campos\">\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "    </table><br>\n";
      $html .= "  </form>\n";
      $html .= "</fieldset><br>\n";
      $html .= "</center>\n";
      $html .= $ctl->RollOverFilas();
//      echo "<pre>uuuuuu";print_r($datos);
        if (!empty($datos)) {
             
            $pghtml = AutoCarga::factory('ClaseHTML');
            $html .= "  <table width=\"100%\" class=\"modulo_table_list\"  id='tablaPendientes'  align=\"center\">";
            $html .= "    <tr class=\"formulacion_table_list\" >\n";
            $html .= "      <td width=\"5%\">No. EVOLUCION</td>\n";
            $html .= "      <td width=\"5%\">No. FORMULA</td>\n";
            $html .= "      <td width=\"5%\">IDENTIFICACION </td>\n";
            $html .= "      <td width=\"20%\">PACIENTE </td>\n";
            $html .= "      <td width=\"5%\">EDAD </td>\n";
            $html .= "      <td width=\"5%\">SEXO </td>\n";
            $html .= "      <td width=\"5%\">CODIGO</td>\n";
            $html .= "      <td width=\"35%\">NOMBRE MEDICAMENTO</td>\n";
            $html .= "      <td width=\"35%\">JUSTIFICACION</td>\n";
            $html .= "      <td width=\"5%\">CANTIDAD</td>\n";
            $html .= "      <td width=\"10%\">DESCARTAR</td>\n";
            $html .= "  </tr>\n";
            $est = "modulo_list_claro";
            $back = "#DDDDDD";
            $i = 0;
            $mdl = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
            $dias_vigencia_formula = ModuloGetVar('', '', 'dispensacion_dias_vigencia_formula');

            $coun = 0;
            foreach ($datos as $key => $dtl) { 
                list($anio, $mes, $dias) = explode(":", $dtl['edad']);
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
                $disp_id=$dtl['hc_pendiente_dispensacion_id'];
                $cod_med_id=$dtl['codigo_medicamento'];
                $nombre_med=$dtl['descripcion_prod'];
                $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";
                $html .= "  <tr class=\"" . $est . "\"  onmouseout=mOut(this,'$back');  onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
                $html .= "      <td ><b>" . $dtl['evolucion_id'] . "</b></td>\n";
                $html .= "      <td ><b>" . $dtl['numero_formula'] . "</b></td>\n";
                $html .= "      <td ><b>" . $dtl['tipo_id_paciente'] . " " . $dtl['paciente_id'] . "</b></td>\n";
                $html .= "      <td ><b>" . $dtl['nombrepacientes'] . "</b></td>\n";
                $html .= "      <td ><b>" . $edad . " &nbsp; $edad_t </b></td>\n";
                $html .= "      <td ><b>" . $dtl['sexo'] . "</b></td>\n";
                $html .= "      <td ><b>" . $dtl['codigo_medicamento'] . "</b></td>\n";
                $html .= "      <td ><b>" . $dtl['descripcion_prod'] . "</b></td>\n";
                $html .= "      <td align='center' >
                                    <SELECT   id='justificacion$disp_id' class=\"select\" >
                                     <option value='-1' align='center' >--seleccionar--</option>
                                     <option value='0' >Error de Formulaci&oacute;n</option>
                                     <option value='1' >Error de Digitaci&oacute;n</option>
                                     <option value='2' >Confrontado</option>
                                    </SELECT >
                                </td>\n";
                $html .= "      <td align='right'><b>" . $dtl['cantidad'] . "</b></td>\n";
                $html .= "      <td align='center'>\n";                
                $html .= "          <input class=\"input-submit\" type=\"button\" onclick=\"descartar('$disp_id','$cod_med_id','$nombre_med',this)\" value=\"DESCARTAR\">\n";
                $html .= "      </td>\n";                
                $html .= "    </tr>\n";
                $coun++;
            }
            $html .= "  </table><br>\n";
            $html .= $pghtml->ObtenerPaginado($conteo, $pagina, $action['paginador']);
        } else {
            if ($request)
                $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
        }

        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }
    /* Funcion que me permite visualizar la formula del paciente */

    function FormaFomulaPaciente($action, $paciente, $medi_form, $request, $datos, $paciente, $Cabecera_Formulacion, $Cabecera_Formulacion_AESM, $request, $Cabecera_Formulacion_AEM, $Datos_Fueza, $Datos_Ad, $ESM_pac, $opcion, $dix_r, $formula_id, $datos_ex, $dias_dipensados, $existe_f, $dusuario_id) {
        $ctl = AutoCarga::factory("ClaseUtil");
        $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
        $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
        $html = $ctl->RollOverFilas();

        $html .= " <script> \n";
        $html .= " function recogerTeclaBus(evt) ";
        $html .= " {";

        $html .= "var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   ";
        $html .= "var keyChar = String.fromCharCode(keyCode);";

        $html .= "if(keyCode==13)";
        $html .= "{   ";

        $html .= "   xajax_BuscarProducto1(xajax.getFormValues('buscador'),'" . $paciente['0']['evolucion_id'] . "',''); ";
        $html .= "}";
        $html .= " }   ";
        $html .= "  function ValidarCantidad(campo,valor,cant_sol,capa)\n";
        $html .= "  {\n";

        $html .= "    document.getElementById(campo).style.background='';\n";
        $html .= "    document.getElementById('error').innerHTML='';\n";
        $html .= "    if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='')\n";
        $html .= "    {\n";
        $html .= "      document.getElementById(campo).value='';\n";
        $html .= "      document.getElementById(campo).style.background='#ff9595';\n";
        $html .= "      document.getElementById('error').innerHTML='<center>CANTIDAD NO VALIDA</center>';\n";
        $html .= "      document.getElementById(capa).style.display=\"none\"\n";
        $html .= "    }\n";
        $html .= "    else{\n";
        $html .= "      document.getElementById(capa).style.display=\"\"\n";
        $html .= "    }\n";
        $html .= "  }\n";

        $html .= "  function Recargar_informacion(bodega_otra)\n";
        $html .= "  {\n";
        $html .= " document.buscador.bodega.value=bodega_otra; ";
        $html .= "  xajax_BuscarProducto1(xajax.getFormValues('buscador'),'" . $formula_id . "','');  ";
        $html .= " }";
        
        $html.="function mOut(src,clrIn) {\n";
        $html.="src.style.background = clrIn;\n";
        $html.="}\n";


        $html .=" </script>\n";
        $html .= ThemeAbrirTabla('FORMULA MEDICA  COMPLETA ');
        $html .= "<table border=\"0\" width=\"98%\" align=\"center\" >\n";
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
        $html .= "      <fieldset class=\"fieldset\">\n";
        $html .= "        <legend class=\"normal_10AN\">INFORMACION DE LA FORMULA</legend>\n";
        $html .= "        <div id='fecha_actual'></div>\n";
        $html .= "        <table width=\"100%\" cellspacing=\"2\">\n";
        $html .= "          <tr>\n";
        $html .= "            <td align=\"center\">\n";
        $html .= "              <table width=\"98%\" class=\"label\" $style>\n";
        $html .= "                <tr >\n";
        $html .= "                  <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE REGISTRO</td>\n";
        $html .= "                  <td width=\"10%\" align=\"right\">" . $paciente['0']['fecha_registro'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                  <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE FORMULA</td>\n";
        $html .= "                  <td width=\"10%\" align=\"right\">" . $paciente['0']['fecha_formulacion'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                  <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >EVOLUCION No</td>\n";
        $html .= "                  <td width=\"10%\" align=\"right\">" . $paciente['0']['evolucion_id'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                </tr>\n";
        $html .= "              </table>\n";
        $html .= "          <tr>\n";
        $html .= "            <td align=\"center\">\n";
        $html .= "              <table  width=\"98%\" class=\"label\" $style>\n";
        $html .= "                <tr>\n";
        $html .= "                  <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
        $html .= "                  <td colspan=\"3\">\n";
        $html .= "                    " . $paciente['0']['tipo_id_paciente'] . " " . $paciente['0']['paciente_id'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                  <td  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">NOMBRE COMPLETO</td>\n";
        $html .= "                  <td  > " . $paciente['0']['nombres'] . " " . $paciente['0']['apellidos'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                </tr>\n";

        if ($paciente['0']['sexo_id'] == 'M') {
            $sexo = 'MASCULINO';
        } else {
            $sexo = 'FEMENINO';
        }
        list($anio, $mes, $dias) = explode(":", $paciente['0']['edad']);

        if ($anio != 0) {

            $edad_t = 'AÑOS';
            $edad_t = htmlentities($edad_t, ENT_QUOTES,'UTF-8');
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

        $html .= "                <tr>\n";
        $html .= "                  <td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">EDAD</td>\n";
        $html .= "                  <td >" . $edad . " &nbsp; $edad_t \n";
        $html .= "                  </td>\n";
        $html .= "                  <td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEXO</td>\n";
        $html .= "                  <td align=\"left\">" . $sexo . "\n";
        $html .= "                  </td>\n";
        $html .= "                </tr>\n";
        $html .= "              </table>\n";
        $html .= "          <tr>\n";
        $html .= "            <td align=\"center\">\n";
        $html .= "            <td>\n";
        $html .= "          </tr>\n";
        $html .= "          <tr>\n";
        $html .= "            <td align=\"center\">\n";
        $html .= "            <td>\n";
        $html .= "          </tr>\n";
        $html .= "          <tr>\n";
        $html .= "            <td align=\"center\">\n";
        $html .= "              <table   width=\"98%\" class=\"label\" $style>\n";
        $html .= "                <tr>\n";
        $html .= "                  <td  colspan=\"12\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTOS  SOLICITADOS.</td>\n";
        $html .= "                </tr>\n";
        $html .= "                <tr>\n";
        $html .= "                  <td  colspan=\"2\"  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CODIGO</td>\n";
        $html .= "                  <td   colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTO</td>\n";
        $html .= "                  <td   colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">PRINCIPIO ACTIVO</td>\n";
        $html .= "                  </td>\n";
        $html .= "</tr>";
        $est = " ";
        $back = " ";

        for ($i = 0; $i < sizeof($medi_form); $i++) {

            $html .= "  <tr   " . $est . " onmouseout=\"mOut(this,'$back');\" onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
            $html .= "  <td align=\"center\" colspan=\"2\">" . $medi_form[$i]['codigo_medicamento'] . " </td>";
            $html .= "  <td align=\"center\" colspan=\"2\">" . $medi_form[$i]['descripcion_prod'] . " </td>";
            $html .= "  <td colspan=\"2\" align=\"center\" width=\"43%\">" . $medi_form[$i]['principio_activo'] . "</td>";
            $html .= "        <tr >\n";
            $html .= "    <td >";
            $html .= "      <table   width=\"70%\" >";
            $html .= "        <tr >\n";
            $html .= "  <td  class=\"label\"  align=\"left\" colspan=\"2\" ><b>Cantidad a Entregar: </b>" . round($medi_form[$i]['cantidad_entrega']) . " " . $medi_form[$i]['unidad_dosificacion'] . "</td>";
            $html .= "</tr>";
            $html .= "        <tr >\n";
            $html .= "  <td class=\"label\"  align=\"left\" colspan=\"2\"  ><b>Perioricidad Entrega:<b>" . $medi_form[$i]['perioricidad_entrega'] . " </td>";
            $html .= "</tr>";
            $html .= "        <tr >\n";
            $html .= "  <td class=\"label\"  align=\"left\" colspan=\"2\"  ><b>Tiempo Tratamiento:<b>" . $medi_form[$i]['tiempo_total'] . " </td>";
            $html .= "</tr>";
            $html .= "      </table>";
            $html .= "    </td>";
            $html .= "  </tr>";
            $html .= " </tr>";
        }
        $html .= "              </table>\n";
        $html .= "          <tr>\n";
        $html .= "            <td align=\"center\">\n";
        $html .= "          <tr>\n";
        $html .= "            <td align=\"center\">\n";
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


        $html .= "<form name=\"buscador\" id=\"buscador\"  method=\"post\">\n";
        $html .= "  <div id='error_doc' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $html .= "  <div id=\"ventana1\">\n";
        $html .= "   <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "     <tr >\n";
        $html .= "        <td class=\"formulacion_table_list\" colspan=\"6\">BUSCADOR......</td>";
        $html .= "     </tr>\n";
        $html .= "     <tr class=\"modulo_table_list_title\">\n";
        $html .= "        <td  align=\"left\">CODIGO BARRAS</td>\n";
        $html .= "       <td class=\"modulo_list_claro\" >";
        $empresa = SessionGetVar("DatosEmpresaAF");
        $html .= "                      <input type=\"hidden\" id=\"empresa_id\" name=\"empresa_id\" value=\"" . $empresa['empresa_id'] . "\">\n";
        $html .= "                      <input type=\"hidden\" id=\"centro_utilidad\" name=\"centro_utilidad\" value=\"" . $empresa['centro_utilidad'] . "\">\n";
        $html .= "                       <input type=\"hidden\" id=\"bodega\" name=\"bodega\" value=\"" . $empresa['bodega'] . "\">\n";
        $html .= "                       <input type=\"hidden\" id=\"evolucion\" name=\"evolucion\" value=\"" . $paciente['0']['evolucion_id'] . "\">\n";
        $html .= "        <input type=\"text\" name=\"codigo_barras\" id=\"codigo_barras\" class=\"input-text\" style=\"width:100%\" onkeydown=\"recogerTeclaBus(event);\">";
        $html .= "        </td>\n";
        $html .= "       <td  align=\"left\">DESCRIPCION</td>\n";
        $html .= "        <td class=\"modulo_list_claro\" >";
        $html .= "       <input type=\"text\" name=\"descripcion\"  id=\"descripcion\" class=\"input-text\" style=\"width:100%\" onkeydown=\"recogerTeclaBus(event)\">";
        $html .= "      </td>\n";
        $html .= "       <td  align=\"left\">LOTE</td>\n";
        $html .= "       <td class=\"modulo_list_claro\" >";
        $html .= "        <input type=\"text\" name=\"lote\" id=\"lote\"  class=\"input-text\" style=\"width:100%\" onkeydown=\"recogerTeclaBus(event)\">";
        $html .= "       </td>\n";
        $html .= "     </tr>\n";
        $html .= "   </table>";
        $html .= "  </div>";
        $html .= "</form>\n";
        $colores['PV'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_proximo_vencer');
        $colores['VN'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_vencido');

//        $html .= "               <table class=\"modulo_table_list\" width=\"35%\" align=\"center\">";
//        $html .= "               <td  class=\"label\" style=\"background:" . $colores['PV'] . "\" width=\"50%\" align=\"center\">";
//        $html .= "                  PROD. PROXIMO A VENCER";
//        $html .= "                  </td>";
//        $html .= "                 <td  class=\"label\" style=\"background:" . $colores['VN'] . "\" width=\"50%\" align=\"center\">";
//        $html .= "                  PROD. VENCIDO";
//        $html .= "                  </td>";
//        $html .= "                 </table>";
        $html .= "               <br>";
        $html .= "  <div id=\"BuscadorProductos\"></div><br>\n";
        $html .= "  <div id='productostmp'></div>\n";

        $html.= " <script>";
        $html.= " xajax_MostrarProductox('" . $paciente['0']['evolucion_id'] . "'); ";
        $html.= " </script>";

        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        
        return $html;
    }

    /*   PREPARAR DOCUMENTO PARA LA DISPENSACION */

    function Forma_Preparar_Documento_Dispensar_($action, $empresa, $Cabecera_Formulacion, $temporales, $evolucion, $pendiente, $todo_pendiente) {
  
        $sql = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
        $tipos_formula = $sql->consultar_tipo_formulas();
       
        $html = ThemeAbrirTabla('ENTREGA MEDICAMENTOS ');
        
        $html .= "  <script>
            
                        function confirmar_entrega_medicamentos(observacion, evolucion, pendiente, comentario, todo_pendiente){
                        
                            alert('Una vez realizada la entrega de los medicamentos no se podr\xe1 modificar');
                            
                            if(confirm('Â¿Desea realizar la entrega de medicamentos ?')){                                
                                xajax_PacienteReclama(observacion, evolucion, pendiente, comentario, todo_pendiente)
                            }else{
                                alert('Â¡Por favor verifique que los medicamentos entregados coinciden con los formulados!');
                            }
                            
                        }
                        
                        function guardar_tipo_formula(){
                        
                            var selected_id = document.getElementById('tipo_formula');
                            var tipo_formula_id = selected_id.options[selected_id.selectedIndex].value;
                           
                            if(tipo_formula_id !==''){ 
                                xajax_guardar_tipo_formula('{$evolucion}', tipo_formula_id);
                            }
                            
                            habilitar_btn_reclama_paciente(true);
                            
                        }
                        
                        function habilitar_btn_reclama_paciente(opcion){
                        
                            document.getElementById('btn_reclama_paciente').disabled = opcion;
                        }
                        
                    </script>

        ";
        
        $html .= "<form name=\"FormaPintarEntrega\" id=\"FormaPintarEntrega\"  method=\"post\" >\n";
        $html .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "                   <tr class=\"formulacion_table_list\">\n";
        $html .= "                     <td align=\"center\">\n";
        $html .= "                        <a title='farmacia'>FARMACIA:<a>";
        $html .= "                      </td>\n";
        $html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
        $html .= "                          " . $empresa['razon_social'] . " -" . $empresa['centro_utilidad_des'];
        $html .= "                       </td>\n";
        $html .= "                      <td align=\"center\">\n";
        $html .= "                         BODEGA";
        $html .= "                       </td>\n";
        $html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "                       " . $empresa['bodega_des'];
        $html .= "                       </td>\n";
        $html .= "  </tr>\n";
        $html .= "                   <tr class=\"formulacion_table_list\">\n";
        $html .= "                     <td align=\"center\">\n";
        $html .= "                        <a title='Identificacion'>IDENTIFICACION:<a>";
        $html .= "                      </td>\n";
        $html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
        $html .= "                          " . $Cabecera_Formulacion['tipo_id_paciente'] . " " . $Cabecera_Formulacion['paciente_id'];
        $html .= "                       </td>\n";
        $html .= "                      <td align=\"center\">\n";
        $html .= "                         PACIENTE ";
        $html .= "                       </td>\n";
        $html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
        $html .= "                        " . $Cabecera_Formulacion['nombres'] . " " . $Cabecera_Formulacion['apellidos'];
        $html .= "                       </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "                     <td align=\"center\">\n";
        $html .= "                        <a title='Identificacion'>TIPO FORMULA:<a>";
        $html .= "                      </td>\n";
        $html .= "			  <td align=\"left\" class=\"modulo_list_claro\" colspan=\"\">\n";
        $html .= "                          <select name=\"tipo_formula\" id='tipo_formula' class=\"select\" style=\"width:60%\" onchange='guardar_tipo_formula()'>\n";
        $html .= "				<option value = '' > -- Seleccione -- </option>\n";
        
        $selected = "";
        foreach ($tipos_formula as $key => $dtl) {
            
            if($Cabecera_Formulacion['tipo_formula']== $dtl['tipo_formula_id']){
                $selected = " selected = 'selected' ";
            }
            
            $html .= "  			<option value=\"" . $dtl['tipo_formula_id'] . "\" " . $selected . ">";
            $html .= "				" . $dtl['descripcion_tipo_formula'] . "" . (($dtl['tope'] != "") ? " (SALDO TOPE MENSUAL: $" . FormatoValor($dtl['tope']) . ")" : "") . "";
            $html .= "				</option>\n";
        }
        $html .= "										</select>\n";
        $html .= "				</td>\n";
        $html .= "  </tr>\n";
        $html .= "  </table>\n";
        
        $strings = htmlentities("!", ENT_QUOTES,'UTF-8');   
        $html .= "<table align='center' border='0' style='color: red;'>\n";
        $html .= "<tr >\n";
        $html .= "<td align='center' valign='middle'>\n";
        $html .= "<b>NOTA:</b>";
        $html .= "</td>\n";
        $html .= "<td align='center' valign='middle'>\n";
        $html .= " $strings".""."Por favor verifique que los medicamentos entregados coincidan con los formulados!";        
        $html .= "</td>\n";
        $html .= "</tr>\n";
        $html .= "</table>\n";
        
        
        $html .= "  \n";
        $html .= "  <table width=\"85%\" class=\"modulo_table_list\"   align=\"center\">";
        $html .= "    <tr class=\"formulacion_table_list\" >\n";
        $html .= "      <td width=\"20%\" >CODIGO PRODUCTO</td>\n";
        $html .= "      <td width=\"35%\">PRODUCTO</td>\n";
        $html .= "      <td width=\"15%\">FECHA VEC</td>\n";
        $html .= "      <td width=\"15%\">LOTE</td>\n";
        $html .= "      <td width=\"30%\">ENTREGA</td>\n";
        $html .= "  </tr>\n";

        foreach ($temporales as $k1 => $dt1) {

            $html .= "  <tr class=\"modulo_list_claro\" >\n";
            $html .= "      <td align=\"left\"><b>" . $dt1['codigo_producto'] . "</b></td>\n";
            $html .= "      <td align=\"left\"><b>" . $dt1['descripcion_prod'] . "</b></td>\n";
            $html .= "      <td align=\"left\"><b>" . $dt1['fecha_vencimiento'] . "</b></td>\n";
            $html .= "      <td align=\"left\"><b>" . $dt1['lote'] . "</b></td>\n";
            $html .= "      <td align=\"left\"><b>" . $dt1['cantidad_despachada'] . " </b></td>\n";
            $html .= "  </tr>\n";
        }
        $html .= "  </table><br>\n";
        $html .= "  <br>\n";

        $html .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "                     <tr class=\"formulacion_table_list\" >\n";
        $html .= "                        <td rowspan='1' colspan='10' align=\"center\" class=\"modulo_list_claro\"> \n";
        $html .= "                          <fieldset>";
        $html .= "                           <legend>OBSERVACIONES..</legend>";
        $html .= "                              <TEXTAREA id='observar' name='observar' ROWS='3' COLS=100 ></TEXTAREA>\n";
        $html .= " <input type=\"hidden\" name=\"observacion2\" value=\"No. Formula: {$Cabecera_Formulacion['numero_formula']} Evolucion No: " . $evolucion . "  Paciente:" . $Cabecera_Formulacion['tipo_id_paciente'] . " " . $Cabecera_Formulacion['paciente_id'] . " " . $Cabecera_Formulacion['nombres'] . " " . $Cabecera_Formulacion['apellidos'] . "\" > ";
        $html .= "                        </td>\n";
        $html .= "                     </tr>\n";
        $html .= "</table>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "  <tr>\n";

        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        //$html .= "         <input class=\"input-submit\" id='btn_reclama_paciente' disabled='disabled' type=\"button\" value=\"RECLAMA PACIENTE..\" onclick=\"xajax_PacienteReclama(document.FormaPintarEntrega.observar.value,'" . $evolucion . "','" . $pendiente . "',document.FormaPintarEntrega.observacion2.value,'" . $todo_pendiente . "')\" class=\"label_error\">\n";
        $html .= "         <input class=\"input-submit\" id='btn_reclama_paciente' disabled='disabled' type=\"button\" value=\"RECLAMA PACIENTE..\"  onclick=\"confirmar_entrega_medicamentos(document.FormaPintarEntrega.observar.value, '{$evolucion}', '{$pendiente}', document.FormaPintarEntrega.observacion2.value, '{$todo_pendiente}' )\" class=\"label_error\">\n";
        $html .= "      </td>\n";
        $html .= "      <td>\n";
        $html .= "      </td>\n";
        $html .= "      <td>\n";
        $html .= "      </td>\n";
        $html .= "      <td>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "</form> ";
        
        
        if(!empty($selected)){
              $html .= "<script>document.getElementById('btn_reclama_paciente').disabled =false; </script>";
        }
        
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*  PENDIENTES  LISTA */

    function FormaFomulaPaciente_P($action, $request, $paciente, $medi_form, $evolucion) {
        $ctl = AutoCarga::factory("ClaseUtil");
        $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
        $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
        $html = $ctl->RollOverFilas();
        $html .= " <script> \n";
        $html .= " function recogerTeclaBus(evt) ";
        $html .= " {";
        $html .= "var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   ";
        $html .= "var keyChar = String.fromCharCode(keyCode);";
        $html .= "if(keyCode==13)";
        $html .= "{   ";
        $html .= "   xajax_BuscarProducto2(xajax.getFormValues('buscador'),'" . $evolucion . "',1); ";
        $html .= "}";
        $html .= " }   ";
        $html .= "  function ValidarCantidad(campo,valor,cant_sol,capa)\n";
        $html .= "  {\n";
        $html .= "    document.getElementById(campo).style.background='';\n";
        $html .= "    document.getElementById('error').innerHTML='';\n";
        $html .= "    if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='')\n";
        $html .= "    {\n";
        $html .= "      document.getElementById(campo).value='';\n";
        $html .= "      document.getElementById(campo).style.background='#ff9595';\n";
        $html .= "      document.getElementById('error').innerHTML='<center>CANTIDAD NO VALIDA</center>';\n";
        $html .= "      document.getElementById(capa).style.display=\"none\"\n";
        $html .= "    }\n";
        $html .= "    else{\n";
        $html .= "      document.getElementById(capa).style.display=\"\"\n";
        $html .= "    }\n";
        $html .= "  }\n";
        $html .= "  function Recargar_informacion(bodega_otra)\n";
        $html .= "  {\n";
        $html .= " document.buscador.bodega.value=bodega_otra; ";
        $html .= "  xajax_BuscarProducto2(xajax.getFormValues('buscador'),'" . $evolucion . "','');  ";
        $html .= " }";
        $html .=" </script>\n";
        $html .= ThemeAbrirTabla('FORMULA MEDICA  COMPLETA ');
        $html .= "<table border=\"0\" width=\"98%\" align=\"center\" >\n";
        $html .= "  <tr>\n";
        $html .= "    <td>\n";
        $html .= "      <fieldset class=\"fieldset\">\n";
        $html .= "        <legend class=\"normal_10AN\">INFORMACION DE LA FORMULA</legend>\n";
        $html .= "        <table width=\"100%\" cellspacing=\"2\">\n";
        $html .= "          <tr>\n";
        $html .= "            <td align=\"center\">\n";
        $html .= "              <table width=\"98%\" class=\"label\" $style>\n";
        $html .= "                <tr >\n";
        $html .= "                  <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE REGISTRO</td>\n";
        $html .= "                  <td width=\"10%\" align=\"right\">" . $paciente['fecha_registro'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                  <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE FORMULA</td>\n";
        $html .= "                  <td width=\"10%\" align=\"right\">" . $paciente['fecha_formulacion'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                  <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >EVOLUCION No</td>\n";
        $html .= "                  <td width=\"10%\" align=\"right\">" . $paciente['evolucion_id'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                </tr>\n";
        $html .= "              </table>\n";
        $html .= "          <tr>\n";
        $html .= "            <td align=\"center\">\n";
        $html .= "              <table  width=\"98%\" class=\"label\" $style>\n";
        $html .= "                <tr>\n";
        $html .= "                  <td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
        $html .= "                  <td colspan=\"3\">\n";
        $html .= "                    " . $paciente['tipo_id_paciente'] . " " . $paciente['paciente_id'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                  <td  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">NOMBRE COMPLETO</td>\n";
        $html .= "                  <td  > " . $paciente['nombres'] . " " . $paciente['apellidos'] . "\n";
        $html .= "                  </td>\n";
        $html .= "                </tr>\n";
        if ($paciente['sexo_id'] == 'M') {
            $sexo = 'MASCULINO';
        } else {
            $sexo = 'FEMENINO';
        }
        list($anio, $mes, $dias) = explode(":", $paciente['edad']);

        if ($anio != 0) {

            $edad_t = 'AÑOS';
            $edad_t = htmlentities($edad_t, ENT_QUOTES,'UTF-8');
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

        $html .= "                <tr>\n";
        $html .= "                  <td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">EDAD</td>\n";
        $html .= "                  <td >" . $edad . " &nbsp; $edad_t \n";
        $html .= "                  </td>\n";
        $html .= "                  <td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEXO</td>\n";
        $html .= "                  <td align=\"left\">" . $sexo . "\n";
        $html .= "                  </td>\n";
        $html .= "                </tr>\n";
        $html .= "              </table>\n";
        $html .= "          <tr>\n";
        $html .= "            <td align=\"center\">\n";
        $html .= "              <table   width=\"98%\" class=\"label\" $style>\n";
        $html .= "                <tr>\n";
        $html .= "                  <td  colspan=\"12\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTOS  SOLICITADOS..</td>\n";
        $html .= "                </tr>\n";
        $html .= "                <tr>\n";
        $html .= "                  <td  colspan=\"2\"  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CODIGO</td>\n";
        $html .= "                  <td   colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTO</td>\n";
        $html .= "                  <td   colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CANTIDAD</td>\n";

        $html .= "</tr>";

        $est = " ";
        $back = " ";
        for ($i = 0; $i < sizeof($medi_form); $i++) {
            $html .= "  <tr   " . $est . " onmouseout=mOut(this,'$back'); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
            $html .= "  <td align=\"center\" colspan=\"2\">" . $medi_form[$i]['codigo_medicamento'] . " </td>";
            $html .= "  <td align=\"center\" colspan=\"2\">" . $medi_form[$i]['descripcion_prod'] . " </td>";
            $html .= "  <td colspan=\"2\" align=\"center\" width=\"43%\">" . $medi_form[$i]['total'] . "</td>";
            $html .= "</tr>";
        }
        $html .= "      </table>";
        $html .= "    </td>";
        $html .= "  </tr>";

        $html .= "            </td>\n";
        $html .= "          </tr>\n";
        $html .= "        </table>\n";
        $html .= "      </fieldset>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<form name=\"buscador\" id=\"buscador\" action=\"\" method=\"post\">\n";
        $html .= "  <div id='error_doc' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $html .= "  <div id=\"ventana1\">\n";
        $html .= "   <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "     <tr >\n";
        $html .= "        <td class=\"formulacion_table_list\" colspan=\"6\">BUSCADOR.</td>";
        $html .= "     </tr>\n";
        $html .= "     <tr class=\"modulo_table_list_title\">\n";
        $html .= "        <td  align=\"left\">CODIGO BARRAS</td>\n";
        $html .= "       <td class=\"modulo_list_claro\" >";
        $empresa = SessionGetVar("DatosEmpresaAF");
        $html .= "                          <input type=\"hidden\" id=\"orden_requisicion_id\" name=\"orden_requisicion_id\" value=\"" . $Cabecera_Formulacion['formula_id'] . "\">\n";
        $html .= "                      <input type=\"hidden\" id=\"empresa_id\" name=\"empresa_id\" value=\"" . $empresa['empresa_id'] . "\">\n";
        $html .= "                      <input type=\"hidden\" id=\"centro_utilidad\" name=\"centro_utilidad\" value=\"" . $empresa['centro_utilidad'] . "\">\n";
        $html .= "                       <input type=\"hidden\" id=\"bodega\" name=\"bodega\" value=\"" . $empresa['bodega'] . "\">\n";
        $html .= "        <input type=\"text\" name=\"codigo_barras\" id=\"codigo_barras\" class=\"input-text\" style=\"width:100%\" onkeydown=\"recogerTeclaBus(event)\">";
        $html .= "        </td>\n";
        $html .= "       <td  align=\"left\">DESCRIPCION</td>\n";
        $html .= "        <td class=\"modulo_list_claro\" >";
        $html .= "       <input type=\"text\" name=\"descripcion\" id=\"descripcion\"  class=\"input-text\" style=\"width:100%\" onkeydown=\"recogerTeclaBus(event)\">";
        $html .= "      </td>\n";
        $html .= "       <td  align=\"left\">LOTE</td>\n";
        $html .= "       <td class=\"modulo_list_claro\" >";
        $html .= "        <input type=\"text\" name=\"lote\" id=\"lote\" class=\"input-text\"  style=\"width:100%\" onkeydown=\"recogerTeclaBus(event)\">";
        $html .= "       </td>\n";
        $html .= "     </tr>\n";
        $html .= "   </table>";
        $html .= "  </div>";
        $html .= "</form>\n";
        $colores['PV'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_proximo_vencer');
        $colores['VN'] = ModuloGetVar('app', 'ReportesInventariosGral', 'color_vencido');

        $html .= "               <table class=\"modulo_table_list\" width=\"35%\" align=\"center\">";
        $html .= "               <td  class=\"label\" style=\"background:" . $colores['PV'] . "\" width=\"50%\" align=\"center\">";
        $html .= "                  PROD. PROXIMO A VENCER";
        $html .= "                  </td>";
        $html .= "                 <td  class=\"label\" style=\"background:" . $colores['VN'] . "\" width=\"50%\" align=\"center\">";
        $html .= "                  PROD. VENCIDO";
        $html .= "                  </td>";
        $html .= "                 </table>";
        $html .= "               <br>";
        $html .= "  <div id=\"BuscadorProductos\"></div><br>\n";
        $html .= "  <div id='productostmp'></div>\n";
        $html.= " <script>";
        $html.= " xajax_MostrarProductox2('" . $evolucion . "'); ";
        $html.= " </script>";
        $html.= " <br>";

        $html .= "<table align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";

        $html .= ThemeCerrarTabla();
        return $html;
    }

    /**
     * Funcion donde se crea la forma mostrar el detalle del despacho
     * @param array $action Vector de links de la aplicaion
     * @param array $datos  Vector de medicamentos despachados
     * @param array $request vector que contiene la informacion del request
     * @param string $tipo_id_paciente cadena con el tipo de identificacion del paciente
     * @param string $paciente_id cadena con el numero  de identificacion del paciente
     * @param string $primer_nombre  cadena con el primero nombre del paciente
     * @param string $primer_nombre  cadena con el primero nombre del paciente
     * @param string $segundo_nombre  cadena con el segundo  nombre del paciente
     * @param string $primer_apellido  cadena con el primero apellido del paciente
     * @param string $segundo_apellido   cadena con el sengundo apellido del paciente
     * @return string $html retorna la cadena con el codigo html de la pagina
     * @return String
     */
    function FormaPintarDetalle($action, $datos, $tipo_id_paciente, $paciente_id, $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $datosNR) {

        $html = ThemeAbrirTabla('DESPACHO DE MEDICAMENTOS DETALLE');
        $html .= "<form name=\"Forma5\" id=\"Forma5\" method=\"post\">\n";
        $html .= "  <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"25%\">IDENTIFICACION</td>\n";
        $html .= "      <td>NOMBRE</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"label_mark\">" . $tipo_id_paciente . "  " . $paciente_id . "</td>\n";
        $html .= "      <td class=\"label_mark\">\n";
        $html .= "         " . $primer_nombre . "   " . $segundo_nombre . "  " . $primer_apellido . "  " . $segundo_apellido;
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "  </table><br>\n";

        if (!empty($datos)) {

            $html .= "<fieldset class=\"fieldset\" style=\"width:95%\">\n";
            $html .= "  <legend class=\"normal_10AN\" align=\"center\">DESPACHO FORMULA</legend>\n";
            $html .= "  <table width=\"90%\" class=\"modulo_table_list\" align=\"center\">";
            $html .= "    <tr class=\"formulacion_table_list\" >\n";
            $html .= "      <td width=\"25%\">PRODUCTO DESPACHADO</td>\n";
            $html .= "      <td width=\"25%\" class=\"modulo_list_oscuro\" >" . $datos['0']['codigo_medicamento_despachado'] . " </td>\n";
            $html .= "      <td width=\"%\" colspan=\"2\" class=\"modulo_list_oscuro\" > " . $datos['0']['producto'] . " </td>\n";
            $html .= "    </tr>\n";
            $html .= "    <tr class=\"formulacion_table_list\">\n";
            $html .= "      <td >CANTIDAD</td>\n";
            $html .= "      <td colspan=\"3\" class=\"modulo_list_oscuro\" > " . $datos['0']['cantidad_entrega'] . " " . $datos['0']['unidad_entrega'] . " </td>\n";
            $html .= "  </tr>\n";
            $html .= "    <tr class=\"formulacion_table_list\" >\n";
            $html .= "      <td >FECHA ENTREGA</td>\n";
            $html .= "      <td class=\"modulo_list_oscuro\" width=\"15%\">" . $datos['0']['fecha_entrega'] . "  </td>\n";
            $html .= "      <td width=\"25%\">FECHA PROXIMA ENTREGA</td>\n";
            $html .= "      <td  class=\"modulo_list_oscuro\" width=\"25%\"> " . $datos['0']['fecha_proxima_entrega'] . " </td>\n";
            $html .= "    </tr>\n";
            $html .= "    <tr class=\"formulacion_table_list\" >\n";
            $html .= "      <td >EMPRESA</td>\n";
            $html .= "      <td class=\"modulo_list_oscuro\" colspan=\"3\">" . $datos['0']['razon_social'] . "  </td>\n";
            $html .= "  </tr>\n";
            $html .= "    </tr>\n";
            $html .= "    <tr class=\"formulacion_table_list\" >\n";
            $html .= "    <td >DESPACHO</td>\n";
            $html .= "      <td class=\"modulo_list_oscuro\" > " . $datos['0']['nombre'] . " </td>\n";
            $html .= "      <td >PERSONA RECLAMO</td>\n";

            if ($datos['0']['persona_reclama_tipo_id'] == "") {
                $html .= "      <td  class=\"modulo_list_oscuro\"  width=\"15%\"> " . $tipo_id_paciente . " " . $paciente_id . " " . $primer_nombre . " " . $primer_apellido . "</td>\n";
            } else {
                $html .= "      <td  class=\"modulo_list_oscuro\"  width=\"15%\"> " . $datos['0']['persona_reclama_tipo_id'] . " " . $datos['0']['persona_reclama_id'] . " " . $datos['0']['persona_reclama'] . "</td>\n";
            }
            $html .= "    </tr>\n";
            if ($datos['0']['observacion'] != "") {
                $html .= "    <tr class=\"formulacion_table_list\" >\n";
                $html .= "      <td colspan=\"4\">OBSERVACIONES</td>\n";
                $html .= "    </tr>\n";
                $html .= "    <tr class=\"modulo_list_claro\" >\n";
                $html .= "      <td colspan=\"4\">" . $datos['0']['observacion'] . "</td>\n";
                $html .= "    </tr>\n";
            }
            $html .= "  </table>\n";
            $html .= "  </fieldset><br>\n";
        }
        if (!empty($datosNR)) {
            $html .= "<fieldset class=\"fieldset\" style=\"width:95%\">\n";
            $html .= "  <legend class=\"normal_10AN\" align=\"center\">PACIENTE NO RECLAMO</legend>\n";

            $html .= "  <table width=\"90%\" class=\"modulo_table_list\" align=\"center\">";
            $html .= "    <tr class=\"formulacion_table_list\" >\n";
            $html .= "      <td width=\"25%\">PRODUCTO FORMULA</td>\n";
            $html .= "      <td width=\"25%\" class=\"modulo_list_oscuro\" >" . $datosNR['0']['codigo_medicamento'] . " </td>\n";
            $html .= "      <td width=\"%\" colspan=\"2\" class=\"modulo_list_oscuro\" > " . $datosNR['0']['nombre_producto'] . " </td>\n";
            $html .= "    </tr>\n";
            $html .= "    <tr class=\"formulacion_table_list\">\n";
            $html .= "      <td >CANTIDAD</td>\n";
            $html .= "      <td colspan=\"3\" class=\"modulo_list_oscuro\" > " . $datosNR['0']['cantidad_pendiente'] . " </td>\n";
            $html .= "  </tr>\n";

            $html .= "    <tr class=\"formulacion_table_list\" >\n";
            $html .= "      <td >FECHA PROXIMA ENTREGA</td>\n";
            $html .= "      <td  class=\"modulo_list_oscuro\" colspan=\"3\"> " . $datosNR['0']['fecha_proxima_entrega'] . " </td>\n";
            $html .= "    </tr>\n";
            $html .= "    <tr class=\"formulacion_table_list\" >\n";
            $html .= "      <td >EMPRESA</td>\n";
            $html .= "      <td class=\"modulo_list_oscuro\" colspan=\"3\">" . $datosNR['0']['razon_social'] . "  </td>\n";
            $html .= "  </tr>\n";
            $html .= "    <tr class=\"formulacion_table_list\" >\n";
            $html .= "    <td >USUARIO SISTEMA</td>\n";
            $html .= "      <td class=\"modulo_list_oscuro\"colspan=\"3\" > " . $datosNR['0']['nombre'] . " </td>\n";

            $html .= "    </tr>\n";
            if ($datosNR['0']['observacion'] != "") {
                $html .= "    <tr class=\"formulacion_table_list\" >\n";
                $html .= "      <td colspan=\"4\">OBSERVACIONES</td>\n";
                $html .= "    </tr>\n";
                $html .= "    <tr class=\"modulo_list_claro\" >\n";
                $html .= "      <td colspan=\"4\">" . $datosNR['0']['observacion'] . "</td>\n";
                $html .= "    </tr>\n";
            }
            $html .= "  </table><br>\n";
            $html .= "  </fieldset><br>\n";
        }



        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . " \" class=\"label_error\">\n";
        $html .= "        VOLVER\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "  </form>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /**
     * Funcion donde se crea la forma que permite Mostrar el mensaje de entrega del paciente
     * @param array $action Vector de links de la aplicaion
     * @param string $tipo_id_paciente cadena con el tipo de identificacion del paciente
     * @param string $paciente_id cadena con el numero  de identificacion del paciente
     * @param string $primer_nombre  cadena con el primero nombre del paciente
     * @param string $segundo_nombre  cadena con el segundo  nombre del paciente
     * @param string $primer_apellido  cadena con el primero apellido del paciente
     * @param string $segundo_apellido   cadena con el sengundo apellido del paciente
     * @return string $html retorna la cadena con el codigo html de la pagina
     * @return string $html retorna la cadena con el codigo html de la pagina
     */
    function FormaPintarUltimoPaso($action, $formula_id, $pendientes, $evolucion, $todo_pendiente, $msj_ws) {
        $html .= ThemeAbrirTabla('MENSAJE DE ENTREGA DE MEDICAMENTO');
        $html .= "<form name=\"FormaPintarEntrega2\" id=\"FormaPintarEntrega2\"  method=\"post\" >\n";
        $html .= "                 <table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";

        $html .= "                     <tr class=\"modulo_table_list_title\"  >\n";
        $html .= "                        <td  class=\"modulo_list_claro\"  colspan='10' align=\"center\"><b> \n";
        $html .= "                           SE REALIZO LA ENTREGA DE LOS MEDICAMENTOS  ";
        $html .= "                        </b></td>\n";
        $html .= "                     </tr>\n";
        $html .= "                     <tr class=\"modulo_table_list_title\"  >\n";
        $html .= "                        <td  class=\"modulo_list_claro\"  colspan='10' align=\"center\"><b> \n";
        $html .= "                           Msj WS Notificacion = {$msj_ws} = ";
        $html .= "                        </b></td>\n";
        $html .= "                     </tr>\n";
        $html .= "  </table><br>\n";

        $html .= " <script>
            document.oncontextmenu = function(){return false}
                      </script> ";

        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr class=\"modulo_table_list\">\n";

        $reporte2 = new GetReports();
        $mostrar_P = $reporte2->GetJavaReport('app', 'Dispensacion', 'MedicamentoDispensado', array("evolucion" => $evolucion, "tipo_reporte" => 0), array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));


        $funcion = $reporte2->GetJavaFunction();
        $html .= "        " . $mostrar_P . "\n";
        if ($todo_pendiente != '1') {
            $html .= " <td align=\"center\" width=\"33%\"><a href=\"javascript:$funcion\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'> <b>IMPRIMIR  DISPENSADOS.</b></a></td>";
        }


        if (!empty($pendientes)) {

            //$mostrar_ = $reporte2->GetJavaReport('app', 'Dispensacion', 'MedicamentoPendientes', array("evolucion" => $evolucion), array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $mostrar_ = $reporte2->GetJavaReport('app', 'Dispensacion', 'MedicamentoDispensado', array("evolucion" => $evolucion, "tipo_reporte" => 2), array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));

            $funcion2 = $reporte2->GetJavaFunction();
            $html .= "        " . $mostrar_ . "\n";
            $html .= " <td align=\"center\" width=\"33%\"><a href=\"javascript:$funcion2\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'> <b>IMPRIMIR PENDIENTES..</b></a></td>";
        }

        $html .= "  </tr>\n";
        $html .= "</table>\n";

        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> \n";
        $html .= "</table>\n";
        $html .= "</form> ";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*
     * Funcion donde se crea una Forma con una Ventana con capas para mostrar informacion
     * en pantalla
     * @param int $tmn Tamaï¿½o que tendra la ventana
     * @return string
     */

    function CrearVentana($tmn, $Titulo) {
        $html .= "<script>\n";
        $html .= "  var contenedor = 'Contenedor';\n";
        $html .= "  var titulo = 'titulo';\n";
        $html .= "  var hiZ = 4;\n";
        $html .= "  function OcultarSpan()\n";
        $html .= "  { \n";
        $html .= "    try\n";
        $html .= "    {\n";
        $html .= "      e = xGetElementById('Contenedor');\n";
        $html .= "      e.style.display = \"none\";\n";
        $html .= "    }\n";
        $html .= "    catch(error){}\n";
        $html .= "  }\n";
        $html .= "  function MostrarSpan()\n";
        $html .= "  { \n";
        $html .= "    try\n";
        $html .= "    {\n";
        $html .= "      e = xGetElementById('Contenedor');\n";
        $html .= "      e.style.display = \"\";\n";
        $html .= "      Iniciar();\n";
        $html .= "    }\n";
        $html .= "    catch(error){alert(error)}\n";
        $html .= "  }\n";
        $html .= "  function MostrarTitle(Seccion)\n";
        $html .= "  {\n";
        $html .= "    xShow(Seccion);\n";
        $html .= "  }\n";
        $html .= "  function OcultarTitle(Seccion)\n";
        $html .= "  {\n";
        $html .= "    xHide(Seccion);\n";
        $html .= "  }\n";
        $html.="function mOut(src,clrIn) {\n";
        $html.="src.style.background = clrIn;\n";
        $html.="}\n";
        $html .= "  function Iniciar()\n";
        $html .= "  {\n";
        $html .= "    contenedor = 'Contenedor';\n";
        $html .= "    titulo = 'titulo';\n";
        $html .= "    ele = xGetElementById('Contenido');\n";
        $html .= "    xResizeTo(ele," . $tmn . ", 'auto');\n";
        $html .= "    ele = xGetElementById(contenedor);\n";
        $html .= "    xResizeTo(ele," . $tmn . ", 'auto');\n";
        $html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
        $html .= "    ele = xGetElementById(titulo);\n";
        $html .= "    xResizeTo(ele," . ($tmn - 20) . ", 20);\n";
        $html .= "    xMoveTo(ele, 0, 0);\n";
        $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $html .= "    ele = xGetElementById('cerrar');\n";
        $html .= "    xResizeTo(ele,20, 20);\n";
        $html .= "    xMoveTo(ele," . ($tmn - 20) . ", 0);\n";
        $html .= "  }\n";
        $html .= "  function myOnDragStart(ele, mx, my)\n";
        $html .= "  {\n";
        $html .= "    window.status = '';\n";
        $html .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
        $html .= "    else xZIndex(ele, hiZ++);\n";
        $html .= "    ele.myTotalMX = 0;\n";
        $html .= "    ele.myTotalMY = 0;\n";
        $html .= "  }\n";
        $html .= "  function myOnDrag(ele, mdx, mdy)\n";
        $html .= "  {\n";
        $html .= "    if (ele.id == titulo) {\n";
        $html .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
        $html .= "    }\n";
        $html .= "    else {\n";
        $html .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $html .= "    }  \n";
        $html .= "    ele.myTotalMX += mdx;\n";
        $html .= "    ele.myTotalMY += mdy;\n";
        $html .= "  }\n";
        $html .= "  function myOnDragEnd(ele, mx, my)\n";
        $html .= "  {\n";
        $html .= "  }\n";
        $html.= "function Cerrar(Elemento)\n";
        $html.= "{\n";
        $html.= "    capita = xGetElementById(Elemento);\n";
        $html.= "    capita.style.display = \"none\";\n";
        $html.= "}\n";
        $html .= "</script>\n";
        $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
        $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">" . $Titulo . "</div>\n";
        $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
        $html .= "  <div id='Contenido' class='d2Content'>\n";
        $html .= "  </div>\n";
        $html .= "</div>\n";
        $html .= "</script>\n";
        $html .= "<div id='Contenedor2' class='d2Container' style=\"display:none;z-index:4\">\n";
        $html .= "  <div id='titulo2' class='draggable' style=\" text-transform: uppercase;text-align:center;\">" . $Titulo . "</div>\n";
        $html .= "  <div id='cerrar2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
        $html .= "  <div id='Contenido2' class='d2Content'>\n";
        $html .= "  </div>\n";
        $html .= "</div>\n";
        return $html;
    }

    /*  Funcion donde se crea la forma que permite mostrar  mensaje donde no se encuentre un documento de bodega para la dispensacion de los pacientes
     * @param array $action Vector de links de la aplicaion
     * @return string $html retorna la cadena con el codigo html de la pagina
     * @return String
     */

    function FormaMenuMensaje($action) {
        $html = ThemeAbrirTabla('DISPENSACION');
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "  <tr>\n";
        $html .= "    <td  class=\"label_error\">\n";
        $html .= "    NO SE ENCONTRO UN DOCUMENTO PARAMETRIZADO PARA LA DISPENSACION</td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>";
        $html .= "</fieldset><br>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /**
     * Funcion donde se crea la forma del menu para los pendientes en el caso de que exista
     * @param array $action Vector de links de la aplicaion
     * @param array $request vector que contiene la informacion del request
     * @param array $datos vector que contiene la informacion de los medicamentos formulados que van hacer despachados
     * @return string $html retorna la cadena con el codigo html de la pagina
     * @return String
     */
    function FormaMenuPendiente($action, $request, $datos) {

        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= ThemeAbrirTabla('MENU - PENDIENTE');
        $pghtml = AutoCarga::factory('ClaseHTML');
        $html .= "  <table width=\"55%\" class=\"modulo_table_list\"   align=\"center\">";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"25%\">PACIENTE </td>\n";
        $html .= "      <td colspan=\"3\" align=\"left\" class=\"modulo_list_claro\"></b>" . $request['tipo_id_paciente'] . " " . $request['paciente_id'] . "<b>  " . $request['apellidos'] . " " . $request['nombres'] . "</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"formulacion_table_list\" >\n";
        $html .= "      <td >FECHA FORMULA</td>\n";
        $html .= "      <td align=\"left\" class=\"modulo_list_claro\" width=\"25%\">" . $request['fecha_formulacion'] . "</td>\n";
        $html .= "      <td width=\"25%\">FECHA FINALIZA</td>\n";
        $html .= "      <td align=\"left\" class=\"modulo_list_claro\" width=\"25%\">" . $request['fecha_finalizacion'] . "</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"formulacion_table_list\" >\n";
        $html .= "      <td >MEDICO</td>\n";
        $html .= "      <td colspan=\"3\" align=\"left\" class=\"modulo_list_claro\">" . $request['nombre'] . "</td>\n";
        $html .= "    </tr>\n";
        $html .= "  </table><BR>\n";


        $html .= "      <table border=\"0\" width=\"55%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "        <tr>\n";
        $html .= "          <td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL</td>\n";
        $html .= "        </tr>\n";
        $html .= "  <tr  class=\"modulo_list_claro\">\n";
        $html .= "      <td  class=\"label\"  align=\"center\">\n";
        $html .= "       <a href=\"" . $action['r_evento'] . "\">\n";
        $html .= "       REGISTRAR EVENTO</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";

        $html .= "  </table>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        //$html .= $this->CrearVentana(650,"GENERAR ENTREGA DE MEDICAMENTOS");
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /**
     * Funcion donde se crea la forma para registrar el evento para los pacientes
     * @param array $action Vector de links de la aplicaion
     * @param array $request vector que contiene la informacion del request
     * @param array $datos vector que contiene la informacion de los medicamentos formulados que van hacer despachados
     * @return string $html retorna la cadena con el codigo html de la pagina
     * @return String
     */
    function FormaRegistrarEvento($action, $request, $datos, $informacion, $bandera, $msm, $evolucion) {
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->RollOverFilas();
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("-");
        $html .= $ctl->LimpiarCampos();
        $html .= ThemeAbrirTabla('PENDIENTE-REGISTRAR EVENTO');
        $pghtml = AutoCarga::factory('ClaseHTML');

        $html .="<script >\n";
        $html .= "  function ValidarDtos(frms)\n";
        $html .= "  {\n";
        $html .= "    if(!IsDate(frms.fecha_inicio.value))\n";
        $html .= "    {\n";
        $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA ';\n";
        $html .= "      return;\n";
        $html .= "    } \n";
        $html .= "    frms.submit();\n";
        $html .= "    }\n";
        $html .="</script>\n";


        $html .= "  <table width=\"75%\" class=\"modulo_table_list\"   align=\"center\">";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"25%\">PACIENTE </td>\n";
        $html .= "      <td colspan=\"3\" align=\"left\" class=\"modulo_list_claro\"></b>" . $request['tipo_id_paciente'] . " " . $request['paciente_id'] . "<b>  " . $request['apellidos'] . " " . $request['nombres'] . "</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"25%\">EDAD </td>\n";
        $edad_t = htmlentities("AÑOS", ENT_QUOTES,'UTF-8');
        $html .= "      <td width=\"25%\" align=\"left\" class=\"modulo_list_claro\"></b>" . $datos[0]['edad'] . " &nbsp $edad_t</td>\n";
        $html .= "      <td width=\"25%\">SEXO </td>\n";
        $html .= "      <td width=\"25%\" align=\"left\" class=\"modulo_list_claro\"></b>" . $datos[0]['sexo_id'] . "</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"25%\">PLAN </td>\n";
        $html .= "      <td colspan=\"3\" align=\"left\" class=\"modulo_list_claro\"></b>" . $request['plan_descripcion'] . "</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"formulacion_table_list\" >\n";
        $html .= "      <td >FECHA FORMULA</td>\n";
        $html .= "      <td align=\"left\" class=\"modulo_list_claro\" width=\"25%\">" . $request['fecha_formulacion'] . "</td>\n";
        $html .= "      <td width=\"25%\">FECHA FINALIZA</td>\n";
        $html .= "      <td align=\"left\" class=\"modulo_list_claro\" width=\"25%\">" . $request['fecha_finalizacion'] . "</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"formulacion_table_list\" >\n";
        $html .= "      <td >MEDICO</td>\n";
        $html .= "      <td colspan=\"3\" align=\"left\" class=\"modulo_list_claro\">" . $request['nombre'] . "</td>\n";
        $html .= "    </tr>\n";
        $html .= "  </table><BR>\n";


        $html .= "<center>\n";
        $html .= "<fieldset class=\"fieldset\" style=\"width:85%\">\n";
        $html .= "  <legend class=\"normal_10AN\" align=\"left\">MEDICAMENTOS PENDIENTES</legend>\n";
        $html .= "  <form name=\"FormaPp\" action=\"" . $action['ingresar'] . "\" method=\"post\">";
        $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
        $html .= "  <table width=\"95%\" class=\"modulo_table_list\"   align=\"center\">";
        $html .= "    <tr class=\"formulacion_table_list\" >\n";
        $html .= "      <td >CODIGO</td>\n";
        $html .= "      <td >MEDICAMENTO</td>\n";
        $html .= "      <td >PENDIENTE</td>\n";
        $html .= "    </tr>\n";
        $est = "modulo_list_claro";
        $back = "#DDDDDD";

        foreach ($informacion as $indice => $valor) {
            $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";
            $html .= "  <tr class=\"" . $est . "\"  onmouseout=mOut(this,'$back'); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";

            $html .= "      <td align=\"left\" class=\"modulo_list_claro\" width=\"15%\">" . $valor['codigo_medicamento'] . "</td>\n";
            $html .= "      <td align=\"left\" class=\"modulo_list_claro\" width=\"65%\">" . $valor['descripcion_prod'] . " </td>\n";
            $html .= "      <td align=\"left\" class=\"modulo_list_claro\" width=\"35%\">" . $valor['total'] . " </td>\n";
            $html .= "    </tr>\n";
        }
        $html .= "  </table><BR>\n";
        $html .= "  <table width=\"65%\" class=\"modulo_table_list\"   align=\"center\">";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td width=\"30%\" align=\"left\" >* FECHA:</td>\n";
        $html .= "    <td width=\"30%\" class=\"modulo_list_claro\" align=\"center\">\n";
        $html .= "      <input type=\"text\" class=\"input-text\" name=\"fecha_inicio\"  id=\"fecha_inicio\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"" . $_REQUEST['fecha_inicio'] . "\"  >\n";
        $html .= "    </td>\n";
        $html .= "    <td  width=\"35%\" class=\"modulo_list_claro\" >\n";
        $html .= "        " . ReturnOpenCalendario('FormaPp', 'fecha_inicio', '-') . "\n";
        $html .= "    </td>\n";
        $html .= "  </tr >\n";
        $html .= "                     <tr class=\"formulacion_table_list\" >\n";
        $html .= "                        <td rowspan='3' colspan='15' align=\"center\" class=\"modulo_list_claro\"> \n";
        $html .= "                          <fieldset>";
        $html .= "                           <legend>OBSERVACIONES</legend>";
        $html .= "                              <TEXTAREA id='observar' name='observar' ROWS='5' COLS=55 ></TEXTAREA>\n";
        $html .= "   <input type=\"hidden\" name=\"bandera\" value=\"1\">";
        $html .= "                        </td>\n";
        $html .= "                     </tr>\n";
        $html .= "  </table><BR>\n";

        $html .= "  <table width=\"50%\"    border=\"0\"  align=\"center\">";
        $html .= "    <tr  >\n";
        $html .= $msm;
        $html .= "    <tr>\n";
        $html .= "  </table><br>\n";
        $html .= "  <table width=\"25%\"  border=\"0\"  align=\"center\">";
        if ($bandera == 0) {
            $disable = " ";
        } else {
            $disable = " Disabled= true ";
        }

        $html .= "    <tr>\n";
        $html .= "                <td  colspan=\"2\"  align='center'>\n";
        $html .= "               <input  $disable class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"GUARDAR\" onclick=\" ValidarDtos(document.FormaPp);\"  >\n";
        $html .= "                </td>\n";
        $html .= " </form> ";

        $html .= "  <form name=\"FormaPp_\" action=\"" . $action['pendiente'] . "\" method=\"post\">";
        $html .= "                <td  colspan=\"2\"  align='center'>\n";
        $html .= " <input type=\"hidden\" name=\"evolucion\" id=\"evolucion\" value=\"" . $evolucion . "\">";
        $html .= "               <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"REALIZAR ENTREGA..\" onclick=\" ValidarDtos_Entrega(document.FormaPp);\"  >\n";
        $html .= "                </td>\n";
        $html .= "    <tr>\n";
        $html .= "  </table><br>\n";

        $html .= " </form> ";
        $html .= "</fieldset><br>\n";
        $html .= "</center>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

}

?>