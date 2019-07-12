<?php

  /**************************************************************************************
  * $Id: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * $Revision: 1.3 $   
  * @author Manuel Ruiz Fernandez
  *
  ***************************************************************************************/
 IncludeClass("ClaseHTML");
 IncludeClass("ClaseUtil");

class FichaFamiliograma_HTML extends FichaFamiliograma
{
  function FichaFamiliograma_HTML()
  {
    $this->FichaFamiliograma();
    return true;   
  }
  /**
  * GetConsulta() llama a la funcion FrmConsulta del submoduloHijo HTML para obtiener el
  * HTML de listado y lo retorna a la funcion xxx del modulo
  */   
  function GetConsulta()
  {
    $this->FrmConsulta();
    return $this->salida;
  }
  //function FrmForma($evento)
  function GetForma()
  {
    $pfj = $this->frmPrefijo;
    $evento = $_REQUEST['accion'.$pfj];
    
    switch($evento)
    {
      case 'RegistrarContaminacion':    
        $request = $_REQUEST;
        $datos_paciente = $this->datosPaciente;
        $evolucion = $this->evolucion;
        //var_dump($request['fechaIngreso']);   
        $mdl = AutoCarga::factory('FichaFamiliogramaSQL', '', 'hc1', 'FichaFamiliograma');
        $mdl->IngresarContaminacion($request, $datos_paciente, $evolucion);
        $action['volver'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array());
        
        $this->salida = $this->frmMensajeIngreso($action,"LOS DATOS DEL PACIENTE ".$datos_paciente['primer_nombre']." ".$datos_paciente['segundo_nombre']." ".$datos_paciente['primer_apellido']." ".$datos_paciente['segundo_apellido']." FUERON INGRESADOS ");
        break;
      case 'ModificarContaminacion':
        $request = $_REQUEST;
        //print_r($request['paciente_id']);
        
        $tema = true;
        $mdl = AutoCarga::factory('FichaFamiliogramaSQL', '', 'hc1', 'FichaFamiliograma');
        $datos = $mdl->ConsultarContaminante($request);
        
        $action['modificar_contam'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array());
        $this->salida = $this->frmIngresoContaminacionAmbiental($tema, $datos);
        break;
      case 'ActualizarContaminacion':
        $request = $_REQUEST;
        $datos_paciente = $this->datosPaciente;
        $action['actualizar_contam'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array());
        $action['volver'] = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array());
        $mdl = AutoCarga::factory('FichaFamiliogramaSQL', '', 'hc1', 'FichaFamiliograma');
        $datos = $mdl->ModificarContaminacion($request);
        $this->salida = $this->frmMensajeIngreso($action,"LOS DATOS DEL PACIENTE ".$datos_paciente['primer_nombre']." ".$datos_paciente['segundo_nombre']." ".$datos_paciente['primer apellido']." ".$datos_paciente['segundo_apellido']." FUERON MODIFICADOS ");
        break;
      default:
        $datos_paciente = $this->datosPaciente;
        $action['paginador'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array());
        $mdl   = AutoCarga::factory('FichaFamiliogramaSQL', '', 'hc1', 'FichaFamiliograma');
        $datos = $mdl->ConsultarContaminacion($_REQUEST['offset'], $datos_paciente);
        $this->salida  = $this->frmConsultaContaminacionAmbiental($action, $datos, $mdl->pagina, $mdl->conteo);
      break;
    }
    return $this->salida;
  }
  
  function frmIngresoContaminacionAmbiental($tema, $datos)
  {
    $pfj=$this->frmPrefijo;
    
    if ($tema)
    {
      $html .= ThemeAbrirTablaSubModulo('MODIFICACION CONTAMINACION AMBIENTAL');
      $titulo = "MODIFICACION DE CONTAMINACION AMBIENTAL";
    }
    else
    {
      $titulo = "INGRESO DE CONTAMINACION AMBIENTAL";
    }
    $action['registro_contam'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'RegistrarContaminacion'));
    
    $action['actualizar_contam'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'ActualizarContaminacion'));
    
    $action['volver'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>''));
                 
    $html .= "<table align=\"center\" border=\"0\" width=\"70%\" class=\"modulo_table_list\">\n";
    if ($tema)
    {
      foreach($datos as $indice => $valor)
      {  
        if($valor['fecha_informe'])
        {
          $f = explode("-",$valor['fecha_informe']);
          if(sizeof($f) == 3) $fIngreso = $f[2]."/".$f[1]."/".$f[0];
        }
        //$fIngreso = $valor['fecha_informe'];
        $tContaminante = $valor['tipo_contaminante'];
        $desc = $valor['descripcion'];
        $cContaminacion = $valor['causa'];
        $lTratamiento = $valor['tratamiento'];
        $info['contaminante_id'] = $valor['contaminante_id'];
        $info['paciente_id'] = $valor['paciente_id'];
      }   
    }else
    {
      $fIngreso = "";
      $tContaminante = "";
      $desc = "";
      $cContaminacion = "";
      $lTratamiento = "";
    }
    if ($tema)
    {
      $html .= "  <form id=\"formModificarContaminacion\" name=\"formModificarContaminacion\" action=\"".$action['actualizar_contam'].URLRequest(array("paciente_id"=>$info['paciente_id'], "contaminante_id"=>$info['contaminante_id']))."\" method=\"post\">\n";
      $formName = "formModificarContaminacion"; 
    }else
    {
      $html .= "  <form id=\"formIngresoContaminacion\" name=\"formIngresoContaminacion\" action=\"".$action['registro_contam']."\" method=\"post\">\n";
      $formName = "formIngresoContaminacion";
    }

    $html .= "    <tr class=\"modulo_table_title\">";
    $html .= "      <td align=\"center\" colspan=\"2\">".$titulo."\n";
    $html .= "      </td>\n";
    $html .= "    </tr>";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"left\" class=\"formulacion_table_list\">FECHA DE INFORME:\n";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
    
    $cut = new ClaseUtil();
    
     $fecha = $paciente['fecha_nacimiento'];
    
    if ($tema)
    {
      $html .= $cut->AcceptDate("/");  
      $html .= "        <input type=\"text\" name=\"fechaIngreso\" class=\"input-text\" size=\"20%\"  value = \"".$fIngreso."\" onkeyPress=\"return acceptDate(event)\" disable=\"true\">\n";
      $html .= "      ".ReturnOpenCalendario('formModificarContaminacion','fechaIngreso','/')."\n";
    }else
    {
      $html .= $cut->AcceptDate("/");
      $html .= "        <input type=\"text\" name=\"fechaIngreso\" class=\"input-text\" size=\"20%\"  value = \"".$fIngreso."\" onkeyPress=\"return acceptDate(event)\" disable=\"true\">\n";
      $html .= "      ".ReturnOpenCalendario('formIngresoContaminacion','fechaIngreso','/')."\n";
    }
    $html .= "      </td>";
    $html .= "    </tr>\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"left\" class=\"formulacion_table_list\">TIPO DE CONTAMINANTE:\n";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
    $html .= "        <input type=\"text\" name=\"tipoContaminante\" class=\"input-text\" size=\"80%\" value=\"".$tContaminante."\">\n";
    $html .= "      </td>";
    $html .= "    </tr>\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\" class=\"formulacion_table_list\" colspan=\"2\">DESCRIPCION:\n";
    $html .= "      </td>";
     $html .= "    </tr>\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">\n";
    $html .= "        <textarea style=\"width:100%\" rows=5 name=\"descContaminante\" value=\"\">".$desc."</textarea>";
    $html .= "      </td>";
    $html .= "    </tr>\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"left\" class=\"formulacion_table_list\">CAUSANTE DE LA CONTAMINACION:\n";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
    $html .= "        <input type=\"text\" name=\"causanteContaminacion\" class=\"input-text\" size=\"80%\" value=\"".$cContaminacion."\">\n";
    $html .= "      </td>";
    $html .= "    </tr>\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"left\" class=\"formulacion_table_list\">LUGAR O PERSONA PARA TRATAMIENTO:\n";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
    $html .= "        <input type=\"text\" name=\"tratamiento\" class=\"input-text\" size=\"80%\" value=\"".$lTratamiento."\">\n";
    $html .= "      </td>";
    $html .= "    </tr>\n";
    
    if ($tema)
    {
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"2\">\n";
      $html .= "        <center><div id=\"error\" class=\"label_error\"></div></center>";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $fSistema = date('Y/m/d');
      $html .= "    <input type=\"hidden\" name=\"fecha_sistema\" id=\"fecha_sistema\" value=\"".$fSistema."\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"2\">\n";
      $html .= "        <table align=\"center\">\n";     
      $html .= "          <tr>\n";
      $html .= "            <td align=\"center\" >\n";
      $html .= "              <input class=\"input-submit\" type=\"button\" name=\"Modificar\" value=\"Modificar\" onclick=\"EvaluarDatos(document.formModificarContaminacion)\">";
      $html .= "            </td>\n";
      $html .= "          </form>";
      $html .= "          <form id=\"formVolver\" name=\"formVolver\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "            <td align=\"center\" colspan=\"1\">\n";
      $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"Cancelar\">";
      $html .= "            </td>\n";
      $html .= "          </form>\n";
      $html .= "          </tr>\n";
      $html .= "        </table>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      
      $html .= "  </form>";
      $html .= "</table>\n";
            
    }else
    {
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"2\">\n";
      $html .= "        <center><div id=\"error\" class=\"label_error\"></div></center>\n"; 
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatos(document.formIngresoContaminacion)\">";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $fSistema = date('Y/m/d');
      $html .= "    <input type=\"hidden\" name=\"fecha_sistema\" id=\"fecha_sistema\" value=\"".$fSistema."\">";
      $html .= "  </form>";
      $html .= "</table>\n";
    }  
    
    $html .= $cut->IsDate();
    $html .= "<script>\n";
    $html .= "  function EvaluarDatos(obj)\n";
    $html .= "  {\n";
    $html .= "    if(obj.fechaIngreso.value==\"\")\n";
    $html .= "    {\n";
    $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la Fecha';\n";
    $html .= "      return;";
    $html .= "    }\n";
    $html .= "    if(!IsDate(obj.fechaIngreso.value))\n";
    $html .= "    {\n";
    $html .= "      document.getElementById('error').innerHTML = 'La fecha posee un formato invalido';\n";
    $html .= "      return;";
    $html .= "    }\n";
    $html .= "    if(obj.tipoContaminante.value==\"\")\n";
    $html .= "    {\n";
    $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el Tipo de Contaminante';\n";
    $html .= "      return;";
    $html .= "    }\n";
    $html .= "    fs = obj.fecha_sistema.value;\n";
    $html .= "    fi = obj.fechaIngreso.value;\n";
    $html .= "    var fecha_s = fs.split('/');\n";
    $html .= "    var fecha_i = fi.split('/');\n";
    $html .= "    ffs = new Date(fecha_s[0]+'/'+fecha_s[1]+'/'+fecha_s[2]);\n";
    $html .= "    ffi = new Date(fecha_i[2]+'/'+fecha_i[1]+'/'+fecha_i[0]);\n";
    $html .= "    if(ffi > ffs)\n";
    $html .= "    {\n";
    $html .= "      document.getElementById('error').innerHTML = 'La fecha del informe debe ser menor o igual a la fecha actual';\n";
    $html .= "      return;\n";
    $html .= "    }\n";
    $html .= "  obj.submit();\n";
    $html .= "  }\n";
    $html .= "</script>\n";
    
   if ($tema){
      $html .= ThemeCerrarTablaSubModulo();
   }
    
    return $html;
  }
  
  function frmConsultaContaminacionAmbiental($action, $datos, $pagina, $conteo)
  {
    $pfj = $this->frmPrefijo;
    $action['modificar_contam'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'ModificarContaminacion'));
  
    $html  = ThemeAbrirTablaSubModulo('FAMILIOGRAMA Y CONTAMINACION AMBIENTAL');
    
    $tema = false;
    $html .= $this->frmIngresoContaminacionAmbiental($tema, $datos);
    $html .= "<br>";
    
    $path  = GetThemePath();
    $html .= "<form id=\"formConsultaContaminacion\" name=\"formConsultaContaminacion\" action=\"#\" method=\"post\">\n";
    $html .= "<table border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
    $html .= "  <tr class=\"modulo_table_title\">\n";
    $html .= "    <td align=\"center\" colspan=\"8\">CONSULTA CONTAMINACION AMBIENTAL \n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
    $html .= "    <td align=\"center\" width=\"5%\">FECHA INFORME\n";
    $html .= "    </td>";
    $html .= "    <td align=\"center\" width=\"10%\">TIPO CONTAMINANTE\n";
    $html .= "    </td>";
    $html .= "    <td align=\"center\" width=\"24%\">DESCRIPCION\n";
    $html .= "    </td>";
    $html .= "    <td align=\"center\" width=\"24%\">CAUSANTE CONTAMINACION\n";
    $html .= "    </td>";
    $html .= "    <td align=\"center\" width=\"24%\">TRATAMIENTO\n";
    $html .= "    </td>";
    $html .= "    <td align=\"center\" width=\"5%\">EVOLUCION\n";
    $html .= "    </td>";
    $html .= "    <td align=\"center\" width=\"5%\">FECHA REGISTRO\n";
    $html .= "    </td>";
    $html .= "    <td align=\"center\" width=\"3%\">M\n";
    $html .= "    </td>";
    $html .= "  </tr>\n";
    $est = "modulo_list_claro";
    foreach($datos as $indice => $valor)
    {
      if($valor['fecha_informe'])
      {
        $f = explode("-",$valor['fecha_informe']);
        if(sizeof($f) == 3) $fIngreso = $f[2]."/".$f[1]."/".$f[0];
      }
      if($valor['fecha_registro'])
      {
        $fr = explode("-",$valor['fecha_registro']);
        if(sizeof($fr) == 3) $fReg = $fr[2]."/".$fr[1]."/".$fr[0];
      }
      ($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro";
      
      $html .= "  <tr class=\"".$est."\" border=\"1\">\n";
      $html .= "    <td align=\"center\" width=\"5%\"> ".$fIngreso."\n";     
      $html .= "    </td>";
      $html .= "    <td align=\"center\" width=\"10%\"> ".$valor['tipo_contaminante']."\n";
      $html .= "    </td>";
      $html .= "    <td align=\"center\" width=\"24%\"> ".$valor['descripcion']."\n";
      $html .= "    </td>";
      $html .= "    <td align=\"center\" width=\"24%\"> ".$valor['causa']."\n";     
      $html .= "    </td>";
      $html .= "    <td align=\"center\" width=\"24%\"> ".$valor['tratamiento']."\n";     
      $html .= "    </td>";
      $html .= "    <td align=\"center\" width=\"5%\"> ".$valor['evolucion_id']."\n";     
      $html .= "    </td>";
      $html .= "    <td align=\"center\" width=\"5%\"> ".$fReg."\n";     
      $html .= "    </td>";
      $html .= "    <td align=\"center\" width=\"3%\">\n";     
      $html .= "      <a href=\"".$action['modificar_contam'].URLRequest(array("paciente_id"=>$valor['paciente_id'], "contaminante_id"=>$valor['contaminante_id']))."\" align=\"center\">\n";
      $html .= "        <sub><img src=\"".$path."/images/flecha.png\" title=\"MODIFICAR\" border=\"0\"></sub>\n";
      $html .= "      </a>\n";
      $html .= "    </td>";
      $html .= "  </tr>\n";
    }
    
    $html .= "</table>\n"; 
    $html .= "</form>\n"; 
    $html .= "<br>\n";
    
    $chtml = AutoCarga::factory('ClaseHTML');   
    $html .= "    ".$chtml->ObtenerPaginado($conteo, $pagina, $action['paginador'], 20);    
    //$html .= $this->frmLugarTratamiento();
    
    $html .= ThemeCerrarTablaSubModulo();  
    
    return $html;    
  }
  
  function frmMensajeIngreso($action,$mensaje)
  {
    //$request = $_REQUEST;
    //var_dump($request);
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