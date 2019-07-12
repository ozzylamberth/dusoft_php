<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: AutorizacionCreditoHTML.class.php,v 1.1 2008/09/22 16:10:29 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez 
  */
  /**
  * Clase Vista: AutorizacionCreditoHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */ 
  IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");
  class AutorizacionCreditoHTML
  {
    /**
    *Constructor de la clase
    */    
    function AutorizacionCreditoHTML(){}
    /**
    * Funcion donde se crea la forma para el menu de las autorizaciones de credito
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaMenu($action)
    {
      $html  = ThemeAbrirTabla('AUTORIZACIONES CREDITO');
      $html .= "<table width=\"30%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
      $html .= "  <tr class=\"modulo_table_title\">\n";
      $html .= "    <td align=\"center\">MENU AUTORIZACIONES\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_oscuro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['autorizar_credito']."\">Autorizar Credito</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_oscuro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['buscar_cuen_autorizadas']."\">Buscar Cuentas Autorizadas</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_oscuro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['generar_reporte']."\">Reportes</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </form>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= ThemeCerrarTabla();
            
      return $html;
    }
    /**
    * Funcion donde se crea la forma para la consulta de las cuentas, permitiendo iniciar la
    * autorizacion de los creditos
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $datos vector con la informacion de las cuentas
    * @param string $pagina cadena con el numero de la pagina que se esta visualizando
    * @param string $conteo cadena con la cantidad de datos total
    * @param array $request informacion a consultar
    * @param array $request1 arreglo con la informacion del request
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaAutorizarCredito($action, $datos, $pagina, $conteo, $request, $request1)
    {
      $html  = ThemeAbrirTabla('AUTORIZACION CREDITOS');  
      $html .= "<form name=\"formConsultarCuentas\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "  <tr class=\"modulo_table_title\">\n";
      $html .= "    <td align=\"center\" colspan=\"4\">CRITERIOS DE BUSQUEDA\n";
      $html .= "    </td>\n";      
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"normal_10AN\" align=\"left\" width=\"20%\">No. CUENTA:\n";
      $html .= "    </td>\n";
      $html .= "    <td width=\"30%\">\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"buscar[cuenta]\" value=\"".$request['cuenta']."\">\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"normal_10AN\" width=\"15%\">NOMBRE:\n";
      $html .= "    </td>\n";
      $html .= "    <td >\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"buscar[nombre]\" size=\"45%\" value=\"".$request['nombre']."\">\n";
      $html .= "    </td>\n";      
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"normal_10AN\" width=\"20%\">No. IDENTIFICACION:\n";
      $html .= "    </td>\n";
      $html .= "    <td width=\"30%\">\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"buscar[identificacion]\" value=\"".$request['identificacion']."\">\n";
      $html .= "    </td>\n";
      
      $html .= "    <td class=\"normal_10AN\" width=\"15%\">APELLIDO:\n";
      $html .= "    </td>\n";
      $html .= "    <td>\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"buscar[apellido]\" size=\"45%\" value=\"".$request['apellido']."\">\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"normal_10AN\" width=\"20%\">No. HISTORIA CLINICA:\n";
      $html .= "    </td>\n";
      $html .= "    <td width=\"30%\">\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"buscar[historia]\" value=\"".$request['historia']."\">\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n"; 
      $html .= "  <input type=\"hidden\" name=\"sw_oculto\" id=\"sw_oculto\" value=\"consultar\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"4\">\n";
      $html .= "      <table align=\"center\" width=\"80%\">\n";
      $html .= "        <tr>\n";
      $html .= "          <td align=\"center\">\n";
      $html .= "            <input type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"Buscar\">";
      $html .= "          </td>\n";
      $html .= "</form>\n";
      $html .= "      <form name=\"formVolver\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "          <td align=\"center\">\n";
      $html .= "            <input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"Cancelar\">\n";
      $html .= "          </td>\n";
      $html .= "      </form>\n";
      $html .= "        </tr>\n";     
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";    
      $html .= "</table>\n";
      $html .= "<br>\n";

      $html .= "<br>\n";
      //print_r(" -- ".count($datos));
      if(!empty($datos))
      {
        $html .= "<table align=\"center\" class=\"modulo_table_list\" width=\"80%\">\n";
        $html .= "  <tr class=\"modulo_table_title\">\n";
        $html .= "    <td align=\"center\" colspan=\"6\">CONSULTA CUENTAS\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
        $html .= "    <td>No. CUENTA\n";
        $html .= "    </td>\n";
        $html .= "    <td>No. IDENTIFICACION\n";
        $html .= "    </td>\n";
        $html .= "    <td>No. HISTORIA\n";
        $html .= "    </td>\n";
        $html .= "    <td>NOMBRES\n";
        $html .= "    </td>\n";
        $html .= "    <td>APELLIDOS\n";
        $html .= "    </td>\n";
        $html .= "    <td>A\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $path = GetThemePath();
        $est = "modulo_list_claro";
        foreach($datos as $indice => $valor)
        {
          ($est == "modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
          $html .= "  <tr class=\"".$est."\">\n";
          $html .= "    <td>".$valor['numerodecuenta']."\n";
          $html .= "    </td>\n";
          $html .= "    <td>".$valor['paciente_id']."\n";
          $html .= "    </td>\n";
          $html .= "    <td>".$valor['historia_numero']."\n";
          $html .= "    </td>\n";
          $html .= "    <td>".$valor['primer_nombre']." ".$valor['segundo_nombre']."\n";
          $html .= "    </td>\n";
          $html .= "    <td>".$valor['primer_apellido']." ".$valor['segundo_apellido']."\n";
          $html .= "    </td>\n";
          $html .= "    <td align=\"center\">\n";
          $html .= "      <a href=\"".$action['autorizar'].URLRequest(array("historia_numero"=>$valor['historia_numero'], "primer_nombre"=>$valor['primer_nombre'], "segundo_nombre"=>$valor['segundo_nombre'], "primer_apellido"=>$valor['primer_apellido'], "segundo_apellido"=>$valor['segundo_apellido'], "residencia_direccion"=>$valor['residencia_direccion'], "paciente_id"=>$valor['paciente_id'], "residencia_telefono"=>$valor['residencia_telefono'], "numerodecuenta"=>$valor['numerodecuenta'], "total_cuenta"=>$valor['total_cuenta'], "tipo_id_paciente"=>$valor['tipo_id_paciente'], "ingreso"=>$valor['ingreso']))."\">\n";
          $html .= "        <sub><img src=\"".$path."/images/ingresar.png\" border=\"0\"></sub>\n";
          $html .= "      </a>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "  <tr>\n";
        $html .= "    <td colspan=\"5\" align=\"center\">\n";
        $chtml = AutoCarga::factory('ClaseHTML');
        $html .= "  ".$chtml->ObtenerPaginado($conteo, $pagina, $action['paginador'], 100);
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
      }else if($request1['sw_oculto']=="consultar")
      {
        $html .= "<p align=\"center\"><font color=\"red\">No existen registros</font></p>\n";
      }
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma para la consulta de las cuentas, permitiendo iniciar la
    * autorizacion de los creditos
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $request arreglo con la informacion del request
    * @param array $datos vector con la informacion de la clasificacion financiera y el tipo
    * de grado de un paciente
    * @param array $destinos vector con la informacion de los destinos existentes
    * @param array $plazos vector con la informacion de los plazos de pago existentes
    * @param array $responsable vector con la informacion del familiar responsable (garante)
    * @param array $grupos vector con la informacion de la via de ingreso 
    * @param array $repartos vector con la informacion de los repartos existentes
    * @param array $tipoid vector con la informacion de los tipos de identificacion 
    * existentes
    */
    function formaRealizarAutorizacion($action, $request, $datos, $destinos, $plazos, $responsable, $grupos, $repartos, $tipoid)
    {
      $html  = ThemeAbrirTabla('AUTORIZACION DE CREDITO');
      $html .= "<form name=\"formRealizarAutorizacion\" id=\"formRealizarAutorizacion\" method=\"post\" action=\"".$action['Registrar_Autorizacion'].URLRequest(array("paciente_id"=>$request['paciente_id'], "tipo_id_paciente"=>$request["tipo_id_paciente"], "ingreso"=>$request['ingreso'], "numerodecuenta"=>$request['numerodecuenta']))."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td colspan=\"4\">CUENTA POR COBRAR\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\" align=\"left\" width=\"18%\">HCL:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['historia_numero']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\" align=\"left\" width=\"18%\">CLASI. FINANCIERA:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['clasi_financiera']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">NOMBRE PACIENTE:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['primer_nombre']." ".$request['segundo_nombre']." ".$request['primer_apellido']." ".$request['segundo_apellido']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">DIR. PACIENTE:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['residencia_direccion']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">CEDULA:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['paciente_id']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">TELF. PACIENTE:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['residencia_telefono']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">ATENCION (CUENTA):\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['numerodecuenta']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">GRADO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['grado']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">DEUDA:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['total_cuenta']."\n";
      $html .= "      <input type=\"hidden\" name=\"deuda\" id=\"deuda\" value=\"".$request['total_cuenta']."\"></input>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">CTA. POR PAGAR:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['total_cuenta']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">DEPOSITO O ABONO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"deposito\" size=\"15%\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">PLAZO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select name=\"plazo\" class=\"select\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      
      foreach($plazos as $indice => $valor)
      {
        $val1 = $indice + 1;
        $html .= "        <option value=\"".$valor['plazo_id']."\">".$valor['descripcion']."</option>\n";
      }     
      $html .= "        </select>\n";
      $html .= "        <input type=\"hidden\" name=\"plazoDesc\" value=\"\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">INTERES:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"interes\" size=\"5%\"> %\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">No. CUOTAS:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"noCuotas\" value=\"\" size=\"5%\">\n";   
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">FECHA INICIO:\n";
      $html .= "      </td>\n";
      $cut = new ClaseUtil();
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= $cut->AcceptDate("/");
      $html .= $cut->IsDate();
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"fechaInicio\" value=\"\" onkeyPress=\"return acceptDate(event)\" size=\"10%\">\n";
      $html .= "".ReturnOpenCalendario('formRealizarAutorizacion', 'fechaInicio', '/')."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">DESTINO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select name=\"destino\" class=\"select\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";

      foreach ($destinos as $indice => $valor)
      {
        $val = $indice + 1;
        $html .= "        <option value=\"".$val."\">".$valor['descripcion']."</option>\n";
        //print_r("indice ".$val."\n");
      } 

      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">CUOTA:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <div id=\"cuotaMensual\"></div>\n";
      $html .= "        <div id=\"pagoTotal\"></div>\n";
      $html .= "        <div id=\"fPago\"></div>\n";
      $cm = "false";
      $html .= "        <input type=\"hidden\" name=\"cuotaM\" value=\"".$cm."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"4\">\n";
      $html .= "        <div id=\"error\" class=\"label_error\" align=\"center\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"\" colspan=\"4\" align=\"center\">\n";
      //$html .= "              <input class=\"input-submit\" type=\"button\" name=\"consultar\" value=\"Consultar Forma Pago\" onclick=\"xajax_CalcularFormaPago(xajax.getFormValues('formRealizarAutorizacion'))\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"consultar\" value=\"Consultar Forma Pago\" onclick=\"ValidarDatosCuenta()\"></input>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "<script>\n";
      $html .= "  function ValidarDatosCuenta()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formRealizarAutorizacion;\n";
      $html .= "    fi = frm.fechaInicio.value;\n";
      $html .= "    var fecha_i = fi.split('/');\n";
      $html .= "    ffi = new Date(fecha_i[2]+'/'+fecha_i[1]+'/'+fecha_i[0]);\n";
      $html .= "    ffs = new Date();\n";
      $html .= "    if(frm.deposito.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el valor del deposito';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.deposito.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(!IsNumeric(frm.deposito.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El deposito debe ser un valor numerico';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.deposito.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(frm.plazo.value==\"-1\")\n";  
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el plazo';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.plazo.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(frm.interes.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el porcentaje de Interes';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.interes.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(!IsNumeric(frm.interes.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El porcentaje de interes debe ser un valor numerico';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.interes.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(frm.noCuotas.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el numero de Cuotas'\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.noCuotas.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(!IsNumeric(frm.noCuotas.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El numero de cuotas debe ser un valor numerico';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.noCuotas.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none'\n";
      $html .= "      return;\n";
      $html .= "    }else if(frm.noCuotas.value<\"1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El numero de cuotas debe ser mayor a cero';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.noCuotas.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none'\n";
      $html .= "      return;\n";
      $html .= "    }else if(frm.fechaInicio.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la fecha de inicio';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.fechaInicio.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(!IsDate(frm.fechaInicio.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha posee un formato invalido';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.fechaInicio.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(ffi<ffs)\n";
      $html .= "    {";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de inicio debe ser mayor a la fecha actual';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.fechaInicio.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(frm.destino.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el destino';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.destino.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else\n";
      $html .= "    {\n";
      $html .= "      frm.cuotaM.value = 'true';\n";
      $html .= "      var indice = frm.plazo.selectedIndex;\n";
      $html .= "      frm.plazoDesc.value = frm.plazo.options[indice].text;\n";
      $html .= "      document.getElementById('error').innerHTML = null;\n";
      $html .= "      document.getElementById('errori').innerHTML = null;\n";
      $html .= "      xajax_CalcularFormaPago(xajax.getFormValues('formRealizarAutorizacion'));\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">GRUPO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select name=\"grupo\" class=\"select\">\n";
      $html .= "          <option value=\"-1\">--Seleccionar--</option>\n";
      foreach($grupos as $indice => $valor)
      {
        $val2 = $indice + 1 ;
        $html .= "        <option value=\"".$val2."\">".$valor['via_ingreso_nombre']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">TIPO GARANTIA:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"33%\">\n";
      $html .= "        <input type=\"checkbox\" name=\"garantiaP\" value=\"1\" onclick=\"marcado=true\">Pagare</input>\n";
      $html .= "        <input type=\"checkbox\" name=\"garantiaLC\" value=\"1\" onclick=\"marcado=true\">Letra de Cambio</input>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if (count($responsable)>0){
        $html .= "    <tr>\n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" colspan=\"4\">INFORMACION DEL GARANTE\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"modulo_table_title\">GARANTE:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">".$responsable[0]['pri_nombre']." ".$responsable[0]['seg_nombre']." ".$responsable[0]['pri_apellido']." ".$responsable[0]['seg_apellido']."\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_table_title\">DIR. GARANTE:\n";      
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">".$responsable[0]['direccionfam']."\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"modulo_table_title\">No. IDENTIFICACION:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">".$responsable[0]['no_identi_id']."\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_table_title\">TEL. GARANTE:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">".$responsable[0]['telefonofam']."\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <input type=\"hidden\" name=\"infoGarante\" value=\"true\">\n";
        $html .= "    <input type=\"hidden\" name=\"responsable_familiar_id\" value=\"".$responsable[0]['responsable_familiar_id']."\">\n";
      }else
      {
        $html .= "    <tr>\n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" colspan=\"4\">INFORMACION DEL GARANTE\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"modulo_table_title\">PRIMER NOMBRE:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"priNomGarante\" size=\"20%\">\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_table_title\">SEGUNDO NOMBRE:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\"\n>";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"segNomGarante\" size=\"20%\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"modulo_table_title\">PRIMER APELLIDO:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"priApeGarante\" size=\"20%\">\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_table_title\">SEGUNDO APELLIDO:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\"\n>";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"segApeGarante\" size=\"20%\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"modulo_table_title\">TIPO IDENTIFICACION:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <select class=\"select\" name=\"tipoIdGarante\">\n";
        $html .= "          <option value=\"-1\">--Seleccionar--</option>\n";
        foreach($tipoid as $indice => $valor)
        {
          $html .= "        <option value=\"".$valor['tipo_id_paciente']."\">".$valor['descripcion']."</option>\n";
        }
        $html .= "        </select>\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_table_title\">No. IDENTIFICACION:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"noIdGarante\" size=\"10%\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"modulo_table_title\">DIRECCION:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"dirGarante\" size=\"35%\">";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_table_title\">TELEFONO:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"telGarante\" size=\"20%\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"modulo_table_title\">REPARTO:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "      <select class=\"select\" name=\"reparto\">\n";
        $html .= "        <option value=\"-1\">--Seleccionar--</option>\n";
        foreach ($repartos as $indice => $valor)
        {
        $html .= "        <option value=\"".$valor['reparto_id']."\">".$valor['nombre']."</option>\n";
        }
        
        $html .= "      </select>\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $pct = AutoCarga::factory('Pacientes','','app','DatosPaciente');
        //$cl = new ClaseUtil();
        $zona = GetVarConfigAplication('DefaultZona');
        $pais = GetVarConfigAplication('DefaultPais');
        $dpto = GetVarConfigAplication('DefaultDpto');
        $mpio = GetVarConfigAplication('DefaultMpio');
        $html .= "    <input type=\"hidden\" name=\"zona\" value=\"".$zona."\">\n";
        $html .= "    <input type=\"hidden\" name=\"pais\" value=\"".$pais."\">\n";
        $html .= "    <input type=\"hidden\" name=\"dpto\" value=\"".$dpto."\">\n";
        $html .= "    <input type=\"hidden\" name=\"mpio\" value=\"".$mpio."\">\n";
        $html .= "    <input type=\"hidden\" name=\"comuna\" value=\"\">\n";
        $NomPais = $pct->ObtenerNombrePais($pais);
        $NomDpto = $pct->ObtenerNombreDepartamento($pais, $dpto); 
        $NomMpio = $pct->ObtenerNombreCiudad($pais, $dpto, $mpio);
        $url = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$pais."&dept=".$dpto."&mpio=".$mpio."&forma=formRealizarAutorizacion ";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" colspan=\"4\">RESIDENCIA DEL GARANTE\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"modulo_table_title\">LUGAR RESIDENCIA:\n";
        $html .= "      </td>\n";  
        $html .= "      <input type=\"hidden\" name=\"nomPais\" value=\"".$NomPais."\">\n";
        $html .= "      <input type=\"hidden\" name=\"nomDpto\" value=\"".$NomDpto."\">\n";
        $html .= "      <input type=\"hidden\" name=\"nomMpio\" value=\"".$NomMpio."\">\n";
          
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <label id=\"ubicacion\">".$NomPais." - ".$NomDpto." - ".$NomMpio."</label>\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_table_title\">PARROQUIA:\n";
        $html .= "      </td>\n";        
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <label id=\"tipo_comuna\"></label>\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td colspan=\"4\" align=\"center\">\n";
        $html .= "        <input class=\"input-submit\" type=\"button\" name=\"ConsultarR\" value=\"Consultar Residencia\" target=\"localidad\" onclick=\"window.open('".$url."','localidad','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\"\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <input type=\"hidden\" name=\"infoGarante\" value=\"false\">\n";
      }
      $html .= "    <tr>\n";
      $html .= "      <td class=\"hc_table_submodulo_list_title\" colspan=\"4\">INFORMACION DEL TITULAR\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">PRIMER NOMBRE:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"priNomTitular\" size=\"20%\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">SEGUNDO NOMBRE:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"segNomTitular\" size=\"20%\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">PRIMER APELLIDO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"priApeTitular\" size=\"20%\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">SEGUNDO APELLIDO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"segApeTitular\" size=\"20%\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">TIPO IDENTIFICACION:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"tipoIdTitular\">\n";
      $html .= "          <option value=\"-1\">--Seleccionar--</option>\n";
      foreach($tipoid as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['tipo_id_paciente']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">No. IDENTIFICACION:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"noIdTitular\" size=\"10%\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">DIRECCION:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"dirTitular\" size=\"35%\">";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">TELEFONO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"telTitular\" size=\"20%\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">PARENTESCO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"parTitular\" size=\"35%\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">No. AFILIACION:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"noAfiTitular\" size=\"10%\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "          <tr>\n";
      $html .= "            <td colspan=\"4\">\n";
      $html .= "             <div class=\"label_error\" id=\"errori\" align=\"center\"></div>\n";
      $html .= "            </td>\n";
      $html .= "          </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"4\">\n";
      $html .= "        <table align=\"center\">\n";
      $html .= "          <tr>\n";
      $html .= "            <td colspan=\"2\">\n";
      $html .= "              <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"ValidarDatosIngreso()\">\n";
      $html .= "            </td>\n";
      $html .= "</form>\n";
      $html .= "<script>\n";
      $html .= "  function ValidarDatosIngreso()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formRealizarAutorizacion;\n";
      $html .= "    fi = frm.fechaInicio.value;\n";
      $html .= "    var fecha_i = fi.split('/');\n";
      $html .= "    ffi = new Date(fecha_i[2]+'/'+fecha_i[1]+'/'+fecha_i[0]);\n";
      $html .= "    ffs = new Date();\n";
      //$html .= "    alert(frm.cuotaM.value);\n";
      $html .= "    if(frm.cuotaM.value==\"false\")\n";
      $html .= "    {\n";
      $html .= "    document.getElementById('errori').innerHTML = 'Debe consultar la forma de pago';\n";
      $html .= "    frm.consultar.focus();\n";
      $html .= "    return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.deposito.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe ingresar el valor del deposito';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.deposito.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(!IsNumeric(frm.deposito.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'El deposito debe ser un valor numerico';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.deposito.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(frm.plazo.value==\"-1\")\n";  
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe seleccionar el plazo';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.plazo.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(frm.interes.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe ingresar el porcentaje de Interes';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.interes.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(!IsNumeric(frm.interes.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'El porcentaje de interes debe ser un valor numerico';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.interes.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(frm.noCuotas.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe ingresar el numero de Cuotas'\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.noCuotas.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(!IsNumeric(frm.noCuotas.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'El numero de cuotas debe ser un valor numerico';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.noCuotas.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none'\n";
      $html .= "      return;\n";
      $html .= "    }else if(frm.fechaInicio.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe ingresar la fecha de inicio';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.fechaInicio.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(!IsDate(frm.fechaInicio.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'La fecha posee un formato invalido';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.fechaInicio.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(ffi<ffs)\n";
      $html .= "    {";
      $html .= "      document.getElementById('errori').innerHTML = 'La fecha de inicio debe ser mayor a la fecha actual';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.fechaInicio.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }else if(frm.destino.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe seleccionar el destino';\n";
      $html .= "      document.getElementById('cuotaMensual').innerHTML = '';\n";
      $html .= "      frm.cuotaM.value = 'false';\n";
      $html .= "      frm.destino.focus();\n";
      $html .= "      document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "      return;\n";
      $html .= "    }\n";//Validacion Forma Pago
      $html .= "    if(frm.grupo.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe seleccionar el grupo';\n";
      $html .= "      frm.grupo.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!frm.garantiaP.checked && !frm.garantiaLC.checked)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe seleccionar el tipo de garantia';\n";
      $html .= "      frm.garantiaP.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.infoGarante.value==\"false\")\n";
      $html .= "    {\n";
      $html .= "      if(frm.priNomGarante.value==\"\")\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('errori').innerHTML = 'Debe ingresar el primer nombre del garante';\n";
      $html .= "        frm.priNomGarante.focus();\n";
      //$html .= "        document.getElementById('formaPago').style.display = 'none';\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(frm.priApeGarante.value==\"\")\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('errori').innerHTML = 'Debe ingresar el primer apellido del garante';\n";
      $html .= "        frm.priApeGarante.focus();\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(frm.tipoIdGarante.value==\"-1\")\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('errori').innerHTML = 'Debe seleccionar el tipo de identificacion del garante';\n";
      $html .= "        frm.tipoIdGarante.focus();\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(frm.noIdGarante.value==\"\")\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('errori').innerHTML = 'Debe ingresar el numero de identificacion del garante';";
      $html .= "        frm.noIdGarante.focus();\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(!IsNumeric(frm.noIdGarante.value))";
      $html .= "      {\n";
      $html .= "        document.getElementById('errori').innerHTML = 'El numero de identificacion del garante debe ser un valor numerico'\n;";
      $html .= "        frm.noIdGarante.focus();\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(frm.dirGarante.value==\"\")\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('errori').innerHTML = 'Debe ingresar la direccion del garante';\n";
      $html .= "        frm.dirGarante.focus();\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(frm.reparto.value==\"-1\")\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('errori').innerHTML = 'Debe seleccionar el reparto del garante';\n";
      $html .= "        frm.reparto.focus();\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(frm.comuna.value==\"\" || frm.comuna.value==\"-1\")\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('errori').innerHTML = 'Para la residencia del garante debe seleccionar la parroquia';\n";
      $html .= "        frm.ConsultarR.focus();\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "    }";
      $html .= "    if(frm.priNomTitular.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe ingresar el primer nombre del titular';\n";
      $html .= "      frm.priNomTitular.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.priApeTitular.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe ingresar el primer apellido del titular';\n";
      $html .= "      frm.priApeTitular.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.tipoIdTitular.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe seleccionar el tipo de identificacion del titular';\n";
      $html .= "      frm.tipoIdTitular.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.noIdTitular.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe ingresar el numero de identificacion del titular';";
      $html .= "      frm.noIdTitular.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.noIdTitular.value))";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'El numero de identificacion del titular debe ser un valor numerico'\n;";
      $html .= "      frm.noIdTitular.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.dirTitular.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe ingresar la direccion del titular';\n";
      $html .= "      frm.dirTitular.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.parTitular.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe ingresar el parentesco';\n";
      $html .= "      frm.parTitular.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.noAfiTitular.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'Debe ingresar el numero de afiliacion del titular';";
      $html .= "      frm.noAfiTitular.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.noAfiTitular.value))";
      $html .= "    {\n";
      $html .= "      document.getElementById('errori').innerHTML = 'El numero de afiliacion del titular debe ser un valor numerico'\n;";
      $html .= "      frm.noAfiTitular.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    frm.submit();";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<form action=\"".$action['volver']."\" name=\"formVolver\" id=\"formVolver\" method=\"post\">\n";
      $html .= "            <td colspan=\"2\">\n";
      $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
      $html .= "            </td>\n";
      $html .= "</form>\n";
      $html .= "          </tr>\n";
      $html .= "        </table>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "<br>\n";
      
      $html .= "<div id=\"formaPago\" style=\"display:block\"></div>\n";
      
      $html .= ThemecerrarTabla();
            
      return $html;
    }
    /**
    * Funcion donde se crea la forma para consultar las cuentas que han sido autorizadas
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $datos vector con la informacion de las cuentas
    * @param string $pagina cadena con el numero de la pagina que se esta visualizando
    * @param string $conteo cadena con la cantidad de datos total
    * @param array $request informacion a consultar
    * @param array $request1 arreglo con la informacion del request
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaBuscarCuentasAutorizadas($action, $datos, $pagina, $conteo, $request, $request1)
    {
      $html  = ThemeAbrirTabla('BUSCAR CUENTAS AUTORIZADAS');
      $html .= "<form name=\"formBuscarCuentasAutorizadas\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "  <tr class=\"modulo_table_title\">\n";
      $html .= "    <td align=\"center\" colspan=\"4\">CRITERIOS DE BUSQUEDA\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"normal_10AN\" align=\"left\" width=\"20%\">No. CUENTA:\n";
      $html .= "    </td>\n";
      $html .= "    <td width=\"30%\">\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"buscar[cuenta]\" value=\"".$request['cuenta']."\">\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"normal_10AN\" width=\"15%\">NOMBRE:\n";
      $html .= "    </td>\n";
      $html .= "    <td>\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"buscar[nombre]\" size=\"45%\" value=\"".$request['nombre']."\">\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"normal_10AN\" width=\"20\">No. IDENTIFICACION:\n";
      $html .= "    </td>\n";
      $html .= "    <td width=\"30%\">\n"; 
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"buscar[identificacion]\" value=\"".$request['identificacion']."\">\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"normal_10AN\" width=\"15%\">APELLIDO:\n";
      $html .= "    </td>\n";
      $html .= "    <td>\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"buscar[apellido]\" size=\"45%\" value=\"".$request['apellido']."\">\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"normal_10AN\" width=\"20%\">No.HISTORIA CLINICA:\n";
      $html .= "    </td>\n";
      $html .= "    <td>\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"buscar[historia]\" value=\"".$request['historia']."\">\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"normal_10AN\" width=\"15%\">No. FACTURA:\n";
      $html .= "    </td>\n";
      $html .= "    <td>\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"buscar[factura]\" value=\"".$request['factura']."\">\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <input type=\"hidden\" name=\"sw_oculto\" id=\"sw_oculto\" value=\"consultar\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"4\">\n";
      $html .= "      <table align=\"center\" width=\"80%\">\n";
      $html .= "        <tr>\n";
      $html .= "          <td align=\"center\">\n";
      $html .= "            <input type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"Buscar\">\n";
      $html .= "          </td>\n";
      $html .= "</form>\n";
      $html .= "      <form name=\"formVolver\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "          <td align=\"center\">\n";
      $html .= "            <input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"Cancelar\">\n";
      $html .= "          </td>\n";
      $html .= "      </form>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<br>\n";
      $html .= "<br>\n";
      if(!empty($datos))
      {
        $html .= "<table align=\"center\" class=\"modulo_table_list\" width=\"80%\">\n";
        $html .= "  <tr class=\"modulo_table_title\">\n";
        $html .= "    <td align=\"center\" colspan=\"7\">CONSULTA CUENTA AUTORIZADA\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
        $html .= "    <td>No. CUENTA\n";
        $html .= "    </td>\n";
        $html .= "    <td>No. IDENTIFICACION\n";
        $html .= "    </td>\n";
        $html .= "    <td>No. HISTORIA\n";
        $html .= "    </td>\n";
        $html .= "    <td>No. FACTURA\n";
        $html .= "    </td>\n";
        $html .= "    <td>NOMBRES\n";
        $html .= "    </td>\n";
        $html .= "    <td>APELLIDOS\n";
        $html .= "    </td>\n";
        $html .= "    <td>C\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $path = GetThemePath();
        $est = "modulo_list_claro";
        foreach($datos as $indice => $valor)
        {
          ($est=="modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
          $html .= "  <tr class=\"".$est."\">\n";
          $html .= "    <td>".$valor['numerodecuenta']."\n";
          $html .= "    </td>\n";
          $html .= "    <td>".$valor['paciente_id']."\n";
          $html .= "    </td>\n";
          $html .= "    <td>".$valor['historia_numero']."\n";
          $html .= "    </td>\n";
          $html .= "    <td>".$valor['factura_fiscal']."\n";
          $html .= "    </td>\n";
          $html .= "    <td>".$valor['primer_nombre']." ".$valor['segundo_nombre']."\n";
          $html .= "    </td>\n";
          $html .= "    <td>".$valor['primer_apellido']." ".$valor['segundo_apellido']."\n";
          $html .= "    </td>\n";
          $html .= "    <td align=\"center\">\n";
          $html .= "      <a href=\"".$action['Informacion_Autorizacion'].URLRequest(array("historia_numero"=>$valor['historia_numero'], "primer_nombre"=>$valor['primer_nombre'], "segundo_nombre"=>$valor['segundo_nombre'], "primer_apellido"=>$valor['primer_apellido'], "segundo_apellido"=>$valor['segundo_apellido'], "residencia_direccion"=>$valor['residencia_direccion'], "paciente_id"=>$valor['paciente_id'], "residencia_telefono"=>$valor['residencia_telefono'], "numerodecuenta"=>$valor['numerodecuenta'], "total_cuenta"=>$valor['total_cuenta'], "tipo_id_paciente"=>$valor['tipo_id_paciente'], "ingreso"=>$valor['ingreso'], "factura_fiscal"=>$valor['factura_fiscal']))."\">\n";
          $html .= "        <sub><img src=\"".$path."/images/ingresar.png\" border=\"0\"></sub>\n";
          $html .= "      </a>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "    <tr>\n";
        $html .= "      <td colspan=\"7\" align=\"center\">\n";
        $chtml = AutoCarga::factory('ClaseHTML');
        $html .= "  ".$chtml->ObtenerPaginado($conteo, $pagina, $action['paginador'], 100);
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "</table>\n";
      }else 
      {
        $html .= "<p align=\"center\"><font color=\"red\">No se encontraron registros</font></p>";
      }
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * Forma en la que se muestra un mensaje y permite ir a una pagina de la 
    * aplicacion
    * @param array $action vector que contiene los link de la aplicacion
    * @param string $mensaje cadena con el mensaje que se va a mostrar 
    * @return string $html cadena con el codigo html de la pagina
    */    
    function formaMensaje($action, $mensaje)
    {
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
      $html .= "      <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
      $html .= "      </form>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $html .= ThemeCerrarTabla();      
      return $html;
    }
    /**
    * Funcion donde se crea la forma para mostrar la informacion de una autorizacion de 
    * credito especifica
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $request arreglo con la informacion del request
    * @param array $datos arreglo con la informacion de la clasificacion financiera y el
    *              tipo de grado del paciente
    * @param array $InfoAutorizacion arreglo con la informacion de la cuenta autorizada
    * @param array $DetAutorizacion arreglo con el detalle de la cuenta autorizada
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaInformacionAutorizacion($action, $request, $datos, $InfoAutorizacion, $DetAutorizacion)
    {
      $html  = ThemeAbrirTabla('INFORMACION AUTORIZACION CREDITO');
      $html .= "<form name=\"formInformacionAutorizacion\" id=\"formInformacionAutorizacion\" method=\"post\" action=\"".$action['volver']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td colspan=\"4\">AUTORIZACION DE CREDITO\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"hc_table_submodulo_list_title\" colspan=\"4\">INFORMACION DEL PACIENTE\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\" align=\"left\" width=\"18%\">HCL:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"30%\">".$request['historia_numero']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\" align=\"left\" width=\"18%\">CLASI. FINANCIERA:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"30%\">".$datos[0]['clasi_financiera']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">NOMBRE PACIENTE:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['primer_nombre']." ".$request['segundo_nombre']." ".$request['primer_apellido']." ".$request['segundo_apellido']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">DIR. PACIENTE:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['residencia_direccion']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">CEDULA:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['paciente_id']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">TELF. PACIENTE:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['residencia_telefono']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">ATENCION (CUENTA):\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['numerodecuenta']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">GRADO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['grado']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"hc_table_submodulo_list_title\" colspan=\"4\">INFORMACION DEL CREDITO\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">DEUDA:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['total_cuenta']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">CTA. POR PAGAR:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['total_cuenta']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">DEPOSITO O ABONO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['deposito']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">PLAZO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['desc_plazo']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">INTERES:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['porcentaje_interes']." %"."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">No. CUOTAS:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['no_cuotas']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if($InfoAutorizacion[0]['fecha_inicio'])
      {
        $fi = explode("-",$InfoAutorizacion[0]['fecha_inicio']);
        if(sizeof($fi==3)) $fIni=$fi[2]."/".$fi[1]."/".$fi[0];
      }
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">FECHA INICIO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$fIni."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">DESTINO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['desc_destino']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">CUOTA:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".formatoValor($DetAutorizacion[0]['cuota'])."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">No. FACTURA:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$request['factura_fiscal']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">GRUPO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['via_ingreso_nombre']."\n";
      $html .= "      </td>\n";
      if($InfoAutorizacion[0]['sw_pagare']=="0")
        $pagare='';
      else
        $pagare='Pagare';
        
      if($InfoAutorizacion[0]['sw_letra_cambio']=="0")
        $lcambio='';
      else
        $lcambio='Letra de Cambio';
        
      if($InfoAutorizacion[0]['sw_pagare']=="1" && $InfoAutorizacion[0]['sw_letra_cambio']=="1")
        $sep = ' - ';
      else
        $sep = '';
      
      $html .= "      <td class=\"modulo_table_title\">TIPO GARANTIA:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$pagare.$sep.$lcambio."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if($InfoAutorizacion[0]['fecha_registro'])
      {
        $fr = (explode("-",$InfoAutorizacion[0]['fecha_registro']));
        if(sizeof($fr)==3) $fReg=$fr[2]."/".$fr[1]."/".$fr[0];
      }
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">FECHA REGISTRO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$fReg."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"hc_table_submodulo_list_title\" colspan=\"4\">INFORMACION DEL GARANTE\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">NOMBRE GARANTE:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['pri_nombre']." ".$InfoAutorizacion[0]['seg_nombre']." ".$InfoAutorizacion[0]['pri_apellido']." ".$InfoAutorizacion[0]['seg_apellido']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">DIR. GARANTE:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['direccionfam']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">CEDULA GARANTE:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['no_identi_id']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">TEL. GARANTE:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['telefonofam']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">LUGAR RESIDENCIA:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['pais']." - ".$InfoAutorizacion[0]['departamento']." - ".$InfoAutorizacion[0]['municipio']." - ".$InfoAutorizacion[0]['comuna']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"hc_table_submodulo_list_title\" colspan=\"4\">INFORMACION DEL TITULAR\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">NOMBRE TITULAR:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['primer_nombre']." ".$InfoAutorizacion[0]['segundo_nombre']." ".$InfoAutorizacion[0]['primer_apellido']." ".$InfoAutorizacion[0]['segundo_apellido']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">DIR. TITULAR:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['direccion']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">No. IDENTIFICACION:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['identificacion']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">TEL. TITULAR:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['telefono']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_title\">PARENTESCO:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['parentesco']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_table_title\">No. AFILIACION:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$InfoAutorizacion[0]['no_afiliacion']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "<br>\n";
      $html .= "<br>\n";
      $html .= "<table class=\"modulo_table_list\" width=\"40%\" align=\"center\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td colspan=\"4\">FORMA DE PAGO\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
      $html .= "    <td>FECHA PROGRAMADA\n";
      $html .= "    </td>\n";
      $html .= "    <td>CUOTA\n"; 
      $html .= "    </td>\n";
      $html .= "    <td>CARGO POR INTERES\n"; 
      $html .= "    </td>\n";  
      $html .= "    <td>TOTAL CUOTA\n"; 
      $html .= "    </td>\n"; 
      $html .= "  </tr>\n";
      $ttc = 0;
      foreach($DetAutorizacion as $indice => $valor)
      {
        if($valor['fecha_cuota'])
        {
          $fc = explode("-",$valor['fecha_cuota']);
          if(sizeof($fc)==3) $fCuota=$fc[2]."/".$fc[1]."/".$fc[0];
        }
        $html .= "  <tr class=\"normal_10AN\">\n";
        $html .= "    <td align=\"center\">".$fCuota."\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">".formatoValor($valor['cuota'])."\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">".formatoValor($valor['intereses'])."\n";
        $html .= "    </td>\n";
        $tc = $valor['cuota'] + $valor['intereses'];
        $html .= "    <td align=\"center\">".formatoValor($tc)."\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $ttc = $ttc + $tc;
      }
      $html .= "  <tr>\n";
      $html .= "    <td class=\"modulo_table_list\" align=\"right\" colspan=\"3\">PAGO TOTAL:\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"modulo_table_list\" align=\"center\">".formatoValor($ttc)."\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n"; 
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n"; 
      $html .= "</form>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma para generar los reportes por medio de informacion 
    * suministrada por el usuario
    * 
    * @param array $action vector que contiene los link de la aplicacion
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaGenerarReporte($action)
    {
      $request = $_REQUEST;
      /*foreach($request as $indice => $valor)
      {
        print_r($indice." ".$valor."\n");
      }*/
     
      $rpt = new GetReports();
      $mst = $rpt->GetJavaReport('app', 'AutorizacionCredito', 'autorizacredito', array("oculto"=>$request['oculto'], "fechaInicio"=>$request['fechaInicio'], "fechaFinal"=>$request['fechaFinal'], "noHistoria"=>$request['noHistoria'], "noIdentificacion"=>$request['noIdentificacion'], "nombres"=>$request['nombres'], "apellidos"=>$request['apellidos'], "garantiaP"=>$request['garantiaP'], "garantiaLC"=>$request['garantiaLC'], "garP"=>$request['garP'], "garLC"=>$request['garLC'], "codAutorizacion"=>$request['codAutorizacion']), array('rpt_name'=>'', 'rpt_dir'=>'cache', 'rpt_rewrite'=>TRUE));
      $fnc = $rpt->GetJavaFunction();
      
      $html  = ThemeAbrirTabla('REPORTES AUTORIZACION DE CREDITOS');
      $path = GetThemePath();
      $html .= "<form name=\"formReportesCreditosFecha\" id=\"formReportesCreditosFecha\" action=\"".$action['reporte']."\" method=\"post\">\n";
      $html .= "<table class=\"modulo_table_list\" width=\"70%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"modulo_table_list_title\" colspan=\"6\">AUTORIZACIONES DE CREDITO GENERADAS POR RANGO DE FECHA\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"normal_10AN\">FECHA INICIO:\n";
      $html .= "    </td>\n";
      $cut = new ClaseUtil();
      $html .= "    <td class=\"\">\n";
      $html .= $cut->AcceptDate("/");
      $html .= $cut->IsDate();
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"fechaInicio\" value=\"".$request['fechaInicio']."\" onkeyPress=\"return acceptDate(event)\" size=\"10%\">\n";
      $html .= "".ReturnOpenCalendario('formReportesCreditosFecha', 'fechaInicio', '/')."\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"normal_10AN\">FECHA FINAL:\n";
      $html .= "    </td>\n";
      $cut = new ClaseUtil();
      $html .= "    <td class=\"\">\n";
      $html .= $cut->AcceptDate("/");
      $html .= $cut->IsDate();
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"fechaFinal\" value=\"".$request['fechaFinal']."\" onkeyPress=\"return acceptDate(event)\" size=\"10%\">\n";
      $html .= "".ReturnOpenCalendario('formReportesCreditosFecha', 'fechaFinal', '/')."\n";
      $html .= "    </td>\n";
      $html .= "    <td>\n";
      $html .= "      <input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatos(document.formReportesCreditosFecha)\">\n";
      $html .= "    </td>\n";
      $html .= "    <td width=\"12%\">\n";
      if($request['oculto']=="fecha")
      {
        $html .= "      <center>\n";
        $html .= "    ".$mst."\n";
        $html .= "        <a href=\"javascript:".$fnc."\" class=\"label_error\">\n";
        $html .= "          <img src=\"".$path."/images/imprimir.png\" border=\"0\">IMPRIMIR\n";
        $html .= "        </a>\n";
        $html .= "      </center>\n";
      }
      $html .= "    </td>\n";      
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"6\">\n";
      $html .= "      <center><div id=\"error\" class=\"label_error\">".$request['mensaje']."</div></center>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<input type=\"hidden\" name=\"oculto\" id=\"oculto\" value=\"false\">\n";
      $html .= "<input type=\"hidden\" name=\"mensaje\" id=\"mensaje\" value=\"\">\n";
      $html .= "</form>\n";
      $html .= "<script>\n";
      $html .= "  function EvaluarDatos()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formReportesCreditosFecha;\n";
      $html .= "    if(frm.fechaInicio.value==\"\")\n"; 
      $html .= "    {\n";
      $html .= "      frm.oculto.value='false';\n";
      $html .= "      frm.mensaje.value = 'Debe ingresar la fecha de inicio';\n";
      $html .= "      frm.submit();\n";
      $html .= "    }\n";
      $html .= "    if(frm.fechaFinal.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      frm.oculto.value='false';\n";
      $html .= "      frm.mensaje.value = 'Debe ingresar la fecha final';\n";
      $html .= "      frm.submit();\n";
      $html .= "    }\n";
      $html .= "    if(!IsDate(frm.fechaInicio.value))\n";
      $html .= "    {\n";
      $html .= "      frm.oculto.value='false';\n";
      $html .= "      frm.mensaje.value = 'La fecha de inicio no posee el formato adecuado';\n";
      $html .= "      frm.submit();\n";
      $html .= "    }\n";
      $html .= "    if(!IsDate(frm.fechaFinal.value))\n";
      $html .= "    {\n";
      $html .= "      frm.oculto.value='false';\n";
      $html .= "      frm.mensaje.value = 'La fecha final no posee el formato adecuado';\n";
      $html .= "      frm.submit();\n";
      $html .= "    }\n";
      $html .= "    fi = frm.fechaInicio.value;\n";
      $html .= "    ff = frm.fechaFinal.value;\n";
      $html .= "    var fecha_i = fi.split('/');\n";
      $html .= "    var fecha_f = ff.split('/');\n";
      $html .= "    ffi = new Date(fecha_i[2]+'/'+fecha_i[1]+'/'+fecha_i[0]);\n";
      $html .= "    fff = new Date(fecha_f[2]+'/'+fecha_f[1]+'/'+fecha_f[0]);\n";
      $html .= "    if(fff < ffi)\n";
      $html .= "    {\n";
      $html .= "      frm.oculto.value='false';\n";
      $html .= "      frm.mensaje.value = 'La fecha de inicio debe ser menor o igual a la fecha final';\n";
      $html .= "      frm.submit();\n";
      $html .= "    }\n";                              
      $html .= "    frm.oculto.value='fecha';\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";

      $html .= "<form name=\"formReportesCreditosPaciente\" id=\"formReportesCreditosPaciente\" action=\"".$action['reporte']."\" method=\"post\">\n";
      $html .= "<table class=\"modulo_table_list\" width=\"70%\" align=\"center\">\n";
      $html .= "  <tr class=\"modulo_table_list_title\">\n";
      $html .= "    <td colspan=\"5\">AUTORIZACIONES DE CREDITO GENERADAS POR PACIENTE\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"normal_10AN\">No. HISTORIA:\n";
      $html .= "    </td>\n";
      $html .= "    <td>\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"noHistoria\" value=\"".$request['noHistoria']."\" size=\"15%\">\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"normal_10AN\">No. IDENTIFICACION:\n";
      $html .= "    </td>\n";
      $html .= "    <td>\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"noIdentificacion\" value=\"".$request['noIdentificacion']."\" size=\"15%\">\n";
      $html .= "    </td>\n";
      $html .= "    <td width=\"12%\" align=\"center\">\n";
      $html .= "      <input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatosP(document.formReportesCreditosPaciente)\">";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"normal_10AN\">NOMBRES:\n";
      $html .= "    </td>\n";
      $html .= "    <td>\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"nombres\" value=\"".$request['nombres']."\">\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"normal_10AN\">APELLIDOS:\n";
      $html .= "    </td>\n";
      $html .= "    <td>\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"apellidos\" value=\"".$request['apellidos']."\">\n";
      $html .= "    </td>\n";
      $html .= "    <td width=\"12%\">\n";
      if ($request['oculto']=="paciente")
      {
        $html .= "      <center>\n";
        $html .= "    ".$mst."\n";
        $html .= "        <a href=\"javascript:".$fnc."\" class=\"label_error\">\n";
        $html .= "          <img src=\"".$path."/images/imprimir.png\" border=\"0\">IMPRIMIR\n";
        $html .= "        </a>\n";
        $html .= "      </center>\n";
      }
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "<input type=\"hidden\" name=\"oculto\" id=\"oculto\" value=\"false\">\n";
      $html .= "</table>\n";      
      $html .= "</form>\n";
      $html .= "<script>\n";
      $html .= "  function EvaluarDatosP()";
      $html .= "  {\n";
      $html .= "    frm = document.formReportesCreditosPaciente;\n";
      $html .= "    frm.oculto.value='paciente';\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      
      $html .= "<form name=\"formReportesCreditosActiva\" id=\"formReportesCreditosActiva\" action=\"".$action['reporte']."\" method=\"post\">\n";
      $html .= "<table class=\"modulo_table_list\" width=\"70%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"modulo_table_list_title\">AUTORIZACIONES DE CREDITO ACTIVAS\n";
      $html .= "    </td>\n";
      $html .= "    <td>Autorizaciones de credito que tienen una factura con saldo pendiente\n";
      $html .= "    </td>\n";
      $html .= "    <td width=\"10%\">\n";
      $html .= "      <input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"aceptar\" onclick=\"EvaluarDatosAc(document.formReportesCreditosActiva)\">\n";
      $html .= "    </td>\n";
      $html .= "    <td width=\"12%\">\n";
      if($request['oculto']=="activa")
      {
        $html .= "      <center>\n";
        $html .= "    ".$mst."\n";
        $html .= "        <a href=\"javascript:".$fnc."\" class=\"label_error\">\n";
        $html .= "          <img src=\"".$path."/images/imprimir.png\" border=\"0\">IMPRIMIR\n";
        $html .= "        </a>\n";
        $html .= "      </center>\n";
      }
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<input type=\"hidden\" name=\"oculto\" id=\"oculto\" value=\"\">\n";        
      $html .= "</form>\n"; 
      $html .= "<script>\n";
      $html .= "  function EvaluarDatosAc()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formReportesCreditosActiva;\n";
      $html .= "    frm.oculto.value='activa';\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      //$html .= "<br>\n";
      $html .= "<form name=\"formReportesCreditosGarantia\" id=\"formReportesCreditosGarantia\" action=\"".$action['reporte']."\" method=\"post\">\n";
      $html .= "<table class=\"modulo_table_list\" width=\"70%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"modulo_table_list_title\" width=\"34%\">AUTORIZACIONES LETRAS Y PAGARES\n";
      $html .= "    </td>\n";
      $html .= "    <td>\n";
      if($request['garantiaP']=="1"){
        $html .= "      <input type=\"checkbox\" name=\"garantiaP\" value=\"1\" onclick=\"\" checked>Pagare</input>\n";
        $html .= "      <input type=\"hidden\" name=\"garP\" id=\"garP\" value=\"1\"></input>\n";
      }else
      {
        $html .= "      <input type=\"checkbox\" name=\"garantiaP\" value=\"0\" onclick=\"\">Pagare</input>\n";
        $html .= "      <input type=\"hidden\" name=\"garP\" id=\"garP\" value=\"0\"></input>\n";
      }  
      if($request['garantiaLC']=="1") 
      {
        $html .= "      <input type=\"checkbox\" name=\"garantiaLC\" value=\"1\" onclick=\"\" checked>Letra de Cambio</input>\n";
        $html .= "      <input type=\"hidden\" name=\"garLC\" id=\"garLC\" value=\"1\"></input>\n";  
      }else 
      {
        $html .= "      <input type=\"checkbox\" name=\"garantiaLC\" value=\"0\" onclick=\"\">Letra de Cambio</input>\n";
        $html .= "      <input type=\"hidden\" name=\"garLC\" id=\"garLC\" value=\"0\"></input>\n";
      }  
      $html .= "    </td>\n";
      $html .= "    <td width=\"10%\">\n";
      $html .= "      <input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatosG(document.formReportesCreditosGarantia)\">\n";
      $html .= "    </td>\n";
      $html .= "    <td width=\"12%\">\n";
      if($request['oculto']=="garantia")
      {
        $html .= "      <center>\n";        
        $html .= "      ".$mst."\n";
        $html .= "        <a href=\"javascript:".$fnc."\" class=\"label_error\">\n";
        $html .= "          <img src=\"".$path."/images/imprimir.png\" border=\"0\">IMPRIMIR\n";
        $html .= "        </a>\n";
        $html .= "      </center>\n";
      }
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"4\">\n";
      $html .= "      <center>\n";
      $html .= "        <div id=\"error\" class=\"label_error\">".$request['mensajeG']."</div>\n";
      $html .= "      </center>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<input type=\"hidden\" name=\"oculto\" id=\"oculto\" value=\"false\">\n";
      $html .= "<input type=\"hidden\" name=\"mensajeG\" id=\"mensajeG\" value=\"\">\n";
      $html .= "</form>\n";
      $html .= "<script>\n";
      $html .= "  function EvaluarDatosG()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formReportesCreditosGarantia;\n";
      $html .= "    if(!frm.garantiaP.checked && !frm.garantiaLC.checked)\n";
      $html .= "    {\n";
      $html .= "      frm.oculto.value = 'false';\n";
      $html .= "      frm.garantiaP.value = '0';\n";
      $html .= "      frm.garantiaLC.value = '0';\n";
      $html .= "      frm.garP.value = '0';\n";
      $html .= "      frm.garLC.value = '0';\n";
      $html .= "      frm.mensajeG.value = 'Debe seleccionar el tipo de garantia';\n";
      $html .= "      frm.submit();\n";
      $html .= "    }\n";
      $html .= "    if(frm.garantiaP.checked && !frm.garantiaLC.checked)\n";
      $html .= "    {\n";
      $html .= "      frm.garantiaP.value = '1';\n";
      $html .= "      frm.garantiaLC.value = '0';\n";
      $html .= "      frm.garP.value = '1';\n";
      $html .= "      frm.garLC.value = '0';\n";
      $html .= "      frm.oculto.value = 'garantia';\n";
      $html .= "      frm.submit();\n";
      $html .= "    }\n";
      $html .= "    if(!frm.garantiaP.checked && frm.garantiaLC.checked)\n";
      $html .= "    {\n";
      $html .= "      frm.garantiaP.value = '0';\n";
      $html .= "      frm.garantiaLC.value = '1';\n";
      $html .= "      frm.garP.value = '0';\n";
      $html .= "      frm.garLC.value = '1';\n";
      $html .= "      frm.oculto.value = 'garantia';\n";
      $html .= "      frm.submit();\n";
      $html .= "    }\n";
      $html .= "    if(frm.garantiaP.checked && frm.garantiaLC.checked)\n";
      $html .= "    {\n";
      $html .= "      frm.garantiaP.value = '1';\n";
      $html .= "      frm.garantiaLC.value = '1';\n";
      $html .= "      frm.garP.value = '1';\n";
      $html .= "      frm.garLC.value = '1';\n";
      $html .= "      frm.oculto.value = 'garantia';\n";
      $html .= "      frm.submit();\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<form name=\"formReportesCreditosAutori\" id=\"formReportesCreditosAutori\" action=\"".$action['reporte']."\" method=\"post\">\n";
      $html .= "  <table class=\"modulo_table_list\" width=\"70%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_list_title\" colspan=\"4\">AUTORIZACIONES DE CREDITO GENERADAS POR SU CODIGO\n";      
      $html .= "      </td>\n";      
      $html .= "    </tr>\n";      
      $html .= "    <tr>\n";
      $html .= "      <td class=\"normal_10AN\" width=\"18%\">COD. AUTORIZACION:\n";
      $html .= "      </td>\n";
      $html .= "      <td align=\"left\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"codAutorizacion\" value=\"".$request['codAutorizacion']."\"></input>\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"10%\">\n";
      $html .= "        <input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatosA(document.formReportesCreditosAutori)\"></input>\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"12%\">\n";
      if($request['oculto']=="codAutori")
      {
        $html .= "        <center>\n";
        $html .= "        ".$mst."\n";
        $html .= "          <a href=\"javascript:".$fnc."\" class=\"label_error\">\n";
        $html .= "            <img src=\"".$path."/images/imprimir.png\" border=\"0\">IMPRIMIR\n";
        $html .= "          </a>\n";
        $html .= "        </center>\n"; 
      }
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"4\">\n";
      $html .= "        <center>\n";
      $html .= "          <div id=\"error\" class=\"label_error\">".$request['mensajeA']."\n";
      $html .= "          </div>\n";
      $html .= "        </center>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "<input type=\"hidden\" name=\"oculto\" id=\"oculto\" value=\"false\">\n";
      $html .= "<input type=\"hidden\" name=\"mensajeA\" id=\"mensajeA\" value=\"\">\n";
      $html .= "</form>\n";
      $cut = new ClaseUtil();
      $html .= $cut->IsNumeric();
      $html .= "<script>\n";
      $html .= "  function EvaluarDatosA()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formReportesCreditosAutori;\n";
      $html .= "    if(frm.codAutorizacion.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      frm.oculto.value='false';\n";
      $html .= "      frm.mensajeA.value='Debe ingresar el codigo de la autorizacion';\n";
      $html .= "      frm.submit();\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.codAutorizacion.value))\n";
      $html .= "    {\n";
      $html .= "      frm.oculto.value='false';\n";
      $html .= "      frm.mensajeA.value='El codigo debe ser numerico';\n";
      $html .= "      frm.submit();\n";
      $html .= "    }\n";
      $html .= "    frm.oculto.value='codAutori';\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<br>\n";
      $html .= "<form name=\"formVolver\" id=\"formVolver\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "</form>\n";            
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
  }
?>