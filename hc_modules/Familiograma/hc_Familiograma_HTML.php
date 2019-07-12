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
  
  class Familiograma_HTML extends Familiograma
  {
    function Familiograma_HTML()
    {
      $this->Familiograma();
      return true;
    }
    
    function GetForma()
    {
      $pfj = $this->frmPrefijo;
      $evento = $_REQUEST['accion'.$pfj];
      
      switch($evento)
      {
        case 'IngresarFamiliograma':
          $request = $_REQUEST;  
        
          $action['IngresarFamiliograma'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array());
          $datos_paciente = $this->datosPaciente;
          $evolucion = $this->evolucion;

          /*for($i=0;$i<$request['cont_simb'];$i++)
            print_r("checks".$i." ".$request['checks'.$i]);
          for($j=0;$j<$request['cont_abre'];$j++)
            print_r("checka".$j." ".$request['checka'.$j]);
          */
          $mdl = AutoCarga::factory('FamiliogramaSQL', '', 'hc1', 'Familiograma');
          $familiograma = $mdl->IngresarFamiliograma($request, $datos_paciente, $evolucion);
          
          $this->salida = $this->frmMensajeIngreso('SE ALMACENO EL FAMILIOGRAMA');
          break;
        case 'DetFamiliograma':
          $request = $_REQUEST;
          $mdl = AutoCarga::factory('FamiliogramaSQL', '', 'hc1', 'Familiograma');
          $det_fami_simb = $mdl->DetalleFamiliogramaSimb($request);
          $det_fami_abre = $mdl->DetalleFamiliogramaAbre($request);
          $this->salida = $this->frmDetalleFamiliograma($det_fami_simb, $det_fami_abre);
          break;
        default:
          $this->SetXajax(array("MostrarFormaFamiliograma"),             "hc_modules/Familiograma/RemoteXajax/FamiliogramaXajax.php"); 
          $datos_paciente = $this->datosPaciente;
          $action['paginador'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array());
          $mdl = AutoCarga::factory('FamiliogramaSQL', '', 'hc1', 'Familiograma');
          $familiograma = $mdl->ConsultarFamiliograma($_REQUEST['offset'], $datos_paciente);
          $tiposPersona = $mdl->ConsultarTiposPersona();
          $this->salida = $this->frmFamiliograma($tiposPersona, $familiograma, $mdl->pagina, $mdl->conteo);
          break;
      }
     
      return $this->salida;
    }
    /**
    * Funcion donde se crea la forma para seleccionar la informacion correspondiente al 
    * familiograma 
    *
    * @param array $tiposPersona vector que contiene la informacion de los tipos de persona
    * @param array $familiograma vector que contiene la informacion del familiograma del 
    * paciente
    * @param string $pagina cadena con el numero de la pagina que se esta visualizando
    * @param string $conteo cadena con la cantidad de datos total
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function frmFamiliograma($tiposPersona, $familiograma, $pagina, $conteo)
    {
      $pfj = $this->frmPrefijo;
      $request = $_REQUEST;
      
      $action['ingresar_familiograma'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'IngresarFamiliograma'));
      $action['det_familiograma'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'DetFamiliograma'));
      $action['paginador'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array());
      
      $html  = ThemeAbrirTablaSubmodulo('FAMILIOGRAMA');
      $html .= "<form name=\"formFamiliograma\" id=\"formFamiliograma\" method=\"post\" action=\"".$action['ingresar_familiograma']."\">\n";
      $html .= "  <table align=\"center\" border=\"0\" width=\"60%\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"5\">FAMILIOGRAMA\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tipos persona:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"tiposPersona\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($tiposPersona as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['tipo_persona_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Sexo:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"sexo\">Sexo:\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      $html .= "          <option value=\"F\">FEMENINO</option>\n";
      $html .= "          <option value=\"M\">MASCULINO</option>\n";
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"MostrarFamiliograma(document.formFamiliograma)\">\n";     
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"5\">\n";
      $html .= "        <div id=\"error\" class=\"label_error\" align=\"center\">\n";
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"normal_10AN\" colspan=\"5\">\n";
      $html .= "        <div id=\"divFamiliograma\" style=\"display:block\">\n";
      $html .= "        </div>\n";
      $html .= "        <div id=\"divCheck\" style=\"display:none\">\n";
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"5\">\n";
      $html .= "        <div id=\"error_s\" class=\"label_error\"></div>\n";      
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"5\">\n";
      $html .= "        <div id=\"divGuardar\" style=\"display:none\">\n";
      $html .= "          <input class=\"input-submit\" type=\"button\" name=\"guardar\" value=\"Guardar\" onclick=\"EvaluarDatos(document.formFamiliograma)\">\n";
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "<script>\n";
      $html .= "  function MostrarFamiliograma(frm)\n";
      $html .= "  {\n";
      $html .= "    if(frm.tiposPersona.value==\"-1\" || frm.sexo.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el tipo de persona y el sexo';\n";
      $html .= "      document.getElementById('error_s').innerHTML = null;\n";
      $html .= "      document.getElementById('divFamiliograma').style.display = 'none';\n";
      $html .= "      document.getElementById('divGuardar').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else{\n";
      $html .= "      document.getElementById('divGuardar').style.display = 'block';\n";
      $html .= "      document.getElementById('error').innerHTML = null;\n";
      $html .= "      document.getElementById('error_s').innerHTML = null;\n";     
      $html .= "      xajax_MostrarFormaFamiliograma(xajax.getFormValues('formFamiliograma'));\n";
      //$html .= "      return;\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<script>\n";
      $html .= "  function EvaluarDatos(frm)\n";
      $html .= "  {\n";
      $html .= "    if(frm.tiposPersona.value==\"-1\" || frm.sexo.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el tipo de persona y el sexo';\n";
      $html .= "      document.getElementById('error_s').innerHTML = null;\n";
      $html .= "      document.getElementById('divFamiliograma').style.display = 'none';\n";
      $html .= "      document.getElementById('divGuardar').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    var sum=0;\n";
      $html .= "    for(i=0;i<frm.cont_simb.value;i++)\n";
      $html .= "    {\n";
      $html .= "      if(document.getElementById('check_'+i).checked)\n";
      $html .= "        sum=sum+1;\n";
      $html .= "    }\n";
      $html .= "    if(sum==0)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error_s').innerHTML = 'Debe seleccionar una opcion en el area simbologias';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    frm.submit();\n";      
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<br>\n";
      $html .= "<br>\n";
      if((count($familiograma))>0)
      {
      $html .= "<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
      $html .= "  <tr class=\"modulo_table_title\">\n";
      $html .= "    <td align=\"center\"  colspan=\"7\">CONSULTA FAMILIOGRAMA\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
      $html .= "    <td>ID PACIENTE\n";
      $html .= "    </td>\n";
      $html .= "    <td>EVOLUCION\n";
      $html .= "    </td>\n";
      $html .= "    <td>ID USUARIO\n";
      $html .= "    </td>\n";
      $html .= "    <td>FECHA REGISTRO\n";
      $html .= "    </td>\n";
      $html .= "    <td>PARIENTE\n";
      $html .= "    </td>\n";
      $html .= "    <td>SEXO PARIENTE\n";
      $html .= "    </td>\n";
      $html .= "    <td>D\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $est = "modulo_list_oscuro";
      foreach($familiograma as $indice => $valor)
      {
        if($valor['fecha_registro'])
        {
          $f = explode("-",$valor['fecha_registro']);
          if(sizeof($f)==3) $fReg = $f[2]."/".$f[1]."/".$f[0];
        }
        
        if($valor['sexo']=="F")
          $sexo = "FEMENINO";
        else
          $sexo = "MASCULINO";
      
        ($est=="modulo_list_oscuro")? $est="modulo_list_claro" : $est="modulo_list_oscuro";
        $html .= "  <tr class=\"".$est."\">\n";
        $html .= "    <td align=\"center\">".$valor['paciente_id']."\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">".$valor['evolucion_id']."\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">".$valor['usuario_id']."\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">".$fReg."\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">".$valor['descripcion']."\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">".$sexo."\n";
        $html .= "    </td>\n";
        $path  = GetThemePath();
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"".$action['det_familiograma'].URLRequest(array("familiograma_id"=>$valor['familiograma_id'], "descripcion"=>$valor['descripcion'], "sexo"=>$sexo))."\" align=\"center\">\n";
        $html .= "        <sub><img src=\"".$path."/images/flecha.png\" title=\"DETALLE FAMILIOGRAMA\" border=\"0\"></sub>\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }      
      $html .= "</table>\n";
      $html .= "<br>\n";
      
      $chtml = AutoCarga::factory('ClaseHTML');
      $html .= "  ".$chtml->ObtenerPaginado($conteo, $pagina, $action['paginador'], 50);
      }
      $html .= ThemeCerrarTablaSubmodulo();
      return $html;
    }
    
    /**
    * Funcion donde se crea la forma para mostrar un mensaje de salida 
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param string $mensaje cadena con el mensaque se se va a mostrar
    * @return string $html cadena con el codigo html de la pagina
    */    
    function frmMensajeIngreso($mensaje)
    {
      $pfj = $this->frmPrefijo;
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
    /**
    * Funcion que permite crear la forma en donde se muestra el detalle de la informacion 
    * de los familiogramas registrados para un paciente
    * @param array $det_fami_simb vector que contiene la informacion del detalle de los
    * simbolos relacionados al familiograma
    * @param array $det_fami_abre vector que contiene la informacion del detalle de las
    * abreviaturas relacionadas al familiograma
    * @return string $html cadena que contiene el codigo html de la pagina
    */
    function frmDetalleFamiliograma($det_fami_simb, $det_fami_abre)
    {
      $pfj = $this->frmPrefijo;
      $action['volver'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>''));
      $request = $_REQUEST;
      
      $html  = ThemeAbrirTablaSubmodulo('DETALLE FAMILIOGRAMA');
      $html .= "<form name=\"formDetFamiliograma\" id=\"formDetFamiliograma\" method=\"post\" action=\"".$action['volver']."\">\n";
      $html .= "  <table align=\"center\" border=\"0\" width=\"60%\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"2\">DETALLE FAMILIOGRAMA\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"hc_table_submodulo_list_title\" width=\"50%\" align=\"left\">Pariente:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"50%\">".$request['descripcion']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"hc_table_submodulo_list_title\" width=\"50%\" align=\"left\">Sexo Pariente:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"50%\">".$request['sexo']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"hc_table_submodulo_list_title\">\n";
      $html .= "      <td colspan=\"2\">Simbologias\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $cont = 1;
      foreach($det_fami_simb as $indice => $valor)
      {
        if($cont==1)
          $html .= "    <tr>\n";
          
        $html .= "        <td class=\"modulo_list_claro\">".$valor['simbolo']."\n";
        $html .= "        </td>\n";
        
        if($cont==2)
        {  
          $html .= "    </tr>\n";
          $cont=0;
        }
          
        $cont++;
      }
      $cantidad = count($det_fami_abre);
      if($cantidad!=0)
      {
        $html .= "    <tr class=\"hc_table_submodulo_list_title\">\n";
        $html .= "      <td colspan=\"2\">Abreviaturas\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        
        $cont1 = 1;
        foreach($det_fami_abre as $indice => $valor)
        {
          if($cont1==1)
            $html .= "<tr>\n";
            
          $html .= "<td class=\"modulo_list_claro\">".$valor['descripcion']."\n";
          $html .= "</td>\n";
          
          if($cont1==2)
          {
            $html .= "</tr>\n";
            $cont1 = 0;
          }
          $cont1++;
        }
      }
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"2\">\n";
      $html .= "        <input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"Volver\">\n";      
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";      
      $html .= ThemeCerrarTablaSubmodulo();
      
      return $html;
    }
  }
?>