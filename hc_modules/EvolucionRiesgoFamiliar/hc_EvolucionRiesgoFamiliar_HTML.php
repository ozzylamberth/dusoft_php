<?php
  /**************************************************************************************
  * $Id: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * $Revision: 1.1 $   
  * @author Manuel Ruiz Fernandez
  *
  ***************************************************************************************/
  IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");
  
  class EvolucionRiesgoFamiliar_HTML extends EvolucionRiesgoFamiliar
  {
    function EvolucionRiesgoFamiliar_HTML()
    {
      $this->EvolucionRiesgoFamiliar();
      return true;
    }
    function GetForma()
    {
      $pfj = $this->frmPrefijo;
      $evento = $_REQUEST['accion'.$pfj];
      
      switch($evento)
      {
        case 'ConsultarCalificacion':
          $action['volver'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array());
          
          $request = $_REQUEST;
          $datos_paciente = $this->datosPaciente;
          $evolucion = $this->evolucion;
          $usuario_id = UserGetUID();
          //print_r("DATOS  ".$request['rf_paciente_id']."\n");
          
          $mdl = AutoCarga::factory('EvolucionRiesgoFamiliarSQL', '', 'hc1', 'EvolucionRiesgoFamiliar');
          $datos = $mdl->ContarComponentes();
          $datos_riesgos = $mdl->ConsultarRiesgosGC();
          $datos_grupos = $mdl->ConsultarGruposRiesgos();
          $datos_rfd = $mdl->ConsultarRFDetalle($request, $datos_paciente);
          //print_r($request['rf_paciente_id']);
          $datos_crf = $mdl->ConsultarCalificacionRF($request);
          $cant_gr = $mdl->ConsultarCantGR($request);
          
          $this->salida = $this->frmIngresoCalificacion($datos, $datos_riesgos, $datos_grupos, $request, $datos_rfd, $datos_crf, $cant_gr);
        break;
        case 'EvolucionGestion':
          $request = $_REQUEST;
          $tipo = "actividad";
          //print_r("DATO ".$request['rf_paciente_d_id']."\n");
                              
          $mdl = AutoCarga::factory('EvolucionRiesgoFamiliarSQL', '', 'hc1', 'EvolucionRiesgoFamiliar');
          $datos = $mdl->ConsultarActPaciente($request);
          
          if (count($datos)==0)
            $this->salida = $this->frmEvolucionGestionRF();
          else
            $this->salida = $this->frmMensajeIngreso($action, "LAS ACTIVIDADES YA FUERON REGISTRADAS", $tipo, $datos);
        break;
        case 'RegistrarEvolucion':
          $request = $_REQUEST;
          //print_r("dato ".$request['fechaAnalisis']."\n");
                              
          $mdl = AutoCarga::factory('EvolucionRiesgoFamiliarSQL', '', 'hc1', 'EvolucionRiesgoFamiliar');
          $mdl->IngresoActividadesPaciente($request);
          
          $this->salida = $this->frmMensajeIngreso($action, "LAS ACTIVIDADES FUERON INGRESADAS");
        break;
        case 'EvaluacionCumplimiento':
          $request = $_REQUEST;
          //print_r("DATO ".$request['rf_paciente_d_id']."\n");
          
          $action['paginador'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array("accion".$pfj=>"EvaluacionCumplimiento","rf_paciente_d_id"=>$request['rf_paciente_d_id'],"rf_paciente_id"=>$request['rf_paciente_id'],"desc_comp"=>$request['desc_comp'],"desc_grup"=>$request['desc_grup'], "paciente_id"=>$_REQUEST['paciente_id'], "tipo_id_paciente"=>$_REQUEST['tipo_id_paciente'], "nivel"=>$request['nivel'], "calificacion_total"=>$request['calificacion_total'],"tema"=>$request['tema'])); 
          $mdl = AutoCarga::factory('EvolucionRiesgoFamiliarSQL', '', 'hc1', 'EvolucionRiesgoFamiliar');
          
          $datos = $mdl->ConsultarActPaciente($request);
          
          if (count($datos)>0)
          {
            $evaluaciones = $mdl->ConsultarEvalPaciente($datos, $_REQUEST['offset']);
            $this->salida = $this->frmEvaluacionCumplimiento($evaluaciones, $action, $mdl->pagina, $mdl->conteo, $datos);
          }
          else
            $this->salida = $this->frmMensajeIngreso($action, "PRIMERO DEBE REGISTRAR LAS ACTIVIDADES PARA EL PACIENTE");
          
        break;
        case 'RegistrarEvaluacion':        
          $request = $_REQUEST;
          $usuario_id = UserGetUID();
          //print_r("dato ".$request['fechaEvaluacion']."\n");
                             
          $mdl = AutoCarga::factory('EvolucionRiesgoFamiliarSQL', '', 'hc1', 'EvolucionRiesgoFamiliar');
          $datos = $mdl->ConsultarActPaciente($request);
          $mdl->IngresoEvaluacionCumplimiento($request, $usuario_id, $datos);      
          $this->salida = $this->frmMensajeIngreso($action, "LA EVALUACION FUE INGRESADA");
        break;
        default:
          $request = $_REQUEST;
          $datos_paciente = $this->datosPaciente;
          $evolucion = $this->evolucion;
          $usuario_id = UserGetUID();
          $action['paginador'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array());
          $mdl = AutoCarga::factory('EvolucionRiesgoFamiliarSQL', '', 'hc1', 'EvolucionRiesgoFamiliar');
          $datos_rf = $mdl->ConsultarRFPaciente($datos_paciente, $_REQUEST['offset']);
          $action['volver'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array());
          
          $this->salida = $this->frmConsultaRiesgoPaciente($datos_rf, $action, $mdl->pagina, $mdl->conteo);
          break;  
      }
      return $this->salida;
    }
    
    function frmConsultaRiesgoPaciente($datos_rf, $action, $pagina, $conteo)
    {
      $pfj = $this->frmPrefijo;
      
      $action['consultar_calificacion'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'ConsultarCalificacion'));    
      
      $html  = ThemeAbrirTablaSubModulo('CONSULTAR RIESGO PACIENTE'); 
     
      $html .= "<table align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">\n";
      $html .= "  <form id=\"formConsultaRiesgoPaciente\" name=\"formConsultaRiesgoPaciente\" action=\"#\" method=\"post\">";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">CONSULTA RIESGO FAMILIAR PACIENTE\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"hc_table_submodulo_list_title\">\n";
      $html .= "      <td align=\"center\" width=\"15%\">FECHA CALIFICACION\n";
      $html .= "      </td>\n"; 
      $html .= "      <td align=\"center\" width=\"15%\">FECHA REGISTRO\n";
      $html .= "      </td>\n";      
      $html .= "      <td align=\"center\" width=\"40%\">RESPONSABLE\n";
      $html .= "      </td>\n"; 
      $html .= "      <td align=\"center\" width=\"10%\">TOTAL\n";
      $html .= "      </td>\n"; 
      $html .= "      <td align=\"center\" width=\"15%\">NIVEL\n";
      $html .= "      </td>\n"; 
      $html .= "      <td align=\"center\" width=\"5%\">C\n";
      $html .= "      </td>\n"; 
      $html .= "    </tr>\n";
      $path = GetThemePath();
      $est = "modulo_list_claro";
      foreach ($datos_rf as $indice => $valor)
      {      
        ($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
        if ($valor['fecha_calificacion'])
        {
          $fc = explode("-",$valor['fecha_calificacion']);
          if (sizeof($fc)==3) $fCal=$fc[2]."/".$fc[1]."/".$fc[0];
        }
        
        if ($valor['fecha_registro'])
        {
          $fr = explode("-",$valor['fecha_registro']);
          if (sizeof($fr)==3) $fReg=$fr[2]."/".$fr[1]."/".$fr[0];
        }
        $html .= "    <tr class=\"".$est."\">\n";
        $html .= "      <td align=\"center\" width=\"15%\">".$fCal."\n";
        $html .= "      </td>\n"; 
        $html .= "      <td align=\"center\" width=\"15%\">".$fReg."\n";
        $html .= "      </td>\n";      
        $html .= "      <td align=\"center\" width=\"40%\">".$valor['responsable']."\n";
        $html .= "      </td>\n"; 
        $html .= "      <td align=\"center\" width=\"10%\">".$valor['calificacion_total']."\n";
        $html .= "      </td>\n"; 
        if($valor['calificacion_total']==0)
          $nivel = "SIN RIESGO";
        else if ($valor['calificacion_total']>0 && $valor['calificacion_total']<15)
                $nivel = "RIESGO BAJO";
             else if ($valor['calificacion_total']>14 && $valor['calificacion_total']<35) 
                     $nivel = "RIESGO MEDIO";
                  else if ($valor['calificacion_total']>34 && $valor['calificacion_total']<73)
                          $nivel = "RIESGO ALTO";
                                               
        $html .= "      <td align=\"center\" width=\"15%\">".$nivel."\n";
        $html .= "      </td>\n"; 
        $html .= "      <td align=\"center\" width=\"5%\">\n";
        $html .= "        <a href=\"".$action['consultar_calificacion'].URLRequest(array("rf_paciente_id"=>$valor['rf_paciente_id'], "paciente_id"=>$valor['paciente_id'],"tema"=>"consulta", "nivel"=>$nivel))."\" align=\"center\" title=\"CONSULTAR DETALLE DE CALIFICACION\">\n";
        $html .= "          <sub><img src=\"".$path."/images/flecha.png\" border=\"0\"></sub>\n";
        $html .= "        </a>\n";
        $html .= "      </td>\n"; 
        $html .= "    </tr>\n";
      }
      $html .= "  </form>";
      $html .= "</table>\n";
      $html .= "<br>\n";
      
      $chtml = AutoCarga::factory('ClaseHTML');
      $html .= "    ".$chtml->ObtenerPaginado($conteo, $pagina, $action['paginador'], 20);
      
      $html .= ThemeCerrarTablaSubModulo();
      
      return $html;
    }
    
    function frmIngresoCalificacion($datos, $datos_riesgos, $datos_grupos, $request, $datos_rfd, $datos_crf, $cant_gr)
    {
      $pfj=$this->frmPrefijo;
      
      $request = $_REQUEST;
      $html  = ThemeAbrirTablaSubModulo('CONSULTA RIESGO FAMILIAR'); 
      $action['volver'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>''));
      $action['ingresar_evolucion'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'EvolucionGestion',"rf_paciente_id"=>$_REQUEST['rf_paciente_id'], "paciente_id"=>$_REQUEST['paciente_id'], "tipo_id_paciente"=>$_REQUEST['tipo_id_paciente'], "nivel"=>$request['nivel'], "calificacion_total"=>$request['calificacion_total'],"tema"=>$request['tema'],    "rf_paciente_d_id"=>$_REQUEST['rf_paciente_d_id']));
      
      $action['evaluar_cumplimiento'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'EvaluacionCumplimiento',"rf_paciente_id"=>$_REQUEST['rf_paciente_id'], "paciente_id"=>$_REQUEST['paciente_id'], "tipo_id_paciente"=>$_REQUEST['tipo_id_paciente'], "nivel"=>$request['nivel'], "calificacion_total"=>$request['calificacion_total'],"tema"=>$request['tema'], "rf_paciente_d_id"=>$_REQUEST['rf_paciente_d_id']));
            
      $html .= "<script>\n";
      $html .= "  function SumarCalificacion()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formIngresoRiesgo;\n";
      $html .= "    total = 0;\n";
      $html .= "    for(i=0; i<frm.length; i++)\n";
      $html .= "    {\n";
      $html .= "      switch(frm[i].type)\n";
      $html .= "      {\n";
      $html .= "        case 'select-one': \n";
      $html .= "          numero = frm[i].value*1;\n";
      $html .= "          if( numero >= 0) \n";
      $html .= "            total += numero; \n";
      $html .= "        break;\n";
      $html .= "      }\n";
      $html .= "    }\n";      
      $html .= "    if (total==0)\n";
      $html .= "      nivel='SIN RIESGO';\n";
      $html .= "    else if (total>0 && total<15)\n";
      $html .= "            nivel='RIESGO BAJO';\n";
      $html .= "         else if (total>14 && total<35)\n";
      $html .= "                nivel = 'RIESGO MEDIO';\n";
      $html .= "              else if (total>34 && total<73)\n";
      $html .= "                      nivel = 'RIESGO ALTO';\n";
      $html .= "    document.getElementById('total').innerHTML = total;\n";
      $html .= "    document.getElementById('nivel').innerHTML = nivel;\n";
      $html .= "    document.getElementById('total_oculto').value = total;\n";
      $html .= "    return;";
      $html .= "  }\n";      
      $html .= "</script>\n";
      //URLRequest(array("codigos"=>$codigos)).    
      $html .= "<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
      $html .= "  <form id=\"formIngresoRiesgo\" name=\"formIngresoRiesgo\" action=\"#\" method=\"post\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\">CALIFICACION DEL RIESGO FAMILIAR\n";
      $html .= "      </td>\n";
      $html .= "    </tr>";
      $html .= "    <tr class=\"modulo_list_oscuro\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\" rowspan=\"1\">CALIFICACION DEL RIESGO-RANGO POR COMPONENTE\n";
      $html .= "      </td>\n";
      $html .= "    </tr>";
      $html .= "    <tr class=\"modulo_list_oscuro\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\" rowspan=\"1\">0=SIN RIESGO  --   1=RIESGO MUY BAJO  --  2=RIESGO BAJO  --  3=RIESGO MODERADO  --  4=RIESGO ALTO\n";
      $html .= "      </td>\n";
      $html .= "    </tr>";
      $html .= "    <tr >\n";
      $html .= "      <td align=\"center\" colspan=\"1\" rowspan=\"1\" class=\"formulacion_table_list\">RESPONSABLE:\n";
      $html .= "      </td>\n";      
      $html .=        "<td align=\"left\" colspan=\"5\" rowspan=\"1\" class=\"modulo_table_list\"> ".$datos_crf[0]['responsable']."\n";
      $html .=        "</td>\n";              
      $html .= "    </tr>";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td align=\"center\" colspan=\"3\" rowspan=\"2\">GRUPOS DE RIESGO Y COMPONENTES\n";
      $html .= "      </td>\n";
      $html .= "      <td align=\"center\" colspan=\"3\"rowspan=\"1\" width=\"10%\">FECHA DE CALIFICACION\n";
      $html .= "      </td>\n";
      $html .= "    </tr\n>";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td align=\"center\" colspan=\"3\" rowspan=\"1\" width=\"\">\n";
      
      if($datos_crf[0]['fecha_calificacion'])
      {
        $f = explode("-",$datos_crf[0]['fecha_calificacion']);
        if(sizeof($f) == 3) $fCalificacion = $f[2]."/".$f[1]."/".$f[0];
      }
      
      $html .= "".$fCalificacion.""; 
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $i = 0;
      $path = GetThemePath();
      $sw = 1;
      $rows = 0;
      $row = 0;
      foreach ($datos_crf as $indice => $valor)
      {
        $next = $indice+1;
        
        if ($sw == 1)
        {
          $rows = $cant_gr[$row]['cant'];
          $filas = $rows + 1;
          $html .= "    <tr class=\"modulo_table_title\">\n";            
          $html .= "      <td align=\"center\" colspan=\"1\" rowspan=\"".$filas."\" width=\"30%\">".$valor['grupo_riesgo_id']." -- ".$valor['descgrup']."\n";
          $html .= "      </td>\n";               
          $html .= "    </tr>\n";
          $row = $row + 1;
        }  
        
        if ($datos_crf[$indice]['grupo_riesgo_id'] != $datos_crf[$next]['grupo_riesgo_id'])
        {
          $sw = 1;
        }else
        {
          $sw = 0;
        }      
        
        $html .= "    <tr>\n";
        $html .= "      <td align=\"center\" colspan=\"1\" rowspan=\"1\" width=\"5%\" class=\"hc_table_submodulo_list_title\">".$valor['comp_riesgo_id']."\n";
        $html .= "      </td>\n";
        $html .= "      <td align=\"left\" colspan=\"1\" rowspan=\"1\" width=\"45%\" class=\"modulo_list_oscuro\">".$valor['desccomp']."\n";
        $html .= "      </td>\n";
        
        if ($valor['calificacion'] > 0)
        {
          $html .= "      <td align=\"center\" colspan=\"1\" rowspan=\"1\" width=\"5%\" class=\"modulo_table_list\">".$valor['calificacion']."\n";
          $html .= "      </td>\n";
          $html .= "      <td align=\"center\" colspan=\"1\" rowspan=\"1\" width=\"5%\" class=\"modulo_table_list\">\n";
          $html .= "        <a href=\"".$action['ingresar_evolucion'].URLRequest(array('rf_paciente_d_id'=>$valor['rf_paciente_d_id'], 'rf_paciente_id'=>$valor['rf_paciente_id'], 'desc_comp'=>$valor['desccomp'],
          'desc_grup'=>$valor['descgrup'], 'fecha_calificacion'=>$fCalificacion))."\" title=\"PROGRAMAR ACTIVIDADES\"><sub><img src=\"".$path."/images/flecha.png\" border=\"0\"></sub> </a>\n";
          $html .= "      </td>\n";
          $html .= "      <td align=\"center\" colspan=\"1\" rowspan=\"1\" width=\"5%\" class=\"modulo_table_list\">\n";
          $html .= "        <a href=\"".$action['evaluar_cumplimiento'].URLRequest(array('rf_paciente_d_id'=>$valor['rf_paciente_d_id'], 'rf_paciente_id'=>$valor['rf_paciente_id'], 'desc_comp'=>$valor['desccomp'],
          'desc_grup'=>$valor['descgrup']))."\" title=\"EVALUAR CUMPLIMIENTO DE ACTIVIDADES\"><sub><img src=\"".$path."/images/flecha_der.gif\" border=\"0\"></sub> </a>\n";
          $html .= "      </td>\n";
          
        }else
            {
              $html .= "      <td align=\"center\" colspan=\"1\" rowspan=\"1\" width=\"5%\" class=\"modulo_table_list\"> ".$valor['calificacion']."\n";                               
              $html .= "      </td>\n";
              $html .= "      <td align=\"center\" colspan=\"1\" rowspan=\"1\" width=\"5%\" class=\"modulo_table_list\">\n";
              $html .= "          <sub><img src=\"".$path."/images/no_autorizado.png\"></sub>";
              $html .= "      </td>\n";
              $html .= "      <td align=\"center\" colspan=\"1\" rowspan=\"1\" width=\"5%\" class=\"modulo_table_list\">\n";
              $html .= "          <sub><img src=\"".$path."/images/no_autorizado.png\"></sub>";
              $html .= "      </td>\n";
            }
        $html .= "    </tr>\n";
      }
      
      if ($request['tema']=="consulta")
      {
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\" colspan=\"3\" class=\"modulo_list_claro\"> CALIFICACION DEL RIESGO - RANGO TOTAL\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\" colspan=\"3\" class=\"hc_table_submodulo_list_title\">TOTAL: ".$datos_crf[0]['calificacion_total']."\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\" colspan=\"3\" class=\"modulo_list_claro\"> 0=SIN RIESGO   --   1-14=RIESGO BAJO   --   15-34=RIESGO MEDIO   --   35-72=RIESGO ALTO \n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\" colspan=\"3\" class=\"hc_table_submodulo_list_title\">NIVEL: ".$request['nivel']."\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\" colspan=\"6\">\n";
      $html .= "       <center> <div id=\"error\" class=\"label_error\"></div> </center>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"6\">\n";
      $html .= "      <table align=\"center\">\n";      
      $html .= "        <tr class=\"modulo_list_claro\">\n";            
      $html .= "  </form>";
      $html .= "          <form id=\"formVolver\" name=\"formVolver\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "            <td align=\"center\">\n";
      $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"Cancelar\">";
      $html .= "            </td>\n";
      $html .= "          </form>\n";     
      $html .= "        </tr>";
      $html .= "      </table>\n";   
      $html .= "    </td>\n";            
      $html .= "  </tr>\n";      
                 
      $html .= "</table>";
      
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    function frmEvolucionGestionRF()
    {
      $pfj = $this->frmPrefijo;
      
      $action['volver'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'ConsultarCalificacion',"rf_paciente_id"=>$_REQUEST['rf_paciente_id'], "paciente_id"=>$_REQUEST['paciente_id'], "nivel"=>$_REQUEST['nivel'], "calificacion_total"=>$_REQUEST['calificacion_total'],"tema"=>$_REQUEST['tema']));
      
      $action['registro_evolucion'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'RegistrarEvolucion', "rf_paciente_id"=>$_REQUEST['rf_paciente_id'], "rf_paciente_d_id"=>$_REQUEST['rf_paciente_d_id'], "paciente_id"=>$_REQUEST['paciente_id'], "nivel"=>$_REQUEST['nivel'], "calificacion_total"=>$_REQUEST['calificacion_total'], "tema"=>$_REQUEST['tema']));
      
      $request = $_REQUEST;
      
      $html  = ThemeAbrirTablaSubModulo('EVOLUCION DE LA GESTION DEL RIESGO FAMILIAR');
      $html .= "<form name=\"formEvolucionGestionRF\" id=\"formEvolucionGestionRF\" action=\"".$action['registro_evolucion']."\" method=\"post\">\n";
      $html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"65%\" border=\"0\">\n";
      $html .= "  <tr class=\"modulo_table_title\">\n";
      $html .= "    <td align=\"center\" colspan=\"3\">EVOLUCION DEL RIESGO FAMILIAR\n";
      $html .= "    </td>\n";
      $html .= "  </tr\n>";
      $cut = new ClaseUtil();
      $html .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
      $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"3\" width=\"20%\"> RIESGO\n";
      $html .= "    </td>\n";
      $html .= "  </tr\n>";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"1\">FECHA ANALISIS\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"1\">DESCRIPCION RIESGO\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"1\" width=\"40%\">\n";
      $html .= $cut->AcceptDate("/");
      $html .= "      <input type=\"text\" name=\"fechaAnalisis\" class=\"input-text\" size=\"15%\" value=\"\" onkeyPress=\"return acceptDate(event)\">\n";
      $html .= "      ".ReturnOpenCalendario('formEvolucionGestionRF','fechaAnalisis','/')."\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"1\" width=\"60%\">".$request['desc_grup']." - ".$request['desc_comp']."\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
      $html .= "    <td align=\"center\" colspan=\"3\" rowspan=\"1\">ACTIVIDADES PROGRAMADAS\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td align=\"center\" rowspan=\"1\" colspan=\"3\">COMPROMISO DE LA FAMILIA\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_table_list\">\n";
      $html .= "    <td align=\"center\" colspan=\"3\" rowspan=\"1\">\n";
      $html .= "      <textarea style=\"width:100%\" rows=\"4\" name=\"compromisoFamilia\"></textarea>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"formulacion_table_list\" >\n";
      $html .= "    <td align=\"center\" rowspan=\"1\" colspan=\"3\">COMPROMISO DEL EQUIPO DE SALUD\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_table_list\">\n";
      $html .= "    <td align=\"center\" colspan=\"3\" rowspan=\"1\">\n";
      $html .= "      <textarea style=\"width:100%\" rows=\"4\" name=\"compromisoEquipo\"></textarea>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"3\">\n";
      $html .= "      <center><div id=\"error\" class=\"label_error\"></div></center>";
      $html .= "    <td>\n";
      $html .= "  </tr>\n";
      $html .= "  <input type=\"hidden\" name=\"fCalificacion\" id=\"fCalificacion\" value=\"".$request['fecha_calificacion']."\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"3\">\n";
      $html .= "      <table align=\"center\" border=\"0\">\n";
      $html .= "        <tr>\n";
      $html .= "          <td align=\"center\">\n";
      $html .= "            <input class=\"input-submit\" type=\"button\" name=\"Ingresar\" value=\"Ingresar\" onclick=\"EvaluarDatos(document.formEvolucionGestionRF)\">";
      $html .= "          </td>\n";     
      $html .= "</form>\n";
      $html .= $cut->IsDate();
      $html .= "<script>\n";
      $html .= "  function EvaluarDatos()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formEvolucionGestionRF;";
      $html .= "    for(i=0; i<frm.length; i++)\n";
      $html .= "    {\n";
      $html .= "      switch(frm[i].type)\n";
      $html .= "      {\n";
      $html .= "        case 'text':\n";
      $html .= "          val = frm[i].value;\n";
      $html .= "          if(val==\"\")\n";
      $html .= "          {\n";
      $html .= "            document.getElementById('error').innerHTML = 'Debe ingresar todos los campos';\n";
      $html .= "            return;\n";
      $html .= "          }\n";
      $html .= "        case 'textarea':\n";
      $html .= "          val = frm[i].value;\n";
      $html .= "          if(val==\"\")\n";
      $html .= "          {\n";
      $html .= "            document.getElementById('error').innerHTML = 'Debe ingresar los compromisos';\n";
      $html .= "            return;\n";
      $html .= "          }\n";
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "    if(!IsDate(frm.fechaAnalisis.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha posee un formato invalido';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    fa = frm.fechaAnalisis.value;\n";
      $html .= "    fc = frm.fCalificacion.value;\n";
      $html .= "    var fecha_a = fa.split('/');\n";
      $html .= "    var fecha_c = fc.split('/');\n";
      $html .= "    ffa = new Date(fecha_a[2]+'/'+fecha_a[1]+'/'+fecha_a[0]);\n";
      $html .= "    ffc = new Date(fecha_c[2]+'/'+fecha_c[1]+'/'+fecha_c[0]);\n";
      $html .= "    if(ffa < ffc)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de analisis debe ser mayor o igual a la fecha de calificacion'\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    frm.submit();";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "          <form id=\"formVolver\" name=\"formVolver\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "            <td align=\"center\">\n";
      $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"Cancelar\">";
      $html .= "            </td>\n";
      $html .= "          </form>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    function frmEvaluacionCumplimiento($evaluaciones, $action, $pagina, $conteo, $datos)
    {
      $pfj = $this->frmPrefijo;
      
      $request = $_REQUEST;
      
      $action['volver'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'ConsultarCalificacion',"rf_paciente_id"=>$_REQUEST['rf_paciente_id'], "paciente_id"=>$_REQUEST['paciente_id'], "nivel"=>$_REQUEST['nivel'], "calificacion_total"=>$_REQUEST['calificacion_total'], "tema"=>$_REQUEST['tema'], "rf_paciente_d_id"=>$_REQUEST['rf_paciente_d_id']));
      
      $action['registro_evaluacion'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'RegistrarEvaluacion', "rf_paciente_d_id"=>$_REQUEST['rf_paciente_d_id'], "rf_paciente_id"=>$_REQUEST['rf_paciente_id'], "paciente_id"=>$_REQUEST['paciente_id'], "nivel"=>$_REQUEST['nivel'], "calificacion_total"=>$_REQUEST['calificacion_total'], "tema"=>$_REQUEST['tema']));
      
      $html  = ThemeAbrirTablaSubModulo('EVOLUCION DE LA GESTION DEL RIESGO FAMILIAR');
      $html .= "<form id=\"formEvalCumplimiento\" name=\"formEvalCumplimiento\" action=\"".$action['registro_evaluacion']."\" method=\"post\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" border=\"0\" width=\"67%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"4\">EVALUACION DEL CUMPLIMIENTO DE LOS COMPROMISOS\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">DESCRIPCION RIESGO\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['desc_grup']." - ".$request['desc_comp']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" colspan=\"1\" align=\"left\" width=\"24%\">FECHA DE EVALUACION\n";
      $html .= "      </td>\n";
      $cut = new ClaseUtil();
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"1\" align=\"left\">\n";
      $html .= $cut->AcceptDate("/");
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"fechaEvaluacion\" value=\"\" onkeyPress=\"return acceptDate(event)\" size=\"10%\">";
      $html .= "".ReturnOpenCalendario('formEvalCumplimiento', 'fechaEvaluacion', '/')."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" border=\"1\" width=\"67%\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td align=\"center\" colspan=\"2\">CUMPLIMIENTO\n";
      $html .= "      </td>\n";
      $html .= "      <td align=\"center\" colspan=\"1\">CAUSAS DE INCUMPLIMIENTO Y OBSERVACIONES\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"1\" class=\"modulo_list_oscuro\" rowspan=\"1\">Si Cumple\n";
      $html .= "      </td>\n";
      $html .= "      <td align=\"center\" colspan=\"1\" class=\"modulo_list_claro\" rowspan=\"1\">\n";
      $html .= "        <input type=\"radio\" name=\"cumplimiento\" value=\"S\">";
      $html .= "      </td>\n";      
      $html .= "      <td align=\"center\" colspan=\"1\" class=\"modulo_list_claro\" rowspan=\"3\">\n";
      $html .= "        <textarea style=\"width:100%\" rows=\"3\" name=\"causaIncumplimiento\"></textarea>";
      $html .= "      </td>\n";      
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"1\" class=\"modulo_list_oscuro\">No Cumple\n";
      $html .= "      </td>\n";
      $html .= "      <td align=\"center\" colspan=\"1\" class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"radio\" name=\"cumplimiento\" value=\"N\">";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"1\" class=\"modulo_list_oscuro\">Parcial\n";
      $html .= "      </td>\n";
      $html .= "      <td align=\"center\" colspan=\"1\" class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"radio\" name=\"cumplimiento\" value=\"P\">";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"3\">\n";
      $html .= "        <center><div id=\"error\" class=\"label_error\"></div></center>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      //print_r('fecha analisis '.$datos[0]['fecha_analisis']."\n");
      
      $cant = count($evaluaciones); 
            
      $fa = explode("-",$datos[0]['fecha_analisis']);
      if (sizeof($fa)==3) $fAna=$fa[2]."/".$fa[1]."/".$fa[0];
      $html .= "<input type=\"hidden\" name=\"fechaAnalisis\" id=\"fechaAnalisis\" value=\"".$fAna."\">";
      
      if ($cant>0)
      {
        $fe = explode("-",$evaluaciones[$cant-1]['fecha_evaluacion']);
        if (sizeof($fe)==3) $fEval=$fe[2]."/".$fe[1]."/".$fe[0];
        $html .= "<input type=\"hidden\" name=\"total_oculto\" id=\"total_oculto\" value=\"".$fEval."\">\n";
      }else
      {
        if (sizeof($fe)==3) $fEval=$fe[2]."/".$fe[1]."/".$fe[0];
        $html .= "<input type=\"hidden\" name=\"total_oculto\" id=\"total_oculto\" value=\"\">\n";
      }
      
      $html .= "    <tr>\n";      
      $html .= "      <td align=\"center\" colspan=\"3\" >\n";
      $html .= "        <table align=\"center\" width=\"40%\">\n";
      $html .= "          <tr align=\"center\">\n";
      $html .= "            <td align=\"center\">\n";
      $html .= "              <input type=\"button\" class=\"input-submit\" name=\"Ingresar\" value=\"Ingresar\" onclick=\"EvaluarDatos(document.formEvalCumplimiento)\">\n";
      $html .= "            </td>\n";
      $html .= "</form>\n";
      $html .= $cut->IsDate();
      $html .= "<script>\n";
      $html .= "  function EvaluarDatos()\n";
      $html .= "  {\n";
      $html .= "    cont=0;";
      $html .= "    frm = document.formEvalCumplimiento;\n";
      $html .= "    for(i=0; i<frm.length; i++)\n";
      $html .= "    {\n";
      $html .= "      switch(frm[i].type)\n";
      $html .= "      {\n";
      $html .= "        case 'text':\n";
      $html .= "          val = frm[i].value;\n";
      $html .= "          if(val==\"\")\n";
      $html .= "          {\n";
      $html .= "            document.getElementById('error').innerHTML = 'Debe ingresar todos los campos';\n";
      $html .= "            return;\n";
      $html .= "          }\n";
      $html .= "        case 'radio':\n";
      $html .= "          if(!frm[i].checked && frm[i].type=='radio')\n";
      $html .= "          {\n";
      $html .= "            cont=cont+1;\n";
      $html .= "          }\n";
      $html .= "        case 'textarea':\n";
      $html .= "          val = frm[i].value;\n";
      $html .= "          if(val==\"\")\n";
      $html .= "          {\n";
      $html .= "            document.getElementById('error').innerHTML = 'Debe ingresar todos los campos';\n";
      $html .= "            return;\n";
      $html .= "          }\n";
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "    if(cont==3)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar una opcion de cumplimiento'\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsDate(frm.fechaEvaluacion.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha posee un formato invalido';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    fe = frm.fechaEvaluacion.value;\n";
      $html .= "    fc = frm.total_oculto.value;\n";
      $html .= "    var fecha_e = fe.split('/');\n";
      $html .= "    var fecha_c = fc.split('/');\n";
      $html .= "    ffe = new Date(fecha_e[2]+'/'+fecha_e[1]+'/'+fecha_e[0]);\n";
      $html .= "    ffc = new Date(fecha_c[2]+'/'+fecha_c[1]+'/'+fecha_c[0]);\n";
      $html .= "    if(frm.total_oculto.value!=\"\" && ffe<ffc)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de evaluacion debe ser mayor o igual a la fecha de la ultima evaluacion registrada'\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    fa = frm.fechaAnalisis.value;\n";
      $html .= "    var fecha_a = fa.split('/');\n";
      $html .= "    ffa = new Date(fecha_a[2]+'/'+fecha_a[1]+'/'+fecha_a[0]);\n";
      $html .= "    if(ffe<ffa)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de evaluacion debe ser mayor o igual a la fecha de registro de las actividades';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "            <form id=\"formVolver\" name=\"formVolver\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "            <td align=\"center\">\n";
      $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"Cancelar\">";
      $html .= "            </td>\n";
      $html .= "            </form>\n";
      $html .= "          </tr>\n";
      $html .= "        </table>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      if(count($evaluaciones)>0)
      {
        $html .= "<br>";
        $html .= "<br>";
        $html .= "<table border=\"0\" width=\"67%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"modulo_table_title\">\n";
        $html .= "    <td colspan=\"3\" align=\"center\">CONSULTA EVALUACION DEL CUMPLIMIENTO DE LOS COMPROMISOS\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
        $html .= "    <td align=\"center\" width=\"10%\">FECHA EVALUACION\n";
        $html .= "    </td>";
        $html .= "    <td align=\"center\" width=\"5%\">CUMPLIMIENTO\n";
        $html .= "    </td>";
        $html .= "    <td align=\"center\">CAUSAS DE INCUMPLIMIENTO Y OBSERVACIONES\n";
        $html .= "    </td>";
        $html .= "  </tr>";
        $est = "modulo_list_claro";
        foreach($evaluaciones as $indice => $valor)
        {
          ($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
          if ($valor['cumplimiento']=="S")
            $cump="SI CUMPLE";
          else if ($valor['cumplimiento']=="N")
                  $cump="NO CUMPLE";
               else if ($valor['cumplimiento']=="P")
                       $cump="PARCIAL";
          if($valor['fecha_evaluacion'])
          {
            $f = explode("-",$valor['fecha_evaluacion']);
            if(sizeof($f)==3) $fEvaluacion=$f[2]."/".$f[1]."/".$f[0];
          }
          $html .= "  <tr class=\"".$est."\">\n";
          $html .= "    <td align=\"center\">".$fEvaluacion."\n";
          $html .= "    </td>\n";
          $html .= "    <td align=\"center\">".$cump."\n";
          $html .= "    </td>\n";
          $html .= "    <td>".$valor['causa_observacion']."\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
        }        
        $html .= "</table>\n";
        $html .= "<br>\n";
        $chtml = AutoCarga::factory('ClaseHTML');
        $html .= "    ".$chtml->ObtenerPaginado($conteo, $pagina, $action['paginador'], 20);
      }
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    function frmMensajeIngreso($action,$mensaje, $tipo, $datos)
    {
      $pfj = $this->frmPrefijo;  
      $request = $_REQUEST;
      
      $action['volver'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'ConsultarCalificacion',"rf_paciente_id"=>$_REQUEST['rf_paciente_id'], "paciente_id"=>$_REQUEST['paciente_id'], "nivel"=>$_REQUEST['nivel'], "calificacion_total"=>$_REQUEST['calificacion_total'], "tema"=>$_REQUEST['tema'], "rf_paciente_d_id"=>$_REQUEST['rf_paciente_d_id'], "rf_paciente_id"=>$_REQUEST['rf_paciente_id']));
      
      $html  = ThemeAbrirTabla('MENSAJE');
      if ($tipo == "actividad")
      {
        $html .= "<table border=\"0\" width=\"80%\" class=\"modulo_table_list\" align=\"center\">\n";
        $html .= "  <tr class=\"modulo_table_title\">\n";
        $html .= "    <td colspan=\"3\" align=\"center\">CONSULTA ACTIVIDADES PROGRAMADAS\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"hc_table_submodulo_list_title\">";
        $html .= "    <td align=\"center\" width=\"15%\">FECHA ANALISIS\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">COMPROMISO DE LA FAMILIA\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">COMPROMISO DEL EQUIPO\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        if($datos[0]['fecha_analisis'])
        {
          $f = explode("-",$datos[0]['fecha_analisis']);
          if(sizeof($f)==3) $fAnalisis=$f[2]."/".$f[1]."/".$f[0];
        }
        $html .= "  <tr class=\"modulo_list_claro\">";
        $html .= "    <td align=\"center\" width=\"15%\">".$fAnalisis."\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"left\">".$datos[0]['compromiso_familia']."\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"left\">".$datos[0]['compromiso_equipo']."\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<br>\n";
      }
      
      $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <table width=\"100%\" class=\"modulo_table_list\">\n";
      $html .= "        <tr class=\"normal_10AN\">\n";
      $html .= "          <td align=\"center\">\n".$mensaje."</td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\"><br>\n";
      $html .= "      <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
      $html .= "      </form>";
      $html .= "    </td>";
      $html .= "  </tr>";
      $html .= "</table>";
      
      $html .= ThemeCerrarTabla();      
      return $html;
    }
  }
?> 
