<?php
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz fernandez
  */
  
  /**
  * Funcion que permite seleccionar un ingreso relacionado a un paciente
  *
  * @param string $noId cadena numero de identificacion del paciente
  * @param string $tipoId cadena con el tipo de identificacion del paciente
  * @param string $action cadena que contiene el link de la aplicacion
  * @param string $causa_ingreso cadena que contiene el tipo de ingresos a consultar
  * @return Object $objResponse objeto de respuesta al formulario  
  */
  function SeleccionarIngreso($noId, $tipoId, $action, $causa_ingreso)
  {
    $objResponse = new xajaxResponse();
   
    $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
    
    if($causa_ingreso=="normal")
      $ingresos = $mdl->ConsIngresosFiltro($noId, $tipoId);
    else if($causa_ingreso=="urgencias")
            $ingresos = $mdl->ConsIngresosUrgFiltro($noId, $tipoId);
         
         else if($causa_ingreso=="autorizacion")
                $ingresos = $mdl->ConsIngresosAutoriza($noId, $tipoId);
    //$scpt = "location.href=\"".$action."\"\n";
    if(count($ingresos)>1)
    {
      $html  = "  <table class=\"modulo_table_list\" align=\"center\" width=\"60%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"2\" align=\"center\">INGRESO\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";    
      $est = "modulo_list_claro";
      foreach($ingresos as $indice => $valor)
      {
        if($causa_ingreso=="autorizacion")
        $orden = $mdl->ConsultarOrdenServicio($valor['ingreso']);
        ($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
        $html .= "    <tr class=\"modulo_list_claro\">\n";
        $html .= "      <td align=\"center\">\n";
        /*$html .= "        <a href=\"".$action."&noId=".$noId."&tipoId=".$tipoId."&ingreso=".$valor['ingreso']."&comentario=".$valor['comentario']."&fecha_ingreso=".$valor['fecha_ingreso']."&autorizacion=".$valor['autorizacion']."\" class=\"label_error\">".$valor['ingreso']."</a>\n";*/
        $html .= "        <a href=\"".$action."&noId=".$noId."&tipoId=".$tipoId."&ingreso=".$valor['ingreso']."&plan_id=".$valor['plan_id']."\" class=\"label_error\">".$valor['ingreso']."</a>\n";
        $html .= "      </td>\n";
        $fi = explode(" ",$valor['fecha_ingreso']);
        $fIng = $fi['0'];
        $fin = explode("-",$fIng);
        $fIngreso = $fin[2]."/".$fin[1]."/".$fin[0];
        $html .= "      <td align=\"center\">".$fIngreso."\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
      }
      $html .= "  </table>\n";
      $html .= "<br>\n";
      
      $objResponse->assign("Contenido","innerHTML",$html);
      $objResponse->call("MostrarSpan");
    }else if (count($ingresos)==1){
      /*$action .= $action."&noId=".$noId."&tipoId=".$tipoId."&ingreso=".$ingresos[0]['ingreso']."&comentario=".$ingresos[0]['comentario']."&fecha_ingreso=".$ingresos[0]['fecha_ingreso']."&autorizacion=".$ingresos[0]['autorizacion'];*/
      if($causa_ingreso=="autorizacion")
      {
        $orden = $mdl->ConsultarOrdenServicio($ingresos[0]['ingreso']);
        if(!empty($orden))
          $action .= $action."&noId=".$noId."&tipoId=".$tipoId."&ingreso=".$ingresos[0]['ingreso']."&plan_id=".$ingresos[0]['plan_id'];
        else
        {
          $action .= ModuloGetURL("app", "ReclamacionServicios", "controller", "SeleccionarCargos", array("tipo_id_paciente"=>$tipoId, "paciente_id"=>$noId, "ingreso"=>$ingresos[0]['ingreso'], "plan_id"=>$ingresos[0]['plan_id'], "sw_volver"=>"1"));
        }  
      }else{
        $action .= $action."&noId=".$noId."&tipoId=".$tipoId."&ingreso=".$ingresos[0]['ingreso']."&plan_id=".$ingresos[0]['plan_id'];
      }
      $scpt = "location.href=\"".$action."\"";
      $objResponse->script($scpt);
    }else
    {
      $objResponse->alert("NO SE ENCONTRARON INGRESOS PARA EL PACIENTE");
    }
    return $objResponse;
  }
  /**
  * Funcion que permite mostrar el area de texto para ingresar la fecha
  *
  * @param array $form vector con la informacion de los campos ingresados en el formulario
  * @return object $objResponse objeto de respuesta al formulario
  */
  function MostrarFecha($form)
  {
    $objResponse = new xajaxResponse();
    
    $html .= "        <input class=\"input-text\" type=\"text\" name=\"txtfNac\" id=\"txtfNac\" size=\"15%\" onkeypress=\"return acceptDate(event)\">\n";
    $html .= "        </input>\n";
    $html .= "        <a title=\"Ver Calendario\" href=\"javascript:Mostrar_Campo(document.formInconsisEntPago.txtfNac, 'txtfNac')\" class=\"label_error\">\n";
    $html .= "          <img src=\"".GetThemePath()."/images/calendario/calendario.png\" border=\"0\">\n";
    $html .= "        </a>\n";
    $html .= "        <label class=\"label\">[dd/mm/aaaa]</label>\n";
    $html .= "        <div id=\"calendario_pxCampo\" class=\"calendario_px\"></div>\n";
    
    $objResponse->assign("div_fecha", "innerHTML", $html);
    
    return $objResponse;
  }
  /**
  * Funcion que permite consultar y mostrar la informacion de los cargos
  *
  * @param array $form vector con la informacion de los campos ingresados en el formulario
  * @return object $objResponse objeto de respuesta al formulario
  */
  Function BuscarCargos($form)
  {
    $objResponse = new xajaxResponse();
    
    $ingreso = $form['ingreso'];
    $usuario_id = $form['profesional'];
    
    $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
    
    $cargos = $mdl->ConsCargosProfesional($ingreso, $usuario_id);    
    $diagnosticos = $mdl->ConsultarDiagnosticos($ingreso);
    $prof = $mdl->ConsTipoProfesFiltro($ingreso, $usuario_id);
           
    $html  = "  <table class=\"modulo_table_list\" align=\"center\" width=\"100%\">\n";
    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td colspan=\"2\" align=\"left\">Manejo integral segun guia:\n";
    $html .= "      </td>\n";
    $html .= "      <td class=\"modulo_list_claro\" colspan=\"4\" align=\"left\">\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"16.66%\" align=\"center\">Codigo CUPS\n";
    $html .= "      </td>\n";
    $html .= "      <td width=\"16.66%\" align=\"center\">Cantidad\n";
    $html .= "      </td>\n";
    $html .= "      <td width=\"66.68%\" align=\"left\" colspan=\"4\">Descripcion \n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $est = "modulo_list_claro";
    foreach($cargos as $indice => $valor)
    {
      ($est=="modulo_list_oscuro")?$est="modulo_list_claro":$est="modulo_list_oscuro";
      $html .= "    <tr class=\"".$est."\">\n";
      $html .= "      <td width=\"16.66%\" align=\"center\">".$valor['cargo']."\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"16.66%\" align=\"center\">".$valor['cantidad']."\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"66.68%\" align=\"left\" colspan=\"4\">".$valor['desc_cargo']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
    }
    $html .= "    <tr class=\"modulo_list_claro\">\n";
    $html .= "      <td class=\"formulacion_table_list\" align=\"left\">Justificacion Clinica:\n";
    $html .= "      </td>\n";
    $html .= "      <td align=\"left\" colspan=\"5\">\n";
    $html .= "      </td>\n";    
    $html .= "    </tr>\n";
    $html .= "    <tr>\n";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\">Impresion Diagnostica:\n";
    $html .= "      </td>\n";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\">Codigo CIE10\n";
    $html .= "      </td>\n";
    $html .= "      <td class=\"formulacion_table_list\" align=\"left\" colspan=\"4\">Descripcion\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $cant_diag = count($diagnosticos);
    for($i=0; $i<$cant_diag; $i++)
    {
      $html .= "  <tr>\n";
      if($i==0)
      {
        $html .= "    <td class=\"formulacion_table_list\" align=\"center\">Diagnostico principal\n";
        $html .= "    </td>\n";
      }else{
        $html .= "    <td class=\"formulacion_table_list\" align=\"center\">Diagnos. relacionado ".$i."\n";
        $html .= "    </td>\n";
      }
      
      $html .= "    <td class=\"modulo_list_claro\" align=\"center\">".$diagnosticos[$i]['tipo_diagnostico_id']."\n";
      $html .= "        <input type=\"hidden\" name=\"cant_diag\" id=\"cant_diag\" value=\"".$cant_diag."\">\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"modulo_list_claro\" align=\"left\" colspan=\"4\">".$diagnosticos[$i]['diagnostico_nombre']."\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
    }
    $html .= "    <tr class=\"modulo_table_title\">\n";
    $html .= "      <td align=\"center\" colspan=\"6\">INFORMACION DE LA PERSONA QUE SOLICITA\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";    
    $html .= "    <tr>\n";
    $html .= "      <td class=\"formulacion_table_list\" width=\"16.66%\">Nombre:\n";
    $html .= "      </td>\n";
    $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\" width=\"33.33%\">".$prof['nomb_prof']."\n";
    $html .= "      </td>\n";
    $html .= "      <td class=\"formulacion_table_list\" width=\"16.66%\">Telefono:\n";
    $html .= "      </td>\n";
    $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\" width=\"33.33%\">Ind. ".$prof['indicativo_prof']." -- Num. ".$prof['tel_prof']." -- Ext. ".$prof['extencion_prof']."\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "    <tr>\n";
    $html .= "      <td class=\"formulacion_table_list\" width=\"16.66%\">Cargo o actividad:\n";
    $html .= "      </td>\n";
    $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\" width=\"49.99%\">".$prof['desc_prof']."\n";
    $html .= "      </td>\n";
    $html .= "      <td class=\"formulacion_table_list\" width=\"16.66%\">Telefono Cel.:\n";
    $html .= "      </td>\n";
    $html .= "      <td class=\"modulo_list_claro\" width=\"16.66%\">".$prof['tel_cel_prof']."\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\" colspan=\"6\">\n";
    $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\" onclick=\"\">\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
    
    $objResponse->assign("div_cups", "innerHTML", $html);
    
    return $objResponse;
  }
  /**
  * Funcion que permite consultar y mostrar la informacion de los cargos
  *
  * @param array $form vector con la informacion de los campos ingresados en el formulario
  * @return object $objResponse objeto de respuesta al formulario
  */
  function BuscarCUPS($form)
  {
    $objResponse = new xajaxResponse();
    $cargo['cups'] = $form['cups'];
    $cargo['desc_cups'] = $form['desc_cups'];
    
    $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
    $cups = $mdl->ConsultarCUPS($cargo);
    $path = GetThemePath();
    if(!empty($cups))
    {         
      $html  = "  <table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"10%\" align=\"center\">CARGO\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"%\" align=\"center\">DESCRIPCION\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"5%\" align=\"center\">CANT.\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"5%\" align=\"center\">AD.\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $est = "modulo_list_claro";
      foreach($cups as $indice => $valor)
      {
        ($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro"; 
        $html .= "  <tr class=\"".$est."\">\n";
        $html .= "    <td align=\"center\">".$valor['cargo']."\n";
        $html .= "    </td>\n";
        $html .= "    <td>".$valor['desc_cargo']."\n";
        $html .= "    </td>\n";
        if($valor['sw_cantidad']=='0')
        {
          $html .= "    <td width=\"5%\" align=\"center\">\n";
          $html .= "      <input class=\"input-text\" type=\"text\" id=\"cantidad_".$valor['cargo']."\" size=\"5%\" value=\"1\" maxlength=\"3\">\n";
          $html .= "    </td>\n";
        }else{
          $html .= "    <td width=\"5%\" align=\"center\">1\n";
          $html .= "      <input type=\"hidden\" id=\"cantidad_".$valor['cargo']."\" size=\"5%\" value=\"1\">\n";
          $html .= "    </td>\n";
        }
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"#\" onclick=\"ValidarCantidad('".$valor['cargo']."')\">\n";
        $html .= "        <sub><img src=\"".$path."/images/arriba.png\" border=\"0\"></sub>\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      
      $html .= "  </table>\n";
      $objResponse->assign("div_bc", "innerHTML", $html);
    }else{
      $html  = "NO SE ENCONTRARON CARGOS";
      $objResponse->assign("div_bc", "innerHTML", "");
      $objResponse->assign("error", "innerHTML", $html);
    }
  
    return $objResponse;
  }
  /**
  * Funcion que permite consultar y mostrar la informacion del servicio relacionado a un departamento
  *
  * @param array $form vector con la informacion de los campos ingresados en el formulario
  * @return object $objResponse objeto de respuesta al formulario
  */
  function RelacionarServicio($form)
  {
    $objResponse = new xajaxResponse();
    
    $depto_serv = $form['departamento'];
    $ds = explode("/", $depto_serv);
    $html  = "<p>".$ds[2]."</p>\n" ;
    $html .= "<input type=\"hidden\" name=\"servicio\" value=\"".$ds[1]."\"\n>";
    
    $objResponse->assign("div_serv", "innerHTML", $ds[2]);
    $objResponse->assign("servicio", "value", $ds[1]);
    
    return $objResponse;
  }
  /**
  * Funcion que permite adicionar y mostrar la informacion de los cargos que son seleccionados
  *
  * @param string $cargo cadena con el id del cargo
  * @param integer $cantidad valor de la cantidad relacionada al cargo
  * @return object $objResponse objeto de respuesta al formulario
  */
  function AdicionarCargo($cargo, $cantidad)
  {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
    $cups = $mdl->ConsultarCUPSFiltro($cargo);
    $cups['cantidad'] = $cantidad;
    
    $cargos = SessionGetVar("CargosAdicionados");
    $cargos[$cargo] = $cups;
    SessionSetVar("CargosAdicionados", $cargos);
    
    $html .= "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "          <tr class=\"formulacion_table_list\">\n";
    $html .= "            <td width=\"10%\">CARGO</td>\n";
    $html .= "            <td width=\"40%\">DESCRIPCION</td>\n";
    $html .= "            <td width=\"5%\" >CANT.</td>\n";
    $html .= "            <td width=\"5%\" >ELIM.</td>\n";
    $html .= "          </tr>\n";
    $est = "modulo_list_claro";
    $path = GetThemePath();
    foreach($cargos as $indice => $valor)
    {
      ($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro"; 
      $html .= "          <tr class=\"".$est."\">\n";
      $html .= "            <td align=\"center\">".$valor['cargo']."</td>\n";
      $html .= "            <td>".$valor['desc_cargo']."</td>\n";
      $html .= "            <td align=\"center\">".$valor['cantidad']."</td>\n";
      $html .= "            <td align=\"center\">\n";
      $html .= "              <a href=\"#\" onclick=\"EliminaCargo('".$valor['cargo']."')\">\n";
      $html .= "                <sub><img src=\"".$path."/images/elimina.png\" border=\"0\"></sub>\n";
      $html .= "              </a>\n";
      $html .= "            </td>\n";
      $html .= "          </tr>\n";
    }
    $html .= "          <tr>\n";
    $html .= "            <td colspan=\"4\" align=\"right\">\n";   
    $html .= "              <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"Continuar()\">\n";
    $html .= "            </td>\n";
    $html .= "          </tr>\n";
    $html .= "        </table>\n";
    
    $objResponse->assign("div_ac", "innerHTML", $html);
    
    return $objResponse;
  }
  /**
  * Funcion que permite Eliminar la informacion de los cargos que son seleccionados
  *
  * @param string $cargo cadena con el id del cargo
  * @return object $objResponse objeto de respuesta al formulario
  */
  function EliminarCargo($cargo)
  { 
    $objResponse = new xajaxResponse();
    $cargos = SessionGetVar("CargosAdicionados");
    unset($cargos[$cargo]);
    SessionSetVar("CargosAdicionados", $cargos);
    $html = "";
    if(!empty($cargos))
    {
      $html  = "        <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "          <tr class=\"formulacion_table_list\">\n";
      $html .= "            <td width=\"10%\">CARGO</td>\n";
      $html .= "            <td width=\"40%\">DESCRIPCION</td>\n";
      $html .= "            <td width=\"5%\" >CANT.</td>\n";
      $html .= "            <td width=\"5%\" >ELIM.</td>\n";
      $html .= "          </tr>\n";
      $est = "modulo_list_claro";
      $path = GetThemePath();
      foreach($cargos as $indice => $valor)
      {
        ($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro"; 
        $html .= "          <tr class=\"".$est."\">\n";
        $html .= "            <td align=\"center\">".$valor['cargo']."</td>\n";
        $html .= "            <td>".$valor['desc_cargo']."</td>\n";
        $html .= "            <td align=\"center\">".$valor['cantidad']."</td>\n";
        $html .= "            <td align=\"center\">\n";
        $html .= "              <a href=\"#\" onclick=\"EliminaCargo('".$valor['cargo']."')\">\n";
        $html .= "                <sub><img src=\"".$path."/images/elimina.png\" border=\"0\"></sub>\n";
        $html .= "              </a>\n";
        $html .= "            </td>\n";
        $html .= "          </tr>\n";
      }
      $html .= "          <tr>\n";
      $html .= "            <td colspan=\"4\" align=\"right\">\n";   
      $html .= "              <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"Continuar()\">\n";
      $html .= "            </td>\n";
      $html .= "          </tr>\n";
      $html .= "        </table>\n";
    }
    $objResponse->assign("div_ac", "innerHTML", $html);
    
    return $objResponse;
  }
?>