<?php
  /**************************************************************************************
  * $Id: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * $Revision: 1.2 $   
  * @author Manuel Ruiz Fernandez
  *
  ***************************************************************************************/
  IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");
  
  class RiesgoFamiliar_HTML extends RiesgoFamiliar
  {
    function RiesgoFamiliar_HTML()
    {
      $this->RiesgoFamiliar();
      return true;
    }
    function GetForma()
    {
      $pfj = $this->frmPrefijo;
      $evento = $_REQUEST['accion'.$pfj];
      switch($evento)
      {
        case 'IngresarCalificacion':
          $request = $_REQUEST;
          $datos_paciente = $this->datosPaciente;
          $evolucion = $this->evolucion;
          $usuario_id = UserGetUID();
             
          $mdl = AutoCarga::factory('RiesgoFamiliarSQL', '', 'hc1', 'RiesgoFamiliar');
          $datos = $mdl->ContarComponentes();
          $datos_riesgos = $mdl->ConsultarRiesgosGC();
          $datos_grupos = $mdl->ConsultarGruposRiesgos();
          $datos_rfd = $mdl->ConsultarRFDetalle($request);
          
          if ($request['tema']=="consulta")
          {
            $datos_crf = $mdl->ConsultarCalificacionRF($request);
            $cant_gr = $mdl->ConsultarCantGR($request);
          }  
          $this->salida = $this->frmIngresoCalificacion($datos, $datos_riesgos, $datos_grupos, $request, $datos_rfd, $datos_crf, $cant_gr);
          break;
        case 'IngresarRFPaciente':
          $request = $_REQUEST;
          $datos_paciente = $this->datosPaciente;
          $evolucion = $this->evolucion;
          $usuario_id = UserGetUID();
          
          $mdl = AutoCarga::factory('RiesgoFamiliarSQL', '', 'hc1', 'RiesgoFamiliar'); 
          $mdl->IngresarRFPaciente($request, $datos_paciente, $usuario_id, $evolucion);
          $mensaje = "LA CALIFICACION DE LOS RIESGOS FAMILIARES FUE ALMACENADA";
          $this->salida = $this->frmMensajeIngreso($action, $mensaje);
          break;
        default:
          $request = $_REQUEST;
          $datos_paciente = $this->datosPaciente;
          $evolucion = $this->evolucion;
          $usuario_id = UserGetUID();
          $action['paginador'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array());
          
          $mdl = AutoCarga::factory('RiesgoFamiliarSQL', '', 'hc1', 'RiesgoFamiliar');
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
      $action['ingresar_calificacion'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'IngresarCalificacion'));
      $action['consultar_calificacion'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'IngresarCalificacion'));    
      
      $html  = ThemeAbrirTablaSubModulo('CONSULTAR RIESGO PACIENTE'); 
      $html .= "<table align=\"center\" border=\"0\" width=\"90%\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['ingresar_calificacion'].URLRequest(array("tema"=>"registro"))."\" align=\"center\">REGISTRAR NUEVA CALIFICACION\n";
      $html .= "      </a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n"; 
      
      $html .= "<br>";
      $html .= "<br>";

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
        $html .= "    <tr class=\"".$est."\">\n";
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
        $html .= "        <a href=\"".$action['consultar_calificacion'].URLRequest(array("rf_paciente_id"=>$valor['rf_paciente_id'], "paciente_id"=>$valor['paciente_id'],"tema"=>"consulta", "nivel"=>$nivel))."\" align=\"center\" title=\"CONSULTAR DETALLE DE RIESGOS\">\n";
        $html .= "          <sub><img src=\"".$path."/images/flecha.png\" border=\"0\" ></sub>\n";
        $html .= "        </a>\n";
        $html .= "      </td>\n"; 
        $html .= "    </tr>\n";
      }
      $html .= "  </form>";
      $html .= "</table>\n";
      $html .= "<br>\n";
      
      $chtml = AutoCarga::factory('ClaseHTML');
      $html .= "    ".$chtml->ObtenerPaginado($conteo, $pagina, $action['paginador'], 20);
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    function frmIngresoCalificacion($datos, $datos_riesgos, $datos_grupos, $request, $datos_rfd, $datos_crf, $cant_gr)
    {
      $pfj=$this->frmPrefijo;
      
      if ($request['tema']=="registro")
        $html  = ThemeAbrirTablaSubModulo('INGRESO RIESGO FAMILIAR'); 
      else
        $html  = ThemeAbrirTablaSubModulo('CONSULTA RIESGO FAMILIAR'); 
      $action['volver'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>''));
      $action['registroRF'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'IngresarRFPaciente'));
      
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
      $html .= "<table align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">\n";
      $html .= "  <form id=\"formIngresoRiesgo\" name=\"formIngresoRiesgo\" action=\"".$action['registroRF']."\" method=\"post\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"4\">CALIFICACION DEL RIESGO FAMILIAR\n";
      $html .= "      </td>\n";
      $html .= "    </tr>";
      $html .= "    <tr class=\"modulo_list_oscuro\">\n";
      $html .= "      <td align=\"center\" colspan=\"4\" rowspan=\"1\">CALIFICACION DEL RIESGO-RANGO POR COMPONENTE\n";
      $html .= "      </td>\n";
      $html .= "    </tr>";
      $html .= "    <tr class=\"modulo_list_oscuro\">\n";
      $html .= "      <td align=\"center\" colspan=\"4\" rowspan=\"1\">0=SIN RIESGO  --   1=RIESGO MUY BAJO  --  2=RIESGO BAJO  --  3=RIESGO MODERADO  --  4=RIESGO ALTO\n";
      $html .= "      </td>\n";
      $html .= "    </tr>";
      $html .= "    <tr >\n";
      $html .= "      <td align=\"center\" colspan=\"2\" rowspan=\"1\" class=\"formulacion_table_list\">RESPONSABLE:\n";
      $html .= "      </td>\n";
      if ($request['tema'] == "registro"){
        $html .= "      <td align=\"center\" colspan=\"3\" rowspan=\"1\" class=\"modulo_list_claro\">\n";
        $html .= "        <input type=\"text\" class=\"input-text\" name=\"responsable\" value=\"\" size=\"90%\"></input>\n";
        $html .= "      </td>\n";
      }else if($request['tema'] == "consulta")
              {
                $html .= "<td align=\"left\" colspan=\"3\" rowspan=\"1\" class=\"modulo_table_list\"> ".$datos_crf[0]['responsable']."\n";
                $html .= "</td>\n";
              }
      $html .= "    </tr>";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td align=\"center\" colspan=\"3\" rowspan=\"2\">GRUPOS DE RIESGO Y COMPONENTES\n";
      $html .= "      </td>\n";
      $html .= "      <td align=\"center\" rowspan=\"1\">FECHA DE CALIFICACION\n";
      $html .= "      </td>\n";
      $html .= "    </tr\n>";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td align=\"center\" rowspan=\"1\">\n";
      $cut = new ClaseUtil();
      if ($request['tema']=="registro")
      {
        $html .= $cut->AcceptDate("/");
        $html .= "        <input class=\"input-text\" name=\"fechaCalificacion\" type=\"text\" size=\"15%\" value=\"\" onkeyPress=\"return acceptDate(event)\" disable=\"true\">";
        $html .= "".ReturnOpenCalendario('formIngresoRiesgo', 'fechaCalificacion', '/')."\n";
      }else if ($request['tema']=="consulta")
            {
              if($datos_rfd[0]['fecha_calificacion'])
              {
                $f = explode("-",$datos_crf[0]['fecha_calificacion']);
                if(sizeof($f) == 3) $fCalificacion = $f[2]."/".$f[1]."/".$f[0];
              }
              //$html .= "".$datos_rfd[0]['fecha_calificacion'].""; 
              $html .= "".$fCalificacion.""; 
            }
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      
      
      if ($request['tema']=="registro")
      {
        $i = 0;
        foreach ($datos as $indice => $valor)
        {
          $longitud = count($datos);        
          
          $row = $valor['cantidad'];
          $rows = $row + 1;
          
          $html .= "    <tr class=\"modulo_table_title\">\n";            
          $html .= "      <td align=\"center\" colspan=\"1\" rowspan=\"".$rows."\" width=\"30%\">".$valor['grupo_riesgo_id']." -- ".$valor['desc_grup']."\n";
          $html .= "      </td>\n";               
          $html .= "    </tr>\n";
          
          while ($row > 0){
            $html .= "    <tr>\n";
            $html .= "      <td align=\"center\" colspan=\"1\" rowspan=\"1\" width=\"5%\" class=\"hc_table_submodulo_list_title\">".$datos_riesgos[$i]['comp_riesgo_id']."\n";
            $html .= "      </td>\n";
            $html .= "      <td align=\"left\" colspan=\"1\" rowspan=\"1\" width=\"45%\" class=\"modulo_list_oscuro\">".$datos_riesgos[$i]['desc_comp']."\n";
            $html .= "      </td>\n";
            
            $html .= "<td align=\"center\" colspan=\"1\" rowspan=\"1\" width=\"20%\" class=\"modulo_list_claro\">\n";
            $html .= "  <select name=\"calificacion[".$valor['grupo_riesgo_id']."][".$datos_riesgos[$i]['comp_riesgo_id']."]\" class=\"select\" onchange=\"SumarCalificacion()\">\n";
            $html .= "    <option value=\"-1\">-- Sel --</option>\n";
            $html .= "    <option value=\"0\">0</option>\n";
            $html .= "    <option value=\"1\">1</option>\n";
            $html .= "    <option value=\"2\">2</option>\n";
            $html .= "    <option value=\"3\">3</option>\n";
            $html .= "    <option value=\"4\">4</option>\n";
            $html .= "  </select>\n";
            $html .= "</td>\n";
            
            $html .= "    </tr>\n";
            $row = $row - 1;
            $i = $i + 1;
          }        
        }
      }else if ($request['tema']=="consulta")
            {
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
                
                $html .= "      <td align=\"center\" colspan=\"1\" rowspan=\"1\" width=\"20%\" class=\"modulo_table_list\">".$valor['calificacion']."\n";
                $html .= "      </td>\n";
                $html .= "    </tr>\n";
              }             
            }
            
      if ($request['tema']=="consulta")
      {
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\" colspan=\"3\" class=\"modulo_list_claro\"> CALIFICACION DEL RIESGO - RANGO TOTAL\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\" colspan=\"1\" class=\"hc_table_submodulo_list_title\">TOTAL: ".$datos_rfd[0]['calificacion_total']."\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\" colspan=\"3\" class=\"modulo_list_claro\"> 0=SIN RIESGO   --   1-14=RIESGO BAJO   --   15-34=RIESGO MEDIO   --   35-72=RIESGO ALTO \n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\" colspan=\"1\" class=\"hc_table_submodulo_list_title\">NIVEL: ".$request['nivel']."\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }else if ($request['tema']=="registro")
              {
                $html .= "  <tr>\n";
                $html .= "    <td align=\"center\" colspan=\"3\" class=\"modulo_list_claro\"> CALIFICACION DEL RIESGO - RANGO TOTAL\n";
                $html .= "    </td>\n";
                $html .= "    <td align=\"center\" colspan=\"1\" class=\"hc_table_submodulo_list_title\">TOTAL: \n";
                $html .= "      <center><div id=\"total\"></div></center>\n";
                $html .= "    </td>\n";
                $html .= "  </tr>\n";
                $html .= "  <tr>\n";
                $html .= "    <td align=\"center\" colspan=\"3\" class=\"modulo_list_claro\"> 0=SIN RIESGO   --   1-14=RIESGO BAJO   --   15-34=RIESGO MEDIO   --   35-72=RIESGO ALTO\n";
                $html .= "    </td>\n";
                $html .= "    <td align=\"center\" colspan=\"1\" class=\"hc_table_submodulo_list_title\">NIVEL: \n";
                $html .= "      <center><div id=\"nivel\"></div></center>\n";
                $html .= "    </td>\n";
                $html .= "  </tr>\n";
              }
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\" colspan=\"4\">\n";
      $html .= "       <center> <div id=\"error\" class=\"label_error\"></div> </center>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"4\">\n";
      $html .= "      <table align=\"center\">\n";
      
      $html .= "        <tr class=\"modulo_list_claro\">\n";
      if ($request['tema']=="registro")
      {
        
        $html .= "          <td align=\"center\">\n";
        $html .= "            <input class=\"input-submit\" type=\"button\" name=\"Ingresar\" value=\"Ingresar\" onclick=\"EvaluarDatos(document.formIngresoRiesgo)\">\n";
        $html .= "          </td>\n";
      }
      
      $html .= $cut->IsDate();
      $html .= "<script>\n";
      $html .= "  function EvaluarDatos()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formIngresoRiesgo;\n";
      $html .= "    for(i=0; i<frm.length; i++)\n";
      $html .= "    {\n";
      $html .= "      switch(frm[i].type)\n";
      $html .= "      {\n"; 
      $html .= "        case 'text':\n";
      $html .= "          val = frm[i].value;\n";
      //$html .= "          alert(val);\n";
      $html .= "          if (val==\"\")\n";
      $html .= "          {\n";
      $html .= "            document.getElementById('error').innerHTML = 'Debe ingresar todos los campos';\n";
      $html .= "            return;\n";      
      $html .= "          }\n";
      $html .= "        case 'select-one':\n";
      $html .= "          val = frm[i].value;\n";
      $html .= "          if (val==-1)\n";
      $html .= "          {\n";
      $html .= "            document.getElementById('error').innerHTML = 'Debe seleccionar un valor de calificacion para cada componente';\n"; 
      $html .= "            return;\n";     
      $html .= "          }\n";
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "    if(!IsDate(frm.fechaCalificacion.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha posee un formato invalido';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    fr = frm.fere.value;\n";
      $html .= "    fc = frm.fechaCalificacion.value;\n";
      $html .= "    var fecha_r = fr.split('/');\n";
      $html .= "    var fecha_c = fc.split('/');\n";
      $html .= "    ffr = new Date(fecha_r[2]+'/'+fecha_r[1]+'/'+fecha_r[0]);\n";
      $html .= "    ffc = new Date(fecha_c[2]+'/'+fecha_c[1]+'/'+fecha_c[0]);\n";
      $html .= "    if(ffc>ffr)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de calificacion debe ser menor o igual a la fecha actual';\n";
      //$html .= "      alert(fr+' - '+frm.fechaCalificacion.value);\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    frm.submit();";
      $html .= "  }\n";
      $html .= "</script>\n";
      $fe_re = date('d/m/Y');
      $html .= "<input type=\"hidden\" name=\"total_oculto\" id=\"total_oculto\" value=\"\">";
      $html .= "<input type=\"hidden\" name=\"fere\" id=\"fere\" value=\"".$fe_re."\">";
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
    
    function frmMensajeIngreso($action,$mensaje)
    {
      $action['volver'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>''));
      
      $html  = ThemeAbrirTabla('MENSAJE');
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